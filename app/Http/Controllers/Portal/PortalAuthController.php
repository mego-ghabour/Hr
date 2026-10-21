<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PortalAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && $user->isAdministrative() && !$user->employee) {
                return redirect('/admin');
            }

            return redirect()->route('portal.dashboard');
        }

        return view('portal.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'يرجى إدخال البريد الإلكتروني',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'password.required' => 'يرجى إدخال كلمة المرور',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user && $user->isAdministrative() && !$user->employee) {
                return redirect()->intended('/admin');
            }

            return redirect()->intended(route('portal.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login')->with('success', 'تم تسجيل الخروج بنجاح.');
    }
}
