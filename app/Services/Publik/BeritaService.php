<?php

namespace App\Services\Publik;

use App\Models\Berita;
use Illuminate\Database\Eloquent\Collection;

class BeritaService
{
    public function __construct(
        protected Berita $berita
    ) {}

    public function getAll(): Collection
    {
        return $this->berita
            ->newQuery()
            ->orderByDesc('id')
            ->get();
    }

    public function findById(int $id): Berita
    {
        return $this->berita
            ->newQuery()
            ->findOrFail($id);
    }
}
