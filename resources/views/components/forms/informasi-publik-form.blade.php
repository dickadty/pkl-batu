@props([
    'action',
    'method' => 'POST',
    'informasi' => null,
    'admin' => null,
    'ppidPembantu' => [],
    'title' => 'Informasi Dokumen Publik',
    'description' => 'Lengkapi informasi dokumen, klasifikasi, ringkasan, unit PPID, dan berkas informasi publik.',
    'submitLabel' => 'Simpan Informasi',
    'cancelUrl' => null,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi form
    |--------------------------------------------------------------------------
    */

    $formMethod = strtoupper($method);

    $cancelUrl = $cancelUrl ?? route('admin.informasi-publik.index');

    $isAdminUtama = (int) data_get($admin, 'role', 0) === 1;

    $ppidPembantuList = collect($ppidPembantu ?? []);

    /*
    |--------------------------------------------------------------------------
    | Nilai field
    |--------------------------------------------------------------------------
    */

    $namaValue = old('nama', data_get($informasi, 'nama', ''));

    $tahunValue = old('tahun', data_get($informasi, 'tahun', now()->year));

    /*
    |--------------------------------------------------------------------------
    | Normalisasi sifat informasi
    |--------------------------------------------------------------------------
    |
    | Nilai yang dikirim ke controller harus menggunakan huruf kecil:
    |
    | berkala
    | serta merta
    | setiap saat
    | dikecualikan
    |
    | Normalisasi ini juga menangani data lama yang mungkin tersimpan dengan
    | kapitalisasi berbeda seperti "Berkala" atau "Setiap Saat".
    |
    */

    $sifatValue = strtolower(trim((string) old('sifat', data_get($informasi, 'sifat', ''))));

    $ppidPembantuValue = (string) old('ppid_pembantuid', data_get($informasi, 'ppid_pembantuid', ''));

    $ringkasanValue = old('ringkasan', data_get($informasi, 'ringkasan', ''));

    $currentFile = data_get($informasi, 'file');

    $currentFileName = $currentFile ? basename($currentFile) : null;

    $fileRequired = $informasi === null;

    $summaryLength = mb_strlen(trim(strip_tags((string) $ringkasanValue)));

    /*
    |--------------------------------------------------------------------------
    | Pilihan sifat informasi
    |--------------------------------------------------------------------------
    |
    | Key digunakan sebagai value yang dikirim ke server.
    | Value digunakan sebagai label yang ditampilkan kepada pengguna.
    |
    */

    $sifatOptions = [
        'berkala' => 'Berkala',
        'serta merta' => 'Serta Merta',
        'setiap saat' => 'Setiap Saat',
        'dikecualikan' => 'Dikecualikan',
    ];
@endphp

<x-common.component-card :title="$title">
    <div class="space-y-6">
        @if ($description)
            <div
                class="
                    flex
                    items-start
                    gap-3
                    rounded-xl
                    border
                    border-blue-100
                    bg-blue-50/70
                    px-4
                    py-3.5
                    dark:border-blue-500/20
                    dark:bg-blue-500/10
                ">
                <div
                    class="
                        flex
                        h-9
                        w-9
                        shrink-0
                        items-center
                        justify-center
                        rounded-full
                        bg-blue-100
                        text-blue-600
                        dark:bg-blue-500/15
                        dark:text-blue-400
                    ">
                    <i class="ri-information-line text-lg"></i>
                </div>

                <div class="min-w-0">
                    <p
                        class="
                            text-sm
                            font-semibold
                            text-gray-800
                            dark:text-white/90
                        ">
                        Data informasi publik
                    </p>

                    <p
                        class="
                            mt-0.5
                            text-sm
                            leading-6
                            text-gray-600
                            dark:text-gray-400
                        ">
                        {{ $description }}
                    </p>
                </div>
            </div>
        @endif

        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" x-data="{
            submitting: false,
            selectedSifat: @js($sifatValue),
            selectedPpid: @js($ppidPembantuValue),
            fileName: @js($currentFileName ?? ''),
            initialFileName: @js($currentFileName ?? ''),
            fileError: '',
            summaryLength: @js($summaryLength),
        
            updateSummaryLength(event) {
                this.summaryLength = event.target.value.length;
            },
        
            handleFile(event) {
                const file = event.target.files[0];
        
                this.fileError = '';
        
                if (!file) {
                    this.fileName = this.initialFileName;
                    return;
                }
        
                const allowedExtensions = [
                    'pdf',
                    'doc',
                    'docx',
                    'xls',
                    'xlsx',
                    'jpg',
                    'jpeg',
                    'png'
                ];
        
                const extension = file.name
                    .split('.')
                    .pop()
                    .toLowerCase();
        
                if (!allowedExtensions.includes(extension)) {
                    this.fileError =
                        'Format file harus PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, atau PNG.';
        
                    event.target.value = '';
                    this.fileName = this.initialFileName;
        
                    return;
                }
        
                if (file.size > 5 * 1024 * 1024) {
                    this.fileError =
                        'Ukuran file maksimal 5 MB.';
        
                    event.target.value = '';
                    this.fileName = this.initialFileName;
        
                    return;
                }
        
                this.fileName = file.name;
            },
        
            resetFile() {
                this.fileName = this.initialFileName;
                this.fileError = '';
        
                if (this.$refs.fileInput) {
                    this.$refs.fileInput.value = '';
                }
            }
        }"
            @submit="submitting = true" @reset="resetFile()" class="space-y-8">
            @csrf

            @if (!in_array($formMethod, ['GET', 'POST'], true))
                @method($formMethod)
            @endif

            {{-- ========================================================
                INFORMASI UTAMA
            ========================================================= --}}

            <section class="space-y-5">
                <div
                    class="
                        flex
                        items-center
                        gap-3
                        border-b
                        border-gray-100
                        pb-3
                        dark:border-gray-800
                    ">
                    <div
                        class="
                            flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-lg
                            bg-blue-50
                            text-blue-600
                            dark:bg-blue-500/15
                            dark:text-blue-400
                        ">
                        <i class="ri-file-list-3-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Informasi Utama
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Identitas dasar dan tahun penerbitan informasi.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        grid
                        grid-cols-1
                        gap-5
                        lg:grid-cols-[minmax(0,1fr)_220px]
                    ">
                    {{-- Nama informasi --}}
                    <div>
                        <label for="nama"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Nama Informasi

                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <span
                                class="
                                    pointer-events-none
                                    absolute
                                    inset-y-0
                                    left-0
                                    flex
                                    items-center
                                    pl-3.5
                                    text-gray-400
                                ">
                                <i class="ri-file-text-line text-lg"></i>
                            </span>

                            <input id="nama" type="text" name="nama" value="{{ $namaValue }}"
                                placeholder="Masukkan nama informasi publik" autocomplete="off" required autofocus
                                class="
                                    dark:bg-dark-900
                                    shadow-theme-xs
                                    focus:border-brand-300
                                    focus:ring-brand-500/10
                                    dark:focus:border-brand-800
                                    h-11
                                    w-full
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    py-2.5
                                    pl-11
                                    pr-4
                                    text-sm
                                    text-gray-800
                                    placeholder:text-gray-400
                                    focus:ring-3
                                    focus:outline-hidden
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-white/90
                                    dark:placeholder:text-white/30
                                    @error('nama')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                        </div>

                        @error('nama')
                            <p
                                class="
                                    mt-1.5
                                    flex
                                    items-center
                                    gap-1.5
                                    text-xs
                                    text-red-500
                                ">
                                <i class="ri-error-warning-line"></i>

                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Tahun --}}
                    <div>
                        <label for="tahun"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Tahun
                        </label>

                        <div class="relative">
                            <span
                                class="
                                    pointer-events-none
                                    absolute
                                    inset-y-0
                                    left-0
                                    flex
                                    items-center
                                    pl-3.5
                                    text-gray-400
                                ">
                                <i class="ri-calendar-line text-lg"></i>
                            </span>

                            <input id="tahun" type="number" name="tahun" value="{{ $tahunValue }}"
                                min="1900" max="2100" placeholder="{{ now()->year }}"
                                class="
                                    dark:bg-dark-900
                                    shadow-theme-xs
                                    focus:border-brand-300
                                    focus:ring-brand-500/10
                                    dark:focus:border-brand-800
                                    h-11
                                    w-full
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    py-2.5
                                    pl-11
                                    pr-4
                                    text-sm
                                    text-gray-800
                                    placeholder:text-gray-400
                                    focus:ring-3
                                    focus:outline-hidden
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    dark:text-white/90
                                    dark:placeholder:text-white/30
                                    @error('tahun')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                        </div>

                        @error('tahun')
                            <p
                                class="
                                    mt-1.5
                                    flex
                                    items-center
                                    gap-1.5
                                    text-xs
                                    text-red-500
                                ">
                                <i class="ri-error-warning-line"></i>

                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- ========================================================
                KLASIFIKASI DAN UNIT PPID
            ========================================================= --}}

            <section class="space-y-5">
                <div
                    class="
                        flex
                        items-center
                        gap-3
                        border-b
                        border-gray-100
                        pb-3
                        dark:border-gray-800
                    ">
                    <div
                        class="
                            flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-lg
                            bg-purple-50
                            text-purple-600
                            dark:bg-purple-500/15
                            dark:text-purple-400
                        ">
                        <i class="ri-price-tag-3-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Klasifikasi Informasi
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Tentukan sifat informasi dan unit PPID yang bertanggung jawab.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        grid
                        grid-cols-1
                        gap-5
                        {{ $isAdminUtama ? 'lg:grid-cols-2' : '' }}
                    ">
                    {{-- Sifat informasi --}}
                    <div>
                        <label for="sifat"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Sifat Informasi
                        </label>

                        <div class="relative z-20 bg-transparent">
                            <select id="sifat" name="sifat" x-model="selectedSifat"
                                :class="selectedSifat !== ''
                                    ?
                                    'text-gray-800 dark:text-white/90' :
                                    'text-gray-400 dark:text-white/30'"
                                class="
                                    dark:bg-dark-900
                                    shadow-theme-xs
                                    focus:border-brand-300
                                    focus:ring-brand-500/10
                                    dark:focus:border-brand-800
                                    h-11
                                    w-full
                                    appearance-none
                                    rounded-lg
                                    border
                                    border-gray-300
                                    bg-transparent
                                    bg-none
                                    px-4
                                    py-2.5
                                    pr-11
                                    text-sm
                                    focus:ring-3
                                    focus:outline-hidden
                                    dark:border-gray-700
                                    dark:bg-gray-900
                                    @error('sifat')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                                <option value=""
                                    class="
                                        text-gray-700
                                        dark:bg-gray-900
                                        dark:text-gray-400
                                    ">
                                    Pilih sifat informasi
                                </option>

                                @foreach ($sifatOptions as $optionValue => $optionLabel)
                                    <option value="{{ $optionValue }}" @selected($sifatValue === $optionValue)
                                        class="
                                            text-gray-700
                                            dark:bg-gray-900
                                            dark:text-gray-400
                                        ">
                                        {{ $optionLabel }}
                                    </option>
                                @endforeach
                            </select>

                            <span
                                class="
                                    pointer-events-none
                                    absolute
                                    right-4
                                    top-1/2
                                    z-30
                                    -translate-y-1/2
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>

                        @error('sifat')
                            <p
                                class="
                                    mt-1.5
                                    flex
                                    items-center
                                    gap-1.5
                                    text-xs
                                    text-red-500
                                ">
                                <i class="ri-error-warning-line"></i>

                                <span>{{ $message }}</span>
                            </p>
                        @else
                            <p
                                class="
                                    mt-1.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Pilih klasifikasi akses informasi publik.
                            </p>
                        @enderror
                    </div>

                    {{-- PPID Pembantu --}}
                    @if ($isAdminUtama)
                        <div>
                            <label for="ppid_pembantuid"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-medium
                                    text-gray-700
                                    dark:text-gray-400
                                ">
                                PPID Pembantu
                            </label>

                            <div class="relative z-20 bg-transparent">
                                <select id="ppid_pembantuid" name="ppid_pembantuid" x-model="selectedPpid"
                                    :class="selectedPpid !== ''
                                        ?
                                        'text-gray-800 dark:text-white/90' :
                                        'text-gray-400 dark:text-white/30'"
                                    class="
                                        dark:bg-dark-900
                                        shadow-theme-xs
                                        focus:border-brand-300
                                        focus:ring-brand-500/10
                                        dark:focus:border-brand-800
                                        h-11
                                        w-full
                                        appearance-none
                                        rounded-lg
                                        border
                                        border-gray-300
                                        bg-transparent
                                        bg-none
                                        px-4
                                        py-2.5
                                        pr-11
                                        text-sm
                                        focus:ring-3
                                        focus:outline-hidden
                                        dark:border-gray-700
                                        dark:bg-gray-900
                                        @error('ppid_pembantuid')
                                            border-red-500
                                            focus:border-red-500
                                            focus:ring-red-500/10
                                            dark:border-red-500
                                        @enderror
                                    ">
                                    <option value=""
                                        class="
                                            text-gray-700
                                            dark:bg-gray-900
                                            dark:text-gray-400
                                        ">
                                        Pilih PPID Pembantu
                                    </option>

                                    @foreach ($ppidPembantuList as $ppid)
                                        <option value="{{ (string) $ppid->id }}" @selected($ppidPembantuValue === (string) $ppid->id)
                                            class="
                                                text-gray-700
                                                dark:bg-gray-900
                                                dark:text-gray-400
                                            ">
                                            {{ $ppid->nama ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>

                                <span
                                    class="
                                        pointer-events-none
                                        absolute
                                        right-4
                                        top-1/2
                                        z-30
                                        -translate-y-1/2
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none">
                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>

                            @error('ppid_pembantuid')
                                <p
                                    class="
                                        mt-1.5
                                        flex
                                        items-center
                                        gap-1.5
                                        text-xs
                                        text-red-500
                                    ">
                                    <i class="ri-error-warning-line"></i>

                                    <span>{{ $message }}</span>
                                </p>
                            @else
                                <p
                                    class="
                                        mt-1.5
                                        text-xs
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    Pilih unit PPID Pembantu pemilik dokumen.
                                </p>
                            @enderror
                        </div>
                    @endif
                </div>
            </section>

            {{-- ========================================================
                RINGKASAN
            ========================================================= --}}

            <section class="space-y-5">
                <div
                    class="
                        flex
                        items-center
                        gap-3
                        border-b
                        border-gray-100
                        pb-3
                        dark:border-gray-800
                    ">
                    <div
                        class="
                            flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-lg
                            bg-orange-50
                            text-orange-600
                            dark:bg-orange-500/15
                            dark:text-orange-400
                        ">
                        <i class="ri-file-text-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Ringkasan Informasi
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Berikan deskripsi singkat mengenai isi dokumen.
                        </p>
                    </div>
                </div>

                <div>
                    <label for="ringkasan"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-400
                        ">
                        Ringkasan
                    </label>

                    <textarea id="ringkasan" name="ringkasan" rows="6" placeholder="Masukkan ringkasan isi informasi publik"
                        @input="updateSummaryLength($event)"
                        class="
                            dark:bg-dark-900
                            shadow-theme-xs
                            focus:border-brand-300
                            focus:ring-brand-500/10
                            dark:focus:border-brand-800
                            w-full
                            resize-y
                            rounded-lg
                            border
                            border-gray-300
                            bg-transparent
                            px-4
                            py-3
                            text-sm
                            leading-6
                            text-gray-800
                            placeholder:text-gray-400
                            focus:ring-3
                            focus:outline-hidden
                            dark:border-gray-700
                            dark:bg-gray-900
                            dark:text-white/90
                            dark:placeholder:text-white/30
                            @error('ringkasan')
                                border-red-500
                                focus:border-red-500
                                focus:ring-red-500/10
                                dark:border-red-500
                            @enderror
                        ">{{ $ringkasanValue }}</textarea>

                    <div
                        class="
                            mt-1.5
                            flex
                            items-start
                            justify-between
                            gap-3
                        ">
                        @error('ringkasan')
                            <p
                                class="
                                    flex
                                    items-center
                                    gap-1.5
                                    text-xs
                                    text-red-500
                                ">
                                <i class="ri-error-warning-line"></i>

                                <span>{{ $message }}</span>
                            </p>
                        @else
                            <p
                                class="
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Ringkasan ditampilkan sebagai informasi singkat dokumen.
                            </p>
                        @enderror

                        <span
                            class="
                                shrink-0
                                text-xs
                                text-gray-400
                                dark:text-gray-500
                            ">
                            <span x-text="summaryLength"></span> karakter
                        </span>
                    </div>
                </div>
            </section>

            {{-- ========================================================
                FILE INFORMASI
            ========================================================= --}}

            <section class="space-y-5">
                <div
                    class="
                        flex
                        items-center
                        gap-3
                        border-b
                        border-gray-100
                        pb-3
                        dark:border-gray-800
                    ">
                    <div
                        class="
                            flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-lg
                            bg-green-50
                            text-green-600
                            dark:bg-green-500/15
                            dark:text-green-400
                        ">
                        <i class="ri-upload-cloud-2-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Berkas Informasi
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Unggah dokumen atau gambar yang akan dipublikasikan.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        grid
                        grid-cols-1
                        gap-5
                        lg:grid-cols-[minmax(0,1fr)_360px]
                    ">
                    <div class="space-y-3">
                        <label for="file"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            File Informasi

                            @if ($fileRequired)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        <input x-ref="fileInput" id="file" type="file" name="file"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" @change="handleFile($event)"
                            @if ($fileRequired) required @endif
                            class="
                                focus:border-ring-brand-300
                                shadow-theme-xs
                                focus:file:ring-brand-300
                                h-11
                                w-full
                                overflow-hidden
                                rounded-lg
                                border
                                border-gray-300
                                bg-transparent
                                text-sm
                                text-gray-500
                                transition-colors
                                file:mr-5
                                file:cursor-pointer
                                file:rounded-l-lg
                                file:border-0
                                file:border-r
                                file:border-solid
                                file:border-gray-200
                                file:bg-gray-50
                                file:py-3
                                file:pl-3.5
                                file:pr-3
                                file:text-sm
                                file:text-gray-700
                                hover:file:bg-gray-100
                                focus:outline-hidden
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-gray-400
                                dark:file:border-gray-800
                                dark:file:bg-white/[0.03]
                                dark:file:text-gray-400
                                @error('file')
                                    border-red-500
                                    dark:border-red-500
                                @enderror
                            ">

                        <div x-cloak x-show="fileName !== ''"
                            class="
                                flex
                                items-center
                                gap-3
                                rounded-lg
                                border
                                border-gray-200
                                bg-gray-50
                                px-4
                                py-3
                                dark:border-gray-800
                                dark:bg-gray-900
                            ">
                            <div
                                class="
                                    flex
                                    h-9
                                    w-9
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-lg
                                    bg-blue-50
                                    text-blue-600
                                    dark:bg-blue-500/15
                                    dark:text-blue-400
                                ">
                                <i class="ri-file-line text-lg"></i>
                            </div>

                            <div class="min-w-0">
                                <p
                                    class="
                                        text-xs
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    File terpilih
                                </p>

                                <p x-text="fileName"
                                    class="
                                        mt-0.5
                                        truncate
                                        text-sm
                                        font-medium
                                        text-gray-800
                                        dark:text-white/90
                                    ">
                                </p>
                            </div>
                        </div>

                        <p x-cloak x-show="fileError !== ''"
                            class="
                                flex
                                items-center
                                gap-1.5
                                text-xs
                                text-red-500
                            ">
                            <i class="ri-error-warning-line"></i>

                            <span x-text="fileError"></span>
                        </p>

                        @error('file')
                            <p
                                class="
                                    flex
                                    items-center
                                    gap-1.5
                                    text-xs
                                    text-red-500
                                ">
                                <i class="ri-error-warning-line"></i>

                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <div
                        class="
                            rounded-xl
                            border
                            border-gray-200
                            bg-gray-50
                            p-4
                            dark:border-gray-800
                            dark:bg-gray-900
                        ">
                        <h4
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Ketentuan file
                        </h4>

                        <ul
                            class="
                                mt-3
                                space-y-2
                                text-xs
                                leading-5
                                text-gray-500
                                dark:text-gray-400
                            ">
                            <li class="flex items-start gap-2">
                                <i
                                    class="
                                        ri-checkbox-circle-line
                                        mt-0.5
                                        text-green-500
                                    "></i>

                                <span>
                                    Format PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, atau PNG.
                                </span>
                            </li>

                            <li class="flex items-start gap-2">
                                <i
                                    class="
                                        ri-checkbox-circle-line
                                        mt-0.5
                                        text-green-500
                                    "></i>

                                <span>
                                    Ukuran file maksimal 5 MB.
                                </span>
                            </li>

                            <li class="flex items-start gap-2">
                                <i
                                    class="
                                        ri-checkbox-circle-line
                                        mt-0.5
                                        text-green-500
                                    "></i>

                                <span>
                                    Pastikan dokumen dapat dibaca dan tidak rusak.
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            {{-- ========================================================
                TOMBOL AKSI
            ========================================================= --}}

            <div
                class="
                    flex
                    flex-col-reverse
                    gap-3
                    border-t
                    border-gray-100
                    pt-6
                    dark:border-gray-800
                    sm:flex-row
                    sm:items-center
                    sm:justify-end
                ">
                <a href="{{ $cancelUrl }}"
                    class="
                        inline-flex
                        h-11
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        border
                        border-gray-300
                        bg-white
                        px-5
                        text-sm
                        font-medium
                        text-gray-700
                        shadow-theme-xs
                        transition
                        hover:bg-gray-50
                        hover:text-gray-800
                        focus:outline-hidden
                        focus:ring-3
                        focus:ring-gray-500/10
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-300
                        dark:hover:bg-gray-800
                        dark:hover:text-white
                    ">
                    <i class="ri-arrow-left-line text-lg"></i>

                    <span>Kembali</span>
                </a>

                <button type="reset" :disabled="submitting"
                    class="
                        inline-flex
                        h-11
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        border
                        border-gray-300
                        bg-white
                        px-5
                        text-sm
                        font-medium
                        text-gray-700
                        shadow-theme-xs
                        transition
                        hover:bg-gray-50
                        hover:text-gray-800
                        focus:outline-hidden
                        focus:ring-3
                        focus:ring-gray-500/10
                        disabled:cursor-not-allowed
                        disabled:opacity-50
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-300
                        dark:hover:bg-gray-800
                        dark:hover:text-white
                    ">
                    <i class="ri-refresh-line text-lg"></i>

                    <span>Reset</span>
                </button>

                <button type="submit" :disabled="submitting"
                    class="
                        inline-flex
                        h-11
                        min-w-[180px]
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        bg-brand-500
                        px-5
                        text-sm
                        font-semibold
                        text-white
                        shadow-theme-xs
                        transition
                        hover:bg-brand-600
                        focus:outline-hidden
                        focus:ring-3
                        focus:ring-brand-500/20
                        disabled:cursor-not-allowed
                        disabled:opacity-60
                    ">
                    <i x-show="!submitting" class="ri-save-line text-lg"></i>

                    <svg x-cloak x-show="submitting" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"
                        aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>

                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z"></path>
                    </svg>

                    <span x-show="!submitting">
                        {{ $submitLabel }}
                    </span>

                    <span x-cloak x-show="submitting">
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</x-common.component-card>
