<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaravaneVillageTouche;
use App\Models\FicheAnimationVente;
use App\Models\FicheCaravane;
use App\Models\FicheDemonstrationCulinaire;
use App\Models\FicheEmissionsRadio;
use App\Models\FicheLeaderOpinion;
use App\Models\FicheVentesDistributeur;
use App\Models\FoyerType;
use App\Models\RadioParticipant;
use App\Models\Region;
use App\Models\SyncLog;
use App\Models\VenteDistributeurItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncController extends Controller
{
    /**
     * Pull referential and stats data to mobile app
     */
    public function pull(Request $request): JsonResponse
    {
        $regions = Region::with(['departements.communes'])->get();
        $foyerTypes = FoyerType::where('is_active', true)->orderBy('ordre')->get();

        // Récupération des distributeurs déjà enregistrés pour l'autocomplétion
        $distributeurs = FicheVentesDistributeur::select('distributeur_nom', 'distributeur_telephone', 'distributeur_statut')
            ->distinct()
            ->orderBy('distributeur_nom')
            ->get();

        // Calcul des indicateurs macro du projet SENRM
        $totalFaDistributeurs = (int) FicheVentesDistributeur::sum('total_fa_vendus');
        $totalFaAnimations = (int) FicheAnimationVente::sum('nb_fa_vendus');
        $totalFaCaravanes = (int) FicheCaravane::sum('nb_fa_vendus');
        $grandTotalFa = $totalFaDistributeurs + $totalFaAnimations + $totalFaCaravanes;
        $cible100k = 100000;
        $tauxAtteinte = round(($grandTotalFa / $cible100k) * 100, 2);

        $response = [
            'status' => 'success',
            'timestamp' => now()->toIso8601String(),
            'referentiels' => [
                'regions' => $regions,
                'foyer_types' => $foyerTypes,
                'distributeurs_repertoire' => $distributeurs,
            ],
            'indicateurs_macro' => [
                'cible_foyers' => $cible100k,
                'total_foyers_diffuses' => $grandTotalFa,
                'taux_realisation_pct' => $tauxAtteinte,
                'total_animations' => FicheAnimationVente::count(),
                'total_demonstrations' => FicheDemonstrationCulinaire::count(),
                'total_caravanes' => FicheCaravane::count(),
                'total_emissions' => FicheEmissionsRadio::count(),
                'total_leaders' => FicheLeaderOpinion::count(),
                'total_ventes_enregistrees' => FicheVentesDistributeur::count(),
            ],
        ];

        if ($request->user()) {
            $response['user'] = [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => $request->user()->role,
                'region' => $request->user()->region,
                'phone' => $request->user()->phone,
            ];
        }

        return response()->json($response);
    }

    /**
     * Valide et garantit que l'agent de collecte enregistre exclusivement dans sa région affectée.
     */
    protected function validateAndGetRegion($user, array $item): string
    {
        if ($user && $user->role !== 'admin' && !empty($user->region)) {
            $assignedRegion = trim($user->region);
            if (!empty($item['region']) && strcasecmp(trim($item['region']), $assignedRegion) !== 0) {
                throw new \DomainException("Accès refusé : Vous êtes affecté(e) à la région {$assignedRegion}. Vous ne pouvez pas enregistrer de données pour la région {$item['region']}.");
            }
            return $assignedRegion;
        }

        return !empty($item['region']) ? $item['region'] : ($user ? ($user->region ?? '') : '');
    }

    /**
     * Push offline records from mobile app into central database
     */
    public function push(Request $request): JsonResponse
    {
        $user = $request->user();
        $userId = $user ? $user->id : null;
        $userName = $user ? $user->name : '';
        $payload = $request->all();
        $processedUuids = [];
        $totalProcessed = 0;

        DB::beginTransaction();
        try {
            // 1. Fiches Animation-Ventes
            if (!empty($payload['animations']) && is_array($payload['animations'])) {
                foreach ($payload['animations'] as $item) {
                    $uuid = $item['uuid'] ?? null;
                    if (!$uuid) continue;

                    $effectiveRegion = $this->validateAndGetRegion($user, $item);

                    FicheAnimationVente::updateOrCreate(
                        ['uuid' => $uuid],
                        [
                            'user_id' => $userId,
                            'region' => $effectiveRegion,
                            'departement' => $item['departement'] ?? '',
                            'commune' => $item['commune'] ?? '',
                            'village_quartier' => $item['village_quartier'] ?? '',
                            'lieu_animation' => $item['lieu_animation'] ?? '',
                            'date_animation' => $item['date_animation'] ?? now()->toDateString(),
                            'distributeur_nom' => $item['distributeur_nom'] ?? '',
                            'distributeur_adresse' => $item['distributeur_adresse'] ?? null,
                            'distributeur_contact' => $item['distributeur_contact'] ?? null,
                            'distributeur_signature' => $item['distributeur_signature'] ?? null,
                            'materiel_utilise' => $item['materiel_utilise'] ?? null,
                            'animateur_nom' => !empty($item['animateur_nom']) ? $item['animateur_nom'] : $userName,
                            'animateur_contact' => $item['animateur_contact'] ?? null,
                            'nb_fa_vendus' => (int) ($item['nb_fa_vendus'] ?? 0),
                            'latitude' => isset($item['latitude']) ? (float)$item['latitude'] : null,
                            'longitude' => isset($item['longitude']) ? (float)$item['longitude'] : null,
                            'photo_url' => $item['photo_url'] ?? null,
                            'sync_status' => 'synced',
                            'client_created_at' => $item['client_created_at'] ?? now(),
                        ]
                    );
                    $processedUuids[] = $uuid;
                    $totalProcessed++;
                }
            }

            // 2. Fiches Démonstrations Culinaires
            if (!empty($payload['demonstrations']) && is_array($payload['demonstrations'])) {
                foreach ($payload['demonstrations'] as $item) {
                    $uuid = $item['uuid'] ?? null;
                    if (!$uuid) continue;

                    $effectiveRegion = $this->validateAndGetRegion($user, $item);

                    FicheDemonstrationCulinaire::updateOrCreate(
                        ['uuid' => $uuid],
                        [
                            'user_id' => $userId,
                            'region' => $effectiveRegion,
                            'departement' => $item['departement'] ?? '',
                            'commune' => $item['commune'] ?? '',
                            'village_quartier' => $item['village_quartier'] ?? '',
                            'date_demonstration' => $item['date_demonstration'] ?? now()->toDateString(),
                            'gpf_nom' => $item['gpf_nom'] ?? '',
                            'activites_principales_gpf' => $item['activites_principales_gpf'] ?? null,
                            'nb_presents' => (int) ($item['nb_presents'] ?? 0),
                            'nb_hommes' => (int) ($item['nb_hommes'] ?? 0),
                            'nb_femmes' => (int) ($item['nb_femmes'] ?? 0),
                            'presidente_nom' => $item['presidente_nom'] ?? '',
                            'presidente_contact' => $item['presidente_contact'] ?? null,
                            'presidente_age' => isset($item['presidente_age']) ? (int)$item['presidente_age'] : null,
                            'consent_photo_video' => !empty($item['consent_photo_video']),
                            'consent_interview' => !empty($item['consent_interview']),
                            'responsable_nom' => !empty($item['responsable_nom']) ? $item['responsable_nom'] : $userName,
                            'responsable_signature' => $item['responsable_signature'] ?? null,
                            'photo_url' => $item['photo_url'] ?? null,
                            'latitude' => isset($item['latitude']) ? (float)$item['latitude'] : null,
                            'longitude' => isset($item['longitude']) ? (float)$item['longitude'] : null,
                            'sync_status' => 'synced',
                            'client_created_at' => $item['client_created_at'] ?? now(),
                        ]
                    );
                    $processedUuids[] = $uuid;
                    $totalProcessed++;
                }
            }

            // 3. Fiches Caravanes
            if (!empty($payload['caravanes']) && is_array($payload['caravanes'])) {
                foreach ($payload['caravanes'] as $item) {
                    $uuid = $item['uuid'] ?? null;
                    if (!$uuid) continue;

                    $effectiveRegion = $this->validateAndGetRegion($user, $item);

                    $caravane = FicheCaravane::updateOrCreate(
                        ['uuid' => $uuid],
                        [
                            'user_id' => $userId,
                            'region' => $effectiveRegion,
                            'departement' => $item['departement'] ?? '',
                            'date_caravane' => $item['date_caravane'] ?? now()->toDateString(),
                            'heure_debut' => $item['heure_debut'] ?? null,
                            'heure_fin' => $item['heure_fin'] ?? null,
                            'distributeurs_beneficiaires' => $item['distributeurs_beneficiaires'] ?? null,
                            'distributeurs_contacts' => $item['distributeurs_contacts'] ?? null,
                            'itineraire' => $item['itineraire'] ?? null,
                            'moyens_logistiques' => $item['moyens_logistiques'] ?? [],
                            'nb_villages_sillonnes' => (int) ($item['nb_villages_sillonnes'] ?? 0),
                            'supports_affiches' => (int) ($item['supports_affiches'] ?? 0),
                            'supports_depliants' => (int) ($item['supports_depliants'] ?? 0),
                            'supports_autres_libelle' => $item['supports_autres_libelle'] ?? null,
                            'supports_autres_nb' => (int) ($item['supports_autres_nb'] ?? 0),
                            'nb_fa_vendus' => (int) ($item['nb_fa_vendus'] ?? 0),
                            'responsable_nom' => !empty($item['responsable_nom']) ? $item['responsable_nom'] : $userName,
                            'responsable_signature' => $item['responsable_signature'] ?? null,
                            'photo_url' => $item['photo_url'] ?? null,
                            'latitude' => isset($item['latitude']) ? (float)$item['latitude'] : null,
                            'longitude' => isset($item['longitude']) ? (float)$item['longitude'] : null,
                            'sync_status' => 'synced',
                            'client_created_at' => $item['client_created_at'] ?? now(),
                        ]
                    );

                    // Mise à jour des villages touchés
                    if (isset($item['villages_touches']) && is_array($item['villages_touches'])) {
                        $caravane->villagesTouches()->delete();
                        foreach ($item['villages_touches'] as $idx => $v) {
                            $nom = is_array($v) ? ($v['nom_village_quartier'] ?? '') : (string)$v;
                            if (!empty($nom)) {
                                CaravaneVillageTouche::create([
                                    'fiche_caravane_id' => $caravane->id,
                                    'numero_ordre' => $idx + 1,
                                    'commune_ou_zone' => is_array($v) ? ($v['commune_ou_zone'] ?? null) : null,
                                    'nom_village_quartier' => $nom,
                                ]);
                            }
                        }
                    }

                    $processedUuids[] = $uuid;
                    $totalProcessed++;
                }
            }

            // 4. Fiches Émissions Radios
            if (!empty($payload['emissions']) && is_array($payload['emissions'])) {
                foreach ($payload['emissions'] as $item) {
                    $uuid = $item['uuid'] ?? null;
                    if (!$uuid) continue;

                    $effectiveRegion = $this->validateAndGetRegion($user, $item);

                    $emission = FicheEmissionsRadio::updateOrCreate(
                        ['uuid' => $uuid],
                        [
                            'user_id' => $userId,
                            'region' => $effectiveRegion,
                            'commune' => $item['commune'] ?? '',
                            'radio_nom' => $item['radio_nom'] ?? '',
                            'radio_frequence' => $item['radio_frequence'] ?? null,
                            'date_emission' => $item['date_emission'] ?? now()->toDateString(),
                            'heure_debut' => $item['heure_debut'] ?? null,
                            'heure_fin' => $item['heure_fin'] ?? null,
                            'theme' => $item['theme'] ?? '',
                            'format' => $item['format'] ?? 'direct',
                            'lieu' => $item['lieu'] ?? 'studio',
                            'animateur_nom' => !empty($item['animateur_nom']) ? $item['animateur_nom'] : $userName,
                            'responsable_radio_nom' => $item['responsable_radio_nom'] ?? null,
                            'responsable_radio_signature' => $item['responsable_radio_signature'] ?? null,
                            'audio_or_photo_url' => $item['audio_or_photo_url'] ?? null,
                            'latitude' => isset($item['latitude']) ? (float)$item['latitude'] : null,
                            'longitude' => isset($item['longitude']) ? (float)$item['longitude'] : null,
                            'sync_status' => 'synced',
                            'client_created_at' => $item['client_created_at'] ?? now(),
                        ]
                    );

                    if (isset($item['participants']) && is_array($item['participants'])) {
                        $emission->participants()->delete();
                        foreach ($item['participants'] as $idx => $p) {
                            if (!empty($p['prenom_nom'])) {
                                RadioParticipant::create([
                                    'fiche_emissions_radio_id' => $emission->id,
                                    'numero_ordre' => $idx + 1,
                                    'prenom_nom' => $p['prenom_nom'],
                                    'sexe' => $p['sexe'] ?? 'M',
                                    'profession' => $p['profession'] ?? null,
                                ]);
                            }
                        }
                    }

                    $processedUuids[] = $uuid;
                    $totalProcessed++;
                }
            }

            // 5. Fiches Leaders d'Opinion
            if (!empty($payload['leaders']) && is_array($payload['leaders'])) {
                foreach ($payload['leaders'] as $item) {
                    $uuid = $item['uuid'] ?? null;
                    if (!$uuid) continue;

                    $effectiveRegion = $this->validateAndGetRegion($user, $item);

                    FicheLeaderOpinion::updateOrCreate(
                        ['uuid' => $uuid],
                        [
                            'user_id' => $userId,
                            'region' => $effectiveRegion,
                            'departement' => $item['departement'] ?? '',
                            'commune' => $item['commune'] ?? '',
                            'village_quartier' => $item['village_quartier'] ?? '',
                            'lieu_habitation' => $item['lieu_habitation'] ?? null,
                            'prenom_nom' => $item['prenom_nom'] ?? '',
                            'sexe' => $item['sexe'] ?? 'M',
                            'titre_profession' => $item['titre_profession'] ?? null,
                            'telephone' => $item['telephone'] ?? '',
                            'email' => $item['email'] ?? null,
                            'membre_entites' => $item['membre_entites'] ?? null,
                            'fonctions_entites' => $item['fonctions_entites'] ?? null,
                            'localites_influence' => $item['localites_influence'] ?? null,
                            'responsable_nom' => !empty($item['responsable_nom']) ? $item['responsable_nom'] : $userName,
                            'responsable_signature' => $item['responsable_signature'] ?? null,
                            'photo_url' => $item['photo_url'] ?? null,
                            'latitude' => isset($item['latitude']) ? (float)$item['latitude'] : null,
                            'longitude' => isset($item['longitude']) ? (float)$item['longitude'] : null,
                            'sync_status' => 'synced',
                            'client_created_at' => $item['client_created_at'] ?? now(),
                        ]
                    );

                    $processedUuids[] = $uuid;
                    $totalProcessed++;
                }
            }

            // 6. Fiches Ventes Distributeurs (avec items par type de FA)
            if (!empty($payload['ventes_distributeurs']) && is_array($payload['ventes_distributeurs'])) {
                foreach ($payload['ventes_distributeurs'] as $item) {
                    $uuid = $item['uuid'] ?? null;
                    if (!$uuid) continue;

                    $effectiveRegion = $this->validateAndGetRegion($user, $item);
                    $totalFa = (int) ($item['total_fa_vendus'] ?? 0);

                    // Si le total n'est pas fourni, le calculer à partir des items
                    if ($totalFa === 0 && !empty($item['items']) && is_array($item['items'])) {
                        foreach ($item['items'] as $q) {
                            $totalFa += (int)($q['quantite'] ?? 0);
                        }
                    }

                    $vente = FicheVentesDistributeur::updateOrCreate(
                        ['uuid' => $uuid],
                        [
                            'user_id' => $userId,
                            'distributeur_nom' => $item['distributeur_nom'] ?? '',
                            'distributeur_statut' => $item['distributeur_statut'] ?? null,
                            'distributeur_telephone' => $item['distributeur_telephone'] ?? null,
                            'region' => $effectiveRegion,
                            'departement' => $item['departement'] ?? '',
                            'commune' => $item['commune'] ?? '',
                            'village_quartier' => $item['village_quartier'] ?? '',
                            'adresse_client' => $item['adresse_client'] ?? null,
                            'date_vente' => $item['date_vente'] ?? now()->toDateString(),
                            'total_fa_vendus' => $totalFa,
                            'distributeur_signature' => $item['distributeur_signature'] ?? null,
                            'photo_url' => $item['photo_url'] ?? null,
                            'latitude' => isset($item['latitude']) ? (float)$item['latitude'] : null,
                            'longitude' => isset($item['longitude']) ? (float)$item['longitude'] : null,
                            'sync_status' => 'synced',
                            'client_created_at' => $item['client_created_at'] ?? now(),
                        ]
                    );

                    if (isset($item['items']) && is_array($item['items'])) {
                        $vente->items()->delete();
                        foreach ($item['items'] as $vItem) {
                            $quantite = (int) ($vItem['quantite'] ?? 0);
                            $foyerTypeId = $vItem['foyer_type_id'] ?? null;
                            if ($quantite > 0 && $foyerTypeId) {
                                VenteDistributeurItem::create([
                                    'fiche_ventes_distributeur_id' => $vente->id,
                                    'foyer_type_id' => $foyerTypeId,
                                    'quantite' => $quantite,
                                ]);
                            }
                        }
                    }

                    $processedUuids[] = $uuid;
                    $totalProcessed++;
                }
            }

            // Journalisation de la synchronisation
            SyncLog::create([
                'user_id' => $userId,
                'action' => 'push',
                'records_count' => $totalProcessed,
                'is_success' => true,
                'payload_summary' => [
                    'animations' => count($payload['animations'] ?? []),
                    'demonstrations' => count($payload['demonstrations'] ?? []),
                    'caravanes' => count($payload['caravanes'] ?? []),
                    'emissions' => count($payload['emissions'] ?? []),
                    'leaders' => count($payload['leaders'] ?? []),
                    'ventes_distributeurs' => count($payload['ventes_distributeurs'] ?? []),
                ],
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Synchronisation réussie. {$totalProcessed} enregistrement(s) traité(s).",
                'processed_uuids' => $processedUuids,
                'synced_at' => now()->toIso8601String(),
            ]);

        } catch (\DomainException $de) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $de->getMessage(),
            ], 403);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur synchronisation mobile : ' . $e->getMessage());

            SyncLog::create([
                'user_id' => $userId,
                'action' => 'push',
                'records_count' => $totalProcessed,
                'is_success' => false,
                'error_message' => $e->getMessage(),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la synchronisation : ' . $e->getMessage(),
            ], 500);
        }
    }
}
