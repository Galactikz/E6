<?php

namespace App\Services;

use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Wedding;
use Illuminate\Support\Collection;

class BudgetService
{
    public function createDefaultCategories(Wedding $wedding): void
    {
        $defaults = BudgetCategory::defaults();

        foreach ($defaults as $category) {
            BudgetCategory::create(array_merge($category, [
                'wedding_id' => $wedding->id,
            ]));
        }
    }

    public function getSummary(Wedding $wedding): array
    {
        $categories = $wedding->budgetCategories()->with('items')->get();

        $totalPlanned = $categories->sum('planned_amount');
        $totalActual = $categories->sum('actual_amount');
        $remaining = $totalPlanned - $totalActual;
        $percentage = $totalPlanned > 0
            ? round(($totalActual / $totalPlanned) * 100, 1)
            : 0;

        return [
            'total_planned' => $totalPlanned,
            'total_actual' => $totalActual,
            'remaining' => $remaining,
            'percentage_used' => $percentage,
            'categories' => $categories->map(fn($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'icon' => $cat->icon,
                'color' => $cat->color,
                'planned_amount' => $cat->planned_amount,
                'actual_amount' => $cat->actual_amount,
                'remaining' => $cat->remaining_budget,
                'percentage' => $cat->usage_percentage,
            ]),
        ];
    }

    public function createCategory(Wedding $wedding, array $data): BudgetCategory
    {
        return BudgetCategory::create(array_merge($data, [
            'wedding_id' => $wedding->id,
            'is_custom' => true,
        ]));
    }

    public function updateCategory(BudgetCategory $category, array $data): BudgetCategory
    {
        $category->update($data);
        return $category->fresh();
    }

    public function createItem(BudgetCategory $category, array $data): BudgetItem
    {
        $item = BudgetItem::create(array_merge($data, [
            'budget_category_id' => $category->id,
            'wedding_id' => $category->wedding_id,
        ]));

        $this->recalculateCategoryTotals($category);

        return $item;
    }

    public function updateItem(BudgetItem $item, array $data): BudgetItem
    {
        $item->update($data);
        $this->recalculateCategoryTotals($item->category);

        return $item->fresh();
    }

    public function deleteItem(BudgetItem $item): void
    {
        $category = $item->category;
        $item->delete();
        $this->recalculateCategoryTotals($category);
    }

    private function recalculateCategoryTotals(BudgetCategory $category): void
    {
        $category->update([
            'planned_amount' => $category->items()->sum('planned_amount'),
            'actual_amount' => $category->items()->sum('actual_amount'),
        ]);
    }

    public function getChartData(Wedding $wedding): array
    {
        $categories = $wedding->budgetCategories()->get();

        return [
            'labels' => $categories->pluck('name')->toArray(),
            'planned' => $categories->pluck('planned_amount')->map(fn($v) => (float) $v)->toArray(),
            'actual' => $categories->pluck('actual_amount')->map(fn($v) => (float) $v)->toArray(),
            'colors' => $categories->pluck('color')->toArray(),
        ];
    }
}
