<?php

namespace App\Services;

use App\Models\Authorization;
use App\Models\Dokumentasi;
use App\Models\Download;
use App\Models\Keberatan;
use App\Models\Permohonan;
use App\Models\PesanMasuk;
use App\Models\PpidPembantu;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    public function __construct(
        protected PpidPembantu $ppidPembantu,
        protected Dokumentasi $dokumentasi,
        protected Permohonan $permohonan,
        protected Keberatan $keberatan,
        protected PesanMasuk $pesanMasuk,
        protected Download $download
    ) {}

    public function getStats(Authorization $admin): array
    {
        if ($this->isAdminUtama($admin)) {
            return $this->getAdminUtamaStats();
        }

        return $this->getAdminPembantuStats($admin);
    }

    public function getLatestDokumentasi(Authorization $admin): Collection
    {
        return $this->dokumentasi
            ->newQuery()
            ->with('ppidPembantu')
            ->when($this->isAdminPembantu($admin), function ($query) use ($admin) {
                $query->where('ppid_pembantuid', $admin->ppid_pembantuid);
            })
            ->orderByDesc('id')
            ->limit(5)
            ->get();
    }

    private function getAdminUtamaStats(): array
    {
        return [
            'total_ppid_pembantu' => $this->ppidPembantu
                ->newQuery()
                ->count(),

            'total_informasi' => $this->dokumentasi
                ->newQuery()
                ->count(),

            'informasi_menunggu' => $this->dokumentasi
                ->newQuery()
                ->where('is_verifikasi', 0)
                ->count(),

            'informasi_terverifikasi' => $this->dokumentasi
                ->newQuery()
                ->where('is_verifikasi', 1)
                ->count(),

            'total_permohonan' => $this->permohonan
                ->newQuery()
                ->count(),

            'total_keberatan' => $this->keberatan
                ->newQuery()
                ->count(),

            'total_pesan_masuk' => $this->pesanMasuk
                ->newQuery()
                ->count(),

            'total_download' => $this->download
                ->newQuery()
                ->count(),
        ];
    }

    private function getAdminPembantuStats(Authorization $admin): array
    {
        return [
            'total_ppid_pembantu' => 0,

            'total_informasi' => $this->dokumentasi
                ->newQuery()
                ->where('ppid_pembantuid', $admin->ppid_pembantuid)
                ->count(),

            'informasi_menunggu' => $this->dokumentasi
                ->newQuery()
                ->where('ppid_pembantuid', $admin->ppid_pembantuid)
                ->where('is_verifikasi', 0)
                ->count(),

            'informasi_terverifikasi' => $this->dokumentasi
                ->newQuery()
                ->where('ppid_pembantuid', $admin->ppid_pembantuid)
                ->where('is_verifikasi', 1)
                ->count(),

            'total_permohonan' => 0,

            'total_keberatan' => 0,

            'total_pesan_masuk' => 0,

            'total_download' => 0,
        ];
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
