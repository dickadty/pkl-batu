<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use Illuminate\Support\Facades\Storage;

class InformasiController extends Controller
{
    public function index()
    {
        $dokumentasi = Dokumentasi::with('ppidPembantu')
            ->where('is_verifikasi', 1)
            ->latest('id')
            ->get();

        return view('pages.public.informasi.index', compact('dokumentasi'));
    }

    public function show($slug)
    {
        $dokumen = Dokumentasi::with('ppidPembantu')
            ->where('slug', $slug)
            ->where('is_verifikasi', 1)
            ->firstOrFail();

        return view('pages.public.informasi.show', compact('dokumen'));
    }

    public function download($id)
    {
        $dokumen = Dokumentasi::where('id', $id)
            ->where('is_verifikasi', 1)
            ->firstOrFail();

        if (! $dokumen->file || ! Storage::disk('public')->exists($dokumen->file)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(Storage::disk('public')->path($dokumen->file));
    }
}
