<?php

namespace App\Providers;

use App\Contracts\NotificationProviderInterface;
use App\Services\BudgetService;
use App\Services\ChecklistService;
use App\Services\GuestService;
use App\Services\ProviderService;
use App\Services\SocialAuthService;
use App\Services\SubscriptionService;
use App\Services\TablePlanService;
use App\Services\WeddingService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BudgetService::class);
        $this->app->singleton(ChecklistService::class);
        $this->app->singleton(GuestService::class);
        $this->app->singleton(TablePlanService::class);
        $this->app->singleton(ProviderService::class);
        $this->app->singleton(SocialAuthService::class);
        $this->app->singleton(SubscriptionService::class);

        $this->app->singleton(WeddingService::class, function ($app) {
            return new WeddingService(
                $app->make(BudgetService::class),
                $app->make(ChecklistService::class),
            );
        });

        // NotificationProvider — can be swapped to Twilio, Brevo, etc.
        $this->app->bind(NotificationProviderInterface::class, function () {
            return null; // No SMS provider configured by default
        });
    }

    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        RateLimiter::for('public', function (Request $request) {
            return Limit::perMinute(100)->by($request->ip());
        });
    }
}
