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

    public function findById(int $id): Berita
    {
        return $this->berita
            ->newQuery()
            ->findOrFail($id);
    }

    public function create(
        array $data,
        ?UploadedFile $gambar = null
    ): Berita {
        if ($gambar instanceof UploadedFile) {
            $data['gambar'] = $this->storeGambar(
                $gambar
            );
        }

        $data['tanggal'] = time();

        return $this->berita
            ->newQuery()
            ->create($data);
    }

    public function update(
        int $id,
        array $data,
        ?UploadedFile $gambar = null
    ): Berita {
        $berita = $this->findById($id);

        $gambarLama = $berita->gambar;

        if ($gambar instanceof UploadedFile) {
            $data['gambar'] = $this->storeGambar(
                $gambar
            );
        } else {
            unset($data['gambar']);
        }

        $data['tanggal'] = time();

        $berita->fill($data);
        $berita->save();

        if (
            $gambar instanceof UploadedFile &&
            !empty($gambarLama) &&
            $gambarLama !== $berita->gambar
        ) {
            $this->deleteFile(
                $gambarLama
            );
        }

        return $berita->fresh() ?? $berita;
    }

    public function delete(int $id): void
    {
        $berita = $this->findById($id);

        $gambar = $berita->gambar;

        $berita->delete();

        $this->deleteFile(
            $gambar
        );
    }

    private function storeGambar(
        UploadedFile $file
    ): string {
        $originalName = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $slug = str($originalName)
            ->slug()
            ->toString();

        if ($slug === '') {
            $slug = 'berita';
        }

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $filename =
            now()->format('YmdHis') .
            '_' .
            uniqid() .
            '_' .
            $slug .
            '.' .
            $extension;

        return $file->storeAs(
            'berita',
            $filename,
            'public'
        );
    }

    private function deleteFile(
        ?string $path
    ): void {
        if (
            $path === null ||
            trim($path) === ''
        ) {
            return;
        }

        $disk = $this->storage
            ->disk('public');

        if ($disk->exists($path)) {
            $disk->delete($path);
        }
    }
}
