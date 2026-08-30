<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';

    protected $fillable = [
        'name',
        'service',
        'rating',
        'message',
        'respondent_hash',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];
}
