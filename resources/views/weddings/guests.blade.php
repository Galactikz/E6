<x-layouts.dashboard title="Gestion des Invités" subtitle="{{ $wedding->name }}">
    <x-slot:headerActions>
        <a href="{{ route('weddings.show', $wedding->slug) }}" class="btn-ghost btn-sm">← Retour</a>
    </x-slot:headerActions>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="card text-center">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-sm text-gray-500">Total invités</p>
        </div>
        <div class="card text-center">
            <p class="text-3xl font-bold text-green-600">{{ $stats['confirmed'] }}</p>
            <p class="text-sm text-gray-500">Confirmés</p>
        </div>
        <div class="card text-center">
            <p class="text-3xl font-bold text-red-500">{{ $stats['declined'] }}</p>
            <p class="text-sm text-gray-500">Déclinés</p>
        </div>
        <div class="card text-center">
            <p class="text-3xl font-bold text-yellow-500">{{ $stats['pending'] }}</p>
            <p class="text-sm text-gray-500">En attente</p>
        </div>
    </div>

    {{-- Meal summary --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="card text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['adults'] }}</p>
            <p class="text-sm text-gray-500">🍽️ Adultes</p>
        </div>
        <div class="card text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['children'] }}</p>
            <p class="text-sm text-gray-500">🍼 Enfants</p>
        </div>
        <div class="card text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['with_dietary'] }}</p>
            <p class="text-sm text-gray-500">🥗 Régimes</p>
        </div>
    </div>

    {{-- Groups --}}
    @if($groups->count())
    <div class="card mb-6">
        <h3 class="font-semibold text-gray-900 mb-4">Groupes d'invités</h3>
        <div class="flex flex-wrap gap-3">
            @foreach($groups as $group)
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200">
                <div class="w-3 h-3 rounded-full" style="background-color: {{ $group->color }}"></div>
                <span class="text-sm font-medium text-gray-700">{{ $group->name }}</span>
                <span class="badge-info text-xs">{{ $group->guests_count }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Livewire component placeholder --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900">Liste des invités</h3>
            <div class="flex gap-2">
                <a href="#" class="btn-outline btn-sm">📤 Importer CSV</a>
                <a href="#" class="btn-primary btn-sm">+ Ajouter un invité</a>
            </div>
        </div>
        <p class="text-gray-400 text-center py-8">
            Utilisez l'API ou les composants Livewire pour gérer vos invités de manière interactive.
        </p>
    </div>
</x-layouts.dashboard>
