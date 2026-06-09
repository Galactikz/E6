<?php

use App\Models\Guest;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->wedding = Wedding::factory()->create(['user_id' => $this->user->id]);
});

test('user can add a guest', function () {
    $response = $this->actingAs($this->user)
        ->postJson("/api/v1/weddings/{$this->wedding->id}/guests", [
            'first_name' => 'Marie',
            'last_name' => 'Martin',
            'email' => 'marie@example.com',
            'meal_type' => 'adult',
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('first_name', 'Marie')
        ->assertJsonPath('rsvp_status', 'pending');
});

test('guest has rsvp_token on creation', function () {
    Guest::factory()->create(['wedding_id' => $this->wedding->id]);

    $guest = Guest::first();
    expect($guest->rsvp_token)->not->toBeNull()->toHaveLength(64);
});

test('guest can respond to rsvp via token', function () {
    $guest = Guest::factory()->create([
        'wedding_id' => $this->wedding->id,
        'rsvp_status' => 'pending',
    ]);

    $response = $this->postJson("/api/v1/rsvp/{$guest->rsvp_token}", [
        'status' => 'confirmed',
    ]);

    $response->assertOk()->assertJsonPath('guest.rsvp_status', 'confirmed');

    $this->assertDatabaseHas('guests', [
        'id' => $guest->id,
        'rsvp_status' => 'confirmed',
    ]);
});

test('user can list guests with stats', function () {
    Guest::factory()->count(5)->create(['wedding_id' => $this->wedding->id]);

    $response = $this->actingAs($this->user)
        ->getJson("/api/v1/weddings/{$this->wedding->id}/guests");

    $response->assertOk()
        ->assertJsonStructure(['guests', 'stats'])
        ->assertJsonCount(5, 'guests');
});

test('user can filter guests by rsvp status', function () {
    Guest::factory()->count(3)->create([
        'wedding_id' => $this->wedding->id,
        'rsvp_status' => 'confirmed',
    ]);
    Guest::factory()->count(2)->create([
        'wedding_id' => $this->wedding->id,
        'rsvp_status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->getJson("/api/v1/weddings/{$this->wedding->id}/guests?rsvp_status=confirmed");

    $response->assertOk()->assertJsonCount(3, 'guests');
});

test('user can delete a guest', function () {
    $guest = Guest::factory()->create(['wedding_id' => $this->wedding->id]);

    $this->actingAs($this->user)
        ->deleteJson("/api/v1/weddings/{$this->wedding->id}/guests/{$guest->id}")
        ->assertOk();

    $this->assertDatabaseMissing('guests', ['id' => $guest->id]);
});
