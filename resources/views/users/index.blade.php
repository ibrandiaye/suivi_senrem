@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs Mobiles')
@section('page_title', 'Gestion des Utilisateurs & Affectations Régionales')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Utilisateurs et Collecteurs Mobiles</h3>
            <p class="text-xs text-slate-500 mt-1">
                Créez et gérez les comptes pour les animateurs terrain. Chaque agent mobile est obligatoirement affecté à <strong>une seule région</strong>.
            </p>
        </div>
        <a href="{{ route('users.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-senrm-700 hover:bg-senrm-800 text-white rounded-xl text-sm font-semibold shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Créer un Utilisateur</span>
        </a>
    </div>

    <!-- Feedback Alerts -->
    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filters Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, email ou tél..."
                       class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-senrm-500">
            </div>
            <div>
                <select name="role" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-senrm-500">
                    <option value="">Tous les rôles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateurs</option>
                    <option value="superviseur" {{ request('role') == 'superviseur' ? 'selected' : '' }}>Superviseurs</option>
                    <option value="animateur" {{ request('role') == 'animateur' ? 'selected' : '' }}>Animateurs Terrain</option>
                </select>
            </div>
            <div class="flex gap-2">
                <select name="region" class="w-full text-xs px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-senrm-500">
                    <option value="">Toutes les régions</option>
                    @foreach ($regions as $r)
                        <option value="{{ $r->nom }}" {{ request('region') == $r->nom ? 'selected' : '' }}>{{ $r->nom }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg text-xs font-semibold hover:bg-slate-700 transition">
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'role', 'region']))
                    <a href="{{ route('users.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold hover:bg-slate-200 transition flex items-center">
                        Effacer
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
                        <th class="py-3 px-4">Utilisateur / Collecteur</th>
                        <th class="py-3 px-4">Rôle</th>
                        <th class="py-3 px-4">Région d'Affectation</th>
                        <th class="py-3 px-4">Contact</th>
                        <th class="py-3 px-4">Statut</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $u)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-700 font-bold flex items-center justify-center border border-slate-200 shrink-0 text-xs">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm">{{ $u->name }}</div>
                                        <div class="text-slate-500 font-mono text-[11px]">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                @if ($u->role === 'admin')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                        Administrateur
                                    </span>
                                @elseif ($u->role === 'superviseur')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                        Superviseur
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Animateur Mobile
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-amber-500">📍</span>
                                    <span class="font-semibold text-slate-800">{{ $u->region ?: 'Non assignée' }}</span>
                                </div>
                                <div class="text-[10px] text-slate-400">Collecte verrouillée à cette zone</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-mono text-slate-600">{{ $u->phone ?: 'Non renseigné' }}</div>
                            </td>
                            <td class="py-3 px-4">
                                @if ($u->is_active)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Désactivé
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Toggle Active -->
                                    <form method="POST" action="{{ route('users.toggle', $u) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                title="{{ $u->is_active ? 'Désactiver le compte' : 'Activer le compte' }}"
                                                class="p-1.5 rounded-lg border {{ $u->is_active ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }} transition text-xs">
                                            {{ $u->is_active ? '⏸️' : '▶️' }}
                                        </button>
                                    </form>

                                    <!-- Edit -->
                                    <a href="{{ route('users.edit', $u) }}" 
                                       title="Modifier l'utilisateur"
                                       class="p-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 transition text-xs">
                                        ✏️
                                    </a>

                                    <!-- Delete -->
                                    @if ($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $u) }}" class="inline" onsubmit="return confirm('Confirmez-vous la suppression de cet utilisateur ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Supprimer l'utilisateur"
                                                    class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition text-xs">
                                                🗑️
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-sm">
                                Aucun utilisateur trouvé correspondant aux critères.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
