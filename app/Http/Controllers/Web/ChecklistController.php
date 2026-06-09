<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ChecklistTemplate;
use App\Models\Wedding;
use App\Services\ChecklistService;
use Illuminate\View\View;

class ChecklistController extends Controller
{
    public function __construct(private readonly ChecklistService $checklistService) {}

    public function index(Wedding $wedding): View
    {
        $this->authorize('view', $wedding);

        $tasks = $wedding->checklistTasks()->orderBy('sort_order')->get();
        $stats = $this->checklistService->getStats($wedding);
        $templates = ChecklistTemplate::where('is_active', true)->get();

        return view('weddings.checklist', compact('wedding', 'tasks', 'stats', 'templates'));
    }
}
