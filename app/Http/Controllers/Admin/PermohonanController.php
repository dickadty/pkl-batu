<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permohonan;
use App\Models\PpidPembantu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PermohonanController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $query = Permohonan::with(['userPublic', 'ppidPembantu'])
            ->latest('id');

        if ((int) $admin->role === 2) {
            $query->where('ppid_pembantuid', $admin->ppid_pembantuid);
        }

        $permohonan = $query->get();

        return view('pages.admin.permohonan.index', compact('permohonan', 'admin'));
    }
    // public function index()
    // {
    //     $admin = Auth::guard('admin')->user();

    //     $query = Permohonan::with(['userPublic', 'ppidPembantu'])
    //         ->latest('id');

    //     if ((int) $admin->role === 2) {
    //         $query->where('ppid_pembantuid', $admin->ppid_pembantuid);
    //     }

    //     $permohonan = $query->get();

    //     return view('pages.admin.permohonan.index', compact('permohonan', 'admin'));
    // }

    public function show($id)
    {
        $admin = Auth::guard('admin')->user();

        $permohonan = Permohonan::with(['userPublic', 'ppidPembantu', 'admin'])
            ->findOrFail($id);

        if ((int) $admin->role === 2 && (int) $permohonan->ppid_pembantuid !== (int) $admin->ppid_pembantuid) {
            abort(403, 'Akses ditolak.');
        }

        $ppidPembantu = PpidPembantu::orderBy('nama')->get();

        return view('pages.admin.permohonan.show', compact(
            'permohonan',
            'admin',
            'ppidPembantu'
        ));
    }

    public function teruskan(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        if ((int) $admin->role !== 1) {
            abort(403, 'Hanya admin utama yang dapat meneruskan permohonan.');
        }

        $validated = $request->validate([
            'ppid_pembantuid' => 'required|exists:ppid_pembantu,id',
            'catatan_utama' => 'nullable|string',
        ]);

        $permohonan = Permohonan::findOrFail($id);

        $permohonan->update([
            'ppid_pembantuid' => $validated['ppid_pembantuid'],
            'catatan_utama' => $validated['catatan_utama'] ?? null,
            'tanggal_diteruskan' => now()->toDateString(),
            'status' => 'Diteruskan ke PPID Pembantu',
        ]);

        return redirect()
            ->route('admin.permohonan.show', $permohonan->id)
            ->with('success', 'Permohonan berhasil diteruskan ke PPID Pembantu.');
    }

    public function jawabPembantu(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        if ((int) $admin->role !== 2) {
            abort(403, 'Hanya admin pembantu yang dapat memberi laporan.');
        }

        $permohonan = Permohonan::findOrFail($id);

        if ((int) $permohonan->ppid_pembantuid !== (int) $admin->ppid_pembantuid) {
            abort(403, 'Permohonan ini bukan untuk PPID Pembantu Anda.');
        }

        $validated = $request->validate([
            'jawaban_pembantu' => 'required|string',
            'file_pembantu' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('file_pembantu')) {
            if ($permohonan->file_pembantu && Storage::disk('public')->exists($permohonan->file_pembantu)) {
                Storage::disk('public')->delete($permohonan->file_pembantu);
            }

            $file = $request->file('file_pembantu');

            $filename = time() . '_' .
                Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) .
                '.' .
                $file->getClientOriginalExtension();

            $validated['file_pembantu'] = $file->storeAs('laporan-permohonan', $filename, 'public');
        }

        $permohonan->update([
            'jawaban_pembantu' => $validated['jawaban_pembantu'],
            'file_pembantu' => $validated['file_pembantu'] ?? $permohonan->file_pembantu,
            'tanggal_jawab_pembantu' => now()->toDateString(),
            'status' => 'Menunggu Validasi Admin Utama',
        ]);

        return redirect()
            ->route('admin.permohonan.show', $permohonan->id)
            ->with('success', 'Laporan berhasil dikirim ke Admin Utama.');
    }

    public function validasi(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        if ((int) $admin->role !== 1) {
            abort(403, 'Hanya admin utama yang dapat validasi.');
        }

        $permohonan = Permohonan::findOrFail($id);

        $validated = $request->validate([
            'jawaban_final' => 'required|string',
        ]);

        $permohonan->update([
            'jawaban' => $validated['jawaban_final'],
            'file_jawaban' => $permohonan->file_pembantu,
            'tanggal_jawab' => now()->toDateString(),
            'tanggal_validasi' => now()->toDateString(),
            'adminid' => $admin->id,
            'status' => 'Selesai',
        ]);

        return redirect()
            ->route('admin.permohonan.show', $permohonan->id)
            ->with('success', 'Permohonan berhasil divalidasi dan dikirim ke warga.');
    }

    public function revisi(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        if ((int) $admin->role !== 1) {
            abort(403, 'Hanya admin utama yang dapat meminta revisi.');
        }

        $validated = $request->validate([
            'catatan_revisi' => 'required|string',
        ]);

        $permohonan = Permohonan::findOrFail($id);

        $permohonan->update([
            'catatan_revisi' => $validated['catatan_revisi'],
            'status' => 'Revisi PPID Pembantu',
        ]);

        return redirect()
            ->route('admin.permohonan.show', $permohonan->id)
            ->with('success', 'Permohonan dikembalikan ke PPID Pembantu untuk revisi.');
    }
}
