<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Mariage Planner') }}</title>
    <meta name="description" content="{{ $description ?? 'Organisez votre mariage parfait avec Mariage Planner' }}">
    @if(isset($canonical))
    <link rel="canonical" href="{{ $canonical }}">
    @endif
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $description ?? 'Organisez votre mariage parfait' }}">
    <meta property="og:type" content="website">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 font-sans">

    {{-- Navigation --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <span class="text-2xl">💍</span>
                        <span class="text-xl font-bold gradient-text">Mariage Planner</span>
                    </a>
                </div>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-1">
                    @auth
                        <a href="{{ route('dashboard') }}" class="nav-link">Tableau de bord</a>
                        @if($activeWedding = auth()->user()->weddings()->first())
                            <a href="{{ route('weddings.budget', $activeWedding->slug) }}" class="nav-link">Budget</a>
                            <a href="{{ route('weddings.checklist', $activeWedding->slug) }}" class="nav-link">Checklist</a>
                            <a href="{{ route('weddings.guests', $activeWedding->slug) }}" class="nav-link">Invités</a>
                            <a href="{{ route('weddings.table-plan', $activeWedding->slug) }}" class="nav-link">Plan de table</a>
                        @endif
                        <a href="{{ route('providers.index') }}" class="nav-link">Prestataires</a>
                    @else
                        <a href="{{ route('providers.index') }}" class="nav-link">Prestataires</a>
                        <a href="{{ route('pricing') }}" class="nav-link">Tarifs</a>
                    @endauth
                </div>

                {{-- User menu --}}
                <div class="flex items-center gap-3">
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition">
                                <div class="w-8 h-8 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 font-semibold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Mon profil</a>
                                <a href="{{ route('subscription.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Abonnement</a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost btn-sm">Connexion</a>
                        <a href="{{ route('register') }}" class="btn-primary btn-sm">Commencer gratuitement</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-20 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-20 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg">
        {{ session('error') }}
    </div>
    @endif

    {{-- Main content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-2xl">💍</span>
                        <span class="text-xl font-bold gradient-text">Mariage Planner</span>
                    </div>
                    <p class="text-gray-500 text-sm">La plateforme tout-en-un pour organiser votre mariage parfait.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">Fonctionnalités</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-violet-600">Budget</a></li>
                        <li><a href="#" class="hover:text-violet-600">Checklist</a></li>
                        <li><a href="#" class="hover:text-violet-600">Invités</a></li>
                        <li><a href="#" class="hover:text-violet-600">Plan de table</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-3">À propos</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="{{ route('pricing') }}" class="hover:text-violet-600">Tarifs</a></li>
                        <li><a href="{{ route('providers.index') }}" class="hover:text-violet-600">Prestataires</a></li>
                        <li><a href="#" class="hover:text-violet-600">Mentions légales</a></li>
                        <li><a href="#" class="hover:text-violet-600">Confidentialité</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200 text-sm text-gray-400 text-center">
                © {{ date('Y') }} Mariage Planner. Tous droits réservés.
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
