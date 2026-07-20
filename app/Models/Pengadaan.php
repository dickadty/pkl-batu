<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengadaan extends Model
{
    protected $table = 'pengadaan';

    public $timestamps = false;

    protected $fillable = [
        'nama_paket',
        'pagu',
        'sumber_dana',
        'metode',
        'rencana_kegiatan',
        'ppid_pembantuid',
    ];

    protected $casts = [
        'id' => 'integer',
        'ppid_pembantuid' => 'integer',
    ];

    protected $appends = [
        'pagu_rupiah',
    ];

    public function ppidPembantu(): BelongsTo
    {
        return $this->belongsTo(
            PpidPembantu::class,
            'ppid_pembantuid',
            'id'
        );
    }

    public function getPaguRupiahAttribute(): string
    {
        $digits = preg_replace(
            '/[^0-9]/',
            '',
            (string) $this->pagu
        );

        $digits = ltrim(
            $digits,
            '0'
        );

        if ($digits === '') {
            $digits = '0';
        }

        $formatted = preg_replace(
            '/\B(?=(\d{3})+(?!\d))/',
            '.',
            $digits
        );

        return 'Rp ' . $formatted;
    }
}
