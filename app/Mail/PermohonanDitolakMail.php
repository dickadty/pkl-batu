<?php

namespace App\Mail;

use App\Models\Permohonan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class PermohonanDitolakMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Maksimal percobaan pemrosesan job.
     */
    public int $tries = 3;

    /**
     * Batas waktu pemrosesan job dalam detik.
     */
    public int $timeout = 90;

    /**
     * Membuat instance email penolakan.
     */
    public function __construct(
        public Permohonan $permohonan
    ) {
        /*
         * Email baru diproses setelah transaksi database selesai.
         */
        $this->afterCommit();
    }

    /**
     * Menyusun email penolakan.
     */
    public function build(): self
    {
        $namaWarga = trim(
            (string) $this->permohonan->namaWarga()
        );

        if ($namaWarga === '') {
            $namaWarga = 'Pemohon';
        }

        $nomorRegistrasi = trim(
            (string) $this->permohonan->no_pemohon
        );

        if ($nomorRegistrasi === '') {
            $nomorRegistrasi = '#' . $this->permohonan->id;
        }

        $alasanPenolakan = trim(
            (string) $this->permohonan->catatan_revisi
        );

        if ($alasanPenolakan === '') {
            $alasanPenolakan =
                'Data atau dokumen permohonan belum memenuhi kelengkapan yang dipersyaratkan.';
        }

        $tanggalPenolakan = '-';

        if ($this->permohonan->tanggal_revisi) {
            $tanggalPenolakan = Carbon::parse(
                $this->permohonan->tanggal_revisi
            )
                ->locale('id')
                ->translatedFormat('d F Y');
        }

        $trackingUrl = $this->permohonan->trackingUrl();

        if (! is_string($trackingUrl) || trim($trackingUrl) === '') {
            $trackingUrl = url('/');
        }

        return $this
            ->subject(
                'Permintaan Informasi Publik Tidak Dapat Diproses'
            )
            ->view(
                'emails.permohonan.permohonan-ditolak'
            )
            ->with([
                'namaWarga' => $namaWarga,
                'nomorRegistrasi' => $nomorRegistrasi,
                'alasanPenolakan' => $alasanPenolakan,
                'tanggalPenolakan' => $tanggalPenolakan,
                'trackingUrl' => $trackingUrl,
            ]);
    }

    /**
     * Mencatat kegagalan pengiriman email.
     */
    public function failed(
        Throwable $exception
    ): void {
        Log::error(
            'Queue email penolakan permohonan gagal diproses.',
            [
                'permohonan_id' => $this->permohonan->id ?? null,
                'no_pemohon' => $this->permohonan->no_pemohon ?? null,
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
            ]
        );
    }
}
