<?php

namespace App\Services\Admin;

use App\Models\Authorization;
use App\Models\Keberatan;
use App\Models\PpidPembantu;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class KeberatanService
{
    public function __construct(
        protected Keberatan $keberatan
    ) {}

    /**
     * Mengambil daftar keberatan sesuai hak akses admin.
     *
     * @param array<string, mixed> $filters
     */
    public function getForAdmin(
        Authorization $admin,
        array $filters = []
    ): LengthAwarePaginator {
        $search = trim(
            (string) (
                $filters['q']
                ?? ''
            )
        );

        $status = trim(
            (string) (
                $filters['status']
                ?? ''
            )
        );

        $ppidPembantuId = isset(
            $filters['ppid_pembantuid']
        ) && $filters['ppid_pembantuid'] !== ''
            ? (int) $filters['ppid_pembantuid']
            : null;

        $perPage = (int) (
            $filters['per_page']
            ?? 15
        );

        if (
            ! in_array(
                $perPage,
                [
                    10,
                    15,
                    25,
                    50,
                    100,
                ],
                true
            )
        ) {
            $perPage = 15;
        }

        $query = $this->queryForAdmin(
            $admin,
            true
        );

        if ($search !== '') {
            $query->where(
                function (
                    Builder $subQuery
                ) use ($search): void {
                    $subQuery
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
                        )
                        ->orWhereHas(
                            'permohonan',
                            function (
                                Builder $permohonanQuery
                            ) use ($search): void {
                                $permohonanQuery
                                    ->where(
                                        'no_pemohon',
                                        'like',
                                        '%' . $search . '%'
                                    )
                                    ->orWhere(
                                        'nama_pemohon',
                                        'like',
                                        '%' . $search . '%'
                                    )
                                    ->orWhere(
                                        'email_pemohon',
                                        'like',
                                        '%' . $search . '%'
                                    )
                                    ->orWhere(
                                        'rincian',
                                        'like',
                                        '%' . $search . '%'
                                    );
                            }
                        );
                }
            );
        }

        if ($status !== '') {
            $query->where(
                'status',
                $status
            );
        }

        if (
            $admin->isAdminUtama()
            && $ppidPembantuId !== null
        ) {
            $query->whereHas(
                'permohonan',
                function (
                    Builder $permohonanQuery
                ) use ($ppidPembantuId): void {
                    $permohonanQuery->where(
                        'ppid_pembantuid',
                        $ppidPembantuId
                    );
                }
            );
        }

        return $query
            ->orderByDesc(
                'tanggal_pengajuan'
            )
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Menghitung card ringkasan sesuai hak akses admin.
     *
     * @return array{
     *     semua: int,
     *     diajukan: int,
     *     diproses: int,
     *     selesai: int,
     *     ditolak: int
     * }
     */
    public function getSummaryForAdmin(
        Authorization $admin
    ): array {
        $query = $this->queryForAdmin(
            $admin,
            false
        );

        $totalSemua = (clone $query)
            ->count();

        $statusCounts = (clone $query)
            ->selectRaw(
                'status, COUNT(*) AS total'
            )
            ->groupBy('status')
            ->pluck(
                'total',
                'status'
            );

        return [
            'semua' => $totalSemua,

            'diajukan' => (int) $statusCounts
                ->get(
                    Keberatan::STATUS_DIAJUKAN,
                    0
                ),

            'diproses' => (int) $statusCounts
                ->get(
                    Keberatan::STATUS_DIPROSES,
                    0
                ),

            'selesai' => (int) $statusCounts
                ->get(
                    Keberatan::STATUS_SELESAI,
                    0
                ),

            'ditolak' => (int) $statusCounts
                ->get(
                    Keberatan::STATUS_DITOLAK,
                    0
                ),
        ];
    }


    public function getPpidPembantuListForAdmin(
        Authorization $admin
    ): Collection {
        if (! $admin->isAdminUtama()) {
            return new Collection();
        }

        return PpidPembantu::query()
            ->select([
                'id',
                'nama',
            ])
            ->orderBy('nama')
            ->get();
    }

    public function getDetailForAdmin(
        int $id,
        Authorization $admin
    ): Keberatan {
        return $this
            ->queryForAdmin(
                $admin,
                true
            )
            ->findOrFail($id);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateResponse(
        int $id,
        Authorization $admin,
        array $data
    ): Keberatan {
        $this->ensureAdminUtama(
            $admin
        );

        return DB::transaction(
            function () use (
                $id,
                $admin,
                $data
            ): Keberatan {
                $keberatan = $this
                    ->queryForAdmin(
                        $admin,
                        false
                    )
                    ->lockForUpdate()
                    ->findOrFail($id);

                $status = trim(
                    (string) $data['status']
                );

                $tanggapan = trim(
                    (string) (
                        $data['tanggapan']
                        ?? ''
                    )
                );

                $isFinal = in_array(
                    $status,
                    [
                        Keberatan::STATUS_SELESAI,
                        Keberatan::STATUS_DITOLAK,
                    ],
                    true
                );

                $keberatan->update([
                    'status' => $status,

                    'tanggapan' =>
                        $tanggapan !== ''
                            ? $tanggapan
                            : null,

                    'tanggal_tanggapan' =>
                        $isFinal
                            ? now()->toDateString()
                            : null,

                    'adminid' => $admin->id,
                ]);

                return $keberatan
                    ->refresh()
                    ->load([
                        'permohonan.userPublic',
                        'permohonan.ppidPembantu',
                        'admin',
                    ]);
            }
        );
    }

    private function queryForAdmin(
        Authorization $admin,
        bool $withRelations = true
    ): Builder {
        $query = $this
            ->keberatan
            ->newQuery();

        if ($withRelations) {
            $query->with([
                'permohonan.userPublic',
                'permohonan.ppidPembantu',
                'admin',
            ]);
        }
        if ($admin->isAdminPembantu()) {
            abort_if(
                empty($admin->ppid_pembantuid),
                403,
                'Akun Admin Pembantu belum terhubung dengan PPID Pembantu.'
            );

            $query->whereHas(
                'permohonan',
                function (
                    Builder $permohonanQuery
                ) use ($admin): void {
                    $permohonanQuery->where(
                        'ppid_pembantuid',
                        $admin->ppid_pembantuid
                    );
                }
            );
        }

        return $query;
    }

    private function ensureAdminUtama(
        Authorization $admin
    ): void {
        if (! $admin->isAdminUtama()) {
            throw new AuthorizationException(
                'Hanya Admin Utama yang dapat memberikan tanggapan keberatan.'
            );
        }
    }
}