<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Pages;
use Illuminate\Http\Request;
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
        $modules = Module::where('is_active', 1)
            ->orderBy('nama')
            ->get();

        return view(
            'pages.admin.pages.create',
            compact('modules')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|max:255',
            'module_id' => 'required|exists:modules,id',
            'status'    => 'required',
        ]);

        Pages::create([
            'judul'     => $request->judul,
            'slug'      => Str::slug($request->judul),
            'module_id' => $request->module_id,
            'content'   => $request->input('content'),
            'status'    => $request->status,
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

        $module = $page->module;

        // Jika module memiliki route khusus
        if ($module && !empty($module->route_name)) {
            return redirect()->route($module->route_name);
        }

        // Jika menggunakan view custom
        if ($module && !empty($module->view_name)) {
            return view($module->view_name, compact('page'));
        }

        // Default
        return view('pages.public.page', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $page = Pages::findOrFail($id);

        $modules = Module::where('is_active', 1)
            ->orderBy('nama')
            ->get();

        return view(
            'pages.admin.pages.edit',
            compact(
                'page',
                'modules'
            )
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'     => 'required|max:255',
            'module_id' => 'required|exists:modules,id',
            'status'    => 'required',
        ]);

        $page = Pages::findOrFail($id);

        $page->update([
            'judul'     => $request->judul,
            'slug'      => Str::slug($request->judul),
            'module_id' => $request->module_id,
            'content'   => $request->input('content'),
            'status'    => $request->status,
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

        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dihapus.');
    }
}