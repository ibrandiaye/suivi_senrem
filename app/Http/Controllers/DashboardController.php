<?php

namespace App\Http\Controllers;

use App\Models\CaravaneVillageTouche;
use App\Models\FicheAnimationVente;
use App\Models\FicheCaravane;
use App\Models\FicheDemonstrationCulinaire;
use App\Models\FicheEmissionsRadio;
use App\Models\FicheLeaderOpinion;
use App\Models\FicheVentesDistributeur;
use App\Models\FoyerType;
use App\Models\Region;
use App\Models\VenteDistributeurItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Calcule et applique les filtres de date et période.
     */
    private function resolveDateBounds(Request $request): array
    {
        $dateDebut = $request->date_debut;
        $dateFin = $request->date_fin;

        if (!$dateDebut && !$dateFin && $request->filled('periode')) {
            switch ($request->periode) {
                case 'ce_jour':
                    $dateDebut = now()->toDateString();
                    $dateFin = now()->toDateString();
                    break;
                case 'cette_semaine':
                    $dateDebut = now()->startOfWeek()->toDateString();
                    $dateFin = now()->endOfWeek()->toDateString();
                    break;
                case 'ce_mois':
                    $dateDebut = now()->startOfMonth()->toDateString();
                    $dateFin = now()->endOfMonth()->toDateString();
                    break;
                case 'dernier_mois':
                    $dateDebut = now()->subMonth()->startOfMonth()->toDateString();
                    $dateFin = now()->subMonth()->endOfMonth()->toDateString();
                    break;
                case 'ce_trimestre':
                    $dateDebut = now()->firstOfQuarter()->toDateString();
                    $dateFin = now()->lastOfQuarter()->toDateString();
                    break;
                case 'cette_annee':
                    $dateDebut = now()->startOfYear()->toDateString();
                    $dateFin = now()->endOfYear()->toDateString();
                    break;
            }
        }

        return [$dateDebut, $dateFin];
    }

    private function applyDateFilter($query, ?string $dateDebut, ?string $dateFin, string $dateColumn, bool $useCreatedAtFallback = false)
    {
        if ($dateDebut) {
            if ($useCreatedAtFallback) {
                $query->where(function ($q) use ($dateDebut, $dateColumn) {
                    $q->whereDate($dateColumn, '>=', $dateDebut)
                      ->orWhere(function ($sub) use ($dateDebut, $dateColumn) {
                          $sub->whereNull($dateColumn)
                              ->whereDate('created_at', '>=', $dateDebut);
                      });
                });
            } else {
                $query->whereDate($dateColumn, '>=', $dateDebut);
            }
        }

        if ($dateFin) {
            if ($useCreatedAtFallback) {
                $query->where(function ($q) use ($dateFin, $dateColumn) {
                    $q->whereDate($dateColumn, '<=', $dateFin)
                      ->orWhere(function ($sub) use ($dateFin, $dateColumn) {
                          $sub->whereNull($dateColumn)
                              ->whereDate('created_at', '<=', $dateFin);
                      });
                });
            } else {
                $query->whereDate($dateColumn, '<=', $dateFin);
            }
        }
    }

    public function index(Request $request)
    {
        $cible100k = 100000;

        // Filtre optionnel par région
        $regionFilter = $request->query('region');
        $periodeFilter = $request->query('periode');

        // Résolution de la période
        [$dateDebut, $dateFin] = $this->resolveDateBounds($request);

        $queryAnim = FicheAnimationVente::query();
        $queryDemo = FicheDemonstrationCulinaire::query();
        $queryCaravane = FicheCaravane::query();
        $queryRadio = FicheEmissionsRadio::query();
        $queryLeader = FicheLeaderOpinion::query();
        $queryVente = FicheVentesDistributeur::query();

        if ($regionFilter) {
            $queryAnim->where('region', $regionFilter);
            $queryDemo->where('region', $regionFilter);
            $queryCaravane->where('region', $regionFilter);
            $queryRadio->where('region', $regionFilter);
            $queryLeader->where('region', $regionFilter);
            $queryVente->where('region', $regionFilter);
        }

        // Application des filtres de dates/périodes à toutes les fiches
        $this->applyDateFilter($queryAnim, $dateDebut, $dateFin, 'date_animation');
        $this->applyDateFilter($queryDemo, $dateDebut, $dateFin, 'date_demonstration');
        $this->applyDateFilter($queryCaravane, $dateDebut, $dateFin, 'date_caravane');
        $this->applyDateFilter($queryRadio, $dateDebut, $dateFin, 'date_emission');
        $this->applyDateFilter($queryLeader, $dateDebut, $dateFin, 'client_created_at', true);
        $this->applyDateFilter($queryVente, $dateDebut, $dateFin, 'date_vente');

        // Totaux
        $totalFaDistributeurs = (int) $queryVente->sum('total_fa_vendus');
        $totalFaAnimations = (int) $queryAnim->sum('nb_fa_vendus');
        $totalFaCaravanes = (int) $queryCaravane->sum('nb_fa_vendus');
        $grandTotalFa = $totalFaDistributeurs + $totalFaAnimations + $totalFaCaravanes;
        $pourcentageCible = round(($grandTotalFa / $cible100k) * 100, 2);

        // Indicateurs clés
        $countAnimations = $queryAnim->count();
        $countDemonstrations = $queryDemo->count();
        $totalPresentsDemo = (int) $queryDemo->sum('nb_presents');
        $totalFemmesDemo = (int) $queryDemo->sum('nb_femmes');
        $totalHommesDemo = (int) $queryDemo->sum('nb_hommes');
        $countCaravanes = $queryCaravane->count();
        $totalVillagesTouches = (int) $queryCaravane->sum('nb_villages_sillonnes');
        $countEmissions = $queryRadio->count();
        $countLeaders = $queryLeader->count();
        $countVentes = $queryVente->count();

        // 1. Répartition des ventes par type de combustible (Charbon vs Bois vs Bois/Charbon)
        $ventesParCombustibleQuery = DB::table('vente_distributeur_items')
            ->join('foyer_types', 'vente_distributeur_items.foyer_type_id', '=', 'foyer_types.id')
            ->join('fiche_ventes_distributeurs', 'vente_distributeur_items.fiche_ventes_distributeur_id', '=', 'fiche_ventes_distributeurs.id')
            ->when($regionFilter, function ($q) use ($regionFilter) {
                return $q->where('fiche_ventes_distributeurs.region', $regionFilter);
            });

        if ($dateDebut) {
            $ventesParCombustibleQuery->whereDate('fiche_ventes_distributeurs.date_vente', '>=', $dateDebut);
        }
        if ($dateFin) {
            $ventesParCombustibleQuery->whereDate('fiche_ventes_distributeurs.date_vente', '<=', $dateFin);
        }

        $ventesParCombustible = $ventesParCombustibleQuery
            ->select('foyer_types.combustible', DB::raw('SUM(vente_distributeur_items.quantite) as total'))
            ->groupBy('foyer_types.combustible')
            ->pluck('total', 'foyer_types.combustible')
            ->toArray();

        // 2. Ventes par Région
        $ventesParRegionQuery = FicheVentesDistributeur::query();
        if ($dateDebut) {
            $ventesParRegionQuery->whereDate('date_vente', '>=', $dateDebut);
        }
        if ($dateFin) {
            $ventesParRegionQuery->whereDate('date_vente', '<=', $dateFin);
        }
        $ventesParRegion = $ventesParRegionQuery
            ->select('region', DB::raw('SUM(total_fa_vendus) as total'))
            ->groupBy('region')
            ->orderByDesc('total')
            ->pluck('total', 'region')
            ->toArray();

        // 3. Modèles de foyers les plus diffusés
        $topFoyersQuery = DB::table('vente_distributeur_items')
            ->join('foyer_types', 'vente_distributeur_items.foyer_type_id', '=', 'foyer_types.id')
            ->join('fiche_ventes_distributeurs', 'vente_distributeur_items.fiche_ventes_distributeur_id', '=', 'fiche_ventes_distributeurs.id')
            ->when($regionFilter, function ($q) use ($regionFilter) {
                return $q->where('fiche_ventes_distributeurs.region', $regionFilter);
            });

        if ($dateDebut) {
            $topFoyersQuery->whereDate('fiche_ventes_distributeurs.date_vente', '>=', $dateDebut);
        }
        if ($dateFin) {
            $topFoyersQuery->whereDate('fiche_ventes_distributeurs.date_vente', '<=', $dateFin);
        }

        $topFoyers = $topFoyersQuery
            ->select('foyer_types.nom', 'foyer_types.combustible', DB::raw('SUM(vente_distributeur_items.quantite) as quantite_totale'))
            ->groupBy('foyer_types.id', 'foyer_types.nom', 'foyer_types.combustible')
            ->orderByDesc('quantite_totale')
            ->get();

        // 4. Dernières activités saisies (tenant compte des filtres actifs)
        $recentVentes = (clone $queryVente)->latest('date_vente')->take(5)->get();
        $recentAnimations = (clone $queryAnim)->latest('date_animation')->take(5)->get();
        $recentDemonstrations = (clone $queryDemo)->latest('date_demonstration')->take(5)->get();

        $regionsList = Region::orderBy('nom')->get();

        return view('dashboard', compact(
            'cible100k',
            'grandTotalFa',
            'pourcentageCible',
            'totalFaDistributeurs',
            'totalFaAnimations',
            'totalFaCaravanes',
            'countAnimations',
            'countDemonstrations',
            'totalPresentsDemo',
            'totalFemmesDemo',
            'totalHommesDemo',
            'countCaravanes',
            'totalVillagesTouches',
            'countEmissions',
            'countLeaders',
            'countVentes',
            'ventesParCombustible',
            'ventesParRegion',
            'topFoyers',
            'recentVentes',
            'recentAnimations',
            'recentDemonstrations',
            'regionsList',
            'regionFilter',
            'periodeFilter',
            'dateDebut',
            'dateFin'
        ));
    }
}
