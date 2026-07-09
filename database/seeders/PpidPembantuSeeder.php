<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PpidPembantuSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'nama' => 'Dinas Kominfo Kota Batu',
                'keterangan' => 'PPID Pembantu Dinas Komunikasi dan Informatika Kota Batu',
                'kategori_ppidid' => null,
                'linkweb' => 'https://kominfo.batukota.go.id',
                'telp' => '-',
                'alamat' => 'Kota Batu',
                'icon' => null,
            ],
            [
                'id' => 2,
                'nama' => 'Kecamatan Batu',
                'keterangan' => 'PPID Pembantu Kecamatan Batu',
                'kategori_ppidid' => null,
                'linkweb' => null,
                'telp' => '-',
                'alamat' => 'Kota Batu',
                'icon' => null,
            ],
            [
                'id' => 3,
                'nama' => 'Kecamatan Junrejo',
                'keterangan' => 'PPID Pembantu Kecamatan Junrejo',
                'kategori_ppidid' => null,
                'linkweb' => null,
                'telp' => '-',
                'alamat' => 'Kota Batu',
                'icon' => null,
            ],
            [
                'id' => 4,
                'nama' => 'Kecamatan Bumiaji',
                'keterangan' => 'PPID Pembantu Kecamatan Bumiaji',
                'kategori_ppidid' => null,
                'linkweb' => null,
                'telp' => '-',
                'alamat' => 'Kota Batu',
                'icon' => null,
            ],
            [
                'id' => 5,
                'nama' => 'Kelurahan Sisir',
                'keterangan' => 'PPID Pembantu Kelurahan Sisir',
                'kategori_ppidid' => null,
                'linkweb' => null,
                'telp' => '-',
                'alamat' => 'Kota Batu',
                'icon' => null,
            ],
        ];

        foreach ($data as $item) {
            DB::table('ppid_pembantu')->updateOrInsert(
                ['id' => $item['id']],
                [
                    'nama' => $item['nama'],
                    'keterangan' => $item['keterangan'],
                    'kategori_ppidid' => $item['kategori_ppidid'],
                    'linkweb' => $item['linkweb'],
                    'telp' => $item['telp'],
                    'alamat' => $item['alamat'],
                    'icon' => $item['icon'],
                    'slug' => Str::slug($item['nama']),
                ]
            );
        }
    }
}
