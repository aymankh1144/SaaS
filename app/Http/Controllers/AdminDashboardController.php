<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    private const HEX_COLOR = ['required', 'regex:/^#[0-9A-F]{6}$/i'];

    public function dashboard()
    {
        $restaurants = Restaurant::latest()->paginate(10);

        return view('admin.dashboard', compact('restaurants'));
    }

    public function createForm()
    {
        return view('admin.create-restaurant');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'primary_color' => self::HEX_COLOR,
            'secondary_color' => self::HEX_COLOR,
            'accent_color' => self::HEX_COLOR,
            'text_color' => self::HEX_COLOR,
            'border_color' => self::HEX_COLOR,
            'background_color' => self::HEX_COLOR,
        ], [
            'name.required' => 'اسم المطعم مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'primary_color.required' => 'اللون الأساسي مطلوب',
            'primary_color.regex' => 'صيغة اللون غير صحيحة',
        ]);

        $slug = $this->uniqueSlug($data['name']);
        $username = $this->uniqueUsername($data['name']);
        $password = Str::random(14);

        $logoPath = $request->hasFile('logo')
            ? $request->file('logo')->store('logos', 'public')
            : null;

        DB::transaction(function () use ($data, $slug, $username, $password, $logoPath) {
            $restaurant = Restaurant::create([
                'name' => $data['name'],
                'slug' => $slug,
                'logo_path' => $logoPath,
                'admin_email' => $data['email'] ?? null,
                'admin_username' => $username,
                'admin_password' => Hash::make($password),
                'is_active' => true,
            ]);

            $restaurant->colorScheme()->create([
                'primary_color' => $data['primary_color'],
                'secondary_color' => $data['secondary_color'],
                'accent_color' => $data['accent_color'],
                'text_color' => $data['text_color'],
                'border_color' => $data['border_color'],
                'background_color' => $data['background_color'],
            ]);
        });

        return redirect()->route('admin.dashboard')->with('success', $this->credentialsMessage(
            'تم إنشاء المطعم بنجاح! احفظ البيانات التالية الآن فهي لا تُعرض مرة أخرى.',
            $slug,
            $username,
            $password
        ));
    }

    public function toggle(Restaurant $restaurant)
    {
        $restaurant->update(['is_active' => ! $restaurant->is_active]);

        return back()->with('success', $restaurant->is_active ? 'تم تفعيل المطعم' : 'تم تعطيل المطعم');
    }

    public function resetPassword(Restaurant $restaurant)
    {
        $password = Str::random(14);
        $restaurant->update(['admin_password' => Hash::make($password)]);

        return back()->with('success', $this->credentialsMessage(
            'تم تعيين كلمة مرور جديدة. احفظها الآن فهي لا تُعرض مرة أخرى.',
            $restaurant->slug,
            $restaurant->admin_username,
            $password
        ));
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->deleteFiles();
        $restaurant->delete(); // الفئات والأطباق والعروض تُحذف تلقائياً (cascade)

        return back()->with('success', 'تم حذف المطعم وكل بياناته');
    }

    private function credentialsMessage(string $title, string $slug, string $username, string $password): string
    {
        return $title
            . "\nرابط الدخول: " . route('restaurant.login', ['slug' => $slug])
            . "\nاسم المستخدم: {$username}"
            . "\nكلمة المرور: {$password}";
    }

    private function uniqueSlug(string $name): string
    {
        $base = rtrim(Str::limit(Str::slug($name, '-', 'ar'), 40, ''), '-') ?: 'restaurant';

        do {
            $slug = $base . '-' . Str::lower(Str::random(6));
        } while (Restaurant::where('slug', $slug)->exists());

        return $slug;
    }

    private function uniqueUsername(string $name): string
    {
        $base = rtrim(Str::limit(Str::slug($name, '_', 'ar'), 30, ''), '_') ?: 'restaurant';

        do {
            $username = $base . '_' . Str::lower(Str::random(4));
        } while (Restaurant::where('admin_username', $username)->exists());

        return $username;
    }
}
