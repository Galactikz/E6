<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(private readonly SubscriptionService $subscriptionService) {}

    public function stripe(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        if (!$signature) {
            return response()->json(['message' => 'Missing signature'], 400);
        }

        try {
            $this->subscriptionService->handleWebhook($payload, $signature);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['message' => 'Invalid signature'], 400);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Webhook error: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Webhook handled.']);
    }
}
