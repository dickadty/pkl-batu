<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'user_publikid',
    ];

    public function userPublik()
    {
        return $this->belongsTo(UserPublic::class, 'user_publikid');
    }
}
