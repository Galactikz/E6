<x-layouts.app>
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-violet-50 via-pink-50 to-white py-20 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-block bg-violet-100 text-violet-700 text-sm font-medium px-4 py-1.5 rounded-full mb-6">
                ✨ Planification de mariage simplifiée
            </span>
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                Organisez votre <span class="gradient-text">mariage parfait</span>
            </h1>
            <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
                Budget, checklist, invités, plan de table, prestataires — tout ce qu'il vous faut en une seule plateforme.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="btn-primary btn-lg">
                    Commencer gratuitement
                </a>
                <a href="{{ route('pricing') }}" class="btn-outline btn-lg">
                    Voir les tarifs
                </a>
            </div>
            <p class="text-sm text-gray-400 mt-4">Gratuit pour commencer. Pas de carte bancaire requise.</p>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="section-title">Tout pour votre grand jour</h2>
                <p class="section-subtitle">5 modules puissants pour une organisation sans stress</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach([
                    ['emoji' => '💰', 'title' => 'Budget Mariage', 'desc' => 'Suivez vos dépenses par catégorie, visualisez l\'état de votre budget en temps réel avec des graphiques clairs.'],
                    ['emoji' => '✅', 'title' => 'Checklist Intelligente', 'desc' => 'Modèles 6, 12 et 18 mois pré-remplis. Priorisez les tâches et suivez votre avancement étape par étape.'],
                    ['emoji' => '👥', 'title' => 'Gestion des Invités', 'desc' => 'Gérez vos listes, envoyez les invitations, collectez les RSVP et gérez les régimes alimentaires.'],
                    ['emoji' => '🪑', 'title' => 'Plan de Table', 'desc' => 'Créez votre plan de salle par drag & drop. Tables rondes et rectangulaires, visualisation intuitive.'],
                    ['emoji' => '🔍', 'title' => 'Annuaire Prestataires', 'desc' => 'Trouvez photographe, traiteur, DJ… par ville et catégorie. Des prestataires vérifiés partout en France.'],
                    ['emoji' => '📱', 'title' => 'Multi-plateforme', 'desc' => 'Accessible sur web, et prêt pour les applications mobiles iOS et Android grâce à notre API.'],
                ] as $feature)
                <div class="card-hover group">
                    <div class="text-4xl mb-4">{{ $feature['emoji'] }}</div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-gray-500">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pricing preview --}}
    <section class="py-20 px-4 bg-gray-50">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="section-title">Tarifs simples et transparents</h2>
            <p class="section-subtitle">Commencez gratuitement, évoluez quand vous le souhaitez</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12">
                @foreach($plans as $plan)
                <div class="card {{ !$plan->isFree() ? 'ring-2 ring-violet-500 relative' : '' }}">
                    @if(!$plan->isFree())
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                            <span class="bg-violet-500 text-white text-xs font-bold px-4 py-1 rounded-full">Recommandé</span>
                        </div>
                    @endif
                    <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                    <div class="my-4">
                        @if($plan->isFree())
                            <span class="text-4xl font-bold text-gray-900">Gratuit</span>
                        @else
                            <span class="text-4xl font-bold text-gray-900">{{ number_format($plan->price_monthly, 2, ',', ' ') }}€</span>
                            <span class="text-gray-500">/mois</span>
                        @endif
                    </div>
                    <ul class="space-y-3 text-left mb-6">
                        @foreach($plan->features ?? [] as $feature)
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ $plan->isFree() ? route('register') : route('pricing') }}"
                       class="{{ $plan->isFree() ? 'btn-outline' : 'btn-primary' }} w-full">
                        {{ $plan->isFree() ? 'Commencer gratuitement' : 'Passer à Premium' }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
