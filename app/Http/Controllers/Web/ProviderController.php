<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\ProviderCategory;
use App\Services\ProviderService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProviderController extends Controller
{
    public function __construct(private readonly ProviderService $providerService) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'city', 'department', 'region', 'price_min', 'price_max']);
        $providers = $this->providerService->search($filters);
        $categories = ProviderCategory::orderBy('sort_order')->get();

        return view('providers.index', compact('providers', 'categories', 'filters'));
    }

    public function category(Request $request, string $category): View
    {
        $cat = ProviderCategory::where('slug', $category)->firstOrFail();
        $filters = array_merge($request->only(['search', 'city']), ['category' => $category]);
        $providers = $this->providerService->search($filters);

        return view('providers.category', compact('providers', 'cat', 'filters'));
    }

    public function cityCategory(Request $request, string $category, string $city): View
    {
        $cat = ProviderCategory::where('slug', $category)->firstOrFail();
        $filters = ['category' => $category, 'city' => $city];
        $providers = $this->providerService->search($filters);

        return view('providers.city', compact('providers', 'cat', 'city', 'filters'));
    }

    public function show(string $category, string $city, Provider $provider): View
    {
        $provider->load('category');

        return view('providers.show', compact('provider', 'category', 'city'));
    }
}
