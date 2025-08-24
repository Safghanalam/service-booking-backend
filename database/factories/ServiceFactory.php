<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services = [
            'Haircut',
            'Beard Trim',
            'Shave',
            'Hair Wash',
            'Hair Styling',
            'Facial',
            'Head Massage',
            'Hair Coloring',
            'Kids Haircut',
            'Beard Styling'
        ];

        return [
            'name' => fake()->randomElement($services),
            'category_id' => Category::factory(),
            'price' => 100,
            'duration' => 30,
            'is_featured' => fake()->boolean(),
            'is_active' => fake()->boolean(),
            'is_deleted' => fake()->boolean(),
        ];
    }
}
