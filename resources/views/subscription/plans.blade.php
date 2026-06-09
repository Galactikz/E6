<x-layouts.app title="Tarifs">
    <div class="py-20 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h1 class="section-title">Choisissez votre plan</h1>
                <p class="section-subtitle">Commencez gratuitement, évoluez selon vos besoins</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($plans as $plan)
                <div class="card {{ !$plan->isFree() ? 'ring-2 ring-violet-500 relative' : '' }}">
                    @if(!$plan->isFree())
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                            <span class="bg-gradient-to-r from-violet-500 to-pink-500 text-white text-sm font-bold px-5 py-1.5 rounded-full shadow">
                                ⭐ Le plus populaire
                            </span>
                        </div>
                    @endif

                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h2>
                        <p class="text-gray-500 text-sm mt-1">{{ $plan->description }}</p>
                    </div>

                    <div class="mb-6">
                        @if($plan->isFree())
                            <span class="text-5xl font-bold text-gray-900">Gratuit</span>
                        @else
                            <div class="flex items-end gap-2">
                                <span class="text-5xl font-bold text-gray-900">{{ number_format($plan->price_monthly, 2, ',', ' ') }}€</span>
                                <span class="text-gray-500 mb-1">/mois</span>
                            </div>
                            <p class="text-sm text-green-600 font-medium mt-1">
                                Ou {{ number_format($plan->price_yearly, 2, ',', ' ') }}€/an (économisez 33%)
                            </p>
                        @endif
                    </div>

                    <ul class="space-y-3 mb-8">
                        @foreach($plan->features ?? [] as $feature)
                            <li class="flex items-center gap-3 text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    @auth
                        <a href="{{ $plan->isFree() ? route('dashboard') : route('subscription.index') }}"
                           class="{{ $plan->isFree() ? 'btn-outline' : 'btn-primary' }} w-full btn-lg">
                            {{ $plan->isFree() ? 'Continuer gratuitement' : 'Passer à Premium' }}
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="{{ $plan->isFree() ? 'btn-outline' : 'btn-primary' }} w-full btn-lg">
                            {{ $plan->isFree() ? 'Commencer gratuitement' : 'Essayer Premium' }}
                        </a>
                    @endauth
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
