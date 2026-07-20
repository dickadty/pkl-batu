<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Notifications\NotifikasiSistem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    public function index(Request $request): View
    {
        $admin = $this->getAuthenticatedAdmin();

        $status = (string) $request->query(
            'status',
            'semua'
        );

        $availableStatuses = [
            'semua',
            'belum_dibaca',
            'sudah_dibaca',
        ];

        if (! in_array($status, $availableStatuses, true)) {
            $status = 'semua';
        }

        $perPage = (int) $request->query(
            'per_page',
            15
        );

        $availablePerPage = [
            10,
            15,
            25,
            50,
            100,
        ];

        if (! in_array($perPage, $availablePerPage, true)) {
            $perPage = 15;
        }

        $query = $admin
            ->notifications()
            ->orderByDesc('created_at');

        if ($status === 'belum_dibaca') {
            $query->whereNull('read_at');
        }

        if ($status === 'sudah_dibaca') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query
            ->paginate($perPage)
            ->withQueryString();

        $totalSemua = $admin
            ->notifications()
            ->count();

        $totalBelumDibaca = $admin
            ->notifications()
            ->whereNull('read_at')
            ->count();

        $totalSudahDibaca = $admin
            ->notifications()
            ->whereNotNull('read_at')
            ->count();

        return view(
            'pages.admin.notifikasi.index',
            compact(
                'admin',
                'notifications',
                'status',
                'totalSemua',
                'totalBelumDibaca',
                'totalSudahDibaca'
            )
        );
    }

    public function test(): RedirectResponse
    {
        $admin = $this->getAuthenticatedAdmin();

        $admin->notify(
            new NotifikasiSistem(
                judul: 'Notifikasi Percobaan',
                pesan: 'Notifikasi percobaan berhasil dikirim melalui halaman admin.',
                jenis: 'tes_notifikasi',
                routeName: 'admin.notifikasi.index',
                routeParams: [],
                icon: 'ri-flask-line',
                metadata: [
                    'admin_id' => $admin->id,
                    'username' => $admin->username,
                    'role' => $admin->role,
                    'sumber' => 'ui_admin',
                    'waktu' => now()->toDateTimeString(),
                ]
            )
        );

        return redirect()
            ->route('admin.notifikasi.index')
            ->with(
                'success',
                'Notifikasi percobaan berhasil dikirim.'
            );
    }

    public function buka(string $id): RedirectResponse
    {
        $admin = $this->getAuthenticatedAdmin();

        $notification = $this->getNotification(
            $admin,
            $id
        );

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        $data = is_array($notification->data)
            ? $notification->data
            : [];

        $routeName = data_get(
            $data,
            'route_name',
            'admin.notifikasi.index'
        );

        $routeParams = data_get(
            $data,
            'route_params',
            []
        );

        if (! is_string($routeName) || $routeName === '') {
            $routeName = 'admin.notifikasi.index';
        }

        if (! is_array($routeParams)) {
            $routeParams = [];
        }

        if (! Route::has($routeName)) {
            return redirect()
                ->route('admin.notifikasi.index')
                ->with(
                    'warning',
                    'Halaman tujuan notifikasi tidak tersedia.'
                );
        }

        return redirect()->route(
            $routeName,
            $routeParams
        );
    }

    public function tandaiDibaca(
        string $id
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $notification = $this->getNotification(
            $admin,
            $id
        );

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return back()->with(
            'success',
            'Notifikasi telah ditandai sebagai dibaca.'
        );
    }

    public function tandaiSemuaDibaca(): RedirectResponse
    {
        $admin = $this->getAuthenticatedAdmin();

        $admin
            ->notifications()
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return back()->with(
            'success',
            'Semua notifikasi telah ditandai sebagai dibaca.'
        );
    }

    public function destroy(
        string $id
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $notification = $this->getNotification(
            $admin,
            $id
        );

        $notification->delete();

        return back()->with(
            'success',
            'Notifikasi berhasil dihapus.'
        );
    }

    public function hapusSemuaDibaca(): RedirectResponse
    {
        $admin = $this->getAuthenticatedAdmin();

        $admin
            ->notifications()
            ->whereNotNull('read_at')
            ->delete();

        return back()->with(
            'success',
            'Semua notifikasi yang sudah dibaca berhasil dihapus.'
        );
    }

    private function getAuthenticatedAdmin(): Authorization
    {
        /** @var Authorization|null $admin */
        $admin = Auth::guard('admin')->user();

        abort_unless(
            $admin instanceof Authorization,
            401,
            'Sesi admin tidak ditemukan.'
        );

        return $admin;
    }

    private function getNotification(
        Authorization $admin,
        string $id
    ): DatabaseNotification {
        /** @var DatabaseNotification $notification */
        $notification = $admin
            ->notifications()
            ->whereKey($id)
            ->firstOrFail();

        return $notification;
    }
}
