<?php

namespace App\Services\Publik;

use App\Models\Berita;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BeritaService
{
    public function __construct(
        protected Berita $berita
    ) {}

    /**
     * Menampilkan daftar berita untuk publik.
     */
    public function getAll(
        int $perPage = 9
    ): LengthAwarePaginator {
        return $this->berita
            ->newQuery()
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    /**
     * Detail berita.
     */
    public function findById(int $id): Berita
    {
        return $this->berita
            ->newQuery()
            ->findOrFail($id);
    }
}