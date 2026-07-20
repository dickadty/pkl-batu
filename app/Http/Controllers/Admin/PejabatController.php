<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PejabatService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class PejabatController extends Controller
{
    public function __construct(
        protected PejabatService $pejabatService
    ) {}

    /**
     * Menampilkan daftar pejabat.
     */
    public function index(
        Request $request
    ): View {
        $pejabatCollection = $this
            ->pejabatService
            ->getAllForAdmin();

        $search = trim(
            (string) $request->input(
                'q',
                ''
            )
        );

        if ($search !== '') {
            $normalizedSearch = mb_strtolower(
                $search
            );

            $pejabatCollection = $pejabatCollection
                ->filter(
                    function (
                        $item
                    ) use ($normalizedSearch): bool {
                        $searchableText = mb_strtolower(
                            implode(
                                ' ',
                                [
                                    (string) ($item->nama ?? ''),
                                    (string) ($item->jabatan ?? ''),
                                    (string) ($item->masa ?? ''),
                                    (string) ($item->tmp_tgl_lahir ?? ''),
                                    (string) ($item->alamat ?? ''),
                                    (string) ($item->no_telp ?? ''),
                                ]
                            )
                        );

                        return str_contains(
                            $searchableText,
                            $normalizedSearch
                        );
                    }
                )
                ->values();
        }

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

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $pejabat = new LengthAwarePaginator(
            $pejabatCollection
                ->forPage(
                    $currentPage,
                    $perPage
                )
                ->values(),
            $pejabatCollection->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view(
            'pages.admin.pejabat.index',
            compact('pejabat')
        );
    }

    /**
     * Menampilkan halaman tambah pejabat.
     */
    public function create(): View
    {
        return view(
            'pages.admin.pejabat.create'
        );
    }

    /**
     * Menyimpan pejabat.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $this
            ->validatePejabat($request);

        $pejabat = $this
            ->pejabatService
            ->create(
                $validated,
                $request->file('foto')
            );

        return redirect()
            ->route(
                'admin.pejabat.show',
                $pejabat->id
            )
            ->with(
                'success',
                'Data pejabat berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail pejabat.
     */
    public function show(int $id): View
    {
        $pejabat = $this
            ->pejabatService
            ->findById($id);

        return view(
            'pages.admin.pejabat.show',
            compact('pejabat')
        );
    }

    /**
     * Menampilkan halaman edit pejabat.
     */
    public function edit(int $id): View
    {
        $pejabat = $this
            ->pejabatService
            ->findById($id);

        return view(
            'pages.admin.pejabat.edit',
            compact('pejabat')
        );
    }

    /**
     * Memperbarui pejabat.
     */
    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $validated = $this
            ->validatePejabat($request);

        $pejabat = $this
            ->pejabatService
            ->update(
                $id,
                $validated,
                $request->file('foto')
            );

        return redirect()
            ->route(
                'admin.pejabat.show',
                $pejabat->id
            )
            ->with(
                'success',
                'Data pejabat berhasil diperbarui.'
            );
    }

    /**
     * Menghapus pejabat.
     */
    public function destroy(
        int $id
    ): RedirectResponse {
        $this->pejabatService
            ->delete($id);

        return redirect()
            ->route(
                'admin.pejabat.index'
            )
            ->with(
                'success',
                'Data pejabat berhasil dihapus.'
            );
    }

    /**
     * Validasi tambah dan edit pejabat.
     */
    private function validatePejabat(
        Request $request
    ): array {
        return $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
            ],

            'jabatan' => [
                'required',
                'string',
                'max:100',
            ],

            'masa' => [
                'nullable',
                'string',
                'max:100',
            ],

            'tmp_tgl_lahir' => [
                'nullable',
                'string',
                'max:150',
            ],

            'alamat' => [
                'nullable',
                'string',
                'max:255',
            ],

            'no_telp' => [
                'nullable',
                'string',
                'max:30',
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);
    }
}
