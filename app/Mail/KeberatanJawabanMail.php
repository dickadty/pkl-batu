<?php

namespace App\Mail;

use App\Models\Keberatan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KeberatanJawabanMail extends Mailable implements ShouldQueue
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
            ->subject('Jawaban PPID Pelaksana untuk Keberatan Diterima')
            ->view('emails.keberatan.jawaban')
            ->with([
                'detailUrl' => route('admin.keberatan.show', ['id' => $this->keberatan->id]),
            ]);
    }
}
