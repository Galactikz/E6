<x-layouts.dashboard title="Mon Profil">
    <div class="max-w-2xl space-y-6">
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations personnelles</h2>
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="label">Nom</label>
                    <input name="name" type="text" value="{{ old('name', $user->name) }}" class="input" required>
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" value="{{ $user->email }}" class="input bg-gray-50" disabled>
                    <p class="text-xs text-gray-400 mt-1">L'email ne peut pas être modifié ici.</p>
                </div>
                <div>
                    <label class="label">Téléphone</label>
                    <input name="phone" type="tel" value="{{ old('phone', $user->phone) }}" class="input" placeholder="+33 6 00 00 00 00">
                </div>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </form>
        </div>

        <div class="card">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Abonnement actuel</h2>
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-900">
                        @if($user->isPremium())
                            <span class="badge-purple">Premium</span>
                        @else
                            <span class="badge">Plan Gratuit</span>
                        @endif
                    </p>
                </div>
                <a href="{{ route('subscription.index') }}" class="btn-outline btn-sm">Gérer l'abonnement</a>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
