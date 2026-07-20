<?php

namespace App\Services\Admin;

use App\Models\Authorization;
use App\Models\Permohonan;
use App\Models\PermohonanTenggatNotifikasi;
use App\Notifications\NotifikasiSistem;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Throwable;

class PermohonanReminderService
{
    private const ROLE_ADMIN_UTAMA = 1;

    private const ROLE_ADMIN_PEMBANTU = 2;

    private const STATUS_SELESAI = 'selesai';

    public function run(
        ?CarbonImmutable $today = null
    ): array {
        $today ??= CarbonImmutable::today(
            config('app.timezone')
        );

        $statistics = [
            'tanggal_pemeriksaan' =>
                $today->format('Y-m-d'),

            'diperiksa' => 0,
            'dikirim' => 0,
            'sudah_pernah_dikirim' => 0,
            'tidak_ada_penerima' => 0,
            'tanggal_tidak_valid' => 0,
            'status_diabaikan' => 0,
            'gagal' => 0,
        ];

        Permohonan::query()
            ->where(function ($query): void {
                $query
                    ->whereNull('status')
                    ->orWhereRaw(
                        'LOWER(status) NOT LIKE ?',
                        ['%selesai%']
                    );
            })
            ->orderBy('id')
            ->chunkById(
                100,
                function (
                    $permohonanList
                ) use (
                    $today,
                    &$statistics
                ): void {
                    foreach (
                        $permohonanList
                        as $permohonan
                    ) {
                        $statistics['diperiksa']++;

                        try {
                            $result = $this
                                ->processPermohonan(
                                    $permohonan,
                                    $today
                                );

                            if (
                                array_key_exists(
                                    $result,
                                    $statistics
                                )
                            ) {
                                $statistics[$result]++;
                            }
                        } catch (Throwable $exception) {
                            report($exception);

                            $statistics['gagal']++;
                        }
                    }
                }
            );

        return $statistics;
    }

    private function processPermohonan(
        Permohonan $permohonan,
        CarbonImmutable $today
    ): string {
        $stage = $this->resolveStage(
            $permohonan
        );

        if ($stage === null) {
            return 'status_diabaikan';
        }

        $referenceDate = $this->getReferenceDate(
            $permohonan,
            $stage
        );

        if ($referenceDate === null) {
            return 'tanggal_tidak_valid';
        }

        $ageInDays = $this->calculateAgeInDays(
            $referenceDate,
            $today
        );

        $threshold = $this->resolveThreshold(
            $stage,
            $ageInDays
        );

        if ($threshold === null) {
            return 'status_diabaikan';
        }

        $notificationType =
            $this->notificationType(
                $stage,
                $threshold
            );

        $alreadySent =
            PermohonanTenggatNotifikasi::query()
                ->where(
                    'permohonan_id',
                    $permohonan->id
                )
                ->where(
                    'jenis_notifikasi',
                    $notificationType
                )
                ->whereDate(
                    'tanggal_acuan',
                    $referenceDate->format(
                        'Y-m-d'
                    )
                )
                ->exists();

        if ($alreadySent) {
            return 'sudah_pernah_dikirim';
        }

        $recipients = $this->getRecipients(
            $permohonan,
            $stage,
            $threshold
        );

        if ($recipients->isEmpty()) {
            return 'tidak_ada_penerima';
        }

        $notificationData =
            $this->buildNotificationData(
                $permohonan,
                $stage,
                $threshold,
                $ageInDays,
                $referenceDate,
                $today
            );

        $sent = DB::transaction(
            function () use (
                $permohonan,
                $stage,
                $threshold,
                $ageInDays,
                $referenceDate,
                $notificationType,
                $notificationData,
                $recipients
            ): bool {
                $log =
                    PermohonanTenggatNotifikasi::query()
                        ->firstOrCreate(
                            [
                                'permohonan_id' =>
                                    $permohonan->id,

                                'jenis_notifikasi' =>
                                    $notificationType,

                                'tanggal_acuan' =>
                                    $referenceDate
                                        ->format(
                                            'Y-m-d'
                                        ),
                            ],
                            [
                                'status_permohonan' =>
                                    $permohonan->status,

                                'usia_hari' =>
                                    $ageInDays,

                                'dikirim_pada' =>
                                    now(),
                            ]
                        );

                if (! $log->wasRecentlyCreated) {
                    return false;
                }

                Notification::send(
                    $recipients,
                    new NotifikasiSistem(
                        judul:
                            $notificationData[
                                'judul'
                            ],

                        pesan:
                            $notificationData[
                                'pesan'
                            ],

                        jenis:
                            $notificationType,

                        routeName:
                            'admin.permohonan.show',

                        routeParams: [
                            'id' =>
                                $permohonan->id,
                        ],

                        icon:
                            $notificationData[
                                'icon'
                            ],

                        metadata: [
                            'permohonan_id' =>
                                $permohonan->id,

                            'no_pemohon' =>
                                $permohonan
                                    ->no_pemohon,

                            'status_permohonan' =>
                                $permohonan
                                    ->status,

                            'tahap_pengingat' =>
                                $stage,

                            'batas_pengingat_hari' =>
                                $threshold,

                            'usia_hari' =>
                                $ageInDays,

                            'tanggal_acuan' =>
                                $referenceDate
                                    ->format(
                                        'Y-m-d'
                                    ),

                            'tanggal_pemeriksaan' =>
                                $notificationData[
                                    'tanggal_pemeriksaan'
                                ],

                            'ppid_pembantuid' =>
                                $permohonan
                                    ->ppid_pembantuid,

                            'dikirim_pada' =>
                                now()
                                    ->toDateTimeString(),
                        ]
                    )
                );

                return true;
            }
        );

        return $sent
            ? 'dikirim'
            : 'sudah_pernah_dikirim';
    }

