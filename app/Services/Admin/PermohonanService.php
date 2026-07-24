<?php

namespace App\Services\Admin;

use App\Mail\PermohonanDitolakMail;
use App\Mail\PermohonanSelesaiMail;
use App\Models\Authorization;
use App\Models\Permohonan;
use App\Models\PpidPembantu;
use App\Models\UserPublic;
use App\Notifications\NotifikasiSistem;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use RuntimeException;
use Throwable;

class PermohonanService
{
    private const ROLE_ADMIN_UTAMA = 1;

    private const ROLE_ADMIN_PEMBANTU = 2;

    public function __construct(
        protected Permohonan $permohonan,
        protected PpidPembantu $ppidPembantu,
        protected FilesystemFactory $storage
    ) {}

    /**
     * Mengambil seluruh permohonan berdasarkan hak akses admin.
     */
    public function getForAdmin(
        Authorization $admin
    ): Collection {
        $items = $this->permohonan
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

        return $items->map(
            function (Permohonan $item): Permohonan {
                return $this->attachGuestUserFallback(
                    $item
                );
            }
        );
    }

    /**
     * Mengambil detail permohonan berdasarkan hak akses admin.
     */
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

        return $this->attachGuestUserFallback(
            $permohonan
        );
    }

    /**
     * Mengambil daftar PPID Pembantu.
     */
    public function getPpidPembantuList(): Collection
    {
        return $this->ppidPembantu
            ->newQuery()
            ->orderBy('nama')
            ->get();
    }

    /**
     * Admin Utama meneruskan permohonan ke PPID Pembantu.
     */
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

        if (! in_array(
            $permohonan->status,
            [
                'Diajukan',
                'Diproses',
            ],
            true
        )) {
            throw new AuthorizationException(
                'Permohonan ini tidak dapat diteruskan pada status saat ini.'
            );
        }

        $permohonan->update([
            'ppid_pembantuid' => $data['ppid_pembantuid'],
            'catatan_utama' => $data['catatan_utama'] ?? null,
            'tanggal_diteruskan' => now()->toDateString(),
            'status' => 'Diteruskan ke PPID Pembantu',
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
                $this->nomorPermohonan($permohonan),
                $this->namaPpidPembantu($permohonan)
            ),
            jenis: 'permohonan_diteruskan',
            icon: 'ri-send-plane-line',
            actor: $admin
        );

        return $this->attachGuestUserFallback(
            $permohonan
        );
    }

    /**
     * PPID Pembantu mengirim jawaban kepada Admin Utama.
     */
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

        if (! in_array(
            $permohonan->status,
            [
                'Diteruskan ke PPID Pembantu',
                'Revisi PPID Pembantu',
            ],
            true
        )) {
            throw new AuthorizationException(
                'Laporan tidak dapat dikirim pada status permohonan saat ini.'
            );
        }

        if ($filePembantu instanceof UploadedFile) {
            $this->deleteFile(
                $permohonan->file_pembantu
            );

            $data['file_pembantu'] = $this
                ->storeLaporanPermohonanFile(
                    $filePembantu
                );
        }

        $permohonan->update([
            'jawaban_pembantu' => $data['jawaban_pembantu'],
            'file_pembantu' => $data['file_pembantu']
                ?? $permohonan->file_pembantu,
            'tanggal_jawab_pembantu' => now()->toDateString(),
            'status' => 'Menunggu Validasi Admin Utama',
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
                $this->namaPpidPembantu($permohonan),
                $this->nomorPermohonan($permohonan)
            ),
            jenis: 'jawaban_ppid_pembantu',
            icon: 'ri-file-check-line',
            actor: $admin
        );

        return $this->attachGuestUserFallback(
            $permohonan
        );
    }

    /**
     * Admin Utama memvalidasi jawaban final.
     */
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
            ->with('userPublic')
            ->findOrFail($id);

        if (
            $permohonan->status
            !== 'Menunggu Validasi Admin Utama'
        ) {
            throw new AuthorizationException(
                'Permohonan ini tidak dapat divalidasi pada status saat ini.'
            );
        }

        $permohonan->update([
            'jawaban' => $data['jawaban_final'],
            'file_jawaban' => $permohonan->file_pembantu,
            'tanggal_jawab' => now()->toDateString(),
            'tanggal_validasi' => now()->toDateString(),
            'tanggal_selesai' => now()->toDateString(),
            'adminid' => $admin->id,
            'status' => 'Selesai',
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
                $this->nomorPermohonan($permohonan)
            ),
            jenis: 'permohonan_divalidasi',
            icon: 'ri-checkbox-circle-line',
            actor: $admin
        );

        $this->queueFinalEmailToCitizen(
            $permohonan
        );

        return $this->attachGuestUserFallback(
            $permohonan
        );
    }

    /**
     * Admin Utama meminta revisi jawaban.
     */
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

        if (
            $permohonan->status
            !== 'Menunggu Validasi Admin Utama'
        ) {
            throw new AuthorizationException(
                'Revisi tidak dapat diminta pada status permohonan saat ini.'
            );
        }

        $permohonan->update([
            'catatan_revisi' => $data['catatan_revisi'],
            'tanggal_revisi' => now()->toDateString(),
            'status' => 'Revisi PPID Pembantu',
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
                $this->nomorPermohonan($permohonan),
                $data['catatan_revisi']
            ),
            jenis: 'revisi_permohonan',
            icon: 'ri-edit-2-line',
            actor: $admin
        );

        return $this->attachGuestUserFallback(
            $permohonan
        );
    }

    /**
     * Admin Utama menolak permohonan pada pemeriksaan awal.
     */
    public function tolak(
        int $id,
        Authorization $admin,
        array $data
    ): Permohonan {
        $this->ensureAdminUtama(
            $admin,
            'Hanya admin utama yang dapat menolak permohonan.'
        );

        $permohonan = $this->permohonan
            ->newQuery()
            ->with('userPublic')
            ->findOrFail($id);

        if (! in_array(
            $permohonan->status,
            [
                'Diajukan',
                'Diproses',
            ],
            true
        )) {
            throw new AuthorizationException(
                'Permohonan hanya dapat ditolak pada tahap pemeriksaan awal.'
            );
        }

        $alasanPenolakan = trim(
            (string) ($data['alasan_penolakan'] ?? '')
        );

        if ($alasanPenolakan === '') {
            throw new RuntimeException(
                'Alasan penolakan tidak tersedia.'
            );
        }

        $permohonan->update([
            'catatan_revisi' => $alasanPenolakan,
            'tanggal_revisi' => now()->toDateString(),
            'adminid' => $admin->id,
            'status' => 'Ditolak',
        ]);

        $permohonan = $permohonan
            ->refresh()
            ->load([
                'userPublic',
                'ppidPembantu',
                'admin',
            ]);

        /*
         * Apabila permohonan belum diteruskan ke PPID Pembantu,
         * method notifikasi ini otomatis berhenti karena
         * ppid_pembantuid masih kosong.
         */
        $this->kirimNotifikasiKePpidPembantu(
            permohonan: $permohonan,
            judul: 'Permohonan Ditolak',
            pesan: sprintf(
                'Permohonan %s telah ditolak oleh Admin Utama. Alasan: %s',
                $this->nomorPermohonan($permohonan),
                $alasanPenolakan
            ),
            jenis: 'permohonan_ditolak',
            icon: 'ri-close-circle-line',
            actor: $admin
        );

        /*
         * Memasukkan email penolakan ke antrean database.
         */
        $this->queueRejectionEmailToCitizen(
            $permohonan
        );

        return $this->attachGuestUserFallback(
            $permohonan
        );
    }

    /**
     * Membuat fallback user untuk permohonan tanpa akun warga.
     */
    private function attachGuestUserFallback(
        Permohonan $permohonan
    ): Permohonan {
        if (
            $permohonan->userPublic
            instanceof UserPublic
        ) {
            return $permohonan;
        }

        $fallback = new UserPublic();

        $fallback->forceFill([
            'nama' => $permohonan->nama_pemohon,
            'nik' => $permohonan->nomor_identitas,
            'email' => $permohonan->email_pemohon,
            'telp' => $permohonan->telp_pemohon,
            'pekerjaan' => $permohonan->pekerjaan_pemohon,
            'alamat' => $permohonan->alamat_pemohon,
            'scanktp' => $permohonan->file_identitas,
        ]);

        $fallback->exists = false;

        $permohonan->setRelation(
            'userPublic',
            $fallback
        );

        return $permohonan;
    }

    /**
     * Memasukkan email jawaban final ke antrean.
     */
    private function queueFinalEmailToCitizen(
        Permohonan $permohonan
    ): void {
        $email = strtolower(
            trim((string) $permohonan->emailWarga())
        );

        if ($email === '') {
            Log::warning(
                'Email penyelesaian tidak dimasukkan ke antrean karena email warga kosong.',
                [
                    'permohonan_id' => $permohonan->id,
                    'no_pemohon' => $permohonan->no_pemohon,
                ]
            );

            return;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::warning(
                'Email penyelesaian tidak dimasukkan ke antrean karena email warga tidak valid.',
                [
                    'permohonan_id' => $permohonan->id,
                    'email' => $email,
                ]
            );

            return;
        }

        try {
            Mail::to($email)->queue(
                new PermohonanSelesaiMail(
                    $permohonan
                )
            );

            Log::info(
                'Email penyelesaian berhasil dimasukkan ke antrean.',
                [
                    'permohonan_id' => $permohonan->id,
                    'email' => $email,
                ]
            );
        } catch (Throwable $exception) {
            Log::error(
                'Gagal memasukkan email penyelesaian ke antrean.',
                [
                    'permohonan_id' => $permohonan->id,
                    'email' => $email,
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                ]
            );

            throw $exception;
        }
    }

    /**
     * Memasukkan email penolakan ke antrean.
     */
    private function queueRejectionEmailToCitizen(
        Permohonan $permohonan
    ): void {
        /*
     * Pastikan relasi warga tersedia karena emailWarga()
     * dapat mengambil email dari relasi userPublic.
     */
        $permohonan->loadMissing([
            'userPublic',
        ]);

        $email = strtolower(
            trim((string) $permohonan->emailWarga())
        );

        if ($email === '') {
            Log::error(
                'Email penolakan tidak dapat dikirim karena alamat email warga kosong.',
                [
                    'permohonan_id' => $permohonan->id,
                    'no_pemohon' => $permohonan->no_pemohon,
                    'email_pemohon' => $permohonan->email_pemohon,
                    'email_user_public' => data_get(
                        $permohonan,
                        'userPublic.email'
                    ),
                ]
            );

            throw new RuntimeException(
                'Alamat email pemohon tidak tersedia.'
            );
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::error(
                'Email penolakan tidak dapat dikirim karena alamat email warga tidak valid.',
                [
                    'permohonan_id' => $permohonan->id,
                    'no_pemohon' => $permohonan->no_pemohon,
                    'email' => $email,
                ]
            );

            throw new RuntimeException(
                'Alamat email pemohon tidak valid: ' . $email
            );
        }

        try {
            $mailable = new PermohonanDitolakMail(
                $permohonan
            );

            $mailable
                ->onConnection('database')
                ->onQueue('default');

            Mail::to($email)->queue(
                $mailable
            );

            Log::info(
                'Email penolakan berhasil dimasukkan ke antrean.',
                [
                    'permohonan_id' => $permohonan->id,
                    'no_pemohon' => $permohonan->no_pemohon,
                    'email' => $email,
                    'connection' => 'database',
                    'queue' => 'default',
                ]
            );
        } catch (Throwable $exception) {
            Log::error(
                'Email penolakan gagal dimasukkan ke antrean.',
                [
                    'permohonan_id' => $permohonan->id,
                    'no_pemohon' => $permohonan->no_pemohon,
                    'email' => $email,
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                ]
            );

            throw $exception;
        }
    }

    /**
     * Mengirim notifikasi kepada PPID Pembantu.
     */
    private function kirimNotifikasiKePpidPembantu(
        Permohonan $permohonan,
        string $judul,
        string $pesan,
        string $jenis,
        string $icon,
        Authorization $actor
    ): void {
        if (! $permohonan->ppid_pembantuid) {
            return;
        }

        $penerima = Authorization::query()
            ->where(
                'role',
                self::ROLE_ADMIN_PEMBANTU
            )
            ->where(
                'ppid_pembantuid',
                $permohonan->ppid_pembantuid
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
                    'id' => $permohonan->id,
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

    /**
     * Mengirim notifikasi kepada Admin Utama.
     */
    private function kirimNotifikasiKeAdminUtama(
        Permohonan $permohonan,
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
                    'id' => $permohonan->id,
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

    /**
     * Membentuk metadata notifikasi.
     */
    private function metadataNotifikasi(
        Permohonan $permohonan,
        Authorization $actor,
        string $jenis
    ): array {
        return [
            'permohonan_id' => $permohonan->id,
            'no_pemohon' => $permohonan->no_pemohon,
            'user_publikid' => $permohonan->user_publikid,
            'ppid_pembantuid' => $permohonan->ppid_pembantuid,
            'ppid_pembantu' => $this->namaPpidPembantu(
                $permohonan
            ),
            'status' => $permohonan->status,
            'actor_id' => $actor->id,
            'actor_username' => $actor->username,
            'actor_role' => (int) $actor->role,
            'jenis_aktivitas' => $jenis,
            'dikirim_pada' => now()->toDateTimeString(),
        ];
    }

    /**
     * Mengambil nomor permohonan.
     */
    private function nomorPermohonan(
        Permohonan $permohonan
    ): string {
        $nomor = trim(
            (string) $permohonan->no_pemohon
        );

        return $nomor !== ''
            ? $nomor
            : '#' . $permohonan->id;
    }

    /**
     * Mengambil nama PPID Pembantu.
     */
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

    /**
     * Menyimpan file laporan PPID Pembantu.
     */
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
            . $file->getClientOriginalExtension();

        return $file->storeAs(
            'laporan-permohonan',
            $filename,
            'public'
        );
    }

    /**
     * Menghapus file lama.
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
     * Memastikan admin dapat membuka permohonan.
     */
    private function ensureAdminCanAccessPermohonan(
        Authorization $admin,
        Permohonan $permohonan
    ): void {
        if (! $this->isAdminPembantu($admin)) {
            return;
        }

        if (
            (int) $permohonan->ppid_pembantuid
            !== (int) $admin->ppid_pembantuid
        ) {
            throw new AuthorizationException(
                'Akses ditolak.'
            );
        }
    }

    /**
     * Memastikan permohonan milik PPID Pembantu terkait.
     */
    private function ensurePermohonanBelongsToAdminPembantu(
        Authorization $admin,
        Permohonan $permohonan
    ): void {
        if (
            (int) $permohonan->ppid_pembantuid
            !== (int) $admin->ppid_pembantuid
        ) {
            throw new AuthorizationException(
                'Permohonan ini bukan untuk PPID Pembantu Anda.'
            );
        }
    }

    /**
     * Memastikan pengguna adalah Admin Utama.
     */
    private function ensureAdminUtama(
        Authorization $admin,
        string $message
    ): void {
        if (! $this->isAdminUtama($admin)) {
            throw new AuthorizationException(
                $message
            );
        }
    }

    /**
     * Memastikan pengguna adalah Admin Pembantu.
     */
    private function ensureAdminPembantu(
        Authorization $admin,
        string $message
    ): void {
        if (! $this->isAdminPembantu($admin)) {
            throw new AuthorizationException(
                $message
            );
        }
    }

    /**
     * Memeriksa role Admin Utama.
     */
    private function isAdminUtama(
        Authorization $admin
    ): bool {
        return (int) $admin->role
            === self::ROLE_ADMIN_UTAMA;
    }

    /**
     * Memeriksa role Admin Pembantu.
     */
    private function isAdminPembantu(
        Authorization $admin
    ): bool {
        return (int) $admin->role
            === self::ROLE_ADMIN_PEMBANTU;
    }
}
