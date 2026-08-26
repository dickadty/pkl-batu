<?php

namespace App\Services\Publik;

use App\Models\Dokumentasi;
use App\Models\Download;
use App\Models\UserPublic;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InformasiService
{
    public function __construct(
        protected Dokumentasi $dokumentasi,
        protected Download $download,
        protected FilesystemFactory $storage
    ) {}


    /*
    |--------------------------------------------------------------------------
    | Semua informasi terverifikasi
    |--------------------------------------------------------------------------
    */

    public function getVerifiedInformation(): Collection
    {
        return $this->dokumentasi
            ->newQuery()
            ->with([
                'ppidPembantu',
                'kategori',
            ])
            ->where('is_verifikasi', 1)
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Detail berdasarkan slug
    |--------------------------------------------------------------------------
    */

    public function findVerifiedBySlug(string $slug): Dokumentasi
    {
        return $this->dokumentasi
            ->newQuery()
            ->with([
                'ppidPembantu',
                'kategori',
            ])
            ->where('slug', $slug)
            ->where('is_verifikasi', 1)
            ->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | Informasi berdasarkan sifat kategori
    |--------------------------------------------------------------------------
    */

    public function getBySifat(string $sifat)
    {
        return $this->dokumentasi
            ->newQuery()
            ->with([
                'kategori',
                'ppidPembantu',
            ])

            /*
            |--------------------------------------------------------------------------
            | Hanya informasi terverifikasi
            |--------------------------------------------------------------------------
            */

            ->where('is_verifikasi', 1)

            /*
            |--------------------------------------------------------------------------
            | Filter berdasarkan sifat pada tabel kategori_informasi
            |--------------------------------------------------------------------------
            */

            ->whereHas('kategori', function ($query) use ($sifat) {

                $query->whereRaw(
                    'LOWER(TRIM(sifat)) = ?',
                    [
                        strtolower(
                            trim($sifat)
                        )
                    ]
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Pengurutan
            |--------------------------------------------------------------------------
            */

            ->orderBy('kategori_id')
            ->orderByDesc('tahun')
            ->orderBy('nama')

            /*
            |--------------------------------------------------------------------------
            | Ambil data
            |--------------------------------------------------------------------------
            */

            ->get()

            /*
            |--------------------------------------------------------------------------
            | Kelompokkan berdasarkan nama kategori
            |--------------------------------------------------------------------------
            */

            ->groupBy(function ($item) {

                return $item->kategori?->nama
                    ?? 'Tanpa Kategori';
            });
    }


    /*
    |--------------------------------------------------------------------------
    | Download file
    |--------------------------------------------------------------------------
    */

    public function getVerifiedDownloadPath(
        int $id,
        ?UserPublic $user = null,
        ?string $tujuan = null
    ): string {

        $dokumen = $this->dokumentasi
            ->newQuery()
            ->where('id', $id)
            ->where('is_verifikasi', 1)
            ->firstOrFail();


        $disk = $this->storage
            ->disk('public');


        if (
            ! $dokumen->file ||
            ! $disk->exists($dokumen->file)
        ) {
            throw new NotFoundHttpException(
                'File tidak ditemukan.'
            );
        }


        if ($user) {

            $this->download
                ->newQuery()
                ->create([
                    'tujuan' => $tujuan
                        ?? 'Mengunduh informasi publik',

                    'tanggal' => time(),

                    'user_publikid' => $user->id,

                    'dokumentasiid' => $dokumen->id,
                ]);
        }


        return $disk->path(
            $dokumen->file
        );
    }
}
