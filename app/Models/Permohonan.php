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
        'tanggal_validasi',
    ];

    public function userPublic()
    {
        return $this->belongsTo(UserPublic::class, 'user_publikid');
    }

    public function admin()
    {
        return $this->belongsTo(Authorization::class, 'adminid');
    }

    public function ppidPembantu()
    {
        return $this->belongsTo(PpidPembantu::class, 'ppid_pembantuid');
    }
}
