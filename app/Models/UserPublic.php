<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserPublic extends Authenticatable
{
    protected $table = 'user_publik';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'nik',
        'scanktp',
        'l_kelamin',
        'tmp_lahir',
        'tgl_lahir',
        'pekerjaan',
        'alamat',
        'desa_kel',
        'kecamatan',
        'kota_kab',
        'kode_pos',
        'provinsi',
        'telp',
        'email',
        'hint',
        'password',
        'is_aktif',
        'wilayahkode',
    ];

    protected $hidden = [
        'password',
    ];
}
