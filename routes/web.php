<?php

use App\Http\Controllers\Admin\PpidPembantuController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('ppid-pembantu/tambah', [PpidPembantuController::class, 'create'])
        ->name('ppid-pembantu.create');

    Route::post('ppid-pembantu/tambah', [PpidPembantuController::class, 'store'])
        ->name('ppid-pembantu.store');

    Route::get('ppid-pembantu/{id}/edit', [PpidPembantuController::class, 'edit'])
        ->name('ppid-pembantu.edit');

    Route::put('ppid-pembantu/{id}/edit', [PpidPembantuController::class, 'update'])
        ->name('ppid-pembantu.update');

    Route::delete('ppid-pembantu/{id}/hapus', [PpidPembantuController::class, 'destroy'])
        ->name('ppid-pembantu.destroy');
    Route::get('ppid-pembantu', [PpidPembantuController::class, 'index'])
        ->name('ppid-pembantu.index');
    Route::get('/admin/ppid-pembantu', [PpidPembantuController::class, 'index'])
    ->name('admin.ppid-pembantu.index');
});
