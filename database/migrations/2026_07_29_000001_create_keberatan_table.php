<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel keberatan.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Bersihkan tabel sisa migration yang gagal
        |--------------------------------------------------------------------------
        |
        | MySQL dapat meninggalkan tabel keberatan meskipun migration gagal
        | ketika menambahkan foreign key.
        |
        */

        Schema::disableForeignKeyConstraints();

        try {
            Schema::dropIfExists('keberatan');
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        /*
        |--------------------------------------------------------------------------
        | Membuat tabel keberatan
        |--------------------------------------------------------------------------
        |
        | Foreign key database tidak dipasang karena tabel permohonan dan
        | authorization merupakan tabel legacy yang tipe primary key atau
        | storage engine-nya belum dipastikan kompatibel.
        |
        | Relasi tetap dijalankan melalui Eloquent.
        |
        */

        Schema::create(
            'keberatan',
            function (Blueprint $table): void {
                $table->engine = 'InnoDB';

                /*
                 * Primary key INT UNSIGNED.
                 */
                $table->increments('id');

                /*
                 * Nomor registrasi keberatan.
                 */
                $table
                    ->string('no_keberatan', 50)
                    ->unique();

                /*
                 * Mengacu pada permohonan.id.
                 *
                 * Satu permohonan hanya dapat mempunyai satu keberatan.
                 * Tidak menggunakan foreign key fisik agar kompatibel
                 * dengan tabel legacy.
                 */
                $table
                    ->unsignedInteger('permohonanid')
                    ->unique();

                /*
                 * Alasan yang disampaikan warga.
                 */
                $table->longText('alasan');

                /*
                 * Status:
                 * - Diajukan
                 * - Diproses
                 * - Selesai
                 * - Ditolak
                 */
                $table
                    ->string('status', 50)
                    ->default('Diajukan');

                /*
                 * Tanggapan resmi Admin Utama.
                 */
                $table
                    ->longText('tanggapan')
                    ->nullable();

                /*
                 * Tanggal warga mengajukan keberatan.
                 */
                $table->date('tanggal_pengajuan');

                /*
                 * Tanggal tanggapan final diberikan.
                 */
                $table
                    ->date('tanggal_tanggapan')
                    ->nullable();

                /*
                 * Mengacu pada authorization.id.
                 *
                 * Nilai null berarti belum ada admin yang menanggapi.
                 */
                $table
                    ->unsignedInteger('adminid')
                    ->nullable();

                $table->timestamps();

                /*
                 * Index untuk pencarian dan filter.
                 */
                $table->index(
                    'status',
                    'keberatan_status_index'
                );

                $table->index(
                    'tanggal_pengajuan',
                    'keberatan_tanggal_pengajuan_index'
                );

                $table->index(
                    'adminid',
                    'keberatan_adminid_index'
                );

                $table->index(
                    [
                        'status',
                        'tanggal_pengajuan',
                    ],
                    'keberatan_status_tanggal_index'
                );
            }
        );
    }

    /**
     * Menghapus tabel keberatan.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            Schema::dropIfExists('keberatan');
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }
};
