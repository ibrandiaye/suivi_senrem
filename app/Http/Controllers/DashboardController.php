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
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $cible100k = 100000;

        // Filtre optionnel par région
        $regionFilter = $request->query('region');

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
        $ventesParCombustible = DB::table('vente_distributeur_items')
            ->join('foyer_types', 'vente_distributeur_items.foyer_type_id', '=', 'foyer_types.id')
            ->join('fiche_ventes_distributeurs', 'vente_distributeur_items.fiche_ventes_distributeur_id', '=', 'fiche_ventes_distributeurs.id')
            ->when($regionFilter, function ($q) use ($regionFilter) {
                return $q->where('fiche_ventes_distributeurs.region', $regionFilter);
            })
            ->select('foyer_types.combustible', DB::raw('SUM(vente_distributeur_items.quantite) as total'))
            ->groupBy('foyer_types.combustible')
            ->pluck('total', 'foyer_types.combustible')
            ->toArray();

        // 2. Ventes par Région
        $ventesParRegion = FicheVentesDistributeur::select('region', DB::raw('SUM(total_fa_vendus) as total'))
            ->groupBy('region')
            ->orderByDesc('total')
            ->pluck('total', 'region')
            ->toArray();

        // 3. Modèles de foyers les plus diffusés
        $topFoyers = DB::table('vente_distributeur_items')
            ->join('foyer_types', 'vente_distributeur_items.foyer_type_id', '=', 'foyer_types.id')
            ->select('foyer_types.nom', 'foyer_types.combustible', DB::raw('SUM(vente_distributeur_items.quantite) as quantite_totale'))
            ->groupBy('foyer_types.id', 'foyer_types.nom', 'foyer_types.combustible')
            ->orderByDesc('quantite_totale')
            ->get();

        // 4. Dernières activités saisies
        $recentVentes = FicheVentesDistributeur::latest()->take(5)->get();
        $recentAnimations = FicheAnimationVente::latest()->take(5)->get();
        $recentDemonstrations = FicheDemonstrationCulinaire::latest()->take(5)->get();

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
            'regionFilter'
        ));
    }
}
