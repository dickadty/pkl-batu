<?php

namespace App\Mail;

use App\Models\Permohonan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PermohonanDiterimaMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Permohonan $permohonan,
        public ?string $activationUrl = null
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Tanda Terima Permohonan Informasi Publik')
            ->view('emails.permohonan.diterima')
            ->with([
                'detailUrl' => $this->permohonan->trackingUrl(),
            ]);
    }
}
