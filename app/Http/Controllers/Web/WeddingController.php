<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreWeddingRequest;
use App\Http\Requests\Api\UpdateWeddingRequest;
use App\Models\Wedding;
use App\Services\WeddingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeddingController extends Controller
{
    public function __construct(private readonly WeddingService $weddingService) {}

    public function index(Request $request): View
    {
        $weddings = $request->user()->weddings()->withCount(['guests', 'checklistTasks'])->get();

        return view('weddings.index', compact('weddings'));
    }

    public function create(): View
    {
        return view('weddings.create');
    }

    public function store(StoreWeddingRequest $request): RedirectResponse
    {
        $this->authorize('create', Wedding::class);

        $wedding = $this->weddingService->create($request->user(), $request->validated());

        return redirect()->route('weddings.show', $wedding->slug)
            ->with('success', 'Votre mariage a été créé avec succès !');
    }

    public function show(Wedding $wedding): View
    {
        $this->authorize('view', $wedding);

        return view('weddings.show', compact('wedding'));
    }

    public function edit(Wedding $wedding): View
    {
        $this->authorize('update', $wedding);

        return view('weddings.edit', compact('wedding'));
    }

    public function update(UpdateWeddingRequest $request, Wedding $wedding): RedirectResponse
    {
        $this->authorize('update', $wedding);

        $this->weddingService->update($wedding, $request->validated());

        return redirect()->route('weddings.show', $wedding->slug)
            ->with('success', 'Mariage mis à jour avec succès.');
    }
}
