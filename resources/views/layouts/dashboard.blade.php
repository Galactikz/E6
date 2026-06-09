<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Tableau de bord' }} — Mariage Planner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 font-sans" x-data="{ sidebarOpen: false }">

<div class="flex h-full">
    {{-- Sidebar --}}
    <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-white border-r border-gray-200">
        <div class="flex flex-col flex-1 min-h-0 overflow-y-auto">
            {{-- Logo --}}
            <div class="flex items-center gap-2 px-6 py-5 border-b border-gray-200">
                <span class="text-2xl">💍</span>
                <span class="text-lg font-bold gradient-text">Mariage Planner</span>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-1">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'nav-link-active' : 'nav-link' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Tableau de bord
                </a>

                @if($wedding = auth()->user()->weddings()->first())
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $wedding->name }}</p>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('weddings.budget', $wedding->slug) }}" class="{{ request()->routeIs('weddings.budget') ? 'nav-link-active' : 'nav-link' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Budget
                        </a>
                        <a href="{{ route('weddings.checklist', $wedding->slug) }}" class="{{ request()->routeIs('weddings.checklist') ? 'nav-link-active' : 'nav-link' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Checklist
                        </a>
                        <a href="{{ route('weddings.guests', $wedding->slug) }}" class="{{ request()->routeIs('weddings.guests') ? 'nav-link-active' : 'nav-link' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Invités
                        </a>
                        <a href="{{ route('weddings.table-plan', $wedding->slug) }}" class="{{ request()->routeIs('weddings.table-plan') ? 'nav-link-active' : 'nav-link' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Plan de table
                        </a>
                    </div>
                </div>
                @endif

                <div class="pt-4 border-t border-gray-200">
                    <a href="{{ route('providers.index') }}" class="nav-link">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Prestataires
                    </a>
                    <a href="{{ route('subscription.index') }}" class="nav-link">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        Abonnement
                    </a>
                </div>
            </nav>

            {{-- User --}}
            <div class="px-4 py-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 font-semibold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        @if(auth()->user()->isPremium())
                            <span class="badge-purple text-xs">Premium</span>
                        @else
                            <span class="text-xs text-gray-400">Plan Gratuit</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">{{ $title ?? 'Tableau de bord' }}</h1>
                @if(isset($subtitle))
                    <p class="text-sm text-gray-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                {{ $headerActions ?? '' }}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-ghost btn-sm text-red-500 hover:text-red-700 hover:bg-red-50">
                        Déconnexion
                    </button>
                </form>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-6">
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
                 class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
