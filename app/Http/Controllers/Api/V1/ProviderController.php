<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use App\Models\ProviderCategory;
use App\Services\ProviderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function __construct(private readonly ProviderService $providerService) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category', 'city', 'department', 'region', 'price_min', 'price_max', 'search']);
        $providers = $this->providerService->search($filters);

        return response()->json([
            'data' => ProviderResource::collection($providers),
            'meta' => [
                'total' => $providers->total(),
                'per_page' => $providers->perPage(),
                'current_page' => $providers->currentPage(),
                'last_page' => $providers->lastPage(),
            ],
        ]);
    }

    public function show(Provider $provider): ProviderResource
    {
        $provider->load('category');

        return new ProviderResource($provider);
    }

    public function categories(): JsonResponse
    {
        $categories = ProviderCategory::orderBy('sort_order')->get();

        return response()->json($categories);
    }
}
