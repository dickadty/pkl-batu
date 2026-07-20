<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PermohonanService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermohonanController extends Controller
{
    public function __construct(
        protected PermohonanService $permohonanService
    ) {}

    /**
     * Menampilkan daftar permohonan sesuai hak akses admin.
     */
    public function index(): View
    {
        $admin = $this->getAuthenticatedAdmin();

        $permohonan = $this->permohonanService
            ->getForAdmin($admin);

        return view(
            'pages.admin.permohonan.index',
            compact('permohonan', 'admin')
        );
    }

    /**
     * Menampilkan detail permohonan.
     */
    public function show(int $id): View
    {
        $admin = $this->getAuthenticatedAdmin();

        $permohonan = $this->permohonanService
            ->getDetailForAdmin(
                $id,
                $admin
            );

        $ppidPembantu = $this->permohonanService
            ->getPpidPembantuList();

        return view(
            'pages.admin.permohonan.show',
            compact(
                'permohonan',
                'admin',
                'ppidPembantu'
            )
        );
    }

    /**
     * Admin Utama meneruskan permohonan ke PPID Pembantu.
     */
    public function teruskan(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $validated = $request->validate([
            'ppid_pembantuid' => [
                'bail',
                'required',
                'integer',
                'exists:ppid_pembantu,id',
            ],

            'catatan_utama' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $permohonan = $this->permohonanService
            ->teruskan(
                $id,
                $admin,
                $validated
            );

        /*
         * Notifikasi disposisi nantinya ditempatkan setelah
         * service berhasil mengembalikan data permohonan.
         *
         * Penerima notifikasi belum ditentukan di sini karena
         * relasi Admin dengan PPID Pembantu belum diperiksa.
         */

        return redirect()
            ->route(
                'admin.permohonan.show',
                $permohonan->id
            )
            ->with(
                'success',
                'Permohonan berhasil diteruskan ke PPID Pembantu.'
            );
    }

    /**
     * PPID Pembantu mengirim jawaban kepada Admin Utama.
     */
    public function jawabPembantu(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $validated = $request->validate([
            'jawaban_pembantu' => [
                'bail',
                'required',
                'string',
                'max:10000',
            ],

            'file_pembantu' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $permohonan = $this->permohonanService
            ->jawabPembantu(
                $id,
                $admin,
                $validated,
                $request->file('file_pembantu')
            );

        /*
         * Notifikasi kepada Admin Utama nantinya ditempatkan
         * setelah method service ini berhasil.
         */

        return redirect()
            ->route(
                'admin.permohonan.show',
                $permohonan->id
            )
            ->with(
                'success',
                'Laporan berhasil dikirim ke Admin Utama.'
            );
    }

    /**
     * Admin Utama memvalidasi jawaban final.
     */
    public function validasi(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $validated = $request->validate([
            'jawaban_final' => [
                'bail',
                'required',
                'string',
                'max:10000',
            ],
        ]);

        $permohonan = $this->permohonanService
            ->validasi(
                $id,
                $admin,
                $validated
            );

        return redirect()
            ->route(
                'admin.permohonan.show',
                $permohonan->id
            )
            ->with(
                'success',
                'Permohonan berhasil divalidasi dan dikirim ke warga.'
            );
    }

    /**
     * Admin Utama mengembalikan jawaban untuk direvisi.
     */
    public function revisi(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $validated = $request->validate([
            'catatan_revisi' => [
                'bail',
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $permohonan = $this->permohonanService
            ->revisi(
                $id,
                $admin,
                $validated
            );

        /*
         * Notifikasi revisi kepada PPID Pembantu nantinya
         * ditempatkan setelah method service ini berhasil.
         */

        return redirect()
            ->route(
                'admin.permohonan.show',
                $permohonan->id
            )
            ->with(
                'success',
                'Permohonan dikembalikan ke PPID Pembantu untuk revisi.'
            );
    }

    /**
     * Mengambil admin yang sedang login.
     */
    private function getAuthenticatedAdmin()
    {
        $admin = Auth::guard('admin')->user();

        abort_unless(
            $admin,
            401,
            'Sesi admin tidak ditemukan.'
        );

        return $admin;
    }
}
