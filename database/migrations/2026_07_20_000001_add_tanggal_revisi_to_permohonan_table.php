<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasTable('permohonan')
            && ! Schema::hasColumn(
                'permohonan',
                'tanggal_revisi'
            )
        ) {
            Schema::table(
                'permohonan',
                function (Blueprint $table): void {
                    $table
                        ->date('tanggal_revisi')
                        ->nullable()
                        ->after('catatan_revisi');
                }
            );
        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('permohonan')
            && Schema::hasColumn(
                'permohonan',
                'tanggal_revisi'
            )
        ) {
            Schema::table(
                'permohonan',
                function (Blueprint $table): void {
                    $table->dropColumn(
                        'tanggal_revisi'
                    );
                }
            );
        }
    }
};
