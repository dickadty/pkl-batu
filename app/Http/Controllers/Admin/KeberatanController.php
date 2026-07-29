<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Models\Keberatan;
use App\Services\Admin\KeberatanService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KeberatanController extends Controller
{
    public function __construct(
        protected KeberatanService $keberatanService
    ) {}

    public function index(
        Request $request
    ): View {
        $admin = $this
            ->getAuthenticatedAdmin();

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
                            Keberatan::statusOptions()
                        )
                    ),
                ],

                'ppid_pembantuid' => [
                    'nullable',
                    'integer',
                    Rule::exists(
                        'ppid_pembantu',
                        'id'
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
                    'Status keberatan tidak valid.',

                'ppid_pembantuid.integer' =>
                    'PPID Pembantu tidak valid.',

                'ppid_pembantuid.exists' =>
                    'PPID Pembantu tidak ditemukan.',

                'per_page.integer' =>
                    'Jumlah data per halaman tidak valid.',

                'per_page.in' =>
                    'Pilihan jumlah data per halaman tidak valid.',
            ]
        );

        $keberatan = $this
            ->keberatanService
            ->getForAdmin(
                $admin,
                $validated
            );

        $summary = $this
            ->keberatanService
            ->getSummaryForAdmin(
                $admin
            );

        $ppidPembantuList = $this
            ->keberatanService
            ->getPpidPembantuListForAdmin(
                $admin
            );

        return view(
            'pages.admin.keberatan.index',
            [
                'admin' => $admin,
                'keberatan' => $keberatan,
                'summary' => $summary,
                'ppidPembantuList' =>
                    $ppidPembantuList,
                'statusOptions' =>
                    Keberatan::statusOptions(),
            ]
        );
    }


    public function show(
        int $id
    ): View {
        $admin = $this
            ->getAuthenticatedAdmin();

        $keberatan = $this
            ->keberatanService
            ->getDetailForAdmin(
                $id,
                $admin
            );

        return view(
            'pages.admin.keberatan.show',
            [
                'admin' => $admin,
                'keberatan' => $keberatan,
                'statusOptions' =>
                    Keberatan::statusOptions(),
            ]
        );
    }


    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this
            ->getAuthenticatedAdmin();

        $validated = $request->validate(
            [
                'status' => [
                    'bail',
                    'required',
                    'string',
                    Rule::in(
                        array_keys(
                            Keberatan::statusOptions()
                        )
                    ),
                ],

                'tanggapan' => [
                    'bail',
                    Rule::requiredIf(
                        in_array(
                            $request->input('status'),
                            [
                                Keberatan::STATUS_SELESAI,
                                Keberatan::STATUS_DITOLAK,
                            ],
                            true
                        )
                    ),
                    'nullable',
                    'string',
                    'max:10000',
                ],
            ],
            [
                'status.required' =>
                    'Status keberatan wajib dipilih.',

                'status.in' =>
                    'Status keberatan tidak valid.',

                'tanggapan.required' =>
                    'Tanggapan wajib diisi ketika keberatan selesai atau ditolak.',

                'tanggapan.string' =>
                    'Tanggapan harus berupa teks.',

                'tanggapan.max' =>
                    'Tanggapan maksimal 10.000 karakter.',
            ]
        );

        $keberatan = $this
            ->keberatanService
            ->updateResponse(
                $id,
                $admin,
                $validated
            );

        return redirect()
            ->route(
                'admin.keberatan.show',
                [
                    'id' => $keberatan->id,
                ]
            )
            ->with(
                'success',
                'Data keberatan berhasil diperbarui.'
            );
    }


    private function getAuthenticatedAdmin(): Authorization
    {
        $admin = Auth::guard(
            'admin'
        )->user();

        abort_unless(
            $admin instanceof Authorization,
            401,
            'Sesi admin tidak ditemukan.'
        );

        return $admin;
    }
}