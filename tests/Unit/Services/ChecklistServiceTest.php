<?php

use App\Models\ChecklistTask;
use App\Models\ChecklistTemplate;
use App\Models\ChecklistTemplateTask;
use App\Models\User;
use App\Models\Wedding;
use App\Services\ChecklistService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('checklist service applies template correctly', function () {
    $user = User::factory()->create();
    $wedding = Wedding::factory()->create([
        'user_id' => $user->id,
        'wedding_date' => now()->addYear(),
    ]);

    $template = ChecklistTemplate::factory()->create();
    ChecklistTemplateTask::factory()->count(5)->create([
        'checklist_template_id' => $template->id,
        'months_before_wedding' => 6,
    ]);

    $service = new ChecklistService();
    $tasks = $service->applyTemplate($wedding, $template->load('tasks'));

    expect($tasks)->toHaveCount(5);
    expect(ChecklistTask::where('wedding_id', $wedding->id)->count())->toBe(5);
});

test('checklist service calculates stats correctly', function () {
    $user = User::factory()->create();
    $wedding = Wedding::factory()->create(['user_id' => $user->id]);

    ChecklistTask::factory()->count(4)->create([
        'wedding_id' => $wedding->id,
        'status' => 'done',
    ]);
    ChecklistTask::factory()->count(1)->create([
        'wedding_id' => $wedding->id,
        'status' => 'pending',
    ]);

    $service = new ChecklistService();
    $stats = $service->getStats($wedding->load('checklistTasks'));

    expect($stats['total'])->toBe(5)
        ->and($stats['done'])->toBe(4)
        ->and($stats['completion_percentage'])->toBe(80);
});

test('checklist service toggles task status', function () {
    $user = User::factory()->create();
    $wedding = Wedding::factory()->create(['user_id' => $user->id]);
    $task = ChecklistTask::factory()->create([
        'wedding_id' => $wedding->id,
        'status' => 'pending',
    ]);

    $service = new ChecklistService();
    $updated = $service->toggleStatus($task);

    expect($updated->status)->toBe('done');
});
