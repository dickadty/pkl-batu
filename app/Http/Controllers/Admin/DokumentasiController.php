<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Models\KategoriInformasi;
use App\Services\Admin\InformasiPublikService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        $kategori = KategoriInformasi::orderBy('nama')
            ->get();

        return view(
            'pages.admin.dokumentasi.create',
            compact(
                'admin',
                'ppidPembantu',
                'kategori'
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
            $this->validationRules(
                fileRequired: true
            )
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
            ->route(
                'admin.informasi-publik.index'
            )
            ->with(
                'success',
                'Informasi publik berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail informasi publik.
     */
    public function show(int $id): View
    {
        $admin = $this->currentAdmin();

        $dokumentasi = $this->informasiPublikService
            ->getByIdForAdmin(
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
     * Menampilkan file informasi publik secara INLINE.
     *
     * PDF dan gambar ditampilkan di browser.
     * DOC/DOCX/XLS/XLSX tergantung kemampuan browser.
     */
    public function showFile(int $id): StreamedResponse
    {
        $admin = $this->currentAdmin();

        /*
        |--------------------------------------------------------------------------
        | Ambil data informasi
        |--------------------------------------------------------------------------
        */

        $dokumentasi = $this->informasiPublikService
            ->getByIdForAdmin(
                $id,
                $admin
            );

        /*
        |--------------------------------------------------------------------------
        | Ambil path file dari database
        |--------------------------------------------------------------------------
        */

        $filePath = trim(
            (string) data_get(
                $dokumentasi,
                'file',
                ''
            )
        );

        if ($filePath === '') {
            abort(
                404,
                'File informasi belum tersedia.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Normalisasi path
        |--------------------------------------------------------------------------
        */

        $filePath = str_replace(
            '\\',
            '/',
            $filePath
        );

        $filePath = ltrim(
            $filePath,
            '/'
        );

        /*
        |--------------------------------------------------------------------------
        | Bersihkan prefix path
        |--------------------------------------------------------------------------
        |
        | Mendukung isi database seperti:
        |
        | informasi-publik/file.pdf
        | storage/informasi-publik/file.pdf
        | public/informasi-publik/file.pdf
        | storage/app/public/informasi-publik/file.pdf
        |
        */

        $prefixes = [
            'storage/app/public/',
            'storage/',
            'public/',
        ];

        foreach ($prefixes as $prefix) {
            if (
                str_starts_with(
                    $filePath,
                    $prefix
                )
            ) {
                $filePath = substr(
                    $filePath,
                    strlen($prefix)
                );

                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi file
        |--------------------------------------------------------------------------
        */

        if (
            !Storage::disk('public')
                ->exists($filePath)
        ) {
            abort(
                404,
                'File tidak ditemukan pada penyimpanan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Path file sebenarnya
        |--------------------------------------------------------------------------
        */

        $absolutePath = Storage::disk('public')
            ->path($filePath);

        if (
            !is_file($absolutePath)
            || !is_readable($absolutePath)
        ) {
            abort(
                404,
                'File tidak dapat dibaca.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Nama dan extension
        |--------------------------------------------------------------------------
        */

        $fileName = basename(
            $filePath
        );

        $extension = strtolower(
            pathinfo(
                $fileName,
                PATHINFO_EXTENSION
            )
        );

        /*
        |--------------------------------------------------------------------------
        | MIME Type
        |--------------------------------------------------------------------------
        |
        | MIME ditentukan berdasarkan extension agar PDF benar-benar
        | dikirim sebagai application/pdf.
        |
        */

        $mimeTypes = [
            'pdf' => 'application/pdf',

            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'bmp' => 'image/bmp',

            'doc' => 'application/msword',

            'docx' =>
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

            'xls' =>
            'application/vnd.ms-excel',

            'xlsx' =>
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $mimeType = $mimeTypes[$extension]
            ?? 'application/octet-stream';

        /*
        |--------------------------------------------------------------------------
        | Bersihkan nama file untuk header
        |--------------------------------------------------------------------------
        */

        $safeFileName = str_replace(
            [
                '"',
                "\r",
                "\n",
            ],
            '',
            $fileName
        );

        /*
        |--------------------------------------------------------------------------
        | Ukuran file
        |--------------------------------------------------------------------------
        */

        $fileSize = filesize(
            $absolutePath
        );

        if ($fileSize === false) {
            $fileSize = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Header INLINE
        |--------------------------------------------------------------------------
        |
        | BAGIAN TERPENTING:
        |
        | Content-Type        : application/pdf
        | Content-Disposition : inline
        |
        | Tidak menggunakan attachment.
        |
        */

        $headers = [
            'Content-Type' => $mimeType,

            'Content-Disposition' =>
            'inline; filename="' .
                $safeFileName .
                '"',

            'Cache-Control' =>
            'private, no-store, no-cache, must-revalidate, max-age=0',

            'Pragma' => 'no-cache',

            'Expires' => '0',

            'Accept-Ranges' => 'bytes',
        ];

        if ($fileSize !== null) {
            $headers['Content-Length'] = (string) $fileSize;
        }

        /*
        |--------------------------------------------------------------------------
        | Stream file langsung
        |--------------------------------------------------------------------------
        */

        return response()->stream(
            function () use ($absolutePath): void {

                $handle = fopen(
                    $absolutePath,
                    'rb'
                );

                if ($handle === false) {
                    return;
                }

                while (!feof($handle)) {
                    echo fread(
                        $handle,
                        1024 * 1024
                    );

                    flush();
                }

                fclose(
                    $handle
                );
            },
            200,
            $headers
        );
    }

    /**
     * Menampilkan halaman edit informasi publik.
     */
    public function edit(int $id): View
    {
        $admin = $this->currentAdmin();

        $kategori = KategoriInformasi::orderBy('nama')
            ->get();

        $dokumentasi = $this->informasiPublikService
            ->getByIdForAdmin(
                $id,
                $admin
            );

        $ppidPembantu = $admin->isAdminUtama()
            ? $this->informasiPublikService
            ->getPpidPembantuList()
            : collect();

        return view(
            'pages.admin.dokumentasi.edit',
            compact(
                'admin',
                'dokumentasi',
                'ppidPembantu',
                'kategori'
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
            $this->validationRules(
                fileRequired: false
            )
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
            ->route(
                'admin.informasi-publik.index'
            )
            ->with(
                'success',
                'Informasi publik berhasil dihapus.'
            );
    }

    /**
     * Mengambil filter.
     */
    private function getFilters(
        Request $request
    ): array {
        return [
            'search' => trim(
                (string) $request->input(
                    'q',
                    $request->input(
                        'search',
                        ''
                    )
                )
            ),

            'status' => $request->input(
                'status'
            ),

            'tahun' => $request->input(
                'tahun'
            ),

            'ppid_pembantuid' =>
            $request->input(
                'ppid_pembantuid'
            ),
        ];
    }

    /**
     * Jumlah data per halaman.
     */
    private function getPerPage(
        Request $request
    ): int {
        $perPage = (int) $request->input(
            'per_page',
            15
        );

        return max(
            5,
            min(
                $perPage,
                100
            )
        );
    }

    /**
     * Validation rules.
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

            'ppid_pembantuid' => [
                'nullable',
                'integer',

                Rule::exists(
                    'ppid_pembantu',
                    'id'
                ),
            ],

            'kategori_id' => [
                'required',
                'integer',

                Rule::exists(
                    'kategori_informasi',
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
     * Admin login.
     */
    private function currentAdmin(): Authorization
    {
        $admin = Auth::guard('admin')
            ->user();

        abort_unless(
            $admin instanceof Authorization,
            401,
            'Sesi admin tidak valid.'
        );

        return $admin;
    }
}
