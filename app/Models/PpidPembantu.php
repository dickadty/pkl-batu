<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpidPembantu extends Model
{
    protected $table = 'ppid_pembantu';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'keterangan',
        'kategori_ppidid',
        'linkweb',
        'telp',
        'alamat',
        'icon',
        'slug',
    ];

    public function kategoriPpid()
    {
        return $this->belongsTo(KategoriPpid::class, 'kategori_ppidid');
    }
}
