<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // restaurant_id غير قابل للتعبئة الجماعية عن قصد؛ يُضبط فقط عبر
    // العلاقة ($restaurant->categories()->create(...)).
    protected $fillable = [
        'name',
        'description',
        'icon',
        'sort_order',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function dishes(): HasMany
    {
        return $this->hasMany(Dish::class);
    }
}
