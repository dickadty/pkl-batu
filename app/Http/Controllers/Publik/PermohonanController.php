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
     * Menampilkan riwayat permohonan milik warga.
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
     * Menampilkan formulir pengajuan permohonan.
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
     * Menyimpan pengajuan permohonan informasi.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $user = $this->authService
            ->getLoggedUser();

        /*
        |--------------------------------------------------------------------------
        | Aturan Validasi Umum
        |--------------------------------------------------------------------------
        |
        | KTP dan surat kuasa selalu wajib, baik untuk warga yang sudah login
        | maupun untuk pemohon baru tanpa login.
        |
        */

        $rules = [
            'kategori_pemohon' => [
                'bail',
                'required',
                'string',
                Rule::in([
                    'Orang Perorangan',
                    'Badan Hukum',
                    'Kelompok Orang',
                ]),
            ],

            'file_identitas' => [
                'bail',
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'file_surat_kuasa' => [
                'bail',
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'rincian' => [
                'bail',
                'required',
                'string',
                'max:500',
            ],

            'tujuan' => [
                'bail',
                'required',
                'string',
                'max:500',
            ],

            'cara_memperoleh' => [
                'bail',
                'required',
                'string',
                Rule::in([
                    'Mendapatkan salinan informasi (softcopy)',
                    'Melihat, membaca, mendengarkan, atau mencatat',
                ]),
            ],

            'cara_pengiriman' => [
                'bail',
                'required',
                'string',
                Rule::in([
                    'E-mail',
                    'Mengambil langsung',
                    'Pos atau jasa pengiriman',
                ]),
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Aturan Tambahan untuk Pengajuan Tanpa Login
        |--------------------------------------------------------------------------
        */

        if (! $user) {
            $rules = array_merge(
                $rules,
                [
                    'nama_pemohon' => [
                        'bail',
                        'required',
                        'string',
                        'max:100',
                    ],

                    'nomor_identitas' => [
                        'bail',
                        'required',
                        'digits:16',
                    ],

                    'email_pemohon' => [
                        'bail',
                        'required',
                        'email:rfc',
                        'max:100',
                    ],

                    'telp_pemohon' => [
                        'bail',
                        'required',
                        'string',
                        'max:20',
                        'regex:/^\+?[0-9\s\-().]{8,20}$/',
                    ],

                    'l_kelamin' => [
                        'bail',
                        'required',
                        Rule::in([
                            'Laki-laki',
                            'Perempuan',
                        ]),
                    ],

                    'tmp_lahir' => [
                        'bail',
                        'required',
                        'string',
                        'max:50',
                    ],

                    'tgl_lahir' => [
                        'bail',
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
                        'bail',
                        'required',
                        'string',
                        'max:500',
                    ],

                    'desa_kel' => [
                        'nullable',
                        'string',
                        'max:50',
                    ],

                    'kecamatan' => [
                        'nullable',
                        'string',
                        'max:50',
                    ],

                    'kota_kab' => [
                        'nullable',
                        'string',
                        'max:50',
                    ],

                    'provinsi' => [
                        'nullable',
                        'string',
                        'max:50',
                    ],

                    'persetujuan_akun' => [
                        'bail',
                        'required',
                        'accepted',
                    ],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Proses Validasi
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            $rules,
            [
                'kategori_pemohon.required' =>
                'Kategori pemohon wajib dipilih.',

                'kategori_pemohon.string' =>
                'Kategori pemohon harus berupa teks.',

                'kategori_pemohon.in' =>
                'Kategori pemohon tidak valid.',

                'nama_pemohon.required' =>
                'Nama pemohon wajib diisi.',

                'nama_pemohon.string' =>
                'Nama pemohon harus berupa teks.',

                'nama_pemohon.max' =>
                'Nama pemohon maksimal 100 karakter.',

                'nomor_identitas.required' =>
                'NIK wajib diisi.',

                'nomor_identitas.digits' =>
                'NIK harus terdiri dari 16 angka.',

                'email_pemohon.required' =>
                'Email pemohon wajib diisi.',

                'email_pemohon.email' =>
                'Format email pemohon tidak valid.',

                'email_pemohon.max' =>
                'Email pemohon maksimal 100 karakter.',

                'telp_pemohon.required' =>
                'Nomor telepon wajib diisi.',

                'telp_pemohon.string' =>
                'Nomor telepon harus berupa teks.',

                'telp_pemohon.max' =>
                'Nomor telepon maksimal 20 karakter.',

                'telp_pemohon.regex' =>
                'Format nomor telepon tidak valid.',

                'l_kelamin.required' =>
                'Jenis kelamin wajib dipilih.',

                'l_kelamin.in' =>
                'Jenis kelamin tidak valid.',

                'tmp_lahir.required' =>
                'Tempat lahir wajib diisi.',

                'tmp_lahir.string' =>
                'Tempat lahir harus berupa teks.',

                'tmp_lahir.max' =>
                'Tempat lahir maksimal 50 karakter.',

                'tgl_lahir.required' =>
                'Tanggal lahir wajib diisi.',

                'tgl_lahir.date' =>
                'Tanggal lahir tidak valid.',

                'tgl_lahir.before' =>
                'Tanggal lahir harus sebelum hari ini.',

                'pekerjaan_pemohon.string' =>
                'Pekerjaan harus berupa teks.',

                'pekerjaan_pemohon.max' =>
                'Pekerjaan maksimal 50 karakter.',

                'alamat_pemohon.required' =>
                'Alamat pemohon wajib diisi.',

                'alamat_pemohon.string' =>
                'Alamat pemohon harus berupa teks.',

                'alamat_pemohon.max' =>
                'Alamat pemohon maksimal 500 karakter.',

                /*
                 * Pesan validasi KTP.
                 */
                'file_identitas.required' =>
                'KTP wajib diunggah.',

                'file_identitas.file' =>
                'File KTP tidak valid.',

                'file_identitas.mimes' =>
                'KTP harus berupa PDF, JPG, JPEG, atau PNG.',

                'file_identitas.max' =>
                'Ukuran KTP maksimal 5 MB.',

                /*
                 * Pesan validasi surat kuasa.
                 */
                'file_surat_kuasa.required' =>
                'Surat kuasa wajib diunggah.',

                'file_surat_kuasa.file' =>
                'File surat kuasa tidak valid.',

                'file_surat_kuasa.mimes' =>
                'Surat kuasa harus berupa PDF, JPG, JPEG, atau PNG.',

                'file_surat_kuasa.max' =>
                'Ukuran surat kuasa maksimal 5 MB.',

                'rincian.required' =>
                'Rincian informasi wajib diisi.',

                'rincian.string' =>
                'Rincian informasi harus berupa teks.',

                'rincian.max' =>
                'Rincian informasi maksimal 500 karakter.',

                'tujuan.required' =>
                'Tujuan penggunaan informasi wajib diisi.',

                'tujuan.string' =>
                'Tujuan penggunaan informasi harus berupa teks.',

                'tujuan.max' =>
                'Tujuan penggunaan informasi maksimal 500 karakter.',

                'cara_memperoleh.required' =>
                'Cara memperoleh informasi wajib dipilih.',

                'cara_memperoleh.in' =>
                'Cara memperoleh informasi tidak valid.',

                'cara_pengiriman.required' =>
                'Cara pengiriman informasi wajib dipilih.',

                'cara_pengiriman.in' =>
                'Cara pengiriman informasi tidak valid.',

                'persetujuan_akun.required' =>
                'Anda harus menyetujui pembuatan akun layanan PPID.',

                'persetujuan_akun.accepted' =>
                'Anda harus menyetujui pembuatan akun layanan PPID.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Pastikan File Benar-Benar Tersedia
        |--------------------------------------------------------------------------
        |
        | Validasi Laravel seharusnya sudah menjamin file tersedia. Pemeriksaan
        | ini dipertahankan agar tipe data yang dikirim ke service selalu benar.
        |
        */

        $fileIdentitas = $request->file(
            'file_identitas'
        );

        if (! $fileIdentitas instanceof UploadedFile) {
            throw ValidationException::withMessages([
                'file_identitas' =>
                'KTP wajib diunggah.',
            ]);
        }

        $fileSuratKuasa = $request->file(
            'file_surat_kuasa'
        );

        if (! $fileSuratKuasa instanceof UploadedFile) {
            throw ValidationException::withMessages([
                'file_surat_kuasa' =>
                'Surat kuasa wajib diunggah.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pemeriksaan Akun untuk Pemohon Tanpa Login
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
                        'Identitas Anda sudah terdaftar dan aktif. Silakan masuk menggunakan email untuk mengajukan permohonan berikutnya.'
                    );
            }

            if ($accountState['state'] === 'pending') {
                return redirect()
                    ->route(
                        'public.aktivasi.resend.form'
                    )
                    ->with(
                        'activation_email',
                        strtolower(
                            trim(
                                (string) $validated['email_pemohon']
                            )
                        )
                    )
                    ->with(
                        'warning',
                        'Akun Anda sudah dibuat tetapi belum aktif. Buat password melalui tautan aktivasi atau kirim ulang email aktivasi.'
                    );
            }

            if ($accountState['state'] === 'conflict') {
                return back()
                    ->withInput(
                        $request->except([
                            'file_identitas',
                            'file_surat_kuasa',
                        ])
                    )
                    ->withErrors([
                        'email_pemohon' =>
                        'Data identitas sudah terdaftar dan tidak cocok. Silakan masuk menggunakan akun yang sudah ada atau hubungi layanan PPID.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Membuat Permohonan
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

        $message = $user
            ? 'Permohonan berhasil diajukan. Tanda terima dan tautan tiket telah dikirim ke email akun Anda.'
            : 'Permohonan berhasil diajukan. Akun warga otomatis dibuat dan email berisi tiket serta tautan pembuatan password telah dikirim.';

        return redirect()
            ->route(
                'public.permohonan.show',
                [
                    'token' => $permohonan->token,
                ]
            )
            ->with(
                'success',
                $message
            );
    }

    /**
     * Menampilkan detail permohonan berdasarkan token publik.
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
