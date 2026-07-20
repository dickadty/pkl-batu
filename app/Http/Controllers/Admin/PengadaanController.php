<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpidPembantu;
use App\Services\Admin\PengadaanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PengadaanController extends Controller
{
    public function __construct(
        private readonly PengadaanService $pengadaanService
    ) {}

    public function index(
        Request $request
    ): View {
        $admin = $this->getAuthenticatedAdmin();

        $pengadaanList =
            $this->pengadaanService
            ->paginateForAdmin(
                $admin,
                $request->query('q'),
                10
            );

        return view(
            'pages.admin.pengadaan.index',
            compact('pengadaanList')
        );
    }

    public function create(): View
    {
        $admin = $this->getAuthenticatedAdmin();

        $formContext =
            $this->pengadaanService
            ->getFormContext($admin);

        return view(
            'pages.admin.pengadaan.create',
            [
                'ppidPembantu' =>
                $formContext['ppidPembantu'],

                'lockedPpid' =>
                $formContext['lockedPpid'],
            ]
        );
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $validated = $this->validatePengadaan(
            $request,
            $admin
        );

        $pengadaan =
            $this->pengadaanService
            ->createForAdmin(
                $admin,
                $validated
            );

        return redirect()
            ->route(
                'admin.pengadaan.show',
                $pengadaan->id
            )
            ->with(
                'success',
                'Data pengadaan berhasil ditambahkan.'
            );
    }

    public function show(int $id): View
    {
        $admin = $this->getAuthenticatedAdmin();

        $pengadaan =
            $this->pengadaanService
            ->findForAdmin(
                $admin,
                $id
            );

        return view(
            'pages.admin.pengadaan.show',
            compact('pengadaan')
        );
    }

    public function edit(int $id): View
    {
        $admin = $this->getAuthenticatedAdmin();

        $pengadaan =
            $this->pengadaanService
            ->findForAdmin(
                $admin,
                $id
            );

        $formContext =
            $this->pengadaanService
            ->getFormContext($admin);

        return view(
            'pages.admin.pengadaan.edit',
            [
                'pengadaan' => $pengadaan,

                'ppidPembantu' =>
                $formContext['ppidPembantu'],

                'lockedPpid' =>
                $formContext['lockedPpid'],
            ]
        );
    }

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $validated = $this->validatePengadaan(
            $request,
            $admin
        );

        $pengadaan =
            $this->pengadaanService
            ->updateForAdmin(
                $admin,
                $id,
                $validated
            );

        return redirect()
            ->route(
                'admin.pengadaan.show',
                $pengadaan->id
            )
            ->with(
                'success',
                'Data pengadaan berhasil diperbarui.'
            );
    }

    public function destroy(int $id): RedirectResponse
    {
        $admin = $this->getAuthenticatedAdmin();

        $this->pengadaanService
            ->deleteForAdmin(
                $admin,
                $id
            );

        return redirect()
            ->route('admin.pengadaan.index')
            ->with(
                'success',
                'Data pengadaan berhasil dihapus.'
            );
    }

    private function validatePengadaan(
        Request $request,
        object $admin
    ): array {
        $ppidTable = (
            new PpidPembantu()
        )->getTable();

        $ppidRules = [
            'nullable',
            'integer',
            Rule::exists(
                $ppidTable,
                'id'
            ),
        ];

        if (
            $this->pengadaanService
            ->isAdminUtama($admin)
        ) {
            $ppidRules[0] = 'required';
        }

        return $request->validate(
            [
                'nama_paket' => [
                    'required',
                    'string',
                    'max:200',
                ],

                'pagu' => [
                    'required',
                    'string',
                    'max:250',
                    'regex:/^[0-9\.\,\s]+$/',
                ],

                'sumber_dana' => [
                    'required',
                    'string',
                    'max:250',
                ],

                'metode' => [
                    'required',
                    'string',
                    'max:250',
                ],

                'rencana_kegiatan' => [
                    'required',
                    'string',
                    'max:500',
                ],

                'ppid_pembantuid' =>
                $ppidRules,
            ],
            [
                'nama_paket.required' =>
                'Nama paket wajib diisi.',

                'nama_paket.max' =>
                'Nama paket maksimal 200 karakter.',

                'pagu.required' =>
                'Pagu anggaran wajib diisi.',

                'pagu.regex' =>
                'Pagu anggaran hanya boleh berisi angka.',

                'pagu.max' =>
                'Pagu anggaran terlalu panjang.',

                'sumber_dana.required' =>
                'Sumber dana wajib diisi.',

                'sumber_dana.max' =>
                'Sumber dana maksimal 250 karakter.',

                'metode.required' =>
                'Metode pengadaan wajib diisi.',

                'metode.max' =>
                'Metode pengadaan maksimal 250 karakter.',

                'rencana_kegiatan.required' =>
                'Rencana kegiatan wajib diisi.',

                'rencana_kegiatan.max' =>
                'Rencana kegiatan maksimal 500 karakter.',

                'ppid_pembantuid.required' =>
                'PPID Pembantu wajib dipilih.',

                'ppid_pembantuid.integer' =>
                'PPID Pembantu tidak valid.',

                'ppid_pembantuid.exists' =>
                'PPID Pembantu yang dipilih tidak ditemukan.',
            ]
        );
    }

    private function getAuthenticatedAdmin(): object
    {
        $admin = Auth::guard('admin')->user();

        abort_unless(
            $admin,
            403,
            'Sesi admin tidak ditemukan.'
        );

        return $admin;
    }
}
