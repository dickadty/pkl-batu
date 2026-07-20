<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Services\Admin\InformasiPublikService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DokumentasiController extends Controller
{
    public function __construct(
        protected InformasiPublikService $informasiPublikService
    ) {}

    /**
     * Menampilkan daftar informasi publik.
     */
    public function index(Request $request): View
    {
        $admin = $this->currentAdmin();

        $filters = $this->getFilters($request);

        $perPage = $this->getPerPage($request);

        $dokumentasi = $this->informasiPublikService->getForAdmin(
            $admin,
            $filters,
            $perPage
        );

        $ppidPembantuList = $admin->isAdminUtama()
            ? $this->informasiPublikService->getPpidPembantuList()
            : collect();

        return view(
            'pages.admin.dokumentasi.index',
            compact(
                'admin',
                'dokumentasi',
                'filters',
                'ppidPembantuList'
            )
        );
    }

    /**
     * Menampilkan halaman tambah informasi publik.
     */
    public function create(): View
    {
        $admin = $this->currentAdmin();

        $ppidPembantu = $admin->isAdminUtama()
            ? $this->informasiPublikService->getPpidPembantuList()
            : collect();

        return view(
            'pages.admin.dokumentasi.create',
            compact(
                'admin',
                'ppidPembantu'
            )
        );
    }

    /**
     * Menyimpan informasi publik.
     */
    public function store(Request $request): RedirectResponse
    {
        $admin = $this->currentAdmin();

        $validated = $request->validate(
            $this->validationRules(fileRequired: true)
        );

        /** @var UploadedFile $file */
        $file = $request->file('file');

        unset($validated['file']);

        $this->informasiPublikService->create(
            $validated,
            $file,
            $admin
        );

        return redirect()
            ->route('admin.informasi-publik.index')
            ->with(
                'success',
                'Informasi publik berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail informasi publik di area admin.
     */
    public function show(int $id): View
    {
        $admin = $this->currentAdmin();

        $dokumentasi = $this->informasiPublikService->getByIdForAdmin(
            $id,
            $admin
        );

        return view(
            'pages.admin.dokumentasi.show',
            compact(
                'admin',
                'dokumentasi'
            )
        );
    }

    /**
     * Menampilkan halaman edit informasi publik.
     */
    public function edit(int $id): View
    {
        $admin = $this->currentAdmin();

        $dokumentasi = $this->informasiPublikService->getByIdForAdmin(
            $id,
            $admin
        );

        $ppidPembantu = $admin->isAdminUtama()
            ? $this->informasiPublikService->getPpidPembantuList()
            : collect();

        return view(
            'pages.admin.dokumentasi.edit',
            compact(
                'admin',
                'dokumentasi',
                'ppidPembantu'
            )
        );
    }

    /**
     * Memperbarui informasi publik.
     */
    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this->currentAdmin();

        $validated = $request->validate(
            $this->validationRules(fileRequired: false)
        );

        /** @var UploadedFile|null $file */
        $file = $request->file('file');

        unset($validated['file']);

        $this->informasiPublikService->update(
            $id,
            $validated,
            $file,
            $admin
        );

        return redirect()
            ->route(
                'admin.informasi-publik.show',
                $id
            )
            ->with(
                'success',
                'Informasi publik berhasil diperbarui.'
            );
    }

    /**
     * Memverifikasi informasi publik.
     */
    public function verifikasi(int $id): RedirectResponse
    {
        $admin = $this->currentAdmin();

        $this->informasiPublikService->verify(
            $id,
            $admin
        );

        return back()->with(
            'success',
            'Informasi publik berhasil diverifikasi.'
        );
    }

    /**
     * Menghapus informasi publik.
     */
    public function destroy(int $id): RedirectResponse
    {
        $admin = $this->currentAdmin();

        $this->informasiPublikService->delete(
            $id,
            $admin
        );

        return redirect()
            ->route('admin.informasi-publik.index')
            ->with(
                'success',
                'Informasi publik berhasil dihapus.'
            );
    }

    /**
     * Mengambil filter dari request.
     */
    private function getFilters(Request $request): array
    {
        return [
            'search' => trim(
                (string) $request->input(
                    'q',
                    $request->input('search', '')
                )
            ),

            'status' => $request->input('status'),
            'sifat' => $request->input('sifat'),
            'tahun' => $request->input('tahun'),
            'ppid_pembantuid' => $request->input(
                'ppid_pembantuid'
            ),
        ];
    }

    /**
     * Mengatur jumlah data per halaman.
     */
    private function getPerPage(Request $request): int
    {
        $perPage = (int) $request->input(
            'per_page',
            15
        );

        return max(
            5,
            min($perPage, 100)
        );
    }

    /**
     * Aturan validasi tambah dan edit.
     */
    private function validationRules(
        bool $fileRequired
    ): array {
        return [
            'nama' => [
                'required',
                'string',
                'max:250',
            ],

            'tahun' => [
                'nullable',
                'integer',
                'min:2000',
                'max:2100',
            ],

            'ringkasan' => [
                'nullable',
                'string',
            ],

            'sifat' => [
                'nullable',
                'string',
                Rule::in([
                    'setiap saat',
                    'berkala',
                    'serta merta',
                    'dikecualikan',
                ]),
            ],

            'ppid_pembantuid' => [
                'nullable',
                'integer',
                Rule::exists(
                    'ppid_pembantu',
                    'id'
                ),
            ],

            'file' => [
                $fileRequired
                    ? 'required'
                    : 'nullable',

                'file',

                'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg',

                'max:5120',
            ],
        ];
    }

    /**
     * Mengambil admin yang sedang login.
     */
    private function currentAdmin(): Authorization
    {
        $admin = Auth::guard('admin')->user();

        abort_unless(
            $admin instanceof Authorization,
            401,
            'Sesi admin tidak valid.'
        );

        return $admin;
    }
}
