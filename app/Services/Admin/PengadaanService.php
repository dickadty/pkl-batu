<?php

namespace App\Services\Admin;

use App\Models\Pengadaan;
use App\Models\PpidPembantu;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PengadaanService
{
    public function isAdminUtama(object $admin): bool
    {
        return $this->getAdminRole($admin) === 1;
    }

    public function isAdminPpidPembantu(object $admin): bool
    {
        return $this->getAdminRole($admin) === 2;
    }

    public function paginateForAdmin(
        object $admin,
        ?string $search = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        $this->ensureAllowedRole($admin);

        $query = Pengadaan::query()
            ->with([
                'ppidPembantu:id,nama',
            ]);

        $this->applyAdminScope(
            $query,
            $admin
        );

        $search = trim(
            (string) $search
        );

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search): void {
                $builder
                    ->where(
                        'nama_paket',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'sumber_dana',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'metode',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhereHas(
                        'ppidPembantu',
                        function (Builder $ppidQuery) use ($search): void {
                            $ppidQuery->where(
                                'nama',
                                'like',
                                '%' . $search . '%'
                            );
                        }
                    );
            });
        }

        return $query
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getFormContext(object $admin): array
    {
        $this->ensureAllowedRole($admin);

        if ($this->isAdminUtama($admin)) {
            return [
                'ppidPembantu' => PpidPembantu::query()
                    ->select([
                        'id',
                        'nama',
                    ])
                    ->orderBy('nama')
                    ->get(),

                'lockedPpid' => null,
            ];
        }

        $ppidId = $this->resolveAdminPpidPembantuId(
            $admin
        );

        $lockedPpid = PpidPembantu::query()
            ->select([
                'id',
                'nama',
            ])
            ->findOrFail($ppidId);

        return [
            'ppidPembantu' => new Collection([
                $lockedPpid,
            ]),

            'lockedPpid' => $lockedPpid,
        ];
    }

    public function findForAdmin(
        object $admin,
        int $id
    ): Pengadaan {
        $this->ensureAllowedRole($admin);

        $query = Pengadaan::query()
            ->with([
                'ppidPembantu:id,nama',
            ])
            ->whereKey($id);

        $this->applyAdminScope(
            $query,
            $admin
        );

        return $query->firstOrFail();
    }

    public function createForAdmin(
        object $admin,
        array $data
    ): Pengadaan {
        $this->ensureAllowedRole($admin);

        $ppidId = $this->resolveSubmittedPpidId(
            $admin,
            $data
        );

        return DB::transaction(
            function () use (
                $data,
                $ppidId
            ): Pengadaan {
                $pengadaan = Pengadaan::query()
                    ->create(
                        $this->normalizePayload(
                            $data,
                            $ppidId
                        )
                    );

                return $pengadaan->load([
                    'ppidPembantu:id,nama',
                ]);
            }
        );
    }

    public function updateForAdmin(
        object $admin,
        int $id,
        array $data
    ): Pengadaan {
        $pengadaan = $this->findForAdmin(
            $admin,
            $id
        );

        $ppidId = $this->resolveSubmittedPpidId(
            $admin,
            $data
        );

        return DB::transaction(
            function () use (
                $pengadaan,
                $data,
                $ppidId
            ): Pengadaan {
                $pengadaan->update(
                    $this->normalizePayload(
                        $data,
                        $ppidId
                    )
                );

                return $pengadaan
                    ->fresh()
                    ->load([
                        'ppidPembantu:id,nama',
                    ]);
            }
        );
    }

    public function deleteForAdmin(
        object $admin,
        int $id
    ): void {
        $pengadaan = $this->findForAdmin(
            $admin,
            $id
        );

        DB::transaction(
            function () use ($pengadaan): void {
                $pengadaan->delete();
            }
        );
    }

    private function getAdminRole(object $admin): int
    {
        return (int) data_get(
            $admin,
            'role',
            0
        );
    }

    private function ensureAllowedRole(object $admin): void
    {
        if (
            !in_array(
                $this->getAdminRole($admin),
                [
                    1,
                    2,
                ],
                true
            )
        ) {
            throw new AuthorizationException(
                'Anda tidak memiliki akses ke fitur pengadaan.'
            );
        }
    }

    private function applyAdminScope(
        Builder $query,
        object $admin
    ): void {
        if ($this->isAdminUtama($admin)) {
            return;
        }

        $query->where(
            'ppid_pembantuid',
            $this->resolveAdminPpidPembantuId(
                $admin
            )
        );
    }

    private function resolveSubmittedPpidId(
        object $admin,
        array $data
    ): int {
        if ($this->isAdminPpidPembantu($admin)) {
            return $this->resolveAdminPpidPembantuId(
                $admin
            );
        }

        $ppidId = (int) (
            $data['ppid_pembantuid'] ?? 0
        );

        if ($ppidId <= 0) {
            throw new AuthorizationException(
                'PPID Pembantu belum dipilih.'
            );
        }

        return $ppidId;
    }

    private function resolveAdminPpidPembantuId(
        object $admin
    ): int {
        $candidates = [
            data_get(
                $admin,
                'ppid_pembantuid'
            ),

            data_get(
                $admin,
                'ppid_pembantu_id'
            ),

            data_get(
                $admin,
                'ppidPembantu.id'
            ),
        ];

        foreach ($candidates as $candidate) {
            $ppidId = (int) $candidate;

            if ($ppidId > 0) {
                return $ppidId;
            }
        }

        throw new AuthorizationException(
            'Akun Admin PPID Pembantu belum terhubung dengan unit PPID Pembantu.'
        );
    }

    private function normalizePayload(
        array $data,
        int $ppidId
    ): array {
        $pagu = preg_replace(
            '/[^0-9]/',
            '',
            (string) (
                $data['pagu'] ?? ''
            )
        );

        $pagu = ltrim(
            $pagu,
            '0'
        );

        if ($pagu === '') {
            $pagu = '0';
        }

        return [
            'nama_paket' => trim(
                (string) $data['nama_paket']
            ),

            'pagu' => $pagu,

            'sumber_dana' => trim(
                (string) $data['sumber_dana']
            ),

            'metode' => trim(
                (string) $data['metode']
            ),

            'rencana_kegiatan' => trim(
                (string) $data['rencana_kegiatan']
            ),

            'ppid_pembantuid' => $ppidId,
        ];
    }
}
