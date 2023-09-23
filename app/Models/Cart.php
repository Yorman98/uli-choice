<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
