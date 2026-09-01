<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authorization;
use App\Models\Keberatan;
use App\Models\PpidPembantu;
use App\Services\Admin\KeberatanService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;
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

        $search = trim(
            (string) $request->query('search', '')
        );

        $status = trim(
            (string) $request->query('status', '')
        );

        $query = Keberatan::query()
            ->with([
                'permohonan',
                'ppidPembantu',
                'admin',
            ]);

        /*
         * Admin PPID Pembantu hanya dapat melihat
         * keberatan dari unit PPID Pembantu miliknya.
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

        if ($search !== '') {
            $query->where(
                function ($searchQuery) use ($search): void {
                    $searchQuery
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

        if (
            $status !== ''
            && in_array(
                $status,
                [
                    Keberatan::STATUS_DIAJUKAN,
                    Keberatan::STATUS_DIPROSES,
                    Keberatan::STATUS_SELESAI,
                ],
                true
            )
        ) {
            $query->where('status', $status);
        }

        $keberatans = $query
            ->orderByDesc('tanggal_pengajuan')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'pages.admin.keberatan.index',
            [
                'keberatans' => $keberatans,
                'ppidPembantuList' => PpidPembantu::query()
                    ->when(
                        (int) $admin->role === 2,
                        fn($query) => $query->where('id', (int) $admin->ppid_pembantuid)
                    )
                    ->orderBy('nama')
                    ->get(),
                'search' => $search,
                'status' => $status,
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

        $keberatan = $this->keberatanService
            ->getDetail(
                $id,
                $admin
            );

        return view(
            'pages.admin.keberatan.show',
            [
                'admin' => $admin,
                'keberatan' => $keberatan,
                'ppidPembantuList' => PpidPembantu::query()
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

        $validated = $request->validate(
            [
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
            ],
            [
                'ppid_pembantuid.required' =>
                'Unit PPID Pelaksana wajib dipilih.',

                'ppid_pembantuid.integer' =>
                'Unit PPID Pelaksana tidak valid.',

                'ppid_pembantuid.exists' =>
                'Unit PPID Pelaksana yang dipilih tidak ditemukan.',

                'catatan_utama.max' =>
                'Catatan utama maksimal 2000 karakter.',
            ]
        );

        try {
            $this->keberatanService->teruskan(
                id: $id,
                admin: $admin,
                data: $validated
            );

            return redirect()
                ->route(
                    'admin.keberatan.show',
                    ['id' => $id]
                )
                ->with(
                    'success',
                    'Keberatan berhasil diteruskan ke PPID Pelaksana.'
                );
        } catch (
            AuthorizationException | RuntimeException $exception
        ) {
            return back()->withInput()->with(
                'error',
                $exception->getMessage()
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->withInput()->with(
                'error',
                'Terjadi kesalahan saat meneruskan keberatan.'
            );
        }
    }

    public function jawabPembantu(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = auth('admin')->user();

        abort_unless(
            $admin instanceof Authorization,
            401
        );

        $validated = $request->validate(
            [
                'tanggapan' => [
                    'required',
                    'string',
                    'min:10',
                    'max:10000',
                ],
                'file_tanggapan' => [
                    'nullable',
                    'file',
                    'mimes:pdf,doc,docx,xls,xlsx',
                    'max:10240',
                ],
            ],
            [
                'tanggapan.required' =>
                'Jawaban PPID Pelaksana wajib diisi.',

                'tanggapan.min' =>
                'Jawaban PPID Pelaksana minimal 10 karakter.',

                'tanggapan.max' =>
                'Jawaban PPID Pelaksana maksimal 10.000 karakter.',

                'file_tanggapan.file' =>
                'Dokumen jawaban tidak valid.',

                'file_tanggapan.mimes' =>
                'Dokumen harus berformat PDF, DOC, DOCX, XLS, atau XLSX.',

                'file_tanggapan.max' =>
                'Ukuran dokumen maksimal 10 MB.',
            ]
        );

        try {
            $this->keberatanService->jawabPembantu(
                id: $id,
                admin: $admin,
                data: $validated,
                fileJawabanPembantu: $request->file('file_tanggapan')
            );

            return redirect()
                ->route(
                    'admin.keberatan.show',
                    ['id' => $id]
                )
                ->with(
                    'success',
                    'Jawaban PPID Pelaksana berhasil dikirim.'
                );
        } catch (
            AuthorizationException | RuntimeException $exception
        ) {
            return back()->withInput()->with(
                'error',
                $exception->getMessage()
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->withInput()->with(
                'error',
                'Terjadi kesalahan saat mengirim jawaban PPID Pelaksana.'
            );
        }
    }

    public function proses(int $id): RedirectResponse
    {
        $admin = auth('admin')->user();

        abort_unless(
            $admin instanceof Authorization,
            401
        );

        try {
            $this->keberatanService->proses($id, $admin);

            return redirect()
                ->route('admin.keberatan.show', ['id' => $id])
                ->with('success', 'Keberatan berhasil diproses.');
        } catch (
            AuthorizationException | RuntimeException $exception
        ) {
            return back()->with('error', $exception->getMessage());
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Terjadi kesalahan saat memproses keberatan.'
            );
        }
    }

    public function selesaikan(
        Request $request,
        int $id
    ): RedirectResponse {
        $admin = auth('admin')->user();

        abort_unless(
            $admin instanceof Authorization,
            401
        );

        $validated = $request->validate(
            [
                'hasil' => [
                    'required',
                    'string',
                    'in:Diterima,Diterima Sebagian,Ditolak',
                ],

                'jenis_tindak_lanjut' => [
                    'required',
                    'string',
                    'in:Penjelasan,Dokumen Tambahan,Dokumen Pengganti,Perbaikan Dokumen,Tanpa Dokumen',
                ],

                'tanggapan' => [
                    'required',
                    'string',
                    'min:10',
                    'max:10000',
                ],

                'file_tanggapan' => [
                    'nullable',
                    'file',
                    'mimes:pdf,doc,docx,xls,xlsx',
                    'max:10240',
                ],
            ],
            [
                'hasil.required' =>
                'Hasil keberatan wajib dipilih.',

                'hasil.in' =>
                'Hasil keberatan tidak valid.',

                'jenis_tindak_lanjut.required' =>
                'Jenis tindak lanjut wajib dipilih.',

                'jenis_tindak_lanjut.in' =>
                'Jenis tindak lanjut tidak valid.',

                'tanggapan.required' =>
                'Tanggapan final wajib diisi.',

                'tanggapan.min' =>
                'Tanggapan final minimal 10 karakter.',

                'tanggapan.max' =>
                'Tanggapan final maksimal 10.000 karakter.',

                'file_tanggapan.file' =>
                'Dokumen tanggapan tidak valid.',

                'file_tanggapan.mimes' =>
                'Dokumen harus berformat PDF, DOC, DOCX, XLS, atau XLSX.',

                'file_tanggapan.max' =>
                'Ukuran dokumen maksimal 10 MB.',
            ]
        );

        try {
            $this->keberatanService->selesaikan(
                id: $id,
                admin: $admin,
                data: $validated,
                fileTanggapan: $request->file('file_tanggapan')
            );

            return redirect()
                ->route(
                    'admin.keberatan.show',
                    ['id' => $id]
                )
                ->with(
                    'success',
                    'Tanggapan final berhasil disimpan dan keberatan telah diselesaikan.'
                );
        } catch (
            AuthorizationException | RuntimeException $exception
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    $exception->getMessage()
                );
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menyimpan tanggapan final.'
                );
        }
    }
}
