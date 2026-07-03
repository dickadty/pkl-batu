<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPpid extends Model
{
    protected $table = 'kategori_ppid';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'kategori',
    ];

    public function ppidPembantu()
    {
        return $this->hasMany(PpidPembantu::class, 'kategori_ppidid');
    }
}
