<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Services\Admin\PermohonanService;
use Illuminate\Contracts\View\View;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class PermohonanController extends Controller
{
    public function __construct(
        protected PermohonanService $permohonanService
    ) {}

    /**
     * Menampilkan daftar permohonan dan card ringkasan status.
     */
    public function index(Request $request): View
    {
        $admin = $this->getAuthenticatedAdmin();

        $validated = $request->validate(
            [
                'q' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'status' => [
                    'nullable',
                    'string',
                    Rule::in(
                        array_keys(
                            PermohonanService::STATUS_FILTERS
                        )
                    ),
                ],

                'ppid_pembantuid' => [
                    'nullable',
                    'integer',
                    Rule::exists(
                        'ppid_pembantu',
                        'id'
                    ),
                ],
            ],
            [
                'q.string' =>
                'Kata pencarian harus berupa teks.',

                'q.max' =>
                'Kata pencarian maksimal 255 karakter.',

                'status.in' =>
                'Filter status permohonan tidak valid.',

                'ppid_pembantuid.integer' =>
                'PPID Pembantu tidak valid.',

                'ppid_pembantuid.exists' =>
                'PPID Pembantu yang dipilih tidak ditemukan.',
            ]
        );

        $filters = [
            'q' => trim(
                (string) ($validated['q'] ?? '')
            ),

            'status' => (string) (
                $validated['status'] ?? 'semua'
            ),

            'ppid_pembantuid' =>
            $validated['ppid_pembantuid']
                ?? null,
        ];

        $permohonan = $this
            ->permohonanService
            ->getForAdmin(
                $admin,
                $filters
            );

        $summary = $this
            ->permohonanService
            ->getSummaryForAdmin(
                $admin,
                $filters
            );

        $ppidPembantuList = $this
            ->permohonanService
            ->getPpidPembantuList(
                $admin
            );

        return view(
            'pages.admin.permohonan.index',
            [
                'admin' => $admin,
                'permohonan' => $permohonan,
                'ppidPembantuList' => $ppidPembantuList,
                'currentStatus' => $filters['status'],
                'filters' => $filters,

                'totalSemua' => $summary['semua'],
                'totalDiajukan' => $summary['diajukan'],
                'totalDiproses' => $summary['diproses'],
                'totalDiteruskan' => $summary['diteruskan'],
                'totalMenungguValidasi' =>
                $summary['menunggu_validasi'],
                'totalRevisi' => $summary['revisi'],
                'totalSelesai' => $summary['selesai'],
                'totalDitolak' => $summary['ditolak'],
            ]
        );
    }

    /**
     * Menampilkan detail permohonan.
     */
    public function show(
        int $id
    ): View {
        $admin = $this->getAuthenticatedAdmin();

        $permohonan = $this
            ->permohonanService
            ->getDetailForAdmin(
                $id,
                $admin
            );

        $ppidPembantu = $this
            ->permohonanService
            ->getPpidPembantuList(
                $admin
            );

        return view(
            'pages.admin.permohonan.show',
            [
                'permohonan' => $permohonan,
                'admin' => $admin,
                'ppidPembantu' => $ppidPembantu,
            ]
        );
    }

    /**
     * Menampilkan dokumen identitas pemohon.
     */
    public function dokumen(
        int $id,
        string $jenis
    ): BinaryFileResponse {
        $admin = $this->getAuthenticatedAdmin();

        $permohonan = $this
            ->permohonanService
            ->getDetailForAdmin(
                $id,
                $admin
            );

        $path = match ($jenis) {
            'identitas' =>
            $permohonan->file_identitas,

            'surat-kuasa' =>
            $permohonan->file_surat_kuasa,

            default => null,
        };

        abort_if(
            empty($path),
            404,
            'Dokumen tidak ditemukan.'
        );

        /**
         * @var FilesystemAdapter $disk
         */
        $disk = Storage::disk('local');

        abort_unless(
            $disk->exists($path),
            404,
            'File dokumen tidak ditemukan pada penyimpanan.'
        );

        $absolutePath = $disk->path($path);

        abort_unless(
            is_file($absolutePath),
            404,
            'File dokumen tidak ditemukan pada server.'
        );

        $mimeType = null;

        if (function_exists('mime_content_type')) {
            $detectedMimeType = mime_content_type(
                $absolutePath
            );

            if (is_string($detectedMimeType)) {
                $mimeType = $detectedMimeType;
            }
        }

        $mimeType ??= 'application/octet-stream';

        $response = response()->file(
            $absolutePath,
            [
                'Content-Type' => $mimeType,
                'Cache-Control' =>
                'private, no-store, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'X-Content-Type-Options' =>
                'nosniff',
            ]
        );

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            basename($path)
        );

        return $response;
    }


    public function teruskan(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $validated = $request->validate(
            [
                'ppid_pembantuid' => [
                    'bail',
                    'required',
                    'integer',
                    'exists:ppid_pembantu,id',
                ],

                'catatan_utama' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],
            ],
            [
                'ppid_pembantuid.required' =>
                'PPID Pembantu wajib dipilih.',

                'ppid_pembantuid.integer' =>
                'PPID Pembantu tidak valid.',

                'ppid_pembantuid.exists' =>
                'PPID Pembantu yang dipilih tidak ditemukan.',

                'catatan_utama.string' =>
                'Catatan harus berupa teks.',

                'catatan_utama.max' =>
                'Catatan maksimal 5.000 karakter.',
            ]
        );

        $permohonan = $this
            ->permohonanService
            ->teruskan(
                $id,
                $admin,
                $validated
            );

        return redirect()
            ->route(
                'admin.permohonan.show',
                [
                    'id' => $permohonan->id,
                ]
            )
            ->with(
                'success',
                'Permohonan berhasil diteruskan ke PPID Pembantu.'
            );
    }

    public function jawabPembantu(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $validated = $request->validate(
            [
                'jawaban_pembantu' => [
                    'bail',
                    'required',
                    'string',
                    'max:10000',
                ],

                'file_pembantu' => [
                    'nullable',
                    'file',
                    'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
                    'max:5120',
                ],
            ],
            [
                'jawaban_pembantu.required' =>
                'Jawaban PPID Pembantu wajib diisi.',

                'jawaban_pembantu.string' =>
                'Jawaban harus berupa teks.',

                'jawaban_pembantu.max' =>
                'Jawaban maksimal 10.000 karakter.',

                'file_pembantu.file' =>
                'Lampiran yang dipilih tidak valid.',

                'file_pembantu.mimes' =>
                'Lampiran harus berupa PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, atau PNG.',

                'file_pembantu.max' =>
                'Ukuran lampiran maksimal 5 MB.',
            ]
        );

        $permohonan = $this
            ->permohonanService
            ->jawabPembantu(
                $id,
                $admin,
                $validated,
                $request->file(
                    'file_pembantu'
                )
            );

        return redirect()
            ->route(
                'admin.permohonan.show',
                [
                    'id' => $permohonan->id,
                ]
            )
            ->with(
                'success',
                'Laporan berhasil dikirim ke Admin Utama.'
            );
    }


    public function validasi(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $validated = $request->validate(
            [
                'jawaban_final' => [
                    'bail',
                    'required',
                    'string',
                    'max:10000',
                ],
            ],
            [
                'jawaban_final.required' =>
                'Jawaban final wajib diisi.',

                'jawaban_final.string' =>
                'Jawaban final harus berupa teks.',

                'jawaban_final.max' =>
                'Jawaban final maksimal 10.000 karakter.',
            ]
        );

        $permohonan = $this
            ->permohonanService
            ->validasi(
                $id,
                $admin,
                $validated
            );

        return redirect()
            ->route(
                'admin.permohonan.show',
                [
                    'id' => $permohonan->id,
                ]
            )
            ->with(
                'success',
                'Permohonan berhasil divalidasi dan dikirim ke warga.'
            );
    }


    public function revisi(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $validated = $request->validate(
            [
                'catatan_revisi' => [
                    'bail',
                    'required',
                    'string',
                    'max:5000',
                ],
            ],
            [
                'catatan_revisi.required' =>
                'Catatan revisi wajib diisi.',

                'catatan_revisi.string' =>
                'Catatan revisi harus berupa teks.',

                'catatan_revisi.max' =>
                'Catatan revisi maksimal 5.000 karakter.',
            ]
        );

        $permohonan = $this
            ->permohonanService
            ->revisi(
                $id,
                $admin,
                $validated
            );

        return redirect()
            ->route(
                'admin.permohonan.show',
                [
                    'id' => $permohonan->id,
                ]
            )
            ->with(
                'success',
                'Permohonan dikembalikan ke PPID Pembantu untuk revisi.'
            );
    }

    public function tolak(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = $this->getAuthenticatedAdmin();

        $validated = $request->validate(
            [
                'alasan_penolakan' => [
                    'bail',
                    'required',
                    'string',
                    'min:10',
                    'max:5000',
                ],
            ],
            [
                'alasan_penolakan.required' =>
                'Alasan penolakan wajib diisi.',

                'alasan_penolakan.string' =>
                'Alasan penolakan harus berupa teks.',

                'alasan_penolakan.min' =>
                'Alasan penolakan minimal 10 karakter.',

                'alasan_penolakan.max' =>
                'Alasan penolakan maksimal 5.000 karakter.',
            ]
        );

        $permohonan = $this
            ->permohonanService
            ->tolak(
                $id,
                $admin,
                $validated
            );

        return redirect()
            ->route(
                'admin.permohonan.show',
                [
                    'id' => $permohonan->id,
                ]
            )
            ->with(
                'success',
                'Permohonan berhasil ditolak dan email pemberitahuan telah dimasukkan ke antrean.'
            );
    }

    /**
     * Mengambil akun admin yang sedang login.
     */
    private function getAuthenticatedAdmin(): Authorization
    {
        $admin = Auth::guard('admin')->user();

        abort_unless(
            $admin instanceof Authorization,
            401,
            'Sesi admin tidak ditemukan.'
        );

        return $admin;
    }
}
