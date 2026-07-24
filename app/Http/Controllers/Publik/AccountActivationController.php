<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Services\Publik\AccountActivationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AccountActivationController extends Controller
{
    public function __construct(
        protected AccountActivationService $activationService
    ) {}

    public function show(
        Request $request,
        string $token
    ): View {
        $email = strtolower(
            trim((string) $request->query('email'))
        );

        $isValid = $this->activationService
            ->findPendingUserForToken(
                email: $email,
                plainToken: $token
            ) !== null;

        return view(
            'pages.public.auth.activate',
            compact(
                'token',
                'email',
                'isValid'
            )
        );
    }

    public function activate(
        Request $request,
        string $token
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'email' => [
                    'required',
                    'email:rfc',
                    'max:100',
                ],
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                        ->letters()
                        ->numbers(),
                ],
            ],
            [
                'email.required' => 'Email aktivasi tidak ditemukan.',
                'email.email' => 'Format email aktivasi tidak valid.',
                'password.required' => 'Password baru wajib diisi.',
                'password.confirmed' => 'Konfirmasi password tidak sama.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.letters' => 'Password harus memiliki huruf.',
                'password.numbers' => 'Password harus memiliki angka.',
            ]
        );

        $user = $this->activationService->activate(
            email: $validated['email'],
            plainToken: $token,
            password: $validated['password']
        );

        Auth::guard('public')->login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('public.permohonan.index')
            ->with(
                'success',
                'Akun berhasil diaktifkan. Anda sekarang dapat melihat riwayat dan mengajukan permohonan berikutnya melalui akun ini.'
            );
    }

    public function showResend(Request $request): View
    {
        $prefillEmail = strtolower(
            trim(
                (string) old(
                    'email',
                    $request->session()->get('activation_email', '')
                )
            )
        );

        return view(
            'pages.public.auth.resend-activation',
            compact('prefillEmail')
        );
    }

    public function resend(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'email' => [
                    'required',
                    'email:rfc',
                    'max:100',
                ],
            ],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
            ]
        );

        $this->activationService->resendActivation(
            $validated['email']
        );

        return redirect()
            ->route('public.aktivasi.resend.form')
            ->with(
                'success',
                'Apabila akun tersebut terdaftar dan belum aktif, tautan aktivasi baru telah dikirim ke email.'
            );
    }
}
