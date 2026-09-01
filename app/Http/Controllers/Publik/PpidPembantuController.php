<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\KategoriPpid;
use App\Models\PpidPembantu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpidPembantuController extends Controller
{
    public function index(Request $request): View
    {
        $categories = KategoriPpid::query()
            ->with([
                'ppidPembantu' => function ($query) {
                    $query->orderBy('nama');
                },
            ])
            ->withCount('ppidPembantu')
            ->orderBy('kategori')
            ->get()
            ->filter(fn ($category) => (int) $category->ppid_pembantu_count > 0);

        $selectedCategoryId = $request->query('kategori');

        $visibleCategories = $categories;

        if ($selectedCategoryId !== null && $selectedCategoryId !== '') {
            $visibleCategories = $categories->filter(function ($category) use ($selectedCategoryId) {
                return (string) $category->id === (string) $selectedCategoryId;
            });
        }

        $totalCategories = $categories->count();
        $totalUnits = $categories->sum(fn ($category) => (int) $category->ppid_pembantu_count);

        return view('pages.public.ppid-pelaksana.index', compact(
            'categories',
            'visibleCategories',
            'selectedCategoryId',
            'totalCategories',
            'totalUnits'
        ));
    }

    public function show(string $slug): View
    {
        $ppidPembantu = PpidPembantu::query()
            ->with('kategoriPpid')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.public.ppid-pelaksana.show', compact('ppidPembantu'));
    }
}
