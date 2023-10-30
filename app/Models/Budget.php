<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_links',
        'status_id',
        'price',
        'cost',
        'message'
    ];

    // Cast product_links to array
    protected $casts = [
        'product_links' => 'array'
    ];

    // Hide pivot table ids
    protected $with = ['status', 'user'];

    /**
     * Get the user that owns the budget.
     * Using the user_budget table
    */
    public function user()
    {
        return $this->belongsToMany(User::class, 'user_budget');
    }

    /**
     * Get the status associated with the budget.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
