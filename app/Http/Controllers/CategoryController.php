<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $categories = $restaurant->categories()->orderBy('sort_order')->paginate(10);
        return view('restaurant.categories.index', compact('restaurant', 'categories'));
    }

    public function create($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        return view('restaurant.categories.create', compact('restaurant'));
    }

    public function store(Request $request, $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $restaurant->categories()->create($request->all());
        return redirect()->route('restaurant.categories.index', ['slug' => $slug])->with('success', 'تم إنشاء الفئة بنجاح');
    }

    public function edit($slug, $id)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $category = Category::findOrFail($id);
        return view('restaurant.categories.edit', compact('restaurant', 'category'));
    }

    public function update(Request $request, $slug, $id)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $category->update($request->all());
        return redirect()->route('restaurant.categories.index', ['slug' => $slug])->with('success', 'تم تحديث الفئة بنجاح');
    }

    public function destroy($slug, $id)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        $category = Category::findOrFail($id);
        $category->delete();
        return back()->with('success', 'تم حذف الفئة بنجاح');
    }
}
