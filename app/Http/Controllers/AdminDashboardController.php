<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\ColorScheme;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $restaurants = Restaurant::paginate(10);
        return view('admin.dashboard', compact('restaurants'));
    }

    public function createForm()
    {
        return view('admin.create-restaurant');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'logo' => 'nullable|image',
            'primary_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'secondary_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'accent_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'text_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'border_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'background_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
        ], [
            'name.required' => 'اسم المطعم مطلوب',
            'primary_color.required' => 'اللون الأساسي مطلوب',
        ]);

        $slug = Str::slug($request->name) . '-' . Str::random(6);
        $username = Str::slug($request->name) . '_admin';
        $password = Str::random(12);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $restaurant = Restaurant::create([
            'name' => $request->name,
            'slug' => $slug,
            'logo_path' => $logoPath,
            'admin_email' => $request->email ?? 'admin@' . $slug . '.local',
            'admin_username' => $username,
            'admin_password' => Hash::make($password),
            'is_active' => true,
        ]);

        ColorScheme::create([
            'restaurant_id' => $restaurant->id,
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'accent_color' => $request->accent_color,
            'text_color' => $request->text_color,
            'border_color' => $request->border_color,
            'background_color' => $request->background_color,
        ]);

        return back()->with('success', "تم إنشاء المطعم بنجاح!\nاسم المستخدم: $username\nكلمة المرور: $password");
    }
}
