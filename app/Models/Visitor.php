<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $table = 'visitor_visits';

    protected $fillable = [
        'visitor_hash',
        'visit_date',
        'hits',
        'first_seen_at',
        'last_seen_at',
        'last_path',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'hits' => 'integer',
    ];
}