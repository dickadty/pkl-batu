<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Services\Publik\InformasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformasiController extends Controller
{
    public function __construct(
        protected InformasiService $informasiService
    ) {}

    public function berkala()
    {
        $dokumentasi = $this->informasiService
            ->getBySifat('berkala');

        $jumlahKategori = $dokumentasi->count();

        $jumlahDokumen = $dokumentasi
            ->flatten()
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

    public function setiapSaat()
    {
        $dokumentasi = $this->informasiService
            ->getBySifat('setiap saat');

        $jumlahKategori = $dokumentasi->count();
        $jumlahDokumen = $dokumentasi->flatten()->count();

        return view(
            'admin.informasi-publik.file.show',
            compact('dokumentasi', 'jumlahKategori', 'jumlahDokumen')
        );
    }

    public function sertaMerta()
    {
        $dokumentasi = $this->informasiService
            ->getBySifat('serta merta');

        $jumlahKategori = $dokumentasi->count();
        $jumlahDokumen = $dokumentasi->flatten()->count();

        return view(
            'pages.public.informasi.serta-merta',
            compact('dokumentasi', 'jumlahKategori', 'jumlahDokumen')
        );
    }

    public function dikecualikan()
    {
        $dokumentasi = $this->informasiService
            ->getBySifat('dikecualikan');

        $jumlahKategori = $dokumentasi->count();
        $jumlahDokumen = $dokumentasi->flatten()->count();

        return view(
            'pages.public.informasi.dikecualikan',
            compact('dokumentasi', 'jumlahKategori', 'jumlahDokumen')
        );
    }

    public function show($slug)
    {
        $dokumen = $this->informasiService
            ->findVerifiedBySlug($slug);

        return view(
            'admin.informasi-publik.file.show',
            compact('dokumen')
        );
    }
}
