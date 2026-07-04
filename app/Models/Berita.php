<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';

    public $timestamps = false;

    protected $fillable = [
        'judul',
        'caption',
        'gambar',
        'tanggal',
    ];
}
