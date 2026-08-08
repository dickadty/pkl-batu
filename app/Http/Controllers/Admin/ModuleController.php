<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::orderBy('nama')->get();

        return view(
            'pages.admin.module.index',
            compact('modules')
        );
    }

    public function create()
    {
        return view(
            'pages.admin.module.create'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'route_name' => 'required|max:255',
            'icon' => 'nullable|max:100',
            'description' => 'nullable',
            'is_active' => 'required|boolean',
        ]);

        Module::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'route_name' => $request->route_name,
            'icon' => $request->icon,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('admin.module.index')
            ->with('success', 'Module berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $module = Module::findOrFail($id);

        return view(
            'pages.admin.module.edit',
            compact('module')
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'route_name' => 'required|max:255',
            'view_name' => 'required|max:255',
            'icon' => 'nullable|max:100',
            'description' => 'nullable',
            'is_active' => 'required|boolean',
        ]);

        $module = Module::findOrFail($id);

        $module->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'route_name' => $request->route_name,
            'icon' => $request->icon,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('admin.module.index')
            ->with('success', 'Module berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $module = Module::findOrFail($id);

        if ($module->pages()->count()) {
            return back()->with(
                'error',
                'Module masih digunakan oleh halaman.'
            );
        }

        if ($module->menus()->count()) {
            return back()->with(
                'error',
                'Module masih digunakan oleh menu.'
            );
        }

        $module->delete();

        return redirect()
            ->route('admin.module.index')
            ->with('success', 'Module berhasil dihapus.');
    }
}