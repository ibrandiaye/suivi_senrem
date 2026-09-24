@extends('layouts.app')

@section('title', 'Suivi des Caravanes')
@section('page_title', 'Fiche de Suivi des Caravanes de Sensibilisation')

@section('content')

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h3 class="text-xl font-bold text-slate-900">Caravanes Mobiles de Sensibilisation</h3>
        <p class="text-xs text-slate-500">Conforme à la fiche FICHE DE SUIVI DES CARAVANES_SENRM.docx</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('exports.download', 'caravanes') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Exporter en Excel (.xlsx)</span>
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
    <form method="GET" action="{{ route('fiches.caravanes') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Recherche (Itinéraire, distributeurs...)</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Itinéraire, ville..." class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
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
                <a href="{{ route('fiches.caravanes') }}" class="px-3 py-2 text-xs text-rose-600 font-semibold hover:underline">Réinitialiser</a>
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
                    <th class="py-3 px-4">Date & Heures</th>
                    <th class="py-3 px-4">Localisation & Itinéraire</th>
                    <th class="py-3 px-4">Logistique</th>
                    <th class="py-3 px-4">Villages Sillonnés</th>
                    <th class="py-3 px-4">Supports Distribués</th>
                    <th class="py-3 px-4 text-right">FA Vendus</th>
                    <th class="py-3 px-4">Responsable</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($caravanes as $c)
                <tr class="hover:bg-slate-50/60">
                    <td class="py-3 px-4 font-medium text-slate-500 whitespace-nowrap">
                        <div class="font-bold text-slate-800">{{ $c->date_caravane ? $c->date_caravane->format('d/m/Y') : '-' }}</div>
                        <div class="text-[11px] text-slate-400 font-mono">{{ $c->heure_debut }} &rarr; {{ $c->heure_fin }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="font-bold text-slate-800">{{ $c->region }} ({{ $c->departement }})</div>
                        <div class="text-[11px] text-slate-500 max-w-xs mt-0.5">{{ $c->itineraire }}</div>
                        <div class="text-[11px] text-emerald-700 font-semibold mt-1">Distributeurs : {{ $c->distributeurs_beneficiaires }}</div>
                    </td>
                    <td class="py-3 px-4 text-slate-600">
                        @if(is_array($c->moyens_logistiques))
                            <div class="flex flex-wrap gap-1">
                                @foreach($c->moyens_logistiques as $m)
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-[10px] font-medium">{{ $m }}</span>
                                @endforeach
                            </div>
                        @else
                            -
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <span class="font-mono font-bold text-slate-800">{{ $c->nb_villages_sillonnes }} villages</span>
                        <div class="mt-1 flex flex-wrap gap-1">
                            @foreach($c->villagesTouches as $v)
                                <span class="px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-800 text-[10px] border border-emerald-100">{{ $v->nom_village_quartier }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td class="py-3 px-4 text-slate-600 text-[11px]">
                        <div>Affiches : <strong>{{ $c->supports_affiches }}</strong></div>
                        <div>Dépliants : <strong>{{ $c->supports_depliants }}</strong></div>
                        @if($c->supports_autres_nb)
                            <div>{{ $c->supports_autres_libelle }} : <strong>{{ $c->supports_autres_nb }}</strong></div>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-right">
                        <span class="px-3 py-1 rounded-xl bg-amber-100 text-amber-900 font-mono font-black text-sm">
                            {{ $c->nb_fa_vendus }} FA
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <div class="font-bold text-slate-800">{{ $c->user ? $c->user->name : $c->responsable_nom }}</div>
                        <div class="text-[10px] text-slate-400 font-mono">{{ $c->user ? $c->user->email : '' }}</div>
                        <div class="text-[10px] text-amber-600 font-medium">📍 Région : {{ $c->user ? $c->user->region : $c->region }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-xs text-slate-400 italic">
                        Aucune caravane enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-slate-100">
        {{ $caravanes->links() }}
    </div>
</div>

@endsection
