<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PermohonanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermohonanController extends Controller
{
    public function __construct(
        protected PermohonanService $permohonanService
    ) {}

    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $permohonan = $this->permohonanService->getForAdmin($admin);

        return view('pages.admin.permohonan.index', compact('permohonan', 'admin'));
    }

    public function show($id)
    {
        $admin = Auth::guard('admin')->user();

        $permohonan = $this->permohonanService->getDetailForAdmin(
            (int) $id,
            $admin
        );

        $ppidPembantu = $this->permohonanService->getPpidPembantuList();

        return view('pages.admin.permohonan.show', compact(
            'permohonan',
            'admin',
            'ppidPembantu'
        ));
    }

    public function teruskan(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'ppid_pembantuid' => 'required|exists:ppid_pembantu,id',
            'catatan_utama' => 'nullable|string',
        ]);

        $permohonan = $this->permohonanService->teruskan(
            (int) $id,
            $admin,
            $validated
        );

        return redirect()
            ->route('admin.permohonan.show', $permohonan->id)
            ->with('success', 'Permohonan berhasil diteruskan ke PPID Pembantu.');
    }

    public function jawabPembantu(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'jawaban_pembantu' => 'required|string',
            'file_pembantu' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
        ]);

        $permohonan = $this->permohonanService->jawabPembantu(
            (int) $id,
            $admin,
            $validated,
            $request->file('file_pembantu')
        );

        return redirect()
            ->route('admin.permohonan.show', $permohonan->id)
            ->with('success', 'Laporan berhasil dikirim ke Admin Utama.');
    }

    public function validasi(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'jawaban_final' => 'required|string',
        ]);

        $permohonan = $this->permohonanService->validasi(
            (int) $id,
            $admin,
            $validated
        );

        return redirect()
            ->route('admin.permohonan.show', $permohonan->id)
            ->with('success', 'Permohonan berhasil divalidasi dan dikirim ke warga.');
    }

    public function revisi(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'catatan_revisi' => 'required|string',
        ]);

        $permohonan = $this->permohonanService->revisi(
            (int) $id,
            $admin,
            $validated
        );

        return redirect()
            ->route('admin.permohonan.show', $permohonan->id)
            ->with('success', 'Permohonan dikembalikan ke PPID Pembantu untuk revisi.');
    }
}
