<?php

namespace App\Services\Admin;

use App\Models\Berita;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class BeritaService
{
    public function __construct(
        protected Berita $berita,
        protected FilesystemFactory $storage
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->berita
            ->newQuery()
            ->orderByDesc('id')
            ->get();
    }

    public function create(array $data, ?UploadedFile $gambar = null): Berita
    {
        if ($gambar) {
            $data['gambar'] = $this->storeGambar($gambar);
        }

        $data['tanggal'] = time();

        return $this->berita
            ->newQuery()
            ->create($data);
    }

    public function delete(int $id): void
    {
        $berita = $this->berita
            ->newQuery()
            ->findOrFail($id);

        $this->deleteFile($berita->gambar);

        $berita->delete();
    }

    private function storeGambar(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $filename = time() . '_' .
            str($originalName)->slug()->toString() .
            '.' .
            $file->getClientOriginalExtension();

        return $file->storeAs('berita', $filename, 'public');
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
