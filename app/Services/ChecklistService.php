<?php

namespace App\Services;

use App\Models\ChecklistTask;
use App\Models\ChecklistTemplate;
use App\Models\Wedding;
use Illuminate\Support\Collection;

class ChecklistService
{
    public function applyTemplate(Wedding $wedding, ChecklistTemplate $template): Collection
    {
        $weddingDate = $wedding->wedding_date;
        $tasks = collect();

        foreach ($template->tasks as $templateTask) {
            $dueDate = $weddingDate
                ? $weddingDate->copy()->subMonths($templateTask->months_before_wedding)
                : null;

            $task = ChecklistTask::create([
                'wedding_id' => $wedding->id,
                'checklist_template_task_id' => $templateTask->id,
                'title' => $templateTask->title,
                'description' => $templateTask->description,
                'due_date' => $dueDate,
                'priority' => $templateTask->priority,
                'category' => $templateTask->category,
                'sort_order' => $templateTask->sort_order,
                'status' => ChecklistTask::STATUS_PENDING,
            ]);

            $tasks->push($task);
        }

        return $tasks;
    }

    public function createTask(Wedding $wedding, array $data): ChecklistTask
    {
        $maxOrder = $wedding->checklistTasks()->max('sort_order') ?? 0;

        return ChecklistTask::create(array_merge($data, [
            'wedding_id' => $wedding->id,
            'sort_order' => $maxOrder + 1,
        ]));
    }

    public function updateTask(ChecklistTask $task, array $data): ChecklistTask
    {
        $task->update($data);
        return $task->fresh();
    }

    public function toggleStatus(ChecklistTask $task): ChecklistTask
    {
        $newStatus = $task->status === ChecklistTask::STATUS_DONE
            ? ChecklistTask::STATUS_PENDING
            : ChecklistTask::STATUS_DONE;

        $task->update(['status' => $newStatus]);
        return $task->fresh();
    }

    public function getStats(Wedding $wedding): array
    {
        $tasks = $wedding->checklistTasks;

        return [
            'total' => $tasks->count(),
            'done' => $tasks->where('status', ChecklistTask::STATUS_DONE)->count(),
            'pending' => $tasks->where('status', ChecklistTask::STATUS_PENDING)->count(),
            'overdue' => $tasks->filter(fn($t) =>
                $t->due_date && $t->due_date->isPast() && $t->status !== ChecklistTask::STATUS_DONE
            )->count(),
            'by_priority' => [
                'high' => $tasks->where('priority', ChecklistTask::PRIORITY_HIGH)->count(),
                'medium' => $tasks->where('priority', ChecklistTask::PRIORITY_MEDIUM)->count(),
                'low' => $tasks->where('priority', ChecklistTask::PRIORITY_LOW)->count(),
            ],
            'completion_percentage' => $tasks->count() > 0
                ? round(($tasks->where('status', ChecklistTask::STATUS_DONE)->count() / $tasks->count()) * 100)
                : 0,
        ];
    }

    public function reorder(Wedding $wedding, array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            ChecklistTask::where('id', $id)
                ->where('wedding_id', $wedding->id)
                ->update(['sort_order' => $index + 1]);
        }
    }
}
