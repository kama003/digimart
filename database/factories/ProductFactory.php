<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(3, true);
        return [
            'user_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\Category::factory(),
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'description' => fake()->paragraph(),
            'short_description' => fake()->sentence(),
            'product_type' => fake()->randomElement(['audio', 'video', '3d', 'template', 'graphic']),
            'price' => fake()->randomFloat(2, 5, 100),
            'license_type' => 'Standard License',
            'thumbnail_path' => 'thumbnails/test.jpg',
            'file_path' => 'products/test.zip',
            'file_size' => fake()->numberBetween(1000000, 100000000),
            'is_approved' => false,
            'is_active' => true,
            'downloads_count' => 0,
        ];
    }
}
