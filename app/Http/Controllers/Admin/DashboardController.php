<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $stats = $this->dashboardService->getStats($admin);
        $latestDokumentasi = $this->dashboardService->getLatestDokumentasi($admin);

        return view('pages.admin.dashboard', compact(
            'admin',
            'stats',
            'latestDokumentasi'
        ));
    }
}
