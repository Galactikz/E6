<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Gratuit',
                'slug' => 'free',
                'description' => 'Commencez votre planification gratuitement',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'features' => [
                    '1 mariage',
                    'Checklist de base (50 tâches)',
                    'Jusqu\'à 50 invités',
                    'Jusqu\'à 10 tables',
                    'Budget de base',
                ],
                'max_weddings' => 1,
                'max_guests' => 50,
                'max_tables' => 10,
                'has_sms' => false,
                'has_email_invitations' => false,
                'has_collaboration' => false,
                'has_advanced_table_plan' => false,
                'has_unlimited_checklist' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Tout ce qu\'il faut pour un mariage parfait',
                'price_monthly' => 9.99,
                'price_yearly' => 79.99,
                'features' => [
                    'Mariages illimités',
                    'Checklist illimitée',
                    'Invités illimités',
                    'Tables illimitées',
                    'Envoi emails personnalisés',
                    'Envoi SMS',
                    'Partage collaboratif',
                    'Plan de table avancé',
                    'Export PDF',
                    'Support prioritaire',
                ],
                'max_weddings' => 999,
                'max_guests' => 9999,
                'max_tables' => 9999,
                'has_sms' => true,
                'has_email_invitations' => true,
                'has_collaboration' => true,
                'has_advanced_table_plan' => true,
                'has_unlimited_checklist' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
