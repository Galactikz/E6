<?php

namespace Database\Factories;

use App\Models\ChecklistTemplate;
use App\Models\ChecklistTemplateTask;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChecklistTemplateTaskFactory extends Factory
{
    protected $model = ChecklistTemplateTask::class;

    public function definition(): array
    {
        return [
            'checklist_template_id' => ChecklistTemplate::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->sentence(),
            'months_before_wedding' => $this->faker->numberBetween(1, 18),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'category' => $this->faker->randomElement(['Prestataires', 'Organisation', 'Invités']),
            'sort_order' => $this->faker->numberBetween(1, 50),
        ];
    }
}
