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

class FicheController extends Controller
{
    // 1. Animations-Ventes
    public function animations(Request $request)
    {
        $query = FicheAnimationVente::with('user')->latest();
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
        $animations = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('nom')->get();

        return view('fiches.animations', compact('animations', 'regions'));
    }

    // 2. Démonstrations Culinaires
    public function demonstrations(Request $request)
    {
        $query = FicheDemonstrationCulinaire::with('user')->latest();
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
        $demonstrations = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('nom')->get();

        return view('fiches.demonstrations', compact('demonstrations', 'regions'));
    }

    // 3. Caravanes
    public function caravanes(Request $request)
    {
        $query = FicheCaravane::with(['villagesTouches', 'user'])->latest();
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('itineraire', 'like', "%{$request->search}%")
                  ->orWhere('distributeurs_beneficiaires', 'like', "%{$request->search}%");
            });
        }
        $caravanes = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('nom')->get();

        return view('fiches.caravanes', compact('caravanes', 'regions'));
    }

    // 4. Émissions Radios
    public function emissions(Request $request)
    {
        $query = FicheEmissionsRadio::with(['participants', 'user'])->latest();
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
        $emissions = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('nom')->get();

        return view('fiches.emissions', compact('emissions', 'regions'));
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
        $leaders = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('nom')->get();

        return view('fiches.leaders', compact('leaders', 'regions'));
    }

    // 6. Ventes Distributeurs
    public function ventesDistributeurs(Request $request)
    {
        $query = FicheVentesDistributeur::with(['items.foyerType', 'user'])->latest();
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
        $ventes = $query->paginate(15)->withQueryString();
        $regions = Region::orderBy('nom')->get();

        return view('fiches.ventes_distributeurs', compact('ventes', 'regions'));
    }
}
