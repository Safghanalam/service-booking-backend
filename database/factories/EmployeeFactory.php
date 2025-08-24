<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'profession' => fake()->jobTitle(),
            'avatar' => fake()->imageUrl(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'social_links' => '{"instagram" : "https://google.com"}',
            'is_active' => fake()->randomElement([0, 1]),
            'is_deleted' => fake()->randomElement([0, 1])
        ];
    }
}
