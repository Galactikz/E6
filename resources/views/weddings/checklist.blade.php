<x-layouts.dashboard title="Checklist Mariage" subtitle="{{ $wedding->name }}">
    <x-slot:headerActions>
        <a href="{{ route('weddings.show', $wedding->slug) }}" class="btn-ghost btn-sm">← Retour</a>
    </x-slot:headerActions>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="card text-center">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-sm text-gray-500">Total</p>
        </div>
        <div class="card text-center">
            <p class="text-3xl font-bold text-green-600">{{ $stats['done'] }}</p>
            <p class="text-sm text-gray-500">Terminées</p>
        </div>
        <div class="card text-center">
            <p class="text-3xl font-bold text-orange-500">{{ $stats['pending'] }}</p>
            <p class="text-sm text-gray-500">En attente</p>
        </div>
        <div class="card text-center">
            <p class="text-3xl font-bold text-red-500">{{ $stats['overdue'] }}</p>
            <p class="text-sm text-gray-500">En retard</p>
        </div>
    </div>

    {{-- Global progress --}}
    <div class="card mb-8">
        <div class="flex justify-between items-center mb-2">
            <h3 class="font-semibold text-gray-900">Avancement</h3>
            <span class="text-2xl font-bold text-green-600">{{ $stats['completion_percentage'] }}%</span>
        </div>
        <div class="progress-bar h-4">
            <div class="progress-fill bg-green-500" style="width: {{ $stats['completion_percentage'] }}%"></div>
        </div>
    </div>

    {{-- Tasks list --}}
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900">Tâches ({{ $tasks->count() }})</h3>
            <div class="flex gap-2">
                @if($templates->count())
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="btn-outline btn-sm">📋 Appliquer un modèle</button>
                    <div x-show="open" @click.outside="open = false"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                        @foreach($templates as $template)
                        <form method="POST" action="{{ route('weddings.checklist', $wedding->slug) }}">
                            @csrf
                            <input type="hidden" name="template_id" value="{{ $template->id }}">
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                {{ $template->name }}
                            </button>
                        </form>
                        @endforeach
                    </div>
                </div>
                @endif
                <a href="#" class="btn-primary btn-sm" onclick="alert('Utilisez l\'API pour ajouter des tâches')">+ Ajouter</a>
            </div>
        </div>

        <div class="space-y-2">
            @forelse($tasks as $task)
            <div class="flex items-center gap-4 p-4 rounded-xl border {{ $task->status === 'done' ? 'border-green-200 bg-green-50' : ($task->due_date && $task->due_date->isPast() ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-white') }} hover:shadow-sm transition">
                <form method="POST" action="/api/v1/weddings/{{ $wedding->id }}/checklist/{{ $task->id }}/toggle" class="flex-shrink-0">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="checkbox"
                           {{ $task->status === 'done' ? 'checked' : '' }}
                           onchange="this.form.submit()"
                           class="w-5 h-5 rounded border-gray-300 text-green-600 cursor-pointer">
                </form>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-900 {{ $task->status === 'done' ? 'line-through text-gray-400' : '' }}">
                        {{ $task->title }}
                    </p>
                    @if($task->due_date)
                        <p class="text-xs text-gray-500 mt-0.5">
                            📅 {{ $task->due_date->format('d/m/Y') }}
                            @if($task->due_date->isPast() && $task->status !== 'done')
                                <span class="text-red-500 font-medium">— En retard !</span>
                            @endif
                        </p>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    @if($task->category)
                        <span class="badge-info text-xs">{{ $task->category }}</span>
                    @endif
                    <span class="badge text-xs {{ match($task->priority) { 'high' => 'bg-red-100 text-red-700', 'medium' => 'bg-yellow-100 text-yellow-700', default => 'bg-gray-100 text-gray-600' } }}">
                        {{ match($task->priority) { 'high' => '🔴', 'medium' => '🟡', default => '🟢' } }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <p class="text-gray-400 mb-4">Aucune tâche créée.</p>
                <p class="text-sm text-gray-400">Appliquez un modèle pour démarrer rapidement !</p>
            </div>
            @endforelse
        </div>
    </div>
</x-layouts.dashboard>
