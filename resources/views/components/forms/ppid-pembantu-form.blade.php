@props([
    'action',
    'method' => 'POST',
    'ppid' => null,
    'kategori' => [],
    'title' => 'Informasi Profil PPID Pembantu',
    'description' => 'Lengkapi informasi dasar, kategori, kontak, alamat, dan ikon PPID Pembantu.',
    'submitLabel' => 'Simpan PPID',
    'cancelUrl' => null,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Normalisasi method form
    |--------------------------------------------------------------------------
    */

    $formMethod = strtoupper($method);

    /*
    |--------------------------------------------------------------------------
    | URL kembali
    |--------------------------------------------------------------------------
    */

    $cancelUrl = $cancelUrl ?? route('admin.ppid-pembantu.index');

    /*
    |--------------------------------------------------------------------------
    | Daftar kategori
    |--------------------------------------------------------------------------
    */

    $kategoriList = collect($kategori ?? []);

    /*
    |--------------------------------------------------------------------------
    | Nilai field
    |--------------------------------------------------------------------------
    */

    $namaValue = old('nama', data_get($ppid, 'nama', ''));

    $keteranganValue = old('keterangan', data_get($ppid, 'keterangan', ''));

    $kategoriValue = (string) old('kategori_ppidid', data_get($ppid, 'kategori_ppidid', ''));

    $linkwebValue = old('linkweb', data_get($ppid, 'linkweb', ''));

    $telpValue = old('telp', data_get($ppid, 'telp', ''));

    $alamatValue = old('alamat', data_get($ppid, 'alamat', ''));

    $iconValue = old('icon', data_get($ppid, 'icon', ''));
@endphp

<x-common.component-card :title="$title">
    <div class="space-y-6">
        {{-- ============================================================
            DESKRIPSI FORM
        ============================================================= --}}

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
                        Data profil PPID Pembantu
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

        {{-- ============================================================
            FORM
        ============================================================= --}}

        <form action="{{ $action }}" method="POST" x-data="{
            submitting: false
        }" @submit="submitting = true"
            class="space-y-8">
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
                        <i class="ri-government-line text-lg"></i>
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
                            Identitas dan klasifikasi unit PPID Pembantu.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        grid
                        grid-cols-1
                        gap-5
                        lg:grid-cols-2
                    ">
                    {{-- Nama PPID --}}
                    <div class="lg:col-span-2">
                        <label for="nama"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Nama PPID Pembantu

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
                                <i class="ri-government-line text-lg"></i>
                            </span>

                            <input id="nama" type="text" name="nama" value="{{ $namaValue }}"
                                placeholder="Masukkan nama PPID Pembantu" autocomplete="organization" required autofocus
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

                    {{-- Keterangan --}}
                    <div class="lg:col-span-2">
                        <label for="keterangan"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Keterangan
                        </label>

                        <textarea id="keterangan" name="keterangan" rows="5"
                            placeholder="Masukkan keterangan singkat mengenai PPID Pembantu"
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
                                py-2.5
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
                                @error('keterangan')
                                    border-red-500
                                    focus:border-red-500
                                    focus:ring-red-500/10
                                    dark:border-red-500
                                @enderror
                            ">{{ $keteranganValue }}</textarea>

                        <div
                            class="
                                mt-1.5
                                flex
                                items-start
                                justify-between
                                gap-3
                            ">
                            @error('keterangan')
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
                                    Jelaskan tugas atau cakupan layanan unit ini.
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Kategori PPID --}}
                    <div class="lg:col-span-2">
                        <label for="kategori_ppidid"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Kategori PPID

                            <span class="text-red-500">*</span>
                        </label>

                        <div x-data="{
                            isOptionSelected: @js($kategoriValue !== '')
                        }"
                            class="
                                relative
                                z-20
                                bg-transparent
                            ">
                            <select id="kategori_ppidid" name="kategori_ppidid" required
                                @change="
                                    isOptionSelected =
                                        $event.target.value !== ''
                                "
                                :class="isOptionSelected
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
                                    @error('kategori_ppidid')
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
                                    Pilih kategori PPID
                                </option>

                                @foreach ($kategoriList as $item)
                                    <option value="{{ $item->id }}" @selected($kategoriValue === (string) $item->id)
                                        class="
                                            text-gray-700
                                            dark:bg-gray-900
                                            dark:text-gray-400
                                        ">
                                        {{ $item->kategori ?? '-' }}
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
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>

                        @error('kategori_ppidid')
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
                KONTAK DAN ALAMAT
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
                        <i class="ri-contacts-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Kontak dan Alamat
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Informasi yang dapat digunakan masyarakat
                            untuk menghubungi unit PPID.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        grid
                        grid-cols-1
                        gap-5
                        lg:grid-cols-2
                    ">
                    {{-- URL Website --}}
                    <div>
                        <label for="linkweb"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            URL Website
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
                                    border-r
                                    border-gray-200
                                    px-3.5
                                    text-gray-500
                                    dark:border-gray-800
                                    dark:text-gray-400
                                ">
                                <i class="ri-global-line text-lg"></i>
                            </span>

                            <input id="linkweb" type="url" name="linkweb" value="{{ $linkwebValue }}"
                                placeholder="https://contoh.go.id" autocomplete="url"
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
                                    pl-[58px]
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
                                    @error('linkweb')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                        </div>

                        @error('linkweb')
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
                                Gunakan URL lengkap, termasuk
                                <span class="font-medium">https://</span>.
                            </p>
                        @enderror
                    </div>

                    {{-- Nomor telepon --}}
                    <div>
                        <label for="telp"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            No. Telepon
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
                                <i class="ri-phone-line text-lg"></i>
                            </span>

                            <input id="telp" type="tel" name="telp" value="{{ $telpValue }}"
                                placeholder="Contoh: 08123456789" autocomplete="tel"
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
                                    @error('telp')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                        </div>

                        @error('telp')
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

                    {{-- Alamat --}}
                    <div class="lg:col-span-2">
                        <label for="alamat"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Alamat
                        </label>

                        <div class="relative">
                            <span
                                class="
                                    pointer-events-none
                                    absolute
                                    left-0
                                    top-0
                                    flex
                                    h-11
                                    items-center
                                    pl-3.5
                                    text-gray-400
                                ">
                                <i class="ri-map-pin-line text-lg"></i>
                            </span>

                            <textarea id="alamat" name="alamat" rows="4" placeholder="Masukkan alamat lengkap PPID Pembantu"
                                autocomplete="street-address"
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
                                    py-2.5
                                    pl-11
                                    pr-4
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
                                    @error('alamat')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">{{ $alamatValue }}</textarea>
                        </div>

                        @error('alamat')
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
                PENGATURAN TAMPILAN
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
                        <i class="ri-palette-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Pengaturan Tampilan
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Atur ikon yang digunakan untuk menampilkan
                            profil PPID Pembantu.
                        </p>
                    </div>
                </div>

                {{-- Icon --}}
                <div>
                    <label for="icon"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-400
                        ">
                        Nama Icon
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
                            <i class="ri-shapes-line text-lg"></i>
                        </span>

                        <input id="icon" type="text" name="icon" value="{{ $iconValue }}"
                            placeholder="Contoh: ri-government-line" autocomplete="off"
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
                                font-mono
                                text-sm
                                text-gray-800
                                placeholder:text-gray-400
                                focus:ring-3
                                focus:outline-hidden
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-white/90
                                dark:placeholder:text-white/30
                                @error('icon')
                                    border-red-500
                                    focus:border-red-500
                                    focus:ring-red-500/10
                                    dark:border-red-500
                                @enderror
                            ">
                    </div>

                    @error('icon')
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
                            Masukkan class Remix Icon, misalnya
                            <span
                                class="
                                    rounded
                                    bg-gray-100
                                    px-1.5
                                    py-0.5
                                    font-mono
                                    text-gray-700
                                    dark:bg-gray-800
                                    dark:text-gray-300
                                ">
                                ri-government-line
                            </span>
                        </p>
                    @enderror
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
                        min-w-[150px]
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
                        dark:bg-brand-500
                        dark:hover:bg-brand-600
                    ">
                    <i x-show="!submitting" class="ri-save-line text-lg"></i>

                    <svg x-cloak x-show="submitting"
                        class="
                            h-4
                            w-4
                            animate-spin
                        "
                        viewBox="0 0 24 24" fill="none" aria-hidden="true">
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
