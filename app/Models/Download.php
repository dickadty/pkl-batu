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

    protected $casts = [

        'tanggal' => 'integer'

    ];

    public function dokumentasi()
    {

        return $this->belongsTo(

            Dokumentasi::class,

            'dokumentasiid',

            'id'

        );
    }

    public function getTanggalFormatAttribute()
    {


        if (!$this->tanggal) {

            return '-';
        }



        return date(

            'd F Y H:i',

            $this->tanggal

        );
    }
}
