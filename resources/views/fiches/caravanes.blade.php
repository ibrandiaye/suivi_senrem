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
        <a href="{{ route('exports.download', ['type' => 'caravanes'] + request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Exporter en Excel (.xlsx)</span>
            @if(request()->hasAny(['date_debut', 'date_fin', 'periode']))
                <span class="ml-1 px-1.5 py-0.5 rounded bg-emerald-900 text-[10px] text-emerald-200 font-normal">Période filtrée</span>
            @endif
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
    <form method="GET" action="{{ route('fiches.caravanes') }}" class="space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
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
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Période Prédéfinie</label>
                <select name="periode" class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">Dates personnalisées ou tout</option>
                    <option value="ce_jour" {{ request('periode') == 'ce_jour' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="cette_semaine" {{ request('periode') == 'cette_semaine' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="ce_mois" {{ request('periode') == 'ce_mois' ? 'selected' : '' }}>Ce mois-ci</option>
                    <option value="dernier_mois" {{ request('periode') == 'dernier_mois' ? 'selected' : '' }}>Le mois dernier</option>
                    <option value="ce_trimestre" {{ request('periode') == 'ce_trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                    <option value="cette_annee" {{ request('periode') == 'cette_annee' ? 'selected' : '' }}>Cette année</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 border-t border-slate-100 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Date Début (Caravane)</label>
                <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Date Fin (Caravane)</label>
                <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition flex items-center justify-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Appliquer les filtres</span>
                </button>
                @if(request()->hasAny(['search', 'region', 'periode', 'date_debut', 'date_fin']))
                    <a href="{{ route('fiches.caravanes') }}" class="px-3 py-2 text-xs text-rose-600 font-semibold hover:underline">Réinitialiser</a>
                @endif
            </div>
        </div>

        <div class="text-[11px] text-slate-500 pt-1 flex items-center justify-between">
            <span>{{ $totalFiltered ?? $caravanes->total() }} caravane(s) trouvée(s)</span>
            @if(request()->filled('date_debut') || request()->filled('date_fin') || request()->filled('periode'))
                <span class="text-emerald-700 font-medium bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200">
                    Filtre période actif
                </span>
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
