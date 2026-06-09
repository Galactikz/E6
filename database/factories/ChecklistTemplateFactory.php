<?php

namespace Database\Factories;

use App\Models\ChecklistTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChecklistTemplateFactory extends Factory
{
    protected $model = ChecklistTemplate::class;

    public function definition(): array
    {
        return [
            'name' => 'Mariage en ' . $this->faker->numberBetween(6, 18) . ' mois',
            'slug' => $this->faker->unique()->slug(3),
            'description' => $this->faker->sentence(),
            'months_before' => $this->faker->randomElement([6, 12, 18]),
            'is_premium' => false,
            'is_active' => true,
        ];
    }
}
