<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => fake()->name(),
            'category_id' => 1,
            'image' => fake()->imageUrl(),
            'description' => fake()->text(),
            'position' => fake()->streetAddress(),
            'quantity' => fake()->numberBetween(1, 200),
            'price' => fake()->numberBetween(50000, 500000),
            'loan_price' => fake()->numberBetween(5000, 100000),
            'author' => fake()->name(),
            'published_at' => fake()->date(),
            'status' => fake()->boolean()
        ];
    }
}
