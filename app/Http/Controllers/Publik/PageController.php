<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Pages;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Pages::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('pages.public.page', compact('page'));
    }
}