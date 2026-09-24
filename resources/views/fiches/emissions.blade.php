@extends('layouts.app')

@section('title', 'Suivi des Émissions Radios')
@section('page_title', 'Fiche de Suivi de la Diffusion des Émissions Radio')

@section('content')

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h3 class="text-xl font-bold text-slate-900">Diffusion des Émissions Radios Communautaires</h3>
        <p class="text-xs text-slate-500">Conforme à la fiche FICHE DE SUIVI DES EMISSIONS RADIOS_SENRM.docx</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('exports.download', 'emissions') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Exporter en Excel (.xlsx)</span>
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
    <form method="GET" action="{{ route('fiches.emissions') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Recherche (Radio, animateur, thème...)</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Radio, animateur, thème..." class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
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
                <a href="{{ route('fiches.emissions') }}" class="px-3 py-2 text-xs text-rose-600 font-semibold hover:underline">Réinitialiser</a>
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
                    <th class="py-3 px-4">Station Radio & Commune</th>
                    <th class="py-3 px-4">Thème Abordé</th>
                    <th class="py-3 px-4">Format & Lieu</th>
                    <th class="py-3 px-4">Animateur(rice)</th>
                    <th class="py-3 px-4">Participants en Plateau</th>
                    <th class="py-3 px-4">Responsable Radio</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($emissions as $e)
                <tr class="hover:bg-slate-50/60">
                    <td class="py-3 px-4 font-medium text-slate-500 whitespace-nowrap">
                        <div class="font-bold text-slate-800">{{ $e->date_emission ? $e->date_emission->format('d/m/Y') : '-' }}</div>
                        <div class="text-[11px] text-slate-400 font-mono">{{ $e->heure_debut }} &rarr; {{ $e->heure_fin }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="font-bold text-slate-900">{{ $e->radio_nom }}</div>
                        <div class="text-[11px] text-slate-500">{{ $e->commune }} ({{ $e->region }}) &bull; <strong class="text-emerald-700 font-mono">{{ $e->radio_frequence }}</strong></div>
                    </td>
                    <td class="py-3 px-4 max-w-xs text-slate-700">
                        {{ $e->theme }}
                    </td>
                    <td class="py-3 px-4">
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold {{ $e->format == 'direct' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700' }}">
                            {{ strtoupper($e->format) }}
                        </span>
                        <div class="text-[10px] text-slate-400 mt-0.5">{{ ucfirst($e->lieu) }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="font-semibold text-slate-800">{{ $e->user ? $e->user->name : $e->animateur_nom }}</div>
                        <div class="text-[10px] text-slate-400 font-mono">{{ $e->user ? $e->user->email : '' }}</div>
                        <div class="text-[10px] text-amber-600 font-medium">📍 Région : {{ $e->user ? $e->user->region : $e->region }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="space-y-1">
                            @foreach($e->participants as $p)
                                <div class="text-[11px] text-slate-700">
                                    <span class="font-bold">{{ $p->prenom_nom }}</span>
                                    <span class="text-slate-400">({{ $p->sexe }}, {{ $p->profession }})</span>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="py-3 px-4 font-medium text-slate-700">
                        {{ $e->responsable_radio_nom ?: '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-xs text-slate-400 italic">
                        Aucune émission radio enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-slate-100">
        {{ $emissions->links() }}
    </div>
</div>

@endsection
