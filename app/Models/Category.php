<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'description',
        'category_id'
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
        return $this->hasMany(Category::class, 'category_id')->with('subcategories');
    }

    /**
     * Get the parent category
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id')->with('parent');
    }
}
