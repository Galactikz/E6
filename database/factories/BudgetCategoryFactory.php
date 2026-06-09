<?php

namespace Database\Factories;

use App\Models\BudgetCategory;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetCategoryFactory extends Factory
{
    protected $model = BudgetCategory::class;

    public function definition(): array
    {
        return [
            'wedding_id' => Wedding::factory(),
            'name' => $this->faker->randomElement(['Salle', 'Traiteur', 'Photographe', 'DJ']),
            'slug' => $this->faker->slug(1),
            'color' => $this->faker->hexColor(),
            'planned_amount' => $this->faker->randomFloat(2, 500, 10000),
            'actual_amount' => $this->faker->randomFloat(2, 0, 5000),
            'sort_order' => $this->faker->numberBetween(1, 12),
            'is_custom' => false,
        ];
    }
}
