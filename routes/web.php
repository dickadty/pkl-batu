<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DokumentasiController as AdminInformasiPublikController;
use App\Http\Controllers\Admin\PejabatController;
use App\Http\Controllers\Admin\PermohonanController as AdminPermohonanController;
use App\Http\Controllers\Admin\PesanMasukController;
use App\Http\Controllers\Admin\PpidPembantuController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Publik\FaqController as PublikFaqController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Publik\AuthController as PublikAuthController;
use App\Http\Controllers\Publik\BeritaController as PublikBeritaController;
use App\Http\Controllers\Publik\InformasiController;
use App\Http\Controllers\Publik\PermohonanController as PublikPermohonanController;
use App\Http\Controllers\Publik\PesanController as PublikPesanController;

/*
|--------------------------------------------------------------------------
| Halaman Awal
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/informasi')->name('home');

/*
|--------------------------------------------------------------------------
| Informasi Publik
|--------------------------------------------------------------------------
*/

Route::prefix('informasi')
    ->name('public.informasi.')
    ->controller(InformasiController::class)
    ->group(function (): void {
        Route::get('/', 'index')
            ->name('index');

        Route::get('/{id}/download', 'download')
            ->whereNumber('id')
            ->name('download');

        Route::get('/{slug}', 'show')
            ->where('slug', '[A-Za-z0-9\-]+')
            ->name('show');
    });

/*
|--------------------------------------------------------------------------
| FAQ Publik
|--------------------------------------------------------------------------
*/

Route::get('/faq', [PublikFaqController::class, 'index'])
    ->name('public.faq.index');


/*
|--------------------------------------------------------------------------
| Berita Publik
|--------------------------------------------------------------------------
*/

Route::prefix('berita')
    ->name('public.berita.')
    ->controller(PublikBeritaController::class)
    ->group(function (): void {
        Route::get('/', 'index')
            ->name('index');

        Route::get('/{id}', 'show')
            ->whereNumber('id')
            ->name('show');
    });

/*
|--------------------------------------------------------------------------
| Pesan Publik / Percakapan
|--------------------------------------------------------------------------
*/

Route::prefix('pesan')
    ->name('public.pesan.')
    ->controller(PublikPesanController::class)
    ->group(function (): void {
        Route::get('/', 'create')
            ->name('create');

        Route::post('/', 'store')
            ->middleware('throttle:5,1')
            ->name('store');

        Route::get('/cek/{token}', 'show')
            ->where('token', '[A-Za-z0-9]+')
            ->name('show');

        Route::get('/cek/{token}/messages', 'messages')
            ->where('token', '[A-Za-z0-9]+')
            ->name('messages');

        Route::post('/cek/{token}/reply', 'reply')
            ->where('token', '[A-Za-z0-9]+')
            ->middleware('throttle:10,1')
            ->name('reply');
    });

/*
|--------------------------------------------------------------------------
| Autentikasi Warga
|--------------------------------------------------------------------------
*/

Route::prefix('warga')
    ->controller(PublikAuthController::class)
    ->group(function (): void {
        Route::middleware('guest:public')
            ->group(function (): void {
                Route::get('/login', 'showLogin')
                    ->name('login');

                Route::post('/login', 'login')
                    ->middleware('throttle:5,1')
                    ->name('public.login.process');

                Route::get('/register', 'showRegister')
                    ->name('public.register');

                Route::post('/register', 'register')
                    ->middleware('throttle:5,1')
                    ->name('public.register.store');
            });

        Route::post('/logout', 'logout')
            ->middleware('auth:public')
            ->name('public.logout');
    });

/*
|--------------------------------------------------------------------------
| Permohonan Informasi Warga
|--------------------------------------------------------------------------
*/

