<?php

namespace App\Services\Publik;

use App\Models\UserPublic;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthService
{
    public function __construct(
        protected AuthFactory $auth
    ) {}

    public function isLoggedIn(): bool
    {
        return $this->auth
            ->guard('public')
            ->check();
    }

    public function getLoggedUser(): ?UserPublic
    {
        $user = $this->auth
            ->guard('public')
            ->user();

        return $user instanceof UserPublic
            ? $user
            : null;
    }

    public function attemptLogin(
        array $credentials,
        Request $request
    ): bool {
        $credentials['email'] = strtolower(
            trim($credentials['email'])
        );

        $credentials['is_aktif'] = 1;

        if (! $this->auth
            ->guard('public')
            ->attempt($credentials)) {
            return false;
        }

        $request->session()->regenerate();

        return true;
    }

    public function register(Request $request): UserPublic
    {
        $input = $request->all();

        $input['nama'] = trim(
            (string) $request->input('nama')
        );

        $input['nik'] = preg_replace(
            '/\D+/',
            '',
            (string) $request->input('nik')
        );

        $input['email'] = strtolower(
            trim((string) $request->input('email'))
        );

        $input['telp'] = preg_replace(
            '/[\s\-().]/',
            '',
            (string) $request->input('telp')
        );

        $input['tmp_lahir'] = trim(
            (string) $request->input('tmp_lahir')
        );

        $input['pekerjaan'] = trim(
            (string) $request->input('pekerjaan')
        );

        $input['alamat'] = trim(
            (string) $request->input('alamat')
        );

        $input['desa_kel'] = trim(
            (string) $request->input('desa_kel')
        );

        $input['kecamatan'] = trim(
            (string) $request->input('kecamatan')
        );

        $input['kota_kab'] = trim(
            (string) $request->input('kota_kab')
        );

        $input['kode_pos'] = trim(
            (string) $request->input('kode_pos')
        );

        $input['provinsi'] = trim(
            (string) $request->input('provinsi')
        );

        $validator = Validator::make(
            $input,
            [
                'nama' => [
                    'required',
                    'string',
                    'max:100',
                ],
                'nik' => [
                    'required',
                    'digits:16',
                    Rule::unique('user_publik', 'nik'),
                ],
                'email' => [
                    'required',
                    'email',
                    'max:100',
                    Rule::unique('user_publik', 'email'),
                ],
                'telp' => [
                    'required',
                    'regex:/^\+?[0-9]{10,15}$/',
                    Rule::unique('user_publik', 'telp'),
                ],
                'l_kelamin' => [
                    'required',
                    Rule::in([
                        'Laki-laki',
                        'Perempuan',
                    ]),
                ],
                'tmp_lahir' => [
                    'required',
                    'string',
                    'max:50',
                ],
                'tgl_lahir' => [
                    'required',
                    'date',
                    'before:today',
                ],
                'pekerjaan' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'alamat' => [
                    'required',
                    'string',
                    'max:100',
                ],
                'desa_kel' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'kecamatan' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'kota_kab' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'kode_pos' => [
                    'nullable',
                    'digits_between:5,10',
                ],
                'provinsi' => [
                    'nullable',
                    'string',
                    'max:50',
                ],
                'wilayahkode' => [
                    'nullable',
                    'string',
                    'max:13',
                    Rule::exists('wilayah', 'kode'),
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
                'nama.required' => 'Nama lengkap wajib diisi.',
                'nama.max' => 'Nama maksimal 100 karakter.',

                'nik.required' => 'NIK wajib diisi.',
                'nik.digits' => 'NIK harus terdiri dari 16 angka.',
                'nik.unique' => 'NIK sudah terdaftar.',

                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',

                'telp.required' => 'Nomor telepon wajib diisi.',
                'telp.regex' => 'Format nomor telepon tidak valid.',
                'telp.unique' => 'Nomor telepon sudah terdaftar.',

                'l_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'l_kelamin.in' => 'Jenis kelamin tidak valid.',

                'tmp_lahir.required' => 'Tempat lahir wajib diisi.',

                'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
                'tgl_lahir.date' => 'Tanggal lahir tidak valid.',
                'tgl_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',

                'alamat.required' => 'Alamat wajib diisi.',

                'kode_pos.digits_between' => 'Kode pos harus terdiri dari 5 sampai 10 angka.',

                'wilayahkode.exists' => 'Wilayah yang dipilih tidak valid.',

                'password.required' => 'Password wajib diisi.',
                'password.confirmed' => 'Konfirmasi password tidak sama.',
            ]
        );

        $data = $validator->validate();

        $user = DB::transaction(function () use ($data) {
            return UserPublic::query()->create([
                'nama' => $data['nama'],
                'nik' => $data['nik'],
                'email' => $data['email'],
                'telp' => $data['telp'],
                'l_kelamin' => $data['l_kelamin'],
                'tmp_lahir' => $data['tmp_lahir'],
                'tgl_lahir' => $data['tgl_lahir'],
                'pekerjaan' => $data['pekerjaan'] ?? null,
                'alamat' => $data['alamat'],
                'desa_kel' => $data['desa_kel'] ?? null,
                'kecamatan' => $data['kecamatan'] ?? null,
                'kota_kab' => $data['kota_kab'] ?? null,
                'kode_pos' => $data['kode_pos'] ?? null,
                'provinsi' => $data['provinsi'] ?? null,
                'wilayahkode' => $data['wilayahkode'] ?? null,
                'password' => Hash::make(
                    $data['password']
                ),
            ]);
        });

        $this->auth
            ->guard('public')
            ->login($user);

        $request->session()->regenerate();

        return $user;
    }

    public function logout(Request $request): void
    {
        $this->auth
            ->guard('public')
            ->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
