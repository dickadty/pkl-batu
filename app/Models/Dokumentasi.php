<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    protected $table = 'dokumentasi';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'tahun',
        'ringkasan',
        'file',
        'tanggal',
        'sifat',
        'kategori_id',
        'is_verifikasi',
        'slug',
        'ppid_pembantuid',
    ];

    public function ppidPembantu()
    {
        return $this->belongsTo(PpidPembantu::class, 'ppid_pembantuid', 'id');
    }

   public function kategori()
{
    return $this->belongsTo(
        KategoriInformasi::class,
        'kategori_id'
    );
}
}
