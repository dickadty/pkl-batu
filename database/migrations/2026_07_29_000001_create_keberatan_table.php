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
        Schema::create(
            'keberatan',
            function (Blueprint $table): void {
                $table->engine = 'InnoDB';

                $table->increments('id');

                $table
                    ->string('no_keberatan', 50)
                    ->unique();

                $table
                    ->unsignedInteger('permohonanid')
                    ->unique();
                $table->longText('alasan');

                $table
                    ->string('status', 50)
                    ->default('Diajukan');

                $table
                    ->string('hasil', 50)
                    ->nullable();
                $table
                    ->string('jenis_tindak_lanjut', 50)
                    ->nullable();

                $table
                    ->longText('tanggapan')
                    ->nullable();
                $table
                    ->string('file_tanggapan', 255)
                    ->nullable();

                $table
                    ->string('nama_file_tanggapan', 255)
                    ->nullable();
                $table->date('tanggal_pengajuan');

                $table
                    ->date('tanggal_diproses')
                    ->nullable();
                $table
                    ->date('tanggal_tanggapan')
                    ->nullable();

                $table
                    ->date('tanggal_selesai')
                    ->nullable();

                $table
                    ->unsignedInteger('adminid')
                    ->nullable();

                $table->timestamps();

                $table->index(
                    'status',
                    'keberatan_status_index'
                );

                $table->index(
                    'hasil',
                    'keberatan_hasil_index'
                );

                $table->index(
                    'tanggal_pengajuan',
                    'keberatan_tanggal_pengajuan_index'
                );

                $table->index(
                    'tanggal_selesai',
                    'keberatan_tanggal_selesai_index'
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

                $table->index(
                    [
                        'status',
                        'hasil',
                    ],
                    'keberatan_status_hasil_index'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('keberatan');
    }
};