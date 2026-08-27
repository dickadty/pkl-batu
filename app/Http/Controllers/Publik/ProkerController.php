<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Proker;
use Illuminate\View\View;

class ProkerController extends Controller
{
    public function index(): View
    {
        $proker = Proker::query()
            ->with('ppidPembantu:id,nama')
            ->orderByDesc('jadwal_pelaksanaan')
            ->orderByDesc('id')
            ->paginate(10);

        return view('pages.public.program-kerja.index', compact('proker'));
    }

    public function show(int $id): View
    {
        $proker = Proker::query()
            ->with('ppidPembantu:id,nama')
            ->findOrFail($id);

        return view('pages.public.program-kerja.show', compact('proker'));
    }
}