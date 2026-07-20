<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermohonanTenggatNotifikasi extends Model
{
    protected $table =
    'permohonan_tenggat_notifikasi';

    protected $fillable = [
        'permohonan_id',
        'jenis_notifikasi',
        'status_permohonan',
        'tanggal_acuan',
        'usia_hari',
        'dikirim_pada',
    ];

    protected $casts = [
        'permohonan_id' => 'integer',
        'usia_hari' => 'integer',
        'tanggal_acuan' => 'date',
        'dikirim_pada' => 'datetime',
    ];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(
            Permohonan::class,
            'permohonan_id',
            'id'
        );
    }
}
