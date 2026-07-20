@props([
    'action',
    'method' => 'POST',
    'pejabat' => null,
    'title' => 'Informasi Pejabat',
    'description' => 'Lengkapi identitas, jabatan, data pribadi, kontak, alamat, dan foto pejabat.',
    'submitLabel' => 'Simpan Pejabat',
    'cancelUrl' => null,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi form
    |--------------------------------------------------------------------------
    */

    $formMethod = strtoupper($method);

    $cancelUrl = $cancelUrl ?? route('admin.pejabat.index');

    /*
    |--------------------------------------------------------------------------
    | Nilai field
    |--------------------------------------------------------------------------
    */

    $namaValue = old('nama', data_get($pejabat, 'nama', ''));

    $jabatanValue = old('jabatan', data_get($pejabat, 'jabatan', ''));

    $masaValue = old('masa', data_get($pejabat, 'masa', ''));

    $tempatTanggalLahirValue = old('tmp_tgl_lahir', data_get($pejabat, 'tmp_tgl_lahir', ''));

    $alamatValue = old('alamat', data_get($pejabat, 'alamat', ''));

    $nomorTeleponValue = old('no_telp', data_get($pejabat, 'no_telp', ''));

    /*
    |--------------------------------------------------------------------------
    | Foto pejabat saat edit
    |--------------------------------------------------------------------------
    */

    $currentPhotoUrl = null;

    $currentPhoto = data_get($pejabat, 'foto');

    if (!empty($currentPhoto)) {
        $currentPhotoUrl = \Illuminate\Support\Str::startsWith($currentPhoto, ['http://', 'https://'])
            ? $currentPhoto
            : asset('storage/' . ltrim($currentPhoto, '/'));
    }
@endphp

<x-common.component-card :title="$title">
    <div class="space-y-6">
        {{-- ============================================================
            INFORMASI FORM
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
                        Data profil pejabat
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
            FORM PEJABAT
        ============================================================= --}}

        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" x-data="{
            submitting: false,
            photoPreview: @js($currentPhotoUrl),
            initialPhoto: @js($currentPhotoUrl),
            photoError: '',
        
            handlePhoto(event) {
                const file = event.target.files[0];
        
                this.photoError = '';
        
                if (!file) {
                    this.photoPreview = this.initialPhoto;
                    return;
                }
        
                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];
        
                if (!allowedTypes.includes(file.type)) {
                    this.photoError =
                        'Format foto harus JPG, JPEG, PNG, atau WEBP.';
        
                    event.target.value = '';
                    this.photoPreview = this.initialPhoto;
                    return;
                }
        
                if (file.size > 2 * 1024 * 1024) {
                    this.photoError =
                        'Ukuran foto maksimal 2 MB.';
        
                    event.target.value = '';
                    this.photoPreview = this.initialPhoto;
                    return;
                }
        
                const reader = new FileReader();
        
                reader.onload = loadEvent => {
                    this.photoPreview =
                        loadEvent.target.result;
                };
        
                reader.readAsDataURL(file);
            },
        
            resetPhoto() {
                this.photoPreview = this.initialPhoto;
                this.photoError = '';
        
                if (this.$refs.photoInput) {
                    this.$refs.photoInput.value = '';
                }
            }
        }"
            @submit="submitting = true" @reset.window="resetPhoto()" class="space-y-8">
            @csrf

            @if (!in_array($formMethod, ['GET', 'POST'], true))
                @method($formMethod)
            @endif

            {{-- ========================================================
                IDENTITAS DAN JABATAN
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
                        <i class="ri-user-star-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Identitas dan Jabatan
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Informasi utama mengenai pejabat dan posisi yang dijabat.
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
                    {{-- Nama pejabat --}}
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
                            Nama Pejabat

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
                                <i class="ri-user-line text-lg"></i>
                            </span>

                            <input id="nama" type="text" name="nama" value="{{ $namaValue }}"
                                placeholder="Masukkan nama lengkap pejabat" autocomplete="name" required autofocus
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

                    {{-- Jabatan --}}
                    <div>
                        <label for="jabatan"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Jabatan

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
                                <i class="ri-briefcase-4-line text-lg"></i>
                            </span>

                            <input id="jabatan" type="text" name="jabatan" value="{{ $jabatanValue }}"
                                placeholder="Masukkan jabatan" autocomplete="organization-title" required
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
                                    @error('jabatan')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                        </div>

                        @error('jabatan')
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

                    {{-- Masa jabatan --}}
                    <div>
                        <label for="masa"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Masa Jabatan
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
                                <i class="ri-calendar-event-line text-lg"></i>
                            </span>

                            <input id="masa" type="text" name="masa" value="{{ $masaValue }}"
                                placeholder="Contoh: 2024 - 2029" autocomplete="off"
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
                                    @error('masa')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                        </div>

                        @error('masa')
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

                    {{-- Tempat dan tanggal lahir --}}
                    <div>
                        <label for="tmp_tgl_lahir"
                            class="
                                mb-1.5
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Tempat/Tanggal Lahir
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
                                <i class="ri-map-pin-time-line text-lg"></i>
                            </span>

                            <input id="tmp_tgl_lahir" type="text" name="tmp_tgl_lahir"
                                value="{{ $tempatTanggalLahirValue }}" placeholder="Contoh: Batu, 01 Januari 1980"
                                autocomplete="off"
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
                                    @error('tmp_tgl_lahir')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                        </div>

                        @error('tmp_tgl_lahir')
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
                            Informasi kontak dan alamat pejabat.
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
                    {{-- Nomor telepon --}}
                    <div>
                        <label for="no_telp"
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

                            <input id="no_telp" type="tel" name="no_telp" value="{{ $nomorTeleponValue }}"
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
                                    @error('no_telp')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/10
                                        dark:border-red-500
                                    @enderror
                                ">
                        </div>

                        @error('no_telp')
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

                            <textarea id="alamat" name="alamat" rows="4" placeholder="Masukkan alamat lengkap pejabat"
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
                FOTO PEJABAT
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
                        <i class="ri-image-add-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Foto Pejabat
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Unggah foto resmi dengan format gambar yang didukung.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        grid
                        grid-cols-1
                        gap-5
                        lg:grid-cols-[220px_minmax(0,1fr)]
                    ">
                    {{-- Preview foto --}}
                    <div>
                        <div
                            class="
                                flex
                                h-[220px]
                                w-full
                                items-center
                                justify-center
                                overflow-hidden
                                rounded-xl
                                border
                                border-dashed
                                border-gray-300
                                bg-gray-50
                                dark:border-gray-700
                                dark:bg-gray-900
                            ">
                            <template x-if="photoPreview">
                                <img :src="photoPreview" alt="Preview foto pejabat"
                                    class="
                                        h-full
                                        w-full
                                        object-cover
                                        object-center
                                    ">
                            </template>

                            <template x-if="!photoPreview">
                                <div
                                    class="
                                        flex
                                        flex-col
                                        items-center
                                        justify-center
                                        px-4
                                        text-center
                                        text-gray-400
                                        dark:text-gray-500
                                    ">
                                    <i class="ri-user-3-line text-5xl"></i>

                                    <span
                                        class="
                                            mt-2
                                            text-xs
                                            font-medium
                                        ">
                                        Preview foto
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Input foto --}}
                    <div class="space-y-3">
                        <div>
                            <label for="foto"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-medium
                                    text-gray-700
                                    dark:text-gray-400
                                ">
                                Upload Foto
                            </label>

                            <input x-ref="photoInput" id="foto" type="file" name="foto"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                @change="handlePhoto($event)"
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
                                    @error('foto')
                                        border-red-500
                                        dark:border-red-500
                                    @enderror
                                ">
                        </div>

                        <div
                            class="
                                rounded-lg
                                border
                                border-gray-200
                                bg-gray-50
                                px-4
                                py-3
                                dark:border-gray-800
                                dark:bg-gray-900
                            ">
                            <ul
                                class="
                                    space-y-1.5
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
                                        Format JPG, JPEG, PNG, atau WEBP.
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
                                        Ukuran file maksimal 2 MB.
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
                                        Gunakan foto formal dengan pencahayaan yang jelas.
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <p x-cloak x-show="photoError !== ''" x-text="photoError"
                            class="
                                flex
                                items-center
                                gap-1.5
                                text-xs
                                text-red-500
                            ">
                        </p>

                        @error('foto')
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
                        min-w-[165px]
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
