<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use App\Services\BudgetService;
use Illuminate\View\View;

class BudgetController extends Controller
{
    public function __construct(private readonly BudgetService $budgetService) {}

    public function index(Wedding $wedding): View
    {
        $this->authorize('view', $wedding);

        $summary = $this->budgetService->getSummary($wedding);
        $chartData = $this->budgetService->getChartData($wedding);

        return view('weddings.budget', compact('wedding', 'summary', 'chartData'));
    }
}
