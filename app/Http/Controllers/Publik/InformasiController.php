<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Services\Publik\InformasiService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InformasiController extends Controller
{
    public function __construct(
        protected InformasiService $informasiService
    ) {}


    /*
    |--------------------------------------------------------------------------
    | INFORMASI BERKALA
    |--------------------------------------------------------------------------
    */

    public function berkala()
    {
        $dokumentasi = $this->informasiService
            ->getBySifat('berkala');

        $jumlahKategori = $dokumentasi->count();

        $jumlahDokumen = $dokumentasi
            ->flatten(1)
            ->count();

        return view(
            'pages.public.informasi.berkala',
            compact(
                'dokumentasi',
                'jumlahKategori',
                'jumlahDokumen'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INFORMASI SETIAP SAAT
    |--------------------------------------------------------------------------
    */

    public function setiapSaat()
    {
        $dokumentasi = $this->informasiService
            ->getBySifat('setiap saat');

        $jumlahKategori = $dokumentasi->count();

        $jumlahDokumen = $dokumentasi
            ->flatten(1)
            ->count();

        return view(
            'pages.public.informasi.setiap-saat',
            compact(
                'dokumentasi',
                'jumlahKategori',
                'jumlahDokumen'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INFORMASI SERTA MERTA
    |--------------------------------------------------------------------------
    */

    public function sertaMerta()
    {
        $dokumentasi = $this->informasiService
            ->getBySifat('serta merta');

        $jumlahKategori = $dokumentasi->count();

        $jumlahDokumen = $dokumentasi
            ->flatten(1)
            ->count();

        return view(
            'pages.public.informasi.serta-merta',
            compact(
                'dokumentasi',
                'jumlahKategori',
                'jumlahDokumen'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INFORMASI DIKECUALIKAN
    |--------------------------------------------------------------------------
    */

    public function dikecualikan()
    {
        $dokumentasi = $this->informasiService
            ->getBySifat('dikecualikan');

        $jumlahKategori = $dokumentasi->count();

        $jumlahDokumen = $dokumentasi
            ->flatten(1)
            ->count();

        return view(
            'pages.public.informasi.dikecualikan',
            compact(
                'dokumentasi',
                'jumlahKategori',
                'jumlahDokumen'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL INFORMASI
    |--------------------------------------------------------------------------
    */

    public function show(string $slug)
    {
        $dokumen = $this->informasiService
            ->findVerifiedBySlug($slug);

        return view(
            'pages.public.informasi.show',
            compact('dokumen')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PREVIEW FILE
    |--------------------------------------------------------------------------
    |
    | response()->file() membuat browser menampilkan file secara inline.
    | Cocok untuk PDF dan gambar.
    |
    */

    public function file(int $id): BinaryFileResponse
    {
        $path = $this->informasiService
            ->getVerifiedDownloadPath($id);

        return response()->file($path);
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD FILE
    |--------------------------------------------------------------------------
    |
    | response()->download() membuat browser mengunduh file.
    |
    */

    public function download(int $id): BinaryFileResponse
    {
        $path = $this->informasiService
            ->getVerifiedDownloadPath($id);

        return response()->download($path);
    }
}
