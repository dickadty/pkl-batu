<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | STATISTIK UTAMA
        |--------------------------------------------------------------------------
        */

        // Total visitor unik sepanjang waktu
        $totalVisitors = Visitor::query()
            ->whereNotNull('visitor_hash')
            ->distinct('visitor_hash')
            ->count('visitor_hash');


        // Visitor unik hari ini
        $todayVisitors = Visitor::query()
            ->whereDate('visit_date', today())
            ->whereNotNull('visitor_hash')
            ->distinct('visitor_hash')
            ->count('visitor_hash');


        // Visitor unik bulan ini
        $monthVisitors = Visitor::query()
            ->whereYear('visit_date', now()->year)
            ->whereMonth('visit_date', now()->month)
            ->whereNotNull('visitor_hash')
            ->distinct('visitor_hash')
            ->count('visitor_hash');


        // Visitor unik tahun ini
        $yearVisitors = Visitor::query()
            ->whereYear('visit_date', now()->year)
            ->whereNotNull('visitor_hash')
            ->distinct('visitor_hash')
            ->count('visitor_hash');


        /*
        |--------------------------------------------------------------------------
        | TOTAL HIT
        |--------------------------------------------------------------------------
        */

        $totalHits = Visitor::query()
            ->sum(DB::raw('COALESCE(hits, 1)'));


        /*
        |--------------------------------------------------------------------------
        | GRAFIK KUNJUNGAN 12 BULAN
        |--------------------------------------------------------------------------
        */

        $visitorChart = Visitor::query()
            ->select(
                DB::raw("DATE_FORMAT(visit_date, '%Y-%m') as bulan"),
                DB::raw("COUNT(DISTINCT visitor_hash) as visitor"),
                DB::raw("SUM(COALESCE(hits, 1)) as hits")
            )
            ->where(
                'visit_date',
                '>=',
                now()->subMonths(12)
            )
            ->groupBy(
                DB::raw("DATE_FORMAT(visit_date, '%Y-%m')")
            )
            ->orderBy('bulan')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | HALAMAN TERPOPULER
        |--------------------------------------------------------------------------
        |
        | Mengambil 10 halaman dengan jumlah hit terbanyak.
        |
        */

        $popularPages = Visitor::query()
            ->select(
                'last_path',
                DB::raw('SUM(COALESCE(hits, 1)) as total')
            )
            ->whereNotNull('last_path')
            ->where('last_path', '!=', '')
            ->groupBy('last_path')
            ->orderByDesc('total')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | KUNJUNGAN 7 HARI TERAKHIR
        |--------------------------------------------------------------------------
        */

        $dailyVisitors = Visitor::query()
            ->select(
                'visit_date',
                DB::raw("COUNT(DISTINCT visitor_hash) as visitor"),
                DB::raw("SUM(COALESCE(hits, 1)) as hits")
            )
            ->where(
                'visit_date',
                '>=',
                now()->subDays(7)
            )
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'pages.admin.visitor.index',
            [
                'stats' => [
                    'total' => $totalVisitors,
                    'today' => $todayVisitors,
                    'month' => $monthVisitors,
                    'year' => $yearVisitors,
                    'hits' => $totalHits,
                ],

                'visitorChart' => $visitorChart,

                'popularPages' => $popularPages,

                'dailyVisitors' => $dailyVisitors,
            ]
        );
    }
}
