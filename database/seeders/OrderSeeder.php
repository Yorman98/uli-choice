<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Cart;
use App\Models\ProductCart;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add product to cart
        $cart = Cart::factory()->count(10)->create();
        
        $cart->each(function ($cart) {
            $cart->products()->saveMany(
                ProductCart::factory()->count(2)->make()
            );
        });

        // Add cart to order
        Order::factory()->count(10)->create();
    }
}
