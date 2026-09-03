<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Authorization;


class DownloadController extends Controller
{

    public function index(Request $request): View
    {

        $admin = $this->currentAdmin();



        /*
        |--------------------------------------------------------------------------
        | FILTER PERIODE
        |--------------------------------------------------------------------------
        */

        $periode = $request->get('periode');



        $query = Download::with([
            'dokumentasi.kategori'
        ]);



        if ($periode === 'today') {

            $query->whereDate(
                DB::raw(
                    "FROM_UNIXTIME(tanggal)"
                ),
                today()
            );
        } elseif ($periode === 'month') {


            $query->whereMonth(
                DB::raw(
                    "FROM_UNIXTIME(tanggal)"
                ),
                now()->month
            )
                ->whereYear(
                    DB::raw(
                        "FROM_UNIXTIME(tanggal)"
                    ),
                    now()->year
                );
        } elseif ($periode === 'year') {


            $query->whereYear(
                DB::raw(
                    "FROM_UNIXTIME(tanggal)"
                ),
                now()->year
            );
        }




        /*
        |--------------------------------------------------------------------------
        | TABLE DATA
        |--------------------------------------------------------------------------
        */

        $downloads = $query
            ->orderByDesc('tanggal')
            ->paginate(20)
            ->withQueryString();





        /*
        |--------------------------------------------------------------------------
        | SUMMARY CARD
        |--------------------------------------------------------------------------
        */


        $totalDownload = Download::count();



        $todayDownload = Download::whereDate(
            DB::raw(
                "FROM_UNIXTIME(tanggal)"
            ),
            today()
        )
            ->count();




        $monthDownload = Download::whereMonth(
            DB::raw(
                "FROM_UNIXTIME(tanggal)"
            ),
            now()->month
        )
            ->whereYear(
                DB::raw(
                    "FROM_UNIXTIME(tanggal)"
                ),
                now()->year
            )
            ->count();






        $yearDownload = Download::whereYear(
            DB::raw(
                "FROM_UNIXTIME(tanggal)"
            ),
            now()->year
        )
            ->count();






        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD BERDASARKAN SIFAT
        |--------------------------------------------------------------------------
        */


        $downloadBySifat = Download::query()

            ->join(
                'dokumentasi',
                'download.dokumentasiid',
                '=',
                'dokumentasi.id'
            )

            ->join(
                'kategori_informasi',
                'dokumentasi.kategori_id',
                '=',
                'kategori_informasi.id'
            )

            ->select(
                'kategori_informasi.sifat',
                DB::raw(
                    'COUNT(download.id) as total'
                )
            )

            ->groupBy(
                'kategori_informasi.sifat'
            )

            ->pluck(
                'total',
                'sifat'
            );







        /*
        |--------------------------------------------------------------------------
        | DOKUMEN TERPOPULER
        |--------------------------------------------------------------------------
        */


        $popularDocuments = Download::query()

            ->select(
                'dokumentasiid',
                DB::raw(
                    'COUNT(download.id) as total'
                )
            )

            ->with(
                'dokumentasi'
            )

            ->groupBy(
                'dokumentasiid'
            )

            ->orderByDesc(
                'total'
            )

            ->limit(5)

            ->get();








        return view(
            'pages.admin.download.index',
            compact(

                'admin',

                'downloads',

                'totalDownload',

                'todayDownload',

                'monthDownload',

                'yearDownload',

                'downloadBySifat',

                'popularDocuments'

            )
        );
    }






    private function currentAdmin(): Authorization
    {

        $admin = Auth::guard('admin')
            ->user();


        abort_unless(
            $admin instanceof Authorization,
            401
        );


        return $admin;
    }
}
