@extends('layouts.app')

@section('title', 'Créer un Utilisateur Mobile')
@section('page_title', 'Créer un Compte Utilisateur / Collecteur')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Nouveau Compte Terrain</h3>
            <p class="text-xs text-slate-500 mt-0.5">Affectez obligatoirement chaque collecteur mobile à une région spécifique.</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
            &larr; Retour à la liste
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <div class="font-bold mb-1">Veuillez corriger les erreurs suivantes :</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom complet -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nom et Prénom <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-senrm-500"
                           placeholder="ex: Mamadou Diallo">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Adresse Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-senrm-500"
                           placeholder="ex: m.diallo@senrm-enda.org">
                    <p class="text-[11px] text-slate-400 mt-1">Sert d'identifiant de connexion sur l'application mobile.</p>
                </div>

                <!-- Téléphone -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Numéro de Téléphone
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-senrm-500"
                           placeholder="ex: +221 77 123 45 67">
                </div>

                <!-- Rôle -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Rôle dans le Projet <span class="text-rose-500">*</span>
                    </label>
                    <select name="role" id="roleSelect" required
                            class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-senrm-500">
                        <option value="animateur" {{ old('role') == 'animateur' ? 'selected' : '' }}>Animateur Relais Terrain (Mobile)</option>
                        <option value="superviseur" {{ old('role') == 'superviseur' ? 'selected' : '' }}>Superviseur Régional (Mobile)</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur National (Backend Web)</option>
                    </select>
                </div>
            </div>

            <!-- Région d'affectation obligatoire -->
            <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200">
                <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1">
                    📍 Région d'Affectation Unique <span class="text-rose-500">*</span>
                </label>
                <p class="text-xs text-amber-700 mb-3">
                    L'agent sera strictement verrouillé sur cette région dans l'application mobile : il ne pourra collecter aucune fiche en dehors de sa région.
                </p>
                <select name="region" id="regionSelect" required
                        class="w-full text-sm px-3.5 py-2.5 bg-white border border-amber-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 font-semibold text-slate-800">
                    <option value="">-- Sélectionnez la région d'affectation --</option>
                    @foreach ($regions as $r)
                        <option value="{{ $r->nom }}" {{ old('region') == $r->nom ? 'selected' : '' }}>
                            {{ $r->nom }} ({{ $r->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Mots de passe -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Mot de passe temporaire <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="password" required minlength="6"
                           class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-senrm-500"
                           placeholder="Minimum 6 caractères">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Confirmer le mot de passe <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required minlength="6"
                           class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-senrm-500"
                           placeholder="Répétez le mot de passe">
                </div>
            </div>

            <!-- Statut Actif -->
            <div class="flex items-center gap-3 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="rounded bg-slate-100 border-slate-300 text-senrm-600 focus:ring-senrm-500 w-4 h-4">
                <label for="is_active" class="text-xs font-semibold text-slate-700 cursor-pointer">
                    Compte activé (l'utilisateur peut se connecter immédiatement)
                </label>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2.5 bg-senrm-700 hover:bg-senrm-800 text-white rounded-xl text-sm font-bold shadow-sm transition">
                    Enregistrer l'Utilisateur
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
