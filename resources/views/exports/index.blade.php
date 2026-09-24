@extends('layouts.app')

@section('title', 'Exports Excel & Rapports')
@section('page_title', 'Centre d\'Exportation des Données & Rapports')

@section('content')

<div class="max-w-4xl space-y-6">
    <div>
        <h3 class="text-xl font-bold text-slate-900">Téléchargement des Fichiers & Indicateurs</h3>
        <p class="text-xs text-slate-500">Générez et téléchargez directement des fichiers Excel (.xlsx) complets pour chaque indicateur de la stratégie SENRM.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Export 1 -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">🛒</span>
                    <div>
                        <h4 class="font-bold text-slate-900">Ventes Distributeurs (10 types de FA)</h4>
                        <p class="text-xs text-slate-500">Conforme au modèle Fiche suivi distributeurs.xlsx</p>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-4">
                    Comprend la ventilation complète des ventes par type de foyer (JEEG, JABOOT, JONGOMA, SAKKANAL, TAARU), adresses clients, contacts distributeurs.
                </p>
            </div>
            <a href="{{ route('exports.download', 'ventes-distributeurs') }}" class="w-full py-2.5 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl text-center shadow-sm transition">
                Télécharger Excel (.xlsx)
            </a>
        </div>

        <!-- Export 2 -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">📢</span>
                    <div>
                        <h4 class="font-bold text-slate-900">Animations - Ventes</h4>
                        <p class="text-xs text-slate-500">Marchés hebdomadaires et places publiques</p>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-4">
                    Liste détaillée des animations réalisées, matériel utilisé, animateurs mobilisés et nombre de foyers améliorés vendus sur place.
                </p>
            </div>
            <a href="{{ route('exports.download', 'animations') }}" class="w-full py-2.5 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl text-center shadow-sm transition">
                Télécharger Excel (.xlsx)
            </a>
        </div>

        <!-- Export 3 -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">🍲</span>
                    <div>
                        <h4 class="font-bold text-slate-900">Démonstrations Culinaires (GPF)</h4>
                        <p class="text-xs text-slate-500">Indicateurs genre et consentement ménages</p>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-4">
                    Données complètes sur les GPF, présidentes, statistiques de présence disagrégées hommes/femmes et autorisations d'images.
                </p>
            </div>
            <a href="{{ route('exports.download', 'demonstrations') }}" class="w-full py-2.5 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl text-center shadow-sm transition">
                Télécharger Excel (.xlsx)
            </a>
        </div>

        <!-- Export 4 -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">🚚</span>
                    <div>
                        <h4 class="font-bold text-slate-900">Caravanes de Sensibilisation</h4>
                        <p class="text-xs text-slate-500">Villages sillonnés & logistique</p>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-4">
                    Itinéraires, horaires, moyens déployés (sonorisation, véhicules, motos), supports de communication distribués et FA vendus.
                </p>
            </div>
            <a href="{{ route('exports.download', 'caravanes') }}" class="w-full py-2.5 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl text-center shadow-sm transition">
                Télécharger Excel (.xlsx)
            </a>
        </div>

        <!-- Export 5 -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">📻</span>
                    <div>
                        <h4 class="font-bold text-slate-900">Émissions Radios</h4>
                        <p class="text-xs text-slate-500">Radios communautaires & participants</p>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-4">
                    Radios partenaires, fréquences FM, dates, thématiques de sensibilisation traitées et répertoire des intervenants en plateau.
                </p>
            </div>
            <a href="{{ route('exports.download', 'emissions') }}" class="w-full py-2.5 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl text-center shadow-sm transition">
                Télécharger Excel (.xlsx)
            </a>
        </div>

        <!-- Export 6 -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-3xl">👤</span>
                    <div>
                        <h4 class="font-bold text-slate-900">Leaders d'Opinion</h4>
                        <p class="text-xs text-slate-500">Cartographie des acteurs influents</p>
                    </div>
                </div>
                <p class="text-xs text-slate-600 mb-4">
                    Répertoire complet des leaders communautaires, religieux, coutumiers, fonctions occupées et territoires sous influence.
                </p>
            </div>
            <a href="{{ route('exports.download', 'leaders') }}" class="w-full py-2.5 px-4 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl text-center shadow-sm transition">
                Télécharger Excel (.xlsx)
            </a>
        </div>
    </div>
</div>

@endsection
