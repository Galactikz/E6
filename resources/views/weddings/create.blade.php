<x-layouts.dashboard title="Créer un mariage">
    <div class="max-w-2xl">
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">💍 Nouveau mariage</h2>

            <form method="POST" action="{{ route('weddings.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="label">Nom du mariage <span class="text-red-500">*</span></label>
                    <input name="name" type="text" required value="{{ old('name') }}"
                           placeholder="Mariage de Sophie & Pierre"
                           class="input @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Date du mariage</label>
                        <input name="wedding_date" type="date"
                               value="{{ old('wedding_date') }}"
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="input @error('wedding_date') border-red-500 @enderror">
                        @error('wedding_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Ville</label>
                        <input name="city" type="text"
                               value="{{ old('city') }}"
                               placeholder="Paris, Lyon, Marseille..."
                               class="input">
                    </div>
                </div>

                <div>
                    <label class="label">Lieu de réception</label>
                    <input name="venue_name" type="text"
                           value="{{ old('venue_name') }}"
                           placeholder="Château du Lac, Salle des Roses..."
                           class="input">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Nombre d'invités estimé</label>
                        <input name="guest_count" type="number" min="1" max="5000"
                               value="{{ old('guest_count') }}"
                               placeholder="100"
                               class="input">
                    </div>
                    <div>
                        <label class="label">Budget total prévu (€)</label>
                        <input name="total_budget" type="number" min="0" step="100"
                               value="{{ old('total_budget') }}"
                               placeholder="20000"
                               class="input">
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary">Créer mon mariage</button>
                    <a href="{{ route('dashboard') }}" class="btn-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
