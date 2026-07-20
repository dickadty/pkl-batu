<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Berita;

class HomeController extends Controller
{
    public function index()
    {
        $berita = Berita::latest('id')
            ->take(6)
            ->get();

        return view('pages.public.beranda', compact('berita'));
    }
}
