<?php

namespace App\Http\Controllers;

use App\Models\FicheAnimationVente;
use App\Models\FicheCaravane;
use App\Models\FicheDemonstrationCulinaire;
use App\Models\FicheEmissionsRadio;
use App\Models\FicheLeaderOpinion;
use App\Models\FicheVentesDistributeur;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ExportController extends Controller
{
    public function index()
    {
        return view('exports.index');
    }

    public function export(Request $request, string $type)
    {
        $filename = "SENRM_Export_{$type}_" . date('Y-m-d_His') . ".xlsx";
        $writer = SimpleExcelWriter::streamDownload($filename);

        switch ($type) {
            case 'ventes-distributeurs':
                $ventes = FicheVentesDistributeur::with(['items.foyerType', 'user'])->get();
                foreach ($ventes as $v) {
                    $row = [
                        'UUID' => $v->uuid,
                        'Agent Collecteur' => $v->user ? $v->user->name : 'N/A',
                        'Email Collecteur' => $v->user ? $v->user->email : '',
                        'Région Affectée' => $v->user ? $v->user->region : '',
                        'Date Vente' => $v->date_vente ? $v->date_vente->format('d/m/Y') : '',
                        'Distributeur' => $v->distributeur_nom,
                        'Statut' => $v->distributeur_statut,
                        'Téléphone' => $v->distributeur_telephone,
                        'Région' => $v->region,
                        'Département' => $v->departement,
                        'Commune' => $v->commune,
                        'Village/Quartier' => $v->village_quartier,
                        'Adresse Client' => $v->adresse_client,
                        'Total FA Vendus' => $v->total_fa_vendus,
                    ];
                    foreach ($v->items as $item) {
                        if ($item->foyerType) {
                            $row[$item->foyerType->nom] = $item->quantite;
                        }
                    }
                    $writer->addRow($row);
                }
                break;

            case 'animations':
                $animations = FicheAnimationVente::with('user')->get();
                foreach ($animations as $a) {
                    $writer->addRow([
                        'UUID' => $a->uuid,
                        'Agent Collecteur' => $a->user ? $a->user->name : $a->animateur_nom,
                        'Email Collecteur' => $a->user ? $a->user->email : '',
                        'Région Affectée' => $a->user ? $a->user->region : '',
                        'Date' => $a->date_animation ? $a->date_animation->format('d/m/Y') : '',
                        'Région' => $a->region,
                        'Département' => $a->departement,
                        'Commune' => $a->commune,
                        'Village/Quartier' => $a->village_quartier,
                        'Lieu Animation' => $a->lieu_animation,
                        'Distributeur' => $a->distributeur_nom,
                        'Contact Distributeur' => $a->distributeur_contact,
                        'Animateur' => $a->animateur_nom,
                        'Contact Animateur' => $a->animateur_contact,
                        'Matériel Utilisé' => $a->materiel_utilise,
                        'Nb FA Vendus' => $a->nb_fa_vendus,
                    ]);
                }
                break;

            case 'demonstrations':
                $demos = FicheDemonstrationCulinaire::with('user')->get();
                foreach ($demos as $d) {
                    $writer->addRow([
                        'UUID' => $d->uuid,
                        'Agent Collecteur' => $d->user ? $d->user->name : $d->responsable_nom,
                        'Email Collecteur' => $d->user ? $d->user->email : '',
                        'Région Affectée' => $d->user ? $d->user->region : '',
                        'Date' => $d->date_demonstration ? $d->date_demonstration->format('d/m/Y') : '',
                        'Région' => $d->region,
                        'Département' => $d->departement,
                        'Commune' => $d->commune,
                        'Village/Quartier' => $d->village_quartier,
                        'Nom GPF' => $d->gpf_nom,
                        'Activités GPF' => $d->activites_principales_gpf,
                        'Total Présents' => $d->nb_presents,
                        'Hommes' => $d->nb_hommes,
                        'Femmes' => $d->nb_femmes,
                        'Nom Présidente' => $d->presidente_nom,
                        'Contact Présidente' => $d->presidente_contact,
                        'Âge Présidente' => $d->presidente_age,
                        'Accord Photo/Vidéo' => $d->consent_photo_video ? 'OUI' : 'NON',
                        'Accord Interview' => $d->consent_interview ? 'OUI' : 'NON',
                        'Responsable' => $d->responsable_nom,
                    ]);
                }
                break;

            case 'caravanes':
                $caravanes = FicheCaravane::with(['villagesTouches', 'user'])->get();
                foreach ($caravanes as $c) {
                    $villagesList = $c->villagesTouches->pluck('nom_village_quartier')->join(', ');
                    $writer->addRow([
                        'UUID' => $c->uuid,
                        'Agent Collecteur' => $c->user ? $c->user->name : $c->responsable_nom,
                        'Email Collecteur' => $c->user ? $c->user->email : '',
                        'Région Affectée' => $c->user ? $c->user->region : '',
                        'Date' => $c->date_caravane ? $c->date_caravane->format('d/m/Y') : '',
                        'Heure Début' => $c->heure_debut,
                        'Heure Fin' => $c->heure_fin,
                        'Région' => $c->region,
                        'Département' => $c->departement,
                        'Itinéraire' => $c->itineraire,
                        'Distributeurs' => $c->distributeurs_beneficiaires,
                        'Moyens Logistiques' => is_array($c->moyens_logistiques) ? implode(', ', $c->moyens_logistiques) : '',
                        'Nb Villages Sillonnés' => $c->nb_villages_sillonnes,
                        'Villages Touchés' => $villagesList,
                        'Supports Affiches' => $c->supports_affiches,
                        'Supports Dépliants' => $c->supports_depliants,
                        'Autres Supports' => "{$c->supports_autres_libelle} ({$c->supports_autres_nb})",
                        'Nb FA Vendus' => $c->nb_fa_vendus,
                        'Responsable' => $c->responsable_nom,
                    ]);
                }
                break;

            case 'emissions':
                $emissions = FicheEmissionsRadio::with(['participants', 'user'])->get();
                foreach ($emissions as $e) {
                    $parts = $e->participants->map(fn($p) => "{$p->prenom_nom} ({$p->sexe}, {$p->profession})")->join('; ');
                    $writer->addRow([
                        'UUID' => $e->uuid,
                        'Agent Collecteur' => $e->user ? $e->user->name : $e->animateur_nom,
                        'Email Collecteur' => $e->user ? $e->user->email : '',
                        'Région Affectée' => $e->user ? $e->user->region : '',
                        'Date' => $e->date_emission ? $e->date_emission->format('d/m/Y') : '',
                        'Région' => $e->region,
                        'Commune' => $e->commune,
                        'Radio' => $e->radio_nom,
                        'Fréquence' => $e->radio_frequence,
                        'Horaire' => "{$e->heure_debut} - {$e->heure_fin}",
                        'Thème' => $e->theme,
                        'Format' => $e->format,
                        'Lieu' => $e->lieu,
                        'Animateur' => $e->animateur_nom,
                        'Participants Plateau' => $parts,
                        'Responsable Radio' => $e->responsable_radio_nom,
                    ]);
                }
                break;

            case 'leaders':
                $leaders = FicheLeaderOpinion::with('user')->get();
                foreach ($leaders as $l) {
                    $writer->addRow([
                        'UUID' => $l->uuid,
                        'Agent Collecteur' => $l->user ? $l->user->name : $l->responsable_nom,
                        'Email Collecteur' => $l->user ? $l->user->email : '',
                        'Région Affectée' => $l->user ? $l->user->region : '',
                        'Nom & Prénom' => $l->prenom_nom,
                        'Sexe' => $l->sexe,
                        'Titre / Profession' => $l->titre_profession,
                        'Téléphone' => $l->telephone,
                        'Email' => $l->email,
                        'Région' => $l->region,
                        'Département' => $l->departement,
                        'Commune' => $l->commune,
                        'Village/Quartier' => $l->village_quartier,
                        'Lieu Habitation' => $l->lieu_habitation,
                        'Entités Membre' => $l->membre_entites,
                        'Fonctions Entités' => $l->fonctions_entites,
                        'Zones Influence' => $l->localites_influence,
                        'Responsable Enregistrement' => $l->responsable_nom,
                    ]);
                }
                break;

            default:
                abort(404, 'Type d export introuvable.');
        }

        return $writer->toBrowser();
    }
}
