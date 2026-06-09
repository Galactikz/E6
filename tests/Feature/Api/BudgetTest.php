<?php

use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->wedding = Wedding::factory()->create(['user_id' => $this->user->id]);
    $this->category = BudgetCategory::factory()->create([
        'wedding_id' => $this->wedding->id,
        'planned_amount' => 5000,
    ]);
});

test('user can get budget summary', function () {
    $response = $this->actingAs($this->user)
        ->getJson("/api/v1/weddings/{$this->wedding->id}/budget/summary");

    $response->assertOk()
        ->assertJsonStructure(['total_planned', 'total_actual', 'remaining', 'percentage_used', 'categories']);
});

test('user can add a budget item', function () {
    $response = $this->actingAs($this->user)
        ->postJson("/api/v1/weddings/{$this->wedding->id}/budget/categories/{$this->category->id}/items", [
            'name' => 'Acompte salle',
            'planned_amount' => 2000,
            'actual_amount' => 1000,
            'vendor_name' => 'Château du Lac',
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('name', 'Acompte salle')
        ->assertJsonPath('planned_amount', 2000);
});

test('user can update a budget item', function () {
    $item = BudgetItem::factory()->create([
        'wedding_id' => $this->wedding->id,
        'budget_category_id' => $this->category->id,
        'planned_amount' => 1000,
    ]);

    $response = $this->actingAs($this->user)
        ->putJson("/api/v1/weddings/{$this->wedding->id}/budget/items/{$item->id}", [
            'is_paid' => true,
            'actual_amount' => 1000,
        ]);

    $response->assertOk()->assertJsonPath('is_paid', true);
});

test('user can delete a budget item', function () {
    $item = BudgetItem::factory()->create([
        'wedding_id' => $this->wedding->id,
        'budget_category_id' => $this->category->id,
    ]);

    $this->actingAs($this->user)
        ->deleteJson("/api/v1/weddings/{$this->wedding->id}/budget/items/{$item->id}")
        ->assertOk();

    $this->assertDatabaseMissing('budget_items', ['id' => $item->id]);
});

test('category totals are recalculated after adding item', function () {
    $this->actingAs($this->user)
        ->postJson("/api/v1/weddings/{$this->wedding->id}/budget/categories/{$this->category->id}/items", [
            'name' => 'Test item',
            'planned_amount' => 500,
            'actual_amount' => 300,
        ]);

    $this->category->refresh();
    expect($this->category->planned_amount)->toBe('500.00')
        ->and($this->category->actual_amount)->toBe('300.00');
});
