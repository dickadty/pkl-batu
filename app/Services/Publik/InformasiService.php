<?php

namespace App\Services\Publik;

use App\Models\Dokumentasi;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InformasiService
{
    public function __construct(
        protected Dokumentasi $dokumentasi,
        protected FilesystemFactory $storage
    ) {}

    public function getVerifiedInformation(): Collection
    {
        return $this->dokumentasi
            ->newQuery()
            ->with('ppidPembantu')
            ->where('is_verifikasi', 1)
            ->orderByDesc('id')
            ->get();
    }

    public function findVerifiedBySlug(string $slug): Dokumentasi
    {
        return $this->dokumentasi
            ->newQuery()
            ->with('ppidPembantu')
            ->where('slug', $slug)
            ->where('is_verifikasi', 1)
            ->firstOrFail();
    }

    public function getVerifiedDownloadPath(int $id): string
    {
        $dokumen = $this->dokumentasi
            ->newQuery()
            ->where('id', $id)
            ->where('is_verifikasi', 1)
            ->firstOrFail();

        $disk = $this->storage->disk('public');

        if (! $dokumen->file || ! $disk->exists($dokumen->file)) {
            throw new NotFoundHttpException('File tidak ditemukan.');
        }

        return $disk->path($dokumen->file);
    }
}
