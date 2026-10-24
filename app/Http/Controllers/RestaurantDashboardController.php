<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestaurantDashboardController extends Controller
{
    public function dashboard(Request $request, string $slug)
    {
        $restaurant = $this->currentRestaurant($request);

        $categoriesCount = $restaurant->categories()->count();
        $dishesCount = $restaurant->dishes()->count();
        $offersCount = $restaurant->offers()->count();

        return view('restaurant.dashboard', compact('restaurant', 'categoriesCount', 'dishesCount', 'offersCount'));
    }
}
