<?php

use App\Models\BudgetCategory;
use App\Models\User;
use App\Models\Wedding;
use App\Services\BudgetService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('budget service creates default categories', function () {
    $user = User::factory()->create();
    $wedding = Wedding::factory()->create(['user_id' => $user->id]);

    $service = new BudgetService();
    $service->createDefaultCategories($wedding);

    expect($wedding->budgetCategories()->count())->toBe(12);
});

test('budget service calculates summary correctly', function () {
    $user = User::factory()->create();
    $wedding = Wedding::factory()->create(['user_id' => $user->id]);

    $cat = BudgetCategory::factory()->create([
        'wedding_id' => $wedding->id,
        'planned_amount' => 10000,
        'actual_amount' => 6000,
    ]);

    $service = new BudgetService();
    $summary = $service->getSummary($wedding);

    expect($summary['total_planned'])->toBe('10000.00')
        ->and($summary['total_actual'])->toBe('6000.00')
        ->and($summary['remaining'])->toBe(4000.0)
        ->and($summary['percentage_used'])->toBe(60.0);
});
