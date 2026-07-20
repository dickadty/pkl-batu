@extends('layouts.admin.app')

@section('title', 'Detail Slider')

@section('content')
    @php
        $bannerUrl = null;

        if (!empty($slider->banner)) {
            $bannerUrl = \Illuminate\Support\Str::startsWith($slider->banner, ['http://', 'https://'])
                ? $slider->banner
                : asset('storage/' . ltrim($slider->banner, '/'));
        }

        $bannerName = !empty($slider->banner) ? basename($slider->banner) : null;

        $bannerExtension = $bannerName ? strtoupper(pathinfo($bannerName, PATHINFO_EXTENSION)) : null;

        $formatTimestamp = static function ($value): string {
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

        $sliderDate = $formatTimestamp(data_get($slider, 'tanggal'));

        $createdAt = $formatTimestamp(data_get($slider, 'created_at'));

        $updatedAt = $formatTimestamp(data_get($slider, 'updated_at'));
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Detail Slider"
            description="Lihat judul, banner utama, tanggal publikasi, dan metadata slider." :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Slider',
                    'url' => route('admin.slider.index'),
                ],
                [
                    'label' => 'Detail Slider',
                ],
            ]">
            <x-slot:actions>
                <a href="{{ route('admin.slider.index') }}"
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
                        px-4
                        text-sm
                        font-semibold
                        text-gray-700
                        shadow-theme-xs
                        transition
                        hover:bg-gray-50
                        focus:outline-none
                        focus:ring-3
                        focus:ring-gray-500/10
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-300
                        dark:hover:bg-gray-800
                    ">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>

                    <span>Kembali</span>
                </a>

                <a href="{{ route('admin.slider.edit', $slider->id) }}"
                    class="
                        inline-flex
                        h-11
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        bg-brand-500
                        px-4
                        text-sm
                        font-semibold
                        text-white
                        shadow-theme-xs
                        transition
                        hover:bg-brand-600
                        focus:outline-none
                        focus:ring-3
                        focus:ring-brand-500/20
                    ">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>

                    <span>Edit Slider</span>
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <x-ui.flash-messages />

        <section
            class="
                overflow-hidden
                rounded-2xl
                border
                border-gray-200
                bg-white
                shadow-theme-xs
                dark:border-gray-800
                dark:bg-white/[0.03]
            ">
            @if ($bannerUrl)
                <div
                    class="
                        relative
                        aspect-[16/6]
                        min-h-[280px]
                        w-full
                        overflow-hidden
                        bg-gray-100
                        dark:bg-gray-900
                    ">
                    <img src="{{ $bannerUrl }}" alt="{{ $slider->title ?? 'Banner slider' }}"
                        class="
                            h-full
                            w-full
                            object-cover
                            object-center
                        ">

                    <div class="
                            absolute
                            inset-0
                            bg-gradient-to-t
                            from-black/80
                            via-black/15
                            to-transparent
                        "
                        aria-hidden="true"></div>

                    <div
                        class="
                            absolute
                            inset-x-0
                            bottom-0
                            p-5
                            sm:p-8
                        ">
                        <div
                            class="
                                flex
                                flex-wrap
                                items-center
                                gap-2
                            ">
                            <span
                                class="
                                    inline-flex
                                    items-center
                                    gap-2
                                    rounded-full
                                    bg-white/15
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    text-white
                                    backdrop-blur
                                ">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                                </svg>

                                {{ $sliderDate }}
                            </span>

                            <span
                                class="
                                    inline-flex
                                    rounded-full
                                    bg-blue-600
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    text-white
                                ">
                                Slider
                            </span>
                        </div>

                        <h2
                            class="
                                mt-3
                                max-w-5xl
                                text-2xl
                                font-bold
                                leading-tight
                                text-white
                                sm:text-3xl
                                lg:text-4xl
                            ">
                            {{ $slider->title ?? '-' }}
                        </h2>
                    </div>
                </div>
            @else
                <div
                    class="
                        relative
                        overflow-hidden
                        bg-gradient-to-br
                        from-blue-50
                        via-white
                        to-purple-50
                        px-5
                        py-12
                        dark:from-blue-500/10
                        dark:via-gray-900
                        dark:to-purple-500/10
                        sm:px-8
                    ">
                    <div class="flex items-start gap-4">
                        <div
                            class="
                                flex
                                h-16
                                w-16
                                shrink-0
                                items-center
                                justify-center
                                rounded-2xl
                                bg-blue-100
                                text-blue-600
                                dark:bg-blue-500/15
                                dark:text-blue-400
                            ">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2 1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <div>
                            <span
                                class="
                                    inline-flex
                                    rounded-full
                                    bg-blue-100
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    text-blue-700
                                    dark:bg-blue-500/15
                                    dark:text-blue-400
                                ">
                                Slider
                            </span>

                            <h2
                                class="
                                    mt-3
                                    max-w-5xl
                                    text-2xl
                                    font-bold
                                    leading-tight
                                    text-gray-900
                                    dark:text-white
                                    sm:text-3xl
                                ">
                                {{ $slider->title ?? '-' }}
                            </h2>

                            <p
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Banner slider belum tersedia.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
                <section
                    class="
                        overflow-hidden
                        rounded-2xl
                        border
                        border-gray-200
                        bg-white
                        shadow-theme-xs
                        dark:border-gray-800
                        dark:bg-white/[0.03]
                    ">
                    <div
                        class="
                            flex
                            items-center
                            gap-3
                            border-b
                            border-gray-100
                            px-5
                            py-4
                            dark:border-gray-800
                            sm:px-6
                        ">
                        <div
                            class="
                                flex
                                h-10
                                w-10
                                items-center
                                justify-center
                                rounded-xl
                                bg-blue-50
                                text-blue-600
                                dark:bg-blue-500/15
                                dark:text-blue-400
                            ">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2 1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <div>
                            <h3
                                class="
                                    text-base
                                    font-semibold
                                    text-gray-800
                                    dark:text-white/90
                                ">
                                File Banner
                            </h3>

                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Informasi berkas gambar yang digunakan pada slider.
                            </p>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        @if ($bannerName)
                            <div
                                class="
                                    flex
                                    flex-col
                                    gap-4
                                    rounded-2xl
                                    border
                                    border-gray-200
                                    bg-gray-50/70
                                    p-4
                                    dark:border-gray-700
                                    dark:bg-gray-900/50
                                    sm:flex-row
                                    sm:items-center
                                    sm:justify-between
                                ">
                                <div class="flex min-w-0 items-center gap-4">
                                    <div
                                        class="
                                            flex
                                            h-12
                                            w-12
                                            shrink-0
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-white
                                            text-blue-600
                                            shadow-theme-xs
                                            dark:bg-gray-800
                                            dark:text-blue-400
                                        ">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2 1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="
                                                truncate
                                                text-sm
                                                font-semibold
                                                text-gray-800
                                                dark:text-white/90
                                            "
                                            title="{{ $bannerName }}">
                                            {{ $bannerName }}
                                        </p>

                                        <div
                                            class="
                                                mt-1
                                                flex
                                                flex-wrap
                                                items-center
                                                gap-2
                                                text-xs
                                                text-gray-500
                                                dark:text-gray-400
                                            ">
                                            @if ($bannerExtension)
                                                <span
                                                    class="
                                                        rounded-md
                                                        bg-gray-200
                                                        px-2
                                                        py-1
                                                        font-semibold
                                                        text-gray-600
                                                        dark:bg-gray-700
                                                        dark:text-gray-300
                                                    ">
                                                    {{ $bannerExtension }}
                                                </span>
                                            @endif

                                            <span>
                                                Gambar banner slider
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ $bannerUrl }}" target="_blank" rel="noopener noreferrer"
                                    class="
                                        inline-flex
                                        h-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        gap-2
                                        rounded-lg
                                        border
                                        border-blue-200
                                        bg-blue-50
                                        px-4
                                        text-sm
                                        font-semibold
                                        text-blue-700
                                        transition
                                        hover:bg-blue-100
                                        dark:border-blue-500/20
                                        dark:bg-blue-500/10
                                        dark:text-blue-400
                                        dark:hover:bg-blue-500/20
                                    ">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-4.553M19.553 5.447H15m4.553 0V10M13 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-7" />
                                    </svg>

                                    <span>Buka Gambar</span>
                                </a>
                            </div>
                        @else
                            <div
                                class="
                                    rounded-2xl
                                    border
                                    border-dashed
                                    border-gray-300
                                    bg-gray-50
                                    px-6
                                    py-10
                                    text-center
                                    dark:border-gray-700
                                    dark:bg-gray-900/50
                                ">
                                <p
                                    class="
                                        text-sm
                                        font-medium
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    Berkas banner belum tersedia.
                                </p>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section
                    class="
                        overflow-hidden
                        rounded-2xl
                        border
                        border-gray-200
                        bg-white
                        shadow-theme-xs
                        dark:border-gray-800
                        dark:bg-white/[0.03]
                    ">
                    <div
                        class="
                            border-b
                            border-gray-100
                            px-5
                            py-4
                            dark:border-gray-800
                        ">
                        <h3
                            class="
                                text-base
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Detail Slider
                        </h3>
                    </div>

                    <dl class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div class="px-5 py-4">
                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                ID Slider
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-gray-200
                                ">
                                {{ $slider->id }}
                            </dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                Judul
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    font-semibold
                                    leading-6
                                    text-gray-800
                                    dark:text-gray-200
                                ">
                                {{ $slider->title ?? '-' }}
                            </dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                Tanggal
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $sliderDate }}
                            </dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                Banner
                            </dt>

                            <dd class="mt-2">
                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-semibold
                                        {{ $bannerUrl
                                            ? 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400'
                                            : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}
                                    ">
                                    {{ $bannerUrl ? 'Tersedia' : 'Tidak tersedia' }}
                                </span>
                            </dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                Dibuat
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $createdAt }}
                            </dd>
                        </div>

                        <div class="px-5 py-4">
                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                Diperbarui
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $updatedAt }}
                            </dd>
                        </div>
                    </dl>
                </section>

                <section
                    class="
                        rounded-2xl
                        border
                        border-red-200
                        bg-red-50/70
                        p-5
                        dark:border-red-500/20
                        dark:bg-red-500/10
                    ">
                    <h3
                        class="
                            text-sm
                            font-semibold
                            text-red-800
                            dark:text-red-300
                        ">
                        Hapus Slider
                    </h3>

                    <p
                        class="
                            mt-1
                            text-sm
                            leading-6
                            text-red-700
                            dark:text-red-400
                        ">
                        Data dan banner slider yang dihapus tidak dapat dikembalikan.
                    </p>

                    <form
                        action="{{ route('admin.slider.destroy', $slider->id) }}"
                        method="POST" class="mt-4"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus slider ini?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="
                                inline-flex
                                h-10
                                w-full
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                border
                                border-red-300
                                bg-white
                                px-4
                                text-sm
                                font-semibold
                                text-red-600
                                transition
                                hover:bg-red-600
                                hover:text-white
                                focus:outline-none
                                focus:ring-3
                                focus:ring-red-500/20
                                dark:border-red-500/30
                                dark:bg-gray-900
                                dark:text-red-400
                                dark:hover:bg-red-600
                                dark:hover:text-white
                            ">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>

                            <span>Hapus Slider</span>
                        </button>
                    </form>
                </section>
            </aside>
        </div>
    </div>
@endsection
