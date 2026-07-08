<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Services\Publik\BeritaService;

class BeritaController extends Controller
{
    public function __construct(
        protected BeritaService $beritaService
    ) {}

    public function index()
    {
        $berita = $this->beritaService->getAll();

        return view('pages.public.berita.index', compact('berita'));
    }

    public function show($id)
    {
        $berita = $this->beritaService->findById((int) $id);

        return view('pages.public.berita.show', compact('berita'));
    }
}
