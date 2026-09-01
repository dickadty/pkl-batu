<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Models\Keberatan;
use App\Models\PpidPembantu;
use App\Services\Admin\KeberatanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class KeberatanController extends Controller
{
    public function __construct(
        protected KeberatanService $keberatanService
    ) {}



    public function index(Request $request): View
    {
        $admin = auth('admin')->user();

        abort_unless(
            $admin instanceof Authorization,
            401
        );


        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        $search = trim(
            (string) $request->query('search', '')
        );


        $status = strtolower(
            trim(
                (string) $request->query('status', 'semua')
            )
        );



        /*
        |--------------------------------------------------------------------------
        | Query Data
        |--------------------------------------------------------------------------
        */

        $query = Keberatan::query()
            ->with([
                'permohonan',
                'ppidPembantu',
                'admin',
            ]);



        /*
        |--------------------------------------------------------------------------
        | Hak Akses PPID Pembantu
        |--------------------------------------------------------------------------
        */

        if (
            (int) $admin->role === 2
            && filled($admin->ppid_pembantuid)
        ) {

            $query->where(
                'ppid_pembantuid',
                (int) $admin->ppid_pembantuid
            );
        }



        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(
                function ($q) use ($search) {

                    $q
                        ->where(
                            'no_keberatan',
                            'like',
                            '%' . $search . '%'
                        )

                        ->orWhere(
                            'alasan',
                            'like',
                            '%' . $search . '%'
                        )

                        ->orWhere(
                            'tanggapan',
                            'like',
                            '%' . $search . '%'
                        );
                }
            );
        }



        /*
        |--------------------------------------------------------------------------
        | Filter Status Card
        |--------------------------------------------------------------------------
        */

        switch ($status) {


            case 'diajukan':

                $query->where(
                    'status',
                    Keberatan::STATUS_DIAJUKAN
                );

                break;



            case 'diproses':

                $query->where(
                    'status',
                    Keberatan::STATUS_DIPROSES
                );

                break;



            case 'selesai':

                $query->where(
                    'status',
                    Keberatan::STATUS_SELESAI
                );

                break;



            case 'ditolak':

                $query
                    ->where(
                        'status',
                        Keberatan::STATUS_SELESAI
                    )
                    ->where(
                        'hasil',
                        Keberatan::HASIL_DITOLAK
                    );

                break;
        }



        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $keberatans = $query
            ->orderByDesc('tanggal_pengajuan')
            ->orderByDesc('id')
            ->paginate(
                (int) $request->query(
                    'per_page',
                    15
                )
            )
            ->withQueryString();





        /*
        |--------------------------------------------------------------------------
        | Summary Card
        |--------------------------------------------------------------------------
        */

        $baseQuery = Keberatan::query();



        if (
            (int) $admin->role === 2
            && filled($admin->ppid_pembantuid)
        ) {

            $baseQuery->where(
                'ppid_pembantuid',
                (int) $admin->ppid_pembantuid
            );
        }



        $summary = [

            'semua' => (clone $baseQuery)->count(),



            'diajukan' => (clone $baseQuery)
                ->where(
                    'status',
                    Keberatan::STATUS_DIAJUKAN
                )
                ->count(),



            'diproses' => (clone $baseQuery)
                ->where(
                    'status',
                    Keberatan::STATUS_DIPROSES
                )
                ->count(),



            'selesai' => (clone $baseQuery)
                ->where(
                    'status',
                    Keberatan::STATUS_SELESAI
                )
                ->count(),



            'ditolak' => (clone $baseQuery)
                ->where(
                    'status',
                    Keberatan::STATUS_SELESAI
                )
                ->where(
                    'hasil',
                    Keberatan::HASIL_DITOLAK
                )
                ->count(),

        ];





        return view(
            'pages.admin.keberatan.index',
            [

                'keberatans' =>
                $keberatans,


                'summary' =>
                $summary,


                'ppidPembantuList' =>
                PpidPembantu::query()

                    ->when(
                        (int) $admin->role === 2,

                        fn($q) =>
                        $q->where(
                            'id',
                            (int) $admin->ppid_pembantuid
                        )

                    )

                    ->orderBy('nama')
                    ->get(),



                'search' => $search,
                'status' => $status,
                'currentStatus' => $status,



                'statusOptions' => [

                    Keberatan::STATUS_DIAJUKAN,

                    Keberatan::STATUS_DIPROSES,

                    Keberatan::STATUS_SELESAI,

                ],


            ]
        );
    }






    public function show(int $id): View
    {
        $admin = auth('admin')->user();

        abort_unless(
            $admin instanceof Authorization,
            401
        );


        $keberatan =
            $this->keberatanService
            ->getDetail(
                $id,
                $admin
            );


        return view(
            'pages.admin.keberatan.show',
            [

                'admin' =>
                $admin,


                'keberatan' =>
                $keberatan,


                'ppidPembantuList' =>
                PpidPembantu::query()
                    ->orderBy('nama')
                    ->get(),


                'hasilOptions' =>
                Keberatan::hasilOptions(),


                'tindakLanjutOptions' =>
                Keberatan::tindakLanjutOptions(),

            ]
        );
    }






    public function teruskan(
        Request $request,
        int $id
    ): RedirectResponse {

        $admin = auth('admin')->user();

        abort_unless(
            $admin instanceof Authorization,
            401
        );


        $validated = $request->validate([

            'ppid_pembantuid' => [
                'required',
                'integer',
                'exists:ppid_pembantu,id',
            ],

            'catatan_utama' => [
                'nullable',
                'string',
                'max:2000',
            ],

        ]);



        try {


            $this->keberatanService->teruskan(
                id: $id,
                admin: $admin,
                data: $validated
            );


            return redirect()
                ->route(
                    'admin.keberatan.show',
                    [
                        'id' => $id
                    ]
                )
                ->with(
                    'success',
                    'Keberatan berhasil diteruskan.'
                );
        } catch (Throwable $e) {


            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}
