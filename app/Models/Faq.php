<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faq';

    public $timestamps = false;

    protected $fillable = [
        'tanya',
        'jawab',
        'tanggal',
        'status',
    ];

    public function getStatusLabelAttribute(): string
    {
        return (int) $this->status === 1 ? 'Aktif' : 'Nonaktif';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return (int) $this->status === 1 ? 'bg-success' : 'bg-secondary';
    }
}
