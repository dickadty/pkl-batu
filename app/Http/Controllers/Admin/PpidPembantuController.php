<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpidPembantu;
use App\Services\Admin\PpidPembantuService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpidPembantuController extends Controller
{
    public function __construct(
        protected PpidPembantuService $ppidPembantuService
    ) {}

    /**
     * Menampilkan daftar PPID Pembantu.
     */
    public function index(
        Request $request
    ): View {
        $query = PpidPembantu::query()
            ->with([
                'kategoriPpid:id,kategori',
            ]);

        $search = trim(
            (string) $request->input(
                'q',
                ''
            )
        );

        $query->when(
            $search !== '',
            function (
                Builder $query
            ) use ($search): void {
                $query->where(
                    function (
                        Builder $subQuery
                    ) use ($search): void {
                        $subQuery
                            ->where(
                                'nama',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'keterangan',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'linkweb',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'telp',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'alamat',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhereHas(
                                'kategoriPpid',
                                function (
                                    Builder $relationQuery
                                ) use ($search): void {
                                    $relationQuery->where(
                                        'kategori',
                                        'like',
                                        "%{$search}%"
                                    );
                                }
                            );
                    }
                );
            }
        );

        $query->when(
            $request->filled('kategori_ppidid'),
            fn(Builder $query) => $query->where(
                'kategori_ppidid',
                $request->integer(
                    'kategori_ppidid'
                )
            )
        );

        $perPage = max(
            5,
            min(
                (int) $request->input(
                    'per_page',
                    15
                ),
                100
            )
        );

        $ppidPembantu = $query
            ->orderBy('nama')
            ->paginate($perPage)
            ->withQueryString();

        $kategoriPpidList = $this
            ->ppidPembantuService
            ->getKategoriList();

        return view(
            'pages.admin.ppid-pembantu.index',
            compact(
                'ppidPembantu',
                'kategoriPpidList'
            )
        );
    }

    /**
     * Menampilkan halaman tambah PPID Pembantu.
     */
    public function create(): View
    {
        $kategori = $this
            ->ppidPembantuService
            ->getKategoriList();

        return view(
            'pages.admin.ppid-pembantu.create',
            compact('kategori')
        );
    }

    /**
     * Menyimpan PPID Pembantu.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $this
            ->validatePpidPembantu($request);

        $ppidPembantu = $this
            ->ppidPembantuService
            ->create($validated);

        return redirect()
            ->route(
                'admin.ppid-pembantu.show',
                $ppidPembantu->id
            )
            ->with(
                'success',
                'Data PPID Pembantu berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail PPID Pembantu.
     */
    public function show(int $id): View
    {
        $ppidPembantu = $this
            ->ppidPembantuService
            ->findById($id);

        return view(
            'pages.admin.ppid-pembantu.show',
            compact('ppidPembantu')
        );
    }

    /**
     * Menampilkan halaman edit PPID Pembantu.
     */
    public function edit(int $id): View
    {
        $ppidPembantu = $this
            ->ppidPembantuService
            ->findById($id);

        $kategori = $this
            ->ppidPembantuService
            ->getKategoriList();

        return view(
            'pages.admin.ppid-pembantu.edit',
            compact(
                'ppidPembantu',
                'kategori'
            )
        );
    }

    /**
     * Memperbarui PPID Pembantu.
     */
    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $validated = $this
            ->validatePpidPembantu($request);

        $ppidPembantu = $this
            ->ppidPembantuService
            ->update(
                $id,
                $validated
            );

        return redirect()
            ->route(
                'admin.ppid-pembantu.show',
                $ppidPembantu->id
            )
            ->with(
                'success',
                'Data PPID Pembantu berhasil diperbarui.'
            );
    }

    /**
     * Menghapus PPID Pembantu.
     */
    public function destroy(
        int $id
    ): RedirectResponse {
        $this->ppidPembantuService
            ->delete($id);

        return redirect()
            ->route(
                'admin.ppid-pembantu.index'
            )
            ->with(
                'success',
                'Data PPID Pembantu berhasil dihapus.'
            );
    }

    /**
     * Validasi tambah dan edit PPID Pembantu.
     */
    private function validatePpidPembantu(
        Request $request
    ): array {
        return $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:500',
            ],

            'kategori_ppidid' => [
                'nullable',
                'integer',
            ],

            'linkweb' => [
                'nullable',
                'string',
                'max:100',
            ],

            'telp' => [
                'nullable',
                'string',
                'max:15',
            ],

            'alamat' => [
                'nullable',
                'string',
                'max:50',
            ],

            'icon' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);
    }
}
