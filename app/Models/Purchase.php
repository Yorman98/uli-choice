<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'description',
        'provider_id',
    ];

    public function provider() : BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
