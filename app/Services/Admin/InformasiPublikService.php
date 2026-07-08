<?php

namespace App\Services\Admin;

use App\Models\Authorization;
use App\Models\Dokumentasi;
use App\Models\PpidPembantu;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class InformasiPublikService
{
    public function __construct(
        protected Dokumentasi $dokumentasi,
        protected PpidPembantu $ppidPembantu,
        protected FilesystemFactory $storage
    ) {}

    public function getForAdmin(Authorization $admin): Collection
    {
        return $this->dokumentasi
            ->newQuery()
            ->with('ppidPembantu')
            ->when($this->isAdminPembantu($admin), function ($query) use ($admin) {
                $query->where('ppid_pembantuid', $admin->ppid_pembantuid);
            })
            ->orderByDesc('id')
            ->get();
    }

    public function getPpidPembantuList(): Collection
    {
        return $this->ppidPembantu
            ->newQuery()
            ->orderBy('nama')
            ->get();
    }

    public function create(array $data, UploadedFile $file, Authorization $admin): Dokumentasi
    {
        $currentTime = time();

        if ($this->isAdminPembantu($admin)) {
            $data['ppid_pembantuid'] = $admin->ppid_pembantuid;
            $data['is_verifikasi'] = 0;
        } else {
            $data['is_verifikasi'] = 1;
        }

        $data['file'] = $this->storeDokumentasiFile($file);
        $data['tanggal'] = $currentTime;
        $data['slug'] = str($data['nama'])->slug()->toString() . '-' . $currentTime;

        return $this->dokumentasi
            ->newQuery()
            ->create($data);
    }

    public function verify(int $id, Authorization $admin): void
    {
        $this->ensureAdminUtama($admin);

        $dokumentasi = $this->dokumentasi
            ->newQuery()
            ->findOrFail($id);

        $dokumentasi->update([
            'is_verifikasi' => 1,
        ]);
    }

    public function delete(int $id, Authorization $admin): void
    {
        $query = $this->dokumentasi->newQuery();

        if ($this->isAdminPembantu($admin)) {
            $query->where('ppid_pembantuid', $admin->ppid_pembantuid);
        }

        $dokumentasi = $query->findOrFail($id);

        $this->deleteFile($dokumentasi->file);

        $dokumentasi->delete();
    }

    private function storeDokumentasiFile(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $filename = time() . '_' .
            str($originalName)->slug()->toString() .
            '.' .
            $file->getClientOriginalExtension();

        return $file->storeAs('dokumentasi', $filename, 'public');
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

    private function ensureAdminUtama(Authorization $admin): void
    {
        if (! $this->isAdminUtama($admin)) {
            throw new AuthorizationException('Akses ditolak.');
        }
    }

    private function isAdminUtama(Authorization $admin): bool
    {
        return (int) $admin->role === 1;
    }

    private function isAdminPembantu(Authorization $admin): bool
    {
        return (int) $admin->role === 2;
    }
}
