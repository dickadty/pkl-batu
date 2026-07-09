<?php

namespace App\Models;

use App\Models\BalasPesan;
use Illuminate\Database\Eloquent\Model;

class PesanMasuk extends Model
{
    protected $table = 'pesan_masuk';

    public $timestamps = false;

    public const STATUS_BARU = 0;
    public const STATUS_DIBACA = 1;
    public const STATUS_DIBALAS = 2;
    public const STATUS_DITUTUP = 3;

    protected $fillable = [
        'token',
        'nama',
        'email',
        'subjek',
        'pesan',
        'status',
        'tanggal',
        'tanggal_dibaca',
        'tanggal_ditutup',
    ];

    public function balasan()
    {
        return $this->hasMany(BalasPesan::class, 'pesan_masukid')
            ->orderBy('tanggal')
            ->orderBy('id');
    }

    public function isClosed(): bool
    {
        return (int) $this->status === self::STATUS_DITUTUP;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ((int) $this->status) {
            self::STATUS_BARU => 'Baru',
            self::STATUS_DIBACA => 'Dibaca',
            self::STATUS_DIBALAS => 'Dibalas',
            self::STATUS_DITUTUP => 'Ditutup',
            default => 'Tidak Diketahui',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ((int) $this->status) {
            self::STATUS_BARU => 'bg-warning text-dark',
            self::STATUS_DIBACA => 'bg-info text-dark',
            self::STATUS_DIBALAS => 'bg-success',
            self::STATUS_DITUTUP => 'bg-secondary',
            default => 'bg-dark',
        };
    }
}
