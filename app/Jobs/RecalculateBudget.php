<?php

namespace App\Jobs;

use App\Models\Wedding;
use App\Services\BudgetService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateBudget implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly Wedding $wedding) {}

    public function handle(BudgetService $budgetService): void
    {
        $summary = $budgetService->getSummary($this->wedding);

        $this->wedding->update([
            'total_budget' => $summary['total_planned'],
        ]);
    }
}
