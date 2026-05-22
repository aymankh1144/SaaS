<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantUser extends Model
{
    protected $fillable = [
        'restaurant_id',
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = ['password'];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
