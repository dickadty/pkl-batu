<?php

namespace App\Services\Publik;

use App\Mail\AktivasiAkunMail;
use App\Models\UserPublic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class AccountActivationService
{
    private const TOKEN_LIFETIME_MINUTES = 1440;

    public function issueToken(UserPublic $user): string
    {
        $email = $this->normalizeEmail((string) $user->email);
        $plainToken = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            [
                'email' => $email,
            ],
            [
                'token' => hash('sha256', $plainToken),
                'created_at' => now(),
            ]
        );

        return $this->buildActivationUrl(
            email: $email,
            token: $plainToken
        );
    }

    public function findPendingUserForToken(
        string $email,
        string $plainToken
    ): ?UserPublic {
        $email = $this->normalizeEmail($email);

        if ($email === '' || strlen($plainToken) !== 64) {
            return null;
        }

        $user = UserPublic::query()
            ->where('email', $email)
            ->first();

        if (! $user || (bool) $user->is_aktif) {
            return null;
        }

        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (! $tokenRecord) {
            return null;
        }

        if (! $tokenRecord->created_at) {
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return null;
        }

        $expiredAt = now()
            ->subMinutes(self::TOKEN_LIFETIME_MINUTES);

        if ($expiredAt->greaterThan($tokenRecord->created_at)) {
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return null;
        }

        $expectedHash = (string) $tokenRecord->token;
        $actualHash = hash('sha256', $plainToken);

        if (! hash_equals($expectedHash, $actualHash)) {
            return null;
        }

        return $user;
    }

    public function activate(
        string $email,
        string $plainToken,
        string $password
    ): UserPublic {
        $user = $this->findPendingUserForToken(
            email: $email,
            plainToken: $plainToken
        );

        if (! $user) {
            throw ValidationException::withMessages([
                'token' => 'Tautan aktivasi tidak valid, sudah digunakan, atau telah kedaluwarsa.',
            ]);
        }

        DB::transaction(function () use ($user, $password): void {
            $user->forceFill([
                'password' => $password,
                'is_aktif' => 1,
            ])->save();

            DB::table('password_reset_tokens')
                ->where('email', $this->normalizeEmail((string) $user->email))
                ->delete();
        });

        return $user->refresh();
    }

    public function resendActivation(string $email): void
    {
        $email = $this->normalizeEmail($email);

        if ($email === '') {
            return;
        }

        $user = UserPublic::query()
            ->where('email', $email)
            ->first();

        if (! $user || (bool) $user->is_aktif) {
            return;
        }

        $activationUrl = $this->issueToken($user);

        try {
            Mail::to($email)->queue(
                new AktivasiAkunMail(
                    user: $user,
                    activationUrl: $activationUrl
                )
            );
        } catch (Throwable $exception) {
            Log::error(
                'Gagal memasukkan email aktivasi akun warga ke antrean.',
                [
                    'user_publikid' => $user->id,
                    'email' => $email,
                    'message' => $exception->getMessage(),
                ]
            );
        }
    }

    private function buildActivationUrl(
        string $email,
        string $token
    ): string {
        return route('public.aktivasi.show', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    private function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }
}
