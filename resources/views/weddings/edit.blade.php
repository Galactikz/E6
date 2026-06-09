<x-layouts.dashboard title="Paramètres" subtitle="{{ $wedding->name }}">
    <div class="max-w-2xl">
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">⚙️ Paramètres du mariage</h2>

            <form method="POST" action="{{ route('weddings.update', $wedding->slug) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="label">Nom du mariage</label>
                    <input name="name" type="text" value="{{ old('name', $wedding->name) }}" class="input" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Date du mariage</label>
                        <input name="wedding_date" type="date"
                               value="{{ old('wedding_date', $wedding->wedding_date?->format('Y-m-d')) }}"
                               class="input">
                    </div>
                    <div>
                        <label class="label">Ville</label>
                        <input name="city" type="text" value="{{ old('city', $wedding->city) }}" class="input">
                    </div>
                </div>

                <div>
                    <label class="label">Lieu de réception</label>
                    <input name="venue_name" type="text" value="{{ old('venue_name', $wedding->venue_name) }}" class="input">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Nombre d'invités</label>
                        <input name="guest_count" type="number" value="{{ old('guest_count', $wedding->guest_count) }}" class="input">
                    </div>
                    <div>
                        <label class="label">Budget total (€)</label>
                        <input name="total_budget" type="number" value="{{ old('total_budget', $wedding->total_budget) }}" class="input">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                    <a href="{{ route('weddings.show', $wedding->slug) }}" class="btn-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
