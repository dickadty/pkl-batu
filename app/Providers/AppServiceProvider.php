<?php

namespace App\Providers;

use App\Models\Authorization;
use App\Services\SidebarService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(
        SidebarService $sidebarService
    ): void {
        View::composer(
            'components.admin.sidebar',
            function ($view) use ($sidebarService): void {
                /** @var Authorization|null $admin */
                $admin = Auth::guard('admin')->user();

                $sidebarData = [
                    'admin' => $admin,
                    'roleLabel' => 'Admin',
                    'totalNotifikasiAdminUtama' => 0,
                    'unreadNotificationCount' => 0,
                ];

                if ($admin instanceof Authorization) {
                    $serviceData = $sidebarService
                        ->getAdminSidebarData($admin);

                    $sidebarData = array_merge(
                        $sidebarData,
                        $serviceData
                    );

                    $sidebarData['admin'] = $admin;

                    $sidebarData['unreadNotificationCount'] = (int) $admin
                        ->notifications()
                        ->whereNull('read_at')
                        ->count();
                }

                $view->with($sidebarData);
            }
        );
    }
}
