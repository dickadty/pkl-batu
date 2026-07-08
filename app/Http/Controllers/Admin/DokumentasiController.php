<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\InformasiPublikService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DokumentasiController extends Controller
{
    public function __construct(
        protected InformasiPublikService $informasiPublikService
    ) {}

    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $dokumentasi = $this->informasiPublikService->getForAdmin($admin);

        return view('pages.admin.dokumentasi.index', compact('dokumentasi', 'admin'));
    }

    public function create()
    {
        $admin = Auth::guard('admin')->user();

        $ppidPembantu = $this->informasiPublikService->getPpidPembantuList();

        return view('pages.admin.dokumentasi.create', compact('admin', 'ppidPembantu'));
    }

    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'nama' => 'required|string|max:250',
            'tahun' => 'nullable|integer|min:2000|max:2100',
            'ringkasan' => 'nullable|string',
            'sifat' => 'nullable|string|max:50',
            'ppid_pembantuid' => 'nullable|exists:ppid_pembantu,id',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:5120',
        ]);

        $this->informasiPublikService->create(
            $validated,
            $request->file('file'),
            $admin
        );

        return redirect()
            ->route('admin.informasi-publik.index')
            ->with('success', 'Dokumentasi berhasil diunggah.');
    }

    public function verifikasi($id)
    {
        $admin = Auth::guard('admin')->user();

        $this->informasiPublikService->verify((int) $id, $admin);

        return back()->with('success', 'Informasi publik berhasil diverifikasi.');
    }

    public function destroy($id)
    {
        $admin = Auth::guard('admin')->user();

        $this->informasiPublikService->delete((int) $id, $admin);

        return back()->with('success', 'Informasi publik berhasil dihapus.');
    }
}
