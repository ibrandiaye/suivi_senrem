@extends('layouts.app')

@section('title', 'Tableau de Bord National')
@section('page_title', 'Tableau de Bord National & Indicateurs SENRM')

@section('content')

<!-- Filter Bar -->
<div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
        <div class="flex items-center gap-2">
            <span class="text-base">📊</span>
            <div>
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Filtrer les Indicateurs Nationaux</h4>
                <p class="text-[11px] text-slate-500">Personnalisez la période d'analyse (dates personnalisées ou raccourcis) et la région.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-[11px] text-slate-500">
            <span>Dernière synchro : <strong class="text-slate-800">{{ now()->format('d/m/Y H:i') }}</strong></span>
        </div>
    </div>

    <form method="GET" action="{{ route('dashboard') }}" class="space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Région -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Région</label>
                <select name="region" class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">Toutes les régions (14)</option>
                    @foreach($regionsList as $reg)
                        <option value="{{ $reg->nom }}" {{ $regionFilter == $reg->nom ? 'selected' : '' }}>{{ $reg->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Raccourci Période -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Période Prédéfinie</label>
                <select name="periode" class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">Historique complet ou dates manuelles</option>
                    <option value="ce_jour" {{ request('periode') == 'ce_jour' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="cette_semaine" {{ request('periode') == 'cette_semaine' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="ce_mois" {{ request('periode') == 'ce_mois' ? 'selected' : '' }}>Ce mois-ci (Mois en cours)</option>
                    <option value="dernier_mois" {{ request('periode') == 'dernier_mois' ? 'selected' : '' }}>Le mois dernier</option>
                    <option value="ce_trimestre" {{ request('periode') == 'ce_trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                    <option value="cette_annee" {{ request('periode') == 'cette_annee' ? 'selected' : '' }}>Cette année</option>
                </select>
            </div>

            <!-- Date Début -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Date Début</label>
                <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <!-- Date Fin -->
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Date Fin</label>
                <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full bg-slate-50 border border-slate-300 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100">
            <div class="flex items-center gap-2">
                <button type="submit" class="py-2 px-5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <span>Appliquer les filtres</span>
                </button>
                @if(request()->hasAny(['region', 'periode', 'date_debut', 'date_fin']))
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 text-xs text-rose-600 font-semibold hover:underline">Réinitialiser</a>
                @endif
            </div>

            <div class="flex items-center gap-2">
                @if(request()->filled('date_debut') || request()->filled('date_fin') || request()->filled('periode') || request()->filled('region'))
                    <span class="text-xs text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-200 font-medium">
                        ✓ Période / Région active
                    </span>
                    <a href="{{ route('exports.index', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-xl transition">
                        <span>📥 Exporter cette sélection</span>
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Macro Banner: Cible 100 000 Foyers Améliorés -->
<div class="bg-gradient-to-r from-senrm-900 via-senrm-800 to-emerald-800 text-white rounded-3xl p-6 md:p-8 shadow-xl relative overflow-hidden">
    <div class="absolute -right-10 -bottom-10 opacity-10 text-9xl select-none">🔥</div>
    
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-400/20 text-amber-300 border border-amber-400/30 rounded-full text-xs font-bold tracking-wide uppercase mb-3">
                ★ Objectif Stratégique Enda ECOPOP / Banque Mondiale
            </div>
            <h3 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                Diffusion de 100 000 Foyers Améliorés (FA)
            </h3>
            <p class="text-emerald-100/90 text-sm max-w-2xl mt-1">
                Réduire la pression sur les ressources forestières, promouvoir les énergies de cuisson propres et améliorer les conditions de vie des ménages, femmes et enfants au Sénégal.
            </p>
        </div>

        <div class="text-right flex-shrink-0 bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/20">
            <div class="text-xs text-emerald-200 font-bold uppercase tracking-wider">Total Réalisé</div>
            <div class="text-3xl md:text-4xl font-black text-amber-400 font-mono">{{ number_format($grandTotalFa, 0, ',', ' ') }}</div>
            <div class="text-xs text-emerald-200 mt-0.5">sur 100 000 FA ({{ $pourcentageCible }} %)</div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="mt-6">
        <div class="flex justify-between text-xs font-bold text-emerald-200 mb-2">
            <span>Progression globale</span>
            <span>{{ $pourcentageCible }}% atteint</span>
        </div>
        <div class="w-full bg-black/30 rounded-full h-4 p-0.5 overflow-hidden border border-white/10">
            <div class="bg-gradient-to-r from-amber-400 to-emerald-400 h-full rounded-full transition-all duration-1000 shadow-sm" style="width: {{ min(100, max(1, $pourcentageCible)) }}%"></div>
        </div>
    </div>
</div>

<!-- 4 Top KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Ventes Foyers</span>
                <span class="w-9 h-9 rounded-xl bg-emerald-50 text-senrm-700 flex items-center justify-center text-lg">🛒</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 font-mono">{{ number_format($grandTotalFa, 0, ',', ' ') }}</div>
            <div class="text-xs text-slate-500 mt-2 space-y-0.5">
                <div>&bull; Distributeurs : <strong class="text-slate-800">{{ $totalFaDistributeurs }}</strong></div>
                <div>&bull; Animations-Ventes : <strong class="text-slate-800">{{ $totalFaAnimations }}</strong></div>
                <div>&bull; Caravanes : <strong class="text-slate-800">{{ $totalFaCaravanes }}</strong></div>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 text-xs font-medium text-emerald-600">
            {{ $countVentes }} points de vente enregistrés
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Femmes Touchées (GPF)</span>
                <span class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-lg">🍲</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 font-mono">{{ number_format($totalFemmesDemo, 0, ',', ' ') }}</div>
            <div class="text-xs text-slate-500 mt-2 space-y-0.5">
                <div>&bull; Total présents : <strong class="text-slate-800">{{ $totalPresentsDemo }}</strong></div>
                <div>&bull; Hommes : <strong class="text-slate-800">{{ $totalHommesDemo }}</strong></div>
                <div>&bull; Démos culinaires : <strong class="text-slate-800">{{ $countDemonstrations }}</strong></div>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 text-xs font-medium text-pink-600">
            Adoption par les ménages & femmes
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Caravanes & Villages</span>
                <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">🚚</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 font-mono">{{ $totalVillagesTouches }}</div>
            <div class="text-xs text-slate-500 mt-2 space-y-0.5">
                <div>&bull; Caravanes déployées : <strong class="text-slate-800">{{ $countCaravanes }}</strong></div>
                <div>&bull; FA vendus en caravane : <strong class="text-slate-800">{{ $totalFaCaravanes }}</strong></div>
                <div>&bull; Animations marché : <strong class="text-slate-800">{{ $countAnimations }}</strong></div>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 text-xs font-medium text-amber-600">
            Sensibilisation de masse
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Radios & Leaders</span>
                <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">📻</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 font-mono">{{ $countLeaders }}</div>
            <div class="text-xs text-slate-500 mt-2 space-y-0.5">
                <div>&bull; Leaders d'opinion mobilisés : <strong class="text-slate-800">{{ $countLeaders }}</strong></div>
                <div>&bull; Émissions diffusées : <strong class="text-slate-800">{{ $countEmissions }}</strong></div>
                <div>&bull; Relais communautaires actifs</div>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 text-xs font-medium text-purple-600">
            Plaidoyer & communication sociale
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Chart 1: Répartition par combustible -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h4 class="text-base font-bold text-slate-800">Répartition par Combustible</h4>
                <p class="text-xs text-slate-500">Charbon vs Bois vs Bois/Charbon</p>
            </div>
            <span class="text-xs font-bold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">Impact Énergétique</span>
        </div>
        <div class="h-64 flex items-center justify-center">
            <canvas id="combustibleChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Répartition par Région -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h4 class="text-base font-bold text-slate-800">Diffusion par Région</h4>
                <p class="text-xs text-slate-500">Quantité totale de FA vendus par région</p>
            </div>
            <span class="text-xs font-bold px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg">Géographie</span>
        </div>
        <div class="h-64 flex items-center justify-center">
            <canvas id="regionChart"></canvas>
        </div>
    </div>
</div>

<!-- Tables Section: Top Models + Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Top Models (1 col) -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
        <div>
            <h4 class="text-base font-bold text-slate-800 mb-1">Modèles de Foyers les plus Diffusés</h4>
            <p class="text-xs text-slate-500 mb-4">Préférences des ménages bénéficiaires</p>

            <div class="divide-y divide-slate-100">
                @forelse($topFoyers as $idx => $foyer)
                    <div class="py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 text-xs font-bold flex items-center justify-center">
                                {{ $idx + 1 }}
                            </span>
                            <div>
                                <div class="text-xs font-bold text-slate-800">{{ $foyer->nom }}</div>
                                <div class="text-[11px] text-slate-400 font-medium">{{ $foyer->combustible }}</div>
                            </div>
                        </div>
                        <span class="text-xs font-black text-emerald-700 font-mono bg-emerald-50 px-2.5 py-1 rounded-lg">
                            {{ $foyer->quantite_totale }} FA
                        </span>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400 italic">Aucune vente détaillée pour le moment</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Sync Submissions (2 cols) -->
    <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h4 class="text-base font-bold text-slate-800">Dernières Activités Enregistrées</h4>
                <p class="text-xs text-slate-500">Flux des remontées des équipes terrain</p>
            </div>
            <a href="{{ route('fiches.ventes') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">Voir tout &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-y border-slate-100">
                    <tr>
                        <th class="py-3 px-3">Type</th>
                        <th class="py-3 px-3">Acteur / Structure</th>
                        <th class="py-3 px-3">Localisation</th>
                        <th class="py-3 px-3">Date</th>
                        <th class="py-3 px-3 text-right">FA Vendus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentVentes as $v)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-3"><span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-medium">Vente</span></td>
                        <td class="py-3 px-3 font-semibold text-slate-800">{{ $v->distributeur_nom }}</td>
                        <td class="py-3 px-3 text-slate-600">{{ $v->commune }} ({{ $v->region }})</td>
                        <td class="py-3 px-3 text-slate-500">{{ $v->date_vente->format('d/m/Y') }}</td>
                        <td class="py-3 px-3 text-right font-mono font-bold text-slate-900">{{ $v->total_fa_vendus }}</td>
                    </tr>
                    @endforeach

                    @foreach($recentAnimations as $a)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-3"><span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-medium">Animation</span></td>
                        <td class="py-3 px-3 font-semibold text-slate-800">{{ $a->animateur_nom }}</td>
                        <td class="py-3 px-3 text-slate-600">{{ $a->commune }} ({{ $a->region }})</td>
                        <td class="py-3 px-3 text-slate-500">{{ $a->date_animation->format('d/m/Y') }}</td>
                        <td class="py-3 px-3 text-right font-mono font-bold text-slate-900">{{ $a->nb_fa_vendus }}</td>
                    </tr>
                    @endforeach

                    @foreach($recentDemonstrations as $d)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-3"><span class="px-2 py-0.5 rounded-full bg-pink-100 text-pink-800 font-medium">Démo GPF</span></td>
                        <td class="py-3 px-3 font-semibold text-slate-800">{{ $d->gpf_nom }}</td>
                        <td class="py-3 px-3 text-slate-600">{{ $d->commune }} ({{ $d->region }})</td>
                        <td class="py-3 px-3 text-slate-500">{{ $d->date_demonstration->format('d/m/Y') }}</td>
                        <td class="py-3 px-3 text-right text-pink-600 font-semibold">{{ $d->nb_femmes }} femmes</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Chart 1 : Combustibles
    const combustibleData = @json($ventesParCombustible);
    const cLabels = Object.keys(combustibleData);
    const cValues = Object.values(combustibleData);

    new Chart(document.getElementById('combustibleChart'), {
        type: 'doughnut',
        data: {
            labels: cLabels.length ? cLabels : ['Charbon', 'Bois', 'Polyvalent'],
            datasets: [{
                data: cValues.length ? cValues : [45, 39, 16],
                backgroundColor: ['#16a34a', '#eab308', '#3b82f6'],
                borderWidth: 2,
                borderColor: '#ffffff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Chart 2 : Régions
    const regionData = @json($ventesParRegion);
    const rLabels = Object.keys(regionData);
    const rValues = Object.values(regionData);

    new Chart(document.getElementById('regionChart'), {
        type: 'bar',
        data: {
            labels: rLabels.length ? rLabels : ['Thiès', 'Kaolack', 'Fatick', 'Ziguinchor', 'Tambacounda'],
            datasets: [{
                label: 'FA diffusés',
                data: rValues.length ? rValues : [62, 53, 45, 48, 84],
                backgroundColor: '#15803d',
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush
