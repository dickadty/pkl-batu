<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PesanMasukService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesanMasukController extends Controller
{
    public function __construct(
        protected PesanMasukService $pesanMasukService
    ) {}

    public function index()
    {
        $pesanMasuk = $this->pesanMasukService->getAllForAdmin();

        return view('pages.admin.pesan-masuk.index', compact('pesanMasuk'));
    }

    public function show($id)
    {
        $pesan = $this->pesanMasukService->getDetailForAdmin((int) $id);

        return view('pages.admin.pesan-masuk.show', compact('pesan'));
    }

    public function messages($id)
    {
        $pesan = $this->pesanMasukService->getDetailForAdmin((int) $id);

        return response()->json(
            $this->pesanMasukService->getConversationPayload($pesan)
        );
    }

    public function reply(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'pesan' => 'required|string|max:1000',
        ]);

        $this->pesanMasukService->replyFromAdmin(
            (int) $id,
            $admin,
            $validated
        );

        return redirect()
            ->route('admin.pesan-masuk.show', $id)
            ->with('success', 'Balasan berhasil dikirim.');
    }

    public function close($id)
    {
        $this->pesanMasukService->close((int) $id);

        return redirect()
            ->route('admin.pesan-masuk.show', $id)
            ->with('success', 'Percakapan berhasil ditutup.');
    }

    public function destroy($id)
    {
        $this->pesanMasukService->delete((int) $id);

        return redirect()
            ->route('admin.pesan-masuk.index')
            ->with('success', 'Pesan masuk berhasil dihapus.');
    }
}
