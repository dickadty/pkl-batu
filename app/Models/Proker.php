<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Proker extends Model
{
    use HasFactory;

    protected $table = 'proker';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'nama_proker',
        'anggaran',
        'sumber_dana',
        'target',
        'jadwal_pelaksanaan',
        'pj',
        'telp',
        'dokumen',
        'slug',
        'ppid_pembantuid',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'jadwal_pelaksanaan' => 'date',
            'ppid_pembantuid' => 'integer',
        ];
    }

    public function ppidPembantu(): BelongsTo
    {
        return $this->belongsTo(
            PpidPembantu::class,
            'ppid_pembantuid',
            'id'
        );
    }

    public function isDokumenEksternal(): bool
    {
        return !empty($this->dokumen)
            && filter_var(
                $this->dokumen,
                FILTER_VALIDATE_URL
            ) !== false;
    }

    public function getDokumenUrlAttribute(): ?string
    {
        if (empty($this->dokumen)) {
            return null;
        }

        if ($this->isDokumenEksternal()) {
            return $this->dokumen;
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->dokumen);
    }
}
