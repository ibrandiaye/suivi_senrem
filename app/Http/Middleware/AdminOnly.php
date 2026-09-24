<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     * Ensures only active users with 'admin' role can access backend web routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Veuillez vous connecter pour accéder à la plateforme.',
            ]);
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte administrateur est désactivé.',
            ]);
        }

        if ($user->role !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => "Accès refusé : Seul l'administrateur a le droit de se connecter sur le backend web. Les comptes animateurs et superviseurs utilisent l'application mobile.",
            ]);
        }

        return $next($request);
    }
}
