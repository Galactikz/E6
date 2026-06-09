<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BudgetController;
use App\Http\Controllers\Api\V1\ChecklistController;
use App\Http\Controllers\Api\V1\GuestController;
use App\Http\Controllers\Api\V1\ProviderController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\TablePlanController;
use App\Http\Controllers\Api\V1\WeddingController;
use App\Http\Controllers\Api\V1\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Mariage Planner v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // ── Auth (public) ──────────────────────────────────────────────────────
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::get('/social/{provider}/redirect', [AuthController::class, 'socialRedirect'])->name('social.redirect');
        Route::post('/social/{provider}/callback', [AuthController::class, 'socialCallback'])->name('social.callback');
    });

    // ── RSVP public endpoint ───────────────────────────────────────────────
    Route::post('/rsvp/{token}', [GuestController::class, 'rsvp'])->name('rsvp');

    // ── Providers (public) ─────────────────────────────────────────────────
    Route::prefix('providers')->name('providers.')->group(function () {
        Route::get('/', [ProviderController::class, 'index'])->name('index');
        Route::get('/categories', [ProviderController::class, 'categories'])->name('categories');
        Route::get('/{provider:slug}', [ProviderController::class, 'show'])->name('show');
    });

    // ── Subscription Plans (public) ────────────────────────────────────────
    Route::get('/plans', [SubscriptionController::class, 'plans'])->name('plans');

    // ── Stripe Webhook (signature-verified, no auth) ───────────────────────
    Route::post('/webhooks/stripe', [WebhookController::class, 'stripe'])
        ->name('webhooks.stripe')
        ->withoutMiddleware(['auth:sanctum']);

    // ── Authenticated routes ───────────────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');

        // Subscription
        Route::prefix('subscription')->name('subscription.')->group(function () {
            Route::get('/current', [SubscriptionController::class, 'current'])->name('current');
            Route::post('/checkout', [SubscriptionController::class, 'checkout'])->name('checkout');
            Route::post('/portal', [SubscriptionController::class, 'portal'])->name('portal');
        });

        // Weddings
        Route::prefix('weddings')->name('weddings.')->group(function () {
            Route::get('/', [WeddingController::class, 'index'])->name('index');
            Route::post('/', [WeddingController::class, 'store'])->name('store');

            Route::prefix('{wedding}')->group(function () {
                Route::get('/', [WeddingController::class, 'show'])->name('show');
                Route::put('/', [WeddingController::class, 'update'])->name('update');
                Route::delete('/', [WeddingController::class, 'destroy'])->name('destroy');
                Route::get('/dashboard', [WeddingController::class, 'dashboard'])->name('dashboard');

                // Budget
                Route::prefix('budget')->name('budget.')->group(function () {
                    Route::get('/summary', [BudgetController::class, 'summary'])->name('summary');
                    Route::get('/chart', [BudgetController::class, 'chartData'])->name('chart');
                    Route::get('/categories', [BudgetController::class, 'categories'])->name('categories');
                    Route::post('/categories', [BudgetController::class, 'storeCategory'])->name('categories.store');
                    Route::put('/categories/{category}', [BudgetController::class, 'updateCategory'])->name('categories.update');
                    Route::post('/categories/{category}/items', [BudgetController::class, 'storeItem'])->name('items.store');
                    Route::put('/items/{item}', [BudgetController::class, 'updateItem'])->name('items.update');
                    Route::delete('/items/{item}', [BudgetController::class, 'destroyItem'])->name('items.destroy');
                });

                // Checklist
                Route::prefix('checklist')->name('checklist.')->group(function () {
                    Route::get('/', [ChecklistController::class, 'index'])->name('index');
                    Route::post('/', [ChecklistController::class, 'store'])->name('store');
                    Route::put('/{task}', [ChecklistController::class, 'update'])->name('update');
                    Route::delete('/{task}', [ChecklistController::class, 'destroy'])->name('destroy');
                    Route::patch('/{task}/toggle', [ChecklistController::class, 'toggle'])->name('toggle');
                    Route::post('/apply-template', [ChecklistController::class, 'applyTemplate'])->name('apply-template');
                    Route::post('/reorder', [ChecklistController::class, 'reorder'])->name('reorder');
                });

                // Guests
                Route::prefix('guests')->name('guests.')->group(function () {
                    Route::get('/', [GuestController::class, 'index'])->name('index');
                    Route::post('/', [GuestController::class, 'store'])->name('store');
                    Route::put('/{guest}', [GuestController::class, 'update'])->name('update');
                    Route::delete('/{guest}', [GuestController::class, 'destroy'])->name('destroy');
                    Route::post('/send-invitations', [GuestController::class, 'sendInvitations'])->name('send-invitations');
                    Route::post('/import', [GuestController::class, 'importCsv'])->name('import');
                });

                // Guest Groups
                Route::prefix('guest-groups')->name('guest-groups.')->group(function () {
                    Route::get('/', [GuestController::class, 'groups'])->name('index');
                    Route::post('/', [GuestController::class, 'storeGroup'])->name('store');
                });

                // Table Plans
                Route::prefix('table-plans')->name('table-plans.')->group(function () {
                    Route::get('/', [TablePlanController::class, 'index'])->name('index');
                    Route::post('/', [TablePlanController::class, 'store'])->name('store');

                    Route::prefix('{plan}')->group(function () {
                        Route::get('/', [TablePlanController::class, 'show'])->name('show');
                        Route::delete('/', [TablePlanController::class, 'destroy'])->name('destroy');
                        Route::post('/move-guest', [TablePlanController::class, 'moveGuest'])->name('move-guest');

                        Route::prefix('tables')->name('tables.')->group(function () {
                            Route::post('/', [TablePlanController::class, 'storeTables'])->name('store');
                            Route::put('/{table}', [TablePlanController::class, 'updateTable'])->name('update');
                            Route::delete('/{table}', [TablePlanController::class, 'destroyTable'])->name('destroy');
                            Route::patch('/{table}/seats/{seat}/assign', [TablePlanController::class, 'assignGuest'])->name('assign-guest');
                        });
                    });
                });
            });
        });

        // Checklist templates (global)
        Route::get('/checklist-templates', [ChecklistController::class, 'templates'])->name('checklist.templates');
    });
});
