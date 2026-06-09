<?php

namespace Database\Factories;

use App\Models\ChecklistTask;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChecklistTaskFactory extends Factory
{
    protected $model = ChecklistTask::class;

    public function definition(): array
    {
        return [
            'wedding_id' => Wedding::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->sentence(),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => 'pending',
            'category' => $this->faker->randomElement(['Prestataires', 'Organisation', 'Invités', 'Tenue']),
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }

    public function done(): static
    {
        return $this->state(['status' => 'done']);
    }
}
