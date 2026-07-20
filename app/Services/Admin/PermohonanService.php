<?php

namespace App\Services\Admin;

use App\Models\Authorization;
use App\Models\Permohonan;
use App\Models\PpidPembantu;
use App\Notifications\NotifikasiSistem;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;

class PermohonanService
{
    private const ROLE_ADMIN_UTAMA = 1;

    private const ROLE_ADMIN_PEMBANTU = 2;

    public function __construct(
        protected Permohonan $permohonan,
        protected PpidPembantu $ppidPembantu,
        protected FilesystemFactory $storage
    ) {}

    public function getForAdmin(
        Authorization $admin
    ): Collection {
        return $this->permohonan
            ->newQuery()
            ->with([
                'userPublic',
                'ppidPembantu',
            ])
            ->when(
                $this->isAdminPembantu($admin),
                function ($query) use ($admin): void {
                    $query->where(
                        'ppid_pembantuid',
                        $admin->ppid_pembantuid
                    );
                }
            )
            ->orderByDesc('id')
            ->get();
    }

    public function getDetailForAdmin(
        int $id,
        Authorization $admin
    ): Permohonan {
        $permohonan = $this->permohonan
            ->newQuery()
            ->with([
                'userPublic',
                'ppidPembantu',
                'admin',
            ])
            ->findOrFail($id);

        $this->ensureAdminCanAccessPermohonan(
            $admin,
            $permohonan
        );

        return $permohonan;
    }

    public function getPpidPembantuList(): Collection
    {
        return $this->ppidPembantu
            ->newQuery()
            ->orderBy('nama')
            ->get();
    }

    public function teruskan(
        int $id,
        Authorization $admin,
        array $data
    ): Permohonan {
        $this->ensureAdminUtama(
            $admin,
            'Hanya admin utama yang dapat meneruskan permohonan.'
        );

        $permohonan = $this->permohonan
            ->newQuery()
            ->findOrFail($id);

        $permohonan->update([
            'ppid_pembantuid' =>
            $data['ppid_pembantuid'],

            'catatan_utama' =>
            $data['catatan_utama']
                ?? null,

            'tanggal_diteruskan' =>
            now()->toDateString(),

            'status' =>
            'Diteruskan ke PPID Pembantu',
        ]);

        $permohonan = $permohonan
            ->refresh()
            ->load([
                'userPublic',
                'ppidPembantu',
            ]);

        $this->kirimNotifikasiKePpidPembantu(
            permohonan: $permohonan,

            judul: 'Permohonan Informasi Baru',

            pesan: sprintf(
                'Permohonan %s telah diteruskan kepada %s dan perlu segera ditindaklanjuti.',
                $this->nomorPermohonan(
                    $permohonan
                ),
                $this->namaPpidPembantu(
                    $permohonan
                )
            ),

            jenis: 'permohonan_diteruskan',

            icon: 'ri-send-plane-line',

            actor: $admin
        );

        return $permohonan;
    }

    public function jawabPembantu(
        int $id,
        Authorization $admin,
        array $data,
        ?UploadedFile $filePembantu = null
    ): Permohonan {
        $this->ensureAdminPembantu(
            $admin,
            'Hanya admin pembantu yang dapat memberi laporan.'
        );

        $permohonan = $this->permohonan
            ->newQuery()
            ->findOrFail($id);

        $this->ensurePermohonanBelongsToAdminPembantu(
            $admin,
            $permohonan
        );

        if (
            $filePembantu
            instanceof UploadedFile
        ) {
            $this->deleteFile(
                $permohonan
                    ->file_pembantu
            );

            $data['file_pembantu'] =
                $this
                ->storeLaporanPermohonanFile(
                    $filePembantu
                );
        }

        $permohonan->update([
            'jawaban_pembantu' =>
            $data['jawaban_pembantu'],

            'file_pembantu' =>
            $data['file_pembantu']
                ?? $permohonan
                ->file_pembantu,

            'tanggal_jawab_pembantu' =>
            now()->toDateString(),

            'status' =>
            'Menunggu Validasi Admin Utama',
        ]);

        $permohonan = $permohonan
            ->refresh()
            ->load([
                'userPublic',
                'ppidPembantu',
            ]);

        $this->kirimNotifikasiKeAdminUtama(
            permohonan: $permohonan,

            judul: 'Jawaban PPID Pembantu Diterima',

            pesan: sprintf(
                '%s telah mengirimkan jawaban untuk permohonan %s. Jawaban menunggu validasi Admin Utama.',
                $this->namaPpidPembantu(
                    $permohonan
                ),
                $this->nomorPermohonan(
                    $permohonan
                )
            ),

            jenis: 'jawaban_ppid_pembantu',

            icon: 'ri-file-check-line',

            actor: $admin
        );

        return $permohonan;
    }

