@extends('layouts.admin.app')

@section('title', 'Detail FAQ')

@section('content')
    @php
        $isActive = (int) data_get($faq, 'status', 0) === 1;

        $statusLabel = $isActive ? 'Aktif' : 'Nonaktif';

        $statusClass = $isActive
            ? 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/15 dark:text-green-400 dark:ring-green-500/20'
            : 'bg-gray-100 text-gray-700 ring-gray-500/20 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-600/30';

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

        $faqDate = $formatTimestamp(data_get($faq, 'tanggal'));

        $createdAt = $formatTimestamp(data_get($faq, 'created_at'));

        $updatedAt = $formatTimestamp(data_get($faq, 'updated_at'));

        $question = trim((string) ($faq->pertanyaan ?? ''));

        $answer = trim((string) ($faq->jawaban ?? ''));
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Detail FAQ"
            description="Lihat pertanyaan, jawaban lengkap, status publikasi, tanggal, dan metadata FAQ." :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'FAQ',
                    'url' => route('admin.faq.index'),
                ],
                [
                    'label' => 'Detail FAQ',
                ],
            ]">
            <x-slot:actions>
                <a href="{{ route('admin.faq.index') }}"
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

                <a href="{{ route('admin.faq.edit', $faq->id) }}"
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

                    <span>Edit FAQ</span>
                </a>
            </x-slot:actions>
        </x-admin.page-header>

        <x-ui.flash-messages />

        <section
            class="
                relative
                overflow-hidden
                rounded-2xl
                border
                border-gray-200
                bg-white
                p-5
                shadow-theme-xs
                dark:border-gray-800
                dark:bg-white/[0.03]
                sm:p-6
            ">
            <div class="
                    pointer-events-none
                    absolute
                    -right-20
                    -top-24
                    h-64
                    w-64
                    rounded-full
                    bg-blue-500/[0.07]
                    blur-3xl
                    dark:bg-blue-500/[0.1]
                "
                aria-hidden="true"></div>

            <div class="relative flex items-start gap-4">
                <div
                    class="
                        flex
                        h-14
                        w-14
                        shrink-0
                        items-center
                        justify-center
                        rounded-2xl
                        bg-blue-50
                        text-blue-600
                        ring-1
                        ring-blue-100
                        dark:bg-blue-500/15
                        dark:text-blue-400
                        dark:ring-blue-500/20
                    ">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9a3.001 3.001 0 115.544 1.607c-.622.873-1.772 1.393-1.772 2.393M12 17h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
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
                                gap-1.5
                                rounded-full
                                px-3
                                py-1.5
                                text-xs
                                font-semibold
                                ring-1
                                ring-inset
                                {{ $statusClass }}
                            ">
                            <span
                                class="
                                    h-1.5
                                    w-1.5
                                    rounded-full
                                    {{ $isActive ? 'bg-green-500' : 'bg-gray-400' }}
                                "></span>

                            {{ $statusLabel }}
                        </span>

                        <span
                            class="
                                inline-flex
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
                            FAQ
                        </span>
                    </div>

                    <h2
                        class="
                            mt-3
                            max-w-5xl
                            text-xl
                            font-bold
                            leading-8
                            text-gray-900
                            dark:text-white
                            sm:text-2xl
                        ">
                        {{ $question !== '' ? $question : '-' }}
                    </h2>

                    <div
                        class="
                            mt-3
                            flex
                            flex-wrap
                            items-center
                            gap-x-5
                            gap-y-2
                            text-sm
                            text-gray-500
                            dark:text-gray-400
                        ">
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                            </svg>

                            {{ $faqDate }}
                        </span>

                        <span>
                            ID FAQ: {{ $faq->id }}
                        </span>
                    </div>
                </div>
            </div>
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
                                bg-green-50
                                text-green-600
                                dark:bg-green-500/15
                                dark:text-green-400
                            ">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5-3a9 9 0 11-16 0 9 9 0 0116 0z" />
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
                                Jawaban FAQ
                            </h3>

                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Jawaban lengkap yang diberikan kepada masyarakat.
                            </p>
                        </div>
                    </div>

                    <div class="px-5 py-6 sm:px-6">
                        @if ($answer !== '')
                            <div
                                class="
                                    rounded-2xl
                                    border
                                    border-green-100
                                    bg-green-50/40
                                    px-5
                                    py-5
                                    dark:border-green-500/20
                                    dark:bg-green-500/[0.06]
                                    sm:px-6
                                ">
                                <div
                                    class="
                                        whitespace-pre-line
                                        text-[15px]
                                        leading-8
                                        text-gray-700
                                        dark:text-gray-300
                                    ">
                                    {{ $answer }}</div>
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
                                    Jawaban FAQ belum tersedia.
                                </p>
                            </div>
                        @endif
                    </div>
                </section>

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
                                bg-purple-50
                                text-purple-600
                                dark:bg-purple-500/15
                                dark:text-purple-400
                            ">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9a3.001 3.001 0 115.544 1.607c-.622.873-1.772 1.393-1.772 2.393M12 17h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                                Pertanyaan
                            </h3>

                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Pertanyaan yang ditampilkan pada halaman FAQ.
                            </p>
                        </div>
                    </div>

                    <div class="px-5 py-5 sm:px-6">
                        <blockquote
                            class="
                                border-l-4
                                border-purple-500
                                pl-4
                                text-base
                                font-semibold
                                leading-8
                                text-gray-800
                                dark:text-gray-200
                            ">
                            {{ $question !== '' ? $question : '-' }}
                        </blockquote>
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
                            Detail FAQ
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
                                ID FAQ
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-gray-200
                                ">
                                {{ $faq->id }}
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
                                Status
                            </dt>

                            <dd class="mt-2">
                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-1.5
                                        rounded-full
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-semibold
                                        ring-1
                                        ring-inset
                                        {{ $statusClass }}
                                    ">
                                    <span
                                        class="
                                            h-1.5
                                            w-1.5
                                            rounded-full
                                            {{ $isActive ? 'bg-green-500' : 'bg-gray-400' }}
                                        "></span>

                                    {{ $statusLabel }}
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
                                Tanggal
                            </dt>

                            <dd
                                class="
                                    mt-1.5
                                    text-sm
                                    text-gray-700
                                    dark:text-gray-300
                                ">
                                {{ $faqDate }}
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
                        Hapus FAQ
                    </h3>

                    <p
                        class="
                            mt-1
                            text-sm
                            leading-6
                            text-red-700
                            dark:text-red-400
                        ">
                        FAQ yang dihapus tidak dapat dikembalikan dan tidak lagi tampil pada halaman publik.
                    </p>

                    <form
                        action="{{ route('admin.faq.destroy', $faq->id) }}"
                        method="POST" class="mt-4"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus FAQ ini?')">
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

                            <span>Hapus FAQ</span>
                        </button>
                    </form>
                </section>
            </aside>
        </div>
    </div>
@endsection
