<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'body' => $this->faker->paragraphs(rand(1,3), true),
            'rating' => $this->faker->numberBetween(1,5),
            'created_at' => now()->subDays(rand(0,30)),
            'updated_at' => now(),
        ];
    }
}
