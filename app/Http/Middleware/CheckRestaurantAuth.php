<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRestaurantAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $slug = (string) $request->route('slug');
        $restaurant = Restaurant::where('slug', $slug)->first();

        if (! $restaurant) {
            abort(404);
        }

        if (! $request->session()->get($restaurant->sessionKey())) {
            return redirect()->route('restaurant.login', ['slug' => $slug]);
        }

        // مطعم تم تعطيله بعد تسجيل الدخول: ننهي الجلسة فوراً.
        if (! $restaurant->is_active) {
            $request->session()->forget($restaurant->sessionKey());

            return redirect()
                ->route('restaurant.login', ['slug' => $slug])
                ->with('error', 'تم تعطيل هذا المطعم. تواصل مع إدارة المنصة.');
        }

        $request->attributes->set('restaurant', $restaurant);

        return $next($request);
    }
}
