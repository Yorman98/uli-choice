<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
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
        'description'
    ];

    /**
     * Get the products associated with the category.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_category');
    }

    /**
     * Get the subcategories
     *
     * @return HasMany
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(Category::class)->with('subcategories');
    }
}
