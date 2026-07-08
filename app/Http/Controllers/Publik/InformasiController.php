<?php

namespace App\Http\Controllers\Publik;
use App\Http\Controllers\Controller;
use App\Services\Publik\InformasiService;

class InformasiController extends Controller
{
    public function __construct(
        protected InformasiService $informasiService
    ) {}

    public function index()
    {
        $dokumentasi = $this->informasiService->getVerifiedInformation();

        return view('pages.public.informasi.index', compact('dokumentasi'));
    }

    public function show($slug)
    {
        $dokumen = $this->informasiService->findVerifiedBySlug($slug);

        return view('pages.public.informasi.show', compact('dokumen'));
    }

    public function download($id)
    {
        $filePath = $this->informasiService->getVerifiedDownloadPath((int) $id);

        return response()->download($filePath);
    }
}
