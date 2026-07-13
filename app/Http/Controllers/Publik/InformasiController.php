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

    public function download(Request $request, $id)
    {
        $user = Auth::guard('public')->user();

        $filePath = $this->informasiService->getVerifiedDownloadPath(
            (int) $id,
            $user,
            $request->input('tujuan')
        );

        return response()->download($filePath);
    }
}
