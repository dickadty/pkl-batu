<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pages;
use App\Models\Module;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::with([
                'page',
                'module',
                'parent'
            ])
            ->orderBy('sort_order')
            ->get();

        return view('pages.admin.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $pages = Pages::where('status', 'published')
        ->orderBy('judul')
        ->get();

    $modules = Module::where('is_active', 1)
        ->orderBy('nama')
        ->get();

    $parents = Menu::with('parent')
        ->orderBy('sort_order')
        ->get();

    return view(
        'pages.admin.menu.create',
        compact(
            'pages',
            'modules',
            'parents'
        )
    );
}

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|max:255',
            'tipe'       => 'required|in:page,module,url',

            'page_id'    => 'nullable|exists:pages,id',
            'module_id'  => 'nullable|exists:modules,id',
            'url'        => 'nullable|max:255',

            'parent_id'  => 'nullable|exists:menu,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'required|boolean',
        ]);

        Menu::create([
            'nama'       => $request->nama,
            'tipe'       => $request->tipe,

            'page_id'    => $request->page_id,
            'module_id'  => $request->module_id,
            'url'        => $request->url,

            'parent_id'  => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->is_active,
        ]);

        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    /**
     * Show the form for editing.
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);

        $pages = Pages::where('status', 'published')
            ->orderBy('judul')
            ->get();

        $modules = Module::where('is_active', 1)
            ->orderBy('nama')
            ->get();

        $parents = Menu::with('parent')
            ->where('id', '!=', $menu->id)
            ->orderBy('sort_order')
            ->get();

        $parents = $parents->reject(function ($parent) use ($menu) {
            $current = $menu;

            while ($current && $current->parent_id) {
                $current = $current->parent;
            }

            return $parent->id === $menu->id || $parent->id === $menu->parent_id || $parent->full_path === $menu->full_path;
        })->values();

        return view(
            'pages.admin.menu.edit',
            compact(
                'menu',
                'pages',
                'modules',
                'parents'
            )
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'       => 'required|max:255',
            'tipe'       => 'required|in:page,module,url',

            'page_id'    => 'nullable|exists:pages,id',
            'module_id'  => 'nullable|exists:modules,id',
            'url'        => 'nullable|max:255',

            'parent_id'  => 'nullable|exists:menu,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'required|boolean',
        ]);

        $menu = Menu::findOrFail($id);

        $menu->update([
            'nama'       => $request->nama,
            'tipe'       => $request->tipe,

            'page_id'    => $request->page_id,
            'module_id'  => $request->module_id,
            'url'        => $request->url,

            'parent_id'  => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->is_active,
        ]);

        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * Remove the specified resource.
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        if ($menu->children()->count() > 0) {
            return redirect()
                ->route('admin.menu.index')
                ->with('error', 'Menu tidak dapat dihapus karena masih memiliki submenu.');
        }

        $menu->delete();

        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}