<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreWeddingRequest;
use App\Http\Requests\Api\UpdateWeddingRequest;
use App\Http\Resources\WeddingResource;
use App\Models\Wedding;
use App\Services\WeddingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WeddingController extends Controller
{
    public function __construct(private readonly WeddingService $weddingService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $weddings = $request->user()->weddings()->with(['budgetCategories', 'guests'])->get();
        return WeddingResource::collection($weddings);
    }

    public function store(StoreWeddingRequest $request): WeddingResource
    {
        $this->authorize('create', Wedding::class);

        $wedding = $this->weddingService->create($request->user(), $request->validated());

        return new WeddingResource($wedding);
    }

    public function show(Request $request, Wedding $wedding): WeddingResource
    {
        $this->authorize('view', $wedding);

        $wedding->load(['budgetCategories.items', 'guestGroups', 'tablePlans']);

        return new WeddingResource($wedding);
    }

    public function update(UpdateWeddingRequest $request, Wedding $wedding): WeddingResource
    {
        $this->authorize('update', $wedding);

        $updated = $this->weddingService->update($wedding, $request->validated());

        return new WeddingResource($updated);
    }

    public function destroy(Wedding $wedding): JsonResponse
    {
        $this->authorize('delete', $wedding);

        $this->weddingService->delete($wedding);

        return response()->json(['message' => 'Mariage supprimé avec succès.']);
    }

    public function dashboard(Wedding $wedding): JsonResponse
    {
        $this->authorize('view', $wedding);

        $data = $this->weddingService->getDashboard($wedding);

        return response()->json($data);
    }
}
