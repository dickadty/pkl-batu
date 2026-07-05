<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('public')->check()) {
            return redirect()->route('public.permohonan.create');
        }

        return view('pages.public.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials['is_aktif'] = 1;

        if (Auth::guard('public')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()
                ->intended(route('public.permohonan.create'))
                ->with('success', 'Login berhasil.');
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('public')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('public.informasi.index')
            ->with('success', 'Logout berhasil.');
    }
}
