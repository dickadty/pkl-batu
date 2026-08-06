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
        Schema::create('menus', function (Blueprint $table) {
    $table->id();

    // Parent Menu
    $table->foreignId('parent_id')
        ->nullable()
        ->constrained('menus')
        ->nullOnDelete();

    // Nama menu
    $table->string('nama');

    // Jenis menu
    $table->enum('type', [
        'page',
        'module',
        'route',
        'url'
    ]);

    // Jika type = page
    $table->foreignId('page_id')
        ->nullable()
        ->constrained('pages')
        ->nullOnDelete();

    // Jika type = module
    $table->string('module')->nullable();

    // Jika type = route
    $table->string('route_name')->nullable();

    // Jika type = url
    $table->string('url')->nullable();

    // Urutan menu
    $table->integer('sort_order')->default(0);

    // Status
    $table->boolean('is_active')->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};