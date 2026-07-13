<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pejabat extends Model
{
    protected $table = 'pejabat';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'jabatan',
        'masa',
        'tmp_tgl_lahir',
        'alamat',
        'no_telp',
        'foto',
    ];
}
