<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'website',
        'phone_number',
    ];

    public function purchases() : HasMany
    {
        return $this->hasMany(Purchase::class);
    }

}
