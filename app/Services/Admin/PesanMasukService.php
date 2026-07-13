<?php

namespace App\Services\Admin;

use App\Models\Authorization;
use App\Models\BalasPesan;
use App\Models\PesanMasuk;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PesanMasukService
{
    public function __construct(
        protected PesanMasuk $pesanMasuk,
        protected BalasPesan $balasPesan
    ) {}

    public function getAllForAdmin(): Collection
    {
        return $this->pesanMasuk
            ->newQuery()
            ->withCount('balasan')
            ->orderByDesc('id')
            ->get();
    }

    public function getDetailForAdmin(int $id): PesanMasuk
    {
        $pesan = $this->pesanMasuk
            ->newQuery()
            ->with(['balasan.admin'])
            ->findOrFail($id);

        if ((int) $pesan->status === PesanMasuk::STATUS_BARU) {
            $pesan->update([
                'status' => PesanMasuk::STATUS_DIBACA,
                'tanggal_dibaca' => $pesan->tanggal_dibaca ?: time(),
            ]);
        }

        return $pesan->fresh(['balasan.admin']);
    }

    public function findByToken(string $token): PesanMasuk
    {
        return $this->pesanMasuk
            ->newQuery()
            ->with(['balasan.admin'])
            ->where('token', $token)
            ->firstOrFail();
    }

    public function createFromPublic(array $data): PesanMasuk
    {
        return $this->pesanMasuk
            ->newQuery()
            ->create([
                'token' => $this->generateToken(),
                'nama' => $data['nama'],
                'email' => $data['email'],
                'subjek' => $data['subjek'],
                'pesan' => $data['pesan'],
                'status' => PesanMasuk::STATUS_BARU,
                'tanggal' => time(),
                'tanggal_dibaca' => null,
                'tanggal_ditutup' => null,
            ]);
    }

    public function replyFromPublic(string $token, array $data): BalasPesan
    {
        $pesanMasuk = $this->findByToken($token);

        $this->ensureConversationIsOpen($pesanMasuk);

        $balasan = $this->balasPesan
            ->newQuery()
            ->create([
                'pesan_masukid' => $pesanMasuk->id,
                'pengirim' => 'publik',
                'adminid' => null,
                'pesan' => $data['pesan'],
                'tanggal' => time(),
            ]);

        $pesanMasuk->update([
            'status' => PesanMasuk::STATUS_BARU,
        ]);

        return $balasan;
    }

    public function replyFromAdmin(int $id, Authorization $admin, array $data): BalasPesan
    {
        $pesanMasuk = $this->pesanMasuk
            ->newQuery()
            ->findOrFail($id);

        $this->ensureConversationIsOpen($pesanMasuk);

        $balasan = $this->balasPesan
            ->newQuery()
            ->create([
                'pesan_masukid' => $pesanMasuk->id,
                'pengirim' => 'admin',
                'adminid' => $admin->id,
                'pesan' => $data['pesan'],
                'tanggal' => time(),
            ]);

        $pesanMasuk->update([
            'status' => PesanMasuk::STATUS_DIBALAS,
            'tanggal_dibaca' => $pesanMasuk->tanggal_dibaca ?: time(),
        ]);

        return $balasan;
    }

    public function close(int $id): void
    {
        $pesan = $this->pesanMasuk
            ->newQuery()
            ->findOrFail($id);

        $pesan->update([
            'status' => PesanMasuk::STATUS_DITUTUP,
            'tanggal_ditutup' => time(),
        ]);
    }

    public function delete(int $id): void
    {
        $pesan = $this->pesanMasuk
            ->newQuery()
            ->findOrFail($id);

        $this->balasPesan
            ->newQuery()
            ->where('pesan_masukid', $pesan->id)
            ->delete();

        $pesan->delete();
    }

    public function countUnread(): int
    {
        return $this->pesanMasuk
            ->newQuery()
            ->where('status', PesanMasuk::STATUS_BARU)
            ->count();
    }

    public function getConversationPayload(PesanMasuk $pesan): array
    {
        $pesan->loadMissing(['balasan.admin']);

        $messages = collect([
            [
                'pengirim' => 'publik',
                'nama_pengirim' => $pesan->nama,
                'pesan' => $pesan->pesan,
                'tanggal' => $this->formatTanggal($pesan->tanggal),
            ],
        ]);

        foreach ($pesan->balasan as $balasan) {
            $messages->push([
                'pengirim' => $balasan->pengirim,
                'nama_pengirim' => $balasan->pengirim === 'admin'
                    ? ($balasan->admin->username ?? 'Admin')
                    : $pesan->nama,
                'pesan' => $balasan->pesan,
                'tanggal' => $this->formatTanggal($balasan->tanggal),
            ]);
        }

        return [
            'id' => $pesan->id,
            'token' => $pesan->token,
            'status' => (int) $pesan->status,
            'status_label' => $pesan->status_label,
            'is_closed' => $pesan->isClosed(),
            'messages' => $messages->values(),
        ];
    }

    private function generateToken(): string
    {
        do {
            $token = Str::random(60);
        } while (
            $this->pesanMasuk
            ->newQuery()
            ->where('token', $token)
            ->exists()
        );

        return $token;
    }

    private function ensureConversationIsOpen(PesanMasuk $pesan): void
    {
        if ($pesan->isClosed()) {
            throw ValidationException::withMessages([
                'pesan' => 'Percakapan sudah ditutup dan tidak dapat dibalas lagi.',
            ]);
        }
    }

    private function formatTanggal(?int $timestamp): string
    {
        if (! $timestamp) {
            return '-';
        }

        return date('d-m-Y H:i', (int) $timestamp);
    }
}
