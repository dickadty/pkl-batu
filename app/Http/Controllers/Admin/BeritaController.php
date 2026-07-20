<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\BeritaService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function __construct(
        protected BeritaService $beritaService
    ) {}

    /**
     * Menampilkan daftar berita admin.
     */
    public function index(Request $request): View
    {
        $beritaCollection = $this->beritaService
            ->getAllForAdmin();

        $search = trim(
            (string) $request->input('q', '')
        );

        if ($search !== '') {
            $normalizedSearch = mb_strtolower($search);

            $beritaCollection = $beritaCollection
                ->filter(function ($item) use ($normalizedSearch): bool {
                    $judul = mb_strtolower(
                        (string) ($item->judul ?? '')
                    );

                    $caption = mb_strtolower(
                        strip_tags(
                            (string) ($item->caption ?? '')
                        )
                    );

                    return str_contains(
                        $judul,
                        $normalizedSearch
                    ) || str_contains(
                        $caption,
                        $normalizedSearch
                    );
                })
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

        $berita = new LengthAwarePaginator(
            $beritaCollection
                ->forPage(
                    $currentPage,
                    $perPage
                )
                ->values(),
            $beritaCollection->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view(
            'pages.admin.berita.index',
            compact('berita')
        );
    }

    /**
     * Menampilkan halaman tambah berita.
     */
    public function create(): View
    {
        return view(
            'pages.admin.berita.create'
        );
    }

    /**
     * Menyimpan berita baru.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'judul' => [
                'required',
                'string',
                'max:500',
            ],

            'caption' => [
                'nullable',
                'string',
            ],

            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $this->beritaService->create(
            $validated,
            $request->file('gambar')
        );

        return redirect()
            ->route('admin.berita.index')
            ->with(
                'success',
                'Berita berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail berita di area admin.
     */
    public function show(int $id): View
    {
        $berita = $this->beritaService
            ->getAllForAdmin()
            ->firstWhere('id', $id);

        abort_if(
            $berita === null,
            404,
            'Data berita tidak ditemukan.'
        );

        return view(
            'pages.admin.berita.show',
            compact('berita')
        );
    }

    /**
     * Menghapus berita.
     */
    public function destroy(
        int $id
    ): RedirectResponse {
        $this->beritaService->delete($id);

        return redirect()
            ->route('admin.berita.index')
            ->with(
                'success',
                'Berita berhasil dihapus.'
            );
    }
}
