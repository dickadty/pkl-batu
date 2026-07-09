<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



class UserPublic extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $table = 'user_publik';

    public $timestamps = false;

    protected $attributes = [
        'is_aktif' => 1,
    ];

    protected $fillable = [
        'nama',
        'nik',
        'scanktp',
        'l_kelamin',
        'tmp_lahir',
        'tgl_lahir',
        'pekerjaan',
        'alamat',
        'desa_kel',
        'kecamatan',
        'kota_kab',
        'kode_pos',
        'provinsi',
        'telp',
        'email',
        'password',
        'wilayahkode',
    ];

    protected $hidden = [
        'password',
        'nik',
        'scanktp',
        'hint',
    ];

    protected function casts(): array
    {
        return [
            'tgl_lahir' => 'date:Y-m-d',
            'is_aktif' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(
            Wilayah::class,
            'wilayahkode',
            'kode'
        );
    }

    public function permohonan(): HasMany
    {
        return $this->hasMany(
            Permohonan::class,
            'user_publikid',
            'id'
        );
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(
            Download::class,
            'user_publikid',
            'id'
        );
    }
}
