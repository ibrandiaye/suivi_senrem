<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Suivi des Indicateurs') - Projet SENRM | Enda ECOPOP</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        senrm: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        gold: {
                            500: '#eab308',
                            600: '#ca8a04',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full font-sans antialiased text-slate-800" x-data="{ sidebarOpen: false }">

<div class="min-h-full flex flex-col lg:flex-row">
    <!-- Sidebar Navigation -->
    <aside class="w-full lg:w-72 bg-gradient-to-b from-senrm-900 to-senrm-800 text-white flex-shrink-0 flex flex-col justify-between shadow-xl">
        <div>
            <!-- Project Header / Logo -->
            <div class="p-6 border-b border-senrm-700/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-white/10 flex items-center justify-center font-black text-xl text-emerald-400 border border-white/20">
                        🔥
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight text-white leading-tight">SENRM Suivi</h1>
                        <p class="text-xs text-emerald-300 font-medium">Enda ECOPOP &bull; Banque Mondiale</p>
                    </div>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-white/70 hover:text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <!-- Global Target Micro Banner -->
            <div class="mx-4 my-4 p-3 bg-emerald-950/60 rounded-xl border border-emerald-500/30">
                <div class="flex justify-between items-center text-xs font-semibold text-emerald-200 mb-1">
                    <span>Objectif National</span>
                    <span class="text-amber-400 font-bold">100 000 FA</span>
                </div>
                <div class="text-[11px] text-emerald-300/80">Stratégie de diffusion de foyers améliorés au Sénégal</div>
            </div>

            <!-- Navigation Links -->
            <nav class="px-3 py-2 space-y-1" :class="sidebarOpen ? 'block' : 'hidden lg:block'">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white shadow-sm font-bold' : 'text-emerald-100/80 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Tableau de Bord</span>
                </a>

                <div class="pt-4 pb-1 px-4 text-xs font-bold uppercase tracking-wider text-emerald-300/60">
                    Fiches de Suivi Terrain
                </div>

                <a href="{{ route('fiches.ventes') }}" 
                   class="flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('fiches.ventes') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/5 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-base">🛒</span>
                        <span>Ventes Distributeurs</span>
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-white/10 text-emerald-300 font-mono">{{ \App\Models\FicheVentesDistributeur::count() }}</span>
                </a>

                <a href="{{ route('fiches.animations') }}" 
                   class="flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('fiches.animations') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/5 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-base">📢</span>
                        <span>Animations - Ventes</span>
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-white/10 text-emerald-300 font-mono">{{ \App\Models\FicheAnimationVente::count() }}</span>
                </a>

                <a href="{{ route('fiches.demonstrations') }}" 
                   class="flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('fiches.demonstrations') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/5 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-base">🍲</span>
                        <span>Démos Culinaires (GPF)</span>
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-white/10 text-emerald-300 font-mono">{{ \App\Models\FicheDemonstrationCulinaire::count() }}</span>
                </a>

                <a href="{{ route('fiches.caravanes') }}" 
                   class="flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('fiches.caravanes') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/5 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-base">🚚</span>
                        <span>Caravanes Sensibilisation</span>
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-white/10 text-emerald-300 font-mono">{{ \App\Models\FicheCaravane::count() }}</span>
                </a>

                <a href="{{ route('fiches.emissions') }}" 
                   class="flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('fiches.emissions') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/5 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-base">📻</span>
                        <span>Émissions Radios</span>
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-white/10 text-emerald-300 font-mono">{{ \App\Models\FicheEmissionsRadio::count() }}</span>
                </a>

                <a href="{{ route('fiches.leaders') }}" 
                   class="flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('fiches.leaders') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/5 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-base">👤</span>
                        <span>Leaders d'Opinion</span>
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-white/10 text-emerald-300 font-mono">{{ \App\Models\FicheLeaderOpinion::count() }}</span>
                </a>

                <div class="pt-4 pb-1 px-4 text-xs font-bold uppercase tracking-wider text-emerald-300/60">
                    Administration
                </div>

                <a href="{{ route('users.index') }}" 
                   class="flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('users.*') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/5 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="text-base">👥</span>
                        <span>Utilisateurs Mobiles</span>
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-white/10 text-emerald-300 font-mono">{{ \App\Models\User::count() }}</span>
                </a>

                <div class="pt-4 pb-1 px-4 text-xs font-bold uppercase tracking-wider text-emerald-300/60">
                    Outils & Rapports
                </div>

                <a href="{{ route('exports.index') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('exports.*') ? 'bg-white/15 text-white' : 'text-emerald-100/80 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Exports Excel & Rapports</span>
                </a>
            </nav>
        </div>

        <!-- Sync Health & Footer -->
        <div class="p-4 border-t border-senrm-700/50 bg-black/10">
            <div class="flex items-center justify-between text-xs text-emerald-200">
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>API Mobile Sync Active</span>
                </span>
                <span class="font-mono text-emerald-400">v1.0</span>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col overflow-y-auto">
        <!-- Top Navbar -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shadow-sm sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-500 hover:text-slate-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h2 class="text-xl font-bold text-slate-800">@yield('page_title', 'Tableau de Bord National')</h2>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg text-xs font-semibold">
                    <span>🇸🇳 Sénégal &bull; 14 Régions</span>
                </div>
                
                @auth
                <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                    <div class="w-9 h-9 rounded-full bg-senrm-700 text-white font-bold flex items-center justify-center text-xs shadow">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <div class="text-xs font-bold text-slate-800">{{ auth()->user()->name }}</div>
                        <div class="text-[11px] text-emerald-600 font-medium">Administrateur SENRM</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-2">
                        @csrf
                        <button type="submit" 
                                title="Se déconnecter" 
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-700 text-xs font-semibold border border-slate-200 hover:border-rose-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="hidden md:inline">Déconnexion</span>
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </header>

        <!-- Page Dynamic Body -->
        <div class="p-6 md:p-8 space-y-8 flex-1">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 px-6 py-4 text-center text-xs text-slate-500">
            Projet de Gestion des Ressources Naturelles au Sénégal (SENRM) &bull; Financement Banque Mondiale &bull; Appui à la diffusion de 100 000 foyers améliorés par Enda ECOPOP
        </footer>
    </main>
</div>

@stack('scripts')
</body>
</html>
