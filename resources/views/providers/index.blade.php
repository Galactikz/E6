<x-layouts.app title="Annuaire Prestataires Mariage">
    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h1 class="section-title">Annuaire Prestataires</h1>
                <p class="section-subtitle">Trouvez les meilleurs professionnels pour votre mariage</p>
            </div>

            {{-- Filters --}}
            <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="label">Catégorie</label>
                        <select name="category" class="input">
                            <option value="">Toutes catégories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ ($filters['category'] ?? '') === $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Ville</label>
                        <input type="text" name="city" value="{{ $filters['city'] ?? '' }}"
                               placeholder="Ex: Marseille" class="input">
                    </div>
                    <div>
                        <label class="label">Budget max</label>
                        <input type="number" name="price_max" value="{{ $filters['price_max'] ?? '' }}"
                               placeholder="5000" class="input">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full">Rechercher</button>
                    </div>
                </div>
            </form>

            {{-- Results --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($providers as $provider)
                <div class="card-hover">
                    @if($provider->gallery && count($provider->gallery) > 0)
                        <img src="{{ $provider->gallery[0] }}" alt="{{ $provider->name }}"
                             class="w-full h-40 object-cover rounded-xl mb-4">
                    @else
                        <div class="w-full h-40 bg-gradient-to-br from-violet-100 to-pink-100 rounded-xl mb-4 flex items-center justify-center">
                            <span class="text-4xl">📸</span>
                        </div>
                    @endif

                    <div class="flex items-start justify-between">
                        <div>
                            <span class="badge-purple text-xs mb-1">{{ $provider->category->name }}</span>
                            <h3 class="font-semibold text-gray-900">{{ $provider->name }}</h3>
                            <p class="text-sm text-gray-500">📍 {{ $provider->city }}</p>
                        </div>
                        @if($provider->is_verified)
                            <span class="badge-success text-xs">✓ Vérifié</span>
                        @endif
                    </div>

                    @if($provider->price_from)
                        <p class="text-sm text-violet-600 font-medium mt-2">
                            À partir de {{ number_format($provider->price_from, 0, ',', ' ') }}€
                        </p>
                    @endif

                    <a href="{{ route('providers.show', [$provider->category->slug, \Illuminate\Support\Str::slug($provider->city), $provider->slug]) }}"
                       class="btn-outline w-full mt-4 btn-sm">
                        Voir la fiche
                    </a>
                </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-400">Aucun prestataire trouvé pour ces critères.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $providers->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
