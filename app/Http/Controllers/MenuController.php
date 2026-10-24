<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;

class MenuController extends Controller
{
    public function show(string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)
            ->where('is_active', true)
            ->with('colorScheme')
            ->firstOrFail();

        // تحميل مسبق للأطباق المتوفرة فقط (يتفادى استعلاماً لكل فئة).
        $categories = $restaurant->categories()
            ->with(['dishes' => fn ($query) => $query->where('is_available', true)->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $offers = $restaurant->offers()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return view('menu.show', compact('restaurant', 'categories', 'offers'));
    }
}
