<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Module;



class Pages extends Model
{
    protected $fillable = [
        'judul',
        'gambar',
        'file',
        'module_id',
        'slug',
        'content',
        'status'
    ];

    public function module()
{
    return $this->belongsTo(Module::class);
}
}

