<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RestaurantAuthController extends Controller
{
    public function showLogin(string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

        return view('restaurant.login', compact('restaurant'));
    }

    public function login(Request $request, string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'اسم المستخدم مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        $usernameOk = hash_equals($restaurant->admin_username, $data['username']);
        $passwordOk = Hash::check($data['password'], $restaurant->admin_password);

        if (! ($usernameOk && $passwordOk)) {
            return back()->withInput($request->only('username'))->with('error', 'بيانات الدخول غير صحيحة');
        }

        if (! $restaurant->is_active) {
            return back()->with('error', 'تم تعطيل هذا المطعم. تواصل مع إدارة المنصة.');
        }

        $request->session()->regenerate();
        $request->session()->put($restaurant->sessionKey(), true);

        return redirect()->route('restaurant.dashboard', ['slug' => $slug])
            ->with('success', 'تم تسجيل الدخول بنجاح');
    }

    public function logout(Request $request, string $slug)
    {
        $restaurant = Restaurant::where('slug', $slug)->firstOrFail();

        $request->session()->forget($restaurant->sessionKey());
        $request->session()->regenerate();

        return redirect()->route('restaurant.login', ['slug' => $slug])
            ->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
