<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Services\Publik\AuthService;
use App\Services\Publik\PermohonanService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class PermohonanController extends Controller
{

    public function __construct(
        protected AuthService $authService,
        protected PermohonanService $permohonanService
    ) {}



    /**
     * Riwayat permohonan warga
     */
    public function index(): View
    {

        $user = $this->authService
            ->getLoggedUser();


        abort_unless(
            $user,
            401,
            'Sesi warga tidak ditemukan.'
        );


        $permohonan = $this
            ->permohonanService
            ->getByUser($user);



        return view(
            'pages.public.permohonan.index',
            [
                'permohonan' => $permohonan,
            ]
        );
    }




    /**
     * Form pengajuan
     */
    public function create(): View
    {

        $user = $this->authService
            ->getLoggedUser();



        return view(
            'pages.public.permohonan.create',
            [
                'user' => $user,
            ]
        );
    }





    /**
     * Simpan permohonan
     */
    public function store(
        Request $request
    ): RedirectResponse {


        $user = $this->authService
            ->getLoggedUser();



        /*
        |--------------------------------------------------------------------------
        | VALIDASI DASAR
        |--------------------------------------------------------------------------
        |
        | KTP wajib semua pemohon.
        | Surat kuasa hanya wajib untuk:
        | - Badan Hukum
        | - Kelompok Orang
        |
        */


        $rules = [

            'kategori_pemohon' => [

                'bail',
                'required',
                'string',

                Rule::in(
                    [
                        'Orang Perorangan',
                        'Badan Hukum',
                        'Kelompok Orang',
                    ]
                ),

            ],

            'file_identitas' => [

                'bail',
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',

            ],

            /*
             * Default nullable
             * karena Orang Perorangan tidak wajib
             */
            'file_surat_kuasa' => [

                'bail',
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',

            ],

            'rincian' => [

                'required',
                'string',
                'max:500',

            ],



            'tujuan' => [

                'required',
                'string',
                'max:500',

            ],



            'cara_memperoleh' => [

                'required',
                'string',

            ],



            'cara_pengiriman' => [

                'required',
                'string',

            ],

        ];







        /*
        |--------------------------------------------------------------------------
        | DATA PEMOHON TANPA LOGIN
        |--------------------------------------------------------------------------
        */


        if (! $user) {


            $rules = array_merge(

                $rules,

                [


                    'nama_pemohon' => [

                        'required',
                        'string',
                        'max:100',

                    ],



                    'nomor_identitas' => [

                        'required',
                        'digits:16',

                    ],



                    'email_pemohon' => [

                        'required',
                        'email:rfc',
                        'max:100',

                    ],



                    'telp_pemohon' => [

                        'required',
                        'string',
                        'max:20',

                    ],



                    'l_kelamin' => [

                        'required',
                        Rule::in(
                            [
                                'Laki-laki',
                                'Perempuan'
                            ]
                        ),

                    ],



                    'tmp_lahir' => [

                        'required',
                        'string',
                        'max:50',

                    ],



                    'tgl_lahir' => [

                        'required',
                        'date',
                        'before:today',

                    ],



                    'pekerjaan_pemohon' => [

                        'nullable',
                        'string',
                        'max:50',

                    ],



                    'alamat_pemohon' => [

                        'required',
                        'string',
                        'max:500',

                    ],



                    'persetujuan_akun' => [

                        'required',
                        'accepted',

                    ],

                ]

            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI INPUT
        |--------------------------------------------------------------------------
        */


        $validated = $request->validate(
            $rules
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI SURAT KUASA BERDASARKAN KATEGORI
        |--------------------------------------------------------------------------
        */


        $fileSuratKuasa = $request
            ->file('file_surat_kuasa');



        $kategori = $validated['kategori_pemohon'];



        if (

            in_array(
                $kategori,
                [
                    'Badan Hukum',
                    'Kelompok Orang'
                ]
            )

            &&

            ! $fileSuratKuasa instanceof UploadedFile

        ) {


            throw ValidationException::withMessages([

                'file_surat_kuasa' =>

                'Surat kuasa wajib diunggah untuk kategori ' . $kategori . '.',

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI FILE IDENTITAS
        |--------------------------------------------------------------------------
        */


        $fileIdentitas = $request
            ->file('file_identitas');



        if (! $fileIdentitas instanceof UploadedFile) {


            throw ValidationException::withMessages([

                'file_identitas' =>

                'KTP wajib diunggah.',

            ]);
        }








        /*
        |--------------------------------------------------------------------------
        | CEK AKUN WARGA
        |--------------------------------------------------------------------------
        */


        if (! $user) {


            $accountState = $this
                ->permohonanService
                ->inspectGuestAccount(
                    $validated
                );



            if ($accountState['state'] === 'active') {


                return redirect()

                    ->route(
                        'public.permohonan.create'
                    )

                    ->with(
                        'account_required',
                        'Identitas sudah terdaftar. Silakan masuk menggunakan akun warga.'
                    );
            }



            if ($accountState['state'] === 'pending') {


                return redirect()

                    ->route(
                        'public.aktivasi.resend.form'
                    )

                    ->with(
                        'activation_email',
                        $validated['email_pemohon']
                    );
            }
        }







        /*
        |--------------------------------------------------------------------------
        | SIMPAN PERMOHONAN
        |--------------------------------------------------------------------------
        */


        $permohonan = $this
            ->permohonanService
            ->createForApplicant(

                user: $user,

                data: $validated,

                fileIdentitas: $fileIdentitas,

                fileSuratKuasa: $fileSuratKuasa

            );







        return redirect()

            ->route(

                'public.permohonan.show',

                [

                    'token' => $permohonan->token,

                ]

            )

            ->with(

                'success',

                'Permohonan berhasil diajukan.'

            );
    }







    /**
     * Detail permohonan
     */
    public function show(
        string $token
    ): View {


        $permohonan = $this
            ->permohonanService
            ->getByToken($token);



        return view(

            'pages.public.permohonan.show',

            [

                'permohonan' => $permohonan,

            ]

        );
    }
}