Route::prefix('permohonan')
    ->name('public.permohonan.')
    ->middleware('auth:public')
    ->controller(PublikPermohonanController::class)
    ->group(function (): void {
        Route::get('/riwayat', 'index')
            ->name('index');

        Route::get('/', 'create')
            ->name('create');

        Route::post('/', 'store')
            ->name('store');
    });

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        /*
        |--------------------------------------------------------------------------
        | Autentikasi Admin
        |--------------------------------------------------------------------------
        */

        Route::controller(AuthController::class)
            ->group(function (): void {
                Route::get('/login', 'showLogin')
                    ->name('login');

                Route::post('/login', 'login')
                    ->middleware('throttle:5,1')
                    ->name('login.process');
            });

        /*
        |--------------------------------------------------------------------------
        | Area Admin
        |--------------------------------------------------------------------------
        */

        Route::middleware('admin.auth')
            ->group(function (): void {
                Route::get('/', function () {
                    return redirect()->route('admin.dashboard');
                })->name('home');

                Route::get('/dashboard', [DashboardController::class, 'index'])
                    ->name('dashboard');

                Route::post('/logout', [AuthController::class, 'logout'])
                    ->name('logout');

                /*
                |--------------------------------------------------------------------------
                | PPID Pembantu
                |--------------------------------------------------------------------------
                */

                Route::middleware('admin.role:1')
                    ->prefix('ppid-pembantu')
                    ->name('ppid-pembantu.')
                    ->controller(PpidPembantuController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/tambah', 'create')
                            ->name('create');

                        Route::post('/tambah', 'store')
                            ->name('store');

                        Route::get('/{id}/edit', 'edit')
                            ->whereNumber('id')
                            ->name('edit');

                        Route::put('/{id}', 'update')
                            ->whereNumber('id')
                            ->name('update');

                        Route::delete('/{id}', 'destroy')
                            ->whereNumber('id')
                            ->name('destroy');
                    });

                /*
                |--------------------------------------------------------------------------
                | Akun Admin
                |--------------------------------------------------------------------------
                */

                Route::middleware('admin.role:1')
                    ->prefix('akun-admin')
                    ->name('akun-admin.')
                    ->controller(AuthController::class)
                    ->group(function (): void {
                        Route::get('/tambah', 'showRegister')
                            ->name('create');

                        Route::post('/tambah', 'register')
                            ->name('store');
                    });

                /*
                |--------------------------------------------------------------------------
                | Informasi Publik Admin
                |--------------------------------------------------------------------------
                */

                Route::middleware('admin.role:1,2')
                    ->prefix('informasi-publik')
                    ->name('informasi-publik.')
                    ->controller(AdminInformasiPublikController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/tambah', 'create')
                            ->name('create');

                        Route::post('/tambah', 'store')
                            ->name('store');

                        Route::delete('/{id}', 'destroy')
                            ->whereNumber('id')
                            ->name('destroy');
                    });

                Route::middleware('admin.role:1')
                    ->patch('/informasi-publik/{id}/verifikasi', [
                        AdminInformasiPublikController::class,
                        'verifikasi',
                    ])
                    ->whereNumber('id')
                    ->name('informasi-publik.verifikasi');

                /*
                |--------------------------------------------------------------------------
                | Berita Admin
                |--------------------------------------------------------------------------
                */

                Route::middleware('admin.role:1,2')
                    ->prefix('berita')
                    ->name('berita.')
                    ->controller(BeritaController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/tambah', 'create')
                            ->name('create');

                        Route::post('/tambah', 'store')
                            ->name('store');

                        Route::delete('/{id}', 'destroy')
                            ->whereNumber('id')
                            ->name('destroy');
                    });

                /*
                |--------------------------------------------------------------------------
                | Pejabat Admin
                |--------------------------------------------------------------------------
                */

                Route::middleware('admin.role:1')
                    ->prefix('pejabat')
                    ->name('pejabat.')
                    ->controller(PejabatController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/tambah', 'create')
                            ->name('create');

                        Route::post('/tambah', 'store')
                            ->name('store');

                        Route::get('/{id}/edit', 'edit')
                            ->whereNumber('id')
                            ->name('edit');

                        Route::put('/{id}', 'update')
                            ->whereNumber('id')
                            ->name('update');

                        Route::delete('/{id}', 'destroy')
                            ->whereNumber('id')
                            ->name('destroy');
                    });

                /*
                |--------------------------------------------------------------------------
                | Slider Admin
                |--------------------------------------------------------------------------
                */

                Route::middleware('admin.role:1')
                    ->prefix('slider')
                    ->name('slider.')
                    ->controller(SliderController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/tambah', 'create')
                            ->name('create');

                        Route::post('/tambah', 'store')
                            ->name('store');

                        Route::get('/{id}/edit', 'edit')
                            ->whereNumber('id')
                            ->name('edit');

                        Route::put('/{id}', 'update')
                            ->whereNumber('id')
                            ->name('update');

                        Route::delete('/{id}', 'destroy')
                            ->whereNumber('id')
                            ->name('destroy');
                    });

                /*
                |--------------------------------------------------------------------------
                | Pesan Masuk Admin
                |--------------------------------------------------------------------------
                */

                Route::middleware('admin.role:1')
                    ->prefix('pesan-masuk')
                    ->name('pesan-masuk.')
                    ->controller(PesanMasukController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/{id}', 'show')
                            ->whereNumber('id')
                            ->name('show');

                        Route::get('/{id}/messages', 'messages')
                            ->whereNumber('id')
                            ->name('messages');

                        Route::post('/{id}/balas', 'reply')
                            ->whereNumber('id')
                            ->name('reply');

                        Route::post('/{id}/tutup', 'close')
                            ->whereNumber('id')
                            ->name('close');

                        Route::delete('/{id}', 'destroy')
                            ->whereNumber('id')
                            ->name('destroy');
                    });

                /*
                |--------------------------------------------------------------------------
                | Permohonan Informasi Admin
                |--------------------------------------------------------------------------
                */

                Route::middleware('admin.role:1,2')
                    ->prefix('permohonan')
                    ->name('permohonan.')
                    ->controller(AdminPermohonanController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/{id}', 'show')
                            ->whereNumber('id')
                            ->name('show');
                    });

                Route::middleware('admin.role:1')
                    ->prefix('permohonan')
                    ->name('permohonan.')
                    ->controller(AdminPermohonanController::class)
                    ->group(function (): void {
                        Route::post('/{id}/teruskan', 'teruskan')
                            ->whereNumber('id')
                            ->name('teruskan');

                        Route::post('/{id}/validasi', 'validasi')
                            ->whereNumber('id')
                            ->name('validasi');

                        Route::post('/{id}/revisi', 'revisi')
                            ->whereNumber('id')
                            ->name('revisi');
                    });

                Route::middleware('admin.role:2')
                    ->post('/permohonan/{id}/jawab-pembantu', [
                        AdminPermohonanController::class,
                        'jawabPembantu',
                    ])
                    ->whereNumber('id')
                    ->name('permohonan.jawab-pembantu');
            });
        /*
|--------------------------------------------------------------------------
| FAQ Admin
|--------------------------------------------------------------------------
| Khusus admin utama untuk mengelola FAQ.
*/

        Route::middleware('admin.role:1')
            ->prefix('faq')
            ->name('faq.')
            ->controller(AdminFaqController::class)
            ->group(function (): void {
                Route::get('/', 'index')
                    ->name('index');

                Route::get('/tambah', 'create')
                    ->name('create');

                Route::post('/tambah', 'store')
                    ->name('store');

                Route::get('/{id}/edit', 'edit')
                    ->whereNumber('id')
                    ->name('edit');

                Route::put('/{id}', 'update')
                    ->whereNumber('id')
                    ->name('update');

                Route::delete('/{id}', 'destroy')
                    ->whereNumber('id')
                    ->name('destroy');
            });
    });
