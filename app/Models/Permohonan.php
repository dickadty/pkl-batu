<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permohonan extends Model
{
    protected $table = 'permohonan';

    public $timestamps = false;

    protected $fillable = [
        'token',
        'no_pemohon',
        'tanggal',
        'rincian',
        'tujuan',
        'cara_memperoleh',
        'cara_pengiriman',
        'status',

        'jawaban',
        'file_jawaban',
        'tanggal_jawab',
        'adminid',

        'user_publikid',
        'kategori_pemohon',
        'nama_pemohon',
        'nomor_identitas',
        'email_pemohon',
        'telp_pemohon',
        'pekerjaan_pemohon',
        'alamat_pemohon',
        'file_identitas',
        'file_surat_kuasa',

        'ppid_pembantuid',
        'catatan_utama',
        'tanggal_diteruskan',

        'jawaban_pembantu',
        'file_pembantu',
        'tanggal_jawab_pembantu',

        'catatan_revisi',
        'tanggal_revisi',
        'tanggal_validasi',
        'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date:Y-m-d',
            'tanggal_jawab' => 'date:Y-m-d',
            'tanggal_diteruskan' => 'date:Y-m-d',
            'tanggal_jawab_pembantu' => 'date:Y-m-d',
            'tanggal_revisi' => 'date:Y-m-d',
            'tanggal_validasi' => 'date:Y-m-d',
            'tanggal_selesai' => 'date:Y-m-d',
        ];
    }

    public function userPublic(): BelongsTo
    {
        return $this->belongsTo(
            UserPublic::class,
            'user_publikid',
            'id'
        );
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(
            Authorization::class,
            'adminid',
            'id'
        );
    }

    public function ppidPembantu(): BelongsTo
    {
        return $this->belongsTo(
            PpidPembantu::class,
            'ppid_pembantuid',
            'id'
        );
    }

    public function tenggatNotifikasi(): HasMany
    {
        return $this->hasMany(
            PermohonanTenggatNotifikasi::class,
            'permohonan_id',
            'id'
        );
    }

    public function namaWarga(): string
    {
        $namaSnapshot = trim((string) $this->nama_pemohon);

        if ($namaSnapshot !== '') {
            return $namaSnapshot;
        }

        return trim((string) data_get(
            $this,
            'userPublic.nama',
            'Warga'
        )) ?: 'Warga';
    }

    public function emailWarga(): ?string
    {
        $emailSnapshot = strtolower(
            trim((string) $this->email_pemohon)
        );

        if ($emailSnapshot !== '') {
            return $emailSnapshot;
        }

        $emailAkun = strtolower(
            trim((string) data_get(
                $this,
                'userPublic.email',
                ''
            ))
        );

        return $emailAkun !== ''
            ? $emailAkun
            : null;
    }

    public function nomorIdentitasWarga(): ?string
    {
        $nomorSnapshot = trim(
            (string) $this->nomor_identitas
        );

        if ($nomorSnapshot !== '') {
            return $nomorSnapshot;
        }

        $nikAkun = trim((string) data_get(
            $this,
            'userPublic.nik',
            ''
        ));

        return $nikAkun !== ''
            ? $nikAkun
            : null;
    }

    public function teleponWarga(): ?string
    {
        $teleponSnapshot = trim(
            (string) $this->telp_pemohon
        );

        if ($teleponSnapshot !== '') {
            return $teleponSnapshot;
        }

        $teleponAkun = trim((string) data_get(
            $this,
            'userPublic.telp',
            ''
        ));

        return $teleponAkun !== ''
            ? $teleponAkun
            : null;
    }

    public function trackingUrl(): string
    {
        return route('public.permohonan.show', [
            'token' => $this->token,
        ]);
    }
}
