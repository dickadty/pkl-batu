<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Download;
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
            ->getBySifat('setiap_saat');

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
            ->getBySifat('serta_merta');

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
    | DAFTAR INFORMASI PUBLIK
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $dokumen = $this->informasiService
            ->getVerifiedPublicDocuments();

        $totalDokumen = $dokumen->count();

        $tahunTersedia = $dokumen
            ->pluck('tahun')
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        $kategoriTersedia = $dokumen
            ->pluck('kategori.nama')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view(
            'pages.public.informasi.index',
            compact(
                'dokumen',
                'totalDokumen',
                'tahunTersedia',
                'kategoriTersedia'
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


    public function download(int $id): BinaryFileResponse
    {
        /*
    |--------------------------------------------------------------------------
    | Validasi dokumen dan ambil lokasi file
    |--------------------------------------------------------------------------
    */

        $path = $this->informasiService
            ->getVerifiedDownloadPath($id);


        /*
    |--------------------------------------------------------------------------
    | Simpan histori download
    |--------------------------------------------------------------------------
    */

        Download::create([
            'tujuan' => request()->ip(),

            'tanggal' => time(),

            'dokumentasiid' => $id,
        ]);


        /*
    |--------------------------------------------------------------------------
    | Download file
    |--------------------------------------------------------------------------
    */

        return response()->download($path);
    }
}
