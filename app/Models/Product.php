<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * Hide pivot table ids
     *
     * @var string[] $hidden
     */
    protected $hidden = ['pivot'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'image'
    ];

    /**
     * Get the categories associated with the product.
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    /**
     * Get the variations associated with the product.
     *
     * @return HasMany
     */
    public function variations(): HasMany
    {
        return $this->hasMany(Variation::class);
    }
}
