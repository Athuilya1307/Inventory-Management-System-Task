<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'code' => fake()->unique()->bothify('P###'),
            'price' => fake()->randomFloat(2, 100, 5000),
            'tax_percentage' => fake()->randomElement([5, 12, 18, 28]),
            'stock' => fake()->numberBetween(1, 100),
        ];
    }
}
