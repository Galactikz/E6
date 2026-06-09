<x-layouts.dashboard title="Tableau de bord">
    @if($weddings->isEmpty())
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center min-h-96 text-center">
            <div class="text-6xl mb-6">💍</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Créez votre premier mariage</h2>
            <p class="text-gray-500 mb-8">Commencez à planifier votre jour J dès aujourd'hui.</p>
            <a href="{{ route('weddings.create') }}" class="btn-primary btn-lg">
                + Créer mon mariage
            </a>
        </div>
    @else
        {{-- Dashboard grid --}}
        @if($dashboard)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Days countdown --}}
            <div class="card bg-gradient-to-br from-violet-500 to-violet-700 text-white">
                <p class="text-violet-100 text-sm">Jours restants</p>
                <p class="text-5xl font-bold mt-1">{{ max(0, $dashboard['days_until_wedding'] ?? '—') }}</p>
                <p class="text-violet-200 text-sm mt-1">avant votre grand jour</p>
            </div>

            {{-- Budget --}}
            <div class="card">
                <p class="text-gray-500 text-sm">Budget dépensé</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($dashboard['budget']['total_actual'], 0, ',', ' ') }}€</p>
                <p class="text-sm text-gray-400 mt-1">sur {{ number_format($dashboard['budget']['total_planned'], 0, ',', ' ') }}€ prévu</p>
                <div class="progress-bar h-2 mt-3">
                    <div class="progress-fill bg-violet-500" style="width: {{ min(100, $dashboard['budget']['percentage_used']) }}%"></div>
                </div>
            </div>

            {{-- Guests --}}
            <div class="card">
                <p class="text-gray-500 text-sm">Invités confirmés</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $dashboard['guests']['confirmed'] }}</p>
                <p class="text-sm text-gray-400 mt-1">sur {{ $dashboard['guests']['total'] }} invités</p>
                <div class="flex gap-2 mt-3">
                    <span class="badge-success">{{ $dashboard['guests']['confirmed'] }} oui</span>
                    <span class="badge-danger">{{ $dashboard['guests']['declined'] }} non</span>
                    <span class="badge-warning">{{ $dashboard['guests']['pending'] }} att.</span>
                </div>
            </div>

            {{-- Checklist --}}
            <div class="card">
                <p class="text-gray-500 text-sm">Tâches complétées</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $dashboard['checklist']['completion_percentage'] }}%</p>
                <p class="text-sm text-gray-400 mt-1">{{ $dashboard['checklist']['done'] }} / {{ $dashboard['checklist']['total'] }} tâches</p>
                <div class="progress-bar h-2 mt-3">
                    <div class="progress-fill bg-green-500" style="width: {{ $dashboard['checklist']['completion_percentage'] }}%"></div>
                </div>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card">
                <h3 class="font-semibold text-gray-900 mb-4">Actions rapides</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('weddings.budget', $activeWedding->slug) }}" class="flex items-center gap-2 p-3 rounded-xl bg-violet-50 hover:bg-violet-100 transition text-violet-700 text-sm font-medium">
                        💰 Budget
                    </a>
                    <a href="{{ route('weddings.checklist', $activeWedding->slug) }}" class="flex items-center gap-2 p-3 rounded-xl bg-green-50 hover:bg-green-100 transition text-green-700 text-sm font-medium">
                        ✅ Checklist
                    </a>
                    <a href="{{ route('weddings.guests', $activeWedding->slug) }}" class="flex items-center gap-2 p-3 rounded-xl bg-blue-50 hover:bg-blue-100 transition text-blue-700 text-sm font-medium">
                        👥 Invités
                    </a>
                    <a href="{{ route('weddings.table-plan', $activeWedding->slug) }}" class="flex items-center gap-2 p-3 rounded-xl bg-pink-50 hover:bg-pink-100 transition text-pink-700 text-sm font-medium">
                        🪑 Plan de table
                    </a>
                </div>
            </div>

            <div class="card">
                <h3 class="font-semibold text-gray-900 mb-4">Prochaines tâches urgentes</h3>
                @if($dashboard['checklist']['overdue'] > 0)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                        <p class="text-red-700 text-sm font-medium">⚠️ {{ $dashboard['checklist']['overdue'] }} tâche(s) en retard</p>
                    </div>
                @endif
                <a href="{{ route('weddings.checklist', $activeWedding->slug) }}" class="btn-outline w-full">
                    Voir la checklist complète
                </a>
            </div>
        </div>
        @endif
    @endif
</x-layouts.dashboard>
