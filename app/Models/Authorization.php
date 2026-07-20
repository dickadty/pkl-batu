<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Authorization extends Authenticatable
{
    use Notifiable;

    protected $table = 'authorization';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'email',
        'role',
        'user_publikid',
        'ppid_pembantuid',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id' => 'integer',
        'role' => 'integer',
        'user_publikid' => 'integer',
        'ppid_pembantuid' => 'integer',
    ];

    public function getAuthPassword(): string
    {
        return (string) $this->password;
    }

    public function getRememberTokenName(): ?string
    {
        return null;
    }

    public function ppidPembantu(): BelongsTo
    {
        return $this->belongsTo(
            PpidPembantu::class,
            'ppid_pembantuid',
            'id'
        );
    }

    public function isAdminUtama(): bool
    {
        return (int) $this->role === 1;
    }

    public function isAdminPembantu(): bool
    {
        return (int) $this->role === 2;
    }
}
