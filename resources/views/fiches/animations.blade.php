@extends('layouts.app')

@section('title', 'Suivi des Animations-Ventes')
@section('page_title', 'Fiche de Suivi des Animations - Ventes')

@section('content')

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h3 class="text-xl font-bold text-slate-900">Animations-Ventes sur les Marchés & Places</h3>
        <p class="text-xs text-slate-500">Conforme à la fiche FICHE DE SUIVI ANIMATION-VENTE_SENRM.docx</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('exports.download', 'animations') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Exporter en Excel (.xlsx)</span>
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
    <form method="GET" action="{{ route('fiches.animations') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Recherche (Animateur, distributeur...)</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom animateur, commune..." class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
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
                <a href="{{ route('fiches.animations') }}" class="px-3 py-2 text-xs text-rose-600 font-semibold hover:underline">Réinitialiser</a>
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
                    <th class="py-3 px-4">Date</th>
                    <th class="py-3 px-4">Localisation & Lieu</th>
                    <th class="py-3 px-4">Distributeur Bénéficiaire</th>
                    <th class="py-3 px-4">Animateur(rice)</th>
                    <th class="py-3 px-4">Matériel Utilisé</th>
                    <th class="py-3 px-4 text-right">FA Vendus</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($animations as $a)
                <tr class="hover:bg-slate-50/60">
                    <td class="py-3 px-4 font-medium text-slate-500 whitespace-nowrap">
                        {{ $a->date_animation ? $a->date_animation->format('d/m/Y') : '-' }}
                    </td>
                    <td class="py-3 px-4 text-slate-700">
                        <div class="font-bold">{{ $a->lieu_animation }}</div>
                        <div class="text-[11px] text-slate-500">{{ $a->commune }} ({{ $a->village_quartier }}), Région de {{ $a->region }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="font-semibold text-slate-800">{{ $a->distributeur_nom }}</div>
                        <div class="text-[11px] text-slate-500 font-mono">{{ $a->distributeur_contact }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="font-semibold text-slate-800">{{ $a->user ? $a->user->name : $a->animateur_nom }}</div>
                        <div class="text-[11px] text-slate-500 font-mono">{{ $a->user ? $a->user->email : $a->animateur_contact }}</div>
                        <div class="text-[10px] text-amber-600 font-medium">📍 Région : {{ $a->user ? $a->user->region : $a->region }}</div>
                    </td>
                    <td class="py-3 px-4 text-slate-600 max-w-xs truncate">
                        {{ $a->materiel_utilise ?: 'Non précisé' }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        <span class="px-3 py-1 rounded-xl bg-blue-100 text-blue-900 font-mono font-black text-sm">
                            {{ $a->nb_fa_vendus }} FA
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-xs text-slate-400 italic">
                        Aucune animation-vente enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-slate-100">
        {{ $animations->links() }}
    </div>
</div>

@endsection
