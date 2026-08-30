<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Services\Publik\BeritaService;
use Illuminate\Contracts\View\View;

class BeritaController extends Controller
{
    public function __construct(
        protected BeritaService $beritaService
    ) {}

    public function index(): View
    {
        $berita = $this->beritaService->getAll();

        return view(
            'pages.public.berita.index',
            compact('berita')
        );
    }

    public function show(int $id): View
    {
        $berita = Berita::query()
            ->findOrFail($id);

        $beritaLainnya = Berita::query()
            ->where('id', '!=', $berita->id)
            ->latest('id')
            ->limit(4)
            ->get();

        return view(
            'pages.public.berita.show',
            compact(
                'berita',
                'beritaLainnya'
            )
        );
    }
}
