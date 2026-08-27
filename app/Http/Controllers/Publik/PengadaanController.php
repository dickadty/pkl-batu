<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use Illuminate\View\View;

class PengadaanController extends Controller
{
    public function index(): View
    {
        $pengadaan = Pengadaan::query()
            ->with('ppidPembantu:id,nama')
            ->orderByDesc('id')
            ->paginate(9);

        return view(
            'pages.public.pengadaan.index',
            compact('pengadaan')
        );
    }

    public function show(int $id): View
    {
        $pengadaan = Pengadaan::query()
            ->with('ppidPembantu:id,nama')
            ->findOrFail($id);

        return view(
            'pages.public.pengadaan.show',
            compact('pengadaan')
        );
    }
}