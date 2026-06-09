<?php

namespace Database\Factories;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionPlanFactory extends Factory
{
    protected $model = SubscriptionPlan::class;

    public function definition(): array
    {
        return [
            'name' => 'Gratuit',
            'slug' => 'free',
            'description' => 'Plan gratuit',
            'price_monthly' => 0,
            'price_yearly' => 0,
            'max_weddings' => 1,
            'max_guests' => 50,
            'max_tables' => 10,
            'has_sms' => false,
            'has_email_invitations' => false,
            'has_collaboration' => false,
            'has_advanced_table_plan' => false,
            'has_unlimited_checklist' => false,
            'is_active' => true,
            'sort_order' => 1,
        ];
    }

    public function premium(): static
    {
        return $this->state([
            'name' => 'Premium',
            'slug' => 'premium',
            'price_monthly' => 9.99,
            'price_yearly' => 79.99,
            'max_weddings' => 999,
            'max_guests' => 9999,
            'max_tables' => 9999,
            'has_sms' => true,
            'has_email_invitations' => true,
            'has_collaboration' => true,
            'has_advanced_table_plan' => true,
            'has_unlimited_checklist' => true,
        ]);
    }
}
