<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(private readonly SubscriptionService $subscriptionService) {}

    public function plans(): View
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get();

        return view('subscription.plans', compact('plans'));
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $currentPlan = $this->subscriptionService->getUserPlan($user);
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get();

        return view('subscription.index', compact('plans', 'currentPlan'));
    }

    public function success(Request $request): View
    {
        return view('subscription.success');
    }

    public function cancel(): View
    {
        return view('subscription.cancel');
    }

    public function portal(Request $request): RedirectResponse
    {
        $url = $this->subscriptionService->createPortalSession($request->user());

        return redirect($url);
    }
}
