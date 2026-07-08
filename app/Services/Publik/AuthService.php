<?php

namespace App\Services\Publik;

use App\Models\UserPublic;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;

class AuthService
{
    public function __construct(
        protected AuthFactory $auth
    ) {}

    public function isLoggedIn(): bool
    {
        return $this->auth->guard('public')->check();
    }

    public function getLoggedUser(): ?UserPublic
    {
        return $this->auth->guard('public')->user();
    }

    public function attemptLogin(array $credentials, Request $request): bool
    {
        $credentials['is_aktif'] = 1;

        if (! $this->auth->guard('public')->attempt($credentials)) {
            return false;
        }

        $request->session()->regenerate();

        return true;
    }

    public function logout(Request $request): void
    {
        $this->auth->guard('public')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
