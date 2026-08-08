<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('pages', function (Blueprint $table) {

        $table->foreignId('module_id')
            ->nullable()
            ->after('judul')
            ->constrained('modules')
            ->nullOnDelete();

        $table->string('gambar')
            ->nullable()
            ->after('slug');

    });
}

public function down(): void
{
    Schema::table('pages', function (Blueprint $table) {

        $table->dropForeign(['module_id']);

        $table->dropColumn([
            'module_id',
            'gambar'
        ]);

    });
}
};



