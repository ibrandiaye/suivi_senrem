<?php

namespace Database\Seeders;

use App\Models\CaravaneVillageTouche;
use App\Models\FicheAnimationVente;
use App\Models\FicheCaravane;
use App\Models\FicheDemonstrationCulinaire;
use App\Models\FicheEmissionsRadio;
use App\Models\FicheLeaderOpinion;
use App\Models\FicheVentesDistributeur;
use App\Models\FoyerType;
use App\Models\RadioParticipant;
use App\Models\User;
use App\Models\VenteDistributeurItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $foyers = FoyerType::all();

        // 1. Fiche Animation-Vente
        FicheAnimationVente::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'region' => 'Kaolack',
            'departement' => 'Nioro du Rip',
            'commune' => 'Medina Sabakh',
            'village_quartier' => 'Village Centre',
            'lieu_animation' => 'Place du marché hebdomadaire',
            'date_animation' => '2026-08-15',
            'distributeur_nom' => 'Moussa Diouf',
            'distributeur_adresse' => 'Medina Sabakh Marché',
            'distributeur_contact' => '+221 77 555 12 34',
            'materiel_utilise' => 'Mégaphone, banderoles SENRM, échantillons foyers Jambar',
            'animateur_nom' => 'Fatou Fall',
            'animateur_contact' => '+221 77 666 43 21',
            'nb_fa_vendus' => 38,
            'latitude' => 13.6300,
            'longitude' => -15.5800,
            'sync_status' => 'synced',
            'client_created_at' => now()->subDays(30),
        ]);

        FicheAnimationVente::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'region' => 'Fatick',
            'departement' => 'Foundiougne',
            'commune' => 'Sokone',
            'village_quartier' => 'Quartier Diamaguène',
            'lieu_animation' => 'Foirail de Sokone',
            'date_animation' => '2026-08-20',
            'distributeur_nom' => 'Awa Ndiaye',
            'distributeur_adresse' => 'Gare routière Sokone',
            'distributeur_contact' => '+221 77 444 88 99',
            'materiel_utilise' => 'Sonorisation mobile, dépliants SENRM, 5 modèles de FA exposés',
            'animateur_nom' => 'Ousmane Ba',
            'animateur_contact' => '+221 77 333 11 22',
            'nb_fa_vendus' => 45,
            'latitude' => 13.8800,
            'longitude' => -16.3700,
            'sync_status' => 'synced',
            'client_created_at' => now()->subDays(25),
        ]);

        // 2. Fiches Démonstrations Culinaires
        FicheDemonstrationCulinaire::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'region' => 'Thiès',
            'departement' => 'Tivaouane',
            'commune' => 'Mékhé',
            'village_quartier' => 'Quartier Escale',
            'date_demonstration' => '2026-08-12',
            'gpf_nom' => 'GPF Bokk Jom de Mékhé',
            'activites_principales_gpf' => 'Transformation de céréales locales, maraîchage et tontine solidaire',
            'nb_presents' => 52,
            'nb_hommes' => 8,
            'nb_femmes' => 44,
            'presidente_nom' => 'Khady Cissé',
            'presidente_contact' => '+221 77 222 77 66',
            'presidente_age' => 48,
            'consent_photo_video' => true,
            'consent_interview' => true,
            'responsable_nom' => 'Aminata Sarr',
            'latitude' => 14.9800,
            'longitude' => -16.7000,
            'sync_status' => 'synced',
            'client_created_at' => now()->subDays(35),
        ]);

        FicheDemonstrationCulinaire::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'region' => 'Kaffrine',
            'departement' => 'Koungheul',
            'commune' => 'Koungheul Ville',
            'village_quartier' => 'Quartier Santhie',
            'date_demonstration' => '2026-08-25',
            'gpf_nom' => 'GPF Andando Defar Sunu Gox',
            'activites_principales_gpf' => 'Aviculture villageoise et séchage de légumes',
            'nb_presents' => 65,
            'nb_hommes' => 12,
            'nb_femmes' => 53,
            'presidente_nom' => 'Mariama Diallo',
            'presidente_contact' => '+221 77 888 99 00',
            'presidente_age' => 42,
            'consent_photo_video' => true,
            'consent_interview' => true,
            'responsable_nom' => 'Cheikh Ndao',
            'latitude' => 13.9800,
            'longitude' => -14.8000,
            'sync_status' => 'synced',
            'client_created_at' => now()->subDays(20),
        ]);

        // 3. Fiche Caravane
        $caravane = FicheCaravane::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'region' => 'Tambacounda',
            'departement' => 'Tambacounda',
            'date_caravane' => '2026-08-18',
            'heure_debut' => '09:00',
            'heure_fin' => '17:30',
            'distributeurs_beneficiaires' => 'GIE Jappoo Tamba, Ets Fode Camara',
            'distributeurs_contacts' => '+221 77 111 22 33 / +221 77 999 00 11',
            'itineraire' => 'Départ Gare routière -> Médina Coura -> Plateau -> Missirah -> Dialacoto',
            'moyens_logistiques' => ['Véhicule', 'Sonorisation', 'Motos'],
            'nb_villages_sillonnes' => 6,
            'supports_affiches' => 120,
            'supports_depliants' => 350,
            'supports_autres_libelle' => 'Casquettes SENRM',
            'supports_autres_nb' => 50,
            'nb_fa_vendus' => 84,
            'responsable_nom' => 'Mamadou Sow',
            'latitude' => 13.7700,
            'longitude' => -13.6700,
            'sync_status' => 'synced',
            'client_created_at' => now()->subDays(28),
        ]);

        CaravaneVillageTouche::create([
            'fiche_caravane_id' => $caravane->id,
            'numero_ordre' => 1,
            'commune_ou_zone' => 'Tambacounda',
            'nom_village_quartier' => 'Médina Coura',
        ]);
        CaravaneVillageTouche::create([
            'fiche_caravane_id' => $caravane->id,
            'numero_ordre' => 2,
            'commune_ou_zone' => 'Missirah',
            'nom_village_quartier' => 'Missirah Centre',
        ]);
        CaravaneVillageTouche::create([
            'fiche_caravane_id' => $caravane->id,
            'numero_ordre' => 3,
            'commune_ou_zone' => 'Dialacoto',
            'nom_village_quartier' => 'Dialacoto Escale',
        ]);

        // 4. Fiche Émission Radio
        $emission = FicheEmissionsRadio::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'region' => 'Kaolack',
            'commune' => 'Kaolack Ville',
            'radio_nom' => 'Radio Dunyaa Kaolack FM',
            'radio_frequence' => '102.5 MHz',
            'date_emission' => '2026-08-22',
            'heure_debut' => '10:30',
            'heure_fin' => '12:00',
            'theme' => 'Promotion des Foyers Améliorés SENRM : économie de bois, santé de la femme et préservation de la forêt',
            'format' => 'direct',
            'lieu' => 'studio',
            'animateur_nom' => 'Babacar Dieng',
            'responsable_radio_nom' => 'Seydou Kane',
            'latitude' => 14.1500,
            'longitude' => -16.0800,
            'sync_status' => 'synced',
            'client_created_at' => now()->subDays(22),
        ]);

        RadioParticipant::create([
            'fiche_emissions_radio_id' => $emission->id,
            'numero_ordre' => 1,
            'prenom_nom' => 'Ibrahima Gueye',
            'sexe' => 'M',
            'profession' => 'Expert Énergie Enda ECOPOP',
        ]);
        RadioParticipant::create([
            'fiche_emissions_radio_id' => $emission->id,
            'numero_ordre' => 2,
            'prenom_nom' => 'Astou Sene',
            'sexe' => 'F',
            'profession' => 'Présidente Réseau des GPF de Kaolack',
        ]);
        RadioParticipant::create([
            'fiche_emissions_radio_id' => $emission->id,
            'numero_ordre' => 3,
            'prenom_nom' => 'Alioune Diop',
            'sexe' => 'M',
            'profession' => 'Agent Eaux et Forêts',
        ]);

        // 5. Fiche Leaders d'Opinion
        FicheLeaderOpinion::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'region' => 'Louga',
            'departement' => 'Linguère',
            'commune' => 'Dahra',
            'village_quartier' => 'Quartier Dahra Djoloff',
            'lieu_habitation' => 'Près Grande Mosquée',
            'prenom_nom' => 'El Hadji Malick Ba',
            'sexe' => 'M',
            'titre_profession' => 'Notable & Délégué de quartier',
            'telephone' => '+221 77 654 32 10',
            'email' => 'malick.ba@gmail.com',
            'membre_entites' => 'Comité villageois de développement (CVD), Association des éleveurs',
            'fonctions_entites' => 'Président du CVD Dahra Sud',
            'localites_influence' => 'Dahra, Yang-Yang, Dodji et campements environnants',
            'responsable_nom' => 'Amadou Ka',
            'latitude' => 15.3500,
            'longitude' => -15.4800,
            'sync_status' => 'synced',
            'client_created_at' => now()->subDays(18),
        ]);

        // 6. Ventes Distributeurs
        $vente1 = FicheVentesDistributeur::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'distributeur_nom' => 'Bara Seck',
            'distributeur_statut' => 'Distributeur Agréé',
            'distributeur_telephone' => '+221 77 789 01 23',
            'region' => 'Thiès',
            'departement' => 'Mbour',
            'commune' => 'Joal-Fadiouth',
            'village_quartier' => 'Fadiouth',
            'adresse_client' => 'Quartier Ngor',
            'date_vente' => '2026-08-28',
            'total_fa_vendus' => 62,
            'latitude' => 14.1600,
            'longitude' => -16.8300,
            'sync_status' => 'synced',
            'client_created_at' => now()->subDays(15),
        ]);

        VenteDistributeurItem::create([
            'fiche_ventes_distributeur_id' => $vente1->id,
            'foyer_type_id' => $foyers[0]->id, // JEEG CHARBON 4kg
            'quantite' => 20,
        ]);
        VenteDistributeurItem::create([
            'fiche_ventes_distributeur_id' => $vente1->id,
            'foyer_type_id' => $foyers[1]->id, // JABOOT CHARBON 7kg
            'quantite' => 25,
        ]);
        VenteDistributeurItem::create([
            'fiche_ventes_distributeur_id' => $vente1->id,
            'foyer_type_id' => $foyers[4]->id, // JEEG BOIS 4kg
            'quantite' => 17,
        ]);

        $vente2 = FicheVentesDistributeur::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'distributeur_nom' => 'Mariama Badji',
            'distributeur_statut' => 'Relais Communautaire',
            'distributeur_telephone' => '+221 77 456 12 78',
            'region' => 'Ziguinchor',
            'departement' => 'Bignona',
            'commune' => 'Thionck Essyl',
            'village_quartier' => 'Quartier Djiwa',
            'adresse_client' => 'Thionck Essyl Centre',
            'date_vente' => '2026-09-05',
            'total_fa_vendus' => 48,
            'latitude' => 12.7800,
            'longitude' => -16.5100,
            'sync_status' => 'synced',
            'client_created_at' => now()->subDays(8),
        ]);

        VenteDistributeurItem::create([
            'fiche_ventes_distributeur_id' => $vente2->id,
            'foyer_type_id' => $foyers[5]->id, // JABOOT BOIS 7kg
            'quantite' => 22,
        ]);
        VenteDistributeurItem::create([
            'fiche_ventes_distributeur_id' => $vente2->id,
            'foyer_type_id' => $foyers[8]->id, // SAKKANAL MULTI
            'quantite' => 16,
        ]);
        VenteDistributeurItem::create([
            'fiche_ventes_distributeur_id' => $vente2->id,
            'foyer_type_id' => $foyers[7]->id, // TAARU
            'quantite' => 10,
        ]);
    }
}
