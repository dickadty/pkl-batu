<?php

namespace App\Services\Publik;

use App\Mail\PermohonanDiterimaMail;
use App\Models\Authorization;
use App\Models\Permohonan;
use App\Models\UserPublic;
use App\Notifications\NotifikasiSistem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class PermohonanService
{
    public function __construct(
        protected Permohonan $permohonan,
        protected AccountActivationService $activationService
    ) {}

    /**
     * Mengambil daftar permohonan milik warga.
     */
    public function getByUser(
        UserPublic $user
    ): LengthAwarePaginator {

        return $this->permohonan
            ->newQuery()
            ->where(
                'user_publikid',
                $user->id
            )
            ->orderByDesc('id')
            ->paginate(
                request('per_page', 15)
            )
            ->withQueryString();
    }

    /**
     * Mengambil detail permohonan berdasarkan token.
     */
    public function getByToken(
        string $token
    ): Permohonan {
        return $this->permohonan
            ->newQuery()
            ->with([
                'userPublic',
                'ppidPembantu',
            ])
            ->where(
                'token',
                $token
            )
            ->firstOrFail();
    }

    /**
     * Memeriksa apakah identitas warga sudah memiliki akun.
     *
     * @param array<string, mixed> $data
     *
     * @return array{
     *     state: string,
     *     user: UserPublic|null
     * }
     */
    public function inspectGuestAccount(
        array $data
    ): array {
        $email = $this->normalizeEmail(
            (string) $data['email_pemohon']
        );

        $identityNumber =
            $this->normalizeIdentityNumber(
                (string) $data['nomor_identitas']
            );

        $phone = $this->normalizePhone(
            (string) $data['telp_pemohon']
        );

        $accounts = UserPublic::query()
            ->where(
                function ($query) use (
                    $email,
                    $identityNumber,
                    $phone
                ): void {
                    $query
                        ->where(
                            'email',
                            $email
                        )
                        ->orWhere(
                            'nik',
                            $identityNumber
                        )
                        ->orWhere(
                            'telp',
                            $phone
                        );
                }
            )
            ->get();

        if ($accounts->isEmpty()) {
            return [
                'state' => 'new',
                'user' => null,
            ];
        }

        $exactAccount = $accounts->first(
            function (
                UserPublic $account
            ) use (
                $email,
                $identityNumber
            ): bool {
                return $this->normalizeEmail(
                    (string) $account->email
                ) === $email
                    && $this->normalizeIdentityNumber(
                        (string) $account->nik
                    ) === $identityNumber;
            }
        );

        if (! $exactAccount) {
            return [
                'state' => 'conflict',
                'user' => null,
            ];
        }

        return [
            'state' => (bool) $exactAccount->is_aktif
                ? 'active'
                : 'pending',

            'user' => $exactAccount,
        ];
    }

    /**
     * Membuat akun warga apabila diperlukan dan menyimpan permohonan.
     *
     * KTP dan surat kuasa wajib diberikan sebagai UploadedFile.
     *
     * @param array<string, mixed> $data
     */
    public function createForApplicant(
        ?UserPublic $user,
        array $data,
        UploadedFile $fileIdentitas,
        UploadedFile $fileSuratKuasa
    ): Permohonan {
        /*
        |--------------------------------------------------------------------------
        | Validasi Invariant Service
        |--------------------------------------------------------------------------
        |
        | Controller sudah melakukan validasi HTTP. Pemeriksaan ini memastikan
        | service tidak dapat dipanggil dari tempat lain tanpa kedua dokumen.
        |
        */

        if (! $fileIdentitas->isValid()) {
            throw ValidationException::withMessages([
                'file_identitas' =>
                'File KTP tidak valid atau gagal diunggah.',
            ]);
        }

        if (! $fileSuratKuasa->isValid()) {
            throw ValidationException::withMessages([
                'file_surat_kuasa' =>
                'File surat kuasa tidak valid atau gagal diunggah.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pemeriksaan Akun Pemohon Baru
        |--------------------------------------------------------------------------
        */

        if (! $user) {
            $accountState = $this
                ->inspectGuestAccount($data);

            if ($accountState['state'] !== 'new') {
                throw ValidationException::withMessages([
                    'email_pemohon' =>
                    'Identitas tersebut sudah terdaftar. Silakan masuk menggunakan akun warga atau gunakan fasilitas kirim ulang aktivasi apabila akun belum aktif.',
                ]);
            }
        }

        $identityPath = null;
        $suratKuasaPath = null;

        try {
            /*
            |--------------------------------------------------------------------------
            | Menyimpan KTP
            |--------------------------------------------------------------------------
            */

            $identityPath = $this
                ->storePrivateFile(
                    file: $fileIdentitas,
                    directory: 'permohonan/identitas'
                );

            /*
            |--------------------------------------------------------------------------
            | Menyimpan Surat Kuasa
            |--------------------------------------------------------------------------
            */

            $suratKuasaPath = $this
                ->storePrivateFile(
                    file: $fileSuratKuasa,
                    directory: 'permohonan/surat-kuasa'
                );

            /*
            |--------------------------------------------------------------------------
            | Transaksi Database
            |--------------------------------------------------------------------------
            */

            $result = DB::transaction(
                function () use (
                    $user,
                    $data,
                    $identityPath,
                    $suratKuasaPath
                ): array {
                    $applicant = $user;
                    $activationUrl = null;

                    /*
                     * Membuat akun baru untuk pemohon tanpa login.
                     */
                    if (! $applicant) {
                        $applicant = $this
                            ->createInactiveAccount(
                                data: $data,
                                identityPath: $identityPath
                            );

                        $activationUrl = $this
                            ->activationService
                            ->issueToken(
                                $applicant
                            );
                    } else {
                        /*
                         * KTP baru disimpan sebagai identitas terbaru akun.
                         * File lama tidak dihapus karena mungkin masih digunakan
                         * oleh riwayat permohonan sebelumnya.
                         */
                        $applicant->forceFill([
                            'scanktp' =>
                            $identityPath,
                        ])->save();
                    }

                    $isNewAccount =
                        $user === null;

                    /*
                    |--------------------------------------------------------------------------
                    | Snapshot Identitas Pemohon
                    |--------------------------------------------------------------------------
                    */

                    $snapshotName = $isNewAccount
                        ? trim(
                            (string) $data['nama_pemohon']
                        )
                        : trim(
                            (string) $applicant->nama
                        );

                    $snapshotIdentityNumber =
                        $isNewAccount
                        ? $this
                        ->normalizeIdentityNumber(
                            (string) $data['nomor_identitas']
                        )
                        : trim(
                            (string) $applicant->nik
                        );

                    $snapshotEmail = $isNewAccount
                        ? $this->normalizeEmail(
                            (string) $data['email_pemohon']
                        )
                        : $this->normalizeEmail(
                            (string) $applicant->email
                        );

                    $snapshotPhone = $isNewAccount
                        ? $this->normalizePhone(
                            (string) $data['telp_pemohon']
                        )
                        : $this->normalizePhone(
                            (string) $applicant->telp
                        );

                    $snapshotOccupation =
                        $isNewAccount
                        ? $this->nullableTrim(
                            $data['pekerjaan_pemohon'] ?? null
                        )
                        : $this->nullableTrim(
                            $applicant->pekerjaan
                        );

                    $snapshotAddress =
                        $isNewAccount
                        ? trim(
                            (string) $data['alamat_pemohon']
                        )
                        : trim(
                            (string) $applicant->alamat
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | Menyimpan Permohonan
                    |--------------------------------------------------------------------------
                    */

                    $permohonan = $this
                        ->permohonan
                        ->newQuery()
                        ->create([
                            'token' =>
                            $this->generateUniqueToken(),

                            'no_pemohon' => null,

                            'tanggal' =>
                            now()->toDateString(),

                            'rincian' => trim(
                                (string) $data['rincian']
                            ),

                            'tujuan' => trim(
                                (string) $data['tujuan']
                            ),

                            'cara_memperoleh' =>
                            $data['cara_memperoleh'],

                            'cara_pengiriman' =>
                            $data['cara_pengiriman'],

                            'status' => 'Diajukan',

                            'user_publikid' =>
                            $applicant->id,

                            'kategori_pemohon' =>
                            $data['kategori_pemohon'],

                            'nama_pemohon' =>
                            $snapshotName,

                            'nomor_identitas' =>
                            $snapshotIdentityNumber,

                            'email_pemohon' =>
                            $snapshotEmail,

                            'telp_pemohon' =>
                            $snapshotPhone,

                            'pekerjaan_pemohon' =>
                            $snapshotOccupation,

                            'alamat_pemohon' =>
                            $snapshotAddress,

                            /*
                             * Kedua path selalu terisi.
                             */
                            'file_identitas' =>
                            $identityPath,

                            'file_surat_kuasa' =>
                            $suratKuasaPath,
                        ]);

                    /*
                     * Nomor permohonan dibuat setelah ID tersedia.
                     */
                    $permohonan->update([
                        'no_pemohon' =>
                        $this->buildRegistrationNumber(
                            $permohonan
                        ),
                    ]);

                    return [
                        'permohonan' =>
                        $permohonan
                            ->refresh()
                            ->load([
                                'userPublic',
                                'ppidPembantu',
                            ]),

                        'activation_url' =>
                        $activationUrl,
                    ];
                }
            );
        } catch (Throwable $exception) {
            /*
             * Menghapus file yang sudah tersimpan apabila proses gagal.
             */
            $this->deletePrivateFile(
                $identityPath
            );

            $this->deletePrivateFile(
                $suratKuasaPath
            );

            if (
                $exception instanceof QueryException
                && (string) $exception->getCode()
                === '23000'
            ) {
                throw ValidationException::withMessages([
                    'email_pemohon' =>
                    'Identitas tersebut sudah terdaftar. Silakan masuk menggunakan akun warga.',
                ]);
            }

            throw $exception;
        }

        /** @var Permohonan $permohonan */
        $permohonan = $result['permohonan'];

        /*
        |--------------------------------------------------------------------------
        | Email dan Notifikasi
        |--------------------------------------------------------------------------
        */

        $this->queueReceiptEmail(
            permohonan: $permohonan,
            activationUrl: $result['activation_url']
        );

        $this->sendDashboardNotificationToMainAdmin(
            $permohonan
        );

        return $permohonan;
    }

    /**
     * Membuat akun warga nonaktif untuk pengajuan pertama.
     *
     * @param array<string, mixed> $data
     */
    private function createInactiveAccount(
        array $data,
        string $identityPath
    ): UserPublic {
        $account = UserPublic::query()
            ->create([
                'nama' => trim(
                    (string) $data['nama_pemohon']
                ),

                'nik' =>
                $this->normalizeIdentityNumber(
                    (string) $data['nomor_identitas']
                ),

                'scanktp' => $identityPath,

                'l_kelamin' =>
                $data['l_kelamin'],

                'tmp_lahir' => trim(
                    (string) $data['tmp_lahir']
                ),

                'tgl_lahir' =>
                $data['tgl_lahir'],

                'pekerjaan' =>
                $this->nullableTrim(
                    $data['pekerjaan_pemohon'] ?? null
                ),

                'alamat' => Str::limit(
                    trim(
                        (string) $data['alamat_pemohon']
                    ),
                    100,
                    ''
                ),

                'desa_kel' =>
                $this->nullableTrim(
                    $data['desa_kel'] ?? null
                ),

                'kecamatan' =>
                $this->nullableTrim(
                    $data['kecamatan'] ?? null
                ),

                'kota_kab' =>
                $this->nullableTrim(
                    $data['kota_kab'] ?? null
                ),

                'provinsi' =>
                $this->nullableTrim(
                    $data['provinsi'] ?? null
                ),

                'telp' =>
                $this->normalizePhone(
                    (string) $data['telp_pemohon']
                ),

                'email' =>
                $this->normalizeEmail(
                    (string) $data['email_pemohon']
                ),

                'password' =>
                Str::random(64),
            ]);

        $account->forceFill([
            'is_aktif' => 0,
        ])->save();

        return $account->refresh();
    }

    /**
     * Membentuk nomor registrasi permohonan.
     */
    private function buildRegistrationNumber(
        Permohonan $permohonan
    ): string {
        return sprintf(
            'PI/%06d/%s/%s',
            $permohonan->id,
            now()->format('m'),
            now()->format('Y')
        );
    }

    /**
     * Membuat token publik yang unik.
     */
    private function generateUniqueToken(): string
    {
        do {
            $token = Str::random(64);
        } while (
            $this->permohonan
            ->newQuery()
            ->where(
                'token',
                $token
            )
            ->exists()
        );

        return $token;
    }

    /**
     * Menyimpan dokumen pada disk lokal privat.
     */
    private function storePrivateFile(
        UploadedFile $file,
        string $directory
    ): string {
        if (! $file->isValid()) {
            throw new RuntimeException(
                'File yang diunggah tidak valid.'
            );
        }

        $originalName = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $safeName = Str::slug(
            $originalName
        );

        if ($safeName === '') {
            $safeName = 'dokumen';
        }

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        if ($extension === '') {
            throw new RuntimeException(
                'Ekstensi file tidak dapat dikenali.'
            );
        }

        $filename = sprintf(
            '%s_%s.%s',
            now()->format('YmdHis'),
            Str::random(12),
            $extension
        );

        $storedPath = $file->storeAs(
            trim($directory, '/')
                . '/'
                . now()->format('Y/m'),

            $safeName
                . '_'
                . $filename,

            'local'
        );

        if (
            ! is_string($storedPath)
            || trim($storedPath) === ''
        ) {
            throw new RuntimeException(
                'File gagal disimpan ke penyimpanan.'
            );
        }

        return $storedPath;
    }

    /**
     * Menghapus file privat.
     */
    private function deletePrivateFile(
        ?string $path
    ): void {
        if (
            ! is_string($path)
            || trim($path) === ''
        ) {
            return;
        }

        /**
         * @var FilesystemAdapter $disk
         */
        $disk = Storage::disk('local');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }

    /**
     * Menormalisasi alamat email.
     */
    private function normalizeEmail(
        string $email
    ): string {
        return strtolower(
            trim($email)
        );
    }

    /**
     * Menormalisasi nomor identitas.
     */
    private function normalizeIdentityNumber(
        string $identityNumber
    ): string {
        return preg_replace(
            '/\s+/',
            '',
            trim($identityNumber)
        ) ?: trim($identityNumber);
    }

    /**
     * Menormalisasi nomor telepon.
     */
    private function normalizePhone(
        string $phone
    ): string {
        return preg_replace(
            '/[\s\-().]/',
            '',
            trim($phone)
        ) ?: trim($phone);
    }

    /**
     * Mengubah teks kosong menjadi null.
     */
    private function nullableTrim(
        mixed $value
    ): ?string {
        $value = trim(
            (string) $value
        );

        return $value !== ''
            ? $value
            : null;
    }

    /**
     * Mengirim notifikasi dashboard kepada Admin Utama.
     */
    private function sendDashboardNotificationToMainAdmin(
        Permohonan $permohonan
    ): void {
        $recipients = Authorization::query()
            ->where(
                'role',
                1
            )
            ->get();

        if ($recipients->isEmpty()) {
            return;
        }

        Notification::send(
            $recipients,
            new NotifikasiSistem(
                judul: 'Permohonan Informasi Baru',

                pesan: sprintf(
                    '%s mengajukan permohonan informasi dengan nomor tiket %s.',
                    $permohonan->namaWarga(),
                    $permohonan->no_pemohon
                ),

                jenis: 'permohonan_baru',

                routeName: 'admin.permohonan.show',

                routeParams: [
                    'id' => $permohonan->id,
                ],

                icon: 'ri-file-add-line',

                metadata: [
                    'permohonan_id' =>
                    $permohonan->id,

                    'no_pemohon' =>
                    $permohonan->no_pemohon,

                    'nama_pemohon' =>
                    $permohonan->namaWarga(),

                    'email_pemohon' =>
                    $permohonan->emailWarga(),

                    'user_publikid' =>
                    $permohonan->user_publikid,

                    'status' =>
                    $permohonan->status,

                    'jenis_aktivitas' =>
                    'permohonan_baru',

                    'dikirim_pada' =>
                    now()->toDateTimeString(),
                ]
            )
        );
    }

    /**
     * Memasukkan email tanda terima ke antrean.
     */
    private function queueReceiptEmail(
        Permohonan $permohonan,
        ?string $activationUrl
    ): void {
        $email = trim(
            (string) $permohonan->emailWarga()
        );

        if ($email === '') {
            Log::warning(
                'Email tanda terima permohonan tidak dikirim karena alamat email kosong.',
                [
                    'permohonan_id' =>
                    $permohonan->id,
                ]
            );

            return;
        }

        if (
            filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            ) === false
        ) {
            Log::warning(
                'Email tanda terima permohonan tidak dikirim karena alamat email tidak valid.',
                [
                    'permohonan_id' =>
                    $permohonan->id,

                    'email' => $email,
                ]
            );

            return;
        }

        try {
            Mail::to($email)->queue(
                new PermohonanDiterimaMail(
                    permohonan: $permohonan,
                    activationUrl: $activationUrl
                )
            );
        } catch (Throwable $exception) {
            Log::error(
                'Gagal memasukkan email tanda terima permohonan ke antrean.',
                [
                    'permohonan_id' =>
                    $permohonan->id,

                    'email' => $email,

                    'exception' =>
                    get_class($exception),

                    'message' =>
                    $exception->getMessage(),
                ]
            );
        }
    }
}
