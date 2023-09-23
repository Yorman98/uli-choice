<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cart_variation');

        Schema::create('product_cart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('variation_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->float('unit_price')->nullable();
            $table->float('unit_cost')->nullable();
            $table->timestamps();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->boolean('active')->default(true)->after('user_id');

            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_cart');

        Schema::create('cart_variation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('variation_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->float('unit_price')->nullable();
            $table->float('unit_cost')->nullable();
            $table->timestamps();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
};
