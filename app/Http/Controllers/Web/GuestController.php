<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use App\Services\GuestService;
use Illuminate\View\View;

class GuestController extends Controller
{
    public function __construct(private readonly GuestService $guestService) {}

    public function index(Wedding $wedding): View
    {
        $this->authorize('view', $wedding);

        $stats = $this->guestService->getStats($wedding);
        $groups = $wedding->guestGroups()->withCount('guests')->get();

        return view('weddings.guests', compact('wedding', 'stats', 'groups'));
    }
}
