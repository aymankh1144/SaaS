<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Models\Restaurant;

class CheckRestaurantAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');
        $restaurant = Restaurant::where('slug', $slug)->first();

        if (!$restaurant) {
            return redirect('/');
        }

        $sessionKey = "restaurant_{$restaurant->id}_logged_in";
        
        if (!Session::get($sessionKey)) {
            return redirect()->route('restaurant.login', ['slug' => $slug]);
        }

        return $next($request);
    }
}
