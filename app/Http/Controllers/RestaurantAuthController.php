<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Restaurant;

class RestaurantAuthController extends Controller
{
    public function showLogin($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        return view('restaurant.login', compact('restaurant'));
    }

    public function login(Request $request, $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'اسم المستخدم مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        if ($request->username === $restaurant->admin_username && Hash::check($request->password, $restaurant->admin_password)) {
            Session::put("restaurant_{$restaurant->id}_logged_in", true);
            return redirect()->route('restaurant.dashboard', ['slug' => $slug])->with('success', 'تم تسجيل الدخول بنجاح');
        }

        return back()->with('error', 'بيانات الدخول غير صحيحة');
    }

    public function logout($slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();
        Session::forget("restaurant_{$restaurant->id}_logged_in");
        return redirect()->route('restaurant.login', ['slug' => $slug])->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
