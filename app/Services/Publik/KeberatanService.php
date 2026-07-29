<?php

namespace App\Services\Publik;

use App\Models\Keberatan;
use App\Models\Permohonan;
use App\Models\UserPublic;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class KeberatanService
{
    private const ALLOWED_PERMOHONAN_STATUS = [
        'Selesai',
        'Ditolak',
    ];

    public function __construct(
        protected Keberatan $keberatan,
        protected Permohonan $permohonan
    ) {}

    /**
     * Mengambil daftar keberatan milik warga.
     */
    public function getByUser(
        UserPublic $user,
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->keberatan
            ->newQuery()
            ->with([
                'permohonan.ppidPembantu',
                'admin',
            ])
            ->whereHas(
                'permohonan',
                function ($query) use (
                    $user
                ): void {
                    $query->where(
                        'user_publikid',
                        $user->id
                    );
                }
            )
            ->orderByDesc(
                'tanggal_pengajuan'
            )
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Mengambil permohonan yang dapat diajukan keberatan.
     */
    public function getEligiblePermohonan(
        UserPublic $user
    ): Collection {
        $existingPermohonanIds =
            $this->keberatan
            ->newQuery()
            ->select('permohonanid');

        return $this->permohonan
            ->newQuery()
            ->where(
                'user_publikid',
                $user->id
            )
            ->whereIn(
                'status',
                self::ALLOWED_PERMOHONAN_STATUS
            )
            ->whereNotIn(
                'id',
                $existingPermohonanIds
            )
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Mengambil detail keberatan milik warga.
     */
    public function getDetailForUser(
        int $id,
        UserPublic $user
    ): Keberatan {
        return $this->keberatan
            ->newQuery()
            ->with([
                'permohonan.ppidPembantu',
                'admin',
            ])
            ->whereHas(
                'permohonan',
                function ($query) use (
                    $user
                ): void {
                    $query->where(
                        'user_publikid',
                        $user->id
                    );
                }
            )
            ->findOrFail($id);
    }

    /**
     * Membuat keberatan baru.
     *
     * @param array<string, mixed> $data
     */
    public function createForUser(
        UserPublic $user,
        array $data
    ): Keberatan {
        return DB::transaction(
            function () use (
                $user,
                $data
            ): Keberatan {
                $permohonan = $this
                    ->permohonan
                    ->newQuery()
                    ->where(
                        'user_publikid',
                        $user->id
                    )
                    ->lockForUpdate()
                    ->findOrFail(
                        (int) $data['permohonanid']
                    );

                if (
                    ! in_array(
                        $permohonan->status,
                        self::ALLOWED_PERMOHONAN_STATUS,
                        true
                    )
                ) {
                    throw ValidationException::withMessages([
                        'permohonanid' =>
                        'Keberatan hanya dapat diajukan untuk permohonan yang telah selesai atau ditolak.',
                    ]);
                }

                $alreadyExists = $this
                    ->keberatan
                    ->newQuery()
                    ->where(
                        'permohonanid',
                        $permohonan->id
                    )
                    ->exists();

                if ($alreadyExists) {
                    throw ValidationException::withMessages([
                        'permohonanid' =>
                        'Keberatan untuk permohonan tersebut sudah pernah diajukan.',
                    ]);
                }

                $keberatan = $this
                    ->keberatan
                    ->newQuery()
                    ->create([
                        'no_keberatan' =>
                        $this
                            ->generateUniqueNumber(),

                        'permohonanid' =>
                        $permohonan->id,

                        'alasan' => trim(
                            (string) $data['alasan']
                        ),

                        'status' =>
                        Keberatan::STATUS_DIAJUKAN,

                        'tanggapan' => null,

                        'tanggal_pengajuan' =>
                        now()->toDateString(),

                        'tanggal_tanggapan' =>
                        null,

                        'adminid' => null,
                    ]);

                return $keberatan
                    ->refresh()
                    ->load([
                        'permohonan.ppidPembantu',
                        'admin',
                    ]);
            }
        );
    }

    /**
     * Membuat nomor keberatan yang unik.
     */
    private function generateUniqueNumber(): string
    {
        do {
            $number = sprintf(
                'KBR/%s/%s',
                now()->format('Ymd'),
                strtoupper(
                    Str::random(8)
                )
            );
        } while (
            $this->keberatan
            ->newQuery()
            ->where(
                'no_keberatan',
                $number
            )
            ->exists()
        );

        return $number;
    }
}
