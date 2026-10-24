<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * موظفو المطعم (owner / manager / staff).
 * الجدول والنموذج جاهزان، لكن تسجيل دخول الموظفين لم يُبنَ بعد
 * (انظر "الميزات المستقبلية" في README).
 */
class RestaurantUser extends Model
{
    protected $fillable = [
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
