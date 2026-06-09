<?php

namespace Database\Factories;

use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetItemFactory extends Factory
{
    protected $model = BudgetItem::class;

    public function definition(): array
    {
        $planned = $this->faker->randomFloat(2, 100, 5000);
        return [
            'budget_category_id' => BudgetCategory::factory(),
            'wedding_id' => Wedding::factory(),
            'name' => $this->faker->sentence(3),
            'planned_amount' => $planned,
            'actual_amount' => $this->faker->randomFloat(2, 0, $planned),
            'is_paid' => $this->faker->boolean(30),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'paid']),
            'vendor_name' => $this->faker->company(),
        ];
    }
}
