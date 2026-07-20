@extends('layouts.admin.app')

@section('title', 'Detail Berita')

@section('content')
    @php
        $imageUrl = null;

        if (!empty($berita->gambar)) {
            $imageUrl = \Illuminate\Support\Str::startsWith($berita->gambar, ['http://', 'https://'])
                ? $berita->gambar
                : asset('storage/' . ltrim($berita->gambar, '/'));
        }

        $formatDate = static function ($value): string {
            if (empty($value)) {
                return '-';
            }

            try {
                if (is_numeric($value) && (int) $value > 100000000) {
                    return \Illuminate\Support\Carbon::createFromTimestamp((int) $value)->translatedFormat('d F Y');
                }

                return \Illuminate\Support\Carbon::parse($value)->translatedFormat('d F Y');
            } catch (\Throwable $exception) {
                return (string) $value;
            }
        };

        $formatDateTime = static function ($value): string {
            if (empty($value)) {
                return '-';
            }

            try {
                return \Illuminate\Support\Carbon::parse($value)->translatedFormat('d F Y, H:i');
            } catch (\Throwable $exception) {
                return (string) $value;
            }
        };

        $publicationDate = $formatDate(data_get($berita, 'tanggal'));

        $createdAt = $formatDateTime(data_get($berita, 'created_at'));

        $updatedAt = $formatDateTime(data_get($berita, 'updated_at'));

        $caption = trim((string) ($berita->caption ?? ''));
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Detail Berita"
            description="Lihat gambar utama, judul, isi berita, tanggal publikasi, dan metadata berita." :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Berita',
                    'url' => route('admin.berita.index'),
                ],
                [
                    'label' => 'Detail Berita',
                ],
            ]">
            <x-slot:actions>
                <a href="{{ route('admin.berita.index') }}"
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

                <a href="{{ route('public.berita.show', $berita->id) }}" target="_blank" rel="noopener noreferrer"
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
                            d="M15 10l4.553-4.553M19.553 5.447H15m4.553 0V10M13 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-7" />
                    </svg>

                    <span>Lihat Halaman Publik</span>
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
            @if ($imageUrl)
                <div
                    class="
                        relative
                        aspect-[16/7]
                        min-h-[260px]
                        w-full
                        overflow-hidden
                        bg-gray-100
                        dark:bg-gray-900
                    ">
                    <img src="{{ $imageUrl }}" alt="{{ $berita->judul ?? 'Gambar berita' }}"
                        class="
                            h-full
                            w-full
                            object-cover
                            object-center
                        ">

                    <div
                        class="
                            absolute
                            inset-0
                            bg-gradient-to-t
                            from-black/70
                            via-black/10
                            to-transparent
                        ">
                    </div>

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

                                {{ $publicationDate }}
                            </span>

                            <span
                                class="
                                    inline-flex
                                    rounded-full
                                    bg-orange-500
                                    px-3
                                    py-1.5
                                    text-xs
                                    font-semibold
                                    text-white
                                ">
                                Berita
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
                            {{ $berita->judul ?? '-' }}
                        </h2>
                    </div>
                </div>
            @else
                <div
                    class="
                        relative
                        overflow-hidden
                        bg-gradient-to-br
                        from-orange-50
                        via-white
                        to-yellow-50
                        px-5
                        py-10
                        dark:from-orange-500/10
                        dark:via-gray-900
                        dark:to-yellow-500/10
                        sm:px-8
                    ">
                    <div class="flex items-start gap-4">
                        <div
                            class="
                                flex
                                h-14
                                w-14
                                shrink-0
                                items-center
                                justify-center
                                rounded-2xl
                                bg-orange-100
                                text-orange-600
                                dark:bg-orange-500/15
                                dark:text-orange-400
                            ">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2zM7 8h10M7 12h10M7 16h6" />
                            </svg>
                        </div>

                        <div>
                            <div
                                class="
                                    flex
                                    flex-wrap
                                    items-center
                                    gap-2
                                ">
                                <span
                                    class="
                                        rounded-full
                                        bg-orange-100
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-semibold
                                        text-orange-700
                                        dark:bg-orange-500/15
                                        dark:text-orange-400
                                    ">
                                    Berita
                                </span>

                                <span
                                    class="
                                        text-sm
                                        text-gray-500
                                        dark:text-gray-400
                                    ">
                                    {{ $publicationDate }}
                                </span>
                            </div>

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
                                {{ $berita->judul ?? '-' }}
                            </h2>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
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
                    xl:col-span-2
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
                            bg-orange-50
                            text-orange-600
                            dark:bg-orange-500/15
                            dark:text-orange-400
                        ">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h10M4 18h7" />
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
                            Isi Berita
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Konten lengkap berita yang disimpan pada sistem.
                        </p>
                    </div>
                </div>

                <div class="px-5 py-6 sm:px-6">
                    @if ($caption !== '')
                        <div
                            class="
                                whitespace-pre-line
                                text-[15px]
                                leading-8
                                text-gray-700
                                dark:text-gray-300
                            ">
                            {{ $caption }}</div>
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
                            <svg class="
                                    mx-auto
                                    h-8
                                    w-8
                                    text-gray-400
                                "
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h10M4 18h7" />
                            </svg>

                            <p
                                class="
                                    mt-3
                                    text-sm
                                    font-medium
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Isi berita belum tersedia.
                            </p>
                        </div>
                    @endif
                </div>
            </section>

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
                            Informasi Berita
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
                                ID Berita
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-gray-200
                                ">
                                {{ $berita->id }}
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
                                Tanggal Publikasi
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $publicationDate }}
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
                                Terakhir Diperbarui
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

                        <div class="px-5 py-4">
                            <dt
                                class="
                                    text-xs
                                    font-medium
                                    uppercase
                                    tracking-wide
                                    text-gray-400
                                ">
                                Gambar
                            </dt>

                            <dd class="mt-1.5">
                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-semibold
                                        {{ $imageUrl
                                            ? 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-400'
                                            : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}
                                    ">
                                    {{ $imageUrl ? 'Tersedia' : 'Tidak tersedia' }}
                                </span>
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
                        Hapus Berita
                    </h3>

                    <p
                        class="
                            mt-1
                            text-sm
                            leading-6
                            text-red-700
                            dark:text-red-400
                        ">
                        Berita dan gambar yang dihapus tidak dapat dikembalikan.
                    </p>

                    <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" class="mt-4"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
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

                            <span>Hapus Berita</span>
                        </button>
                    </form>
                </section>
            </aside>
        </div>
    </div>
@endsection
