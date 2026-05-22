<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColorScheme extends Model
{
    protected $fillable = [
        'restaurant_id',
        'primary_color',
        'secondary_color',
        'accent_color',
        'text_color',
        'border_color',
        'background_color',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
