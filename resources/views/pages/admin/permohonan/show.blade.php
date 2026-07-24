@extends('layouts.admin.app')

@section('title', 'Detail Permohonan Informasi')

@section('content')
    @php
        $adminRole = (int) data_get($admin, 'role', 0);

        $status = trim((string) data_get($permohonan, 'status', 'Diajukan'));
        $status = $status !== '' ? $status : 'Diajukan';

        $formatDateTime = static function ($value): string {
            if ($value === null || $value === '') {
                return '-';
            }

            try {
                if (is_numeric($value) && (int) $value > 100000000) {
                    return \Illuminate\Support\Carbon::createFromTimestamp((int) $value)->translatedFormat(
                        'd F Y, H:i',
                    );
                }

                return \Illuminate\Support\Carbon::parse($value)->translatedFormat('d F Y, H:i');
            } catch (\Throwable $exception) {
                return (string) $value;
            }
        };

        $tanggalPermohonan = $formatDateTime(data_get($permohonan, 'tanggal'));
        $tanggalDiteruskan = $formatDateTime(data_get($permohonan, 'tanggal_diteruskan'));
        $tanggalJawabPembantu = $formatDateTime(data_get($permohonan, 'tanggal_jawab_pembantu'));
        $tanggalRevisi = $formatDateTime(data_get($permohonan, 'tanggal_revisi'));
        $tanggalValidasi = $formatDateTime(data_get($permohonan, 'tanggal_validasi'));
        $tanggalJawab = $formatDateTime(data_get($permohonan, 'tanggal_jawab'));
        $tanggalSelesai = $formatDateTime(data_get($permohonan, 'tanggal_selesai'));
        $createdAt = $formatDateTime(data_get($permohonan, 'created_at'));
        $updatedAt = $formatDateTime(data_get($permohonan, 'updated_at'));

        $isRejected = $status === 'Ditolak';
        $alasanPenolakan = $isRejected ? trim((string) data_get($permohonan, 'catatan_revisi')) : '';
        $tanggalPenolakan = $isRejected ? $tanggalRevisi : '-';

        $statusClasses = [
            'Diajukan' => [
                'badge' =>
                    'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/15 dark:text-blue-400 dark:ring-blue-500/20',
                'dot' => 'bg-blue-500',
                'icon' => 'text-blue-600 dark:text-blue-400',
                'iconBackground' => 'bg-blue-50 dark:bg-blue-500/15',
            ],
            'Diproses' => [
                'badge' =>
                    'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/15 dark:text-yellow-400 dark:ring-yellow-500/20',
                'dot' => 'bg-yellow-500',
                'icon' => 'text-yellow-600 dark:text-yellow-400',
                'iconBackground' => 'bg-yellow-50 dark:bg-yellow-500/15',
            ],
            'Diteruskan ke PPID Pembantu' => [
                'badge' =>
                    'bg-purple-50 text-purple-700 ring-purple-600/20 dark:bg-purple-500/15 dark:text-purple-400 dark:ring-purple-500/20',
                'dot' => 'bg-purple-500',
                'icon' => 'text-purple-600 dark:text-purple-400',
                'iconBackground' => 'bg-purple-50 dark:bg-purple-500/15',
            ],
            'Menunggu Validasi Admin Utama' => [
                'badge' =>
                    'bg-orange-50 text-orange-700 ring-orange-600/20 dark:bg-orange-500/15 dark:text-orange-400 dark:ring-orange-500/20',
                'dot' => 'bg-orange-500',
                'icon' => 'text-orange-600 dark:text-orange-400',
                'iconBackground' => 'bg-orange-50 dark:bg-orange-500/15',
            ],
            'Revisi PPID Pembantu' => [
                'badge' =>
                    'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/15 dark:text-red-400 dark:ring-red-500/20',
                'dot' => 'bg-red-500',
                'icon' => 'text-red-600 dark:text-red-400',
                'iconBackground' => 'bg-red-50 dark:bg-red-500/15',
            ],
            'Ditolak' => [
                'badge' =>
                    'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/15 dark:text-red-400 dark:ring-red-500/20',
                'dot' => 'bg-red-500',
                'icon' => 'text-red-600 dark:text-red-400',
                'iconBackground' => 'bg-red-50 dark:bg-red-500/15',
            ],
            'Selesai' => [
                'badge' =>
                    'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/15 dark:text-green-400 dark:ring-green-500/20',
                'dot' => 'bg-green-500',
                'icon' => 'text-green-600 dark:text-green-400',
                'iconBackground' => 'bg-green-50 dark:bg-green-500/15',
            ],
        ];

        $defaultStatusClass = [
            'badge' =>
                'bg-gray-100 text-gray-700 ring-gray-500/20 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-600/30',
            'dot' => 'bg-gray-400',
            'icon' => 'text-gray-600 dark:text-gray-400',
            'iconBackground' => 'bg-gray-100 dark:bg-gray-800',
        ];

        $currentStatusClass = $statusClasses[$status] ?? $defaultStatusClass;

        $snapshotValue = static function ($snapshot, $account, string $fallback = '-'): string {
            $snapshot = trim((string) $snapshot);

            if ($snapshot !== '') {
                return $snapshot;
            }

            $account = trim((string) $account);

            return $account !== '' ? $account : $fallback;
        };

        $kategoriPemohon = $snapshotValue(data_get($permohonan, 'kategori_pemohon'), null, '-');

        $pemohonNama = $snapshotValue(data_get($permohonan, 'nama_pemohon'), data_get($permohonan, 'userPublic.nama'));

        $pemohonEmail = $snapshotValue(
            data_get($permohonan, 'email_pemohon'),
            data_get($permohonan, 'userPublic.email'),
        );

        $pemohonTelepon = $snapshotValue(
            data_get($permohonan, 'telp_pemohon'),
            data_get($permohonan, 'userPublic.telp'),
        );

        $pemohonNik = $snapshotValue(data_get($permohonan, 'nomor_identitas'), data_get($permohonan, 'userPublic.nik'));

        $pemohonPekerjaan = $snapshotValue(
            data_get($permohonan, 'pekerjaan_pemohon'),
            data_get($permohonan, 'userPublic.pekerjaan'),
        );

        $pemohonAlamat = $snapshotValue(
            data_get($permohonan, 'alamat_pemohon'),
            data_get($permohonan, 'userPublic.alamat'),
        );

        $pemohonInitial = $pemohonNama !== '-' ? mb_strtoupper(mb_substr($pemohonNama, 0, 1)) : 'P';

        $caraMemperoleh = $snapshotValue(data_get($permohonan, 'cara_memperoleh'), null);

        $caraPengiriman = $snapshotValue(data_get($permohonan, 'cara_pengiriman'), null);

        $ppidPembantuNama = $snapshotValue(data_get($permohonan, 'ppidPembantu.nama'), null);

        $fileIdentitas = trim((string) data_get($permohonan, 'file_identitas'));
        $fileSuratKuasa = trim((string) data_get($permohonan, 'file_surat_kuasa'));
        $filePembantu = trim((string) data_get($permohonan, 'file_pembantu'));
        $fileJawaban = trim((string) data_get($permohonan, 'file_jawaban'));

        $localDisk = \Illuminate\Support\Facades\Storage::disk('local');

        $identitasExists = $fileIdentitas !== '' && $localDisk->exists($fileIdentitas);
        $suratKuasaExists = $fileSuratKuasa !== '' && $localDisk->exists($fileSuratKuasa);

        $identitasExtension = strtolower(pathinfo($fileIdentitas, PATHINFO_EXTENSION));
        $suratKuasaExtension = strtolower(pathinfo($fileSuratKuasa, PATHINFO_EXTENSION));

        $imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        $identitasIsImage = $identitasExists && in_array($identitasExtension, $imageExtensions, true);
        $suratKuasaIsImage = $suratKuasaExists && in_array($suratKuasaExtension, $imageExtensions, true);

        $identitasUrl = $identitasExists
            ? route('admin.permohonan.dokumen', [
                'id' => $permohonan->id,
                'jenis' => 'identitas',
            ])
            : null;

        $suratKuasaUrl = $suratKuasaExists
            ? route('admin.permohonan.dokumen', [
                'id' => $permohonan->id,
                'jenis' => 'surat-kuasa',
            ])
            : null;

        $filePembantuUrl = null;
        $fileJawabanUrl = null;

        if ($filePembantu !== '') {
            $filePembantuUrl = \Illuminate\Support\Str::startsWith($filePembantu, ['http://', 'https://'])
                ? $filePembantu
                : asset('storage/' . ltrim($filePembantu, '/'));
        }

        if ($fileJawaban !== '') {
            $fileJawabanUrl = \Illuminate\Support\Str::startsWith($fileJawaban, ['http://', 'https://'])
                ? $fileJawaban
                : asset('storage/' . ltrim($fileJawaban, '/'));
        }

        $canForward = $adminRole === 1 && in_array($status, ['Diajukan', 'Diproses'], true);
        $canReject = $adminRole === 1 && in_array($status, ['Diajukan', 'Diproses'], true);

        $canAnswerAsPpid =
            $adminRole === 2 && in_array($status, ['Diteruskan ke PPID Pembantu', 'Revisi PPID Pembantu'], true);

        $canValidate = $adminRole === 1 && $status === 'Menunggu Validasi Admin Utama';

        $identityRows = [
            ['label' => 'Kategori Pemohon', 'value' => $kategoriPemohon],
            ['label' => 'Nama Pemohon', 'value' => $pemohonNama],
            ['label' => 'Nomor Identitas', 'value' => $pemohonNik],
            ['label' => 'Email', 'value' => $pemohonEmail],
            ['label' => 'Nomor Telepon', 'value' => $pemohonTelepon],
            ['label' => 'Pekerjaan', 'value' => $pemohonPekerjaan],
            ['label' => 'Alamat', 'value' => $pemohonAlamat],
        ];

        $processRows = [
            ['label' => 'Tanggal Pengajuan', 'value' => $tanggalPermohonan],
            ['label' => 'Tanggal Diteruskan', 'value' => $tanggalDiteruskan],
            ['label' => 'Jawaban PPID Pembantu', 'value' => $tanggalJawabPembantu],
            [
                'label' => $isRejected ? 'Tanggal Penolakan' : 'Tanggal Revisi',
                'value' => $tanggalRevisi,
            ],
            ['label' => 'Tanggal Validasi', 'value' => $tanggalValidasi],
            ['label' => 'Tanggal Jawaban', 'value' => $tanggalJawab],
            ['label' => 'Tanggal Selesai', 'value' => $tanggalSelesai],
        ];
    @endphp

    <div class="space-y-6" x-data="{
        rejectionOpen: @js($errors->has('alasan_penolakan')),
        rejectionSubmitting: false
    }" @keydown.escape.window="if (!rejectionSubmitting) rejectionOpen = false">
        <x-admin.page-header title="Detail Permohonan Informasi"
            description="Lihat identitas lengkap pemohon, dokumen identitas, rincian permohonan, alur disposisi, laporan PPID Pembantu, dan jawaban final."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Permohonan Informasi',
                    'url' => route('admin.permohonan.index'),
                ],
                [
                    'label' => 'Detail Permohonan',
                ],
            ]">
            <x-slot:actions>
                <a href="{{ route('admin.permohonan.index') }}"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 shadow-theme-xs transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                    <i class="ri-arrow-left-line text-lg"></i>
                    <span>Kembali</span>
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <x-ui.flash-messages />

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 p-5 dark:border-red-500/20 dark:bg-red-500/10"
                role="alert">
                <div class="flex items-start gap-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600 dark:bg-red-500/15 dark:text-red-400">
                        <i class="ri-error-warning-line text-xl"></i>
                    </div>

                    <div class="min-w-0">
                        <h3 class="text-sm font-semibold text-red-800 dark:text-red-300">Data belum valid</h3>

                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm leading-6 text-red-700 dark:text-red-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <section
            class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
            <div
                class="pointer-events-none absolute -right-20 -top-24 h-64 w-64 rounded-full bg-blue-500/[0.07] blur-3xl dark:bg-blue-500/[0.1]">
            </div>

            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex min-w-0 items-start gap-4">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl {{ $currentStatusClass['iconBackground'] }} {{ $currentStatusClass['icon'] }}">
                        <i class="ri-file-list-3-line text-2xl"></i>
                    </div>

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset {{ $currentStatusClass['badge'] }}">
                                <span class="h-2 w-2 rounded-full {{ $currentStatusClass['dot'] }}"></span>
                                {{ $status }}
                            </span>

                            <span
                                class="inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                ID: {{ $permohonan->id }}
                            </span>
                        </div>

                        <h2
                            class="mt-3 break-words text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                            {{ $permohonan->no_pemohon ?? '-' }}
                        </h2>

                        <div
                            class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="inline-flex items-center gap-2">
                                <i class="ri-calendar-line"></i>
                                {{ $tanggalPermohonan }}
                            </span>

                            <span class="inline-flex items-center gap-2">
                                <i class="ri-user-line"></i>
                                {{ $pemohonNama }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex shrink-0 flex-col gap-3">
                    <div
                        class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/60">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-blue-600 shadow-theme-xs dark:bg-gray-800 dark:text-blue-400">
                            <i class="ri-calendar-check-line text-xl"></i>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Tanggal Pengajuan</p>
                            <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ $tanggalPermohonan }}
                            </p>
                        </div>
                    </div>

                    @if ($canReject)
                        <button type="button" @click.prevent.stop="rejectionOpen = true"
                            class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-red-600 px-5 text-sm font-semibold text-white shadow-theme-xs transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-500/20">
                            <i class="ri-close-circle-line text-lg"></i>
                            Tolak Permohonan
                        </button>
                    @endif
                </div>
            </div>
        </section>

        @if ($isRejected)
            <section
                class="overflow-hidden rounded-2xl border border-red-200 bg-white shadow-theme-xs dark:border-red-500/20 dark:bg-white/[0.03]">
                <div
                    class="flex items-center gap-3 border-b border-red-100 bg-red-50/70 px-5 py-4 dark:border-red-500/20 dark:bg-red-500/[0.08] sm:px-6">
                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400">
                        <i class="ri-close-circle-line text-2xl"></i>
                    </div>

                    <div>
                        <h3 class="text-base font-semibold text-red-900 dark:text-red-300">
                            Permohonan Informasi Ditolak
                        </h3>
                        <p class="mt-0.5 text-xs text-red-700 dark:text-red-400">
                            Ditolak pada {{ $tanggalPenolakan }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4 p-5 sm:p-6">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Alasan Penolakan
                        </p>

                        <div
                            class="mt-2 whitespace-pre-line rounded-2xl border border-red-100 bg-red-50/60 px-5 py-4 text-sm leading-7 text-red-900 dark:border-red-500/20 dark:bg-red-500/[0.08] dark:text-red-300">
                            {{ $alasanPenolakan !== '' ? $alasanPenolakan : 'Data atau dokumen permohonan belum memenuhi kelengkapan yang dipersyaratkan.' }}
                        </div>
                    </div>

                    <p class="text-sm leading-7 text-gray-600 dark:text-gray-400">
                        Status penolakan dan alasannya langsung tersedia pada halaman tiket publik. Sistem juga
                        mengirimkan pemberitahuan ke alamat email pemohon.
                    </p>
                </div>
            </section>
        @endif

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                            <i class="ri-user-search-line text-xl"></i>
                        </div>

                        <div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Identitas Pemohon</h3>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Data lengkap yang dikirim warga
                                melalui formulir permohonan.</p>
                        </div>
                    </div>

                    <dl class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($identityRows as $row)
                            <div class="grid grid-cols-1 gap-2 px-5 py-4 sm:grid-cols-[200px_minmax(0,1fr)] sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $row['label'] }}</dt>
                                <dd
                                    class="break-words whitespace-pre-line text-sm font-semibold leading-6 text-gray-800 dark:text-gray-200">
                                    {{ $row['value'] }}
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </section>

                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600 dark:bg-cyan-500/15 dark:text-cyan-400">
                            <i class="ri-id-card-line text-xl"></i>
                        </div>

                        <div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Dokumen Pemohon</h3>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Salinan identitas dan surat kuasa
                                yang tersimpan pada penyimpanan privat.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 sm:p-6 lg:grid-cols-2">
                        <article
                            class="overflow-hidden rounded-2xl border border-gray-200 bg-gray-50/70 dark:border-gray-700 dark:bg-gray-900/50">
                            <div
                                class="flex items-center justify-between gap-3 border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Salinan Identitas
                                    </h4>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Wajib diunggah saat
                                        permohonan dibuat.</p>
                                </div>

                                @if ($identitasExists)
                                    <span
                                        class="rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-semibold text-green-700 dark:bg-green-500/15 dark:text-green-400">Tersedia</span>
                                @else
                                    <span
                                        class="rounded-full bg-red-100 px-2.5 py-1 text-[11px] font-semibold text-red-700 dark:bg-red-500/15 dark:text-red-400">Tidak
                                        tersedia</span>
                                @endif
                            </div>

                            <div class="p-4">
                                @if ($identitasIsImage && $identitasUrl)
                                    <a href="{{ $identitasUrl }}" target="_blank" rel="noopener noreferrer"
                                        class="block overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                                        <img src="{{ $identitasUrl }}" alt="Salinan identitas pemohon"
                                            class="h-72 w-full object-contain" loading="lazy">
                                    </a>
                                @elseif ($identitasExists)
                                    <div
                                        class="flex h-72 flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white px-5 text-center dark:border-gray-700 dark:bg-gray-800">
                                        <i class="ri-file-pdf-2-line text-5xl text-red-500"></i>
                                        <p class="mt-3 text-sm font-semibold text-gray-800 dark:text-gray-200">Dokumen
                                            identitas tersedia</p>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format
                                            {{ strtoupper($identitasExtension ?: 'dokumen') }}</p>
                                    </div>
                                @else
                                    <div
                                        class="flex h-72 flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white px-5 text-center dark:border-gray-700 dark:bg-gray-800">
                                        <i class="ri-image-off-line text-5xl text-gray-400"></i>
                                        <p class="mt-3 text-sm font-semibold text-gray-700 dark:text-gray-300">File
                                            identitas tidak ditemukan</p>
                                    </div>
                                @endif

                                @if ($identitasUrl)
                                    <a href="{{ $identitasUrl }}" target="_blank" rel="noopener noreferrer"
                                        class="mt-4 inline-flex h-10 w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 text-sm font-semibold text-white transition hover:bg-blue-700">
                                        <i class="ri-external-link-line"></i>
                                        Buka Dokumen Identitas
                                    </a>
                                @endif
                            </div>
                        </article>

                        <article
                            class="overflow-hidden rounded-2xl border border-gray-200 bg-gray-50/70 dark:border-gray-700 dark:bg-gray-900/50">
                            <div
                                class="flex items-center justify-between gap-3 border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200">Surat Kuasa</h4>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Ditampilkan apabila diajukan
                                        melalui kuasa.</p>
                                </div>

                                @if ($suratKuasaExists)
                                    <span
                                        class="rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-semibold text-green-700 dark:bg-green-500/15 dark:text-green-400">Tersedia</span>
                                @else
                                    <span
                                        class="rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-400">Tidak
                                        diunggah</span>
                                @endif
                            </div>

                            <div class="p-4">
                                @if ($suratKuasaIsImage && $suratKuasaUrl)
                                    <a href="{{ $suratKuasaUrl }}" target="_blank" rel="noopener noreferrer"
                                        class="block overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                                        <img src="{{ $suratKuasaUrl }}" alt="Surat kuasa pemohon"
                                            class="h-72 w-full object-contain" loading="lazy">
                                    </a>
                                @elseif ($suratKuasaExists)
                                    <div
                                        class="flex h-72 flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white px-5 text-center dark:border-gray-700 dark:bg-gray-800">
                                        <i class="ri-file-pdf-2-line text-5xl text-red-500"></i>
                                        <p class="mt-3 text-sm font-semibold text-gray-800 dark:text-gray-200">Surat kuasa
                                            tersedia</p>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format
                                            {{ strtoupper($suratKuasaExtension ?: 'dokumen') }}</p>
                                    </div>
                                @else
                                    <div
                                        class="flex h-72 flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white px-5 text-center dark:border-gray-700 dark:bg-gray-800">
                                        <i class="ri-file-forbid-line text-5xl text-gray-400"></i>
                                        <p class="mt-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Surat kuasa
                                            tidak diunggah</p>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Dokumen ini bersifat
                                            opsional.</p>
                                    </div>
                                @endif

                                @if ($suratKuasaUrl)
                                    <a href="{{ $suratKuasaUrl }}" target="_blank" rel="noopener noreferrer"
                                        class="mt-4 inline-flex h-10 w-full items-center justify-center gap-2 rounded-lg bg-cyan-600 px-4 text-sm font-semibold text-white transition hover:bg-cyan-700">
                                        <i class="ri-external-link-line"></i>
                                        Buka Surat Kuasa
                                    </a>
                                @endif
                            </div>
                        </article>
                    </div>
                </section>

                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                            <i class="ri-file-text-line text-xl"></i>
                        </div>

                        <div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Rincian Permohonan</h3>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Informasi yang diminta, tujuan,
                                serta metode penerimaan informasi.</p>
                        </div>
                    </div>

                    <div class="space-y-5 p-5 sm:p-6">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Rincian Informasi</p>
                            <div
                                class="mt-2 whitespace-pre-line rounded-2xl border border-gray-200 bg-gray-50/70 px-5 py-4 text-sm leading-7 text-gray-700 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-300">
                                {{ $permohonan->rincian ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Tujuan Penggunaan
                                Informasi</p>
                            <div
                                class="mt-2 whitespace-pre-line rounded-2xl border border-gray-200 bg-gray-50/70 px-5 py-4 text-sm leading-7 text-gray-700 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-300">
                                {{ $permohonan->tujuan ?? '-' }}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div
                                class="rounded-2xl border border-gray-200 bg-gray-50/70 px-5 py-4 dark:border-gray-700 dark:bg-gray-900/50">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Cara Memperoleh</p>
                                <p class="mt-2 text-sm font-semibold leading-6 text-gray-800 dark:text-gray-200">
                                    {{ $caraMemperoleh }}</p>
                            </div>

                            <div
                                class="rounded-2xl border border-gray-200 bg-gray-50/70 px-5 py-4 dark:border-gray-700 dark:bg-gray-900/50">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Cara Pengiriman</p>
                                <p class="mt-2 text-sm font-semibold leading-6 text-gray-800 dark:text-gray-200">
                                    {{ $caraPengiriman }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-500/15 dark:text-purple-400">
                            <i class="ri-send-plane-line text-xl"></i>
                        </div>

                        <div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Disposisi Permohonan</h3>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Unit PPID tujuan dan catatan dari
                                Admin Utama.</p>
                        </div>
                    </div>

                    <dl class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div class="grid grid-cols-1 gap-2 px-5 py-4 sm:grid-cols-[200px_minmax(0,1fr)] sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">PPID Pembantu Tujuan</dt>
                            <dd class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $ppidPembantuNama }}
                            </dd>
                        </div>

                        <div class="grid grid-cols-1 gap-2 px-5 py-4 sm:grid-cols-[200px_minmax(0,1fr)] sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Admin Utama</dt>
                            <dd class="whitespace-pre-line text-sm leading-7 text-gray-700 dark:text-gray-300">
                                {{ $permohonan->catatan_utama ?? '-' }}</dd>
                        </div>
                    </dl>
                </section>

                @if ($permohonan->jawaban_pembantu || $filePembantu || $permohonan->tanggal_jawab_pembantu)
                    <section
                        class="overflow-hidden rounded-2xl border border-purple-200 bg-white shadow-theme-xs dark:border-purple-500/20 dark:bg-white/[0.03]">
                        <div
                            class="flex items-center gap-3 border-b border-purple-100 bg-purple-50/60 px-5 py-4 dark:border-purple-500/20 dark:bg-purple-500/[0.06] sm:px-6">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-500/15 dark:text-purple-400">
                                <i class="ri-file-check-line text-xl"></i>
                            </div>

                            <div>
                                <h3 class="text-base font-semibold text-purple-900 dark:text-purple-300">Laporan dari PPID
                                    Pembantu</h3>
                                <p class="mt-0.5 text-xs text-purple-700 dark:text-purple-400">Laporan yang dikirim untuk
                                    diperiksa Admin Utama.</p>
                            </div>
                        </div>

                        <div class="space-y-5 p-5 sm:p-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Dikirim pada <strong
                                    class="font-semibold text-gray-700 dark:text-gray-300">{{ $tanggalJawabPembantu }}</strong>
                            </p>

                            <div
                                class="whitespace-pre-line rounded-2xl border border-purple-100 bg-purple-50/40 px-5 py-4 text-sm leading-7 text-gray-700 dark:border-purple-500/20 dark:bg-purple-500/[0.05] dark:text-gray-300">
                                {{ $permohonan->jawaban_pembantu ?? '-' }}
                            </div>

                            @if ($filePembantuUrl)
                                <a href="{{ $filePembantuUrl }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-purple-200 bg-purple-50 px-4 text-sm font-semibold text-purple-700 transition hover:bg-purple-100 dark:border-purple-500/20 dark:bg-purple-500/10 dark:text-purple-400 dark:hover:bg-purple-500/20">
                                    <i class="ri-external-link-line"></i>
                                    Lihat File Laporan
                                </a>
                            @endif
                        </div>
                    </section>
                @endif

                @if (!$isRejected && $permohonan->catatan_revisi)
                    <section
                        class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5 dark:border-yellow-500/20 dark:bg-yellow-500/10 sm:p-6">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400">
                                <i class="ri-edit-2-line text-xl"></i>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-yellow-900 dark:text-yellow-300">Catatan Revisi</h3>
                                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-yellow-800 dark:text-yellow-400">
                                    {{ $permohonan->catatan_revisi }}</p>
                            </div>
                        </div>
                    </section>
                @endif

                @if ($permohonan->jawaban || $fileJawaban || $permohonan->tanggal_jawab)
                    <section
                        class="overflow-hidden rounded-2xl border border-green-200 bg-white shadow-theme-xs dark:border-green-500/20 dark:bg-white/[0.03]">
                        <div
                            class="flex items-center gap-3 border-b border-green-100 bg-green-50/60 px-5 py-4 dark:border-green-500/20 dark:bg-green-500/[0.06] sm:px-6">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100 text-green-600 dark:bg-green-500/15 dark:text-green-400">
                                <i class="ri-checkbox-circle-line text-xl"></i>
                            </div>

                            <div>
                                <h3 class="text-base font-semibold text-green-900 dark:text-green-300">Jawaban Final untuk
                                    Warga</h3>
                                <p class="mt-0.5 text-xs text-green-700 dark:text-green-400">Jawaban yang telah disahkan
                                    dan dikirim kepada pemohon.</p>
                            </div>
                        </div>

                        <div class="space-y-5 p-5 sm:p-6">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Dikirim pada <strong
                                    class="font-semibold text-gray-700 dark:text-gray-300">{{ $tanggalJawab }}</strong>
                            </p>

                            <div
                                class="whitespace-pre-line rounded-2xl border border-green-100 bg-green-50/40 px-5 py-4 text-sm leading-7 text-gray-700 dark:border-green-500/20 dark:bg-green-500/[0.05] dark:text-gray-300">
                                {{ $permohonan->jawaban ?? '-' }}
                            </div>

                            @if ($fileJawabanUrl)
                                <a href="{{ $fileJawabanUrl }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 text-sm font-semibold text-green-700 transition hover:bg-green-100 dark:border-green-500/20 dark:bg-green-500/10 dark:text-green-400 dark:hover:bg-green-500/20">
                                    <i class="ri-external-link-line"></i>
                                    Lihat File Jawaban
                                </a>
                            @endif
                        </div>
                    </section>
                @endif
            </div>

            <aside class="space-y-6">
                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Ringkasan Pemohon</h3>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-50 to-purple-50 text-lg font-bold text-blue-600 ring-1 ring-blue-100 dark:from-blue-500/15 dark:to-purple-500/15 dark:text-blue-400 dark:ring-blue-500/20">
                                {{ $pemohonInitial }}
                            </div>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-800 dark:text-white/90">
                                    {{ $pemohonNama }}</p>
                                <p class="mt-1 truncate text-xs text-gray-500 dark:text-gray-400">{{ $pemohonEmail }}</p>
                            </div>
                        </div>
                    </div>

                    <dl class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Kategori</dt>
                            <dd class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">{{ $kategoriPemohon }}</dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Nomor Identitas</dt>
                            <dd class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">{{ $pemohonNik }}</dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Nomor Telepon</dt>
                            <dd class="mt-1.5">
                                @if ($pemohonTelepon !== '-')
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $pemohonTelepon) }}"
                                        class="text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400">{{ $pemohonTelepon }}</a>
                                @else
                                    <span class="text-sm text-gray-700 dark:text-gray-300">-</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </section>

                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Detail Tiket</h3>
                    </div>

                    <dl class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Nomor Permohonan</dt>
                            <dd class="mt-1.5 break-words text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ $permohonan->no_pemohon ?? '-' }}</dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Status</dt>
                            <dd class="mt-2">
                                <span
                                    class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset {{ $currentStatusClass['badge'] }}">
                                    <span class="h-2 w-2 rounded-full {{ $currentStatusClass['dot'] }}"></span>
                                    {{ $status }}
                                </span>
                            </dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">PPID Tujuan</dt>
                            <dd class="mt-1.5 text-sm font-semibold leading-6 text-gray-800 dark:text-gray-200">
                                {{ $ppidPembantuNama }}</dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Dibuat</dt>
                            <dd class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">{{ $createdAt }}</dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Diperbarui</dt>
                            <dd class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">{{ $updatedAt }}</dd>
                        </div>
                    </dl>
                </section>

                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Riwayat Waktu</h3>
                    </div>

                    <dl class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($processRows as $row)
                            <div class="px-5 py-4">
                                <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">{{ $row['label'] }}
                                </dt>
                                <dd class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">{{ $row['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </section>
            </aside>
        </div>

        @if ($canForward)
            <section
                class="overflow-hidden rounded-2xl border border-blue-200 bg-white shadow-theme-xs dark:border-blue-500/20 dark:bg-white/[0.03]">
                <div
                    class="flex items-center gap-3 border-b border-blue-100 bg-blue-50/60 px-5 py-4 dark:border-blue-500/20 dark:bg-blue-500/[0.06] sm:px-6">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                        <i class="ri-send-plane-line text-xl"></i>
                    </div>

                    <div>
                        <h3 class="text-base font-semibold text-blue-900 dark:text-blue-300">Teruskan ke PPID Pembantu</h3>
                        <p class="mt-0.5 text-xs text-blue-700 dark:text-blue-400">Pilih unit PPID yang bertanggung jawab
                            dan berikan catatan disposisi.</p>
                    </div>
                </div>

                <form action="{{ route('admin.permohonan.teruskan', $permohonan->id) }}" method="POST"
                    x-data="{ submitting: false }" @submit="submitting = true" class="space-y-5 p-5 sm:p-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                        <div>
                            <label for="ppid_pembantuid"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                PPID Pembantu <span class="text-red-500">*</span>
                            </label>

                            <select id="ppid_pembantuid" name="ppid_pembantuid" required
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none transition focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Pilih PPID Pembantu</option>

                                @foreach ($ppidPembantu as $ppid)
                                    <option value="{{ $ppid->id }}" @selected((string) old('ppid_pembantuid', data_get($permohonan, 'ppid_pembantuid')) === (string) $ppid->id)>
                                        {{ $ppid->nama }}
                                    </option>
                                @endforeach
                            </select>

                            @error('ppid_pembantuid')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="catatan_utama"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan untuk
                                PPID Pembantu</label>

                            <textarea id="catatan_utama" name="catatan_utama" rows="5"
                                placeholder="Contoh: Mohon siapkan laporan sesuai rincian permohonan warga."
                                class="w-full resize-y rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm leading-6 text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('catatan_utama', $permohonan->catatan_utama) }}</textarea>

                            @error('catatan_utama')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-5 dark:border-gray-800">
                        <button type="submit" :disabled="submitting"
                            class="inline-flex h-11 min-w-[210px] items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 text-sm font-semibold text-white shadow-theme-xs transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60">
                            <i x-show="!submitting" class="ri-send-plane-line"></i>
                            <i x-cloak x-show="submitting" class="ri-loader-4-line animate-spin"></i>
                            <span x-text="submitting ? 'Memproses...' : 'Teruskan Permohonan'"></span>
                        </button>
                    </div>
                </form>
            </section>
        @endif

        @if ($canAnswerAsPpid)
            <section
                class="overflow-hidden rounded-2xl border border-purple-200 bg-white shadow-theme-xs dark:border-purple-500/20 dark:bg-white/[0.03]">
                <div
                    class="flex items-center gap-3 border-b border-purple-100 bg-purple-50/60 px-5 py-4 dark:border-purple-500/20 dark:bg-purple-500/[0.06] sm:px-6">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-500/15 dark:text-purple-400">
                        <i class="ri-upload-cloud-2-line text-xl"></i>
                    </div>

                    <div>
                        <h3 class="text-base font-semibold text-purple-900 dark:text-purple-300">Kirim Laporan ke Admin
                            Utama</h3>
                        <p class="mt-0.5 text-xs text-purple-700 dark:text-purple-400">Masukkan jawaban PPID Pembantu dan
                            unggah file pendukung bila tersedia.</p>
                    </div>
                </div>

                <form action="{{ route('admin.permohonan.jawab-pembantu', $permohonan->id) }}" method="POST"
                    enctype="multipart/form-data" x-data="{ submitting: false, fileName: '' }" @submit="submitting = true"
                    class="space-y-5 p-5 sm:p-6">
                    @csrf

                    <div>
                        <label for="jawaban_pembantu"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jawaban atau Laporan PPID Pembantu <span class="text-red-500">*</span>
                        </label>

                        <textarea id="jawaban_pembantu" name="jawaban_pembantu" rows="7" required
                            placeholder="Masukkan jawaban atau laporan hasil penelusuran informasi."
                            class="w-full resize-y rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm leading-7 text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-purple-300 focus:ring-3 focus:ring-purple-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('jawaban_pembantu', $permohonan->jawaban_pembantu) }}</textarea>

                        @error('jawaban_pembantu')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="file_pembantu"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">File Laporan</label>

                        <input id="file_pembantu" type="file" name="file_pembantu"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                            @change="fileName = $event.target.files[0]?.name ?? ''"
                            class="block w-full rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 file:mr-4 file:border-0 file:border-r file:border-gray-200 file:bg-gray-50 file:px-4 file:py-3 file:text-sm file:font-medium file:text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:file:border-gray-700 dark:file:bg-gray-800 dark:file:text-gray-300">

                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG,
                            atau PNG. Maksimal 5 MB.</p>

                        <p x-cloak x-show="fileName"
                            class="mt-2 text-xs font-semibold text-purple-600 dark:text-purple-400" x-text="fileName"></p>

                        @error('file_pembantu')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-5 dark:border-gray-800">
                        <button type="submit" :disabled="submitting"
                            class="inline-flex h-11 min-w-[210px] items-center justify-center gap-2 rounded-lg bg-purple-600 px-5 text-sm font-semibold text-white shadow-theme-xs transition hover:bg-purple-700 disabled:cursor-not-allowed disabled:opacity-60">
                            <i x-show="!submitting" class="ri-upload-cloud-2-line"></i>
                            <i x-cloak x-show="submitting" class="ri-loader-4-line animate-spin"></i>
                            <span x-text="submitting ? 'Mengirim...' : 'Kirim ke Admin Utama'"></span>
                        </button>
                    </div>
                </form>
            </section>
        @endif

        @if ($canValidate)
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <section
                    class="overflow-hidden rounded-2xl border border-green-200 bg-white shadow-theme-xs dark:border-green-500/20 dark:bg-white/[0.03]">
                    <div
                        class="flex items-center gap-3 border-b border-green-100 bg-green-50/60 px-5 py-4 dark:border-green-500/20 dark:bg-green-500/[0.06] sm:px-6">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100 text-green-600 dark:bg-green-500/15 dark:text-green-400">
                            <i class="ri-checkbox-circle-line text-xl"></i>
                        </div>

                        <div>
                            <h3 class="text-base font-semibold text-green-900 dark:text-green-300">Validasi dan Kirim ke
                                Warga</h3>
                            <p class="mt-0.5 text-xs text-green-700 dark:text-green-400">Periksa dan sempurnakan jawaban
                                sebelum dikirim.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.permohonan.validasi', $permohonan->id) }}" method="POST"
                        x-data="{ submitting: false }" @submit="submitting = true" class="space-y-5 p-5 sm:p-6">
                        @csrf

                        <div>
                            <label for="jawaban_final"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Jawaban Final untuk Warga <span class="text-red-500">*</span>
                            </label>

                            <textarea id="jawaban_final" name="jawaban_final" rows="9" required
                                placeholder="Masukkan jawaban final yang akan diterima warga."
                                class="w-full resize-y rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm leading-7 text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-green-300 focus:ring-3 focus:ring-green-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('jawaban_final', $permohonan->jawaban_pembantu) }}</textarea>

                            @error('jawaban_final')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" :disabled="submitting"
                            class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-5 text-sm font-semibold text-white shadow-theme-xs transition hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-60">
                            <i x-show="!submitting" class="ri-send-plane-2-line"></i>
                            <i x-cloak x-show="submitting" class="ri-loader-4-line animate-spin"></i>
                            <span x-text="submitting ? 'Mengirim...' : 'Validasi dan Kirim ke Warga'"></span>
                        </button>
                    </form>
                </section>

                <section
                    class="overflow-hidden rounded-2xl border border-yellow-200 bg-white shadow-theme-xs dark:border-yellow-500/20 dark:bg-white/[0.03]">
                    <div
                        class="flex items-center gap-3 border-b border-yellow-100 bg-yellow-50/60 px-5 py-4 dark:border-yellow-500/20 dark:bg-yellow-500/[0.06] sm:px-6">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400">
                            <i class="ri-edit-2-line text-xl"></i>
                        </div>

                        <div>
                            <h3 class="text-base font-semibold text-yellow-900 dark:text-yellow-300">Minta Revisi ke PPID
                                Pembantu</h3>
                            <p class="mt-0.5 text-xs text-yellow-700 dark:text-yellow-400">Jelaskan bagian laporan yang
                                harus dilengkapi atau diperbaiki.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.permohonan.revisi', $permohonan->id) }}" method="POST"
                        x-data="{ submitting: false }" @submit="submitting = true" class="space-y-5 p-5 sm:p-6">
                        @csrf

                        <div>
                            <label for="catatan_revisi"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Catatan Revisi <span class="text-red-500">*</span>
                            </label>

                            <textarea id="catatan_revisi" name="catatan_revisi" rows="9" required
                                placeholder="Jelaskan bagian laporan yang perlu diperbaiki."
                                class="w-full resize-y rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm leading-7 text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-yellow-300 focus:ring-3 focus:ring-yellow-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('catatan_revisi') }}</textarea>

                            @error('catatan_revisi')
                                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" :disabled="submitting"
                            class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-yellow-500 px-5 text-sm font-semibold text-white shadow-theme-xs transition hover:bg-yellow-600 disabled:cursor-not-allowed disabled:opacity-60">
                            <i x-show="!submitting" class="ri-arrow-go-back-line"></i>
                            <i x-cloak x-show="submitting" class="ri-loader-4-line animate-spin"></i>
                            <span x-text="submitting ? 'Mengirim...' : 'Kirim Permintaan Revisi'"></span>
                        </button>
                    </form>
                </section>
            </div>
        @endif

        @if ($canReject)
            <div x-cloak x-show="rejectionOpen" x-transition.opacity
                @keydown.escape.window="if (!rejectionSubmitting) rejectionOpen = false"
                class="fixed inset-0 z-[9999] flex items-center justify-center overflow-y-auto bg-gray-950/60 p-4"
                role="dialog" aria-modal="true" aria-labelledby="rejection-modal-title">
                <div x-show="rejectionOpen" x-transition @click.outside="if (!rejectionSubmitting) rejectionOpen = false"
                    class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-900">
                    <div
                        class="flex items-start justify-between gap-4 border-b border-red-100 bg-red-50 px-5 py-4 dark:border-red-500/20 dark:bg-red-500/[0.08] sm:px-6">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400">
                                <i class="ri-close-circle-line text-2xl"></i>
                            </div>

                            <div>
                                <h3 id="rejection-modal-title"
                                    class="text-base font-semibold text-red-900 dark:text-red-300">
                                    Tolak Permohonan Informasi
                                </h3>
                                <p class="mt-1 text-sm leading-6 text-red-700 dark:text-red-400">
                                    Penolakan akan langsung mengubah status tiket publik dan mengirim email kepada
                                    pemohon.
                                </p>
                            </div>
                        </div>

                        <button type="button" @click="if (!rejectionSubmitting) rejectionOpen = false"
                            :disabled="rejectionSubmitting"
                            class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-red-600 transition hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-50 dark:text-red-400 dark:hover:bg-red-500/15"
                            aria-label="Tutup modal">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.permohonan.tolak', ['id' => $permohonan->id]) }}" method="POST"
                        @submit="rejectionSubmitting = true" class="space-y-5 p-5 sm:p-6">
                        @csrf

                        <div
                            class="rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-sm leading-6 text-yellow-800 dark:border-yellow-500/20 dark:bg-yellow-500/10 dark:text-yellow-400">
                            Pastikan penolakan dilakukan karena data identitas, dokumen, atau persyaratan permohonan
                            belum lengkap atau tidak valid. Tindakan ini tidak dapat dilanjutkan ke PPID Pembantu.
                        </div>

                        <div>
                            <label for="alasan_penolakan"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>

                            <select id="alasan_penolakan_template"
                                @change="
                                    const value = $event.target.value;
                                    if (value !== '') {
                                        $refs.rejectionReason.value = value;
                                        $refs.rejectionReason.dispatchEvent(new Event('input'));
                                    }
                                "
                                class="mb-3 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none transition focus:border-red-300 focus:ring-3 focus:ring-red-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Pilih alasan cepat (opsional)</option>
                                <option value="Data pemohon belum lengkap sehingga permohonan belum dapat diproses.">
                                    Data pemohon belum lengkap
                                </option>
                                <option
                                    value="Data identitas pemohon tidak sesuai dengan dokumen identitas yang diunggah.">
                                    Identitas tidak sesuai
                                </option>
                                <option
                                    value="Dokumen identitas atau dokumen pendukung tidak valid atau tidak dapat diverifikasi.">
                                    Dokumen tidak valid
                                </option>
                                <option value="Data yang dicantumkan tidak sesuai dengan informasi yang sebenarnya.">
                                    Data tidak benar
                                </option>
                                <option value="Persyaratan permohonan informasi publik belum terpenuhi.">
                                    Persyaratan belum terpenuhi
                                </option>
                            </select>

                            <textarea x-ref="rejectionReason" id="alasan_penolakan" name="alasan_penolakan" rows="7" required
                                minlength="10" maxlength="5000"
                                placeholder="Jelaskan data atau dokumen yang belum lengkap, tidak sesuai, atau tidak valid."
                                class="w-full resize-y rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm leading-7 text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-red-300 focus:ring-3 focus:ring-red-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('alasan_penolakan') }}</textarea>

                            <div class="mt-1.5 flex items-start justify-between gap-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Minimal 10 karakter dan maksimal 5.000 karakter.
                                </p>

                                @error('alasan_penolakan')
                                    <p class="text-right text-xs font-medium text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div
                            class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 dark:border-gray-800 sm:flex-row sm:justify-end">
                            <button type="button" @click="rejectionOpen = false" :disabled="rejectionSubmitting"
                                class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-300 bg-white px-5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                                Batal
                            </button>

                            <button type="submit" :disabled="rejectionSubmitting"
                                class="inline-flex h-11 min-w-[190px] items-center justify-center gap-2 rounded-lg bg-red-600 px-5 text-sm font-semibold text-white shadow-theme-xs transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60">
                                <i x-show="!rejectionSubmitting" class="ri-close-circle-line"></i>
                                <i x-cloak x-show="rejectionSubmitting" class="ri-loader-4-line animate-spin"></i>
                                <span x-text="rejectionSubmitting ? 'Memproses...' : 'Tolak Permohonan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
