<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{

    public function index(Request $request): View
    {

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $periode = $request->input(
            'periode',
            'bulan'
        );


        $tanggal = $request->input(
            'tanggal',
            now()->format('Y-m')
        );



        /*
        |--------------------------------------------------------------------------
        | STATISTIK UTAMA
        |--------------------------------------------------------------------------
        */


        // Total visitor unik

        $totalVisitors = Visitor::query()

            ->whereNotNull('visitor_hash')

            ->distinct('visitor_hash')

            ->count('visitor_hash');




        // Visitor hari ini

        $todayVisitors = Visitor::query()

            ->whereDate(
                'visit_date',
                today()
            )

            ->whereNotNull('visitor_hash')

            ->distinct('visitor_hash')

            ->count('visitor_hash');




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

            ->whereNotNull('visitor_hash')

            ->distinct('visitor_hash')

            ->count('visitor_hash');




        // Visitor tahun ini

        $yearVisitors = Visitor::query()

            ->whereYear(
                'visit_date',
                now()->year
            )

            ->whereNotNull('visitor_hash')

            ->distinct('visitor_hash')

            ->count('visitor_hash');





        /*
        |--------------------------------------------------------------------------
        | TOTAL HIT
        |--------------------------------------------------------------------------
        */


        $totalHits = Visitor::query()

            ->sum(
                DB::raw(
                    'COALESCE(hits,1)'
                )
            );





        /*
        |--------------------------------------------------------------------------
        | GRAFIK DINAMIS
        |--------------------------------------------------------------------------
        */

        $visitorChart = collect();



        /*
        |--------------------------------------------------------------------------
        | FILTER BULAN
        |--------------------------------------------------------------------------
        */

        if ($periode === 'bulan') {


            $date = Carbon::createFromFormat(
                'Y-m',
                $tanggal
            );


            $jumlahHari = $date->daysInMonth;



            for ($i = 1; $i <= $jumlahHari; $i++) {


                $hari = $date->copy()
                    ->day($i);



                $visitorChart->push([

                    'bulan' =>
                    $hari->format('d'),


                    'visitor' =>
                    Visitor::query()

                        ->whereDate(
                            'visit_date',
                            $hari
                        )

                        ->whereNotNull(
                            'visitor_hash'
                        )

                        ->distinct(
                            'visitor_hash'
                        )

                        ->count(
                            'visitor_hash'
                        ),


                    'hits' =>
                    Visitor::query()

                        ->whereDate(
                            'visit_date',
                            $hari
                        )

                        ->sum(
                            DB::raw(
                                'COALESCE(hits,1)'
                            )
                        ),

                ]);
            }
        }





        /*
        |--------------------------------------------------------------------------
        | FILTER MINGGU
        |--------------------------------------------------------------------------
        */ elseif ($periode === 'minggu') {



            $date = Carbon::parse(
                $tanggal
            );



            $start = $date
                ->copy()
                ->startOfWeek();



            for ($i = 0; $i < 7; $i++) {


                $hari = $start
                    ->copy()
                    ->addDays($i);



                $visitorChart->push([


                    'bulan' =>
                    $hari
                        ->translatedFormat('D'),



                    'visitor' =>

                    Visitor::query()

                        ->whereDate(
                            'visit_date',
                            $hari
                        )

                        ->whereNotNull(
                            'visitor_hash'
                        )

                        ->distinct(
                            'visitor_hash'
                        )

                        ->count(
                            'visitor_hash'
                        ),



                    'hits' =>

                    Visitor::query()

                        ->whereDate(
                            'visit_date',
                            $hari
                        )

                        ->sum(
                            DB::raw(
                                'COALESCE(hits,1)'
                            )
                        ),


                ]);
            }
        }





        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN
        |--------------------------------------------------------------------------
        */ elseif ($periode === 'tahun') {



            $tahun = $tanggal;



            $visitorChart = Visitor::query()

                ->select(

                    DB::raw(
                        "MONTH(visit_date) as bulan"
                    ),


                    DB::raw(
                        "COUNT(DISTINCT visitor_hash) as visitor"
                    ),


                    DB::raw(
                        "SUM(COALESCE(hits,1)) as hits"
                    )

                )


                ->whereYear(
                    'visit_date',
                    $tahun
                )


                ->groupBy(
                    DB::raw(
                        "MONTH(visit_date)"
                    )
                )


                ->orderBy(
                    'bulan'
                )


                ->get()


                ->map(function ($item) {


                    return [

                        'bulan' =>
                        Carbon::create()
                            ->month(
                                $item->bulan
                            )
                            ->translatedFormat('M'),


                        'visitor' =>
                        $item->visitor,


                        'hits' =>
                        $item->hits,

                    ];
                });
        }





        /*
        |--------------------------------------------------------------------------
        | HALAMAN TERPOPULER
        |--------------------------------------------------------------------------
        */


        $popularPages = Visitor::query()

            ->select(
                'last_path'
            )

            ->selectRaw(
                'SUM(COALESCE(hits,1)) as total'
            )

            ->whereNotNull(
                'last_path'
            )

            ->where(
                'last_path',
                '!=',
                ''
            )

            ->groupBy(
                'last_path'
            )

            ->orderByDesc(
                'total'
            )

            ->limit(10)

            ->get();






        /*
        |--------------------------------------------------------------------------
        | 7 HARI TERAKHIR
        |--------------------------------------------------------------------------
        */


        $dailyVisitors = Visitor::query()

            ->select(
                'visit_date'
            )

            ->selectRaw(
                'COUNT(DISTINCT visitor_hash) as visitor'
            )

            ->selectRaw(
                'SUM(COALESCE(hits,1)) as hits'
            )

            ->where(
                'visit_date',
                '>=',
                now()->subDays(7)
            )

            ->groupBy(
                'visit_date'
            )

            ->orderBy(
                'visit_date'
            )

            ->get();







        return view(

            'pages.admin.visitor.index',

            [

                'stats' => [

                    'total' =>
                    $totalVisitors,


                    'today' =>
                    $todayVisitors,


                    'month' =>
                    $monthVisitors,


                    'year' =>
                    $yearVisitors,


                    'hits' =>
                    $totalHits,

                ],


                'visitorChart' =>
                $visitorChart,


                'popularPages' =>
                $popularPages,


                'dailyVisitors' =>
                $dailyVisitors,


                'periode' =>
                $periode,


                'tanggal' =>
                $tanggal,


            ]

        );
    }
}
