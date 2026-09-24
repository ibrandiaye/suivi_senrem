@extends('layouts.app')

@section('title', 'Identification Leaders d\'Opinion')
@section('page_title', 'Fiche d\'Identification des Leaders d\'Opinion')

@section('content')

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h3 class="text-xl font-bold text-slate-900">Leaders d'Opinion & Personnalités Influentes</h3>
        <p class="text-xs text-slate-500">Conforme à la fiche FICHE DIDENTIFICATION LEADER DOPINION_SENRM.docx</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('exports.download', 'leaders') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Exporter en Excel (.xlsx)</span>
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
    <form method="GET" action="{{ route('fiches.leaders') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Recherche (Nom, profession, entité...)</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom leader, profession, entité..." class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Région</label>
            <select name="region" class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                <option value="">Toutes les régions</option>
                @foreach($regions as $reg)
                    <option value="{{ $reg->nom }}" {{ request('region') == $reg->nom ? 'selected' : '' }}>{{ $reg->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                Filtrer
            </button>
            @if(request()->hasAny(['search', 'region']))
                <a href="{{ route('fiches.leaders') }}" class="px-3 py-2 text-xs text-rose-600 font-semibold hover:underline">Réinitialiser</a>
            @endif
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4">Prénom & Nom</th>
                    <th class="py-3 px-4">Sexe</th>
                    <th class="py-3 px-4">Titre / Profession</th>
                    <th class="py-3 px-4">Contact Téléphone / Email</th>
                    <th class="py-3 px-4">Localisation & Habitation</th>
                    <th class="py-3 px-4">Entités & Fonctions</th>
                    <th class="py-3 px-4">Zones d'Influence</th>
                    <th class="py-3 px-4">Responsable</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($leaders as $l)
                <tr class="hover:bg-slate-50/60">
                    <td class="py-3 px-4 font-bold text-slate-900">
                        {{ $l->prenom_nom }}
                    </td>
                    <td class="py-3 px-4">
                        <span class="inline-block px-2 py-0.5 rounded font-mono font-bold text-[10px] {{ $l->sexe == 'F' ? 'bg-pink-100 text-pink-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $l->sexe == 'F' ? 'Femme' : 'Homme' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 font-medium text-slate-800">
                        {{ $l->titre_profession ?: '-' }}
                    </td>
                    <td class="py-3 px-4">
                        <div class="font-mono text-slate-800 font-bold">{{ $l->telephone }}</div>
                        <div class="text-[11px] text-slate-400">{{ $l->email ?: '-' }}</div>
                    </td>
                    <td class="py-3 px-4 text-slate-700">
                        <div><strong>{{ $l->commune }}</strong> ({{ $l->village_quartier }})</div>
                        <div class="text-[11px] text-slate-500">{{ $l->region }}, {{ $l->departement }}</div>
                    </td>
                    <td class="py-3 px-4 text-slate-700 max-w-xs">
                        <div class="font-semibold">{{ $l->membre_entites }}</div>
                        <div class="text-[11px] text-slate-500">{{ $l->fonctions_entites }}</div>
                    </td>
                    <td class="py-3 px-4 text-slate-600 max-w-xs truncate">
                        {{ $l->localites_influence ?: '-' }}
                    </td>
                    <td class="py-3 px-4">
                        <div class="font-bold text-slate-800">{{ $l->user ? $l->user->name : $l->responsable_nom }}</div>
                        <div class="text-[10px] text-slate-400 font-mono">{{ $l->user ? $l->user->email : '' }}</div>
                        <div class="text-[10px] text-amber-600 font-medium">📍 Région : {{ $l->user ? $l->user->region : $l->region }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-8 text-center text-xs text-slate-400 italic">
                        Aucun leader d'opinion enregistré.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-slate-100">
        {{ $leaders->links() }}
    </div>
</div>

@endsection
