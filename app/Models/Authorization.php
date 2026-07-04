<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Authorization extends Authenticatable
{
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

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberTokenName()
    {
        return null;
    }

    public function ppidPembantu()
    {
        return $this->belongsTo(PpidPembantu::class, 'ppid_pembantuid', 'id');
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