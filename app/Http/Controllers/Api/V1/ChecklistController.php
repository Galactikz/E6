<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreChecklistTaskRequest;
use App\Http\Requests\Api\UpdateChecklistTaskRequest;
use App\Http\Resources\ChecklistTaskResource;
use App\Models\ChecklistTask;
use App\Models\ChecklistTemplate;
use App\Models\Wedding;
use App\Services\ChecklistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    public function __construct(private readonly ChecklistService $checklistService) {}

    public function index(Request $request, Wedding $wedding): JsonResponse
    {
        $this->authorize('view', $wedding);

        $tasks = $wedding->checklistTasks()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->get();

        return response()->json([
            'tasks' => ChecklistTaskResource::collection($tasks),
            'stats' => $this->checklistService->getStats($wedding),
        ]);
    }

    public function store(StoreChecklistTaskRequest $request, Wedding $wedding): ChecklistTaskResource
    {
        $this->authorize('update', $wedding);

        $task = $this->checklistService->createTask($wedding, $request->validated());

        return new ChecklistTaskResource($task);
    }

    public function update(UpdateChecklistTaskRequest $request, Wedding $wedding, ChecklistTask $task): ChecklistTaskResource
    {
        $this->authorize('update', $wedding);

        $updated = $this->checklistService->updateTask($task, $request->validated());

        return new ChecklistTaskResource($updated);
    }

    public function destroy(Wedding $wedding, ChecklistTask $task): JsonResponse
    {
        $this->authorize('update', $wedding);

        $task->delete();

        return response()->json(['message' => 'Tâche supprimée.']);
    }

    public function toggle(Wedding $wedding, ChecklistTask $task): ChecklistTaskResource
    {
        $this->authorize('update', $wedding);

        $updated = $this->checklistService->toggleStatus($task);

        return new ChecklistTaskResource($updated);
    }

    public function applyTemplate(Request $request, Wedding $wedding): JsonResponse
    {
        $this->authorize('update', $wedding);

        $request->validate(['template_id' => 'required|exists:checklist_templates,id']);

        $template = ChecklistTemplate::findOrFail($request->template_id);
        $tasks = $this->checklistService->applyTemplate($wedding, $template);

        return response()->json([
            'message' => "Modèle appliqué : {$tasks->count()} tâches créées.",
            'tasks' => ChecklistTaskResource::collection($tasks),
        ]);
    }

    public function reorder(Request $request, Wedding $wedding): JsonResponse
    {
        $this->authorize('update', $wedding);

        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

        $this->checklistService->reorder($wedding, $request->ids);

        return response()->json(['message' => 'Ordre mis à jour.']);
    }

    public function templates(): JsonResponse
    {
        $templates = ChecklistTemplate::where('is_active', true)->get();

        return response()->json($templates);
    }
}
