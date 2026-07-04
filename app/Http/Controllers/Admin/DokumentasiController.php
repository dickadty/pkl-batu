<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\PpidPembantu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DokumentasiController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $query = Dokumentasi::with('ppidPembantu')->latest('id');

        if ((int) $admin->role === 2) {
            $query->where('ppid_pembantuid', $admin->ppid_pembantuid);
        }

        $dokumentasi = $query->get();

        return view('pages.admin.dokumentasi.index', compact('dokumentasi', 'admin'));
    }

    public function create()
    {
        $admin = Auth::guard('admin')->user();

        $ppidPembantu = PpidPembantu::orderBy('nama')->get();

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

        if ((int) $admin->role === 2) {
            $validated['ppid_pembantuid'] = $admin->ppid_pembantuid;
            $validated['is_verifikasi'] = 0;
        } else {
            $validated['is_verifikasi'] = 1;
        }

        $file = $request->file('file');
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

        $validated['file'] = $file->storeAs('dokumentasi', $filename, 'public');
        $validated['tanggal'] = time();
        $validated['slug'] = Str::slug($validated['nama']) . '-' . time();

        Dokumentasi::create($validated);

        return redirect()
            ->route('admin.dokumentasi.index')
            ->with('success', 'Dokumentasi berhasil diunggah.');
    }

    public function verifikasi($id)
    {
        $admin = Auth::guard('admin')->user();

        if ((int) $admin->role !== 1) {
            abort(403, 'Akses ditolak.');
        }

        $dokumentasi = Dokumentasi::findOrFail($id);

        $dokumentasi->update([
            'is_verifikasi' => 1,
        ]);

        return back()->with('success', 'Informasi publik berhasil diverifikasi.');
    }

    public function destroy($id)
    {
        $admin = Auth::guard('admin')->user();

        $query = Dokumentasi::query();

        if ((int) $admin->role === 2) {
            $query->where('ppid_pembantuid', $admin->ppid_pembantuid);
        }

        $dokumentasi = $query->findOrFail($id);

        if ($dokumentasi->file && Storage::disk('public')->exists($dokumentasi->file)) {
            Storage::disk('public')->delete($dokumentasi->file);
        }

        $dokumentasi->delete();

        return back()->with('success', 'Informasi publik berhasil dihapus.');
    }
}
