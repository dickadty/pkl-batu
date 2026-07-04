<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::latest('id')->get();

        return view('pages.admin.berita.index', compact('berita'));
    }

    public function create()
    {
        return view('pages.admin.berita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'caption' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');

            $filename = time() . '_' .
                Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) .
                '.' .
                $file->getClientOriginalExtension();

            $validated['gambar'] = $file->storeAs('berita', $filename, 'public');
        }

        $validated['tanggal'] = time();

        Berita::create($validated);

        return redirect()
            ->route('admin.berita.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return back()->with('success', 'Berita berhasil dihapus.');
    }
}
