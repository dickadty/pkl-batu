<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PpidPembantuService;
use Illuminate\Http\Request;

class PpidPembantuController extends Controller
{
    public function __construct(
        protected PpidPembantuService $ppidPembantuService
    ) {}

    public function index()
    {
        $ppidPembantu = $this->ppidPembantuService->getAllWithKategori();

        return view('pages.admin.ppid-pembantu.index', compact('ppidPembantu'));
    }

    public function create()
    {
        $kategori = $this->ppidPembantuService->getKategoriList();

        return view('pages.admin.ppid-pembantu.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePpidPembantu($request);

        $this->ppidPembantuService->create($validated);

        return redirect()
            ->route('admin.ppid-pembantu.create')
            ->with('success', 'Data PPID Pembantu berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ppidPembantu = $this->ppidPembantuService->findById((int) $id);

        $kategori = $this->ppidPembantuService->getKategoriList();

        return view('pages.admin.ppid-pembantu.edit', compact(
            'ppidPembantu',
            'kategori'
        ));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validatePpidPembantu($request);

        $ppidPembantu = $this->ppidPembantuService->update(
            (int) $id,
            $validated
        );

        return redirect()
            ->route('admin.ppid-pembantu.edit', $ppidPembantu->id)
            ->with('success', 'Data PPID Pembantu berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->ppidPembantuService->delete((int) $id);

        return redirect()
            ->route('admin.ppid-pembantu.index')
            ->with('success', 'Data PPID Pembantu berhasil dihapus.');
    }

    private function validatePpidPembantu(Request $request): array
    {
        return $request->validate([
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:500',
            'kategori_ppidid' => 'nullable|integer',
            'linkweb' => 'nullable|string|max:100',
            'telp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
        ]);
    }
}
