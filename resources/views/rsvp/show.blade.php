<x-layouts.app title="Invitation Mariage">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 bg-gradient-to-br from-violet-50 to-pink-50">
        <div class="w-full max-w-lg">
            <div class="card text-center">
                <div class="text-6xl mb-4">💍</div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Vous êtes invité(e) !
                </h1>
                <p class="text-gray-500 mb-6">
                    Au mariage de <strong>{{ $guest->wedding->name }}</strong>
                </p>
                @if($guest->wedding->wedding_date)
                    <div class="bg-violet-50 rounded-xl p-4 mb-6">
                        <p class="text-violet-700 font-semibold text-lg">
                            📅 {{ $guest->wedding->wedding_date->format('d F Y') }}
                        </p>
                        @if($guest->wedding->city)
                            <p class="text-violet-600 text-sm mt-1">📍 {{ $guest->wedding->city }}</p>
                        @endif
                    </div>
                @endif

                <p class="text-gray-600 mb-6">
                    Bonjour <strong>{{ $guest->first_name }}</strong>,<br>
                    Pouvez-vous confirmer votre présence ?
                </p>

                <form method="POST" action="{{ route('rsvp.respond', $guest->rsvp_token) }}" class="space-y-3">
                    @csrf
                    <button type="submit" name="status" value="confirmed"
                            class="btn-primary w-full btn-lg">
                        ✅ Oui, je serai présent(e) !
                    </button>
                    <button type="submit" name="status" value="declined"
                            class="btn-ghost w-full">
                        ❌ Je ne pourrai pas être là
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
