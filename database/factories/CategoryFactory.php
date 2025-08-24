<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Haircuts',
            'Beard & Shave',
            'Hair Styling',
            'Hair Coloring',
            'Facials & Skincare',
            'Massage',
            'Kids Grooming',
            'Premium Packages',
        ];

        return [
            'name'        => fake()->randomElement($categories),
            'parent'      => 0,
            'is_featured' => fake()->boolean(),
            'is_active'   => fake()->boolean(),
        ];
    }
}
