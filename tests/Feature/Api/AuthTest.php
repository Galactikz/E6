<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can register', function () {
    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Jean Dupont',
        'email' => 'jean@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['user', 'token'])
        ->assertJsonPath('user.email', 'jean@example.com');

    $this->assertDatabaseHas('users', ['email' => 'jean@example.com']);
});

test('user can login', function () {
    $user = User::factory()->create([
        'email' => 'jean@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'jean@example.com',
        'password' => 'password123',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['user', 'token']);
});

test('login fails with wrong credentials', function () {
    User::factory()->create(['email' => 'jean@example.com']);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'jean@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422);
});

test('authenticated user can get their profile', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson('/api/v1/auth/me');

    $response->assertOk()
        ->assertJsonPath('id', $user->id)
        ->assertJsonPath('email', $user->email);
});

test('user can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/v1/auth/logout');

    $response->assertOk()->assertJsonPath('message', 'Déconnecté avec succès.');
});

test('registration requires valid email', function () {
    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Test',
        'email' => 'not-an-email',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(422)->assertJsonValidationErrors(['email']);
});

test('registration requires password confirmation', function () {
    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Test',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'wrong',
    ]);

    $response->assertStatus(422)->assertJsonValidationErrors(['password']);
});
