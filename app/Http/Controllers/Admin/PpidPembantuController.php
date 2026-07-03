<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpidPembantu;
use App\Models\KategoriPpid;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PpidPembantuController extends Controller
{

    public function index()
    {
        $ppidPembantu = \App\Models\PpidPembantu::with('kategoriPpid')
            ->orderBy('id')
            ->get();

        return view('pages.admin.ppid-pembantu.index', compact('ppidPembantu'));
    }

    public function create()
    {
        $kategori = KategoriPpid::orderBy('kategori')->get();

        return view('pages.admin.ppid-pembantu.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:500',
            'kategori_ppidid' => 'nullable|integer',
            'linkweb' => 'nullable|string|max:100',
            'telp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);

        PpidPembantu::create($validated);

        return redirect()
            ->route('admin.ppid-pembantu.create')
            ->with('success', 'Data PPID Pembantu berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ppidPembantu = PpidPembantu::findOrFail($id);
        $kategori = KategoriPpid::orderBy('kategori')->get();

        return view('pages.admin.ppid-pembantu.edit', compact('ppidPembantu', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $ppidPembantu = PpidPembantu::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:500',
            'kategori_ppidid' => 'nullable|integer',
            'linkweb' => 'nullable|string|max:100',
            'telp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);

        $ppidPembantu->update($validated);

        return redirect()
            ->route('admin.ppid-pembantu.edit', $ppidPembantu->id)
            ->with('success', 'Data PPID Pembantu berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ppidPembantu = PpidPembantu::findOrFail($id);
        $ppidPembantu->delete();

        return redirect()
            ->route('admin.ppid-pembantu.index')
            ->with('success', 'Data PPID Pembantu berhasil dihapus.');
    }
}
