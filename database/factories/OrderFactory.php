<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Status;
use App\Models\Cart;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $cart_id = Cart::all()->random()->id;

        return [
            'cart_id' => $cart_id,
            'reference' => 'REF-' . $this->faker->unique()->numberBetween(1000, 9999),
            'status_id' => Status::all()->random()->id,
            'total_price' => Cart::find($cart_id)->calculateTotalPrice(),
            'total_cost' => Cart::find($cart_id)->calculateTotalCost(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
