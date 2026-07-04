<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $table = 'download';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
