<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permohonan extends Model
{
    protected $table = 'permohonan';

    public $timestamps = false;

    protected $fillable = [
        'no_pemohon',
        'tanggal',
        'rincian',
        'tujuan',
        'status',

        'jawaban',
        'file_jawaban',
        'tanggal_jawab',
        'adminid',

        'user_publikid',
        'ppid_pembantuid',
        'catatan_utama',
        'tanggal_diteruskan',

        'jawaban_pembantu',
        'file_pembantu',
        'tanggal_jawab_pembantu',

        'catatan_revisi',
        'tanggal_revisi',
        'tanggal_validasi',
    ];

    public function userPublic(): BelongsTo
    {
        return $this->belongsTo(
            UserPublic::class,
            'user_publikid',
            'id'
        );
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(
            Authorization::class,
            'adminid',
            'id'
        );
    }

    public function ppidPembantu(): BelongsTo
    {
        return $this->belongsTo(
            PpidPembantu::class,
            'ppid_pembantuid',
            'id'
        );
    }

    public function tenggatNotifikasi(): HasMany
    {
        return $this->hasMany(
            PermohonanTenggatNotifikasi::class,
            'permohonan_id',
            'id'
        );
    }
}