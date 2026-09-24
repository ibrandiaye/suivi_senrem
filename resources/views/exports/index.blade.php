@extends('layouts.app')

@section('title', 'Exports Excel & Rapports')
@section('page_title', 'Centre d\'Exportation des Données & Rapports')

@section('content')

<div class="max-w-5xl space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Téléchargement & Exports Filtrés</h3>
            <p class="text-xs text-slate-500">Générez des extractions Excel (.xlsx) par période, région ou exportez l'historique complet pour chaque indicateur de la stratégie SENRM.</p>
        </div>
    </div>

    <!-- Filtres Globaux d'Exportation par Période -->
    <div class="bg-gradient-to-r from-emerald-950 via-senrm-900 to-slate-950 text-white p-6 rounded-3xl shadow-lg border border-emerald-800/40 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-xl">
                🗓️
            </div>
            <div>
                <h4 class="font-bold text-sm text-emerald-100">Définir une Période ou une Région d'Export</h4>
                <p class="text-[11px] text-emerald-300/80">Les boutons de téléchargement ci-dessous appliqueront instantanément cette période sélectionnée.</p>
            </div>
        </div>

        <form id="exportFiltersForm" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-slate-900">
            <!-- Raccourci Période -->
            <div>
                <label class="block text-[11px] font-bold text-emerald-200 uppercase tracking-wider mb-1.5">Période Rapide</label>
                <select id="filterPeriode" class="w-full bg-slate-900/90 border border-white/20 text-white text-xs rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    <option value="">Toutes les dates (Historique complet)</option>
                    <option value="ce_jour">Aujourd'hui</option>
                    <option value="cette_semaine">Cette semaine</option>
                    <option value="ce_mois">Ce mois-ci (Mois en cours)</option>
                    <option value="dernier_mois">Le mois dernier</option>
                    <option value="ce_trimestre">Ce trimestre (Trimestre en cours)</option>
                    <option value="cette_annee">Cette année civile</option>
                </select>
            </div>

            <!-- Date Début -->
            <div>
                <label class="block text-[11px] font-bold text-emerald-200 uppercase tracking-wider mb-1.5">Date Début</label>
                <input type="date" id="filterDateDebut" class="w-full bg-slate-900/90 border border-white/20 text-white text-xs rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
            </div>

            <!-- Date Fin -->
            <div>
                <label class="block text-[11px] font-bold text-emerald-200 uppercase tracking-wider mb-1.5">Date Fin</label>
                <input type="date" id="filterDateFin" class="w-full bg-slate-900/90 border border-white/20 text-white text-xs rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
            </div>

            <!-- Région -->
            <div>
                <label class="block text-[11px] font-bold text-emerald-200 uppercase tracking-wider mb-1.5">Région</label>
                <select id="filterRegion" class="w-full bg-slate-900/90 border border-white/20 text-white text-xs rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    <option value="">Toutes les régions</option>
                    @foreach($regions as $r)
                        <option value="{{ $r->nom }}">{{ $r->nom }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="mt-4 pt-4 border-t border-white/10 flex flex-wrap items-center justify-between gap-3 text-xs">
            <div id="filterSummaryText" class="text-emerald-200/90 font-medium flex items-center gap-1.5">
                <span>📌</span>
                <span>Mode actuel : <strong>Exportation globale (aucune restriction de date)</strong></span>
            </div>
            <button type="button" id="btnResetFilters" class="text-[11px] font-semibold text-rose-300 hover:text-rose-200 underline">
                Réinitialiser les critères
            </button>
        </div>
    </div>

    <!-- Grille des 6 Exports Officiels -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Export 1 : Ventes Distributeurs -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col justify-between hover:border-emerald-500/40 transition">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl font-black">
                        🛒
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Ventes Distributeurs (10 modèles FA)</h4>
                        <p class="text-[11px] text-slate-500">Conforme à Fiche suivi distributeurs.xlsx</p>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-5 leading-relaxed">
                    Extraction exhaustive des ventes de foyers (JEEG, JABOOT, JONGOMA, SAKKANAL, TAARU), coordonnées clients, stocks, identification de l'agent collecteur et région.
                </p>
            </div>
            <a href="{{ route('exports.download', 'ventes-distributeurs') }}" data-base-url="{{ route('exports.download', 'ventes-distributeurs') }}" class="export-link w-full py-3 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl text-center shadow-sm flex items-center justify-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Télécharger Excel Ventes (.xlsx)</span>
            </a>
        </div>

        <!-- Export 2 : Animations-Ventes -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col justify-between hover:border-emerald-500/40 transition">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-2xl font-black">
                        📢
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Animations - Ventes</h4>
                        <p class="text-[11px] text-slate-500">Marchés hebdomadaires, loumas et places publiques</p>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-5 leading-relaxed">
                    Animations réalisées sur le terrain, matériel déployé, distributeurs bénéficiaires, nombre de foyers vendus lors de la séance, animateurs et signatures.
                </p>
            </div>
            <a href="{{ route('exports.download', 'animations') }}" data-base-url="{{ route('exports.download', 'animations') }}" class="export-link w-full py-3 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl text-center shadow-sm flex items-center justify-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Télécharger Excel Animations (.xlsx)</span>
            </a>
        </div>

        <!-- Export 3 : Démonstrations Culinaires -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col justify-between hover:border-emerald-500/40 transition">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-700 flex items-center justify-center text-2xl font-black">
                        🍲
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Démonstrations Culinaires (GPF)</h4>
                        <p class="text-[11px] text-slate-500">Groupements féminins & indicateurs genre</p>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-5 leading-relaxed">
                    Rapports des séances pratiques, présidentes de GPF, effectifs désagrégés hommes/femmes, recettes préparées, tests comparatifs FA vs foyer traditionnel et consentements.
                </p>
            </div>
            <a href="{{ route('exports.download', 'demonstrations') }}" data-base-url="{{ route('exports.download', 'demonstrations') }}" class="export-link w-full py-3 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl text-center shadow-sm flex items-center justify-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Télécharger Excel Démonstrations (.xlsx)</span>
            </a>
        </div>

        <!-- Export 4 : Caravanes -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col justify-between hover:border-emerald-500/40 transition">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center text-2xl font-black">
                        🚚
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Caravanes Mobiles de Sensibilisation</h4>
                        <p class="text-[11px] text-slate-500">Villages sillonnés & logistique déployée</p>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-5 leading-relaxed">
                    Itinéraires empruntés, horaires, logistique (véhicules, sonos, banderoles), villages traversés, affiches/dépliants distribués et ventes réalisées.
                </p>
            </div>
            <a href="{{ route('exports.download', 'caravanes') }}" data-base-url="{{ route('exports.download', 'caravanes') }}" class="export-link w-full py-3 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl text-center shadow-sm flex items-center justify-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Télécharger Excel Caravanes (.xlsx)</span>
            </a>
        </div>

        <!-- Export 5 : Émissions Radios -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col justify-between hover:border-emerald-500/40 transition">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-2xl font-black">
                        📻
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Émissions Radios Communautaires</h4>
                        <p class="text-[11px] text-slate-500">Radios partenaires & plateaux débats</p>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-5 leading-relaxed">
                    Stations de radio, fréquences FM, dates et heures de diffusion, thèmes débattus (santé, énergie, environnement) et liste nominative des intervenants en plateau.
                </p>
            </div>
            <a href="{{ route('exports.download', 'emissions') }}" data-base-url="{{ route('exports.download', 'emissions') }}" class="export-link w-full py-3 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl text-center shadow-sm flex items-center justify-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Télécharger Excel Radios (.xlsx)</span>
            </a>
        </div>

        <!-- Export 6 : Leaders d'Opinion -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col justify-between hover:border-emerald-500/40 transition">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center text-2xl font-black">
                        👤
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Leaders d'Opinion & Influenceurs</h4>
                        <p class="text-[11px] text-slate-500">Cartographie des acteurs locaux influents</p>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-5 leading-relaxed">
                    Répertoire des personnalités ressources identifiées, fonctions, entités d'ancrage communautaire, zones d'influence géographique et contacts téléphoniques.
                </p>
            </div>
            <a href="{{ route('exports.download', 'leaders') }}" data-base-url="{{ route('exports.download', 'leaders') }}" class="export-link w-full py-3 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl text-center shadow-sm flex items-center justify-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Télécharger Excel Leaders (.xlsx)</span>
            </a>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selPeriode = document.getElementById('filterPeriode');
        const inpDateDebut = document.getElementById('filterDateDebut');
        const inpDateFin = document.getElementById('filterDateFin');
        const selRegion = document.getElementById('filterRegion');
        const summaryText = document.getElementById('filterSummaryText');
        const btnReset = document.getElementById('btnResetFilters');
        const exportLinks = document.querySelectorAll('.export-link');

        function updateExportLinks() {
            const periode = selPeriode.value;
            const dateDebut = inpDateDebut.value;
            const dateFin = inpDateFin.value;
            const region = selRegion.value;

            const params = new URLSearchParams();
            if (dateDebut) params.append('date_debut', dateDebut);
            if (dateFin) params.append('date_fin', dateFin);
            if (!dateDebut && !dateFin && periode) params.append('periode', periode);
            if (region) params.append('region', region);

            const queryString = params.toString() ? ('?' + params.toString()) : '';

            exportLinks.forEach(link => {
                const baseUrl = link.getAttribute('data-base-url');
                link.href = baseUrl + queryString;
            });

            // Update summary description
            let text = 'Exportation globale (aucune restriction)';
            if (dateDebut || dateFin) {
                const d1 = dateDebut ? ('du ' + dateDebut) : '';
                const d2 = dateFin ? ('au ' + dateFin) : '';
                text = `Période filtrée : ${d1} ${d2}`.trim();
            } else if (periode) {
                const labels = {
                    'ce_jour': "Aujourd'hui",
                    'cette_semaine': "Cette semaine",
                    'ce_mois': "Ce mois-ci",
                    'dernier_mois': "Le mois dernier",
                    'ce_trimestre': "Ce trimestre",
                    'cette_annee': "Cette année"
                };
                text = `Période : ${labels[periode] || periode}`;
            }

            if (region) {
                text += ` &bull; Région : <strong>${region}</strong>`;
            }

            summaryText.innerHTML = `<span>📌</span><span>Filtre appliqué : <strong>${text}</strong></span>`;
        }

        // Écouteurs de changement
        selPeriode.addEventListener('change', function () {
            if (this.value) {
                // Si l'utilisateur choisit un raccourci, on peut vider les dates directes
                inpDateDebut.value = '';
                inpDateFin.value = '';
            }
            updateExportLinks();
        });

        inpDateDebut.addEventListener('change', function () {
            if (this.value) selPeriode.value = '';
            updateExportLinks();
        });

        inpDateFin.addEventListener('change', function () {
            if (this.value) selPeriode.value = '';
            updateExportLinks();
        });

        selRegion.addEventListener('change', updateExportLinks);

        btnReset.addEventListener('click', function () {
            selPeriode.value = '';
            inpDateDebut.value = '';
            inpDateFin.value = '';
            selRegion.value = '';
            updateExportLinks();
        });
    });
</script>

@endsection
