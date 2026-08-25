<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Faq;

class HomeController extends Controller
{
    public function index()
    {
        $berita = Berita::latest('id')
            ->take(6)
            ->get();

       $faq = Faq::where('status', 1)
        ->latest('id')
        ->get();

        return view('pages.public.beranda', compact('berita', 'faq'));
    }
}