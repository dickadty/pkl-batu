<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $table = 'download';

    public $timestamps = false;

    protected $fillable = [
        'tujuan',
        'tanggal',
        'user_publikid',
        'dokumentasiid',
    ];

    public function userPublic()
    {
        return $this->belongsTo(UserPublic::class, 'user_publikid');
    }

    public function dokumentasi()
    {
        return $this->belongsTo(Dokumentasi::class, 'dokumentasiid');
    }
}
