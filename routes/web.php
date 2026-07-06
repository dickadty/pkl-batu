<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DokumentasiController as AdminInformasiPublikController;
use App\Http\Controllers\Admin\PpidPembantuController;
use App\Http\Controllers\Public\InformasiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Public\BeritaController as PublicBeritaController;
use App\Http\Controllers\Public\AuthController as PublicAuthController;
use App\Http\Controllers\Public\PermohonanController as PublicPermohonanController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::redirect('/', '/admin/login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');

        Route::middleware('admin.role:1')->group(function () {
            Route::get('/ppid-pembantu', [PpidPembantuController::class, 'index'])
                ->name('ppid-pembantu.index');

            Route::get('/ppid-pembantu/tambah', [PpidPembantuController::class, 'create'])
                ->name('ppid-pembantu.create');

            Route::post('/ppid-pembantu/tambah', [PpidPembantuController::class, 'store'])
                ->name('ppid-pembantu.store');

            Route::get('/ppid-pembantu/{id}/edit', [PpidPembantuController::class, 'edit'])
                ->name('ppid-pembantu.edit');

            Route::put('/ppid-pembantu/{id}', [PpidPembantuController::class, 'update'])
                ->name('ppid-pembantu.update');

            Route::delete('/ppid-pembantu/{id}', [PpidPembantuController::class, 'destroy'])
                ->name('ppid-pembantu.destroy');
        });

        Route::middleware('admin.role:1,2')->group(function () {


            Route::get('/informasi-publik', [AdminInformasiPublikController::class, 'index'])
                ->name('informasi-publik.index');

            Route::get('/informasi-publik/tambah', [AdminInformasiPublikController::class, 'create'])
                ->name('informasi-publik.create');

            Route::post('/informasi-publik/tambah', [AdminInformasiPublikController::class, 'store'])
                ->name('informasi-publik.store');

            Route::patch('/informasi-publik/{id}/verifikasi', [AdminInformasiPublikController::class, 'verifikasi'])
                ->name('informasi-publik.verifikasi');

            Route::delete('/informasi-publik/{id}', [AdminInformasiPublikController::class, 'destroy'])
                ->name('informasi-publik.destroy');
        });
        Route::middleware('admin.role:1,2')->group(function () {
            Route::get('/berita', [BeritaController::class, 'index'])
                ->name('berita.index');

            Route::get('/berita/tambah', [BeritaController::class, 'create'])
                ->name('berita.create');

            Route::post('/berita/tambah', [BeritaController::class, 'store'])
                ->name('berita.store');

            Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])
                ->name('berita.destroy');
        });
    });
});

Route::get('/informasi', [InformasiController::class, 'index'])
    ->name('public.informasi.index');

Route::get('/informasi/{slug}', [InformasiController::class, 'show'])
    ->name('public.informasi.show');

Route::get('/informasi/{id}/download', [InformasiController::class, 'download'])
    ->name('public.informasi.download');

Route::get('/berita', [PublicBeritaController::class, 'index'])
    ->name('public.berita.index');

Route::get('/berita/{id}', [PublicBeritaController::class, 'show'])
    ->name('public.berita.show');

Route::get('/warga/login', [PublicAuthController::class, 'showLogin'])
    ->name('login');

Route::post('/warga/login', [PublicAuthController::class, 'login'])
    ->name('public.login.process');

Route::post('/warga/logout', [PublicAuthController::class, 'logout'])
    ->name('public.logout');

Route::middleware('auth:public')->group(function () {
    Route::get('/permohonan', [PublicPermohonanController::class, 'create'])
        ->name('public.permohonan.create');

    Route::post('/permohonan', [PublicPermohonanController::class, 'store'])
        ->name('public.permohonan.store');

    Route::get('/permohonan/riwayat', [PublicPermohonanController::class, 'index'])
        ->name('public.permohonan.index');
});
