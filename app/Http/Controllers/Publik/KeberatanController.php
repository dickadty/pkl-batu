<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Services\Publik\AuthService;
use App\Services\Publik\KeberatanService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KeberatanController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected KeberatanService $keberatanService
    ) {}

    /**
     * Menampilkan daftar keberatan warga.
     */
    public function index(
        Request $request
    ): View {
        $user = $this->authService
            ->getLoggedUser();

        abort_unless(
            $user,
            401,
            'Sesi warga tidak ditemukan.'
        );

        $validated = $request->validate([
            'per_page' => [
                'nullable',
                'integer',
                'in:10,15,25,50',
            ],
        ]);

        $perPage = (int) (
            $validated['per_page']
            ?? 15
        );

        $keberatan = $this
            ->keberatanService
            ->getByUser(
                $user,
                $perPage
            );

        return view(
            'pages.public.keberatan.index',
            [
                'user' => $user,
                'keberatan' => $keberatan,
            ]
        );
    }

    /**
     * Menampilkan form keberatan.
     */
    public function create(): View
    {
        $user = $this->authService
            ->getLoggedUser();

        abort_unless(
            $user,
            401,
            'Sesi warga tidak ditemukan.'
        );

        $permohonanList = $this
            ->keberatanService
            ->getEligiblePermohonan(
                $user
            );

        return view(
            'pages.public.keberatan.create',
            [
                'user' => $user,
                'permohonanList' =>
                $permohonanList,
            ]
        );
    }

    /**
     * Menyimpan keberatan.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $user = $this->authService
            ->getLoggedUser();

        abort_unless(
            $user,
            401,
            'Sesi warga tidak ditemukan.'
        );

        $validated = $request->validate(
            [
                'permohonanid' => [
                    'bail',
                    'required',
                    'integer',
                    Rule::exists(
                        'permohonan',
                        'id'
                    ),
                ],

                'alasan' => [
                    'bail',
                    'required',
                    'string',
                    'min:20',
                    'max:5000',
                ],
            ],
            [
                'permohonanid.required' =>
                'Permohonan wajib dipilih.',

                'permohonanid.integer' =>
                'Permohonan tidak valid.',

                'permohonanid.exists' =>
                'Permohonan tidak ditemukan.',

                'alasan.required' =>
                'Alasan keberatan wajib diisi.',

                'alasan.string' =>
                'Alasan keberatan harus berupa teks.',

                'alasan.min' =>
                'Alasan keberatan minimal 20 karakter.',

                'alasan.max' =>
                'Alasan keberatan maksimal 5.000 karakter.',
            ]
        );

        $keberatan = $this
            ->keberatanService
            ->createForUser(
                $user,
                $validated
            );

        return redirect()
            ->route(
                'public.keberatan.show',
                [
                    'id' => $keberatan->id,
                ]
            )
            ->with(
                'success',
                'Keberatan berhasil diajukan.'
            );
    }

    /**
     * Menampilkan detail keberatan.
     */
    public function show(
        int $id
    ): View {
        $user = $this->authService
            ->getLoggedUser();

        abort_unless(
            $user,
            401,
            'Sesi warga tidak ditemukan.'
        );

        $keberatan = $this
            ->keberatanService
            ->getDetailForUser(
                $id,
                $user
            );

        return view(
            'pages.public.keberatan.show',
            [
                'user' => $user,
                'keberatan' => $keberatan,
            ]
        );
    }

    /**
     * Mengunduh dokumen tanggapan keberatan milik warga.
     */
    public function file(
        int $id
    ): BinaryFileResponse {
        $user = $this->authService->getLoggedUser();

        abort_unless(
            $user,
            401,
            'Sesi warga tidak ditemukan.'
        );

        $keberatan = $this->keberatanService->getDetailForUser(
            $id,
            $user
        );

        abort_unless(
            $keberatan->isSelesai() && filled($keberatan->file_tanggapan),
            404,
            'Dokumen tanggapan tidak ditemukan.'
        );

        $disk = Storage::disk('local');

        abort_unless(
            $disk->exists($keberatan->file_tanggapan),
            404,
            'Dokumen tanggapan tidak ditemukan pada penyimpanan.'
        );

        return response()->download(
            $disk->path($keberatan->file_tanggapan),
            $keberatan->nama_file_tanggapan ?: basename($keberatan->file_tanggapan)
        );
    }
}
