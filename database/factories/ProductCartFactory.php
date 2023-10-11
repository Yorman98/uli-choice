<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductCart;
use App\Models\Variation;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCart>
 */
class ProductCartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $variation = Variation::all()->random();
        $quantity = fake()->numberBetween(1, 3);

        return [
            'variation_id' => $variation->id,
            'product_id' => $variation->product_id,
            'quantity' => $quantity,
            'unit_price' => $variation->price * $quantity,
            'unit_cost' => $variation->cost * $quantity,
        ];
    }
}
