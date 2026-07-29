<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keberatan extends Model
{
    public const STATUS_DIAJUKAN = 'Diajukan';

    public const STATUS_DIPROSES = 'Diproses';

    public const STATUS_SELESAI = 'Selesai';

    public const STATUS_DITOLAK = 'Ditolak';

    protected $table = 'keberatan';

    protected $primaryKey = 'id';

    protected $fillable = [
        'no_keberatan',
        'permohonanid',
        'alasan',
        'status',
        'tanggapan',
        'tanggal_pengajuan',
        'tanggal_tanggapan',
        'adminid',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'permohonanid' => 'integer',
            'adminid' => 'integer',
            'tanggal_pengajuan' => 'date',
            'tanggal_tanggapan' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

  
    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(
            Permohonan::class,
            'permohonanid',
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

    /**
     * Daftar status keberatan.
     *
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_DIAJUKAN =>
                self::STATUS_DIAJUKAN,

            self::STATUS_DIPROSES =>
                self::STATUS_DIPROSES,

            self::STATUS_SELESAI =>
                self::STATUS_SELESAI,

            self::STATUS_DITOLAK =>
                self::STATUS_DITOLAK,
        ];
    }

    public function isDiajukan(): bool
    {
        return $this->status === self::STATUS_DIAJUKAN;
    }

    public function isDiproses(): bool
    {
        return $this->status === self::STATUS_DIPROSES;
    }

    public function isSelesai(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function isDitolak(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }
}