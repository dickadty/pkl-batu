<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserPublikSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_publik')->updateOrInsert(
            ['email' => 'warga@example.com'],
            [
                'nama' => 'Warga Testing',
                'nik' => '3579000000000001',
                'scanktp' => null,
                'l_kelamin' => 'L',
                'tmp_lahir' => 'Batu',
                'tgl_lahir' => '2000-01-01',
                'pekerjaan' => 'Wiraswasta',
                'alamat' => 'Kota Batu',
                'desa_kel' => 'Sisir',
                'kecamatan' => 'Batu',
                'kota_kab' => 'Batu',
                'kode_pos' => '65311',
                'provinsi' => 'Jawa Timur',
                'telp' => '081234567890',
                'hint' => null,
                'password' => Hash::make('warga123'),
                'is_aktif' => 1,
                'wilayahkode' => null,
            ]
        );
    }
}
