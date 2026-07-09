<?php

namespace App\Services;

use App\Models\Authorization;
use App\Models\Permohonan;
use App\Models\PesanMasuk;
use Illuminate\Routing\Router;

class SidebarService
{
    public function __construct(
        protected Permohonan $permohonan,
        protected PesanMasuk $pesanMasuk,
        protected Router $router
    ) {}

    public function getAdminSidebarData(?Authorization $admin): array
    {
        $role = (int) ($admin->role ?? 0);

        $isAdminUtama = $role === 1;
        $isAdminPembantu = $role === 2;

        $jumlahPermohonanBaru = 0;
        $jumlahValidasiMasuk = 0;
        $jumlahPermohonanPembantu = 0;
        $jumlahPesanMasukBaru = 0;

        if ($admin && $isAdminUtama) {
            $jumlahPermohonanBaru = $this->countPermohonanBaruAdminUtama();
            $jumlahValidasiMasuk = $this->countPermohonanMenungguValidasi();
            $jumlahPesanMasukBaru = $this->countPesanMasukBaru();
        }

        if ($admin && $isAdminPembantu) {
            $jumlahPermohonanPembantu = $this->countPermohonanMasukAdminPembantu(
                $admin->ppid_pembantuid
            );
        }

        return [
            'admin' => $admin,
            'role' => $role,
            'isAdminUtama' => $isAdminUtama,
            'isAdminPembantu' => $isAdminPembantu,
            'roleLabel' => $this->getRoleLabel($role),

            'jumlahPermohonanBaru' => $jumlahPermohonanBaru,
            'jumlahValidasiMasuk' => $jumlahValidasiMasuk,
            'jumlahPermohonanPembantu' => $jumlahPermohonanPembantu,
            'jumlahPesanMasukBaru' => $jumlahPesanMasukBaru,

            'totalNotifikasiAdminUtama' => $jumlahPermohonanBaru + $jumlahValidasiMasuk,

            'hasPpidPembantuRoute' => $this->router->has('admin.ppid-pembantu.index'),
            'hasAkunAdminRoute' => $this->router->has('admin.akun-admin.create'),
            'hasPejabatRoute' => $this->router->has('admin.pejabat.index'),
            'hasSliderRoute' => $this->router->has('admin.slider.index'),
            'hasInformasiPublikRoute' => $this->router->has('admin.informasi-publik.index'),
            'hasBeritaRoute' => $this->router->has('admin.berita.index'),
            'hasPermohonanRoute' => $this->router->has('admin.permohonan.index'),
            'hasPesanMasukRoute' => $this->router->has('admin.pesan-masuk.index'),
            'hasFaqRoute' => $this->router->has('admin.faq.index'),
        ];
    }

    private function getRoleLabel(int $role): string
    {
        return match ($role) {
            1 => 'PPID Utama',
            2 => 'PPID Pembantu',
            default => 'Admin PPID',
        };
    }

    private function countPermohonanBaruAdminUtama(): int
    {
        return $this->permohonan
            ->newQuery()
            ->whereIn('status', [
                'Diajukan',
                'Diproses',
            ])
            ->count();
    }

    private function countPermohonanMenungguValidasi(): int
    {
        return $this->permohonan
            ->newQuery()
            ->where('status', 'Menunggu Validasi Admin Utama')
            ->count();
    }

    private function countPermohonanMasukAdminPembantu(?int $ppidPembantuId): int
    {
        if (! $ppidPembantuId) {
            return 0;
        }

        return $this->permohonan
            ->newQuery()
            ->where('ppid_pembantuid', $ppidPembantuId)
            ->whereIn('status', [
                'Diteruskan ke PPID Pembantu',
                'Revisi PPID Pembantu',
            ])
            ->count();
    }

    private function countPesanMasukBaru(): int
    {
        return $this->pesanMasuk
            ->newQuery()
            ->where('status', PesanMasuk::STATUS_BARU)
            ->count();
    }
}
