<?php

namespace App\Mail;

use App\Models\Permohonan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PermohonanSelesaiMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Permohonan $permohonan
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Permohonan Informasi Publik Selesai')
            ->view('emails.permohonan.selesai')
            ->with([
                'detailUrl' => $this->permohonan->trackingUrl(),
            ]);
    }
}