    private function resolveStage(
        Permohonan $permohonan
    ): ?string {
        $status = Str::lower(
            trim(
                (string) $permohonan->status
            )
        );

        if (
            $status === self::STATUS_SELESAI
            || Str::contains(
                $status,
                'selesai'
            )
        ) {
            return null;
        }

        if (
            Str::contains(
                $status,
                'menunggu validasi'
            )
        ) {
            return 'menunggu_validasi';
        }

        if (
            Str::contains(
                $status,
                'revisi'
            )
        ) {
            return 'revisi';
        }

        if (
            Str::contains(
                $status,
                'diteruskan'
            )
        ) {
            return 'diteruskan';
        }

        if (
            in_array(
                $status,
                [
                    '',
                    'baru',
                    'menunggu',
                    'diajukan',
                    'permohonan baru',
                    'menunggu diproses',
                ],
                true
            )
        ) {
            return 'baru';
        }

        if (
            ! $permohonan->ppid_pembantuid
            && ! $permohonan
                ->jawaban_pembantu
            && ! $permohonan->jawaban
        ) {
            return 'baru';
        }

        return null;
    }

    private function getReferenceDate(
        Permohonan $permohonan,
        string $stage
    ): ?CarbonImmutable {
        $value = match ($stage) {
            'baru' =>
                $permohonan->tanggal,

            'diteruskan' =>
                $permohonan
                    ->tanggal_diteruskan,

            'menunggu_validasi' =>
                $permohonan
                    ->tanggal_jawab_pembantu,

            'revisi' =>
                $permohonan->tanggal_revisi
                ?? $permohonan
                    ->tanggal_jawab_pembantu,

            default => null,
        };

        return $this->parseDate($value);
    }

    private function parseDate(
        mixed $value
    ): ?CarbonImmutable {
        if (
            $value === null
            || $value === ''
        ) {
            return null;
        }

        try {
            if ($value instanceof DateTimeInterface) {
                return CarbonImmutable::instance(
                    $value
                )->startOfDay();
            }

            if (
                is_numeric($value)
                && (int) $value >= 1000000000
            ) {
                return CarbonImmutable
                    ::createFromTimestamp(
                        (int) $value,
                        config('app.timezone')
                    )
                    ->startOfDay();
            }

            return CarbonImmutable::parse(
                (string) $value,
                config('app.timezone')
            )->startOfDay();
        } catch (Throwable) {
            return null;
        }
    }

    private function calculateAgeInDays(
        CarbonImmutable $referenceDate,
        CarbonImmutable $today
    ): int {
        if (
            $referenceDate->greaterThan(
                $today
            )
        ) {
            return 0;
        }

        return (int) floor(
            $referenceDate->diffInDays(
                $today
            )
        );
    }

    private function resolveThreshold(
        string $stage,
        int $ageInDays
    ): ?int {
        $thresholds = collect(
            config(
                "permohonan.reminder.{$stage}",
                []
            )
        )
            ->map(
                fn ($day): int =>
                    (int) $day
            )
            ->filter(
                fn (int $day): bool =>
                    $day > 0
                    && $ageInDays >= $day
            )
            ->sortDesc()
            ->values();

        if ($thresholds->isEmpty()) {
            return null;
        }

        return (int) $thresholds->first();
    }

