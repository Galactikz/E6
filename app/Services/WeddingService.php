<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wedding;
use Illuminate\Support\Facades\DB;

class WeddingService
{
    public function __construct(
        private readonly BudgetService $budgetService,
        private readonly ChecklistService $checklistService,
    ) {}

    public function create(User $user, array $data): Wedding
    {
        return DB::transaction(function () use ($user, $data) {
            $wedding = Wedding::create(array_merge($data, [
                'user_id' => $user->id,
            ]));

            $this->budgetService->createDefaultCategories($wedding);

            return $wedding;
        });
    }

    public function update(Wedding $wedding, array $data): Wedding
    {
        $wedding->update($data);
        return $wedding->fresh();
    }

    public function delete(Wedding $wedding): void
    {
        $wedding->delete();
    }

    public function getDashboard(Wedding $wedding): array
    {
        $wedding->load(['guests', 'checklistTasks', 'budgetCategories', 'tablePlans']);

        $budgetSummary = app(BudgetService::class)->getSummary($wedding);
        $checklistStats = app(ChecklistService::class)->getStats($wedding);
        $guestStats = app(GuestService::class)->getStats($wedding);

        return [
            'wedding' => $wedding,
            'budget' => $budgetSummary,
            'checklist' => $checklistStats,
            'guests' => $guestStats,
            'days_until_wedding' => $wedding->wedding_date
                ? now()->diffInDays($wedding->wedding_date, false)
                : null,
        ];
    }
}
