<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Variation;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variation>
 */
class VariationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $price = fake()->randomFloat(2, 1, 100);

        return [
            'product_id' => Product::all()->random()->id,
            'sku' => fake()->bothify('??##??####'),
            'price' => $price,
            'cost' => function () use ($price) {
                return fake()->randomFloat(2, 1, $price); // generate a random cost between 1 and the price
            },
            'image' => fake()->imageUrl(),
            'stock' => fake()->numberBetween(1, 100),
        ];
    }
}
