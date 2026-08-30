<?php

namespace App\Providers;

use App\Models\Authorization;
use App\Models\Survey;
use App\Services\SidebarService;
use App\View\Components\MenuComposer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(
        SidebarService $sidebarService
    ): void {
        View::composer(
            'components.admin.sidebar',
            function ($view) use ($sidebarService): void {
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

        View::composer(
            'components.public.navbar',
            MenuComposer::class
        );

        $today = now()->toDateString();

        $monthStart = now()
            ->copy()
            ->startOfMonth()
            ->toDateString();

        $monthEnd = now()
            ->copy()
            ->endOfMonth()
            ->toDateString();

        $yearStart = now()
            ->copy()
            ->startOfYear()
            ->toDateString();

        $yearEnd = now()
            ->copy()
            ->endOfYear()
            ->toDateString();

        $todayHits = (int) DB::table('visitor_visits')
            ->where('visit_date', $today)
            ->sum('hits');

        $todayVisitors = (int) DB::table('visitor_visits')
            ->where('visit_date', $today)
            ->distinct()
            ->count('visitor_hash');

        $monthHits = (int) DB::table('visitor_visits')
            ->whereBetween(
                'visit_date',
                [
                    $monthStart,
                    $monthEnd,
                ]
            )
            ->sum('hits');

        $monthVisitors = (int) DB::table('visitor_visits')
            ->whereBetween(
                'visit_date',
                [
                    $monthStart,
                    $monthEnd,
                ]
            )
            ->distinct()
            ->count('visitor_hash');

        $yearHits = (int) DB::table('visitor_visits')
            ->whereBetween(
                'visit_date',
                [
                    $yearStart,
                    $yearEnd,
                ]
            )
            ->sum('hits');

        $yearVisitors = (int) DB::table('visitor_visits')
            ->whereBetween(
                'visit_date',
                [
                    $yearStart,
                    $yearEnd,
                ]
            )
            ->distinct()
            ->count('visitor_hash');

        $totalHits = (int) DB::table('visitor_visits')
            ->sum('hits');

        $totalVisitors = (int) DB::table('visitor_visits')
            ->distinct()
            ->count('visitor_hash');

        View::share(
            'visitorStats',
            [
                'today_hits' => $todayHits,
                'today_visitors' => $todayVisitors,

                'month_hits' => $monthHits,
                'month_visitors' => $monthVisitors,

                'year_hits' => $yearHits,
                'year_visitors' => $yearVisitors,

                'total_hits' => $totalHits,
                'total_visitors' => $totalVisitors,
            ]
        );

        View::composer(
            'components.public.sections.survey',
            function ($view): void {
                $summary = Survey::query()
                    ->whereBetween(
                        'rating',
                        [1, 5]
                    )
                    ->selectRaw(
                        'COUNT(*) AS total, COALESCE(AVG(rating), 0) AS average'
                    )
                    ->first();

                $totalSurvey = (int) (
                    $summary->total ?? 0
                );

                $averageRating = round(
                    (float) (
                        $summary->average ?? 0
                    ),
                    1
                );

                $distribution = Survey::query()
                    ->whereBetween(
                        'rating',
                        [1, 5]
                    )
                    ->selectRaw(
                        'rating, COUNT(*) AS total'
                    )
                    ->groupBy('rating')
                    ->orderBy('rating')
                    ->pluck(
                        'total',
                        'rating'
                    );

                $ratingCounts = [];
                $ratingPercentages = [];

                for ($rating = 1; $rating <= 5; $rating++) {
                    $count = (int) $distribution->get(
                        $rating,
                        0
                    );

                    $ratingCounts[$rating] = $count;

                    $ratingPercentages[$rating] =
                        $totalSurvey > 0
                        ? round(
                            ($count / $totalSurvey) * 100,
                            1
                        )
                        : 0;
                }

                $view->with(
                    'surveyStats',
                    [
                        'average' => $averageRating,
                        'total' => $totalSurvey,
                        'counts' => $ratingCounts,
                        'percentages' => $ratingPercentages,
                    ]
                );
            }
        );
    }
}
