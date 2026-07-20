@props([
    'action',
    'method' => 'POST',
    'berita' => null,
    'title' => 'Informasi Berita',
    'description' => 'Lengkapi judul, isi berita, dan gambar utama yang akan ditampilkan kepada masyarakat.',
    'submitLabel' => 'Simpan Berita',
    'cancelUrl' => null,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi form
    |--------------------------------------------------------------------------
    */

    $formMethod = strtoupper($method);

    $cancelUrl = $cancelUrl ?? route('admin.berita.index');

    /*
    |--------------------------------------------------------------------------
    | Nilai field
    |--------------------------------------------------------------------------
    */

    $judulValue = old('judul', data_get($berita, 'judul', ''));

    $captionValue = old('caption', data_get($berita, 'caption', ''));

    /*
    |--------------------------------------------------------------------------
    | Gambar lama untuk halaman edit
    |--------------------------------------------------------------------------
    */

    $currentImage = data_get($berita, 'gambar');

    $currentImageUrl = null;

    if (!empty($currentImage)) {
        $currentImageUrl = \Illuminate\Support\Str::startsWith($currentImage, ['http://', 'https://'])
            ? $currentImage
            : asset('storage/' . ltrim($currentImage, '/'));
    }

    /*
    |--------------------------------------------------------------------------
    | Panjang teks awal
    |--------------------------------------------------------------------------
    */

    $judulLength = mb_strlen((string) $judulValue);

    $captionLength = mb_strlen(trim(strip_tags((string) $captionValue)));
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
                        Data publikasi berita
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
            FORM BERITA
        ============================================================= --}}

        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" x-data="{
            submitting: false,
        
            judulLength: @js($judulLength),
            captionLength: @js($captionLength),
        
            imagePreview: @js($currentImageUrl),
            initialImage: @js($currentImageUrl),
        
            imageName: '',
            imageError: '',
        
            updateJudulLength(event) {
                this.judulLength =
                    event.target.value.length;
            },
        
            updateCaptionLength(event) {
                this.captionLength =
                    event.target.value.length;
            },
        
            handleImage(event) {
                const file = event.target.files[0];
        
                this.imageError = '';
                this.imageName = '';
        
                if (!file) {
                    this.imagePreview =
                        this.initialImage;
        
                    return;
                }
        
                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];
        
                if (!allowedTypes.includes(file.type)) {
                    this.imageError =
                        'Format gambar harus JPG, JPEG, PNG, atau WEBP.';
        
                    event.target.value = '';
        
                    this.imagePreview =
                        this.initialImage;
        
                    return;
                }
        
                const maximumSize =
                    2 * 1024 * 1024;
        
                if (file.size > maximumSize) {
                    this.imageError =
                        'Ukuran gambar maksimal 2 MB.';
        
                    event.target.value = '';
        
                    this.imagePreview =
                        this.initialImage;
        
                    return;
                }
        
                this.imageName = file.name;
        
                const reader = new FileReader();
        
                reader.onload = loadEvent => {
                    this.imagePreview =
                        loadEvent.target.result;
                };
        
                reader.readAsDataURL(file);
            },
        
            resetImage() {
                this.imagePreview =
                    this.initialImage;
        
                this.imageName = '';
                this.imageError = '';
        
                if (this.$refs.imageInput) {
                    this.$refs.imageInput.value = '';
                }
            },
        
            resetFormState() {
                this.judulLength = 0;
                this.captionLength = 0;
        
                this.resetImage();
            }
        }"
            @submit="submitting = true"
            @reset="
                setTimeout(
                    () => resetFormState(),
                    0
                )
            "
            class="space-y-8">
            @csrf

            @if (!in_array($formMethod, ['GET', 'POST'], true))
                @method($formMethod)
            @endif

            {{-- ========================================================
                INFORMASI UTAMA BERITA
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
                        <i class="ri-newspaper-line text-lg"></i>
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
                            Masukkan judul dan isi utama berita.
                        </p>
                    </div>
                </div>

                {{-- Judul berita --}}
                <div>
                    <label for="judul"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-400
                        ">
                        Judul Berita

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
                            <i class="ri-text text-lg"></i>
                        </span>

                        <input id="judul" type="text" name="judul" value="{{ $judulValue }}"
                            placeholder="Masukkan judul berita" autocomplete="off" maxlength="255" required autofocus
                            @input="
                                updateJudulLength($event)
                            "
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
                                @error('judul')
                                    border-red-500
                                    focus:border-red-500
                                    focus:ring-red-500/10
                                    dark:border-red-500
                                @enderror
                            ">
                    </div>

                    <div
                        class="
                            mt-1.5
                            flex
                            items-start
                            justify-between
                            gap-3
                        ">
                        @error('judul')
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
                                Gunakan judul yang ringkas, jelas, dan mewakili isi berita.
                            </p>
                        @enderror

                        <span
                            class="
                                shrink-0
                                text-xs
                                text-gray-400
                                dark:text-gray-500
                            ">
                            <span x-text="judulLength"></span>/255
                        </span>
                    </div>
                </div>

                {{-- Caption berita --}}
                <div>
                    <label for="caption"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-400
                        ">
                        Isi atau Caption Berita
                    </label>

                    <textarea id="caption" name="caption" rows="10" placeholder="Masukkan isi atau caption berita secara lengkap"
                        @input="
                            updateCaptionLength($event)
                        "
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
                            leading-7
                            text-gray-800
                            placeholder:text-gray-400
                            focus:ring-3
                            focus:outline-hidden
                            dark:border-gray-700
                            dark:bg-gray-900
                            dark:text-white/90
                            dark:placeholder:text-white/30
                            @error('caption')
                                border-red-500
                                focus:border-red-500
                                focus:ring-red-500/10
                                dark:border-red-500
                            @enderror
                        ">{{ $captionValue }}</textarea>

                    <div
                        class="
                            mt-1.5
                            flex
                            items-start
                            justify-between
                            gap-3
                        ">
                        @error('caption')
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
                                Susun isi berita secara informatif dan hindari informasi yang belum terverifikasi.
                            </p>
                        @enderror

                        <span
                            class="
                                shrink-0
                                text-xs
                                text-gray-400
                                dark:text-gray-500
                            ">
                            <span x-text="captionLength"></span>
                            karakter
                        </span>
                    </div>
                </div>
            </section>

            {{-- ========================================================
                GAMBAR BERITA
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
                            Gambar Utama
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Unggah gambar utama yang relevan dengan isi berita.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        grid
                        grid-cols-1
                        gap-6
                        xl:grid-cols-[minmax(0,1.35fr)_minmax(320px,0.65fr)]
                    ">
                    {{-- Preview gambar --}}
                    <div class="space-y-2">
                        <label
                            class="
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-400
                            ">
                            Preview Gambar
                        </label>

                        <div
                            class="
                                flex
                                aspect-[16/9]
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
                            <template x-if="imagePreview">
                                <img :src="imagePreview" alt="Preview gambar berita"
                                    class="
                                        h-full
                                        w-full
                                        object-cover
                                        object-center
                                    ">
                            </template>

                            <template x-if="!imagePreview">
                                <div
                                    class="
                                        flex
                                        flex-col
                                        items-center
                                        justify-center
                                        px-6
                                        text-center
                                        text-gray-400
                                        dark:text-gray-500
                                    ">
                                    <div
                                        class="
                                            flex
                                            h-16
                                            w-16
                                            items-center
                                            justify-center
                                            rounded-full
                                            bg-white
                                            shadow-sm
                                            dark:bg-gray-800
                                        ">
                                        <i class="ri-image-line text-3xl"></i>
                                    </div>

                                    <p
                                        class="
                                            mt-3
                                            text-sm
                                            font-medium
                                        ">
                                        Belum ada gambar
                                    </p>

                                    <p
                                        class="
                                            mt-1
                                            max-w-xs
                                            text-xs
                                            leading-5
                                        ">
                                        Gambar yang dipilih akan ditampilkan di area ini.
                                    </p>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Input gambar --}}
                    <div class="space-y-4">
                        <div>
                            <label for="gambar"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-medium
                                    text-gray-700
                                    dark:text-gray-400
                                ">
                                Upload Gambar Berita
                            </label>

                            <input x-ref="imageInput" id="gambar" type="file" name="gambar"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                @change="
                                    handleImage($event)
                                "
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
                                    @error('gambar')
                                        border-red-500
                                        dark:border-red-500
                                    @enderror
                                ">
                        </div>

                        {{-- File terpilih --}}
                        <div x-cloak x-show="imageName !== ''" x-transition
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
                                <i class="ri-file-image-line text-lg"></i>
                            </div>

                            <div class="min-w-0">
                                <p
                                    class="
                                        text-xs
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    Gambar terpilih
                                </p>

                                <p x-text="imageName"
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

                        {{-- Ketentuan gambar --}}
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
                                Ketentuan gambar
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
                                            shrink-0
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
                                            shrink-0
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
                                            shrink-0
                                            text-green-500
                                        "></i>

                                    <span>
                                        Rasio gambar yang disarankan adalah 16:9.
                                    </span>
                                </li>

                                <li class="flex items-start gap-2">
                                    <i
                                        class="
                                            ri-checkbox-circle-line
                                            mt-0.5
                                            shrink-0
                                            text-green-500
                                        "></i>

                                    <span>
                                        Gunakan gambar yang jelas dan relevan dengan isi berita.
                                    </span>
                                </li>
                            </ul>
                        </div>

                        {{-- Error Alpine --}}
                        <p x-cloak x-show="imageError !== ''" x-transition
                            class="
                                flex
                                items-start
                                gap-1.5
                                text-xs
                                leading-5
                                text-red-500
                            ">
                            <i
                                class="
                                    ri-error-warning-line
                                    mt-0.5
                                    shrink-0
                                "></i>

                            <span x-text="imageError"></span>
                        </p>

                        {{-- Error Laravel --}}
                        @error('gambar')
                            <p
                                class="
                                    flex
                                    items-start
                                    gap-1.5
                                    text-xs
                                    leading-5
                                    text-red-500
                                ">
                                <i
                                    class="
                                        ri-error-warning-line
                                        mt-0.5
                                        shrink-0
                                    "></i>

                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- ========================================================
                RINGKASAN PENGISIAN
            ========================================================= --}}

            <section>
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
                    <div class="flex items-start gap-3">
                        <div
                            class="
                                flex
                                h-10
                                w-10
                                shrink-0
                                items-center
                                justify-center
                                rounded-full
                                bg-green-50
                                text-green-600
                                dark:bg-green-500/15
                                dark:text-green-400
                            ">
                            <i class="ri-checkbox-circle-line text-lg"></i>
                        </div>

                        <div class="min-w-0">
                            <h3
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-white/90
                                ">
                                Periksa kembali sebelum menyimpan
                            </h3>

                            <p
                                class="
                                    mt-1
                                    text-xs
                                    leading-5
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Pastikan judul sudah sesuai, isi berita tidak mengandung kesalahan, dan gambar memiliki
                                hak penggunaan yang jelas.
                            </p>
                        </div>
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
                {{-- Kembali --}}
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

                {{-- Reset --}}
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

                {{-- Simpan --}}
                <button type="submit" :disabled="submitting"
                    class="
                        inline-flex
                        h-11
                        min-w-[170px]
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
