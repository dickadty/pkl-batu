@extends('layouts.admin.app')

@section('title', 'Detail Keberatan')

@section('content')
    @php
        $formatDate = static function ($value): string {
            if (blank($value)) {
                return '-';
            }

            try {
                return \Illuminate\Support\Carbon::parse($value)->translatedFormat('d F Y, H:i');
            } catch (\Throwable $exception) {
                return (string) $value;
            }
        };

        $status = trim((string) ($keberatan->status ?: 'Diajukan'));
        $pemohon =
            data_get($keberatan, 'permohonan.userPublic.nama') ?? data_get($keberatan, 'permohonan.nama_pemohon', '-');
        $tanggalPengajuan = $formatDate($keberatan->tanggal_pengajuan);
        $tanggalDiteruskan = $formatDate($keberatan->tanggal_diproses);
        $tanggalJawabanPelaksana = $formatDate($keberatan->tanggal_jawab_pembantu);
        $tanggalSelesai = $formatDate($keberatan->tanggal_selesai);
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Detail Keberatan" description="Periksa alasan keberatan dan tindak lanjut keberatan."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'url' => route('admin.keberatan.index'),
                ],
                [
                    'label' => 'Detail Keberatan',
                ],
            ]" />

        <x-ui.flash-messages />

        @if ($errors->any())
            <div
                class="
                    rounded-xl
                    border
                    border-red-200
                    bg-red-50
                    p-4
                    dark:border-red-900/50
                    dark:bg-red-500/10
                ">
                <div
                    class="
                        flex
                        items-start
                        gap-3
                    ">
                    <i
                        class="
                            ri-error-warning-line
                            mt-0.5
                            text-lg
                            text-red-600
                            dark:text-red-400
                        "></i>

                    <div>
                        <p
                            class="
                                text-sm
                                font-semibold
                                text-red-800
                                dark:text-red-300
                            ">
                            Terdapat data yang belum valid.
                        </p>

                        <ul
                            class="
                                mt-2
                                list-disc
                                space-y-1
                                pl-5
                                text-sm
                                text-red-700
                                dark:text-red-400
                            ">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <section
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                            {{ $status }}
                        </span>
                        <span
                            class="rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                            ID: {{ $keberatan->id }}
                        </span>
                    </div>

                    <h2
                        class="mt-3 break-words text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                        {{ $keberatan->no_keberatan }}
                    </h2>

                    <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center gap-2"><i
                                class="ri-calendar-line"></i>{{ $tanggalPengajuan }}</span>
                        <span class="inline-flex items-center gap-2"><i class="ri-user-line"></i>{{ $pemohon }}</span>
                    </div>
                </div>

                <div class="grid shrink-0 grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-2">
                    <div
                        class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/60">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Pengajuan</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $tanggalPengajuan }}</p>
                    </div>
                    <div
                        class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-900/60">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Diteruskan</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $tanggalDiteruskan }}</p>
                    </div>
                </div>
            </div>
        </section>

        <div
            class="
                grid
                grid-cols-1
                gap-6
                xl:grid-cols-3
            ">
            {{-- Detail Keberatan --}}
            <div class="
                    space-y-6
                    xl:col-span-2
                ">
                <section
                    class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-4 dark:border-gray-800">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                            <i class="ri-user-search-line text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Identitas Pemohon</h3>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Data warga dan permohonan yang
                                menjadi dasar keberatan.</p>
                        </div>
                    </div>
                    <dl class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div class="grid grid-cols-1 gap-2 px-5 py-4 sm:grid-cols-[200px_minmax(0,1fr)]">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Pemohon</dt>
                            <dd class="break-words text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ $pemohon }}</dd>
                        </div>
                        <div class="grid grid-cols-1 gap-2 px-5 py-4 sm:grid-cols-[200px_minmax(0,1fr)]">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="break-words text-sm text-gray-700 dark:text-gray-300">
                                {{ data_get($keberatan, 'permohonan.email_pemohon') ?? data_get($keberatan, 'permohonan.userPublic.email', '-') }}
                            </dd>
                        </div>
                        <div class="grid grid-cols-1 gap-2 px-5 py-4 sm:grid-cols-[200px_minmax(0,1fr)]">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Permohonan</dt>
                            <dd class="break-words text-sm font-semibold text-gray-800 dark:text-gray-200">
                                @if ($keberatan->permohonanid && $keberatan->permohonan)
                                    <a href="{{ route('admin.permohonan.show', ['id' => $keberatan->permohonanid]) }}"
                                        class="inline-flex items-center gap-1.5 text-brand-600 transition hover:text-brand-700 hover:underline dark:text-brand-400 dark:hover:text-brand-300">
                                        {{ $keberatan->permohonan->no_pemohon ?? '-' }}
                                        <i class="ri-external-link-line text-sm"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                    </dl>
                </section>

                <div
                    class="
                        rounded-2xl
                        border
                        border-gray-200
                        bg-white
                        p-6
                        shadow-theme-sm
                        dark:border-gray-800
                        dark:bg-gray-900
                    ">
                    <div
                        class="
                            flex
                            flex-col
                            gap-4
                            border-b
                            border-gray-100
                            pb-5
                            dark:border-gray-800
                            sm:flex-row
                            sm:items-start
                            sm:justify-between
                        ">
                        <div>
                            <p
                                class="
                                    text-sm
                                    font-semibold
                                    text-brand-600
                                ">
                                {{ $keberatan->no_keberatan }}
                            </p>

                            <h2
                                class="
                                    mt-2
                                    text-xl
                                    font-bold
                                    text-gray-900
                                    dark:text-white
                                ">
                                Keberatan atas
                                {{ data_get($keberatan, 'permohonan.no_pemohon') ?? '-' }}
                            </h2>
                        </div>

                        @if ($keberatan->status === \App\Models\Keberatan::STATUS_DIAJUKAN)
                            <span
                                class="
                                    inline-flex
                                    w-fit
                                    rounded-full
                                    bg-yellow-50
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    text-yellow-700
                                    dark:bg-yellow-500/15
                                    dark:text-yellow-400
                                ">
                                {{ $keberatan->status }}
                            </span>
                        @elseif ($keberatan->status === \App\Models\Keberatan::STATUS_DIPROSES)
                            <span
                                class="
                                    inline-flex
                                    w-fit
                                    rounded-full
                                    bg-blue-50
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    text-blue-700
                                    dark:bg-blue-500/15
                                    dark:text-blue-400
                                ">
                                {{ $keberatan->status }}
                            </span>
                        @else
                            <span
                                class="
                                    inline-flex
                                    w-fit
                                    rounded-full
                                    bg-green-50
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    text-green-700
                                    dark:bg-green-500/15
                                    dark:text-green-400
                                ">
                                {{ $keberatan->status }}
                            </span>
                        @endif
                    </div>

                    <dl
                        class="
                            mt-6
                            grid
                            grid-cols-1
                            gap-6
                            md:grid-cols-2
                        ">
                        <div>
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Pemohon
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-400
                                ">
                                {{ data_get($keberatan, 'permohonan.userPublic.nama') ??
                                    (data_get($keberatan, 'permohonan.nama_pemohon') ?? '-') }}
                            </dd>
                        </div>

                        <div>
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Nomor Permohonan
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-400
                                ">
                                {{ data_get($keberatan, 'permohonan.no_pemohon') ?? '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Unit PPID Pelaksana
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-400
                                ">
                                {{ data_get($keberatan, 'ppidPembantu.nama') ?? 'Belum ditugaskan' }}
                            </dd>
                        </div>

                        <div>
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Tanggal Pengajuan
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-400
                                ">
                                {{ $keberatan->tanggal_pengajuan?->locale('id')->translatedFormat('d F Y') ?? '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Admin Penanggap
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-400
                                ">
                                {{ $keberatan->admin?->username ?? '-' }}
                            </dd>
                        </div>

                        <div>
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Status
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                    dark:text-gray-400
                                ">
                                {{ $keberatan->status }}
                            </dd>
                        </div>

                        <div class="md:col-span-2">
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Alasan Keberatan
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    whitespace-pre-line
                                    rounded-xl
                                    bg-gray-50
                                    p-4
                                    text-sm
                                    leading-7
                                    text-gray-700
                                    dark:bg-gray-800
                                    dark:text-gray-300
                                ">
                                {{ $keberatan->alasan ?: '-' }}
                            </dd>
                        </div>

                        @if ($keberatan->catatan_utama)
                            <div class="md:col-span-2">
                                <dt class="text-sm font-semibold text-gray-700 dark:text-gray-300">Catatan Admin Utama</dt>
                                <dd
                                    class="mt-2 whitespace-pre-line rounded-xl border border-blue-100 bg-blue-50/60 p-4 text-sm leading-7 text-blue-900 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-300">
                                    {{ $keberatan->catatan_utama }}</dd>
                            </div>
                        @endif

                        @if ($keberatan->jawaban_pembantu)
                            <div class="md:col-span-2">
                                <dt class="text-sm font-semibold text-gray-700 dark:text-gray-300">Laporan PPID Pelaksana
                                </dt>
                                <dd
                                    class="mt-2 whitespace-pre-line rounded-xl border border-purple-100 bg-purple-50/60 p-4 text-sm leading-7 text-purple-900 dark:border-purple-500/20 dark:bg-purple-500/10 dark:text-purple-300">
                                    {{ $keberatan->jawaban_pembantu }}</dd>
                            </div>
                        @endif

                        @if ($keberatan->hasil)
                            <div>
                                <dt
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                        dark:text-gray-300
                                    ">
                                    Hasil Keberatan
                                </dt>

                                <dd
                                    class="
                                        mt-2
                                        text-sm
                                        text-gray-600
                                        dark:text-gray-400
                                    ">
                                    {{ $keberatan->hasil }}
                                </dd>
                            </div>
                        @endif

                        @if ($keberatan->jenis_tindak_lanjut)
                            <div>
                                <dt
                                    class="
                                        text-sm
                                        font-semibold
                                        text-gray-700
                                        dark:text-gray-300
                                    ">
                                    Jenis Tindak Lanjut
                                </dt>

                                <dd
                                    class="
                                        mt-2
                                        text-sm
                                        text-gray-600
                                        dark:text-gray-400
                                    ">
                                    {{ $keberatan->jenis_tindak_lanjut }}
                                </dd>
                            </div>
                        @endif

                        <div class="md:col-span-2">
                            <dt
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                Tanggapan
                            </dt>

                            <dd
                                class="
                                    mt-2
                                    whitespace-pre-line
                                    rounded-xl
                                    border
                                    border-gray-200
                                    p-4
                                    text-sm
                                    leading-7
                                    text-gray-700
                                    dark:border-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $keberatan->tanggapan ?: 'Belum ada tanggapan.' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Panel Tindakan --}}
            <div>
                <section
                    class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Riwayat Waktu</h3>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Jejak proses keberatan dari pengajuan
                            sampai penyelesaian.</p>
                    </div>
                    <dl class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Diajukan Warga</dt>
                            <dd class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">{{ $tanggalPengajuan }}</dd>
                        </div>
                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Diteruskan ke PPID
                                Pelaksana</dt>
                            <dd class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">{{ $tanggalDiteruskan }}</dd>
                        </div>
                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Jawaban PPID Pelaksana
                            </dt>
                            <dd class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">{{ $tanggalJawabanPelaksana }}</dd>
                        </div>
                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Tanggapan ke Warga</dt>
                            <dd class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">
                                {{ $formatDate($keberatan->tanggal_tanggapan) }}</dd>
                        </div>
                        <div class="px-5 py-4">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-400">Selesai</dt>
                            <dd class="mt-1.5 text-sm text-gray-700 dark:text-gray-300">{{ $tanggalSelesai }}</dd>
                        </div>
                    </dl>
                </section>

                @if ($admin->isAdminUtama())
                    {{-- Status Diajukan --}}
                    @if ($keberatan->status === \App\Models\Keberatan::STATUS_DIAJUKAN)
                        <div
                            class="
                                overflow-hidden
                                rounded-2xl
                                border
                                border-gray-200
                                bg-white
                                shadow-theme-sm
                                dark:border-gray-800
                                dark:bg-gray-900
                            ">
                            <div
                                class="
                                    border-b
                                    border-gray-100
                                    px-5
                                    py-5
                                    dark:border-gray-800
                                ">
                                <h3
                                    class="
                                        text-lg
                                        font-semibold
                                        text-gray-800
                                        dark:text-white/90
                                    ">
                                    Teruskan ke PPID Pelaksana
                                </h3>

                                <p
                                    class="
                                        mt-1
                                        text-sm
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    Pilih unit PPID Pelaksana yang akan menangani keberatan ini.
                                </p>
                            </div>

                            <form action="{{ route('admin.keberatan.teruskan', ['id' => $keberatan->id]) }}"
                                method="POST" class="space-y-5 p-5">
                                @csrf

                                <div>
                                    <label for="ppid_pembantuid"
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Unit PPID Pelaksana <span class="text-red-500">*</span>
                                    </label>
                                    <select id="ppid_pembantuid" name="ppid_pembantuid" required
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                        <option value="">Pilih unit PPID Pelaksana</option>
                                        @foreach ($ppidPembantuList as $ppid)
                                            <option value="{{ $ppid->id }}" @selected(old('ppid_pembantuid') == $ppid->id)>
                                                {{ $ppid->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('ppid_pembantuid')
                                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="catatan_utama"
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Catatan untuk PPID Pelaksana
                                    </label>
                                    <textarea id="catatan_utama" name="catatan_utama" rows="4"
                                        class="w-full resize-y rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-800 outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('catatan_utama') }}</textarea>
                                </div>

                                <button type="submit"
                                    class="
                                        inline-flex
                                        h-11
                                        w-full
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-lg
                                        bg-brand-500
                                        px-5
                                        text-sm
                                        font-semibold
                                        text-white
                                        transition
                                        hover:bg-brand-600
                                    ">
                                    <i class="ri-send-plane-line"></i>

                                    Teruskan Keberatan
                                </button>
                            </form>
                        </div>

                        <div
                            class="mt-6 overflow-hidden rounded-2xl border border-red-200 bg-white shadow-theme-sm dark:border-red-900/50 dark:bg-gray-900">
                            <div
                                class="border-b border-red-100 bg-red-50 px-5 py-5 dark:border-red-900/50 dark:bg-red-500/10">
                                <h3 class="text-lg font-semibold text-red-800 dark:text-red-300">Tolak Keberatan</h3>
                                <p class="mt-1 text-sm text-red-700 dark:text-red-400">
                                    Gunakan jika data pemohon atau alasan pengajuan keberatan tidak valid.
                                </p>
                            </div>

                            <form action="{{ route('admin.keberatan.tolak', ['id' => $keberatan->id]) }}" method="POST"
                                class="space-y-5 p-5">
                                @csrf

                                <div>
                                    <label for="alasan_penolakan"
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Alasan Penolakan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="alasan_penolakan" name="alasan_penolakan" rows="5" required minlength="10" maxlength="5000"
                                        placeholder="Jelaskan data pemohon atau alasan keberatan yang tidak valid."
                                        class="w-full resize-y rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm leading-7 text-gray-800 outline-none focus:border-red-400 focus:ring-1 focus:ring-red-400 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('alasan_penolakan') }}</textarea>
                                    @error('alasan_penolakan')
                                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                    class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-5 text-sm font-semibold text-white transition hover:bg-red-700">
                                    <i class="ri-close-circle-line"></i>
                                    Tolak Keberatan
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Status Diproses --}}
                    @if ($keberatan->status === \App\Models\Keberatan::STATUS_DIPROSES && $keberatan->jawaban_pembantu)
                        <form action="{{ route('admin.keberatan.selesaikan', ['id' => $keberatan->id]) }}" method="POST"
                            enctype="multipart/form-data"
                            class="
                                overflow-hidden
                                rounded-2xl
                                border
                                border-gray-200
                                bg-white
                                shadow-theme-sm
                                dark:border-gray-800
                                dark:bg-gray-900
                            ">
                            @csrf

                            <div
                                class="
                                    border-b
                                    border-gray-100
                                    px-5
                                    py-5
                                    dark:border-gray-800
                                ">
                                <h3
                                    class="
                                        text-lg
                                        font-semibold
                                        text-gray-800
                                        dark:text-white/90
                                    ">
                                    Selesaikan Keberatan
                                </h3>

                                <p
                                    class="
                                        mt-1
                                        text-sm
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    Masukkan keputusan dan tanggapan final.
                                </p>
                            </div>

                            <div class="space-y-5 p-5">
                                {{-- Hasil --}}
                                <div>
                                    <label for="hasil"
                                        class="
                                            mb-1.5
                                            block
                                            text-sm
                                            font-medium
                                            text-gray-700
                                            dark:text-gray-300
                                        ">
                                        Hasil Keberatan
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <select id="hasil" name="hasil" required
                                        class="
                                            h-11
                                            w-full
                                            rounded-lg
                                            border
                                            border-gray-300
                                            bg-transparent
                                            px-4
                                            text-sm
                                            text-gray-800
                                            outline-none
                                            focus:border-brand-500
                                            focus:ring-1
                                            focus:ring-brand-500
                                            dark:border-gray-700
                                            dark:bg-gray-900
                                            dark:text-white
                                        ">
                                        <option value="">
                                            Pilih hasil keberatan
                                        </option>

                                        @foreach ($hasilOptions as $key => $label)
                                            @php
                                                $value = is_int($key) ? $label : $key;
                                            @endphp

                                            <option value="{{ $value }}" @selected(old('hasil') === $value)>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('hasil')
                                        <p
                                            class="
                                                mt-1.5
                                                text-xs
                                                text-red-600
                                            ">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Jenis Tindak Lanjut --}}
                                <div>
                                    <label for="jenis_tindak_lanjut"
                                        class="
                                            mb-1.5
                                            block
                                            text-sm
                                            font-medium
                                            text-gray-700
                                            dark:text-gray-300
                                        ">
                                        Jenis Tindak Lanjut
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <select id="jenis_tindak_lanjut" name="jenis_tindak_lanjut" required
                                        class="
                                            h-11
                                            w-full
                                            rounded-lg
                                            border
                                            border-gray-300
                                            bg-transparent
                                            px-4
                                            text-sm
                                            text-gray-800
                                            outline-none
                                            focus:border-brand-500
                                            focus:ring-1
                                            focus:ring-brand-500
                                            dark:border-gray-700
                                            dark:bg-gray-900
                                            dark:text-white
                                        ">
                                        <option value="">
                                            Pilih jenis tindak lanjut
                                        </option>

                                        @foreach ($tindakLanjutOptions as $key => $label)
                                            @php
                                                $value = is_int($key) ? $label : $key;
                                            @endphp

                                            <option value="{{ $value }}" @selected(old('jenis_tindak_lanjut') === $value)>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('jenis_tindak_lanjut')
                                        <p
                                            class="
                                                mt-1.5
                                                text-xs
                                                text-red-600
                                            ">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Tanggapan --}}
                                <div>
                                    <label for="tanggapan"
                                        class="
                                            mb-1.5
                                            block
                                            text-sm
                                            font-medium
                                            text-gray-700
                                            dark:text-gray-300
                                        ">
                                        Tanggapan Final
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <textarea id="tanggapan" name="tanggapan" rows="10" minlength="10" maxlength="10000" required
                                        class="
                                            w-full
                                            resize-y
                                            rounded-lg
                                            border
                                            border-gray-300
                                            bg-transparent
                                            px-4
                                            py-3
                                            text-sm
                                            text-gray-800
                                            outline-none
                                            focus:border-brand-500
                                            focus:ring-1
                                            focus:ring-brand-500
                                            dark:border-gray-700
                                            dark:bg-gray-900
                                            dark:text-white
                                        ">{{ old('tanggapan', $keberatan->jawaban_pembantu ?: $keberatan->tanggapan) }}</textarea>

                                    <p
                                        class="
                                            mt-1.5
                                            text-xs
                                            text-gray-500
                                        ">
                                        Minimal 10 karakter dan maksimal
                                        10.000 karakter.
                                    </p>

                                    @error('tanggapan')
                                        <p
                                            class="
                                                mt-1.5
                                                text-xs
                                                text-red-600
                                            ">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- File --}}
                                <div>
                                    <label for="file_tanggapan"
                                        class="
                                            mb-1.5
                                            block
                                            text-sm
                                            font-medium
                                            text-gray-700
                                            dark:text-gray-300
                                        ">
                                        Dokumen Tanggapan
                                    </label>

                                    <input id="file_tanggapan" name="file_tanggapan" type="file"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx"
                                        class="
                                            block
                                            w-full
                                            rounded-lg
                                            border
                                            border-gray-300
                                            bg-transparent
                                            text-sm
                                            text-gray-700
                                            file:mr-4
                                            file:border-0
                                            file:bg-gray-100
                                            file:px-4
                                            file:py-3
                                            file:text-sm
                                            file:font-medium
                                            dark:border-gray-700
                                            dark:text-gray-300
                                            dark:file:bg-gray-800
                                            dark:file:text-gray-300
                                        ">

                                    <p
                                        class="
                                            mt-1.5
                                            text-xs
                                            text-gray-500
                                        ">
                                        PDF, DOC, DOCX, XLS, atau XLSX.
                                        Maksimal 10 MB.
                                    </p>

                                    @error('file_tanggapan')
                                        <p
                                            class="
                                                mt-1.5
                                                text-xs
                                                text-red-600
                                            ">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div
                                class="
                                    border-t
                                    border-gray-100
                                    px-5
                                    py-4
                                    dark:border-gray-800
                                ">
                                <button type="submit"
                                    class="
                                        inline-flex
                                        h-11
                                        w-full
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-lg
                                        bg-brand-500
                                        px-5
                                        text-sm
                                        font-semibold
                                        text-white
                                        transition
                                        hover:bg-brand-600
                                    ">
                                    <i class="ri-check-line"></i>

                                    Selesaikan Keberatan
                                </button>
                            </div>
                        </form>
                    @endif

                    {{-- Status Selesai --}}
                    @if ($keberatan->status === \App\Models\Keberatan::STATUS_SELESAI)
                        <div
                            class="
                                rounded-2xl
                                border
                                border-green-200
                                bg-green-50
                                p-5
                                dark:border-green-900/50
                                dark:bg-green-500/10
                            ">
                            <div
                                class="
                                    flex
                                    items-start
                                    gap-3
                                ">
                                <i
                                    class="
                                        ri-checkbox-circle-line
                                        text-xl
                                        text-green-600
                                        dark:text-green-400
                                    "></i>

                                <div>
                                    <h3
                                        class="
                                            font-semibold
                                            text-green-800
                                            dark:text-green-300
                                        ">
                                        Keberatan Selesai
                                    </h3>

                                    <p
                                        class="
                                            mt-1
                                            text-sm
                                            leading-6
                                            text-green-700
                                            dark:text-green-400
                                        ">
                                        Keberatan telah mendapatkan
                                        keputusan dan tanggapan final.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    @if (
                        $admin->isAdminPembantu() &&
                            $keberatan->status === \App\Models\Keberatan::STATUS_DIPROSES &&
                            (int) $keberatan->ppid_pembantuid === (int) $admin->ppid_pembantuid)
                        <form action="{{ route('admin.keberatan.jawab-pembantu', ['id' => $keberatan->id]) }}"
                            method="POST" enctype="multipart/form-data"
                            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-gray-900">
                            @csrf
                            <div class="border-b border-gray-100 px-5 py-5 dark:border-gray-800">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Kirim Jawaban ke Admin
                                    Utama</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Berikan jawaban dan dokumen
                                    pendukung untuk keberatan ini.</p>
                            </div>
                            <div class="space-y-5 p-5">
                                <div>
                                    <label for="tanggapan_pelaksana"
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Jawaban PPID Pelaksana <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="tanggapan_pelaksana" name="tanggapan" rows="8" required minlength="10" maxlength="10000"
                                        class="w-full resize-y rounded-lg border border-gray-300 bg-transparent px-4 py-3 text-sm leading-7 text-gray-800 outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('tanggapan', $keberatan->jawaban_pembantu) }}</textarea>
                                    @error('tanggapan')
                                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="file_jawaban_pelaksana"
                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">File
                                        Jawaban</label>
                                    <input id="file_jawaban_pelaksana" name="file_tanggapan" type="file"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx"
                                        class="block w-full rounded-lg border border-gray-300 bg-transparent text-sm text-gray-700 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-3 file:text-sm file:font-medium dark:border-gray-700 dark:text-gray-300 dark:file:bg-gray-800">
                                    @error('file_tanggapan')
                                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit"
                                    class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-brand-500 px-5 text-sm font-semibold text-white transition hover:bg-brand-600">
                                    <i class="ri-send-plane-line"></i>
                                    Kirim Jawaban
                                </button>
                            </div>
                        </form>
                    @else
                        <div
                            class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="flex items-start gap-3">
                                <i class="ri-information-line text-xl text-brand-500"></i>
                                <div>
                                    <h3 class="font-semibold text-gray-800 dark:text-white">Informasi Keberatan</h3>
                                    <p class="mt-1 text-sm leading-6 text-gray-500 dark:text-gray-400">Keputusan dan
                                        tanggapan keberatan ditetapkan oleh Admin Utama.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
