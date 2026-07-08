<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DokumentasiController as AdminInformasiPublikController;
use App\Http\Controllers\Admin\PermohonanController as AdminPermohonanController;
use App\Http\Controllers\Admin\PpidPembantuController;

use App\Http\Controllers\Publik\AuthController as PublikAuthController;
use App\Http\Controllers\Publik\BeritaController as PublikBeritaController;
use App\Http\Controllers\Publik\InformasiController;
use App\Http\Controllers\Publik\PermohonanController as PublikPermohonanController;


/*
|--------------------------------------------------------------------------
| Route Publik: Informasi Publik
|--------------------------------------------------------------------------
| Digunakan warga untuk melihat dan mengunduh informasi publik.
| Data berasal dari tabel dokumentasi.
*/

Route::get('/informasi', [InformasiController::class, 'index'])
    ->name('public.informasi.index');

Route::get('/informasi/{slug}', [InformasiController::class, 'show'])
    ->name('public.informasi.show');

Route::get('/informasi/{id}/download', [InformasiController::class, 'download'])
    ->name('public.informasi.download');

/*
|--------------------------------------------------------------------------
| Route Publik: Berita
|--------------------------------------------------------------------------
| Digunakan warga untuk melihat daftar berita dan detail berita.
| Data berasal dari tabel berita.
*/

Route::get('/berita', [PublikBeritaController::class, 'index'])
    ->name('public.berita.index');

Route::get('/berita/{id}', [PublikBeritaController::class, 'show'])
    ->name('public.berita.show');

/*
|--------------------------------------------------------------------------
| Route Publik: Login Warga
|--------------------------------------------------------------------------
| Digunakan warga untuk login sebelum mengajukan permohonan informasi.
| Route name login dipakai oleh middleware auth Laravel.
*/

Route::get('/warga/login', [PublikAuthController::class, 'showLogin'])
    ->name('login');

Route::post('/warga/login', [PublikAuthController::class, 'login'])
    ->name('public.login.process');

Route::post('/warga/logout', [PublikAuthController::class, 'logout'])
    ->name('public.logout');

/*
|--------------------------------------------------------------------------
| Route Publik: Permohonan Informasi Warga
|--------------------------------------------------------------------------
| Warga wajib login menggunakan guard public.
| Warga dapat mengajukan permohonan dan melihat riwayat jawaban.
*/

Route::middleware('auth:public')->group(function () {
    Route::get('/permohonan', [PublikPermohonanController::class, 'create'])
        ->name('public.permohonan.create');

    Route::post('/permohonan', [PublikPermohonanController::class, 'store'])
        ->name('public.permohonan.store');

    Route::get('/permohonan/riwayat', [PublikPermohonanController::class, 'index'])
        ->name('public.permohonan.index');
});

/*
|--------------------------------------------------------------------------
| Route Admin
|--------------------------------------------------------------------------
| Digunakan untuk login admin utama dan admin pembantu.
| Semua route admin memakai prefix /admin dan route name admin.
*/

Route::prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Auth Admin
    |--------------------------------------------------------------------------
    | Login dan logout admin.
    */

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    /*
    |--------------------------------------------------------------------------
    | Area Admin Login
    |--------------------------------------------------------------------------
    | Semua route di bawah ini hanya bisa diakses setelah admin login.
    */

    Route::middleware('admin.auth')->group(function () {

        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');

        /*
        |--------------------------------------------------------------------------
        | Admin Utama: PPID Pembantu
        |--------------------------------------------------------------------------
        | Hanya admin utama role 1 yang boleh mengelola data PPID Pembantu.
        */

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

        /*
|--------------------------------------------------------------------------
| Admin Utama: Register Akun Admin
|--------------------------------------------------------------------------
| Hanya admin utama yang boleh membuat akun admin baru.
| Akun admin disimpan ke tabel authorization.
*/

        Route::middleware('admin.role:1')->group(function () {
            Route::get('/akun-admin/tambah', [AuthController::class, 'showRegister'])
                ->name('akun-admin.create');

            Route::post('/akun-admin/tambah', [AuthController::class, 'register'])
                ->name('akun-admin.store');
        });

        /*
        |--------------------------------------------------------------------------
        | Admin Utama dan Admin Pembantu: Informasi Publik
        |--------------------------------------------------------------------------
        | Admin dapat mengunggah informasi publik.
        | Admin pembantu upload, admin utama dapat verifikasi.
        | Data masuk ke tabel dokumentasi.
        */

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

        /*
        |--------------------------------------------------------------------------
        | Admin Utama dan Admin Pembantu: Berita
        |--------------------------------------------------------------------------
        | Admin dapat menambah dan menghapus berita.
        | Data masuk ke tabel berita.
        */

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

        /*
        |--------------------------------------------------------------------------
        | Admin Utama dan Admin Pembantu: Permohonan Informasi
        |--------------------------------------------------------------------------
        | Admin utama:
        | - melihat semua permohonan warga
        | - meneruskan permohonan ke PPID Pembantu
        | - validasi jawaban PPID Pembantu
        | - mengirim jawaban final ke warga
        |
        | Admin pembantu:
        | - melihat permohonan yang diteruskan kepadanya
        | - mengirim jawaban atau laporan ke admin utama
        */

        Route::middleware('admin.role:1,2')->group(function () {
            Route::get('/permohonan', [AdminPermohonanController::class, 'index'])
                ->name('permohonan.index');

            Route::get('/permohonan/{id}', [AdminPermohonanController::class, 'show'])
                ->name('permohonan.show');

            Route::post('/permohonan/{id}/teruskan', [AdminPermohonanController::class, 'teruskan'])
                ->name('permohonan.teruskan');

            Route::post('/permohonan/{id}/jawab-pembantu', [AdminPermohonanController::class, 'jawabPembantu'])
                ->name('permohonan.jawab-pembantu');

            Route::post('/permohonan/{id}/validasi', [AdminPermohonanController::class, 'validasi'])
                ->name('permohonan.validasi');

            Route::post('/permohonan/{id}/revisi', [AdminPermohonanController::class, 'revisi'])
                ->name('permohonan.revisi');
        });
    });
});
