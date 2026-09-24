<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration - Projet SENRM | Enda ECOPOP</title>
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
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full flex items-center justify-center p-4 bg-gradient-to-br from-slate-950 via-senrm-900 to-slate-950 text-slate-100">

<div class="w-full max-w-md">
    <!-- Card Container -->
    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl relative overflow-hidden">
        
        <!-- Decorative Glow -->
        <div class="absolute -top-16 -right-16 w-36 h-36 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-36 h-36 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Header Branding -->
        <div class="text-center mb-8 relative">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-senrm-700 to-emerald-500 shadow-lg shadow-emerald-900/50 mb-4 text-3xl">
                🔥
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white">Plateforme SENRM</h1>
            <p class="text-xs text-emerald-300 font-medium mt-1">Enda ECOPOP &bull; Banque Mondiale</p>
            <div class="inline-block mt-3 px-3 py-1 rounded-full bg-emerald-950/80 border border-emerald-500/40 text-[11px] font-semibold text-emerald-200">
                🔒 Espace Administration Centrale
            </div>
        </div>

        <!-- Flash Status Message -->
        @if (session('status'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-950/80 border border-emerald-500/50 text-emerald-200 text-xs flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-200 text-xs">
                <div class="font-bold flex items-center gap-2 mb-1 text-rose-300">
                    <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Erreur de connexion</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2">
                    Adresse Email Administrateur
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email', 'admin@senrm-enda.org') }}" required autofocus
                           class="w-full pl-11 pr-4 py-3 bg-slate-900/70 border border-white/20 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent text-sm transition"
                           placeholder="admin@senrm-enda.org">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2">
                    Mot de passe
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </span>
                    <input type="password" id="password" name="password" required
                           class="w-full pl-11 pr-4 py-3 bg-slate-900/70 border border-white/20 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent text-sm transition"
                           placeholder="••••••••">
                </div>
            </div>

            <!-- Remember me -->
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-300">
                    <input type="checkbox" name="remember" class="rounded bg-slate-800 border-white/20 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                    <span>Se souvenir de moi</span>
                </label>
                <span class="text-emerald-400/80 font-medium">Accès Restreint Admin</span>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-senrm-600 to-emerald-500 hover:from-senrm-500 hover:to-emerald-400 text-white font-bold rounded-xl shadow-lg shadow-emerald-900/50 hover:shadow-emerald-900/70 transition duration-150 flex items-center justify-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                <span>Accéder au Tableau de Bord</span>
            </button>
        </form>

        <!-- Information Notice -->
        <div class="mt-8 pt-6 border-t border-white/10 text-center">
            <p class="text-xs text-slate-400 leading-relaxed">
                <strong class="text-emerald-300 font-semibold">Note pour les agents de collecte :</strong><br>
                Les animateurs et superviseurs terrain collectent les données exclusivement via l'<strong>application mobile Ionic SENRM</strong>.
            </p>
        </div>
    </div>

    <!-- Footer Copyright -->
    <div class="mt-6 text-center text-[11px] text-slate-400">
        Projet SENRM &bull; Financement Banque Mondiale &bull; Coordination Enda ECOPOP
    </div>
</div>

</body>
</html>
