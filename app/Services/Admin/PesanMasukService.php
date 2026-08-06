<?php

namespace App\Services\Admin;

use App\Models\Authorization;
use App\Models\BalasPesan;
use App\Models\PesanMasuk;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PesanMasukService
{
    public function __construct(
        protected PesanMasuk $pesanMasuk,
        protected BalasPesan $balasPesan
    ) {}

    /**
     * Mengambil daftar pesan masuk untuk admin.
     *
     * @param array<string, mixed> $filters
     */
    public function getAllForAdmin(
        array $filters = []
    ): LengthAwarePaginator {
        $search = trim(
            (string) ($filters['q'] ?? '')
        );

        $statusKey = trim(
            (string) ($filters['status'] ?? 'semua')
        );

        $perPage = (int) (
            $filters['per_page'] ?? 15
        );

        if (
            ! in_array(
                $perPage,
                [10, 15, 25, 50, 100],
                true
            )
        ) {
            $perPage = 15;
        }

        $statusMap = $this->getStatusFilterMap();

        $statusValue = $statusMap[$statusKey] ?? null;

        return $this->pesanMasuk
            ->newQuery()
            ->withCount('balasan')
            ->when(
                $search !== '',
                function (
                    Builder $query
                ) use ($search): void {
                    $query->where(
                        function (
                            Builder $searchQuery
                        ) use ($search): void {
                            $searchQuery
                                ->where(
                                    'nama',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'subjek',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'pesan',
                                    'like',
                                    '%' . $search . '%'
                                );
                        }
                    );
                }
            )
            ->when(
                $statusKey !== 'semua'
                    && $statusValue !== null,
                function (
                    Builder $query
                ) use ($statusValue): void {
                    $query->where(
                        'status',
                        $statusValue
                    );
                }
            )
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Ringkasan jumlah pesan berdasarkan status.
     *
     * @return array{
     *     semua: int,
     *     baru: int,
     *     dibaca: int,
     *     dibalas: int,
     *     ditutup: int
     * }
     */
    public function getSummaryForAdmin(): array
    {
        $baseQuery = $this->pesanMasuk
            ->newQuery();

        $statusCounts = (clone $baseQuery)
            ->selectRaw(
                'status, COUNT(*) AS total'
            )
            ->groupBy('status')
            ->pluck(
                'total',
                'status'
            );

        return [
            'semua' => (clone $baseQuery)->count(),

            'baru' => (int) $statusCounts->get(
                PesanMasuk::STATUS_BARU,
                0
            ),

            'dibaca' => (int) $statusCounts->get(
                PesanMasuk::STATUS_DIBACA,
                0
            ),

            'dibalas' => (int) $statusCounts->get(
                PesanMasuk::STATUS_DIBALAS,
                0
            ),

            'ditutup' => (int) $statusCounts->get(
                PesanMasuk::STATUS_DITUTUP,
                0
            ),
        ];
    }

    /**
     * Pilihan status untuk filter halaman admin.
     *
     * @return array<string, string>
     */
    public function getStatusOptions(): array
    {
        return [
            'semua' => 'Semua Status',
            'baru' => 'Pesan Baru',
            'dibaca' => 'Sudah Dibaca',
            'dibalas' => 'Sudah Dibalas',
            'ditutup' => 'Ditutup',
        ];
    }

    /**
     * Mengambil detail pesan untuk admin.
     */
    public function getDetailForAdmin(
        int $id
    ): PesanMasuk {
        $pesan = $this->pesanMasuk
            ->newQuery()
            ->with([
                'balasan.admin',
            ])
            ->findOrFail($id);

        if (
            (int) $pesan->status
            === PesanMasuk::STATUS_BARU
        ) {
            $pesan->update([
                'status' =>
                PesanMasuk::STATUS_DIBACA,

                'tanggal_dibaca' =>
                $pesan->tanggal_dibaca
                    ?: time(),
            ]);
        }

        return $pesan->fresh([
            'balasan.admin',
        ]);
    }

    /**
     * Mencari percakapan berdasarkan token publik.
     */
    public function findByToken(
        string $token
    ): PesanMasuk {
        return $this->pesanMasuk
            ->newQuery()
            ->with([
                'balasan.admin',
            ])
            ->where(
                'token',
                $token
            )
            ->firstOrFail();
    }

    /**
     * Membuat pesan baru dari masyarakat.
     *
     * @param array<string, mixed> $data
     */
    public function createFromPublic(
        array $data
    ): PesanMasuk {
        return $this->pesanMasuk
            ->newQuery()
            ->create([
                'token' =>
                $this->generateToken(),

                'nama' => trim(
                    (string) $data['nama']
                ),

                'email' => trim(
                    (string) $data['email']
                ),

                'subjek' => trim(
                    (string) $data['subjek']
                ),

                'pesan' => trim(
                    (string) $data['pesan']
                ),

                'status' =>
                PesanMasuk::STATUS_BARU,

                'tanggal' => time(),

                'tanggal_dibaca' => null,

                'tanggal_ditutup' => null,
            ]);
    }

    /**
     * Menyimpan balasan dari masyarakat.
     *
     * @param array<string, mixed> $data
     */
    public function replyFromPublic(
        string $token,
        array $data
    ): BalasPesan {
        $pesanMasuk = $this
            ->findByToken($token);

        $this->ensureConversationIsOpen(
            $pesanMasuk
        );

        $balasan = $this->balasPesan
            ->newQuery()
            ->create([
                'pesan_masukid' =>
                $pesanMasuk->id,

                'pengirim' => 'publik',

                'adminid' => null,

                'pesan' => trim(
                    (string) $data['pesan']
                ),

                'tanggal' => time(),
            ]);

        $pesanMasuk->update([
            'status' =>
            PesanMasuk::STATUS_BARU,
        ]);

        return $balasan;
    }

    /**
     * Menyimpan balasan dari admin.
     *
     * @param array<string, mixed> $data
     */
    public function replyFromAdmin(
        int $id,
        Authorization $admin,
        array $data
    ): BalasPesan {
        $pesanMasuk = $this->pesanMasuk
            ->newQuery()
            ->findOrFail($id);

        $this->ensureConversationIsOpen(
            $pesanMasuk
        );

        $balasan = $this->balasPesan
            ->newQuery()
            ->create([
                'pesan_masukid' =>
                $pesanMasuk->id,

                'pengirim' => 'admin',

                'adminid' => $admin->id,

                'pesan' => trim(
                    (string) $data['pesan']
                ),

                'tanggal' => time(),
            ]);

        $pesanMasuk->update([
            'status' =>
            PesanMasuk::STATUS_DIBALAS,

            'tanggal_dibaca' =>
            $pesanMasuk->tanggal_dibaca
                ?: time(),
        ]);

        return $balasan;
    }

    /**
     * Menutup percakapan.
     */
    public function close(
        int $id
    ): void {
        $pesan = $this->pesanMasuk
            ->newQuery()
            ->findOrFail($id);

        $pesan->update([
            'status' =>
            PesanMasuk::STATUS_DITUTUP,

            'tanggal_ditutup' => time(),
        ]);
    }

    /**
     * Menghapus percakapan beserta balasannya.
     */
    public function delete(
        int $id
    ): void {
        $pesan = $this->pesanMasuk
            ->newQuery()
            ->findOrFail($id);

        $this->balasPesan
            ->newQuery()
            ->where(
                'pesan_masukid',
                $pesan->id
            )
            ->delete();

        $pesan->delete();
    }

    /**
     * Menghitung chat baru untuk sidebar.
     */
    public function countUnread(): int
    {
        return $this->pesanMasuk
            ->newQuery()
            ->where(
                'status',
                PesanMasuk::STATUS_BARU
            )
            ->count();
    }

    /**
     * Membentuk payload percakapan.
     *
     * @return array<string, mixed>
     */
    public function getConversationPayload(
        PesanMasuk $pesan
    ): array {
        $pesan->loadMissing([
            'balasan.admin',
        ]);

        $messages = collect([
            [
                'pengirim' => 'publik',

                'nama_pengirim' =>
                $pesan->nama,

                'pesan' =>
                $pesan->pesan,

                'tanggal' =>
                $this->formatTanggal(
                    $pesan->tanggal
                ),
            ],
        ]);

        foreach ($pesan->balasan as $balasan) {
            $messages->push([
                'pengirim' =>
                $balasan->pengirim,

                'nama_pengirim' =>
                $balasan->pengirim
                    === 'admin'
                    ? (
                        $balasan
                        ->admin
                        ?->username
                        ?? 'Admin'
                    )
                    : $pesan->nama,

                'pesan' =>
                $balasan->pesan,

                'tanggal' =>
                $this->formatTanggal(
                    $balasan->tanggal
                ),
            ]);
        }

        return [
            'id' => $pesan->id,

            'token' => $pesan->token,

            'status' =>
            (int) $pesan->status,

            'status_label' =>
            $pesan->status_label,

            'is_closed' =>
            $pesan->isClosed(),

            'messages' =>
            $messages->values(),
        ];
    }

    /**
     * Pemetaan key filter ke nilai status database.
     *
     * @return array<string, int|null>
     */
    private function getStatusFilterMap(): array
    {
        return [
            'semua' => null,

            'baru' =>
            PesanMasuk::STATUS_BARU,

            'dibaca' =>
            PesanMasuk::STATUS_DIBACA,

            'dibalas' =>
            PesanMasuk::STATUS_DIBALAS,

            'ditutup' =>
            PesanMasuk::STATUS_DITUTUP,
        ];
    }

    /**
     * Membuat token unik.
     */
    private function generateToken(): string
    {
        do {
            $token = Str::random(60);
        } while (
            $this->pesanMasuk
            ->newQuery()
            ->where(
                'token',
                $token
            )
            ->exists()
        );

        return $token;
    }

    /**
     * Memastikan percakapan belum ditutup.
     */
    private function ensureConversationIsOpen(
        PesanMasuk $pesan
    ): void {
        if ($pesan->isClosed()) {
            throw ValidationException::withMessages([
                'pesan' =>
                'Percakapan sudah ditutup dan tidak dapat dibalas lagi.',
            ]);
        }
    }

    /**
     * Memformat timestamp.
     */
    private function formatTanggal(
        mixed $timestamp
    ): string {
        if (! $timestamp) {
            return '-';
        }

        return date(
            'd-m-Y H:i',
            (int) $timestamp
        );
    }
}
