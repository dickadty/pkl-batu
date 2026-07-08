<?php

namespace App\Services;

use App\Models\Authorization;
use App\Models\Permohonan;
use App\Models\PpidPembantu;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class PermohonanService
{
    public function __construct(
        protected Permohonan $permohonan,
        protected PpidPembantu $ppidPembantu,
        protected FilesystemFactory $storage
    ) {}

    public function getForAdmin(Authorization $admin): Collection
    {
        return $this->permohonan
            ->newQuery()
            ->with(['userPublic', 'ppidPembantu'])
            ->when($this->isAdminPembantu($admin), function ($query) use ($admin) {
                $query->where('ppid_pembantuid', $admin->ppid_pembantuid);
            })
            ->orderByDesc('id')
            ->get();
    }

    public function getDetailForAdmin(int $id, Authorization $admin): Permohonan
    {
        $permohonan = $this->permohonan
            ->newQuery()
            ->with(['userPublic', 'ppidPembantu', 'admin'])
            ->findOrFail($id);

        $this->ensureAdminCanAccessPermohonan($admin, $permohonan);

        return $permohonan;
    }

    public function getPpidPembantuList(): Collection
    {
        return $this->ppidPembantu
            ->newQuery()
            ->orderBy('nama')
            ->get();
    }

    public function teruskan(int $id, Authorization $admin, array $data): Permohonan
    {
        $this->ensureAdminUtama(
            $admin,
            'Hanya admin utama yang dapat meneruskan permohonan.'
        );

        $permohonan = $this->permohonan
            ->newQuery()
            ->findOrFail($id);

        $permohonan->update([
            'ppid_pembantuid' => $data['ppid_pembantuid'],
            'catatan_utama' => $data['catatan_utama'] ?? null,
            'tanggal_diteruskan' => now()->toDateString(),
            'status' => 'Diteruskan ke PPID Pembantu',
        ]);

        return $permohonan;
    }

    public function jawabPembantu(
        int $id,
        Authorization $admin,
        array $data,
        ?UploadedFile $filePembantu = null
    ): Permohonan {
        $this->ensureAdminPembantu(
            $admin,
            'Hanya admin pembantu yang dapat memberi laporan.'
        );

        $permohonan = $this->permohonan
            ->newQuery()
            ->findOrFail($id);

        $this->ensurePermohonanBelongsToAdminPembantu($admin, $permohonan);

        if ($filePembantu) {
            $this->deleteFile($permohonan->file_pembantu);

            $data['file_pembantu'] = $this->storeLaporanPermohonanFile($filePembantu);
        }

        $permohonan->update([
            'jawaban_pembantu' => $data['jawaban_pembantu'],
            'file_pembantu' => $data['file_pembantu'] ?? $permohonan->file_pembantu,
            'tanggal_jawab_pembantu' => now()->toDateString(),
            'status' => 'Menunggu Validasi Admin Utama',
        ]);

        return $permohonan;
    }

    public function validasi(int $id, Authorization $admin, array $data): Permohonan
    {
        $this->ensureAdminUtama(
            $admin,
            'Hanya admin utama yang dapat validasi.'
        );

        $permohonan = $this->permohonan
            ->newQuery()
            ->findOrFail($id);

        $permohonan->update([
            'jawaban' => $data['jawaban_final'],
            'file_jawaban' => $permohonan->file_pembantu,
            'tanggal_jawab' => now()->toDateString(),
            'tanggal_validasi' => now()->toDateString(),
            'adminid' => $admin->id,
            'status' => 'Selesai',
        ]);

        return $permohonan;
    }

    public function revisi(int $id, Authorization $admin, array $data): Permohonan
    {
        $this->ensureAdminUtama(
            $admin,
            'Hanya admin utama yang dapat meminta revisi.'
        );

        $permohonan = $this->permohonan
            ->newQuery()
            ->findOrFail($id);

        $permohonan->update([
            'catatan_revisi' => $data['catatan_revisi'],
            'status' => 'Revisi PPID Pembantu',
        ]);

        return $permohonan;
    }

    private function storeLaporanPermohonanFile(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $filename = time() . '_' .
            str($originalName)->slug()->toString() .
            '.' .
            $file->getClientOriginalExtension();

        return $file->storeAs('laporan-permohonan', $filename, 'public');
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

    private function ensureAdminCanAccessPermohonan(
        Authorization $admin,
        Permohonan $permohonan
    ): void {
        if (! $this->isAdminPembantu($admin)) {
            return;
        }

        if ((int) $permohonan->ppid_pembantuid !== (int) $admin->ppid_pembantuid) {
            throw new AuthorizationException('Akses ditolak.');
        }
    }

    private function ensurePermohonanBelongsToAdminPembantu(
        Authorization $admin,
        Permohonan $permohonan
    ): void {
        if ((int) $permohonan->ppid_pembantuid !== (int) $admin->ppid_pembantuid) {
            throw new AuthorizationException(
                'Permohonan ini bukan untuk PPID Pembantu Anda.'
            );
        }
    }

    private function ensureAdminUtama(Authorization $admin, string $message): void
    {
        if (! $this->isAdminUtama($admin)) {
            throw new AuthorizationException($message);
        }
    }

    private function ensureAdminPembantu(Authorization $admin, string $message): void
    {
        if (! $this->isAdminPembantu($admin)) {
            throw new AuthorizationException($message);
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
