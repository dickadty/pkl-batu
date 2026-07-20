<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AccountSettingController extends Controller
{
    public function index(): View
    {
        $admin = $this->currentAdmin();

        $admin->loadMissing([
            'ppidPembantu:id,nama',
        ]);

        return view(
            'pages.admin.account-settings.index',
            compact('admin')
        );
    }

    public function updateProfile(
        Request $request
    ): RedirectResponse {
        $admin = $this->currentAdmin();

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique(
                    'authorization',
                    'username'
                )->ignore(
                    $admin->id,
                    'id'
                ),
            ],

            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique(
                    'authorization',
                    'email'
                )->ignore(
                    $admin->id,
                    'id'
                ),
            ],
        ]);

        $email = trim(
            (string) ($validated['email'] ?? '')
        );

        $admin->update([
            'username' => trim(
                (string) $validated['username']
            ),

            'email' => $email !== ''
                ? $email
                : null,
        ]);

        return redirect()
            ->route('admin.account-settings.index')
            ->with(
                'success',
                'Informasi akun berhasil diperbarui.'
            );
    }

    public function updatePassword(
        Request $request
    ): RedirectResponse {
        $admin = $this->currentAdmin();

        $validated = $request->validate([
            'current_password' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers(),
            ],
        ], [
            'current_password.required' =>
            'Password saat ini wajib diisi.',

            'password.required' =>
            'Password baru wajib diisi.',

            'password.confirmed' =>
            'Konfirmasi password baru tidak sesuai.',
        ]);

        if (
            ! Hash::check(
                $validated['current_password'],
                (string) $admin->password
            )
        ) {
            throw ValidationException::withMessages([
                'current_password' =>
                'Password saat ini tidak sesuai.',
            ]);
        }

        if (
            Hash::check(
                $validated['password'],
                (string) $admin->password
            )
        ) {
            throw ValidationException::withMessages([
                'password' =>
                'Password baru harus berbeda dari password saat ini.',
            ]);
        }

        $admin->update([
            'password' => Hash::make(
                $validated['password']
            ),
        ]);

        $request->session()->regenerate();

        return redirect()
            ->route('admin.account-settings.index')
            ->with(
                'success',
                'Password akun berhasil diperbarui.'
            );
    }

    private function currentAdmin(): Authorization
    {
        $admin = Auth::guard('admin')->user();

        abort_unless(
            $admin instanceof Authorization,
            401,
            'Sesi admin tidak valid.'
        );

        return $admin;
    }
}
