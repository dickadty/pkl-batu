<?php

namespace App\Services;

use App\Models\Authorization;
use App\Models\Dokumentasi;
use App\Models\PpidPembantu;
use App\Models\Permohonan;
use App\Models\Keberatan;
use App\Models\PesanMasuk;
use App\Models\Download;

class DashboardService
{
    public function getStats(Authorization $admin): array
    {
        if ($admin->isAdminUtama()) {
            return [
                'total_ppid_pembantu' => PpidPembantu::count(),
                'total_informasi' => Dokumentasi::count(),
                'informasi_menunggu' => Dokumentasi::where('is_verifikasi', 0)->count(),
                'informasi_terverifikasi' => Dokumentasi::where('is_verifikasi', 1)->count(),
                'total_permohonan' => Permohonan::count(),
                'total_keberatan' => Keberatan::count(),
                'total_pesan_masuk' => PesanMasuk::count(),
                'total_download' => Download::count(),
            ];
        }

        return [
            'total_ppid_pembantu' => 0,
            'total_informasi' => Dokumentasi::where('ppid_pembantuid', $admin->ppid_pembantuid)->count(),
            'informasi_menunggu' => Dokumentasi::where('ppid_pembantuid', $admin->ppid_pembantuid)
                ->where('is_verifikasi', 0)
                ->count(),
            'informasi_terverifikasi' => Dokumentasi::where('ppid_pembantuid', $admin->ppid_pembantuid)
                ->where('is_verifikasi', 1)
                ->count(),
            'total_permohonan' => 0,
            'total_keberatan' => 0,
            'total_pesan_masuk' => 0,
            'total_download' => 0,
        ];
    }

    public function getLatestDokumentasi(Authorization $admin)
    {
        return Dokumentasi::with('ppidPembantu')
            ->when($admin->isAdminPembantu(), function ($query) use ($admin) {
                $query->where('ppid_pembantuid', $admin->ppid_pembantuid);
            })
            ->orderByDesc('id')
            ->limit(5)
            ->get();
    }
}
