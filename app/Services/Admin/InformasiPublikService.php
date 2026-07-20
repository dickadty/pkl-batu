<?php

namespace App\Services\Admin;

use App\Models\Authorization;
use App\Models\Dokumentasi;
use App\Models\PpidPembantu;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class InformasiPublikService
{
    private const ROLE_ADMIN_UTAMA = 1;

    private const ROLE_ADMIN_PEMBANTU = 2;

    public function __construct(
        protected Dokumentasi $dokumentasi,
        protected PpidPembantu $ppidPembantu,
        protected FilesystemFactory $storage
    ) {}

    /**
     * Mengambil daftar informasi publik untuk halaman index admin.
     */
    public function getForAdmin(
        Authorization $admin,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        $perPage = max(5, min($perPage, 100));

        return $this->applyFilters(
            $this->queryForAdmin($admin)
                ->with([
                    'ppidPembantu:id,nama',
                ]),
            $filters
        )
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Mengambil beberapa data terbaru untuk dashboard.
     */
    public function getLatestForDashboard(
        Authorization $admin,
        array $filters = [],
        int $limit = 5
    ): Collection {
        $limit = max(1, min($limit, 20));

        return $this->applyFilters(
            $this->queryForAdmin($admin)
                ->with([
                    'ppidPembantu:id,nama',
                ]),
            $filters
        )
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    /**
     * Mengambil satu dokumentasi sesuai hak akses admin.
     */
    public function getByIdForAdmin(
        int $id,
        Authorization $admin
    ): Dokumentasi {
        return $this->queryForAdmin($admin)
            ->with([
                'ppidPembantu:id,nama',
            ])
            ->findOrFail($id);
    }

    /**
     * Mengambil daftar PPID Pembantu.
     */
    public function getPpidPembantuList(): Collection
    {
        return $this->ppidPembantu
            ->newQuery()
            ->select([
                'id',
                'nama',
            ])
            ->orderBy('nama')
            ->get();
    }

    /**
     * Menyimpan informasi publik.
     */
    public function create(
        array $data,
        UploadedFile $file,
        Authorization $admin
    ): Dokumentasi {
        $this->ensureValidAdminRole($admin);

        unset(
            $data['file'],
            $data['slug'],
            $data['tanggal'],
            $data['is_verifikasi']
        );

        $storedPath = $this->storeDokumentasiFile($file);

        try {
            return DB::transaction(function () use (
                $data,
                $storedPath,
                $admin
            ): Dokumentasi {
                $data = $this->applyAdminOwnership(
                    $data,
                    $admin,
                    true
                );

                $data['file'] = $storedPath;
                $data['tanggal'] = now()->timestamp;
                $data['slug'] = $this->generateUniqueSlug(
                    $data['nama']
                );

                return $this->dokumentasi
                    ->newQuery()
                    ->create($data);
            });
        } catch (Throwable $exception) {
            $this->deleteFile($storedPath);

            throw $exception;
        }
    }

    /**
     * Memperbarui informasi publik.
     */
    public function update(
        int $id,
        array $data,
        ?UploadedFile $file,
        Authorization $admin
    ): Dokumentasi {
        $dokumentasi = $this->getByIdForAdmin($id, $admin);

        unset(
            $data['file'],
            $data['slug'],
            $data['tanggal'],
            $data['is_verifikasi']
        );

        $oldFilePath = $dokumentasi->file;
        $newFilePath = null;

        if ($file instanceof UploadedFile) {
            $newFilePath = $this->storeDokumentasiFile($file);
        }

        try {
            $updatedDokumentasi = DB::transaction(function () use (
                $data,
                $admin,
                $dokumentasi,
                $newFilePath
            ): Dokumentasi {
                $data = $this->applyAdminOwnership(
                    $data,
                    $admin,
                    false
                );

                $data['slug'] = $this->generateUniqueSlug(
                    $data['nama'],
                    $dokumentasi->id
                );

                if ($newFilePath) {
                    $data['file'] = $newFilePath;
                }

                $dokumentasi->update($data);

                return $dokumentasi
                    ->refresh()
                    ->load('ppidPembantu');
            });
        } catch (Throwable $exception) {
            if ($newFilePath) {
                $this->deleteFile($newFilePath);
            }

            throw $exception;
        }

        if ($newFilePath && $oldFilePath !== $newFilePath) {
            $this->deleteFile($oldFilePath);
        }

        return $updatedDokumentasi;
    }

    /**
     * Memverifikasi informasi publik.
     */
    public function verify(
        int $id,
        Authorization $admin
    ): Dokumentasi {
        $this->ensureAdminUtama($admin);

        $dokumentasi = $this->queryForAdmin($admin)
            ->findOrFail($id);

        $dokumentasi->update([
            'is_verifikasi' => 1,
        ]);

        return $dokumentasi->refresh();
    }

    /**
     * Menghapus informasi publik.
     */
    public function delete(
        int $id,
        Authorization $admin
    ): void {
        $dokumentasi = $this->getByIdForAdmin($id, $admin);
        $filePath = $dokumentasi->file;

        DB::transaction(function () use ($dokumentasi): void {
            $dokumentasi->delete();
        });

        /*
         * File dihapus setelah database berhasil dihapus.
         * Jika penghapusan file gagal, database tetap konsisten.
         */
        $this->deleteFile($filePath);
    }

    /**
     * Query yang sudah dibatasi berdasarkan hak akses admin.
     */
    private function queryForAdmin(
        Authorization $admin
    ): Builder {
        $this->ensureValidAdminRole($admin);

        $query = $this->dokumentasi->newQuery();

        if ($this->isAdminPembantu($admin)) {
            if (! $admin->ppid_pembantuid) {
                throw new AuthorizationException(
                    'Admin tidak memiliki PPID Pembantu.'
                );
            }

            $query->where(
                'ppid_pembantuid',
                $admin->ppid_pembantuid
            );
        }

        return $query;
    }

    /**
     * Menerapkan filter pencarian.
     */
    private function applyFilters(
        Builder $query,
        array $filters
    ): Builder {
        $search = trim(
            (string) ($filters['search'] ?? '')
        );

        if ($search !== '') {
            $query->where(function (Builder $subQuery) use (
                $search
            ): void {
                $subQuery
                    ->where('nama', 'like', "%{$search}%")
                    ->orWhere('ringkasan', 'like', "%{$search}%")
                    ->orWhereHas(
                        'ppidPembantu',
                        function (Builder $relationQuery) use (
                            $search
                        ): void {
                            $relationQuery->where(
                                'nama',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
            });
        }

        $status = $filters['status'] ?? null;

        if (
            in_array(
                $status,
                ['verified', 'terverifikasi', '1'],
                true
            )
        ) {
            $query->where('is_verifikasi', 1);
        }

        if (
            in_array(
                $status,
                ['pending', 'menunggu', '0'],
                true
            )
        ) {
            $query->where('is_verifikasi', 0);
        }

        $sifat = trim(
            (string) ($filters['sifat'] ?? '')
        );

        if ($sifat !== '') {
            $query->where('sifat', $sifat);
        }

        $tahun = $filters['tahun'] ?? null;

        if (is_numeric($tahun)) {
            $query->where('tahun', (int) $tahun);
        }

        $ppidPembantuId =
            $filters['ppid_pembantuid'] ?? null;

        if (is_numeric($ppidPembantuId)) {
            $query->where(
                'ppid_pembantuid',
                (int) $ppidPembantuId
            );
        }

        return $query;
    }

    /**
     * Mengatur kepemilikan dan status berdasarkan peran admin.
     */
    private function applyAdminOwnership(
        array $data,
        Authorization $admin,
        bool $isCreating
    ): array {
        if ($this->isAdminPembantu($admin)) {
            $data['ppid_pembantuid'] =
                $admin->ppid_pembantuid;

            /*
             * Informasi yang dibuat atau diubah admin pembantu
             * harus diverifikasi ulang.
             */
            $data['is_verifikasi'] = 0;

            return $data;
        }

        if ($isCreating) {
            $data['is_verifikasi'] = 1;
        }

        return $data;
    }

    /**
     * Membuat slug unik.
     */
    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'informasi-publik';
        }

        do {
            $slug = $baseSlug . '-' . Str::lower(
                Str::random(8)
            );

            $exists = $this->dokumentasi
                ->newQuery()
                ->where('slug', $slug)
                ->when(
                    $ignoreId !== null,
                    fn(Builder $query) => $query->where(
                        'id',
                        '!=',
                        $ignoreId
                    )
                )
                ->exists();
        } while ($exists);

        return $slug;
    }

    /**
     * Menyimpan file dengan nama unik otomatis.
     */
    private function storeDokumentasiFile(
        UploadedFile $file
    ): string {
        $path = $file->store(
            'dokumentasi',
            'public'
        );

        if (! $path) {
            throw new RuntimeException(
                'File dokumentasi gagal disimpan.'
            );
        }

        return $path;
    }

    /**
     * Menghapus file dari disk public.
     */
    private function deleteFile(
        ?string $path
    ): void {
        if (! $path) {
            return;
        }

        $disk = $this->storage->disk('public');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }

    private function ensureAdminUtama(
        Authorization $admin
    ): void {
        if (! $this->isAdminUtama($admin)) {
            throw new AuthorizationException(
                'Akses hanya diperbolehkan untuk Admin PPID Utama.'
            );
        }
    }

    private function ensureValidAdminRole(
        Authorization $admin
    ): void {
        if (
            ! $this->isAdminUtama($admin) &&
            ! $this->isAdminPembantu($admin)
        ) {
            throw new AuthorizationException(
                'Peran admin tidak valid.'
            );
        }
    }

    private function isAdminUtama(
        Authorization $admin
    ): bool {
        return (int) $admin->role ===
            self::ROLE_ADMIN_UTAMA;
    }

    private function isAdminPembantu(
        Authorization $admin
    ): bool {
        return (int) $admin->role ===
            self::ROLE_ADMIN_PEMBANTU;
    }
}
