<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifikasiSistem extends Notification
{
    use Queueable;

    public function __construct(
        public string $judul,
        public string $pesan,
        public string $jenis = 'umum',
        public string $routeName = 'admin.notifikasi.index',
        public array $routeParams = [],
        public string $icon = 'ri-notification-3-line',
        public array $metadata = []
    ) {}

    /**
     * Menentukan media pengiriman notifikasi.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data yang disimpan pada kolom data di tabel notifications.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'judul' => $this->judul,
            'pesan' => $this->pesan,
            'jenis' => $this->jenis,
            'route_name' => $this->routeName,
            'route_params' => $this->routeParams,
            'icon' => $this->icon,
            'metadata' => $this->metadata,
        ];
    }

    /**
     * Representasi array notifikasi.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
