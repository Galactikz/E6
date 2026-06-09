<x-layouts.app title="RSVP confirmé">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 bg-gradient-to-br from-violet-50 to-pink-50">
        <div class="w-full max-w-lg">
            <div class="card text-center">
                @if($guest->rsvp_status === 'confirmed')
                    <div class="text-6xl mb-4">🎉</div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Présence confirmée !</h1>
                    <p class="text-gray-500">
                        Super ! Nous avons bien enregistré votre réponse. À très bientôt, {{ $guest->first_name }} !
                    </p>
                @else
                    <div class="text-6xl mb-4">😢</div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Dommage...</h1>
                    <p class="text-gray-500">
                        Nous sommes désolés que vous ne puissiez pas être là. Merci de nous avoir prévenus, {{ $guest->first_name }}.
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
