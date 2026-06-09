<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use Illuminate\View\View;

class TablePlanController extends Controller
{
    public function index(Wedding $wedding): View
    {
        $this->authorize('view', $wedding);

        $plan = $wedding->activePlan()->with(['tables.seats.guest'])->first();
        $unassignedGuests = $wedding->guests()
            ->confirmed()
            ->whereDoesntHave('tableSeats')
            ->get();

        return view('weddings.table-plan', compact('wedding', 'plan', 'unassignedGuests'));
    }
}
