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
            ->distinct('visitor_hash')
            ->count('visitor_hash');



        // Visitor unik hari ini
        $todayVisitors = Visitor::query()
            ->whereDate(
                'visit_date',
                today()
            )
            ->count();



        // Visitor bulan ini
        $monthVisitors = Visitor::query()
            ->whereYear(
                'visit_date',
                now()->year
            )
            ->whereMonth(
                'visit_date',
                now()->month
            )
            ->count();



        // Visitor tahun ini
        $yearVisitors = Visitor::query()
            ->whereYear(
                'visit_date',
                now()->year
            )
            ->count();



        /*
        |--------------------------------------------------------------------------
        | TOTAL HIT
        |--------------------------------------------------------------------------
        */


        $totalHits = Visitor::query()
            ->sum('hits');



        /*
        |--------------------------------------------------------------------------
        | GRAFIK KUNJUNGAN 12 BULAN
        |--------------------------------------------------------------------------
        */


        $visitorChart = Visitor::query()

            ->select(

                DB::raw(
                    "DATE_FORMAT(visit_date,'%Y-%m') as bulan"
                ),

                DB::raw(
                    "COUNT(*) as visitor"
                ),

                DB::raw(
                    "SUM(hits) as hits"
                )

            )

            ->where(
                'visit_date',
                '>=',
                now()->subMonths(12)
            )

            ->groupBy('bulan')

            ->orderBy('bulan')

            ->get();



        /*
        |--------------------------------------------------------------------------
        | HALAMAN TERPOPULER
        |--------------------------------------------------------------------------
        */


        $popularPages = Visitor::query()

            ->select(

                'last_path',

                DB::raw(
                    "SUM(hits) as total"
                )

            )

            ->whereNotNull('last_path')

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

                DB::raw(
                    "COUNT(*) as visitor"
                ),

                DB::raw(
                    "SUM(hits) as hits"
                )

            )

            ->where(
                'visit_date',
                '>=',
                now()->subDays(7)
            )

            ->groupBy('visit_date')

            ->orderBy('visit_date')

            ->get();



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
