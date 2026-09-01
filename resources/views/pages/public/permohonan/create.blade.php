@extends('layouts.public.app')

@section('title', 'Ajukan Permohonan Informasi | PPID Kota Batu')

@section('content')
    @php
        $isLoggedIn = $user !== null;
    @endphp

    <x-public.sections.page-hero :eyebrow="$isLoggedIn ? 'Permohonan melalui Akun Warga' : 'Pengajuan Pertama Tanpa Login'" title="Ajukan Permohonan Informasi" highlight="Publik Kota Batu"
        :description="$isLoggedIn
            ? 'Identitas diambil dari akun Anda. Setelah dikirim, permohonan akan langsung masuk ke riwayat akun.'
            : 'Pada pengajuan pertama, sistem membuat akun warga otomatis. Unggah foto KTP untuk membantu mengisi identitas, lalu periksa kembali hasil pembacaannya.'" />

    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

        @if (session('account_required'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-800">
                <h2 class="font-bold">
                    Akun warga sudah terdaftar
                </h2>

                <p class="mt-2 text-sm leading-6">
                    {{ session('account_required') }}
                </p>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('login') }}"
                        class="inline-flex h-11 items-center justify-center rounded-lg bg-emerald-800 px-5 text-sm font-semibold text-white hover:bg-emerald-900">
                        Masuk ke Akun Warga
                    </a>

                    <a href="{{ route('public.aktivasi.resend.form') }}"
                        class="inline-flex h-11 items-center justify-center rounded-lg border border-emerald-300 bg-white px-5 text-sm font-semibold text-emerald-800 hover:bg-emerald-100">
                        Kirim Ulang Aktivasi
                    </a>
                </div>
            </div>
        @endif


        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5 text-red-700">
                <p class="font-semibold">
                    Periksa kembali data berikut:
                </p>

                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif


        @unless ($isLoggedIn)
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-900">
                <h2 class="font-bold">
                    Sudah pernah mengajukan permohonan?
                </h2>

                <p class="mt-2 text-sm leading-6">
                    Pengajuan kedua dan seterusnya wajib melalui akun warga yang dibuat saat permohonan pertama.
                </p>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('login') }}"
                        class="inline-flex h-10 items-center justify-center rounded-lg bg-emerald-800 px-4 text-sm font-semibold text-white hover:bg-emerald-900">
                        Masuk ke Akun
                    </a>

                    <a href="{{ route('public.aktivasi.resend.form') }}"
                        class="inline-flex h-10 items-center justify-center rounded-lg border border-emerald-300 bg-white px-4 text-sm font-semibold text-emerald-800 hover:bg-emerald-100">
                        Belum Membuat Password
                    </a>
                </div>
            </div>
        @endunless


        <form id="permohonan-form" action="{{ route('public.permohonan.store') }}" method="POST"
            enctype="multipart/form-data" class="space-y-6">

            @csrf


            {{-- ========================================================= --}}
            {{-- IDENTITAS PEMOHON --}}
            {{-- ========================================================= --}}

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-emerald-100 bg-emerald-50/30 px-6 py-5">
                    <h2 class="text-lg font-bold text-slate-900">
                        Identitas Pemohon
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $isLoggedIn
                            ? 'Data berikut berasal dari akun warga.'
                            : 'Data digunakan untuk verifikasi dan pembuatan akun warga.' }}
                    </p>
                </div>


                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    {{-- KATEGORI PEMOHON --}}
                    <div>
                        <label for="kategori_pemohon" class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Kategori Pemohon
                            <span class="text-red-500">*</span>
                        </label>

                        <select id="kategori_pemohon" name="kategori_pemohon" required
                            class="h-11 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                            <option value="">
                                Pilih kategori
                            </option>

                            @foreach (['Orang Perorangan', 'Badan Hukum', 'Kelompok Orang'] as $kategori)
                                <option value="{{ $kategori }}" @selected(old('kategori_pemohon', 'Orang Perorangan') === $kategori)>
                                    {{ $kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    @if ($isLoggedIn)
                        {{-- NAMA --}}
                        <div>
                            <p class="mb-1.5 text-sm font-semibold text-slate-700">
                                Nama Pemohon
                            </p>

                            <div
                                class="min-h-11 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800">
                                {{ $user->nama ?? '-' }}
                            </div>
                        </div>


                        {{-- NIK --}}
                        <div>
                            <p class="mb-1.5 text-sm font-semibold text-slate-700">
                                Nomor Identitas
                            </p>

                            <div
                                class="min-h-11 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800">
                                {{ $user->nik ?? '-' }}
                            </div>
                        </div>


                        {{-- EMAIL --}}
                        <div>
                            <p class="mb-1.5 text-sm font-semibold text-slate-700">
                                Email / Username
                            </p>

                            <div
                                class="min-h-11 break-all rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800">
                                {{ $user->email ?? '-' }}
                            </div>
                        </div>


                        {{-- TELEPON --}}
                        <div>
                            <p class="mb-1.5 text-sm font-semibold text-slate-700">
                                Nomor Telepon
                            </p>

                            <div
                                class="min-h-11 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800">
                                {{ $user->telp ?? '-' }}
                            </div>
                        </div>


                        {{-- PEKERJAAN --}}
                        <div>
                            <p class="mb-1.5 text-sm font-semibold text-slate-700">
                                Pekerjaan
                            </p>

                            <div
                                class="min-h-11 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800">
                                {{ $user->pekerjaan ?: '-' }}
                            </div>
                        </div>


                        {{-- ALAMAT --}}
                        <div class="md:col-span-2">
                            <p class="mb-1.5 text-sm font-semibold text-slate-700">
                                Alamat
                            </p>

                            <div
                                class="min-h-20 whitespace-pre-line rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm leading-6 text-slate-800">
                                {{ $user->alamat ?: '-' }}
                            </div>
                        </div>


                        {{-- FILE IDENTITAS --}}
                        <div>
                            <label for="file_identitas" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Salinan Identitas (KTP)
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="file_identitas" name="file_identitas" type="file" required
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="block w-full rounded-lg border border-slate-300 bg-white text-sm text-slate-600 file:mr-4 file:border-0 file:border-r file:border-slate-200 file:bg-slate-50 file:px-4 file:py-2.5 file:font-semibold">

                            <p class="mt-1.5 text-xs text-slate-500">
                                Wajib mengunggah KTP pada setiap pengajuan.
                                Format PDF, JPG, JPEG, atau PNG.
                                Maksimal 5 MB.
                            </p>
                        </div>
                    @else
                        {{-- ================================================= --}}
                        {{-- OCR KTP --}}
                        {{-- ================================================= --}}

                        <div class="md:col-span-2 rounded-2xl border border-emerald-200 bg-emerald-50/70 p-5">

                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start">

                                <div class="min-w-0 flex-1">

                                    <h3 class="text-base font-bold text-emerald-950">
                                        Isi otomatis dari foto KTP
                                    </h3>

                                    <p class="mt-2 text-sm leading-6 text-emerald-800">
                                        Pilih foto JPG, JPEG, atau PNG yang jelas.
                                        Sistem akan mencoba membaca NIK, nama,
                                        tempat dan tanggal lahir, jenis kelamin,
                                        alamat, wilayah, serta pekerjaan.
                                    </p>


                                    <div class="mt-4">

                                        <label for="file_identitas"
                                            class="mb-1.5 block text-sm font-semibold text-emerald-950">
                                            Foto atau Salinan Identitas (KTP)
                                            <span class="text-red-500">*</span>
                                        </label>

                                        <input id="file_identitas" name="file_identitas" type="file" required
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            class="block w-full rounded-lg border border-emerald-300 bg-white text-sm text-slate-600 file:mr-4 file:border-0 file:border-r file:border-emerald-200 file:bg-emerald-100 file:px-4 file:py-2.5 file:font-semibold file:text-emerald-900">

                                        <p class="mt-1.5 text-xs text-emerald-700">
                                            KTP wajib diunggah.
                                            OCR hanya berjalan untuk JPG, JPEG, dan PNG.
                                            PDF tetap dapat diunggah, tetapi datanya harus diisi manual.
                                            Maksimal 5 MB.
                                        </p>

                                    </div>


                                    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center">

                                        <button id="ktp-ocr-button" type="button" disabled
                                            class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-emerald-800 px-5 text-sm font-semibold text-white transition hover:bg-emerald-900 disabled:cursor-not-allowed disabled:opacity-50">

                                            <svg id="ktp-ocr-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h4l2 2h8a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm6 8l2 2 4-4" />
                                            </svg>


                                            <svg id="ktp-ocr-spinner" class="hidden h-5 w-5 animate-spin"
                                                viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>

                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z"></path>
                                            </svg>


                                            <span id="ktp-ocr-button-text">
                                                Baca Data KTP Otomatis
                                            </span>

                                        </button>


                                        <span class="text-xs leading-5 text-emerald-700">
                                            Hasil OCR wajib diperiksa dan dapat diedit.
                                        </span>

                                    </div>


                                    <div id="ktp-ocr-status" class="mt-4 hidden rounded-xl border p-4 text-sm leading-6"
                                        role="status" aria-live="polite"></div>

                                </div>


                                {{-- PREVIEW KTP --}}
                                <div id="ktp-preview-wrapper" class="hidden w-full shrink-0 lg:w-64">

                                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-emerald-800">
                                        Pratinjau KTP
                                    </p>

                                    <img id="ktp-preview" src="" alt="Pratinjau foto KTP"
                                        class="max-h-52 w-full rounded-xl border border-emerald-200 bg-white object-contain shadow-sm">

                                </div>

                            </div>

                        </div>


                        {{-- NAMA PEMOHON --}}
                        <div>

                            <label for="nama_pemohon" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Nama Pemohon
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="nama_pemohon" name="nama_pemohon" type="text"
                                value="{{ old('nama_pemohon') }}" required maxlength="100" autocomplete="name"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">

                        </div>


                        {{-- NIK --}}
                        <div>

                            <label for="nomor_identitas" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                NIK
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="nomor_identitas" name="nomor_identitas" type="text" inputmode="numeric"
                                value="{{ old('nomor_identitas') }}" required minlength="16" maxlength="16"
                                pattern="[0-9]{16}" autocomplete="off"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">

                            <p class="mt-1.5 text-xs text-slate-500">
                                NIK harus terdiri dari 16 angka.
                            </p>

                        </div>


                        {{-- TEMPAT LAHIR --}}
                        <div>

                            <label for="tmp_lahir" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Tempat Lahir
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="tmp_lahir" name="tmp_lahir" type="text" value="{{ old('tmp_lahir') }}"
                                required maxlength="50"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">

                        </div>


                        {{-- TANGGAL LAHIR --}}
                        <div>

                            <label for="tgl_lahir" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Tanggal Lahir
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="tgl_lahir" name="tgl_lahir" type="date" value="{{ old('tgl_lahir') }}"
                                required max="{{ now()->subDay()->format('Y-m-d') }}"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">

                        </div>


                        {{-- JENIS KELAMIN --}}
                        <div>

                            <label for="l_kelamin" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Jenis Kelamin
                                <span class="text-red-500">*</span>
                            </label>

                            <select id="l_kelamin" name="l_kelamin" required
                                class="h-11 w-full rounded-lg border border-slate-300 bg-white px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">

                                <option value="">
                                    Pilih jenis kelamin
                                </option>

                                <option value="Laki-laki" @selected(old('l_kelamin') === 'Laki-laki')>
                                    Laki-laki
                                </option>

                                <option value="Perempuan" @selected(old('l_kelamin') === 'Perempuan')>
                                    Perempuan
                                </option>

                            </select>

                        </div>


                        {{-- PEKERJAAN --}}
                        <div>

                            <label for="pekerjaan_pemohon" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Pekerjaan
                            </label>

                            <input id="pekerjaan_pemohon" name="pekerjaan_pemohon" type="text"
                                value="{{ old('pekerjaan_pemohon') }}" maxlength="50"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">

                        </div>


                        {{-- EMAIL --}}
                        <div>

                            <label for="email_pemohon" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Email
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="email_pemohon" name="email_pemohon" type="email"
                                value="{{ old('email_pemohon') }}" required maxlength="100" autocomplete="email"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">

                            <p class="mt-1.5 text-xs text-slate-500">
                                Email ini menjadi username akun dan menerima tautan pembuatan password.
                            </p>

                        </div>


                        {{-- TELEPON --}}
                        <div>

                            <label for="telp_pemohon" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Nomor Telepon
                                <span class="text-red-500">*</span>
                            </label>

                            <input id="telp_pemohon" name="telp_pemohon" type="text"
                                value="{{ old('telp_pemohon') }}" required maxlength="20" autocomplete="tel"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">

                        </div>


                        {{-- ALAMAT --}}
                        <div class="md:col-span-2">

                            <label for="alamat_pemohon" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Alamat Lengkap
                                <span class="text-red-500">*</span>
                            </label>

                            <textarea id="alamat_pemohon" name="alamat_pemohon" rows="3" required maxlength="500"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">{{ old('alamat_pemohon') }}</textarea>

                        </div>


                        {{-- DESA --}}
                        <div>

                            <label for="desa_kel" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Desa / Kelurahan
                            </label>

                            <input id="desa_kel" name="desa_kel" type="text" value="{{ old('desa_kel') }}"
                                maxlength="50"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">

                        </div>


                        {{-- KECAMATAN --}}
                        <div>

                            <label for="kecamatan" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Kecamatan
                            </label>

                            <input id="kecamatan" name="kecamatan" type="text" value="{{ old('kecamatan') }}"
                                maxlength="50"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">

                        </div>


                        {{-- KOTA / KABUPATEN --}}
                        <div>

                            <label for="kota_kab" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Kota / Kabupaten
                            </label>

                            <input id="kota_kab" name="kota_kab" type="text" value="{{ old('kota_kab') }}"
                                maxlength="50"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">

                        </div>


                        {{-- PROVINSI --}}
                        <div>

                            <label for="provinsi" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Provinsi
                            </label>

                            <input id="provinsi" name="provinsi" type="text" value="{{ old('provinsi') }}"
                                maxlength="50"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">

                        </div>
                    @endif


                    {{-- ================================================= --}}
                    {{-- SURAT KUASA --}}
                    {{-- ================================================= --}}

                    <div id="surat-kuasa-wrapper" class="transition">

                        <label for="file_surat_kuasa" class="mb-1.5 block text-sm font-semibold text-slate-700
        ">
                            Surat Kuasa

                            <span id="surat-kuasa-required" class="hidden text-red-500">
                                *
                            </span>
                        </label>


                        <input id="file_surat_kuasa" name="file_surat_kuasa" type="file"
                            accept=".pdf,.jpg,.jpeg,.png"
                            class="
            block
            w-full
            rounded-lg
            border
            border-slate-300
            bg-white
            text-sm
            text-slate-600
            file:mr-4
            file:border-0
            file:border-r
            file:border-slate-200
            file:bg-slate-50
            file:px-4
            file:py-2.5
            file:font-semibold
        ">


                        <p id="surat-kuasa-help"
                            class="
            mt-1.5
            text-xs
            text-slate-500
        ">
                            Surat kuasa wajib diunggah untuk kategori
                            Badan Hukum dan Kelompok Orang.
                            Format PDF, JPG, JPEG, atau PNG.
                            Maksimal 5 MB.
                        </p>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- RINCIAN PERMOHONAN --}}
                    {{-- ========================================================= --}}

                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                        <div class="border-b border-emerald-100 bg-emerald-50/30 px-6 py-5">

                            <h2 class="text-lg font-bold text-slate-900">
                                Rincian Permohonan
                            </h2>

                        </div>


                        <div class="space-y-5 p-6">

                            {{-- RINCIAN INFORMASI --}}
                            <div>

                                <label for="rincian" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                    Rincian Informasi yang Diminta
                                    <span class="text-red-500">*</span>
                                </label>

                                <textarea id="rincian" name="rincian" rows="5" required maxlength="500"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                                    placeholder="Jelaskan informasi yang ingin diperoleh.">{{ old('rincian') }}</textarea>

                            </div>


                            {{-- TUJUAN --}}
                            <div>

                                <label for="tujuan" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                    Tujuan Penggunaan Informasi
                                    <span class="text-red-500">*</span>
                                </label>

                                <textarea id="tujuan" name="tujuan" rows="4" required maxlength="500"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                                    placeholder="Jelaskan tujuan penggunaan informasi.">{{ old('tujuan') }}</textarea>

                            </div>



                            {{-- ================================================= --}}
                            {{-- CARA MEMPEROLEH & PENGIRIMAN --}}
                            {{-- HANYA SOFT COPY DAN EMAIL --}}
                            {{-- ================================================= --}}

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                                {{-- CARA MEMPEROLEH --}}
                                <div>

                                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                                        Cara Memperoleh Informasi
                                    </label>

                                    <div
                                        class="flex h-11 w-full items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-700">
                                        Soft Copy
                                    </div>

                                    <input type="hidden" id="cara_memperoleh" name="cara_memperoleh"
                                        value="Mendapatkan salinan informasi (softcopy)">

                                    <p class="mt-1.5 text-xs text-slate-500">
                                        Informasi diberikan dalam bentuk dokumen digital.
                                    </p>

                                </div>


                                {{-- CARA PENGIRIMAN --}}
                                <div>

                                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                                        Cara Pengiriman Informasi
                                    </label>

                                    <div
                                        class="flex h-11 w-full items-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-700">
                                        E-mail
                                    </div>

                                    <input type="hidden" id="cara_pengiriman" name="cara_pengiriman" value="E-mail">

                                    <p class="mt-1.5 text-xs text-slate-500">
                                        Soft copy akan dikirim melalui alamat email pemohon.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </section>



                    {{-- ========================================================= --}}
                    {{-- PERSETUJUAN PEMBUATAN AKUN --}}
                    {{-- ========================================================= --}}

                    @unless ($isLoggedIn)
                        <section class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

                            <label class="flex items-start gap-3">

                                <input type="checkbox" name="persetujuan_akun" value="1" @checked(old('persetujuan_akun'))
                                    required
                                    class="mt-1 h-4 w-4 rounded border-emerald-300 text-emerald-800 focus:ring-emerald-500">

                                <span class="text-sm leading-6 text-emerald-950">
                                    Saya menyetujui pembuatan akun layanan PPID berdasarkan
                                    identitas dan email yang saya berikan.

                                    Email digunakan sebagai username, sedangkan password dibuat
                                    sendiri melalui tautan aktivasi yang dikirim setelah
                                    permohonan berhasil.
                                </span>

                            </label>

                        </section>
                    @endunless



                    {{-- ========================================================= --}}
                    {{-- TOMBOL --}}
                    {{-- ========================================================= --}}

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                        <a href="{{ route('public.permohonan.index') }}"
                            class="inline-flex h-11 items-center justify-center rounded-lg border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Batal
                        </a>


                        <button type="submit"
                            class="inline-flex h-11 items-center justify-center rounded-lg bg-emerald-800 px-6 text-sm font-semibold text-white hover:bg-emerald-900">
                            Ajukan Permohonan
                        </button>

                    </div>

        </form>

    </section>



    {{-- =============================================================== --}}
    {{-- OCR SCRIPT --}}
    {{-- =============================================================== --}}

    @unless ($isLoggedIn)
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                const fileInput =
                    document.getElementById('file_identitas');

                const scanButton =
                    document.getElementById('ktp-ocr-button');

                const buttonText =
                    document.getElementById('ktp-ocr-button-text');

                const buttonIcon =
                    document.getElementById('ktp-ocr-icon');

                const spinner =
                    document.getElementById('ktp-ocr-spinner');

                const statusBox =
                    document.getElementById('ktp-ocr-status');

                const previewWrapper =
                    document.getElementById('ktp-preview-wrapper');

                const preview =
                    document.getElementById('ktp-preview');

                const csrfToken =
                    document.querySelector('input[name="_token"]')?.value;

                const ocrUrl =
                    @json(route('public.permohonan.ocr'));


                let previewUrl = null;


                const imageTypes = [
                    'image/jpeg',
                    'image/png'
                ];



                // =====================================================
                // STATUS
                // =====================================================

                const showStatus = (
                    type,
                    message,
                    warnings = []
                ) => {

                    const classes = {

                        success: 'border-green-200 bg-green-50 text-green-800',

                        warning: 'border-amber-200 bg-amber-50 text-amber-900',

                        error: 'border-red-200 bg-red-50 text-red-800',

                        info: 'border-emerald-200 bg-white text-emerald-950'

                    };


                    statusBox.className =
                        `mt-4 rounded-xl border p-4 text-sm leading-6 ${
                            classes[type] ?? classes.info
                        }`;

                    statusBox.classList.remove('hidden');


                    const warningList =
                        warnings.length > 0 ?
                        `
                                <ul class="mt-2 list-disc space-y-1 pl-5">
                                    ${
                                        warnings
                                            .map(
                                                (warning) =>
                                                    `<li>${escapeHtml(warning)}</li>`
                                            )
                                            .join('')
                                    }
                                </ul>
                            ` :
                        '';


                    statusBox.innerHTML =
                        `<p class="font-semibold">${escapeHtml(message)}</p>${warningList}`;

                };



                // =====================================================
                // ESCAPE HTML
                // =====================================================

                const escapeHtml = (value) => {

                    const element =
                        document.createElement('div');

                    element.textContent =
                        String(value ?? '');

                    return element.innerHTML;

                };



                // =====================================================
                // SET FIELD
                // =====================================================

                const setFieldValue = (
                    id,
                    value
                ) => {

                    if (
                        value === null ||
                        value === undefined ||
                        String(value).trim() === ''
                    ) {
                        return;
                    }


                    const field =
                        document.getElementById(id);


                    if (!field) {
                        return;
                    }


                    field.value =
                        String(value).trim();


                    field.dispatchEvent(
                        new Event(
                            'input', {
                                bubbles: true
                            }
                        )
                    );


                    field.dispatchEvent(
                        new Event(
                            'change', {
                                bubbles: true
                            }
                        )
                    );

                };



                // =====================================================
                // LOADING
                // =====================================================

                const setLoading = (
                    loading
                ) => {

                    scanButton.disabled =
                        loading ||
                        !isSelectedFileScannable();


                    buttonText.textContent =
                        loading ?
                        'Membaca KTP...' :
                        'Baca Data KTP Otomatis';


                    spinner.classList.toggle(
                        'hidden',
                        !loading
                    );


                    buttonIcon.classList.toggle(
                        'hidden',
                        loading
                    );

                };



                // =====================================================
                // CEK FILE
                // =====================================================

                const isSelectedFileScannable = () => {

                    const file =
                        fileInput.files?.[0];


                    return (
                        file instanceof File &&
                        imageTypes.includes(file.type)
                    );

                };



                // =====================================================
                // FILE CHANGE
                // =====================================================

                fileInput.addEventListener(
                    'change',
                    () => {

                        const file =
                            fileInput.files?.[0];


                        if (previewUrl !== null) {

                            URL.revokeObjectURL(
                                previewUrl
                            );

                            previewUrl = null;

                        }


                        previewWrapper
                            .classList
                            .add('hidden');


                        preview.removeAttribute(
                            'src'
                        );


                        statusBox
                            .classList
                            .add('hidden');


                        if (
                            !(file instanceof File)
                        ) {

                            scanButton.disabled = true;

                            return;

                        }



                        // MAKSIMAL 5 MB
                        if (
                            file.size >
                            5 * 1024 * 1024
                        ) {

                            scanButton.disabled = true;


                            showStatus(
                                'error',
                                'Ukuran file melebihi 5 MB. Pilih file yang lebih kecil.'
                            );

                            return;

                        }



                        // FILE PDF TIDAK BISA OCR
                        if (
                            !imageTypes.includes(
                                file.type
                            )
                        ) {

                            scanButton.disabled = true;


                            showStatus(

                                'warning',

                                'File tetap dapat digunakan sebagai salinan identitas, tetapi OCR hanya tersedia untuk JPG, JPEG, atau PNG.'

                            );

                            return;

                        }



                        // PREVIEW
                        previewUrl =
                            URL.createObjectURL(
                                file
                            );


                        preview.src =
                            previewUrl;


                        previewWrapper
                            .classList
                            .remove('hidden');


                        scanButton.disabled =
                            false;


                        showStatus(

                            'info',

                            'Foto siap dibaca. Tekan tombol “Baca Data KTP Otomatis”.'

                        );

                    }
                );



                // =====================================================
                // OCR BUTTON
                // =====================================================

                scanButton.addEventListener(
                    'click',
                    async () => {

                        const file =
                            fileInput.files?.[0];


                        if (
                            !(file instanceof File) ||
                            !imageTypes.includes(
                                file.type
                            )
                        ) {

                            showStatus(

                                'warning',

                                'Pilih foto KTP berformat JPG, JPEG, atau PNG terlebih dahulu.'

                            );

                            return;

                        }



                        const formData =
                            new FormData();


                        formData.append(
                            'file_identitas',
                            file
                        );


                        setLoading(
                            true
                        );


                        showStatus(

                            'info',

                            'Sistem sedang membaca foto KTP. Proses ini dapat memerlukan beberapa detik.'

                        );



                        try {

                            const response =
                                await fetch(
                                    ocrUrl, {

                                        method: 'POST',

                                        headers: {

                                            'Accept': 'application/json',

                                            'X-CSRF-TOKEN': csrfToken ?? '',

                                            'X-Requested-With': 'XMLHttpRequest'

                                        },

                                        body: formData,

                                        credentials: 'same-origin'

                                    }
                                );



                            const payload =
                                await response
                                .json()
                                .catch(
                                    () => null
                                );



                            if (
                                !response.ok ||
                                !payload?.success
                            ) {

                                const validationMessage =
                                    payload?.errors ?
                                    Object
                                    .values(
                                        payload.errors
                                    )
                                    .flat()
                                    .join(' ') :
                                    null;



                                throw new Error(

                                    validationMessage ||

                                    payload?.message ||

                                    'Foto KTP tidak dapat dibaca.'

                                );

                            }



                            const data =
                                payload.data ?? {};



                            // =========================================
                            // ISI HASIL OCR
                            // =========================================

                            setFieldValue(
                                'nomor_identitas',
                                data.nik
                            );


                            setFieldValue(
                                'nama_pemohon',
                                data.nama
                            );


                            setFieldValue(
                                'tmp_lahir',
                                data.tempat_lahir
                            );


                            setFieldValue(
                                'tgl_lahir',
                                data.tanggal_lahir
                            );


                            setFieldValue(
                                'l_kelamin',
                                data.jenis_kelamin
                            );


                            setFieldValue(
                                'pekerjaan_pemohon',
                                data.pekerjaan
                            );


                            setFieldValue(
                                'alamat_pemohon',
                                data.alamat
                            );


                            setFieldValue(
                                'desa_kel',
                                data.desa_kel
                            );


                            setFieldValue(
                                'kecamatan',
                                data.kecamatan
                            );


                            setFieldValue(
                                'kota_kab',
                                data.kota_kab
                            );


                            setFieldValue(
                                'provinsi',
                                data.provinsi
                            );



                            const completeness =
                                Number(
                                    payload.completeness ??
                                    0
                                );


                            const message =
                                `${payload.message} Kelengkapan hasil: ${completeness}%.`;



                            showStatus(

                                payload.warnings?.length ?
                                'warning' :
                                'success',

                                message,

                                Array.isArray(
                                    payload.warnings
                                ) ?
                                payload.warnings : []

                            );



                            document
                                .getElementById(
                                    'nomor_identitas'
                                )
                                ?.focus();


                        } catch (error) {


                            showStatus(

                                'error',

                                error instanceof Error ?
                                error.message :
                                'Terjadi kesalahan saat membaca foto KTP.'

                            );


                        } finally {


                            setLoading(
                                false
                            );


                        }

                    }
                );



                // =====================================================
                // CLEANUP PREVIEW
                // =====================================================

                window.addEventListener(
                    'beforeunload',
                    () => {

                        if (
                            previewUrl !== null
                        ) {

                            URL.revokeObjectURL(
                                previewUrl
                            );

                        }

                    }
                );

            });
        </script>
    @endunless


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const kategori = document.getElementById('kategori_pemohon');
            const suratKuasa = document.getElementById('file_surat_kuasa');
            const wrapper = document.getElementById('surat-kuasa-wrapper');
            const required = document.getElementById('surat-kuasa-required');
            const help = document.getElementById('surat-kuasa-help');

            if (!kategori || !suratKuasa) {
                return;
            }

            function updateSuratKuasa() {

                const wajib =
                    kategori.value === 'Badan Hukum' ||
                    kategori.value === 'Kelompok Orang';

                suratKuasa.disabled = !wajib;
                suratKuasa.required = wajib;

                if (wajib) {
                    wrapper.classList.remove(
                        'opacity-50'
                    );

                    required.classList.remove(
                        'hidden'
                    );

                    help.textContent =
                        'Surat kuasa wajib diunggah untuk kategori Badan Hukum dan Kelompok Orang. Format PDF, JPG, JPEG, atau PNG. Maksimal 5 MB.';
                } else {

                    wrapper.classList.add(
                        'opacity-50'
                    );

                    required.classList.add(
                        'hidden'
                    );

                    suratKuasa.required = false;
                    suratKuasa.value = '';

                    help.textContent =
                        'Surat kuasa tidak diperlukan untuk kategori Orang Perorangan.';
                }
            }

            kategori.addEventListener(
                'change',
                updateSuratKuasa
            );

            updateSuratKuasa();

        });
    </script>

@endsection
