<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriInformasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriInformasiController extends Controller
{
    public function index()
    {
        $kategori = KategoriInformasi::latest()->paginate(10);

        return view('pages.admin.kategori-informasi.index', compact('kategori'));
    }

    public function create()
    {
        return view('pages.admin.kategori-informasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|max:255',
            'sifat' => 'required|in:berkala,setiap_saat,serta_merta,dikecualikan',
        ]);

        KategoriInformasi::create([
            'nama'  => $request->nama,
            'slug'  => Str::slug($request->nama),
            'sifat' => $request->sifat,
        ]);

        return redirect()
            ->route('admin.kategori-informasi.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $kategori = KategoriInformasi::findOrFail($id);

        return view(
            'pages.admin.kategori-informasi.edit',
            compact('kategori')
        );
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama'  => 'required|max:255',
            'sifat' => 'required|in:berkala,setiap_saat,serta_merta,dikecualikan',
        ]);

        $kategori = KategoriInformasi::findOrFail($id);

        $kategori->update([
            'nama'  => $request->nama,
            'slug'  => Str::slug($request->nama),
            'sifat' => $request->sifat,
        ]);

        return redirect()
            ->route('admin.kategori-informasi.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $kategori = KategoriInformasi::findOrFail($id);

        $kategori->delete();

        return redirect()
            ->route('admin.kategori-informasi.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}