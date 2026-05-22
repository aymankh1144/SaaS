<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Dish;
use App\Models\Category;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function index($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $dishes = $restaurant->dishes()->paginate(10);
        return view('restaurant.dishes.index', compact('restaurant', 'dishes'));
    }

    public function create($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $categories = $restaurant->categories()->get();
        return view('restaurant.dishes.create', compact('restaurant', 'categories'));
    }

    public function store(Request $request, $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('dishes', 'public');
        }

        $restaurant->dishes()->create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $imagePath,
            'is_available' => $request->has('is_available'),
            'is_vegan' => $request->has('is_vegan'),
            'is_spicy' => $request->has('is_spicy'),
        ]);

        return redirect()->route('restaurant.dishes.index', ['slug' => $slug])->with('success', 'تم إضافة الطبق بنجاح');
    }

    public function edit($slug, $id)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $dish = Dish::findOrFail($id);
        $categories = $restaurant->categories()->get();
        return view('restaurant.dishes.edit', compact('restaurant', 'dish', 'categories'));
    }

    public function update(Request $request, $slug, $id)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $dish = Dish::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $imagePath = $dish->image_path;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('dishes', 'public');
        }

        $dish->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $imagePath,
            'is_available' => $request->has('is_available'),
            'is_vegan' => $request->has('is_vegan'),
            'is_spicy' => $request->has('is_spicy'),
        ]);

        return redirect()->route('restaurant.dishes.index', ['slug' => $slug])->with('success', 'تم تحديث الطبق بنجاح');
    }

    public function destroy($slug, $id)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $dish = Dish::findOrFail($id);
        $dish->delete();
        return back()->with('success', 'تم حذف الطبق بنجاح');
    }
}
