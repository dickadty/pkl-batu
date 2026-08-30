<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\BeritaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BeritaController extends Controller
{
    public function __construct(
        protected BeritaService $beritaService
    ) {}

    public function index(Request $request): View
    {
        $beritaCollection = collect(
            $this->beritaService->getAllForAdmin()
        );

        $search = trim(
            (string) $request->input('q', '')
        );

        if ($search !== '') {
            $normalizedSearch = mb_strtolower(
                $search
            );

            $beritaCollection = $beritaCollection
                ->filter(
                    function ($item) use (
                        $normalizedSearch
                    ): bool {
                        if (!is_object($item)) {
                            return false;
                        }

                        $judul = mb_strtolower(
                            (string) ($item->judul ?? '')
                        );

                        $caption = mb_strtolower(
                            strip_tags(
                                (string) (
                                    $item->caption ?? ''
                                )
                            )
                        );

                        return str_contains(
                            $judul,
                            $normalizedSearch
                        ) || str_contains(
                            $caption,
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

        $currentPage = LengthAwarePaginator::resolveCurrentPage(
            'page'
        );

        $items = $beritaCollection
            ->forPage(
                $currentPage,
                $perPage
            )
            ->values();

        $berita = new LengthAwarePaginator(
            $items,
            $beritaCollection->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
                'pageName' => 'page',
            ]
        );

        return view(
            'pages.admin.berita.index',
            [
                'berita' => $berita,
            ]
        );
    }

    public function create(): View
    {
        return view(
            'pages.admin.berita.create'
        );
    }

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

        $gambar = $this->getUploadedImage(
            $request
        );

        $this->beritaService->create(
            $validated,
            $gambar
        );

        return redirect()
            ->route(
                'admin.berita.index'
            )
            ->with(
                'success',
                'Berita berhasil ditambahkan.'
            );
    }

    public function show(
        int $id
    ): View {
        $berita = $this->findBeritaOrFail(
            $id
        );

        return view(
            'pages.admin.berita.show',
            [
                'berita' => $berita,
            ]
        );
    }

    public function edit(
        int $id
    ): View {
        $berita = $this->findBeritaOrFail(
            $id
        );

        return view(
            'pages.admin.berita.edit',
            [
                'berita' => $berita,
            ]
        );
    }

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $this->findBeritaOrFail(
            $id
        );

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

        $gambar = $this->getUploadedImage(
            $request
        );

        $this->beritaService->update(
            $id,
            $validated,
            $gambar
        );

        return redirect()
            ->route(
                'admin.berita.index'
            )
            ->with(
                'success',
                'Berita berhasil diperbarui.'
            );
    }

    public function destroy(
        int $id
    ): RedirectResponse {
        $this->findBeritaOrFail(
            $id
        );

        $this->beritaService->delete(
            $id
        );

        return redirect()
            ->route(
                'admin.berita.index'
            )
            ->with(
                'success',
                'Berita berhasil dihapus.'
            );
    }

    private function findBeritaOrFail(
        int $id
    ): object {
        $beritaCollection = collect(
            $this->beritaService->getAllForAdmin()
        );

        $berita = $beritaCollection
            ->first(
                function ($item) use ($id): bool {
                    return is_object($item)
                        && (int) ($item->id ?? 0) === $id;
                }
            );

        if (!is_object($berita)) {
            abort(
                404,
                'Data berita tidak ditemukan.'
            );
        }

        return $berita;
    }

    private function getUploadedImage(
        Request $request
    ): ?UploadedFile {
        $file = $request->file(
            'gambar'
        );

        if (!$file instanceof UploadedFile) {
            return null;
        }

        return $file;
    }
}
