<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        $adminEmail = Config::get('app.admin_email');
        $adminPassword = Config::get('app.admin_password');

        if ($request->email === $adminEmail && Hash::check($request->password, $adminPassword)) {
            Session::put('admin_logged_in', true);
            return redirect()->route('admin.dashboard')->with('success', 'تم تسجيل الدخول بنجاح');
        }

        return back()->with('error', 'بيانات الدخول غير صحيحة');
    }

    public function logout()
    {
        Session::forget('admin_logged_in');
        return redirect()->route('admin.login')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
