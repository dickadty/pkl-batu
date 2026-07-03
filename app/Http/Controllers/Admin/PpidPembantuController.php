<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpidPembantu;
use App\Models\KategoriPpid;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PpidPembantuController extends Controller
{
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
}