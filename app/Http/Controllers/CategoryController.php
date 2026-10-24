<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request, string $slug)
    {
        $restaurant = $this->currentRestaurant($request);
        $categories = $restaurant->categories()
            ->withCount('dishes')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(10);

        return view('restaurant.categories.index', compact('restaurant', 'categories'));
    }

    public function create(Request $request, string $slug)
    {
        $restaurant = $this->currentRestaurant($request);

        return view('restaurant.categories.create', compact('restaurant'));
    }

    public function store(Request $request, string $slug)
    {
        $restaurant = $this->currentRestaurant($request);

        $restaurant->categories()->create($this->validated($request));

        return redirect()->route('restaurant.categories.index', ['slug' => $slug])
            ->with('success', 'تم إنشاء الفئة بنجاح');
    }

    public function edit(Request $request, string $slug, string $id)
    {
        $restaurant = $this->currentRestaurant($request);
        $category = $restaurant->categories()->findOrFail($id); // مقيّد بمطعم المستخدم

        return view('restaurant.categories.edit', compact('restaurant', 'category'));
    }

    public function update(Request $request, string $slug, string $id)
    {
        $restaurant = $this->currentRestaurant($request);
        $category = $restaurant->categories()->findOrFail($id);

        $category->update($this->validated($request));

        return redirect()->route('restaurant.categories.index', ['slug' => $slug])
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    public function destroy(Request $request, string $slug, string $id)
    {
        $restaurant = $this->currentRestaurant($request);
        $category = $restaurant->categories()->findOrFail($id);

        // الأطباق تُحذف من قاعدة البيانات بالـ cascade، أما صورها فنحذفها هنا.
        $category->dishes->each->deleteImage();
        $category->delete();

        return back()->with('success', 'تم حذف الفئة بنجاح');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:16',
            'sort_order' => 'nullable|integer|min:0|max:65535',
        ], [
            'name.required' => 'اسم الفئة مطلوب',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
