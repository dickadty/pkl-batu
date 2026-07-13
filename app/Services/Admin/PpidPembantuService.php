<?php

namespace App\Services\Admin;

use App\Models\KategoriPpid;
use App\Models\PpidPembantu;
use Illuminate\Database\Eloquent\Collection;

class PpidPembantuService
{
    public function __construct(
        protected PpidPembantu $ppidPembantu,
        protected KategoriPpid $kategoriPpid
    ) {}

    public function getAllWithKategori(): Collection
    {
        return $this->ppidPembantu
            ->newQuery()
            ->with('kategoriPpid')
            ->orderBy('id')
            ->get();
    }

    public function getKategoriList(): Collection
    {
        return $this->kategoriPpid
            ->newQuery()
            ->orderBy('kategori')
            ->get();
    }

    public function findById(int $id): PpidPembantu
    {
        return $this->ppidPembantu
            ->newQuery()
            ->findOrFail($id);
    }

    public function create(array $data): PpidPembantu
    {
        $data['slug'] = $this->generateSlug($data['nama']);

        return $this->ppidPembantu
            ->newQuery()
            ->create($data);
    }

    public function update(int $id, array $data): PpidPembantu
    {
        $ppidPembantu = $this->findById($id);

        $data['slug'] = $this->generateSlug($data['nama']);

        $ppidPembantu->update($data);

        return $ppidPembantu;
    }

    public function delete(int $id): void
    {
        $ppidPembantu = $this->findById($id);

        $ppidPembantu->delete();
    }

    private function generateSlug(string $nama): string
    {
        return str($nama)->slug()->toString();
    }
}
