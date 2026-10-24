<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminAuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        $adminEmail = (string) config('menusaas.admin_email');
        $adminHash = (string) config('menusaas.admin_password_hash');

        // نفحص الاثنين دائماً (دون short-circuit) حتى لا يختلف زمن الرد.
        $emailOk = $adminEmail !== ''
            && hash_equals(mb_strtolower($adminEmail), mb_strtolower($data['email']));
        $passwordOk = $this->passwordMatches($data['password'], $adminHash);

        if ($emailOk && $passwordOk) {
            $request->session()->regenerate(); // يمنع session fixation
            $request->session()->put('admin_logged_in', true);

            return redirect()->route('admin.dashboard')->with('success', 'تم تسجيل الدخول بنجاح');
        }

        return back()->withInput($request->only('email'))->with('error', 'بيانات الدخول غير صحيحة');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        $request->session()->regenerate();

        return redirect()->route('admin.login')->with('success', 'تم تسجيل الخروج بنجاح');
    }

    private function passwordMatches(string $plain, string $hash): bool
    {
        if ($hash === '') {
            return false; // ADMIN_PASSWORD_HASH غير مضبوط
        }

        try {
            return Hash::check($plain, $hash);
        } catch (RuntimeException) {
            return false; // الهاش في .env بصيغة غير صالحة
        }
    }
}
