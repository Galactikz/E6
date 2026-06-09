<x-layouts.dashboard title="Budget Mariage" subtitle="{{ $wedding->name }}">
    <x-slot:headerActions>
        <a href="{{ route('weddings.show', $wedding->slug) }}" class="btn-ghost btn-sm">← Retour</a>
    </x-slot:headerActions>

    {{-- Summary cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card bg-gradient-to-br from-violet-500 to-violet-700 text-white">
            <p class="text-violet-100 text-sm">Budget prévu</p>
            <p class="text-4xl font-bold mt-1">{{ number_format($summary['total_planned'], 0, ',', ' ') }}€</p>
        </div>
        <div class="card bg-gradient-to-br from-pink-500 to-pink-700 text-white">
            <p class="text-pink-100 text-sm">Dépensé réel</p>
            <p class="text-4xl font-bold mt-1">{{ number_format($summary['total_actual'], 0, ',', ' ') }}€</p>
        </div>
        <div class="card {{ $summary['remaining'] >= 0 ? 'bg-gradient-to-br from-green-500 to-green-700' : 'bg-gradient-to-br from-red-500 to-red-700' }} text-white">
            <p class="text-green-100 text-sm">Restant</p>
            <p class="text-4xl font-bold mt-1">{{ number_format(abs($summary['remaining']), 0, ',', ' ') }}€</p>
            @if($summary['remaining'] < 0)
                <p class="text-red-100 text-xs mt-1">⚠️ Dépassement</p>
            @endif
        </div>
    </div>

    {{-- Global progress --}}
    <div class="card mb-8">
        <div class="flex justify-between items-center mb-2">
            <h3 class="font-semibold text-gray-900">Progression globale du budget</h3>
            <span class="text-2xl font-bold text-violet-600">{{ $summary['percentage_used'] }}%</span>
        </div>
        <div class="progress-bar h-4">
            <div class="progress-fill {{ $summary['percentage_used'] > 100 ? 'bg-red-500' : 'bg-violet-500' }}"
                 style="width: {{ min(100, $summary['percentage_used']) }}%"></div>
        </div>
    </div>

    {{-- Categories --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($summary['categories'] as $category)
        <div class="card" x-data="{ open: false }">
            <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background-color: {{ $category['color'] }}20">
                        <div class="w-3 h-3 rounded-full" style="background-color: {{ $category['color'] }}"></div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $category['name'] }}</h4>
                        <p class="text-sm text-gray-500">{{ number_format($category['actual_amount'], 0, ',', ' ') }}€ / {{ number_format($category['planned_amount'], 0, ',', ' ') }}€</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium {{ $category['percentage'] > 100 ? 'text-red-600' : 'text-violet-600' }}">
                        {{ $category['percentage'] }}%
                    </span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <div class="progress-bar h-2 mt-3">
                <div class="progress-fill" style="width: {{ min(100, $category['percentage']) }}%; background-color: {{ $category['color'] }}"></div>
            </div>
        </div>
        @endforeach
    </div>
</x-layouts.dashboard>
