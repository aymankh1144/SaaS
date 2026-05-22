<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'admin_email',
        'admin_username',
        'admin_password',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $hidden = ['admin_password'];

    public function colorScheme(): HasOne
    {
        return $this->hasOne(ColorScheme::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function dishes(): HasMany
    {
        return $this->hasMany(Dish::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(RestaurantUser::class);
    }
}
