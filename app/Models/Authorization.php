<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Authorization extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * Nama tabel akun administrator.
     */
    protected $table = 'authorization';

    /**
     * Primary key tabel authorization.
     */
    protected $primaryKey = 'id';

    /**
     * Tabel tidak mempunyai created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi secara mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'email',
        'role',
        'user_publikid',
        'ppid_pembantuid',
    ];

    /**
     * Kolom yang tidak ditampilkan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Casting atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'role' => 'integer',
        'user_publikid' => 'integer',
        'ppid_pembantuid' => 'integer',
    ];

    /**
     * Mengembalikan password yang digunakan Laravel Auth.
     */
    public function getAuthPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * Menonaktifkan remember token karena tabel authorization
     * tidak memiliki kolom remember_token.
     */
    public function getRememberTokenName(): ?string
    {
        return null;
    }

    /**
     * Relasi ke unit PPID Pembantu.
     */
    public function ppidPembantu(): BelongsTo
    {
        return $this->belongsTo(
            PpidPembantu::class,
            'ppid_pembantuid',
            'id'
        );
    }

    /**
     * Mengecek apakah akun merupakan Admin Utama.
     */
    public function isAdminUtama(): bool
    {
        return (int) $this->role === 1;
    }

    /**
     * Mengecek apakah akun merupakan Admin PPID Pembantu.
     */
    public function isAdminPembantu(): bool
    {
        return (int) $this->role === 2;
    }
}
