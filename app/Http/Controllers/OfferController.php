<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfferController extends Controller
{
    public function index(Request $request, string $slug)
    {
        $restaurant = $this->currentRestaurant($request);
        $offers = $restaurant->offers()->latest('id')->paginate(10);

        return view('restaurant.offers.index', compact('restaurant', 'offers'));
    }

    public function create(Request $request, string $slug)
    {
        $restaurant = $this->currentRestaurant($request);

        return view('restaurant.offers.create', compact('restaurant'));
    }

    public function store(Request $request, string $slug)
    {
        $restaurant = $this->currentRestaurant($request);

        $restaurant->offers()->create($this->validated($request));

        return redirect()->route('restaurant.offers.index', ['slug' => $slug])
            ->with('success', 'تم إنشاء العرض بنجاح');
    }

    public function edit(Request $request, string $slug, string $id)
    {
        $restaurant = $this->currentRestaurant($request);
        $offer = $restaurant->offers()->findOrFail($id);

        return view('restaurant.offers.edit', compact('restaurant', 'offer'));
    }

    public function update(Request $request, string $slug, string $id)
    {
        $restaurant = $this->currentRestaurant($request);
        $offer = $restaurant->offers()->findOrFail($id);

        $offer->update($this->validated($request));

        return redirect()->route('restaurant.offers.index', ['slug' => $slug])
            ->with('success', 'تم تحديث العرض بنجاح');
    }

    public function destroy(Request $request, string $slug, string $id)
    {
        $restaurant = $this->currentRestaurant($request);
        $restaurant->offers()->findOrFail($id)->delete();

        return back()->with('success', 'تم حذف العرض بنجاح');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'discount_type' => ['required', Rule::in(['percentage', 'fixed'])],
            // النسبة المئوية لا تتجاوز 100.
            'discount_value' => [
                'required',
                'numeric',
                'gt:0',
                'max:999999.99',
                Rule::when($request->input('discount_type') === 'percentage', ['max:100']),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ], [
            'title.required' => 'عنوان العرض مطلوب',
            'discount_value.max' => 'قيمة الخصم أكبر من الحد المسموح',
            'end_date.after' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
        ]);
    }
}
