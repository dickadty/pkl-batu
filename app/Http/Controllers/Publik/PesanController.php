<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Services\Publik\PesanMasukService;
use Illuminate\Http\Request;

class PesanController extends Controller
{
    public function __construct(
        protected PesanMasukService $pesanMasukService
    ) {}

    public function create()
    {
        return view('pages.public.pesan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'subjek' => 'required|string|max:150',
            'pesan' => 'required|string|max:1000',
        ]);

        $pesan = $this->pesanMasukService->createFromPublic($validated);

        return redirect()
            ->route('public.pesan.show', $pesan->token)
            ->with('success', 'Pesan berhasil dikirim. Simpan link percakapan ini untuk melihat balasan admin.');
    }

    public function show($token)
    {
        $pesan = $this->pesanMasukService->findByToken($token);

        return view('pages.public.pesan.show', compact('pesan'));
    }

    public function messages($token)
    {
        $pesan = $this->pesanMasukService->findByToken($token);

        return response()->json(
            $this->pesanMasukService->getConversationPayload($pesan)
        );
    }

    public function reply(Request $request, $token)
    {
        $validated = $request->validate([
            'pesan' => 'required|string|max:1000',
        ]);

        $this->pesanMasukService->replyFromPublic($token, $validated);

        return redirect()
            ->route('public.pesan.show', $token)
            ->with('success', 'Balasan berhasil dikirim.');
    }
}
