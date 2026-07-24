<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserPublic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnifiedLoginController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::guard('public')->check()) {
            return redirect()->route('public.permohonan.index');
        }

        return view('pages.public.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::guard('public')->check()) {
            return redirect()->route('public.permohonan.index');
        }

        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string'],
        ], [
            'identifier.required' => 'Email atau username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $identifier = trim($validated['identifier']);
        $password = $validated['password'];

        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL) !== false;

        if ($isEmail) {
            $publicAuthenticated = Auth::guard('public')->attempt([
                'email' => strtolower($identifier),
                'password' => $password,
                'is_aktif' => 1,
            ]);

            if ($publicAuthenticated) {
                $request->session()->regenerate();

                return redirect()
                    ->route('public.permohonan.index')
                    ->with('success', 'Login warga berhasil.');
            }

            $inactiveAccountExists = UserPublic::query()
                ->where('email', strtolower($identifier))
                ->where('is_aktif', 0)
                ->exists();

            if ($inactiveAccountExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'identifier' => 'Akun warga sudah terdaftar tetapi belum aktif.',
                    ]);
            }
        }

        $adminAuthenticated = Auth::guard('admin')->attempt([
            'username' => $identifier,
            'password' => $password,
        ]);

        if ($adminAuthenticated) {
            $request->session()->regenerate();

            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Login admin berhasil.');
        }

        return back()
            ->withInput()
            ->withErrors([
                'identifier' => 'Email, username, atau password yang dimasukkan tidak sesuai.',
            ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        Auth::guard('public')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('beranda')
            ->with('success', 'Anda berhasil keluar dari sistem.');
    }
}
