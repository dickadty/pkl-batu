<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Pages::with('module')
            ->latest()
            ->get();

        return view('pages.admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:10240',
            'status' => 'required',
        ]);

        $gambar = null;

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('pages', 'public');
        }

        $file = null;

        if ($request->hasFile('file')) {

            $file = $request
                ->file('file')
                ->store('pages/files', 'public');

        }

        Pages::create([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'module_id' => 1,
            'gambar' => $gambar,
            'file' => $file,
            'content' => $request->input('content'),
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $page = Pages::with('module')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('pages.public.page', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $page = Pages::findOrFail($id);

        return view(
            'pages.admin.pages.edit',
            compact('page')
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:10240',
            'status' => 'required',
        ]);

        $page = Pages::findOrFail($id);

        $gambar = $page->gambar;

        if ($request->hasFile('gambar')) {

            if (
                $page->gambar &&
                Storage::disk('public')->exists($page->gambar)
            ) {
                Storage::disk('public')->delete($page->gambar);
            }

            $gambar = $request->file('gambar')->store('pages', 'public');
        }

        $file = $page->file;

        if ($request->hasFile('file')) {

            if (
                $page->file &&
                Storage::disk('public')->exists($page->file)
            ) {
                Storage::disk('public')->delete($page->file);
            }

            $file = $request
                ->file('file')
                ->store('pages/files', 'public');
        }

        $page->update([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'module_id' => 1,
            'gambar' => $gambar,
            'file' => $file,
            'content' => $request->input('content'),
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource.
     */
    public function destroy($id)
    {
        $page = Pages::findOrFail($id);

        if (
            $page->gambar &&
            Storage::disk('public')->exists($page->gambar)
        ) {
            Storage::disk('public')->delete($page->gambar);
        }

        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dihapus.');
    }
}