<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function showLogin()
    {
        if ($this->authService->isAdminLoggedIn()) {
            return redirect()->route('admin.dashboard');
        }

        return view('pages.admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($this->authService->attemptLogin($credentials, $request)) {
            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Login berhasil.');
        }

        return back()
            ->withErrors([
                'username' => 'Username atau password salah.',
            ])
            ->onlyInput('username');
    }

    public function showRegister()
    {
        $admin = $this->authService->getLoggedAdmin();

        $this->authService->ensureAdminUtama($admin);

        $ppidPembantu = $this->authService->getPpidPembantuList();

        return view('pages.admin.auth.register', compact('admin', 'ppidPembantu'));
    }

    public function register(Request $request)
    {
        $admin = $this->authService->getLoggedAdmin();

        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:authorization,username',
            'email' => 'nullable|email|max:100|unique:authorization,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:1,2',
            'ppid_pembantuid' => 'nullable|exists:ppid_pembantu,id',
        ]);

        $this->authService->createAdminAccount($admin, $validated);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Akun admin berhasil dibuat.');
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);

        return redirect()
            ->route('admin.login')
            ->with('success', 'Logout berhasil.');
    }
}
