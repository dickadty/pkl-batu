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
    public function __construct(
        protected Keberatan $keberatan
    ) {}

    public function getDetail(
        int $id,
        Authorization $admin
    ): Keberatan {
        $this->ensureAdminUtama($admin);

        return $this->keberatan
            ->newQuery()
            ->with([
                'permohonan.userPublic',
                'permohonan.ppidPembantu',
                'admin',
            ])
            ->findOrFail($id);
    }

    public function proses(
        int $id,
        Authorization $admin
    ): Keberatan {
        $this->ensureAdminUtama($admin);

        $keberatan = $this->keberatan
            ->newQuery()
            ->findOrFail($id);

        if (! $keberatan->isDiajukan()) {
            throw new AuthorizationException(
                'Keberatan hanya dapat diproses ketika status masih Diajukan.'
            );
        }

        $keberatan->update([
            'status' => Keberatan::STATUS_DIPROSES,
            'tanggal_diproses' => now()->toDateString(),
            'adminid' => $admin->id,
        ]);

        return $keberatan->refresh();
    }

    public function selesaikan(
        int $id,
        Authorization $admin,
        array $data,
        ?UploadedFile $fileTanggapan = null
    ): Keberatan {
        $this->ensureAdminUtama($admin);

        $keberatan = $this->keberatan
            ->newQuery()
            ->findOrFail($id);

        if (! $keberatan->isDiproses()) {
            throw new AuthorizationException(
                'Keberatan harus berstatus Diproses sebelum diselesaikan.'
            );
        }

        $jenisTindakLanjut = trim(
            (string) $data['jenis_tindak_lanjut']
        );

        $requiresDocument = in_array(
            $jenisTindakLanjut,
            [
                Keberatan::TINDAK_LANJUT_DOKUMEN_TAMBAHAN,
                Keberatan::TINDAK_LANJUT_DOKUMEN_PENGGANTI,
                Keberatan::TINDAK_LANJUT_PERBAIKAN_DOKUMEN,
            ],
            true
        );

        if (
            $requiresDocument
            && ! $fileTanggapan instanceof UploadedFile
            && empty($keberatan->file_tanggapan)
        ) {
            throw new RuntimeException(
                'Dokumen tanggapan wajib diunggah untuk jenis tindak lanjut ini.'
            );
        }

        $newPath = null;
        $originalName = null;

        if ($fileTanggapan instanceof UploadedFile) {
            $originalName = $fileTanggapan
                ->getClientOriginalName();

            $newPath = $fileTanggapan->store(
                'keberatan/tanggapan',
                'local'
            );

            if (! is_string($newPath)) {
                throw new RuntimeException(
                    'Dokumen tanggapan gagal disimpan.'
                );
            }
        }

        DB::transaction(function () use (
            $keberatan,
            $admin,
            $data,
            $newPath,
            $originalName
        ): void {
            $oldPath = $keberatan->file_tanggapan;

            $keberatan->update([
                'hasil' => $data['hasil'],
                'jenis_tindak_lanjut' =>
                $data['jenis_tindak_lanjut'],
                'tanggapan' => $data['tanggapan'],

                'file_tanggapan' =>
                $newPath
                    ?? $keberatan->file_tanggapan,

                'nama_file_tanggapan' =>
                $originalName
                    ?? $keberatan->nama_file_tanggapan,

                'tanggal_tanggapan' =>
                now()->toDateString(),

                'tanggal_selesai' =>
                now()->toDateString(),

                'adminid' => $admin->id,
                'status' => Keberatan::STATUS_SELESAI,
            ]);

            if (
                $newPath !== null
                && filled($oldPath)
                && $oldPath !== $newPath
                && Storage::disk('local')->exists($oldPath)
            ) {
                Storage::disk('local')->delete($oldPath);
            }
        });

        return $keberatan
            ->refresh()
            ->load([
                'permohonan.userPublic',
                'admin',
            ]);
    }

    private function ensureAdminUtama(
        Authorization $admin
    ): void {
        if ((int) $admin->role !== 1) {
            throw new AuthorizationException(
                'Hanya Admin Utama yang dapat memproses keberatan.'
            );
        }
    }
}
