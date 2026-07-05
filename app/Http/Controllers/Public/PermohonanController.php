<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Permohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermohonanController extends Controller
{
    public function index()
    {
        $user = Auth::guard('public')->user();

        $permohonan = Permohonan::where('user_publikid', $user->id)
            ->latest('id')
            ->get();

        return view('pages.public.permohonan.index', compact('permohonan'));
    }

    public function create()
    {
        $user = Auth::guard('public')->user();

        return view('pages.public.permohonan.create', compact('user'));
    }

    public function store(Request $request)
    {
        $user = Auth::guard('public')->user();

        $validated = $request->validate([
            'rincian' => 'required|string|max:500',
            'tujuan' => 'required|string|max:500',
        ]);

        Permohonan::create([
            'no_pemohon' => time(),
            'tanggal' => now()->toDateString(),
            'rincian' => $validated['rincian'],
            'tujuan' => $validated['tujuan'],
            'status' => 'Diajukan',
            'user_publikid' => $user->id,
        ]);

        return redirect()
            ->route('public.permohonan.index')
            ->with('success', 'Permohonan informasi berhasil diajukan.');
    }
}