<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\BeritaService;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function __construct(
        protected BeritaService $beritaService
    ) {}

    public function index()
    {
        $berita = $this->beritaService->getAllForAdmin();

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

        $this->beritaService->create(
            $validated,
            $request->file('gambar')
        );

        return redirect()
            ->route('admin.berita.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $this->beritaService->delete((int) $id);

        return back()->with('success', 'Berita berhasil dihapus.');
    }
}
