<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Services\Publik\AuthService;
use App\Services\Publik\PermohonanService;
use Illuminate\Http\Request;

class PermohonanController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected PermohonanService $permohonanService
    ) {}

    public function index()
    {
        $user = $this->authService->getLoggedUser();

        $permohonan = $this->permohonanService->getByUser($user);

        return view('pages.public.permohonan.index', compact('permohonan'));
    }

    public function create()
    {
        $user = $this->authService->getLoggedUser();

        return view('pages.public.permohonan.create', compact('user'));
    }

    public function store(Request $request)
    {
        $user = $this->authService->getLoggedUser();

        $validated = $request->validate([
            'rincian' => 'required|string|max:500',
            'tujuan' => 'required|string|max:500',
        ]);

        $this->permohonanService->createForUser($user, $validated);

        return redirect()
            ->route('public.permohonan.index')
            ->with('success', 'Permohonan informasi berhasil diajukan.');
    }
}
