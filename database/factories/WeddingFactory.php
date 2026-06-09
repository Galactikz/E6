<?php

namespace Database\Factories;

use App\Models\Wedding;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WeddingFactory extends Factory
{
    protected $model = Wedding::class;

    public function definition(): array
    {
        $name = $this->faker->firstName() . ' & ' . $this->faker->firstName();
        return [
            'user_id' => User::factory(),
            'name' => "Mariage de {$name}",
            'wedding_date' => $this->faker->dateTimeBetween('+3 months', '+2 years'),
            'city' => $this->faker->city(),
            'venue_name' => $this->faker->company(),
            'guest_count' => $this->faker->numberBetween(20, 200),
            'total_budget' => $this->faker->randomFloat(2, 5000, 50000),
            'slug' => Str::slug($name . '-' . $this->faker->year()),
            'is_active' => true,
        ];
    }
}
