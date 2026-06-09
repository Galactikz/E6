<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(private readonly SubscriptionService $subscriptionService) {}

    public function plans(): JsonResponse
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get();

        return response()->json($plans);
    }

    public function checkout(Request $request): JsonResponse
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        if ($plan->isFree()) {
            return response()->json(['message' => 'Ce plan est gratuit.'], 422);
        }

        $url = $this->subscriptionService->createCheckoutSession(
            $request->user(),
            $plan,
            $request->billing_cycle
        );

        return response()->json(['checkout_url' => $url]);
    }

    public function portal(Request $request): JsonResponse
    {
        $url = $this->subscriptionService->createPortalSession($request->user());

        return response()->json(['portal_url' => $url]);
    }

    public function current(Request $request): JsonResponse
    {
        $user = $request->user()->load('activeSubscription.plan');
        $plan = $this->subscriptionService->getUserPlan($user);

        return response()->json([
            'plan' => $plan,
            'subscription' => $user->activeSubscription,
            'limits' => [
                'max_weddings' => $plan->max_weddings,
                'max_guests' => $plan->max_guests,
                'max_tables' => $plan->max_tables,
                'has_sms' => $plan->has_sms,
                'has_email_invitations' => $plan->has_email_invitations,
                'has_collaboration' => $plan->has_collaboration,
            ],
        ]);
    }
}
