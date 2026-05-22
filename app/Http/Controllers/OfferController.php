<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $offers = $restaurant->offers()->paginate(10);
        return view('restaurant.offers.index', compact('restaurant', 'offers'));
    }

    public function create($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        return view('restaurant.offers.create', compact('restaurant'));
    }

    public function store(Request $request, $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title' => 'required|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $restaurant->offers()->create($request->all());
        return redirect()->route('restaurant.offers.index', ['slug' => $slug])->with('success', 'تم إنشاء العرض بنجاح');
    }

    public function edit($slug, $id)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $offer = Offer::findOrFail($id);
        return view('restaurant.offers.edit', compact('restaurant', 'offer'));
    }

    public function update(Request $request, $slug, $id)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $offer = Offer::findOrFail($id);

        $request->validate([
            'title' => 'required|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $offer->update($request->all());
        return redirect()->route('restaurant.offers.index', ['slug' => $slug])->with('success', 'تم تحديث العرض بنجاح');
    }

    public function destroy($slug, $id)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $offer = Offer::findOrFail($id);
        $offer->delete();
        return back()->with('success', 'تم حذف العرض بنجاح');
    }
}
