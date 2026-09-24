<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SenrmPlatformTest extends TestCase
{
    protected function getAdminUser(): User
    {
        return User::where('email', 'admin@senrm-enda.org')->first() ?? User::factory()->create([
            'name' => 'Administrateur SENRM',
            'email' => 'admin@senrm-enda.org',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    protected function getAnimateurUser(): User
    {
        return User::where('email', 'animateur.tamba@senrm.sn')->first() ?? User::create([
            'name' => 'Moussa Tamba',
            'email' => 'animateur.tamba@senrm.sn',
            'password' => Hash::make('password123'),
            'role' => 'animateur',
            'region' => 'Tambacounda',
            'telephone' => '771234567',
            'is_active' => true,
        ]);
    }

    /**
     * Test guest users are redirected to login for web routes.
     */
    public function test_guest_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/fiches/ventes-distributeurs')->assertRedirect('/login');
        $this->get('/users')->assertRedirect('/login');
    }

    /**
     * Test web login page renders.
     */
    public function test_login_page_renders(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Plateforme SENRM');
        $response->assertSee('Espace Administration Centrale');
    }

    /**
     * Test non-admin user cannot log into backend web interface.
     */
    public function test_non_admin_cannot_login_web(): void
    {
        $animateur = $this->getAnimateurUser();

        $response = $this->post('/login', [
            'email' => $animateur->email,
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test admin can access dashboard and view SENRM metrics.
     */
    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/');
        $response->assertStatus(200);
        $response->assertSee('SENRM');
        $response->assertSee('100 000');
    }

    /**
     * Test dashboard with period filters.
     */
    public function test_dashboard_period_filters(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/?date_debut=2026-09-01&date_fin=2026-09-30');
        $response->assertStatus(200);
        $response->assertSee('Période / Région active');

        $responsePreset = $this->actingAs($admin)->get('/?periode=ce_mois');
        $responsePreset->assertStatus(200);
        $responsePreset->assertSee('Période / Région active');
    }

    /**
     * Test all Fiches web pages render for admin.
     */
    public function test_fiches_pages_render_for_admin(): void
    {
        $admin = $this->getAdminUser();

        $routes = [
            '/fiches/ventes-distributeurs',
            '/fiches/animations',
            '/fiches/demonstrations',
            '/fiches/caravanes',
            '/fiches/emissions',
            '/fiches/leaders',
            '/exports',
            '/users',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }
    }

    /**
     * Test Admin can create a mobile user with mandatory single region.
     */
    public function test_admin_can_create_mobile_user_with_region(): void
    {
        $admin = $this->getAdminUser();
        $email = 'agent.fatick.' . Str::random(5) . '@senrm.sn';

        $response = $this->actingAs($admin)->post('/users', [
            'name' => 'Agent Fatick Test',
            'email' => $email,
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
            'role' => 'animateur',
            'region' => 'Fatick',
            'telephone' => '778889900',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'role' => 'animateur',
            'region' => 'Fatick',
        ]);
    }

    /**
     * Test API Authentication returns user profile and region.
     */
    public function test_api_login(): void
    {
        $animateur = $this->getAnimateurUser();

        $response = $this->postJson('/api/login', [
            'email' => $animateur->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token',
                'user' => ['id', 'name', 'email', 'role', 'region'],
            ]);
    }

    /**
     * Test API Sync Pull with Sanctum token.
     */
    public function test_api_sync_pull(): void
    {
        $user = $this->getAnimateurUser();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/sync/pull');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'referentiels' => [
                    'regions',
                    'foyer_types',
                    'distributeurs_repertoire',
                ],
                'indicateurs_macro' => [
                    'cible_foyers',
                    'total_foyers_diffuses',
                    'taux_realisation_pct',
                ],
            ]);
    }

    /**
     * Test mobile user CANNOT push records outside their assigned region.
     */
    public function test_mobile_user_cannot_push_outside_assigned_region(): void
    {
        $user = $this->getAnimateurUser(); // Assigned to Tambacounda
        $token = $user->createToken('test-token')->plainTextToken;

        $uuidVente = (string) Str::uuid();

        // Attempt pushing a record in Kaolack (mismatched region)
        $payload = [
            'ventes_distributeurs' => [
                [
                    'uuid' => $uuidVente,
                    'region' => 'Kaolack', // Mismatch! User is Tambacounda
                    'departement' => 'Kaolack',
                    'commune' => 'Kaolack',
                    'distributeur_nom' => 'Distributeur Fraude',
                    'distributeur_telephone' => '770001122',
                    'total_fa_vendus' => 10,
                ]
            ]
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/sync/push', $payload);

        $response->assertStatus(403)
            ->assertJson([
                'status' => 'error',
            ]);

        $this->assertDatabaseMissing('fiche_ventes_distributeurs', ['uuid' => $uuidVente]);
    }

    /**
     * Test mobile user can push in assigned region and user_id is saved.
     */
    public function test_mobile_user_can_push_in_assigned_region_with_attribution(): void
    {
        $user = $this->getAnimateurUser(); // Assigned to Tambacounda
        $token = $user->createToken('test-token')->plainTextToken;

        $uuidVente = (string) Str::uuid();
        $uuidAnim = (string) Str::uuid();

        $payload = [
            'ventes_distributeurs' => [
                [
                    'uuid' => $uuidVente,
                    'region' => 'Tambacounda',
                    'departement' => 'Tambacounda',
                    'commune' => 'Tambacounda',
                    'distributeur_nom' => 'Distributeur Tamba Légal',
                    'distributeur_telephone' => '770001122',
                    'distributeur_statut' => 'GIE',
                    'total_fa_vendus' => 15,
                    'items' => [
                        [
                            'foyer_type_code' => 'JBR_4KG',
                            'nom_modele' => 'Jambar JEEG 4kg',
                            'quantite' => 15,
                        ]
                    ]
                ]
            ],
            'animations' => [
                [
                    'uuid' => $uuidAnim,
                    'date_animation' => '2026-09-24',
                    'region' => 'Tambacounda',
                    'departement' => 'Tambacounda',
                    'commune' => 'Tambacounda',
                    'village_quartier' => 'Village Tamba Centre',
                    'lieu_animation' => 'Place du marché',
                    'distributeur_nom' => 'Moussa Tamba Diouf',
                    'animateur_nom' => 'Moussa Tamba',
                    'nb_fa_vendus' => 8,
                ]
            ]
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/sync/push', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);

        // Verify records are in database with user_id set to the collector
        $this->assertDatabaseHas('fiche_ventes_distributeurs', [
            'uuid' => $uuidVente,
            'user_id' => $user->id,
            'region' => 'Tambacounda',
        ]);

        $this->assertDatabaseHas('fiche_animation_ventes', [
            'uuid' => $uuidAnim,
            'user_id' => $user->id,
            'region' => 'Tambacounda',
        ]);
    }

    /**
     * Test fiches listing with date and period filters.
     */
    public function test_fiches_period_filters(): void
    {
        $admin = $this->getAdminUser();

        // Test with explicit date range
        $response = $this->actingAs($admin)->get('/fiches/ventes-distributeurs?date_debut=2026-09-01&date_fin=2026-09-30');
        $response->assertStatus(200);
        $response->assertSee('Filtre période actif');

        // Test with quick period shortcut
        $responsePreset = $this->actingAs($admin)->get('/fiches/animations?periode=ce_mois');
        $responsePreset->assertStatus(200);
        $responsePreset->assertSee('Filtre période actif');
    }

    /**
     * Test export download with period filter.
     */
    public function test_export_download_with_period(): void
    {
        $admin = $this->getAdminUser();

        ob_start();
        $response = $this->actingAs($admin)->get('/exports/download/ventes-distributeurs?date_debut=2026-09-01&date_fin=2026-09-30&region=Tambacounda');
        ob_end_clean();

        $response->assertStatus(200);
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class, $response->baseResponse);
        
        // Prevent StreamedResponse callback from dumping raw binary ZIP content to stdout during PHPUnit termination
        $response->baseResponse->setCallback(function () {});
    }

    /**
     * Test exports index view renders with period controls.
     */
    public function test_exports_index_renders_with_period_controls(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/exports');
        $response->assertStatus(200);
        $response->assertSee('filterPeriode');
        $response->assertSee('filterDateDebut');
        $response->assertSee('filterDateFin');
    }
}
