<?php

namespace App\Services\Publik;

use App\Models\Permohonan;
use App\Models\UserPublic;
use Illuminate\Database\Eloquent\Collection;

class PermohonanService
{
    public function __construct(
        protected Permohonan $permohonan
    ) {}

    public function getByUser(UserPublic $user): Collection
    {
        return $this->permohonan
            ->newQuery()
            ->where('user_publikid', $user->id)
            ->orderByDesc('id')
            ->get();
    }

    public function createForUser(UserPublic $user, array $data): Permohonan
    {
        return $this->permohonan
            ->newQuery()
            ->create([
                'no_pemohon' => time(),
                'tanggal' => now()->toDateString(),
                'rincian' => $data['rincian'],
                'tujuan' => $data['tujuan'],
                'status' => 'Diajukan',
                'user_publikid' => $user->id,
            ]);
    }
}
