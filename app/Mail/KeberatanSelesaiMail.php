<?php

namespace App\Mail;

use App\Models\Keberatan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KeberatanSelesaiMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Keberatan $keberatan)
    {
        $this->afterCommit();
    }

    public function build(): self
    {
        return $this
            ->subject('Hasil Keberatan Informasi Publik Tersedia')
            ->view('emails.keberatan.selesai')
            ->with([
                'detailUrl' => route('public.keberatan.show', ['id' => $this->keberatan->id]),
            ]);
    }
}
