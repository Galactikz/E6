<?php

use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    SubscriptionPlan::factory()->create([
        'slug' => 'free',
        'max_weddings' => 1,
        'max_guests' => 50,
        'max_tables' => 10,
    ]);
});

test('authenticated user can create a wedding', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/v1/weddings', [
        'name' => 'Mariage de Sophie et Pierre',
        'wedding_date' => now()->addYear()->format('Y-m-d'),
        'city' => 'Paris',
        'guest_count' => 100,
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('name', 'Mariage de Sophie et Pierre')
        ->assertJsonPath('city', 'Paris');

    $this->assertDatabaseHas('weddings', ['name' => 'Mariage de Sophie et Pierre']);
});

test('unauthenticated user cannot create a wedding', function () {
    $this->postJson('/api/v1/weddings', [
        'name' => 'Test',
    ])->assertStatus(401);
});

test('user can list their weddings', function () {
    $user = User::factory()->create();
    Wedding::factory()->count(2)->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->getJson('/api/v1/weddings');

    $response->assertOk()->assertJsonCount(2, 'data');
});

test('user cannot see another user wedding', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $wedding = Wedding::factory()->create(['user_id' => $other->id]);

    $response = $this->actingAs($user)->getJson("/api/v1/weddings/{$wedding->id}");

    $response->assertStatus(403);
});

test('user can update their wedding', function () {
    $user = User::factory()->create();
    $wedding = Wedding::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->putJson("/api/v1/weddings/{$wedding->id}", [
        'city' => 'Lyon',
    ]);

    $response->assertOk()->assertJsonPath('city', 'Lyon');
});

test('user can delete their wedding', function () {
    $user = User::factory()->create();
    $wedding = Wedding::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->deleteJson("/api/v1/weddings/{$wedding->id}")->assertOk();

    $this->assertSoftDeleted('weddings', ['id' => $wedding->id]);
});
