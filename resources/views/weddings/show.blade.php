<x-layouts.dashboard title="{{ $wedding->name }}">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('weddings.budget', $wedding->slug) }}" class="card-hover group">
            <div class="text-3xl mb-2">💰</div>
            <h3 class="font-semibold text-gray-900">Budget</h3>
            <p class="text-sm text-gray-500">Gérer les dépenses</p>
        </a>
        <a href="{{ route('weddings.checklist', $wedding->slug) }}" class="card-hover group">
            <div class="text-3xl mb-2">✅</div>
            <h3 class="font-semibold text-gray-900">Checklist</h3>
            <p class="text-sm text-gray-500">Suivi des tâches</p>
        </a>
        <a href="{{ route('weddings.guests', $wedding->slug) }}" class="card-hover group">
            <div class="text-3xl mb-2">👥</div>
            <h3 class="font-semibold text-gray-900">Invités</h3>
            <p class="text-sm text-gray-500">Gérer les invitations</p>
        </a>
        <a href="{{ route('weddings.table-plan', $wedding->slug) }}" class="card-hover group">
            <div class="text-3xl mb-2">🪑</div>
            <h3 class="font-semibold text-gray-900">Plan de table</h3>
            <p class="text-sm text-gray-500">Organiser les places</p>
        </a>
    </div>

    <div class="card">
        <h3 class="font-semibold text-gray-900 mb-4">Informations du mariage</h3>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm text-gray-500">Date</dt>
                <dd class="font-medium text-gray-900">{{ $wedding->wedding_date?->format('d/m/Y') ?? 'Non définie' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Ville</dt>
                <dd class="font-medium text-gray-900">{{ $wedding->city ?? 'Non définie' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Lieu</dt>
                <dd class="font-medium text-gray-900">{{ $wedding->venue_name ?? 'Non défini' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Nombre d'invités estimé</dt>
                <dd class="font-medium text-gray-900">{{ $wedding->guest_count ?? 'Non défini' }}</dd>
            </div>
        </dl>
        <div class="mt-4">
            <a href="{{ route('weddings.edit', $wedding->slug) }}" class="btn-outline btn-sm">✏️ Modifier</a>
        </div>
    </div>
</x-layouts.dashboard>
