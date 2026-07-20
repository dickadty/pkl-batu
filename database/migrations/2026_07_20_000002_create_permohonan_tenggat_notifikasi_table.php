<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasTable(
                'permohonan_tenggat_notifikasi'
            )
        ) {
            return;
        }

        Schema::create(
            'permohonan_tenggat_notifikasi',
            function (Blueprint $table): void {
                $table->id();

                $table
                    ->unsignedBigInteger('permohonan_id')
                    ->index();

                $table->string(
                    'jenis_notifikasi',
                    100
                );

                $table
                    ->string(
                        'status_permohonan',
                        100
                    )
                    ->nullable();

                $table->date('tanggal_acuan');

                $table
                    ->unsignedInteger('usia_hari')
                    ->default(0);

                $table->timestamp('dikirim_pada');

                $table->timestamps();

                $table->unique(
                    [
                        'permohonan_id',
                        'jenis_notifikasi',
                        'tanggal_acuan',
                    ],
                    'permohonan_tenggat_unique'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'permohonan_tenggat_notifikasi'
        );
    }
};
