<?php

use App\Models\ChecklistTask;
use App\Models\ChecklistTemplate;
use App\Models\ChecklistTemplateTask;
use App\Models\User;
use App\Models\Wedding;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->wedding = Wedding::factory()->create([
        'user_id' => $this->user->id,
        'wedding_date' => now()->addYear(),
    ]);
});

test('user can create a checklist task', function () {
    $response = $this->actingAs($this->user)
        ->postJson("/api/v1/weddings/{$this->wedding->id}/checklist", [
            'title' => 'Réserver la salle',
            'priority' => 'high',
            'due_date' => now()->addMonths(3)->format('Y-m-d'),
        ]);

    $response->assertStatus(201)
        ->assertJsonPath('title', 'Réserver la salle')
        ->assertJsonPath('priority', 'high')
        ->assertJsonPath('status', 'pending');
});

test('user can toggle task status', function () {
    $task = ChecklistTask::factory()->create([
        'wedding_id' => $this->wedding->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->patchJson("/api/v1/weddings/{$this->wedding->id}/checklist/{$task->id}/toggle");

    $response->assertOk()->assertJsonPath('status', 'done');
});

test('user can apply a checklist template', function () {
    $template = ChecklistTemplate::factory()->create(['months_before' => 12]);
    ChecklistTemplateTask::factory()->count(5)->create([
        'checklist_template_id' => $template->id,
        'months_before_wedding' => 6,
    ]);

    $response = $this->actingAs($this->user)
        ->postJson("/api/v1/weddings/{$this->wedding->id}/checklist/apply-template", [
            'template_id' => $template->id,
        ]);

    $response->assertOk()->assertJsonCount(5, 'tasks');
});

test('user can get checklist stats', function () {
    ChecklistTask::factory()->count(3)->create([
        'wedding_id' => $this->wedding->id,
        'status' => 'done',
    ]);
    ChecklistTask::factory()->count(2)->create([
        'wedding_id' => $this->wedding->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($this->user)
        ->getJson("/api/v1/weddings/{$this->wedding->id}/checklist");

    $response->assertOk()
        ->assertJsonPath('stats.total', 5)
        ->assertJsonPath('stats.done', 3)
        ->assertJsonPath('stats.completion_percentage', 60);
});
