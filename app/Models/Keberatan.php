<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keberatan extends Model
{
    public const STATUS_DIAJUKAN = 'Diajukan';

    public const STATUS_DIPROSES = 'Diproses';

    public const STATUS_SELESAI = 'Selesai';

    public const HASIL_DITERIMA = 'Diterima';

    public const HASIL_DITERIMA_SEBAGIAN = 'Diterima Sebagian';

    public const HASIL_DITOLAK = 'Ditolak';

    public const TINDAK_LANJUT_PENJELASAN = 'Penjelasan';

    public const TINDAK_LANJUT_DOKUMEN_TAMBAHAN =
    'Dokumen Tambahan';

    public const TINDAK_LANJUT_DOKUMEN_PENGGANTI =
    'Dokumen Pengganti';

    public const TINDAK_LANJUT_PERBAIKAN_DOKUMEN =
    'Perbaikan Dokumen';

    public const TINDAK_LANJUT_TANPA_DOKUMEN =
    'Tanpa Dokumen';

    protected $table = 'keberatan';

    protected $fillable = [
        'no_keberatan',
        'permohonanid',
        'ppid_pembantuid',
        'catatan_utama',
        'alasan',
        'status',
        'hasil',
        'jenis_tindak_lanjut',
        'tanggapan',
        'jawaban_pembantu',
        'file_tanggapan',
        'file_jawaban_pembantu',
        'nama_file_tanggapan',
        'nama_file_jawaban_pembantu',
        'tanggal_pengajuan',
        'tanggal_diproses',
        'tanggal_jawab_pembantu',
        'tanggal_tanggapan',
        'tanggal_selesai',
        'adminid',
    ];

    protected $casts = [
        'ppid_pembantuid' => 'integer',
        'tanggal_pengajuan' => 'date',
        'tanggal_diproses' => 'date',
        'tanggal_jawab_pembantu' => 'date',
        'tanggal_tanggapan' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public static function statusOptions(): array
    {
        return [
            self::STATUS_DIAJUKAN,
            self::STATUS_DIPROSES,
            self::STATUS_SELESAI,
        ];
    }

    public static function hasilOptions(): array
    {
        return [
            self::HASIL_DITERIMA,
            self::HASIL_DITERIMA_SEBAGIAN,
            self::HASIL_DITOLAK,
        ];
    }

    public static function tindakLanjutOptions(): array
    {
        return [
            self::TINDAK_LANJUT_PENJELASAN,
            self::TINDAK_LANJUT_DOKUMEN_TAMBAHAN,
            self::TINDAK_LANJUT_DOKUMEN_PENGGANTI,
            self::TINDAK_LANJUT_PERBAIKAN_DOKUMEN,
            self::TINDAK_LANJUT_TANPA_DOKUMEN,
        ];
    }

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(
            Permohonan::class,
            'permohonanid'
        );
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(
            Authorization::class,
            'adminid'
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

    public function isDiajukan(): bool
    {
        return $this->status === self::STATUS_DIAJUKAN;
    }

    public function isDiproses(): bool
    {
        return $this->status === self::STATUS_DIPROSES;
    }

    public function isSelesai(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }
}
