<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Services\Publik\AuthService;
use App\Services\Publik\PermohonanService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermohonanController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected PermohonanService $permohonanService
    ) {}

    public function index(): View
    {
        $user = $this->authService->getLoggedUser();

        abort_unless(
            $user,
            401,
            'Sesi warga tidak ditemukan.'
        );

        $permohonan = $this->permohonanService
            ->getByUser($user);

        return view(
            'pages.public.permohonan.index',
            compact('permohonan')
        );
    }

    public function create(): View
    {
        $user = $this->authService->getLoggedUser();

        return view(
            'pages.public.permohonan.create',
            compact('user')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $this->authService->getLoggedUser();

        $rules = [
            'kategori_pemohon' => [
                'required',
                'string',
                Rule::in([
                    'Orang Perorangan',
                    'Badan Hukum',
                    'Kelompok Orang',
                ]),
            ],
            'file_surat_kuasa' => [
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
                Rule::in([
                    'Mendapatkan salinan informasi (softcopy)',
                    'Melihat, membaca, mendengarkan, atau mencatat',
                ]),
            ],
            'cara_pengiriman' => [
                'required',
                'string',
                Rule::in([
                    'E-mail',
                    'Mengambil langsung',
                    'Pos atau jasa pengiriman',
                ]),
            ],
        ];

        if ($user) {
            $rules['file_identitas'] = [
                trim((string) $user->scanktp) === ''
                    ? 'required'
                    : 'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ];
        } else {
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
                        'regex:/^\+?[0-9\s\-().]{8,20}$/',
                    ],
                    'l_kelamin' => [
                        'required',
                        Rule::in([
                            'Laki-laki',
                            'Perempuan',
                        ]),
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
                    'file_identitas' => [
                        'required',
                        'file',
                        'mimes:pdf,jpg,jpeg,png',
                        'max:5120',
                    ],
                    'persetujuan_akun' => [
                        'accepted',
                    ],
                ]
            );
        }

        $validated = $request->validate(
            $rules,
            [
                'kategori_pemohon.required' => 'Kategori pemohon wajib dipilih.',
                'kategori_pemohon.in' => 'Kategori pemohon tidak valid.',
                'nama_pemohon.required' => 'Nama pemohon wajib diisi.',
                'nomor_identitas.required' => 'NIK wajib diisi.',
                'nomor_identitas.digits' => 'NIK harus terdiri dari 16 angka.',
                'email_pemohon.required' => 'Email pemohon wajib diisi.',
                'email_pemohon.email' => 'Format email pemohon tidak valid.',
                'telp_pemohon.required' => 'Nomor telepon wajib diisi.',
                'telp_pemohon.regex' => 'Format nomor telepon tidak valid.',
                'l_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'l_kelamin.in' => 'Jenis kelamin tidak valid.',
                'tmp_lahir.required' => 'Tempat lahir wajib diisi.',
                'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
                'tgl_lahir.date' => 'Tanggal lahir tidak valid.',
                'tgl_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
                'alamat_pemohon.required' => 'Alamat pemohon wajib diisi.',
                'file_identitas.required' => 'Salinan identitas wajib diunggah.',
                'file_identitas.mimes' => 'Salinan identitas harus berupa PDF, JPG, JPEG, atau PNG.',
                'file_identitas.max' => 'Ukuran salinan identitas maksimal 5 MB.',
                'file_surat_kuasa.mimes' => 'Surat kuasa harus berupa PDF, JPG, JPEG, atau PNG.',
                'file_surat_kuasa.max' => 'Ukuran surat kuasa maksimal 5 MB.',
                'rincian.required' => 'Rincian informasi wajib diisi.',
                'tujuan.required' => 'Tujuan penggunaan informasi wajib diisi.',
                'cara_memperoleh.required' => 'Cara memperoleh informasi wajib dipilih.',
                'cara_pengiriman.required' => 'Cara pengiriman informasi wajib dipilih.',
                'persetujuan_akun.accepted' => 'Anda harus menyetujui pembuatan akun layanan PPID.',
            ]
        );

        if (! $user) {
            $accountState = $this->permohonanService
                ->inspectGuestAccount($validated);

            if ($accountState['state'] === 'active') {
                return redirect()
                    ->route('public.permohonan.create')
                    ->with(
                        'account_required',
                        'Identitas Anda sudah terdaftar dan aktif. Silakan masuk menggunakan email untuk mengajukan permohonan berikutnya.'
                    );
            }

            if ($accountState['state'] === 'pending') {
                return redirect()
                    ->route('public.aktivasi.resend.form')
                    ->with(
                        'activation_email',
                        strtolower(trim((string) $validated['email_pemohon']))
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
                        'email_pemohon' => 'Data identitas sudah terdaftar dan tidak cocok. Silakan masuk menggunakan akun yang sudah ada atau hubungi layanan PPID.',
                    ]);
            }
        }

        $permohonan = $this->permohonanService
            ->createForApplicant(
                user: $user,
                data: $validated,
                fileIdentitas: $request->file('file_identitas'),
                fileSuratKuasa: $request->file('file_surat_kuasa')
            );

        $message = $user
            ? 'Permohonan berhasil diajukan. Tanda terima dan tautan tiket telah dikirim ke email akun Anda.'
            : 'Permohonan berhasil diajukan. Akun warga otomatis dibuat dan email berisi tiket serta tautan pembuatan password telah dikirim.';

        return redirect()
            ->route('public.permohonan.show', [
                'token' => $permohonan->token,
            ])
            ->with('success', $message);
    }

    public function show(string $token): View
    {
        $permohonan = $this->permohonanService
            ->getByToken($token);

        return view(
            'pages.public.permohonan.show',
            compact('permohonan')
        );
    }
}
