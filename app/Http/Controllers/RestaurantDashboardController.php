<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;

class RestaurantDashboardController extends Controller
{
    public function dashboard($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $categoriesCount = $restaurant->categories()->count();
        $dishesCount = $restaurant->dishes()->count();
        $offersCount = $restaurant->offers()->count();

        return view('restaurant.dashboard', compact('restaurant', 'categoriesCount', 'dishesCount', 'offersCount'));
    }
}
