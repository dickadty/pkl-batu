<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Services\Publik\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function showLogin()
    {
        if ($this->authService->isLoggedIn()) {
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

        if ($this->authService->attemptLogin($credentials, $request)) {
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
        $this->authService->logout($request);

        return redirect()
            ->route('public.informasi.index')
            ->with('success', 'Logout berhasil.');
    }
}
