<x-layouts.dashboard title="Mon Abonnement">
    <div class="max-w-4xl">
        {{-- Current plan --}}
        <div class="card mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Plan actuel</h2>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $currentPlan->name }}</p>
                    @if(!$currentPlan->isFree())
                        <p class="text-green-600 font-medium">{{ number_format($currentPlan->price_monthly, 2, ',', ' ') }}€/mois</p>
                    @else
                        <p class="text-gray-500">Gratuit</p>
                    @endif
                </div>
                @if(!$currentPlan->isFree())
                    <a href="{{ route('subscription.portal') }}" class="btn-outline btn-sm">Gérer via Stripe</a>
                @endif
            </div>
        </div>

        {{-- Plans comparison --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($plans as $plan)
            <div class="card {{ $plan->id === $currentPlan->id ? 'ring-2 ring-violet-500' : '' }}">
                @if($plan->id === $currentPlan->id)
                    <div class="badge-purple mb-3">Plan actuel</div>
                @endif

                <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                <div class="my-3">
                    @if($plan->isFree())
                        <span class="text-3xl font-bold text-gray-900">Gratuit</span>
                    @else
                        <span class="text-3xl font-bold text-gray-900">{{ number_format($plan->price_monthly, 2, ',', ' ') }}€</span>
                        <span class="text-gray-500">/mois</span>
                    @endif
                </div>

                <ul class="space-y-2 mb-6">
                    @foreach($plan->features ?? [] as $feature)
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>

                @if($plan->id !== $currentPlan->id && !$plan->isFree())
                    <form method="POST" action="/api/v1/subscription/checkout" id="checkout-{{ $plan->id }}">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="billing_cycle" value="monthly">
                        <button type="button"
                                onclick="startCheckout({{ $plan->id }})"
                                class="btn-primary w-full">
                            Passer à {{ $plan->name }}
                        </button>
                    </form>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</x-layouts.dashboard>
