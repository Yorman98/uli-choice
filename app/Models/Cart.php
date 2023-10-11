<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'active'
    ];

    /**
     * Get products in the cart
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(ProductCart::class, 'cart_id');
    }
    
    /**
     * Get the user that owns the cart.
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user that owns the cart.
     *
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calculateTotalPrice()
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->unit_price * $product->quantity;
        }
        return $total;
    }

    public function calculateTotalCost()
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->unit_cost * $product->quantity;
        }
        return $total;
    }

}
