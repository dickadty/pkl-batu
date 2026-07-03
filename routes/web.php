<?php

use App\Http\Controllers\Admin\PpidPembantuController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('ppid-pembantu/tambah', [PpidPembantuController::class, 'create'])
        ->name('ppid-pembantu.create');

    Route::post('ppid-pembantu/tambah', [PpidPembantuController::class, 'store'])
        ->name('ppid-pembantu.store');
});
