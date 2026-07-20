<?php

namespace App\Console\Commands;

use App\Services\Admin\PermohonanReminderService;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Throwable;

class KirimPengingatPermohonan extends Command
{
    protected $signature = 'permohonan:kirim-pengingat
                            {--date= : Simulasi tanggal pemeriksaan dengan format YYYY-MM-DD}';

    protected $description = 'Memeriksa permohonan yang belum ditindaklanjuti dan mengirim notifikasi pengingat';

    public function __construct(
        protected PermohonanReminderService $reminderService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        try {
            $today = $this->resolveDate();

            $this->info('Memeriksa pengingat permohonan...');

            if ($today instanceof CarbonImmutable) {
                $this->warn(
                    'Menggunakan tanggal simulasi: ' .
                        $today->format('Y-m-d')
                );
            }

            $statistics = $this->reminderService->run(
                $today
            );

            $this->newLine();

            $this->table(
                [
                    'Keterangan',
                    'Jumlah',
                ],
                [
                    [
                        'Tanggal pemeriksaan',
                        $statistics['tanggal_pemeriksaan'] ?? '-',
                    ],
                    [
                        'Permohonan diperiksa',
                        $statistics['diperiksa'] ?? 0,
                    ],
                    [
                        'Notifikasi dikirim',
                        $statistics['dikirim'] ?? 0,
                    ],
                    [
                        'Sudah pernah dikirim',
                        $statistics['sudah_pernah_dikirim'] ?? 0,
                    ],
                    [
                        'Tidak ada penerima',
                        $statistics['tidak_ada_penerima'] ?? 0,
                    ],
                    [
                        'Tanggal tidak valid',
                        $statistics['tanggal_tidak_valid'] ?? 0,
                    ],
                    [
                        'Status diabaikan',
                        $statistics['status_diabaikan'] ?? 0,
                    ],
                    [
                        'Gagal',
                        $statistics['gagal'] ?? 0,
                    ],
                ]
            );

            $this->newLine();

            $this->info('Pemeriksaan pengingat selesai.');

            return self::SUCCESS;
        } catch (Throwable $exception) {
            report($exception);

            $this->error('Pemeriksaan pengingat gagal.');
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    private function resolveDate(): ?CarbonImmutable
    {
        $date = trim(
            (string) $this->option('date')
        );

        if ($date === '') {
            return null;
        }

        $parsedDate = CarbonImmutable::createFromFormat(
            '!Y-m-d',
            $date,
            config('app.timezone', 'Asia/Jakarta')
        );

        if (
            ! $parsedDate ||
            $parsedDate->format('Y-m-d') !== $date
        ) {
            throw new InvalidArgumentException(
                'Format tanggal simulasi harus YYYY-MM-DD.'
            );
        }

        return $parsedDate->startOfDay();
    }
}
