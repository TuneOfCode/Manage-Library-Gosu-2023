<?php

namespace Database\Factories;

use App\Enums\StatusRentBook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookUser>
 */
class BookUserFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {

        return [
            'user_id' => fake()->numberBetween(2, 10),
            'book_id' => fake()->numberBetween(1, 5),
            'amount' => fake()->numberBetween(1, 10),
            'payment' => fake()->numberBetween(0, 1000000),
        ];
    }
}
