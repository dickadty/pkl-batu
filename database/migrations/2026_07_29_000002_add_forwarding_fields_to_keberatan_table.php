<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keberatan', function (Blueprint $table): void {
            $table->unsignedInteger('ppid_pembantuid')->nullable()->after('permohonanid');
            $table->longText('catatan_utama')->nullable()->after('ppid_pembantuid');
            $table->longText('jawaban_pembantu')->nullable()->after('tanggapan');
            $table->string('file_jawaban_pembantu', 255)->nullable()->after('file_tanggapan');
            $table->string('nama_file_jawaban_pembantu', 255)->nullable()->after('file_jawaban_pembantu');
            $table->date('tanggal_jawab_pembantu')->nullable()->after('tanggal_diproses');

            $table->index('ppid_pembantuid', 'keberatan_ppid_pembantuid_index');
            $table->index('tanggal_jawab_pembantu', 'keberatan_tanggal_jawab_pembantu_index');
        });
    }

    public function down(): void
    {
        Schema::table('keberatan', function (Blueprint $table): void {
            $table->dropIndex('keberatan_ppid_pembantuid_index');
            $table->dropIndex('keberatan_tanggal_jawab_pembantu_index');
            $table->dropColumn([
                'ppid_pembantuid',
                'catatan_utama',
                'jawaban_pembantu',
                'file_jawaban_pembantu',
                'nama_file_jawaban_pembantu',
                'tanggal_jawab_pembantu',
            ]);
        });
    }
};
