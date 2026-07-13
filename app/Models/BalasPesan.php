<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalasPesan extends Model
{
    protected $table = 'balas_pesan';

    public $timestamps = false;

    protected $fillable = [
        'pesan_masukid',
        'pengirim',
        'adminid',
        'pesan',
        'tanggal',
    ];

    public function pesanMasuk()
    {
        return $this->belongsTo(PesanMasuk::class, 'pesan_masukid');
    }

    public function admin()
    {
        return $this->belongsTo(Authorization::class, 'adminid');
    }
}
