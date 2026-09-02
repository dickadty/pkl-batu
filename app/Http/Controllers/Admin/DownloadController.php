<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Authorization;


class DownloadController extends Controller
{


    public function index(): View
    {

        $admin = $this->currentAdmin();


        $downloads = Download::with(
            'dokumentasi'
        )
            ->orderByDesc(
                'tanggal'
            )
            ->paginate(20);



        return view(
            'pages.admin.download.index',
            compact(
                'admin',
                'downloads'
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
