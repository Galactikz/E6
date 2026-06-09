<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreBudgetCategoryRequest;
use App\Http\Requests\Api\StoreBudgetItemRequest;
use App\Http\Requests\Api\UpdateBudgetItemRequest;
use App\Http\Resources\BudgetCategoryResource;
use App\Http\Resources\BudgetItemResource;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Wedding;
use App\Services\BudgetService;
use Illuminate\Http\JsonResponse;

class BudgetController extends Controller
{
    public function __construct(private readonly BudgetService $budgetService) {}

    public function summary(Wedding $wedding): JsonResponse
    {
        $this->authorize('view', $wedding);

        return response()->json($this->budgetService->getSummary($wedding));
    }

    public function chartData(Wedding $wedding): JsonResponse
    {
        $this->authorize('view', $wedding);

        return response()->json($this->budgetService->getChartData($wedding));
    }

    public function categories(Wedding $wedding): JsonResponse
    {
        $this->authorize('view', $wedding);

        $categories = $wedding->budgetCategories()->with('items')->get();

        return response()->json(BudgetCategoryResource::collection($categories));
    }

    public function storeCategory(StoreBudgetCategoryRequest $request, Wedding $wedding): BudgetCategoryResource
    {
        $this->authorize('update', $wedding);

        $category = $this->budgetService->createCategory($wedding, $request->validated());

        return new BudgetCategoryResource($category);
    }

    public function updateCategory(StoreBudgetCategoryRequest $request, Wedding $wedding, BudgetCategory $category): BudgetCategoryResource
    {
        $this->authorize('update', $wedding);

        $updated = $this->budgetService->updateCategory($category, $request->validated());

        return new BudgetCategoryResource($updated);
    }

    public function storeItem(StoreBudgetItemRequest $request, Wedding $wedding, BudgetCategory $category): BudgetItemResource
    {
        $this->authorize('update', $wedding);

        $item = $this->budgetService->createItem($category, $request->validated());

        return new BudgetItemResource($item);
    }

    public function updateItem(UpdateBudgetItemRequest $request, Wedding $wedding, BudgetItem $item): BudgetItemResource
    {
        $this->authorize('update', $wedding);

        $updated = $this->budgetService->updateItem($item, $request->validated());

        return new BudgetItemResource($updated);
    }

    public function destroyItem(Wedding $wedding, BudgetItem $item): JsonResponse
    {
        $this->authorize('update', $wedding);

        $this->budgetService->deleteItem($item);

        return response()->json(['message' => 'Dépense supprimée.']);
    }
}
