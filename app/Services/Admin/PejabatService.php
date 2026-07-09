<?php

namespace App\Services\Admin;

use App\Models\Pejabat;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class PejabatService
{
    public function __construct(
        protected Pejabat $pejabat,
        protected FilesystemFactory $storage
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->pejabat
            ->newQuery()
            ->orderByDesc('id')
            ->get();
    }

    public function findById(int $id): Pejabat
    {
        return $this->pejabat
            ->newQuery()
            ->findOrFail($id);
    }

    public function create(array $data, ?UploadedFile $foto = null): Pejabat
    {
        if ($foto) {
            $data['foto'] = $this->storeFoto($foto);
        }

        return $this->pejabat
            ->newQuery()
            ->create($data);
    }

    public function update(int $id, array $data, ?UploadedFile $foto = null): Pejabat
    {
        $pejabat = $this->findById($id);

        if ($foto) {
            $this->deleteFile($pejabat->foto);

            $data['foto'] = $this->storeFoto($foto);
        }

        $pejabat->update($data);

        return $pejabat;
    }

    public function delete(int $id): void
    {
        $pejabat = $this->findById($id);

        $this->deleteFile($pejabat->foto);

        $pejabat->delete();
    }

    private function storeFoto(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $filename = time() . '_' .
            str($originalName)->slug()->toString() .
            '.' .
            $file->getClientOriginalExtension();

        return $file->storeAs('pejabat', $filename, 'public');
    }

    private function deleteFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $disk = $this->storage->disk('public');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }
}
