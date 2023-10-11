<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->count(6)->create();
        Product::factory()->count(10)->create();

        // Add one category to each product
        Product::all()->each(function ($product) {
            $product->categories()->attach(
                Category::all()->random(1)->pluck('id')->toArray()
            );
        });
    }
}
