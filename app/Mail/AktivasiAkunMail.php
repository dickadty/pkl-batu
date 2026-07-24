<?php

namespace App\Mail;

use App\Models\UserPublic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AktivasiAkunMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public UserPublic $user,
        public string $activationUrl
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Aktivasi Akun Layanan PPID Kota Batu')
            ->view('emails.akun.aktivasi');
    }
}
