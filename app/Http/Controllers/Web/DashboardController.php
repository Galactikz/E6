<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\WeddingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly WeddingService $weddingService) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $weddings = $user->weddings()->with(['guests', 'checklistTasks', 'budgetCategories'])->get();

        $activeWedding = $weddings->first();
        $dashboard = $activeWedding
            ? $this->weddingService->getDashboard($activeWedding)
            : null;

        return view('dashboard', compact('weddings', 'activeWedding', 'dashboard'));
    }
}
