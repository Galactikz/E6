<?php

namespace App\Services;

use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Webhook;

class SubscriptionService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(
        User $user,
        SubscriptionPlan $plan,
        string $billingCycle = 'monthly'
    ): string {
        $priceId = $billingCycle === 'yearly'
            ? $plan->stripe_yearly_price_id
            : $plan->stripe_monthly_price_id;

        $customerId = $this->getOrCreateStripeCustomer($user);

        $session = Session::create([
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('subscription.cancel'),
            'metadata' => [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'billing_cycle' => $billingCycle,
            ],
        ]);

        return $session->url;
    }

    public function createPortalSession(User $user): string
    {
        $customerId = $this->getOrCreateStripeCustomer($user);

        $session = \Stripe\BillingPortal\Session::create([
            'customer' => $customerId,
            'return_url' => route('dashboard'),
        ]);

        return $session->url;
    }

    public function handleWebhook(string $payload, string $signature): void
    {
        $event = Webhook::constructEvent(
            $payload,
            $signature,
            config('services.stripe.webhook_secret')
        );

        match ($event->type) {
            'customer.subscription.created' => $this->handleSubscriptionCreated($event->data->object),
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($event->data->object),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event->data->object),
            'invoice.payment_succeeded' => $this->handlePaymentSucceeded($event->data->object),
            'invoice.payment_failed' => $this->handlePaymentFailed($event->data->object),
            default => Log::info('Unhandled Stripe event: ' . $event->type),
        };
    }

    private function handleSubscriptionCreated(object $subscription): void
    {
        $user = User::where('stripe_customer_id', $subscription->customer)->first();
        if (!$user) return;

        $plan = SubscriptionPlan::where('stripe_monthly_price_id', $subscription->items->data[0]->price->id)
            ->orWhere('stripe_yearly_price_id', $subscription->items->data[0]->price->id)
            ->first();

        if (!$plan) return;

        UserSubscription::updateOrCreate(
            ['stripe_subscription_id' => $subscription->id],
            [
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'stripe_customer_id' => $subscription->customer,
                'stripe_price_id' => $subscription->items->data[0]->price->id,
                'status' => $subscription->status,
                'current_period_start' => now()->setTimestamp($subscription->current_period_start),
                'current_period_end' => now()->setTimestamp($subscription->current_period_end),
            ]
        );
    }

    private function handleSubscriptionUpdated(object $subscription): void
    {
        UserSubscription::where('stripe_subscription_id', $subscription->id)
            ->update([
                'status' => $subscription->status,
                'current_period_start' => now()->setTimestamp($subscription->current_period_start),
                'current_period_end' => now()->setTimestamp($subscription->current_period_end),
                'cancelled_at' => $subscription->canceled_at
                    ? now()->setTimestamp($subscription->canceled_at)
                    : null,
            ]);
    }

    private function handleSubscriptionDeleted(object $subscription): void
    {
        UserSubscription::where('stripe_subscription_id', $subscription->id)
            ->update(['status' => 'cancelled', 'cancelled_at' => now()]);
    }

    private function handlePaymentSucceeded(object $invoice): void
    {
        Log::info('Payment succeeded for invoice: ' . $invoice->id);
    }

    private function handlePaymentFailed(object $invoice): void
    {
        Log::warning('Payment failed for invoice: ' . $invoice->id);
        // Send notification to user
    }

    private function getOrCreateStripeCustomer(User $user): string
    {
        if ($user->stripe_customer_id) {
            return $user->stripe_customer_id;
        }

        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => ['user_id' => $user->id],
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer->id;
    }

    public function getUserPlan(User $user): SubscriptionPlan
    {
        return $user->activeSubscription?->plan
            ?? SubscriptionPlan::where('slug', 'free')->first();
    }

    public function canCreateWedding(User $user): bool
    {
        $plan = $this->getUserPlan($user);
        $count = $user->weddings()->count();
        return $count < $plan->max_weddings;
    }

    public function canAddGuest(User $user, int $weddingId): bool
    {
        $plan = $this->getUserPlan($user);
        $count = \App\Models\Guest::whereHas('wedding', fn($q) =>
            $q->where('user_id', $user->id)
        )->where('wedding_id', $weddingId)->count();
        return $count < $plan->max_guests;
    }

    public function canAddTable(User $user, int $planId): bool
    {
        $plan = $this->getUserPlan($user);
        $count = \App\Models\ReceptionTable::where('table_plan_id', $planId)->count();
        return $count < $plan->max_tables;
    }
}
