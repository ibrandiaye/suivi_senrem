@extends('layouts.app')

@section('title', 'Modifier Utilisateur')
@section('page_title', 'Modifier le Compte Utilisateur')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Modifier : {{ $user->name }}</h3>
            <p class="text-xs text-slate-500 mt-0.5">Mettre à jour les informations, le rôle ou la région d'affectation.</p>
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
        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom complet -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nom et Prénom <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-senrm-500">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Adresse Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-senrm-500">
                </div>

                <!-- Téléphone -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Numéro de Téléphone
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-senrm-500">
                </div>

                <!-- Rôle -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Rôle dans le Projet <span class="text-rose-500">*</span>
                    </label>
                    <select name="role" required
                            class="w-full text-sm px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-senrm-500">
                        <option value="animateur" {{ old('role', $user->role) == 'animateur' ? 'selected' : '' }}>Animateur Relais Terrain (Mobile)</option>
                        <option value="superviseur" {{ old('role', $user->role) == 'superviseur' ? 'selected' : '' }}>Superviseur Régional (Mobile)</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur National (Backend Web)</option>
                    </select>
                </div>
            </div>

            <!-- Région d'affectation obligatoire -->
            <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200">
                <label class="block text-xs font-bold text-amber-900 uppercase tracking-wider mb-1">
                    📍 Région d'Affectation Unique <span class="text-rose-500">*</span>
                </label>
                <p class="text-xs text-amber-700 mb-3">
                    Cette région détermine la seule zone géographique où cet agent est autorisé à enregistrer des fiches.
                </p>
                <select name="region" required
                        class="w-full text-sm px-3.5 py-2.5 bg-white border border-amber-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 font-semibold text-slate-800">
                    <option value="">-- Sélectionnez la région d'affectation --</option>
                    @foreach ($regions as $r)
                        <option value="{{ $r->nom }}" {{ old('region', $user->region) == $r->nom ? 'selected' : '' }}>
                            {{ $r->nom }} ({{ $r->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Changement de mot de passe (optionnel) -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-4">
                <div class="text-xs font-bold text-slate-800">Modifier le Mot de Passe (laisser vide pour conserver l'actuel)</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nouveau mot de passe</label>
                        <input type="password" name="password" minlength="6"
                               class="w-full text-sm px-3 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-senrm-500"
                               placeholder="Laisser vide pour ne pas changer">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Confirmer nouveau mot de passe</label>
                        <input type="password" name="password_confirmation" minlength="6"
                               class="w-full text-sm px-3 py-2 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-senrm-500"
                               placeholder="Répétez le mot de passe">
                    </div>
                </div>
            </div>

            <!-- Statut Actif -->
            <div class="flex items-center gap-3 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                       class="rounded bg-slate-100 border-slate-300 text-senrm-600 focus:ring-senrm-500 w-4 h-4">
                <label for="is_active" class="text-xs font-semibold text-slate-700 cursor-pointer">
                    Compte actif (autorisé à se connecter)
                </label>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2.5 bg-senrm-700 hover:bg-senrm-800 text-white rounded-xl text-sm font-bold shadow-sm transition">
                    Mettre à Jour le Compte
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
