<x-layouts.dashboard title="Plan de Table" subtitle="{{ $wedding->name }}">
    <x-slot:headerActions>
        <a href="{{ route('weddings.show', $wedding->slug) }}" class="btn-ghost btn-sm">← Retour</a>
    </x-slot:headerActions>

    @if($plan)
    {{-- Stats bar --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="card text-center">
            <p class="text-2xl font-bold text-violet-600">{{ $plan->tables->count() }}</p>
            <p class="text-sm text-gray-500">Tables</p>
        </div>
        <div class="card text-center">
            <p class="text-2xl font-bold text-green-600">{{ $plan->seated_guests_count }}</p>
            <p class="text-sm text-gray-500">Invités placés</p>
        </div>
        <div class="card text-center">
            <p class="text-2xl font-bold text-orange-500">{{ $unassignedGuests->count() }}</p>
            <p class="text-sm text-gray-500">Non placés</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Unassigned guests --}}
        <div class="card lg:col-span-1">
            <h3 class="font-semibold text-gray-900 mb-3">Invités à placer</h3>
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @forelse($unassignedGuests as $guest)
                <div class="flex items-center gap-2 p-2 rounded-lg bg-gray-50 border border-gray-200 cursor-grab"
                     data-guest-id="{{ $guest->id }}" data-guest-name="{{ $guest->full_name }}">
                    <div class="w-8 h-8 rounded-full bg-violet-100 flex items-center justify-center text-violet-700 text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($guest->first_name, 0, 1)) }}
                    </div>
                    <span class="text-sm text-gray-700 truncate">{{ $guest->full_name }}</span>
                </div>
                @empty
                <p class="text-gray-400 text-sm text-center py-4">Tous les invités sont placés ! 🎉</p>
                @endforelse
            </div>
        </div>

        {{-- Table plan canvas --}}
        <div class="lg:col-span-3">
            <div class="card min-h-96 relative overflow-hidden"
                 style="background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 24px 24px;">
                @foreach($plan->tables as $table)
                <div class="absolute" style="left: {{ ($table->position['x'] ?? 50) }}px; top: {{ ($table->position['y'] ?? 50) }}px;">
                    <div class="relative">
                        @if($table->shape === 'round')
                        <div class="rounded-full border-4 flex items-center justify-center cursor-pointer hover:shadow-lg transition"
                             style="width: 100px; height: 100px; border-color: {{ $table->color }}; background-color: {{ $table->color }}20">
                            <div class="text-center">
                                <p class="text-xs font-bold" style="color: {{ $table->color }}">{{ $table->name }}</p>
                                <p class="text-xs text-gray-500">{{ $table->seats->whereNotNull('guest_id')->count() }}/{{ $table->capacity }}</p>
                            </div>
                        </div>
                        @else
                        <div class="rounded-xl border-4 flex items-center justify-center cursor-pointer hover:shadow-lg transition"
                             style="width: 140px; height: 70px; border-color: {{ $table->color }}; background-color: {{ $table->color }}20">
                            <div class="text-center">
                                <p class="text-xs font-bold" style="color: {{ $table->color }}">{{ $table->name }}</p>
                                <p class="text-xs text-gray-500">{{ $table->seats->whereNotNull('guest_id')->count() }}/{{ $table->capacity }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                @if($plan->tables->isEmpty())
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <p class="text-gray-400 mb-2">Aucune table créée</p>
                        <a href="#" class="btn-primary btn-sm">+ Ajouter une table</a>
                    </div>
                </div>
                @endif
            </div>
            <p class="text-xs text-gray-400 mt-2 text-center">
                💡 Le drag & drop interactif est disponible via l'API. Les positions sont sauvegardées automatiquement.
            </p>
        </div>
    </div>
    @else
    <div class="card text-center py-12">
        <div class="text-6xl mb-4">🪑</div>
        <h2 class="text-xl font-semibold text-gray-900 mb-2">Aucun plan de table créé</h2>
        <p class="text-gray-500 mb-6">Créez votre premier plan de table pour organiser vos invités.</p>
        <a href="#" class="btn-primary">Créer un plan de table</a>
    </div>
    @endif
</x-layouts.dashboard>
