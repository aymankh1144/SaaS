<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

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
    ];

    protected $hidden = ['admin_password'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** مفتاح الجلسة الذي يدل على أن صاحب هذا المطعم سجّل الدخول. */
    public function sessionKey(): string
    {
        return "restaurant_{$this->id}_logged_in";
    }

    /** يحذف ملفات المطعم (الشعار وصور الأطباق) من التخزين. */
    public function deleteFiles(): void
    {
        $paths = $this->dishes()->whereNotNull('image_path')->pluck('image_path');

        if ($this->logo_path) {
            $paths->push($this->logo_path);
        }

        Storage::disk('public')->delete($paths->all());
    }

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
