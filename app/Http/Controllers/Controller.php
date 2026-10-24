<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * المطعم الحالي الذي حمّله الـ middleware `restaurant.auth`.
     * كل عمليات لوحة المطعم يجب أن تمر عبر علاقات هذا الكائن
     * ($restaurant->dishes() ...) حتى لا يصل مطعم إلى بيانات مطعم آخر.
     */
    protected function currentRestaurant(Request $request): Restaurant
    {
        return $request->attributes->get('restaurant');
    }
}
