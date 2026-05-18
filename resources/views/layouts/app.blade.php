<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- @vite() charge les assets compilés par Vite (CSS + JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- @yield('title') sera remplacé par le titre de chaque page --}}
    <title>@yield('title', 'SmartExpense') — ExpenseFlow</title>

    {{-- Chart.js pour les graphiques --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

{{-- x-data="{ sidebarOpen: true }" = Alpine.js : état local de la sidebar --}}
<body class="bg-gray-100 font-sans" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen overflow-hidden">

        {{-- ============ SIDEBAR ============ --}}
        {{-- x-show="sidebarOpen" = Alpine affiche/cache selon la variable --}}
        <aside x-show="sidebarOpen"
               class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0">

            {{-- Logo --}}
            <div class="flex items-center justify-center h-16 bg-gray-800 border-b border-gray-700">
                <span class="text-xl font-bold text-blue-400">💰 ExpenseFlow</span>
            </div>

            {{-- Infos utilisateur connecté --}}
            <div class="flex items-center gap-3 px-4 py-4 border-b border-gray-700">
                <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center font-bold text-sm">
                    {{-- substr() = premier caractère du nom --}}
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                    {{-- getRoleNames() = méthode Spatie qui retourne les rôles --}}
                    <p class="text-xs text-gray-400">{{ Auth::user()->getRoleNames()->first() }}</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-1">

                {{-- request()->routeIs('dashboard') = true si on est sur le dashboard --}}
                {{-- Sert à mettre en surbrillance le lien actif --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    📊 Dashboard
                </a>

                <a href="{{ route('expenses.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs('expenses.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    💸 Dépenses
                </a>

                <a href="{{ route('revenues.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs('revenues.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    💰 Recettes
                </a>

                <a href="{{ route('categories.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    🏷️ Catégories
                </a>

                {{-- @can() vérifie la permission Spatie --}}
                {{-- Seul l'admin voit ce lien --}}
                @can('view users')
                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    👥 Utilisateurs
                </a>
                @endcan

            </nav>

            {{-- Bouton déconnexion en bas de sidebar --}}
            <div class="px-3 py-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    {{-- @csrf génère automatiquement le token CSRF caché --}}
                    {{-- En Symfony c'est {{ csrf_token() }} dans le form --}}
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-300 hover:bg-red-600 hover:text-white transition">
                        🚪 Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        {{-- ============ CONTENU PRINCIPAL ============ --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Topbar --}}
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm">
                {{-- Bouton toggle sidebar avec Alpine.js --}}
                {{-- @click="sidebarOpen = !sidebarOpen" inverse la variable Alpine --}}
                <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Titre de la page courante --}}
                <h1 class="text-lg font-semibold text-gray-800">
                    @yield('page-title', 'Dashboard')
                </h1>

                <div class="text-sm text-gray-500">
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </header>

            {{-- Zone de contenu scrollable --}}
            <main class="flex-1 overflow-y-auto p-6">

                {{-- Messages flash de succès --}}
                {{-- session('success') = message envoyé avec ->with('success','...') --}}
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                {{-- Messages flash d'erreur --}}
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                {{-- @yield('content') = endroit où chaque page injecte son contenu --}}
                {{-- Équivalent de {{ block('content') }} en Twig --}}
                @yield('content')

            </main>
        </div>
    </div>

    {{-- @stack('scripts') = endroit où @push('scripts') injecte le JS --}}
    @stack('scripts')


</body>
</html>