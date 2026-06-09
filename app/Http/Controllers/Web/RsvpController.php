<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Services\GuestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RsvpController extends Controller
{
    public function __construct(private readonly GuestService $guestService) {}

    public function show(string $token): View
    {
        $guest = Guest::where('rsvp_token', $token)->with('wedding')->firstOrFail();

        return view('rsvp.show', compact('guest'));
    }

    public function respond(Request $request, string $token): View|RedirectResponse
    {
        $request->validate(['status' => 'required|in:confirmed,declined']);

        $guest = Guest::where('rsvp_token', $token)->firstOrFail();
        $this->guestService->updateRsvp($guest, $request->status);

        return view('rsvp.confirmed', compact('guest'));
    }
}
