<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTablePlanRequest;
use App\Http\Requests\Api\StoreReceptionTableRequest;
use App\Models\ReceptionTable;
use App\Models\TablePlan;
use App\Models\TableSeat;
use App\Models\Wedding;
use App\Services\TablePlanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TablePlanController extends Controller
{
    public function __construct(private readonly TablePlanService $tablePlanService) {}

    public function index(Wedding $wedding): JsonResponse
    {
        $this->authorize('view', $wedding);

        $plans = $wedding->tablePlans()->withCount('tables')->get();

        return response()->json($plans);
    }

    public function store(StoreTablePlanRequest $request, Wedding $wedding): JsonResponse
    {
        $this->authorize('update', $wedding);

        $plan = $this->tablePlanService->createPlan($wedding, $request->validated());

        return response()->json($plan, 201);
    }

    public function show(Wedding $wedding, TablePlan $plan): JsonResponse
    {
        $this->authorize('view', $wedding);

        return response()->json($this->tablePlanService->getPlanState($plan));
    }

    public function destroy(Wedding $wedding, TablePlan $plan): JsonResponse
    {
        $this->authorize('update', $wedding);

        $plan->delete();

        return response()->json(['message' => 'Plan de table supprimé.']);
    }

    public function storeTables(StoreReceptionTableRequest $request, Wedding $wedding, TablePlan $plan): JsonResponse
    {
        $this->authorize('update', $wedding);

        $table = $this->tablePlanService->createTable($plan, $request->validated());

        return response()->json($table, 201);
    }

    public function updateTable(Request $request, Wedding $wedding, TablePlan $plan, ReceptionTable $table): JsonResponse
    {
        $this->authorize('update', $wedding);

        $request->validate([
            'name' => 'sometimes|string|max:100',
            'shape' => 'sometimes|in:round,rectangular',
            'capacity' => 'sometimes|integer|min:1|max:30',
            'position' => 'sometimes|array',
            'color' => 'sometimes|string|max:20',
        ]);

        $updated = $this->tablePlanService->updateTable($table, $request->validated());

        return response()->json($updated);
    }

    public function destroyTable(Wedding $wedding, TablePlan $plan, ReceptionTable $table): JsonResponse
    {
        $this->authorize('update', $wedding);

        $table->delete();

        return response()->json(['message' => 'Table supprimée.']);
    }

    public function assignGuest(Request $request, Wedding $wedding, TablePlan $plan, ReceptionTable $table, TableSeat $seat): JsonResponse
    {
        $this->authorize('update', $wedding);

        $request->validate(['guest_id' => 'nullable|exists:guests,id']);

        $updated = $this->tablePlanService->assignGuest($seat, $request->guest_id);

        return response()->json($updated->load('guest'));
    }

    public function moveGuest(Request $request, Wedding $wedding, TablePlan $plan): JsonResponse
    {
        $this->authorize('update', $wedding);

        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'seat_id' => 'required|exists:table_seats,id',
        ]);

        $seat = $this->tablePlanService->moveGuest($request->guest_id, $request->seat_id);

        return response()->json($seat);
    }
}
