<?php

use App\Http\Controllers\Admin\AccountSettingController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DokumentasiController as AdminInformasiPublikController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\PejabatController;
use App\Http\Controllers\Admin\PengadaanController;
use App\Http\Controllers\Admin\PermohonanController as AdminPermohonanController;
use App\Http\Controllers\Admin\PesanMasukController;
use App\Http\Controllers\Admin\PpidPembantuController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Auth\UnifiedLoginController;
use App\Http\Controllers\Publik\AccountActivationController;
use App\Http\Controllers\Publik\AuthController as PublikAuthController;
use App\Http\Controllers\Publik\BeritaController as PublikBeritaController;
use App\Http\Controllers\Publik\FaqController as PublikFaqController;
use App\Http\Controllers\Publik\HomeController;
use App\Http\Controllers\Publik\InformasiController;
use App\Http\Controllers\Publik\KtpOcrController;
use App\Http\Controllers\Publik\PermohonanController as PublikPermohonanController;
use App\Http\Controllers\Publik\PesanController as PublikPesanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HALAMAN UTAMA & PUBLIK
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('beranda');

/*
|--------------------------------------------------------------------------
| LOGIN SATU PINTU
|--------------------------------------------------------------------------
*/

Route::get(
    '/login',
    [UnifiedLoginController::class, 'showLogin']
)
    ->middleware('guest')
    ->name('login');

Route::post(
    '/login',
    [UnifiedLoginController::class, 'login']
)
    ->middleware([
        'guest',
        'throttle:5,1',
    ])
    ->name('login.process');

Route::post(
    '/logout',
    [UnifiedLoginController::class, 'logout']
)
    ->middleware('auth')
    ->name('public.logout');

/*
|--------------------------------------------------------------------------
| INFORMASI, BERITA, FAQ & PESAN (PUBLIK)
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
            ->where(
                'slug',
                '[A-Za-z0-9\-]+'
            )
            ->name('show');
    });

Route::get(
    '/faq',
    [PublikFaqController::class, 'index']
)->name('public.faq.index');

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
            ->where(
                'token',
                '[A-Za-z0-9]+'
            )
            ->name('show');

        Route::get('/cek/{token}/messages', 'messages')
            ->where(
                'token',
                '[A-Za-z0-9]+'
            )
            ->name('messages');

        Route::post('/cek/{token}/reply', 'reply')
            ->where(
                'token',
                '[A-Za-z0-9]+'
            )
            ->middleware('throttle:10,1')
            ->name('reply');
    });

/*
|--------------------------------------------------------------------------
| REGISTRASI & AKTIVASI AKUN WARGA
|--------------------------------------------------------------------------
*/

Route::prefix('warga')
    ->controller(PublikAuthController::class)
    ->group(function (): void {
        Route::get('/register', 'showRegister')
            ->name('public.register');

        Route::post('/register', 'register')
            ->middleware('throttle:5,1')
            ->name('public.register.store');
    });

Route::prefix('warga/aktivasi')
    ->name('public.aktivasi.')
    ->middleware('guest:public')
    ->controller(AccountActivationController::class)
    ->group(function (): void {
        Route::get('/kirim-ulang', 'showResend')
            ->name('resend.form');

        Route::post('/kirim-ulang', 'resend')
            ->middleware('throttle:3,60')
            ->name('resend');

        Route::get('/{token}', 'show')
            ->where(
                'token',
                '[A-Za-z0-9]{64}'
            )
            ->name('show');

        Route::post('/{token}', 'activate')
            ->where(
                'token',
                '[A-Za-z0-9]{64}'
            )
            ->middleware('throttle:5,1')
            ->name('store');
    });

/*
|--------------------------------------------------------------------------
| PERMOHONAN & OCR KTP (PUBLIK)
|--------------------------------------------------------------------------
*/

Route::prefix('permohonan')
    ->name('public.permohonan.')
    ->group(function (): void {
        Route::post(
            '/baca-ktp',
            [KtpOcrController::class, 'scan']
        )
            ->middleware([
                'guest:public',
                'throttle:5,1',
            ])
            ->name('ocr');

        Route::controller(
            PublikPermohonanController::class
        )->group(function (): void {
            Route::get('/', 'create')
                ->name('create');

            Route::post('/', 'store')
                ->middleware('throttle:5,1')
                ->name('store');

            Route::get('/cek/{token}', 'show')
                ->where(
                    'token',
                    '[A-Za-z0-9]{64}'
                )
                ->name('show');

            Route::get('/riwayat', 'index')
                ->middleware('auth:public')
                ->name('index');
        });
    });

