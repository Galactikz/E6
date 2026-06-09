<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreGuestRequest;
use App\Http\Requests\Api\StoreGuestGroupRequest;
use App\Http\Requests\Api\UpdateGuestRequest;
use App\Http\Resources\GuestResource;
use App\Http\Resources\GuestGroupResource;
use App\Models\Guest;
use App\Models\GuestGroup;
use App\Models\Wedding;
use App\Services\GuestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function __construct(private readonly GuestService $guestService) {}

    public function index(Request $request, Wedding $wedding): JsonResponse
    {
        $this->authorize('view', $wedding);

        $guests = $wedding->guests()
            ->with('group')
            ->when($request->rsvp_status, fn($q) => $q->where('rsvp_status', $request->rsvp_status))
            ->when($request->group_id, fn($q) => $q->where('guest_group_id', $request->group_id))
            ->when($request->search, fn($q) => $q->where(function ($sq) use ($request) {
                $sq->where('first_name', 'ILIKE', "%{$request->search}%")
                   ->orWhere('last_name', 'ILIKE', "%{$request->search}%")
                   ->orWhere('email', 'ILIKE', "%{$request->search}%");
            }))
            ->get();

        return response()->json([
            'guests' => GuestResource::collection($guests),
            'stats' => $this->guestService->getStats($wedding),
        ]);
    }

    public function store(StoreGuestRequest $request, Wedding $wedding): GuestResource
    {
        $this->authorize('update', $wedding);

        $guest = $this->guestService->createGuest($wedding, $request->validated());

        return new GuestResource($guest->load('group'));
    }

    public function update(UpdateGuestRequest $request, Wedding $wedding, Guest $guest): GuestResource
    {
        $this->authorize('update', $wedding);

        $updated = $this->guestService->updateGuest($guest, $request->validated());

        return new GuestResource($updated);
    }

    public function destroy(Wedding $wedding, Guest $guest): JsonResponse
    {
        $this->authorize('update', $wedding);

        $guest->delete();

        return response()->json(['message' => 'Invité supprimé.']);
    }

    public function rsvp(Request $request, string $token): JsonResponse
    {
        $request->validate(['status' => 'required|in:confirmed,declined']);

        $guest = Guest::where('rsvp_token', $token)->firstOrFail();
        $updated = $this->guestService->updateRsvp($guest, $request->status);

        return response()->json([
            'message' => 'Réponse enregistrée, merci !',
            'guest' => new GuestResource($updated),
        ]);
    }

    public function sendInvitations(Request $request, Wedding $wedding): JsonResponse
    {
        $this->authorize('update', $wedding);

        $request->validate(['guest_ids' => 'required|array', 'guest_ids.*' => 'integer']);

        $result = $this->guestService->sendInvitations($wedding, $request->guest_ids);

        return response()->json($result);
    }

    public function groups(Wedding $wedding): JsonResponse
    {
        $this->authorize('view', $wedding);

        $groups = $wedding->guestGroups()->withCount('guests')->get();

        return response()->json(GuestGroupResource::collection($groups));
    }

    public function storeGroup(StoreGuestGroupRequest $request, Wedding $wedding): GuestGroupResource
    {
        $this->authorize('update', $wedding);

        $group = $this->guestService->createGroup($wedding, $request->validated());

        return new GuestGroupResource($group);
    }

    public function importCsv(Request $request, Wedding $wedding): JsonResponse
    {
        $this->authorize('update', $wedding);

        $request->validate(['file' => 'required|file|mimes:csv,txt|max:2048']);

        $content = file_get_contents($request->file('file')->getRealPath());
        $result = $this->guestService->importFromCsv($wedding, $content);

        return response()->json($result);
    }
}
