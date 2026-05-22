<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    protected $fillable = [
        'restaurant_id',
        'title',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function isActive(): bool
    {
        return now()->between($this->start_date, $this->end_date);
    }
}
