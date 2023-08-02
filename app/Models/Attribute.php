<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attribute extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'attribute_group_id'
    ];

     /**
     * Get the group that owns the attribute.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(AttributeGroup::class);
    }

    
}