    public function validasi(
        int $id,
        Authorization $admin,
        array $data
    ): Permohonan {
        $this->ensureAdminUtama(
            $admin,
            'Hanya admin utama yang dapat melakukan validasi.'
        );

        $permohonan = $this->permohonan
            ->newQuery()
            ->findOrFail($id);

        $permohonan->update([
            'jawaban' =>
            $data['jawaban_final'],

            'file_jawaban' =>
            $permohonan
                ->file_pembantu,

            'tanggal_jawab' =>
            now()->toDateString(),

            'tanggal_validasi' =>
            now()->toDateString(),

            'adminid' =>
            $admin->id,

            'status' =>
            'Selesai',
        ]);

        $permohonan = $permohonan
            ->refresh()
            ->load([
                'userPublic',
                'ppidPembantu',
            ]);

        $this->kirimNotifikasiKePpidPembantu(
            permohonan: $permohonan,

            judul: 'Permohonan Telah Divalidasi',

            pesan: sprintf(
                'Jawaban untuk permohonan %s telah divalidasi oleh Admin Utama dan dinyatakan selesai.',
                $this->nomorPermohonan(
                    $permohonan
                )
            ),

            jenis: 'permohonan_divalidasi',

            icon: 'ri-checkbox-circle-line',

            actor: $admin
        );

        return $permohonan;
    }

    public function revisi(
        int $id,
        Authorization $admin,
        array $data
    ): Permohonan {
        $this->ensureAdminUtama(
            $admin,
            'Hanya admin utama yang dapat meminta revisi.'
        );

        $permohonan = $this->permohonan
            ->newQuery()
            ->findOrFail($id);

        $permohonan->update([
            'catatan_revisi' =>
            $data['catatan_revisi'],

            'tanggal_revisi' =>
            now()->toDateString(),

            'status' =>
            'Revisi PPID Pembantu',
        ]);

        $permohonan = $permohonan
            ->refresh()
            ->load([
                'userPublic',
                'ppidPembantu',
            ]);

        $this->kirimNotifikasiKePpidPembantu(
            permohonan: $permohonan,

            judul: 'Revisi Jawaban Permohonan',

            pesan: sprintf(
                'Jawaban untuk permohonan %s perlu direvisi. Catatan: %s',
                $this->nomorPermohonan(
                    $permohonan
                ),
                $data['catatan_revisi']
            ),

            jenis: 'revisi_permohonan',

            icon: 'ri-edit-2-line',

            actor: $admin
        );

        return $permohonan;
    }

