@props([
    'action',
    'method' => 'POST',
    'slider' => null,
    'title' => 'Informasi Slider',
    'description' => 'Lengkapi judul dan banner yang akan ditampilkan pada halaman utama website.',
    'submitLabel' => 'Simpan Slider',
    'cancelUrl' => null,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi form
    |--------------------------------------------------------------------------
    */

    $formMethod = strtoupper($method);

    $cancelUrl = $cancelUrl ?? route('admin.slider.index');

    /*
    |--------------------------------------------------------------------------
    | Nilai field
    |--------------------------------------------------------------------------
    */

    $titleValue = old('title', data_get($slider, 'title', ''));

    /*
    |--------------------------------------------------------------------------
    | Banner lama untuk halaman edit
    |--------------------------------------------------------------------------
    */

    $currentBanner = data_get($slider, 'banner');

    $currentBannerUrl = null;

    if (!empty($currentBanner)) {
        $currentBannerUrl = \Illuminate\Support\Str::startsWith($currentBanner, ['http://', 'https://'])
            ? $currentBanner
            : asset('storage/' . ltrim($currentBanner, '/'));
    }

    $bannerRequired = $slider === null;
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
                        Data banner slider
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
            FORM SLIDER
        ============================================================= --}}

        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" x-data="{
            submitting: false,
            bannerPreview: @js($currentBannerUrl),
            initialBanner: @js($currentBannerUrl),
            bannerError: '',
            bannerName: '',
        
            handleBanner(event) {
                const file = event.target.files[0];
        
                this.bannerError = '';
                this.bannerName = '';
        
                if (!file) {
                    this.bannerPreview = this.initialBanner;
                    return;
                }
        
                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];
        
                if (!allowedTypes.includes(file.type)) {
                    this.bannerError =
                        'Format banner harus JPG, JPEG, PNG, atau WEBP.';
        
                    event.target.value = '';
                    this.bannerPreview = this.initialBanner;
        
                    return;
                }
        
                if (file.size > 3 * 1024 * 1024) {
                    this.bannerError =
                        'Ukuran banner maksimal 3 MB.';
        
                    event.target.value = '';
                    this.bannerPreview = this.initialBanner;
        
                    return;
                }
        
                this.bannerName = file.name;
        
                const reader = new FileReader();
        
                reader.onload = loadEvent => {
                    this.bannerPreview =
                        loadEvent.target.result;
                };
        
                reader.readAsDataURL(file);
            },
        
            resetBanner() {
                this.bannerPreview = this.initialBanner;
                this.bannerError = '';
                this.bannerName = '';
        
                if (this.$refs.bannerInput) {
                    this.$refs.bannerInput.value = '';
                }
            }
        }"
            @submit="submitting = true" @reset="resetBanner()" class="space-y-8">
            @csrf

            @if (!in_array($formMethod, ['GET', 'POST'], true))
                @method($formMethod)
            @endif

            {{-- ========================================================
                INFORMASI SLIDER
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
                        <i class="ri-slideshow-3-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Informasi Slider
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Masukkan judul dan gambar banner slider.
                        </p>
                    </div>
                </div>

                {{-- Judul --}}
                <div>
                    <label for="title"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-400
                        ">
                        Judul Slider

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

                        <input id="title" type="text" name="title" value="{{ $titleValue }}"
                            placeholder="Masukkan judul slider" autocomplete="off" required autofocus
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
                                @error('title')
                                    border-red-500
                                    focus:border-red-500
                                    focus:ring-red-500/10
                                    dark:border-red-500
                                @enderror
                            ">
                    </div>

                    @error('title')
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
                            Gunakan judul singkat yang menjelaskan isi banner.
                        </p>
                    @enderror
                </div>
            </section>

            {{-- ========================================================
                BANNER SLIDER
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
                            Banner Slider
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Gunakan banner horizontal dengan kualitas gambar yang baik.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        grid
                        grid-cols-1
                        gap-5
                        xl:grid-cols-[minmax(0,1.4fr)_minmax(320px,0.6fr)]
                    ">
                    {{-- Preview banner --}}
                    <div>
                        <div
                            class="
                                flex
                                aspect-[16/7]
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
                            <template x-if="bannerPreview">
                                <img :src="bannerPreview" alt="Preview banner slider"
                                    class="
                                        h-full
                                        w-full
                                        object-cover
                                        object-center
                                    ">
                            </template>

                            <template x-if="!bannerPreview">
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
                                    <i class="ri-image-line text-5xl"></i>

                                    <span
                                        class="
                                            mt-2
                                            text-sm
                                            font-medium
                                        ">
                                        Preview banner
                                    </span>

                                    <span class="mt-1 text-xs">
                                        Gambar akan ditampilkan di area ini.
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Input banner --}}
                    <div class="space-y-4">
                        <div>
                            <label for="banner"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-medium
                                    text-gray-700
                                    dark:text-gray-400
                                ">
                                Upload Banner

                                @if ($bannerRequired)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>

                            <input x-ref="bannerInput" id="banner" type="file" name="banner"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                @change="handleBanner($event)" @if ($bannerRequired) required @endif
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
                                    @error('banner')
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
                                        Ukuran file maksimal 3 MB.
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
                                        Rasio yang disarankan adalah 16:7 atau 16:9.
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <p x-cloak x-show="bannerName !== ''"
                            class="
                                flex
                                items-center
                                gap-2
                                text-xs
                                text-gray-600
                                dark:text-gray-400
                            ">
                            <i class="ri-file-image-line text-blue-500"></i>

                            <span x-text="bannerName"></span>
                        </p>

                        <p x-cloak x-show="bannerError !== ''"
                            class="
                                flex
                                items-center
                                gap-1.5
                                text-xs
                                text-red-500
                            ">
                            <i class="ri-error-warning-line"></i>

                            <span x-text="bannerError"></span>
                        </p>

                        @error('banner')
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
