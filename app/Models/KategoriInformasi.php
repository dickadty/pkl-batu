<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriInformasi extends Model
{
    protected $table = 'kategori_informasi';

    protected $fillable = [
        'nama',
        'slug',
        'sifat',
    ];

   public function dokumentasi()
{
    return $this->hasMany(
        Dokumentasi::class,
        'kategori_id'
    );
}
}