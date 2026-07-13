<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Berita;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::orderBy('tanggal', 'desc')->paginate(6);

        return view('pages.public.berita.index', compact('berita'));
    }

    public function show($id)
    {
        $berita = Berita::findOrFail($id);

        return view('pages.public.berita.show', compact('berita'));
    }
}