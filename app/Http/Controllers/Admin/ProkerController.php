<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Models\PpidPembantu;
use App\Models\Proker;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProkerController extends Controller
{
    /**
     * Menampilkan daftar program kerja.
     */
    public function index(Request $request): View
    {
        $admin = $this->currentAdmin();

        $validated = $request->validate([
            'q' => [
                'nullable',
                'string',
                'max:255',
            ],
            'tahun' => [
                'nullable',
                'integer',
                'min:2000',
                'max:2100',
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
        ]);

        $search = trim(
            (string) ($validated['q'] ?? '')
        );

        $tahun = isset($validated['tahun'])
            && $validated['tahun'] !== ''
            ? (int) $validated['tahun']
            : null;

        $ppidPembantuId = isset(
            $validated['ppid_pembantuid']
        ) && $validated['ppid_pembantuid'] !== ''
            ? (int) $validated['ppid_pembantuid']
            : null;

        $perPage = (int) (
            $validated['per_page'] ?? 15
        );

        $proker = $this
            ->queryForAdmin($admin)
            ->when(
                $search !== '',
                function (
                    Builder $query
                ) use ($search): void {
                    $query->where(
                        function (
                            Builder $subQuery
                        ) use ($search): void {
                            $subQuery
                                ->where(
                                    'nama_proker',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'anggaran',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'sumber_dana',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'target',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'pj',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'telp',
                                    'like',
                                    '%' . $search . '%'
                                );
                        }
                    );
                }
            )
            ->when(
                $tahun !== null,
                fn(Builder $query): Builder => $query->whereYear(
                    'jadwal_pelaksanaan',
                    $tahun
                )
            )
            ->when(
                $admin->isAdminUtama()
                    && $ppidPembantuId !== null,
                fn(Builder $query): Builder => $query->where(
                    'ppid_pembantuid',
                    $ppidPembantuId
                )
            )
            ->orderByDesc('jadwal_pelaksanaan')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $ppidPembantuList = $this
            ->getPpidPembantuList($admin);

        return view(
            'pages.admin.proker.index',
            [
                'admin' => $admin,
                'proker' => $proker,
                'ppidPembantuList' => $ppidPembantuList,
            ]
        );
    }

    /**
     * Menampilkan form tambah program kerja.
     */
    public function create(): View
    {
        $admin = $this->currentAdmin();

        $ppidPembantuList = $this
            ->getPpidPembantuList($admin);

        return view(
            'pages.admin.proker.create',
            [
                'admin' => $admin,
                'ppidPembantuList' => $ppidPembantuList,
            ]
        );
    }

    /**
     * Menyimpan program kerja baru.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $admin = $this->currentAdmin();

        $validated = $this->validateRequest(
            $request,
            $admin
        );

        $ppidPembantuId = $this
            ->resolvePpidPembantuId(
                $admin,
                $validated
            );

        $dokumen = $this->storeDocument(
            $request,
            $validated['dokumen_url'] ?? null
        );

        $proker = Proker::query()->create([
            'nama_proker' => trim(
                $validated['nama_proker']
            ),

            'anggaran' => trim(
                $validated['anggaran']
            ),

            'sumber_dana' => trim(
                $validated['sumber_dana']
            ),

            'target' => trim(
                $validated['target']
            ),

            'jadwal_pelaksanaan' =>
            $validated['jadwal_pelaksanaan'],

            'pj' => trim(
                $validated['pj']
            ),

            'telp' => $this->nullableString(
                $validated['telp'] ?? null
            ),

            'dokumen' => $dokumen,

            'slug' => $this->generateUniqueSlug(
                $validated['nama_proker']
            ),

            'ppid_pembantuid' => $ppidPembantuId,
        ]);

        return redirect()
            ->route(
                'admin.proker.show',
                [
                    'id' => $proker->id,
                ]
            )
            ->with(
                'success',
                'Program kerja berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail program kerja.
     */
    public function show(int $id): View
    {
        $admin = $this->currentAdmin();

        $proker = $this->findForAdmin(
            $admin,
            $id
        );

        return view(
            'pages.admin.proker.show',
            [
                'admin' => $admin,
                'proker' => $proker,
            ]
        );
    }

    /**
     * Menampilkan form edit program kerja.
     */
    public function edit(int $id): View
    {
        $admin = $this->currentAdmin();

        $proker = $this->findForAdmin(
            $admin,
            $id
        );

        $ppidPembantuList = $this
            ->getPpidPembantuList($admin);

        return view(
            'pages.admin.proker.edit',
            [
                'admin' => $admin,
                'proker' => $proker,
                'ppidPembantuList' => $ppidPembantuList,
            ]
        );
    }

    /**
     * Memperbarui program kerja.
     */
    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this->currentAdmin();

        $proker = $this->findForAdmin(
            $admin,
            $id
        );

        $validated = $this->validateRequest(
            $request,
            $admin
        );

        $ppidPembantuId = $this
            ->resolvePpidPembantuId(
                $admin,
                $validated
            );

        $dokumen = $this->resolveUpdatedDocument(
            $request,
            $proker,
            $validated['dokumen_url'] ?? null
        );

        $proker->update([
            'nama_proker' => trim(
                $validated['nama_proker']
            ),

            'anggaran' => trim(
                $validated['anggaran']
            ),

            'sumber_dana' => trim(
                $validated['sumber_dana']
            ),

            'target' => trim(
                $validated['target']
            ),

            'jadwal_pelaksanaan' =>
            $validated['jadwal_pelaksanaan'],

            'pj' => trim(
                $validated['pj']
            ),

            'telp' => $this->nullableString(
                $validated['telp'] ?? null
            ),

            'dokumen' => $dokumen,

            'slug' => $this->generateUniqueSlug(
                $validated['nama_proker'],
                $proker->id
            ),

            'ppid_pembantuid' => $ppidPembantuId,
        ]);

        return redirect()
            ->route(
                'admin.proker.show',
                [
                    'id' => $proker->id,
                ]
            )
            ->with(
                'success',
                'Program kerja berhasil diperbarui.'
            );
    }

    /**
     * Menghapus program kerja.
     */
    public function destroy(
        int $id
    ): RedirectResponse {
        $admin = $this->currentAdmin();

        $proker = $this->findForAdmin(
            $admin,
            $id
        );

        $this->deleteLocalDocument(
            $proker->dokumen
        );

        $proker->delete();

        return redirect()
            ->route('admin.proker.index')
            ->with(
                'success',
                'Program kerja berhasil dihapus.'
            );
    }

    /**
     * Membuka URL eksternal atau mengunduh dokumen lokal.
     */
    public function dokumen(
        int $id
    ): RedirectResponse|StreamedResponse {
        $admin = $this->currentAdmin();

        $proker = $this->findForAdmin(
            $admin,
            $id
        );

        abort_if(
            empty($proker->dokumen),
            404,
            'Dokumen program kerja tidak tersedia.'
        );

        /*
         * Jika dokumen berupa URL eksternal,
         * arahkan pengguna ke alamat tersebut.
         */
        if ($proker->isDokumenEksternal()) {
            return redirect()->away(
                (string) $proker->dokumen
            );
        }

        /*
         * Tipe eksplisit FilesystemAdapter diperlukan
         * agar method download dikenali IDE.
         *
         * @var FilesystemAdapter $disk
         */
        $disk = Storage::disk('public');

        abort_unless(
            $disk->exists(
                (string) $proker->dokumen
            ),
            404,
            'File dokumen tidak ditemukan.'
        );

        $extension = pathinfo(
            (string) $proker->dokumen,
            PATHINFO_EXTENSION
        );

        $downloadName = Str::slug(
            (string) $proker->nama_proker
        );

        if ($downloadName === '') {
            $downloadName = 'dokumen-program-kerja';
        }

        if ($extension !== '') {
            $downloadName .= '.'
                . strtolower($extension);
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->download(
            (string) $proker->dokumen,
            $downloadName
        );
    }

    /**
     * Validasi tambah dan edit program kerja.
     *
     * @return array<string, mixed>
     */
    private function validateRequest(
        Request $request,
        Authorization $admin
    ): array {
        return $request->validate(
            [
                'nama_proker' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'anggaran' => [
                    'required',
                    'string',
                    'max:2000',
                ],

                'sumber_dana' => [
                    'required',
                    'string',
                    'max:2000',
                ],

                'target' => [
                    'required',
                    'string',
                    'max:10000',
                ],

                'jadwal_pelaksanaan' => [
                    'required',
                    'date',
                ],

                'pj' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'telp' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'dokumen_url' => [
                    'nullable',
                    'url',
                    'max:2048',
                ],

                'dokumen_file' => [
                    'nullable',
                    'file',
                    'mimes:pdf,doc,docx,xls,xlsx',
                    'max:10240',
                ],

                'hapus_dokumen' => [
                    'nullable',
                    'boolean',
                ],

                'ppid_pembantuid' => [
                    Rule::requiredIf(
                        $admin->isAdminUtama()
                    ),
                    'nullable',
                    'integer',
                    Rule::exists(
                        'ppid_pembantu',
                        'id'
                    ),
                ],
            ],
            [
                'nama_proker.required' =>
                'Nama program kerja wajib diisi.',

                'nama_proker.max' =>
                'Nama program kerja maksimal 255 karakter.',

                'anggaran.required' =>
                'Anggaran wajib diisi.',

                'anggaran.max' =>
                'Anggaran maksimal 2.000 karakter.',

                'sumber_dana.required' =>
                'Sumber dana wajib diisi.',

                'sumber_dana.max' =>
                'Sumber dana maksimal 2.000 karakter.',

                'target.required' =>
                'Target program kerja wajib diisi.',

                'target.max' =>
                'Target program maksimal 10.000 karakter.',

                'jadwal_pelaksanaan.required' =>
                'Jadwal pelaksanaan wajib diisi.',

                'jadwal_pelaksanaan.date' =>
                'Format jadwal pelaksanaan tidak valid.',

                'pj.required' =>
                'Penanggung jawab wajib diisi.',

                'pj.max' =>
                'Penanggung jawab maksimal 255 karakter.',

                'telp.max' =>
                'Nomor telepon maksimal 50 karakter.',

                'dokumen_url.url' =>
                'Alamat dokumen harus berupa URL yang valid.',

                'dokumen_url.max' =>
                'Alamat dokumen maksimal 2.048 karakter.',

                'dokumen_file.file' =>
                'Dokumen yang dipilih tidak valid.',

                'dokumen_file.mimes' =>
                'Dokumen harus berupa PDF, DOC, DOCX, XLS, atau XLSX.',

                'dokumen_file.max' =>
                'Ukuran dokumen maksimal 10 MB.',

                'ppid_pembantuid.required' =>
                'PPID Pembantu wajib dipilih.',

                'ppid_pembantuid.integer' =>
                'PPID Pembantu tidak valid.',

                'ppid_pembantuid.exists' =>
                'PPID Pembantu yang dipilih tidak ditemukan.',
            ]
        );
    }

    /**
     * Mengambil akun admin yang sedang login.
     */
    private function currentAdmin(): Authorization
    {
        $admin = Auth::guard('admin')->user();

        abort_unless(
            $admin instanceof Authorization,
            401,
            'Sesi admin tidak ditemukan.'
        );

        return $admin;
    }

    /**
     * Query program kerja sesuai kewenangan admin.
     */
    private function queryForAdmin(
        Authorization $admin
    ): Builder {
        $query = Proker::query()
            ->with([
                'ppidPembantu',
            ]);

        if ($admin->isAdminPembantu()) {
            $query->where(
                'ppid_pembantuid',
                $admin->ppid_pembantuid
            );
        }

        return $query;
    }

    /**
     * Mengambil satu program kerja sesuai kewenangan admin.
     */
    private function findForAdmin(
        Authorization $admin,
        int $id
    ): Proker {
        return $this
            ->queryForAdmin($admin)
            ->findOrFail($id);
    }

    /**
     * Mengambil daftar PPID Pembantu.
     */
    private function getPpidPembantuList(
        Authorization $admin
    ): Collection {
        $query = PpidPembantu::query()
            ->select([
                'id',
                'nama',
            ])
            ->orderBy('nama');

        if ($admin->isAdminPembantu()) {
            $query->where(
                'id',
                $admin->ppid_pembantuid
            );
        }

        return $query->get();
    }

    /**
     * Menentukan unit PPID untuk program kerja.
     *
     * @param array<string, mixed> $validated
     */
    private function resolvePpidPembantuId(
        Authorization $admin,
        array $validated
    ): int {
        if ($admin->isAdminPembantu()) {
            abort_if(
                empty($admin->ppid_pembantuid),
                422,
                'Akun Admin Pembantu belum terhubung dengan unit PPID Pembantu.'
            );

            return (int) $admin->ppid_pembantuid;
        }

        abort_if(
            empty($validated['ppid_pembantuid']),
            422,
            'PPID Pembantu wajib dipilih.'
        );

        return (int) $validated['ppid_pembantuid'];
    }

    /**
     * Membuat slug unik.
     */
    private function generateUniqueSlug(
        string $namaProker,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug(
            $namaProker
        );

        if ($baseSlug === '') {
            $baseSlug = 'program-kerja';
        }

        $slug = $baseSlug;
        $number = 2;

        while (
            Proker::query()
            ->where('slug', $slug)
            ->when(
                $ignoreId !== null,
                fn(Builder $query): Builder => $query->where(
                    'id',
                    '!=',
                    $ignoreId
                )
            )
            ->exists()
        ) {
            $slug = $baseSlug
                . '-'
                . $number;

            $number++;
        }

        return $slug;
    }

    /**
     * Menyimpan dokumen baru.
     */
    private function storeDocument(
        Request $request,
        ?string $documentUrl
    ): ?string {
        if ($request->hasFile('dokumen_file')) {
            $storedPath = $request
                ->file('dokumen_file')
                ->store(
                    'proker/dokumen',
                    'public'
                );

            return is_string($storedPath)
                ? $storedPath
                : null;
        }

        return $this->nullableString(
            $documentUrl
        );
    }

    /**
     * Menentukan dokumen saat program kerja diperbarui.
     */
    private function resolveUpdatedDocument(
        Request $request,
        Proker $proker,
        ?string $documentUrl
    ): ?string {
        $dokumen = $proker->dokumen;

        if ($request->boolean('hapus_dokumen')) {
            $this->deleteLocalDocument(
                $dokumen
            );

            $dokumen = null;
        }

        $documentUrl = $this->nullableString(
            $documentUrl
        );

        if ($documentUrl !== null) {
            $this->deleteLocalDocument(
                $dokumen
            );

            $dokumen = $documentUrl;
        }

        if ($request->hasFile('dokumen_file')) {
            $this->deleteLocalDocument(
                $dokumen
            );

            $storedPath = $request
                ->file('dokumen_file')
                ->store(
                    'proker/dokumen',
                    'public'
                );

            $dokumen = is_string($storedPath)
                ? $storedPath
                : null;
        }

        return $dokumen;
    }

    /**
     * Menghapus dokumen lokal.
     */
    private function deleteLocalDocument(
        ?string $dokumen
    ): void {
        if (
            empty($dokumen)
            || filter_var(
                $dokumen,
                FILTER_VALIDATE_URL
            ) !== false
        ) {
            return;
        }

        /*
         * Beri tipe eksplisit agar method delete dikenali IDE.
         *
         * @var FilesystemAdapter $disk
         */
        $disk = Storage::disk('public');

        if ($disk->exists($dokumen)) {
            $disk->delete($dokumen);
        }
    }

    /**
     * Mengubah string kosong menjadi null.
     */
    private function nullableString(
        mixed $value
    ): ?string {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value !== ''
            ? $value
            : null;
    }
}
