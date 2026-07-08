<?php

namespace App\Services\Admin;

use App\Models\Authorization;
use App\Models\PpidPembantu;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        protected AuthFactory $auth,
        protected Hasher $hasher,
        protected Authorization $authorization,
        protected PpidPembantu $ppidPembantu
    ) {}

    public function isAdminLoggedIn(): bool
    {
        return $this->auth->guard('admin')->check();
    }

    public function getLoggedAdmin(): ?Authorization
    {
        return $this->auth->guard('admin')->user();
    }

    public function attemptLogin(array $credentials, Request $request): bool
    {
        if (! $this->auth->guard('admin')->attempt($credentials)) {
            return false;
        }

        $request->session()->regenerate();

        return true;
    }

    public function getPpidPembantuList(): Collection
    {
        return $this->ppidPembantu
            ->newQuery()
            ->orderBy('nama')
            ->get();
    }

    public function createAdminAccount(Authorization $admin, array $data): Authorization
    {
        $this->ensureAdminUtama($admin);

        $this->validateAdminPembantuHasPpidPembantu($data);

        return $this->authorization
            ->newQuery()
            ->create([
                'username' => $data['username'],
                'email' => $data['email'] ?? null,
                'password' => $this->hasher->make($data['password']),
                'role' => $data['role'],
                'user_publikid' => null,
                'ppid_pembantuid' => (int) $data['role'] === 2
                    ? $data['ppid_pembantuid']
                    : null,
            ]);
    }

    public function logout(Request $request): void
    {
        $this->auth->guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function ensureAdminUtama(?Authorization $admin): void
    {
        if (! $admin || (int) $admin->role !== 1) {
            throw new AuthorizationException(
                'Hanya admin utama yang dapat membuat akun admin.'
            );
        }
    }

    private function validateAdminPembantuHasPpidPembantu(array $data): void
    {
        if ((int) $data['role'] === 2 && empty($data['ppid_pembantuid'])) {
            throw ValidationException::withMessages([
                'ppid_pembantuid' => 'PPID Pembantu wajib dipilih untuk akun admin pembantu.',
            ]);
        }
    }
}
