<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PejabatService;
use Illuminate\Http\Request;

class PejabatController extends Controller
{
    public function __construct(
        protected PejabatService $pejabatService
    ) {}

    public function index()
    {
        $pejabat = $this->pejabatService->getAllForAdmin();

        return view('pages.admin.pejabat.index', compact('pejabat'));
    }

    public function create()
    {
        return view('pages.admin.pejabat.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePejabat($request);

        $this->pejabatService->create(
            $validated,
            $request->file('foto')
        );

        return redirect()
            ->route('admin.pejabat.index')
            ->with('success', 'Data pejabat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pejabat = $this->pejabatService->findById((int) $id);

        return view('pages.admin.pejabat.edit', compact('pejabat'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validatePejabat($request);

        $pejabat = $this->pejabatService->update(
            (int) $id,
            $validated,
            $request->file('foto')
        );

        return redirect()
            ->route('admin.pejabat.edit', $pejabat->id)
            ->with('success', 'Data pejabat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->pejabatService->delete((int) $id);

        return redirect()
            ->route('admin.pejabat.index')
            ->with('success', 'Data pejabat berhasil dihapus.');
    }

    private function validatePejabat(Request $request): array
    {
        return $request->validate([
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'masa' => 'nullable|string|max:100',
            'tmp_tgl_lahir' => 'nullable|string|max:150',
            'alamat' => 'nullable|string|max:255',
            'no_telp' => 'nullable|string|max:30',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }
}
