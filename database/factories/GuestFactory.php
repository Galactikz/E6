<?php

namespace Database\Factories;

use App\Models\Guest;
use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GuestFactory extends Factory
{
    protected $model = Guest::class;

    public function definition(): array
    {
        return [
            'wedding_id' => Wedding::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'rsvp_status' => $this->faker->randomElement(['pending', 'confirmed', 'declined']),
            'meal_type' => $this->faker->randomElement(['adult', 'child']),
            'invitation_sent' => false,
            'rsvp_token' => Str::random(64),
            'plus_one' => false,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(['rsvp_status' => 'confirmed']);
    }

    public function pending(): static
    {
        return $this->state(['rsvp_status' => 'pending']);
    }
}
