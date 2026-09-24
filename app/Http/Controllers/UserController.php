<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Liste des utilisateurs (administrateurs, superviseurs et agents de collecte mobiles).
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        // Récupérer la liste des régions pour les filtres et attributions
        $regions = Region::orderBy('nom')->get();

        return view('users.index', compact('users', 'regions'));
    }

    /**
     * Formulaire de création d'un utilisateur mobile.
     */
    public function create(): View
    {
        $regions = Region::orderBy('nom')->get();
        return view('users.create', compact('regions'));
    }

    /**
     * Enregistre un nouvel utilisateur (avec affectation d'une région obligatoire pour le mobile).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'role' => ['required', Rule::in(['admin', 'superviseur', 'animateur'])],
            'region' => [
                'required_if:role,animateur',
                'required_if:role,superviseur',
                'nullable',
                'string',
            ],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'region.required_if' => "L'affectation d'une région est obligatoire pour les agents de collecte mobiles (animateurs et superviseurs).",
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre compte.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'region' => $validated['role'] === 'admin' ? ($validated['region'] ?? 'National') : $validated['region'],
            'password' => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('users.index')->with('success', "Le compte de {$validated['name']} a été créé avec succès avec affectation à la région {$validated['region']}.");
    }

    /**
     * Formulaire de modification d'un utilisateur.
     */
    public function edit(User $user): View
    {
        $regions = Region::orderBy('nom')->get();
        return view('users.edit', compact('user', 'regions'));
    }

    /**
     * Met à jour un utilisateur.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'role' => ['required', Rule::in(['admin', 'superviseur', 'animateur'])],
            'region' => [
                'required_if:role,animateur',
                'required_if:role,superviseur',
                'nullable',
                'string',
            ],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'region.required_if' => "L'affectation d'une région est obligatoire pour les utilisateurs mobiles.",
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'region' => $validated['role'] === 'admin' ? ($validated['region'] ?? 'National') : $validated['region'],
            'is_active' => $request->boolean('is_active', true),
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        return redirect()->route('users.index')->with('success', "Le compte de {$user->name} a été mis à jour.");
    }

    /**
     * Active ou désactive un utilisateur.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte administrateur.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $action = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Le compte de {$user->name} a été {$action}.");
    }

    /**
     * Supprime un utilisateur.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte administrateur.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('success', "Le compte de {$userName} a été supprimé.");
    }
}
