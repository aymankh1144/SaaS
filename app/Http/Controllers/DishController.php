<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DishController extends Controller
{
    public function index(Request $request, string $slug)
    {
        $restaurant = $this->currentRestaurant($request);
        $dishes = $restaurant->dishes()->with('category')->latest('id')->paginate(10);

        return view('restaurant.dishes.index', compact('restaurant', 'dishes'));
    }

    public function create(Request $request, string $slug)
    {
        $restaurant = $this->currentRestaurant($request);
        $categories = $restaurant->categories()->orderBy('sort_order')->get();

        return view('restaurant.dishes.create', compact('restaurant', 'categories'));
    }

    public function store(Request $request, string $slug)
    {
        $restaurant = $this->currentRestaurant($request);
        $data = $this->validated($request, $restaurant);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('dishes', 'public');
        }
        unset($data['image']);

        $restaurant->dishes()->create($data);

        return redirect()->route('restaurant.dishes.index', ['slug' => $slug])
            ->with('success', 'تم إضافة الطبق بنجاح');
    }

    public function edit(Request $request, string $slug, string $id)
    {
        $restaurant = $this->currentRestaurant($request);
        $dish = $restaurant->dishes()->findOrFail($id);
        $categories = $restaurant->categories()->orderBy('sort_order')->get();

        return view('restaurant.dishes.edit', compact('restaurant', 'dish', 'categories'));
    }

    public function update(Request $request, string $slug, string $id)
    {
        $restaurant = $this->currentRestaurant($request);
        $dish = $restaurant->dishes()->findOrFail($id);
        $data = $this->validated($request, $restaurant);

        if ($request->hasFile('image')) {
            $dish->deleteImage(); // لا نترك الصورة القديمة يتيمة
            $data['image_path'] = $request->file('image')->store('dishes', 'public');
        }
        unset($data['image']);

        $dish->update($data);

        return redirect()->route('restaurant.dishes.index', ['slug' => $slug])
            ->with('success', 'تم تحديث الطبق بنجاح');
    }

    public function destroy(Request $request, string $slug, string $id)
    {
        $restaurant = $this->currentRestaurant($request);
        $dish = $restaurant->dishes()->findOrFail($id);

        $dish->deleteImage();
        $dish->delete();

        return back()->with('success', 'تم حذف الطبق بنجاح');
    }

    private function validated(Request $request, Restaurant $restaurant): array
    {
        $data = $request->validate([
            // الفئة يجب أن تتبع نفس المطعم، لا أي فئة في المنصة.
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where('restaurant_id', $restaurant->id),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ], [
            'category_id.required' => 'الفئة مطلوبة',
            'category_id.exists' => 'الفئة المختارة غير صالحة',
            'name.required' => 'اسم الطبق مطلوب',
            'price.required' => 'السعر مطلوب',
        ]);

        // خانات الاختيار: غير الموجودة في الطلب تعني false.
        $data['is_available'] = $request->boolean('is_available');
        $data['is_vegan'] = $request->boolean('is_vegan');
        $data['is_spicy'] = $request->boolean('is_spicy');

        return $data;
    }
}
