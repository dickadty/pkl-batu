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

        'dokumentasiid',

    ];



    public function dokumentasi()
    {

        return $this->belongsTo(
            Dokumentasi::class,
            'dokumentasiid'
        );
    }



    public function getTanggalFormatAttribute()
    {

        return $this->tanggal
            ? date(
                'd-m-Y H:i:s',
                $this->tanggal
            )
            : '-';
    }
}
