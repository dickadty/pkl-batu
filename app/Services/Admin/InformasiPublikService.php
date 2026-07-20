<?php

namespace App\Services\Admin;

use App\Models\Authorization;
use App\Models\Dokumentasi;
use App\Models\PpidPembantu;
use App\Notifications\NotifikasiSistem;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
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
        $perPage = max(
            5,
            min($perPage, 100)
        );

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
        $limit = max(
            1,
            min($limit, 20)
        );

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

        $storedPath = $this->storeDokumentasiFile(
            $file
        );

        try {
            $dokumentasi = DB::transaction(
                function () use (
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
                }
            );
        } catch (Throwable $exception) {
            $this->deleteFile($storedPath);

            throw $exception;
        }

        $dokumentasi->load([
            'ppidPembantu:id,nama',
        ]);

        $this->kirimNotifikasiSetelahDibuat(
            $dokumentasi,
            $admin
        );

        return $dokumentasi;
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
        $dokumentasi = $this->getByIdForAdmin(
            $id,
            $admin
        );

        unset(
            $data['file'],
            $data['slug'],
            $data['tanggal'],
            $data['is_verifikasi']
        );

        $oldFilePath = $dokumentasi->file;
        $newFilePath = null;

        if ($file instanceof UploadedFile) {
            $newFilePath = $this->storeDokumentasiFile(
                $file
            );
        }

        try {
            $updatedDokumentasi = DB::transaction(
                function () use (
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
                        ->load([
                            'ppidPembantu:id,nama',
                        ]);
                }
            );
        } catch (Throwable $exception) {
            if ($newFilePath) {
                $this->deleteFile($newFilePath);
            }

            throw $exception;
        }

        if (
            $newFilePath &&
            $oldFilePath !== $newFilePath
        ) {
            $this->deleteFile($oldFilePath);
        }

        $this->kirimNotifikasiSetelahDiperbarui(
            $updatedDokumentasi,
            $admin
        );

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
            ->with([
                'ppidPembantu:id,nama',
            ])
            ->findOrFail($id);

        $sudahDiverifikasi =
            (int) $dokumentasi->is_verifikasi === 1;

        if (! $sudahDiverifikasi) {
            $dokumentasi->update([
                'is_verifikasi' => 1,
            ]);

            $dokumentasi = $dokumentasi
                ->refresh()
                ->load([
                    'ppidPembantu:id,nama',
                ]);

            $this->kirimNotifikasiKePpidPembantu(
                dokumentasi: $dokumentasi,
                judul: 'Informasi Publik Diverifikasi',
                pesan: sprintf(
                    'Informasi publik "%s" telah diverifikasi oleh Admin Utama dan sekarang dapat ditampilkan kepada masyarakat.',
                    $this->namaDokumentasi($dokumentasi)
                ),
                jenis: 'informasi_publik_diverifikasi',
                icon: 'ri-checkbox-circle-line',
                actor: $admin
            );
        }

        return $dokumentasi;
    }

    /**
     * Menghapus informasi publik.
     */
    public function delete(
        int $id,
        Authorization $admin
    ): void {
        $dokumentasi = $this->getByIdForAdmin(
            $id,
            $admin
        );

        $filePath = $dokumentasi->file;

        DB::transaction(
            function () use ($dokumentasi): void {
                $dokumentasi->delete();
            }
        );

        $this->deleteFile($filePath);
    }

    /**
     * Mengirim notifikasi setelah informasi dibuat.
     */
    private function kirimNotifikasiSetelahDibuat(
        Dokumentasi $dokumentasi,
        Authorization $admin
    ): void {
        if ($this->isAdminPembantu($admin)) {
            $this->kirimNotifikasiKeAdminUtama(
                dokumentasi: $dokumentasi,
                judul: 'Informasi Publik Menunggu Verifikasi',
                pesan: sprintf(
                    '%s menambahkan informasi publik "%s". Informasi tersebut menunggu verifikasi Admin Utama.',
                    $this->namaPpidPembantu($dokumentasi),
                    $this->namaDokumentasi($dokumentasi)
                ),
                jenis: 'informasi_publik_baru',
                icon: 'ri-file-add-line',
                actor: $admin
            );

            return;
        }

        if (
            $this->isAdminUtama($admin) &&
            $dokumentasi->ppid_pembantuid
        ) {
            $this->kirimNotifikasiKePpidPembantu(
                dokumentasi: $dokumentasi,
                judul: 'Informasi Publik Baru Diterbitkan',
                pesan: sprintf(
                    'Admin Utama menerbitkan informasi publik "%s" untuk %s.',
                    $this->namaDokumentasi($dokumentasi),
                    $this->namaPpidPembantu($dokumentasi)
                ),
                jenis: 'informasi_publik_diterbitkan',
                icon: 'ri-file-check-line',
                actor: $admin
            );
        }
    }

    /**
     * Mengirim notifikasi setelah informasi diperbarui.
     */
    private function kirimNotifikasiSetelahDiperbarui(
        Dokumentasi $dokumentasi,
        Authorization $admin
    ): void {
        if (! $this->isAdminPembantu($admin)) {
            return;
        }

        $this->kirimNotifikasiKeAdminUtama(
            dokumentasi: $dokumentasi,
            judul: 'Informasi Publik Diperbarui',
            pesan: sprintf(
                '%s memperbarui informasi publik "%s". Informasi tersebut harus diverifikasi ulang.',
                $this->namaPpidPembantu($dokumentasi),
                $this->namaDokumentasi($dokumentasi)
            ),
            jenis: 'informasi_publik_diperbarui',
            icon: 'ri-file-edit-line',
            actor: $admin
        );
    }

    /**
     * Mengirim notifikasi kepada seluruh Admin Utama.
     */
    private function kirimNotifikasiKeAdminUtama(
        Dokumentasi $dokumentasi,
        string $judul,
        string $pesan,
        string $jenis,
        string $icon,
        Authorization $actor
    ): void {
        $penerima = Authorization::query()
            ->where(
                'role',
                self::ROLE_ADMIN_UTAMA
            )
            ->get();

        if ($penerima->isEmpty()) {
            return;
        }

        Notification::send(
            $penerima,
            new NotifikasiSistem(
                judul: $judul,
                pesan: $pesan,
                jenis: $jenis,
                routeName: 'admin.informasi-publik.show',
                routeParams: [
                    'id' => $dokumentasi->id,
                ],
                icon: $icon,
                metadata: $this->metadataNotifikasi(
                    dokumentasi: $dokumentasi,
                    actor: $actor,
                    jenis: $jenis
                )
            )
        );
    }

    /**
     * Mengirim notifikasi kepada akun PPID Pembantu terkait.
     */
    private function kirimNotifikasiKePpidPembantu(
        Dokumentasi $dokumentasi,
        string $judul,
        string $pesan,
        string $jenis,
        string $icon,
        Authorization $actor
    ): void {
        if (! $dokumentasi->ppid_pembantuid) {
            return;
        }

        $penerima = Authorization::query()
            ->where(
                'role',
                self::ROLE_ADMIN_PEMBANTU
            )
            ->where(
                'ppid_pembantuid',
                $dokumentasi->ppid_pembantuid
            )
            ->where(
                'id',
                '!=',
                $actor->id
            )
            ->get();

        if ($penerima->isEmpty()) {
            return;
        }

        Notification::send(
            $penerima,
            new NotifikasiSistem(
                judul: $judul,
                pesan: $pesan,
                jenis: $jenis,
                routeName: 'admin.informasi-publik.show',
                routeParams: [
                    'id' => $dokumentasi->id,
                ],
                icon: $icon,
                metadata: $this->metadataNotifikasi(
                    dokumentasi: $dokumentasi,
                    actor: $actor,
                    jenis: $jenis
                )
            )
        );
    }

    /**
     * Metadata tambahan pada notifikasi.
     */
    private function metadataNotifikasi(
        Dokumentasi $dokumentasi,
        Authorization $actor,
        string $jenis
    ): array {
        return [
            'dokumentasi_id' => $dokumentasi->id,
            'nama' => $dokumentasi->nama,
            'slug' => $dokumentasi->slug,
            'tahun' => $dokumentasi->tahun,
            'sifat' => $dokumentasi->sifat,
            'is_verifikasi' => (int) $dokumentasi->is_verifikasi,
            'ppid_pembantuid' => $dokumentasi->ppid_pembantuid,
            'ppid_pembantu' => $this->namaPpidPembantu(
                $dokumentasi
            ),
            'actor_id' => $actor->id,
            'actor_username' => $actor->username,
            'actor_role' => (int) $actor->role,
            'jenis_aktivitas' => $jenis,
            'dikirim_pada' => now()->toDateTimeString(),
        ];
    }

    /**
     * Query yang sudah dibatasi berdasarkan hak akses admin.
     */
    private function queryForAdmin(
        Authorization $admin
    ): Builder {
        $this->ensureValidAdminRole($admin);

        $query = $this->dokumentasi
            ->newQuery();

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
            $query->where(
                function (Builder $subQuery) use (
                    $search
                ): void {
                    $subQuery
                        ->where(
                            'nama',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'ringkasan',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhereHas(
                            'ppidPembantu',
                            function (
                                Builder $relationQuery
                            ) use ($search): void {
                                $relationQuery->where(
                                    'nama',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        );
                }
            );
        }

        $status = $filters['status'] ?? null;

        if (
            in_array(
                $status,
                [
                    'verified',
                    'terverifikasi',
                    '1',
                ],
                true
            )
        ) {
            $query->where(
                'is_verifikasi',
                1
            );
        }

        if (
            in_array(
                $status,
                [
                    'pending',
                    'menunggu',
                    '0',
                ],
                true
            )
        ) {
            $query->where(
                'is_verifikasi',
                0
            );
        }

        $sifat = trim(
            (string) ($filters['sifat'] ?? '')
        );

        if ($sifat !== '') {
            $query->where(
                'sifat',
                $sifat
            );
        }

        $tahun = $filters['tahun'] ?? null;

        if (is_numeric($tahun)) {
            $query->where(
                'tahun',
                (int) $tahun
            );
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
            if (! $admin->ppid_pembantuid) {
                throw new AuthorizationException(
                    'Admin tidak memiliki PPID Pembantu.'
                );
            }

            $data['ppid_pembantuid'] =
                $admin->ppid_pembantuid;

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
            $slug = $baseSlug
                . '-'
                . Str::lower(
                    Str::random(8)
                );

            $exists = $this->dokumentasi
                ->newQuery()
                ->where(
                    'slug',
                    $slug
                )
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

        $disk = $this->storage->disk(
            'public'
        );

        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }

    /**
     * Mengambil nama informasi publik.
     */
    private function namaDokumentasi(
        Dokumentasi $dokumentasi
    ): string {
        $nama = trim(
            (string) $dokumentasi->nama
        );

        return $nama !== ''
            ? $nama
            : 'Informasi Publik #' . $dokumentasi->id;
    }

    /**
     * Mengambil nama PPID Pembantu.
     */
    private function namaPpidPembantu(
        Dokumentasi $dokumentasi
    ): string {
        $nama = trim(
            (string) data_get(
                $dokumentasi,
                'ppidPembantu.nama',
                ''
            )
        );

        return $nama !== ''
            ? $nama
            : 'PPID Pembantu';
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
