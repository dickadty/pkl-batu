<?php

namespace App\Services;

use App\Models\Admin;
use App\Notifications\NotifikasiSistem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class NotifikasiService
{
    /**
     * Mengirim notifikasi kepada satu admin.
     */
    public static function kirimKeAdmin(
        Admin $admin,
        string $judul,
        string $pesan,
        string $jenis = 'umum',
        string $routeName = 'pages.admin.notifikasi.index',
        array $routeParams = [],
        string $icon = 'ri-notification-3-line',
        array $metadata = []
    ): void {
        $admin->notify(
            new NotifikasiSistem(
                judul: $judul,
                pesan: $pesan,
                jenis: $jenis,
                routeName: $routeName,
                routeParams: $routeParams,
                icon: $icon,
                metadata: $metadata
            )
        );
    }

    /**
     * Mengirim notifikasi kepada beberapa admin.
     */
    public static function kirimKeBanyakAdmin(
        Collection $admins,
        string $judul,
        string $pesan,
        string $jenis = 'umum',
        string $routeName = 'pages.admin.notifikasi.index',
        array $routeParams = [],
        string $icon = 'ri-notification-3-line',
        array $metadata = []
    ): void {
        if ($admins->isEmpty()) {
            return;
        }

        Notification::send(
            $admins,
            new NotifikasiSistem(
                judul: $judul,
                pesan: $pesan,
                jenis: $jenis,
                routeName: $routeName,
                routeParams: $routeParams,
                icon: $icon,
                metadata: $metadata
            )
        );
    }

    /**
     * Mengirim notifikasi kepada seluruh PPID Utama.
     *
     * Method ini memakai isAdminUtama() yang sudah tersedia
     * pada model Admin sehingga tidak bergantung pada nama
     * kolom role atau role_id.
     */
    public static function kirimKeAdminUtama(
        string $judul,
        string $pesan,
        string $jenis = 'umum',
        string $routeName = 'pages.pages.admin.notifikasi.index',
        array $routeParams = [],
        string $icon = 'ri-notification-3-line',
        array $metadata = []
    ): void {
        $adminUtama = Admin::query()
            ->get()
            ->filter(
                fn(Admin $admin): bool => $admin->isAdminUtama()
            );

        self::kirimKeBanyakAdmin(
            admins: $adminUtama,
            judul: $judul,
            pesan: $pesan,
            jenis: $jenis,
            routeName: $routeName,
            routeParams: $routeParams,
            icon: $icon,
            metadata: $metadata
        );
    }
}
