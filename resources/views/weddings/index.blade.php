<x-layouts.dashboard title="Mes Mariages">
    <x-slot:headerActions>
        <a href="{{ route('weddings.create') }}" class="btn-primary btn-sm">+ Nouveau mariage</a>
    </x-slot:headerActions>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($weddings as $wedding)
        <a href="{{ route('weddings.show', $wedding->slug) }}" class="card-hover group">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-900 group-hover:text-violet-600 transition">{{ $wedding->name }}</h3>
                    @if($wedding->wedding_date)
                        <p class="text-sm text-gray-500">📅 {{ $wedding->wedding_date->format('d/m/Y') }}</p>
                    @endif
                    @if($wedding->city)
                        <p class="text-sm text-gray-500">📍 {{ $wedding->city }}</p>
                    @endif
                </div>
                <span class="text-2xl">💍</span>
            </div>
            <div class="flex gap-4 text-sm text-gray-500">
                <span>👥 {{ $wedding->guests_count }} invités</span>
                <span>✅ {{ $wedding->checklist_tasks_count }} tâches</span>
            </div>
        </a>
        @empty
        <div class="col-span-3 text-center py-16">
            <div class="text-6xl mb-4">💍</div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Aucun mariage créé</h2>
            <a href="{{ route('weddings.create') }}" class="btn-primary">Créer mon mariage</a>
        </div>
        @endforelse
    </div>
</x-layouts.dashboard>
