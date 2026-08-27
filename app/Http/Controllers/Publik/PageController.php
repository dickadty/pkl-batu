<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Pages;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Pages::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('pages.public.page', compact('page'));
    }

    public function file($slug)
    {
        $page = Pages::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        abort_unless($page->file && Storage::disk('public')->exists($page->file), 404);

        return response()->file(Storage::disk('public')->path($page->file));
    }
}