    private function getRecipients(
        Permohonan $permohonan,
        string $stage,
        int $threshold
    ): Collection {
        $recipients = collect();

        if (
            in_array(
                $stage,
                [
                    'baru',
                    'menunggu_validasi',
                ],
                true
            )
        ) {
            return $this
                ->getAdminUtamaRecipients();
        }

        if ($stage === 'diteruskan') {
            $recipients = $recipients->merge(
                $this
                    ->getAdminPembantuRecipients(
                        $permohonan
                            ->ppid_pembantuid
                    )
            );

            if ($threshold >= 5) {
                $recipients = $recipients->merge(
                    $this
                        ->getAdminUtamaRecipients()
                );
            }
        }

        if ($stage === 'revisi') {
            $recipients = $recipients->merge(
                $this
                    ->getAdminPembantuRecipients(
                        $permohonan
                            ->ppid_pembantuid
                    )
            );

            if ($threshold >= 3) {
                $recipients = $recipients->merge(
                    $this
                        ->getAdminUtamaRecipients()
                );
            }
        }

        return $recipients
            ->unique('id')
            ->values();
    }

    private function getAdminUtamaRecipients(): Collection
    {
        return Authorization::query()
            ->where(
                'role',
                self::ROLE_ADMIN_UTAMA
            )
            ->get();
    }

    private function getAdminPembantuRecipients(
        mixed $ppidPembantuId
    ): Collection {
        if (! $ppidPembantuId) {
            return collect();
        }

        return Authorization::query()
            ->where(
                'role',
                self::ROLE_ADMIN_PEMBANTU
            )
            ->where(
                'ppid_pembantuid',
                $ppidPembantuId
            )
            ->get();
    }

    private function buildNotificationData(
        Permohonan $permohonan,
        string $stage,
        int $threshold,
        int $ageInDays,
        CarbonImmutable $referenceDate,
        CarbonImmutable $today
    ): array {
        $number = $this->getRequestNumber(
            $permohonan
        );

        $data = match ($stage) {
            'baru' => [
                'judul' =>
                    $threshold >= 3
                        ? 'Permohonan Belum Ditindaklanjuti'
                        : 'Pengingat Permohonan Baru',

                'pesan' => sprintf(
                    'Permohonan %s sudah %d hari belum diteruskan kepada PPID Pembantu. Segera lakukan pemeriksaan dan penugasan.',
                    $number,
                    $ageInDays
                ),

                'icon' =>
                    $threshold >= 3
                        ? 'ri-alarm-warning-line'
                        : 'ri-time-line',
            ],

            'diteruskan' => [
                'judul' =>
                    $threshold >= 5
                        ? 'Permohonan Belum Dijawab'
                        : 'Pengingat Jawaban Permohonan',

                'pesan' => sprintf(
                    'Permohonan %s sudah %d hari diteruskan dan belum diberikan jawaban oleh PPID Pembantu.',
                    $number,
                    $ageInDays
                ),

                'icon' =>
                    $threshold >= 5
                        ? 'ri-alarm-warning-line'
                        : 'ri-timer-line',
            ],

            'menunggu_validasi' => [
                'judul' =>
                    $threshold >= 3
                        ? 'Validasi Jawaban Terlambat'
                        : 'Pengingat Validasi Jawaban',

                'pesan' => sprintf(
                    'Jawaban permohonan %s sudah %d hari menunggu validasi Admin Utama.',
                    $number,
                    $ageInDays
                ),

                'icon' =>
                    $threshold >= 3
                        ? 'ri-alarm-warning-line'
                        : 'ri-file-check-line',
            ],

            'revisi' => [
                'judul' =>
                    $threshold >= 3
                        ? 'Revisi Belum Ditindaklanjuti'
                        : 'Pengingat Revisi Permohonan',

                'pesan' => sprintf(
                    'Revisi jawaban permohonan %s sudah %d hari belum ditindaklanjuti oleh PPID Pembantu.',
                    $number,
                    $ageInDays
                ),

                'icon' =>
                    $threshold >= 3
                        ? 'ri-alarm-warning-line'
                        : 'ri-edit-2-line',
            ],

            default => [
                'judul' =>
                    'Pengingat Permohonan',

                'pesan' => sprintf(
                    'Permohonan %s memerlukan tindak lanjut.',
                    $number
                ),

                'icon' =>
                    'ri-notification-3-line',
            ],
        };

        $data['tanggal_acuan'] =
            $referenceDate->format(
                'Y-m-d'
            );

        $data['tanggal_pemeriksaan'] =
            $today->format('Y-m-d');

        return $data;
    }

    private function notificationType(
        string $stage,
        int $threshold
    ): string {
        return sprintf(
            'pengingat_%s_h%d',
            $stage,
            $threshold
        );
    }

    private function getRequestNumber(
        Permohonan $permohonan
    ): string {
        $number = trim(
            (string) $permohonan
                ->no_pemohon
        );

        return $number !== ''
            ? $number
            : '#' . $permohonan->id;
    }
}