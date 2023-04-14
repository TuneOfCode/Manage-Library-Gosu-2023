<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'quantity' => fake()->numberBetween(0,200),
            'price' => fake()->numberBetween(20000,200000),
            'loan_price' => fake()->numberBetween(5000,20000),
            'status' => fake()->boolean(),
            'author' => fake()->name(),
            'published_at' => fake()->dateTimeBetween(),
        ];
    }
}
