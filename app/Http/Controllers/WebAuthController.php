<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WebAuthController extends Controller
{
    /**
     * Affiche la page de connexion au backend d'administration SENRM.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('dashboard');
            }
            Auth::logout();
        }

        return view('auth.login');
    }

    /**
     * Traite la tentative de connexion au backend.
     * Seul le rôle 'admin' est autorisé à entrer sur le backend web.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withInput($request->only('email', 'remember'))->withErrors([
                'email' => 'Identifiants invalides. Veuillez vérifier votre adresse email et votre mot de passe.',
            ]);
        }

        $user = Auth::user();

        // 1. Vérification du rôle Administrateur strict
        if ($user->role !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withInput($request->only('email'))->withErrors([
                'email' => "Accès refusé : Seul l'administrateur a le droit de se connecter au backend web. Les animateurs et superviseurs de terrain doivent utiliser l'application mobile.",
            ]);
        }

        // 2. Vérification du statut actif
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withInput($request->only('email'))->withErrors([
                'email' => "Ce compte administrateur a été désactivé. Veuillez contacter la coordination SENRM.",
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))->with('status', 'Bienvenue sur la plateforme de suivi SENRM.');
    }

    /**
     * Déconnexion de l'administrateur.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Vous avez été déconnecté avec succès.');
    }
}
