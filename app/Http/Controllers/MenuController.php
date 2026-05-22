<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;

class MenuController extends Controller
{
    public function show($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $categories = $restaurant->categories()->orderBy('sort_order')->get();
        $offers = $restaurant->offers()->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return view('menu.show', compact('restaurant', 'categories', 'offers'));
    }
}
