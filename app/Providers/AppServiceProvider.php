<?php

namespace App\Providers;

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
    public function boot(SidebarService $sidebarService): void
    {
        View::composer('components.admin.sidebar', function ($view) use ($sidebarService) {

            $admin = Auth::guard('admin')->user();

            $view->with(
                $sidebarService->getAdminSidebarData($admin)
            );
        });
    }
}
