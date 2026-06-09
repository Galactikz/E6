<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\WeddingController;
use App\Http\Controllers\Web\ProviderController;
use App\Http\Controllers\Web\SubscriptionController;
use App\Http\Controllers\Web\Auth\SocialAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes — Mariage Planner
|--------------------------------------------------------------------------
*/

// ── Public ─────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tarifs', [SubscriptionController::class, 'plans'])->name('pricing');

// Prestataires — SEO URLs
Route::get('/prestataires', [ProviderController::class, 'index'])->name('providers.index');
Route::get('/prestataires/{category}', [ProviderController::class, 'category'])->name('providers.category');
Route::get('/prestataires/{category}/{city}', [ProviderController::class, 'cityCategory'])->name('providers.city');
Route::get('/prestataires/{category}-{city}/{slug}', [ProviderController::class, 'show'])->name('providers.show');

// RSVP public
Route::get('/invitation/{token}', [App\Http\Controllers\Web\RsvpController::class, 'show'])->name('rsvp.show');
Route::post('/invitation/{token}', [App\Http\Controllers\Web\RsvpController::class, 'respond'])->name('rsvp.respond');

// Share links
Route::get('/partage/{token}', [App\Http\Controllers\Web\ShareController::class, 'show'])->name('share.show');

// ── Auth (Laravel Breeze or custom) ────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [App\Http\Controllers\Web\Auth\LoginController::class, 'show'])->name('login');
    Route::post('/connexion', [App\Http\Controllers\Web\Auth\LoginController::class, 'store'])->name('login.store');
    Route::get('/inscription', [App\Http\Controllers\Web\Auth\RegisterController::class, 'show'])->name('register');
    Route::post('/inscription', [App\Http\Controllers\Web\Auth\RegisterController::class, 'store'])->name('register.store');
});

// Social Auth
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

Route::post('/deconnexion', [App\Http\Controllers\Web\Auth\LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ── Authenticated ───────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/tableau-de-bord', [DashboardController::class, 'index'])->name('dashboard');

    // Mariages
    Route::prefix('mariages')->name('weddings.')->group(function () {
        Route::get('/', [WeddingController::class, 'index'])->name('index');
        Route::get('/nouveau', [WeddingController::class, 'create'])->name('create');
        Route::post('/', [WeddingController::class, 'store'])->name('store');

        Route::prefix('{wedding:slug}')->group(function () {
            Route::get('/', [WeddingController::class, 'show'])->name('show');
            Route::get('/budget', [App\Http\Controllers\Web\BudgetController::class, 'index'])->name('budget');
            Route::get('/checklist', [App\Http\Controllers\Web\ChecklistController::class, 'index'])->name('checklist');
            Route::get('/invites', [App\Http\Controllers\Web\GuestController::class, 'index'])->name('guests');
            Route::get('/plan-de-table', [App\Http\Controllers\Web\TablePlanController::class, 'index'])->name('table-plan');
            Route::get('/parametres', [WeddingController::class, 'edit'])->name('edit');
            Route::put('/parametres', [WeddingController::class, 'update'])->name('update');
        });
    });

    // Subscription
    Route::prefix('abonnement')->name('subscription.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::get('/succes', [SubscriptionController::class, 'success'])->name('success');
        Route::get('/annule', [SubscriptionController::class, 'cancel'])->name('cancel');
        Route::get('/portail', [SubscriptionController::class, 'portal'])->name('portal');
    });

    // Profile
    Route::get('/profil', [App\Http\Controllers\Web\ProfileController::class, 'show'])->name('profile');
    Route::put('/profil', [App\Http\Controllers\Web\ProfileController::class, 'update'])->name('profile.update');
});

// ── Sitemap ─────────────────────────────────────────────────────────────────
Route::get('/sitemap.xml', [App\Http\Controllers\Web\SitemapController::class, 'index'])->name('sitemap');
