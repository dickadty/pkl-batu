<?php

namespace App\Services\Admin;

use App\Models\Authorization;
use App\Models\Keberatan;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class KeberatanService
{
    public function __construct(protected Keberatan $keberatan) {}

    public function getDetail(int $id, Authorization $admin): Keberatan
    {
        $query = $this->keberatan->newQuery()->with([
            'permohonan.userPublic',
            'ppidPembantu',
            'admin',
        ]);

        if ($admin->isAdminUtama()) {
            return $query->findOrFail($id);
        }

        $this->ensureAdminPembantu($admin);

        return $query
            ->where('ppid_pembantuid', (int) $admin->ppid_pembantuid)
            ->findOrFail($id);
    }

    public function teruskan(int $id, Authorization $admin, array $data): Keberatan
    {
        $this->ensureAdminUtama($admin);
        $keberatan = $this->keberatan->newQuery()->findOrFail($id);

        if (! $keberatan->isDiajukan()) {
            throw new AuthorizationException('Keberatan hanya dapat diteruskan ketika status masih Diajukan.');
        }

        $keberatan->update([
            'ppid_pembantuid' => (int) $data['ppid_pembantuid'],
            'catatan_utama' => $data['catatan_utama'] ?? null,
            'status' => Keberatan::STATUS_DIPROSES,
            'tanggal_diproses' => now()->toDateString(),
            'adminid' => $admin->id,
        ]);

        return $keberatan->refresh()->load(['permohonan.userPublic', 'ppidPembantu', 'admin']);
    }

    public function jawabPembantu(int $id, Authorization $admin, array $data, ?UploadedFile $fileJawabanPembantu = null): Keberatan
    {
        $this->ensureAdminPembantu($admin);
        $keberatan = $this->keberatan->newQuery()->findOrFail($id);

        if ((int) $keberatan->ppid_pembantuid !== (int) $admin->ppid_pembantuid) {
            throw new AuthorizationException('Keberatan ini tidak ditugaskan ke unit PPID Anda.');
        }

        if (! $keberatan->isDiproses()) {
            throw new AuthorizationException('Jawaban PPID Pelaksana hanya dapat dikirim ketika keberatan sudah diteruskan.');
        }

        $newPath = null;
        $originalName = null;

        if ($fileJawabanPembantu instanceof UploadedFile) {
            $originalName = $fileJawabanPembantu->getClientOriginalName();
            $newPath = $fileJawabanPembantu->store('keberatan/jawaban-pembantu', 'local');

            if (! is_string($newPath)) {
                throw new RuntimeException('Dokumen jawaban PPID Pelaksana gagal disimpan.');
            }
        }

        DB::transaction(function () use ($keberatan, $data, $newPath, $originalName, $admin): void {
            $oldPath = $keberatan->file_jawaban_pembantu;
            $keberatan->update([
                'jawaban_pembantu' => $data['tanggapan'],
                'file_jawaban_pembantu' => $newPath ?? $oldPath,
                'nama_file_jawaban_pembantu' => $originalName ?? $keberatan->nama_file_jawaban_pembantu,
                'tanggal_jawab_pembantu' => now()->toDateString(),
                'adminid' => $admin->id,
            ]);

            if ($newPath !== null && filled($oldPath) && $oldPath !== $newPath && Storage::disk('local')->exists($oldPath)) {
                Storage::disk('local')->delete($oldPath);
            }
        });

        return $keberatan->refresh()->load(['permohonan.userPublic', 'ppidPembantu', 'admin']);
    }

    public function proses(int $id, Authorization $admin): Keberatan
    {
        $this->ensureAdminUtama($admin);
        $keberatan = $this->keberatan->newQuery()->findOrFail($id);

        if (! $keberatan->isDiajukan()) {
            throw new AuthorizationException('Keberatan hanya dapat diproses ketika status masih Diajukan.');
        }

        $keberatan->update([
            'status' => Keberatan::STATUS_DIPROSES,
            'tanggal_diproses' => now()->toDateString(),
            'adminid' => $admin->id,
        ]);

        return $keberatan->refresh();
    }

    public function selesaikan(int $id, Authorization $admin, array $data, ?UploadedFile $fileTanggapan = null): Keberatan
    {
        $this->ensureAdminUtama($admin);
        $keberatan = $this->keberatan->newQuery()->findOrFail($id);

        if (! $keberatan->isDiproses()) {
            throw new AuthorizationException('Keberatan harus berstatus Diproses sebelum diselesaikan.');
        }

        $finalTanggapan = trim((string) ($data['tanggapan'] ?? $keberatan->jawaban_pembantu));
        if ($finalTanggapan === '') {
            throw new RuntimeException('Jawaban PPID Pelaksana wajib tersedia sebelum keberatan diselesaikan.');
        }

        $requiresDocument = in_array($data['jenis_tindak_lanjut'], [
            Keberatan::TINDAK_LANJUT_DOKUMEN_TAMBAHAN,
            Keberatan::TINDAK_LANJUT_DOKUMEN_PENGGANTI,
            Keberatan::TINDAK_LANJUT_PERBAIKAN_DOKUMEN,
        ], true);

        if ($requiresDocument && ! $fileTanggapan instanceof UploadedFile && empty($keberatan->file_tanggapan) && empty($keberatan->file_jawaban_pembantu)) {
            throw new RuntimeException('Dokumen tanggapan wajib diunggah untuk jenis tindak lanjut ini.');
        }

        $newPath = null;
        $originalName = null;
        if ($fileTanggapan instanceof UploadedFile) {
            $originalName = $fileTanggapan->getClientOriginalName();
            $newPath = $fileTanggapan->store('keberatan/tanggapan', 'local');
            if (! is_string($newPath)) {
                throw new RuntimeException('Dokumen tanggapan gagal disimpan.');
            }
        }

        $finalFilePath = $newPath ?? $keberatan->file_tanggapan ?? $keberatan->file_jawaban_pembantu;
        $finalOriginalName = $originalName ?? $keberatan->nama_file_tanggapan ?? $keberatan->nama_file_jawaban_pembantu;

        DB::transaction(function () use ($keberatan, $admin, $data, $newPath, $finalTanggapan, $finalFilePath, $finalOriginalName): void {
            $oldPath = $keberatan->file_tanggapan;
            $keberatan->update([
                'hasil' => $data['hasil'],
                'jenis_tindak_lanjut' => $data['jenis_tindak_lanjut'],
                'tanggapan' => $finalTanggapan,
                'file_tanggapan' => $finalFilePath,
                'nama_file_tanggapan' => $finalOriginalName,
                'tanggal_tanggapan' => now()->toDateString(),
                'tanggal_selesai' => now()->toDateString(),
                'adminid' => $admin->id,
                'status' => Keberatan::STATUS_SELESAI,
            ]);

            if ($newPath !== null && filled($oldPath) && $oldPath !== $newPath && Storage::disk('local')->exists($oldPath)) {
                Storage::disk('local')->delete($oldPath);
            }
        });

        return $keberatan->refresh()->load(['permohonan.userPublic', 'ppidPembantu', 'admin']);
    }

    private function ensureAdminUtama(Authorization $admin): void
    {
        if (! $admin->isAdminUtama()) {
            throw new AuthorizationException('Hanya Admin Utama yang dapat meneruskan atau menyelesaikan keberatan.');
        }
    }

    private function ensureAdminPembantu(Authorization $admin): void
    {
        if (! $admin->isAdminPembantu()) {
            throw new AuthorizationException('Hanya Admin PPID Pelaksana yang dapat memberi jawaban keberatan.');
        }
    }
}
