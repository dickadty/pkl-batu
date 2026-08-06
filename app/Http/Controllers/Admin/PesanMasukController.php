<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Services\Admin\PesanMasukService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PesanMasukController extends Controller
{
    public function __construct(
        protected PesanMasukService $pesanMasukService
    ) {}

    /**
     * Menampilkan daftar pesan masuk.
     */
    public function index(
        Request $request
    ): View {
        $statusOptions = $this
            ->pesanMasukService
            ->getStatusOptions();

        $validated = $request->validate(
            [
                'q' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'status' => [
                    'nullable',
                    'string',
                    Rule::in(
                        array_keys(
                            $statusOptions
                        )
                    ),
                ],

                'per_page' => [
                    'nullable',
                    'integer',
                    'in:10,15,25,50,100',
                ],
            ],
            [
                'q.string' =>
                'Kata pencarian harus berupa teks.',

                'q.max' =>
                'Kata pencarian maksimal 255 karakter.',

                'status.in' =>
                'Status pesan tidak valid.',

                'per_page.integer' =>
                'Jumlah data per halaman tidak valid.',

                'per_page.in' =>
                'Pilihan jumlah data per halaman tidak valid.',
            ]
        );

        $currentStatus = $validated['status'] ?? 'semua';

        $filters = array_merge(
            $validated,
            [
                'status' => $currentStatus,
            ]
        );

        $pesanMasuk = $this
            ->pesanMasukService
            ->getAllForAdmin($filters);

        $summary = $this
            ->pesanMasukService
            ->getSummaryForAdmin();

        return view(
            'pages.admin.pesan-masuk.index',
            [
                'pesanMasuk' =>
                $pesanMasuk,

                'currentStatus' =>
                $currentStatus,

                'statusOptions' =>
                $statusOptions,

                'totalSemua' =>
                $summary['semua'],

                'totalBaru' =>
                $summary['baru'],

                'totalDibaca' =>
                $summary['dibaca'],

                'totalDibalas' =>
                $summary['dibalas'],

                'totalDitutup' =>
                $summary['ditutup'],
            ]
        );
    }

    /**
     * Menampilkan detail pesan.
     */
    public function show(
        int $id
    ): View {
        $pesan = $this->pesanMasukService
            ->getDetailForAdmin($id);

        return view(
            'pages.admin.pesan-masuk.show',
            [
                'pesan' => $pesan,
            ]
        );
    }

    /**
     * Mengambil isi percakapan.
     */
    public function messages(
        int $id
    ): JsonResponse {
        $pesan = $this->pesanMasukService
            ->getDetailForAdmin($id);

        return response()->json(
            $this->pesanMasukService
                ->getConversationPayload(
                    $pesan
                )
        );
    }

    /**
     * Mengambil jumlah chat baru untuk sidebar.
     */
    public function unreadCount(): JsonResponse
    {
        return response()->json([
            'success' => true,

            'unread_count' =>
            $this->pesanMasukService
                ->countUnread(),
        ]);
    }

    /**
     * Mengirim balasan admin.
     */
    public function reply(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this
            ->getAuthenticatedAdmin();

        $validated = $request->validate(
            [
                'pesan' => [
                    'bail',
                    'required',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'pesan.required' =>
                'Pesan balasan wajib diisi.',

                'pesan.string' =>
                'Pesan balasan harus berupa teks.',

                'pesan.max' =>
                'Pesan balasan maksimal 1.000 karakter.',
            ]
        );

        $this->pesanMasukService
            ->replyFromAdmin(
                $id,
                $admin,
                $validated
            );

        return redirect()
            ->route(
                'admin.pesan-masuk.show',
                [
                    'id' => $id,
                ]
            )
            ->with(
                'success',
                'Balasan berhasil dikirim.'
            );
    }

    /**
     * Menutup percakapan.
     */
    public function close(
        int $id
    ): RedirectResponse {
        $this->pesanMasukService
            ->close($id);

        return redirect()
            ->route(
                'admin.pesan-masuk.show',
                [
                    'id' => $id,
                ]
            )
            ->with(
                'success',
                'Percakapan berhasil ditutup.'
            );
    }

    /**
     * Menghapus percakapan.
     */
    public function destroy(
        int $id
    ): RedirectResponse {
        $this->pesanMasukService
            ->delete($id);

        return redirect()
            ->route(
                'admin.pesan-masuk.index'
            )
            ->with(
                'success',
                'Pesan masuk berhasil dihapus.'
            );
    }

    /**
     * Mengambil admin yang login.
     */
    private function getAuthenticatedAdmin(): Authorization
    {
        $admin = Auth::guard('admin')->user();

        abort_unless(
            $admin instanceof Authorization,
            401,
            'Sesi admin tidak ditemukan.'
        );

        return $admin;
    }
}
