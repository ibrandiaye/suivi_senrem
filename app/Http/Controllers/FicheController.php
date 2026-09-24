<?php

namespace App\Http\Controllers;

use App\Models\FicheAnimationVente;
use App\Models\FicheCaravane;
use App\Models\FicheDemonstrationCulinaire;
use App\Models\FicheEmissionsRadio;
use App\Models\FicheLeaderOpinion;
use App\Models\FicheVentesDistributeur;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FicheController extends Controller
{
    /**
     * Applique les filtres de date et période à une requête Eloquent.
     */
    private function applyDateAndPeriodFilter($query, Request $request, string $dateColumn, bool $useCreatedAtFallback = false)
    {
        $dateDebut = $request->date_debut;
        $dateFin = $request->date_fin;

        // Raccourcis de période prédéfinie si les dates manuelles ne sont pas toutes les deux renseignées
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

    // 1. Animations-Ventes
    public function animations(Request $request)
    {
        $query = FicheAnimationVente::with('user')->latest('date_animation');

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('distributeur_nom', 'like', "%{$request->search}%")
                  ->orWhere('animateur_nom', 'like', "%{$request->search}%")
                  ->orWhere('commune', 'like', "%{$request->search}%");
            });
        }

        $this->applyDateAndPeriodFilter($query, $request, 'date_animation');

        $totalFiltered = (clone $query)->count();
        $animations = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('nom')->get();

        return view('fiches.animations', compact('animations', 'regions', 'totalFiltered'));
    }

    // 2. Démonstrations Culinaires
    public function demonstrations(Request $request)
    {
        $query = FicheDemonstrationCulinaire::with('user')->latest('date_demonstration');

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('gpf_nom', 'like', "%{$request->search}%")
                  ->orWhere('presidente_nom', 'like', "%{$request->search}%")
                  ->orWhere('commune', 'like', "%{$request->search}%");
            });
        }

        $this->applyDateAndPeriodFilter($query, $request, 'date_demonstration');

        $totalFiltered = (clone $query)->count();
        $demonstrations = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('nom')->get();

        return view('fiches.demonstrations', compact('demonstrations', 'regions', 'totalFiltered'));
    }

    // 3. Caravanes
    public function caravanes(Request $request)
    {
        $query = FicheCaravane::with(['villagesTouches', 'user'])->latest('date_caravane');

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('itineraire', 'like', "%{$request->search}%")
                  ->orWhere('distributeurs_beneficiaires', 'like', "%{$request->search}%");
            });
        }

        $this->applyDateAndPeriodFilter($query, $request, 'date_caravane');

        $totalFiltered = (clone $query)->count();
        $caravanes = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('nom')->get();

        return view('fiches.caravanes', compact('caravanes', 'regions', 'totalFiltered'));
    }

    // 4. Émissions Radios
    public function emissions(Request $request)
    {
        $query = FicheEmissionsRadio::with(['participants', 'user'])->latest('date_emission');

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('radio_nom', 'like', "%{$request->search}%")
                  ->orWhere('theme', 'like', "%{$request->search}%")
                  ->orWhere('animateur_nom', 'like', "%{$request->search}%");
            });
        }

        $this->applyDateAndPeriodFilter($query, $request, 'date_emission');

        $totalFiltered = (clone $query)->count();
        $emissions = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('nom')->get();

        return view('fiches.emissions', compact('emissions', 'regions', 'totalFiltered'));
    }

    // 5. Leaders d'Opinion
    public function leaders(Request $request)
    {
        $query = FicheLeaderOpinion::with('user')->latest();

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('prenom_nom', 'like', "%{$request->search}%")
                  ->orWhere('titre_profession', 'like', "%{$request->search}%")
                  ->orWhere('membre_entites', 'like', "%{$request->search}%");
            });
        }

        $this->applyDateAndPeriodFilter($query, $request, 'client_created_at', true);

        $totalFiltered = (clone $query)->count();
        $leaders = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('nom')->get();

        return view('fiches.leaders', compact('leaders', 'regions', 'totalFiltered'));
    }

    // 6. Ventes Distributeurs
    public function ventesDistributeurs(Request $request)
    {
        $query = FicheVentesDistributeur::with(['items.foyerType', 'user'])->latest('date_vente');

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('distributeur_nom', 'like', "%{$request->search}%")
                  ->orWhere('village_quartier', 'like', "%{$request->search}%")
                  ->orWhere('adresse_client', 'like', "%{$request->search}%");
            });
        }

        $this->applyDateAndPeriodFilter($query, $request, 'date_vente');

        $totalFiltered = (clone $query)->count();
        $ventes = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('nom')->get();

        return view('fiches.ventes_distributeurs', compact('ventes', 'regions', 'totalFiltered'));
    }
}
