<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;

class PlatformController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::where('is_active', true)->paginate(12);
        return view('platform.index', compact('restaurants'));
    }

    public function about()
    {
        return view('platform.about');
    }

    public function restaurants()
    {
        $restaurants = Restaurant::where('is_active', true)->paginate(12);
        return view('platform.restaurants', compact('restaurants'));
    }
}