    private function kirimNotifikasiKePpidPembantu(
        Permohonan $permohonan,
        string $judul,
        string $pesan,
        string $jenis,
        string $icon,
        Authorization $actor
    ): void {
        if (
            ! $permohonan
                ->ppid_pembantuid
        ) {
            return;
        }

        $penerima =
            Authorization::query()
            ->where(
                'role',
                self::ROLE_ADMIN_PEMBANTU
            )
            ->where(
                'ppid_pembantuid',
                $permohonan
                    ->ppid_pembantuid
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

                routeName: 'admin.permohonan.show',

                routeParams: [
                    'id' =>
                    $permohonan->id,
                ],

                icon: $icon,

                metadata: $this->metadataNotifikasi(
                    $permohonan,
                    $actor,
                    $jenis
                )
            )
        );
    }

    private function kirimNotifikasiKeAdminUtama(
        Permohonan $permohonan,
        string $judul,
        string $pesan,
        string $jenis,
        string $icon,
        Authorization $actor
    ): void {
        $penerima =
            Authorization::query()
            ->where(
                'role',
                self::ROLE_ADMIN_UTAMA
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

                routeName: 'admin.permohonan.show',

                routeParams: [
                    'id' =>
                    $permohonan->id,
                ],

                icon: $icon,

                metadata: $this->metadataNotifikasi(
                    $permohonan,
                    $actor,
                    $jenis
                )
            )
        );
    }

    private function metadataNotifikasi(
        Permohonan $permohonan,
        Authorization $actor,
        string $jenis
    ): array {
        return [
            'permohonan_id' =>
            $permohonan->id,

            'no_pemohon' =>
            $permohonan
                ->no_pemohon,

            'user_publikid' =>
            $permohonan
                ->user_publikid,

            'ppid_pembantuid' =>
            $permohonan
                ->ppid_pembantuid,

            'ppid_pembantu' =>
            $this->namaPpidPembantu(
                $permohonan
            ),

            'status' =>
            $permohonan->status,

            'actor_id' =>
            $actor->id,

            'actor_username' =>
            $actor->username,

            'actor_role' =>
            (int) $actor->role,

            'jenis_aktivitas' =>
            $jenis,

            'dikirim_pada' =>
            now()->toDateTimeString(),
        ];
    }

    private function nomorPermohonan(
        Permohonan $permohonan
    ): string {
        $nomor = trim(
            (string) $permohonan
                ->no_pemohon
        );

        return $nomor !== ''
            ? $nomor
            : '#' . $permohonan->id;
    }

    private function namaPpidPembantu(
        Permohonan $permohonan
    ): string {
        $nama = trim(
            (string) data_get(
                $permohonan,
                'ppidPembantu.nama',
                ''
            )
        );

        return $nama !== ''
            ? $nama
            : 'PPID Pembantu';
    }

    private function storeLaporanPermohonanFile(
        UploadedFile $file
    ): string {
        $originalName = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $filename = time()
            . '_'
            . str($originalName)
            ->slug()
            ->toString()
            . '.'
            . $file
            ->getClientOriginalExtension();

        return $file->storeAs(
            'laporan-permohonan',
            $filename,
            'public'
        );
    }

    private function deleteFile(
        ?string $path
    ): void {
        if (! $path) {
            return;
        }

        $disk = $this->storage
            ->disk('public');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }

    private function ensureAdminCanAccessPermohonan(
        Authorization $admin,
        Permohonan $permohonan
    ): void {
        if (
            ! $this->isAdminPembantu(
                $admin
            )
        ) {
            return;
        }

        if (
            (int) $permohonan
                ->ppid_pembantuid
            !==
            (int) $admin
                ->ppid_pembantuid
        ) {
            throw new AuthorizationException(
                'Akses ditolak.'
            );
        }
    }

    private function ensurePermohonanBelongsToAdminPembantu(
        Authorization $admin,
        Permohonan $permohonan
    ): void {
        if (
            (int) $permohonan
                ->ppid_pembantuid
            !==
            (int) $admin
                ->ppid_pembantuid
        ) {
            throw new AuthorizationException(
                'Permohonan ini bukan untuk PPID Pembantu Anda.'
            );
        }
    }

    private function ensureAdminUtama(
        Authorization $admin,
        string $message
    ): void {
        if (
            ! $this->isAdminUtama(
                $admin
            )
        ) {
            throw new AuthorizationException(
                $message
            );
        }
    }

    private function ensureAdminPembantu(
        Authorization $admin,
        string $message
    ): void {
        if (
            ! $this->isAdminPembantu(
                $admin
            )
        ) {
            throw new AuthorizationException(
                $message
            );
        }
    }

    private function isAdminUtama(
        Authorization $admin
    ): bool {
        return (int) $admin->role
            === self::ROLE_ADMIN_UTAMA;
    }

    private function isAdminPembantu(
        Authorization $admin
    ): bool {
        return (int) $admin->role
            === self::ROLE_ADMIN_PEMBANTU;
    }
}