/*
|--------------------------------------------------------------------------
| PANEL ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware('admin.auth')
    ->group(function (): void {
        Route::get('/', function () {
            return redirect()->route(
                'admin.dashboard'
            );
        })->name('home');

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');

        Route::post(
            '/logout',
            [UnifiedLoginController::class, 'logout']
        )->name('logout');

        Route::prefix('account-settings')
            ->name('account-settings.')
            ->controller(AccountSettingController::class)
            ->group(function (): void {
                Route::get('/', 'index')
                    ->name('index');

                Route::put('/profile', 'updateProfile')
                    ->name('profile.update');

                Route::put('/password', 'updatePassword')
                    ->name('password.update');
            });

        /*
        |--------------------------------------------------------------------------
        | MODUL SUPER ADMIN (ROLE 1)
        |--------------------------------------------------------------------------
        */

        Route::middleware('admin.role:1')
            ->group(function (): void {
                Route::prefix('ppid-pembantu')
                    ->name('ppid-pembantu.')
                    ->controller(PpidPembantuController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/tambah', 'create')
                            ->name('create');

                        Route::post('/tambah', 'store')
                            ->name('store');

                        Route::get('/{id}', 'show')
                            ->whereNumber('id')
                            ->name('show');

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

                Route::prefix('akun-admin')
                    ->name('akun-admin.')
                    ->controller(AuthController::class)
                    ->group(function (): void {
                        Route::get('/tambah', 'showRegister')
                            ->name('create');

                        Route::post('/tambah', 'register')
                            ->name('store');
                    });

                Route::patch(
                    '/informasi-publik/{id}/verifikasi',
                    [
                        AdminInformasiPublikController::class,
                        'verifikasi',
                    ]
                )
                    ->whereNumber('id')
                    ->name('informasi-publik.verifikasi');

                Route::prefix('pejabat')
                    ->name('pejabat.')
                    ->controller(PejabatController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/tambah', 'create')
                            ->name('create');

                        Route::post('/tambah', 'store')
                            ->name('store');

                        Route::get('/{id}', 'show')
                            ->whereNumber('id')
                            ->name('show');

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

                Route::prefix('slider')
                    ->name('slider.')
                    ->controller(SliderController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/tambah', 'create')
                            ->name('create');

                        Route::post('/tambah', 'store')
                            ->name('store');

                        Route::get('/{id}', 'show')
                            ->whereNumber('id')
                            ->name('show');

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

                Route::prefix('pesan-masuk')
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

                Route::prefix('faq')
                    ->name('faq.')
                    ->controller(AdminFaqController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/tambah', 'create')
                            ->name('create');

                        Route::post('/tambah', 'store')
                            ->name('store');

                        Route::get('/{id}', 'show')
                            ->whereNumber('id')
                            ->name('show');

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

        /*
        |--------------------------------------------------------------------------
        | MODUL BERSAMA (ROLE 1 & 2)
        |--------------------------------------------------------------------------
        */

        Route::middleware('admin.role:1,2')
            ->group(function (): void {
                Route::prefix('informasi-publik')
                    ->name('informasi-publik.')
                    ->controller(
                        AdminInformasiPublikController::class
                    )
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/tambah', 'create')
                            ->name('create');

                        Route::post('/tambah', 'store')
                            ->name('store');

                        Route::get('/{id}', 'show')
                            ->whereNumber('id')
                            ->name('show');

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

                Route::prefix('berita')
                    ->name('berita.')
                    ->controller(BeritaController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/tambah', 'create')
                            ->name('create');

                        Route::post('/tambah', 'store')
                            ->name('store');

                        Route::get('/{id}', 'show')
                            ->whereNumber('id')
                            ->name('show');

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

                Route::prefix('pengadaan')
                    ->name('pengadaan.')
                    ->controller(PengadaanController::class)
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/tambah', 'create')
                            ->name('create');

                        Route::post('/tambah', 'store')
                            ->name('store');

                        Route::get('/{id}', 'show')
                            ->whereNumber('id')
                            ->name('show');

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

                Route::prefix('permohonan')
                    ->name('permohonan.')
                    ->controller(
                        AdminPermohonanController::class
                    )
                    ->group(function (): void {
                        Route::get('/', 'index')
                            ->name('index');

                        Route::get(
                            '/{id}/dokumen/{jenis}',
                            'dokumen'
                        )
                            ->whereNumber('id')
                            ->where(
                                'jenis',
                                'identitas|surat-kuasa'
                            )
                            ->name('dokumen');

                        Route::get('/{id}', 'show')
                            ->whereNumber('id')
                            ->name('show');
                    });
            });

        /*
        |--------------------------------------------------------------------------
        | SPESIFIK AKSI PERMOHONAN
        |--------------------------------------------------------------------------
        */
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

                Route::post('/{id}/tolak', 'tolak')
                    ->whereNumber('id')
                    ->name('tolak');
            });

            
        Route::middleware('admin.role:2')
            ->post(
                '/permohonan/{id}/jawab-pembantu',
                [
                    AdminPermohonanController::class,
                    'jawabPembantu',
                ]
            )
            ->whereNumber('id')
            ->name('permohonan.jawab-pembantu');

        Route::prefix('notifikasi')
            ->name('notifikasi.')
            ->controller(NotifikasiController::class)
            ->group(function (): void {
                Route::get('/', 'index')
                    ->name('index');

                Route::patch(
                    '/baca-semua',
                    'tandaiSemuaDibaca'
                )->name('baca-semua');

                Route::delete(
                    '/hapus-semua-dibaca',
                    'hapusSemuaDibaca'
                )->name('hapus-semua-dibaca');

                Route::patch('/{id}/buka', 'buka')
                    ->whereUuid('id')
                    ->name('buka');

                Route::patch('/{id}/baca', 'tandaiDibaca')
                    ->whereUuid('id')
                    ->name('baca');

                Route::delete('/{id}', 'destroy')
                    ->whereUuid('id')
                    ->name('destroy');
            });
    });
