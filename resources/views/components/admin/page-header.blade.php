@props(['title', 'description' => null, 'breadcrumbs' => []])

<header
    {{ $attributes->class([
        'relative',
        'overflow-hidden',
        'rounded-2xl',
        'border',
        'border-gray-200',
        'bg-white',
        'px-5',
        'py-5',
        'shadow-theme-xs',
        'dark:border-gray-800',
        'dark:bg-white/[0.03]',
        'sm:px-6',
        'sm:py-6',
    ]) }}>
    {{-- Dekorasi latar belakang --}}
    <div class="
            pointer-events-none
            absolute
            -right-16
            -top-20
            h-48
            w-48
            rounded-full
            bg-brand-500/[0.06]
            blur-3xl
            dark:bg-brand-500/[0.08]
        "
        aria-hidden="true"></div>

    <div class="
            pointer-events-none
            absolute
            -bottom-24
            left-1/3
            h-40
            w-40
            rounded-full
            bg-blue-500/[0.04]
            blur-3xl
            dark:bg-blue-500/[0.06]
        "
        aria-hidden="true"></div>

    <div
        class="
            relative
            z-10
            flex
            flex-col
            gap-5
            lg:flex-row
            lg:items-center
            lg:justify-between
        ">
        {{-- Informasi halaman --}}
        <div class="min-w-0 flex-1">
            {{-- Breadcrumb --}}
            @if (!empty($breadcrumbs))
                <nav class="
                        mb-3
                        flex
                        flex-wrap
                        items-center
                        gap-x-1.5
                        gap-y-2
                    "
                    aria-label="Breadcrumb">
                    @foreach ($breadcrumbs as $breadcrumb)
                        @php
                            $breadcrumbLabel = $breadcrumb['label'] ?? '';
                            $breadcrumbUrl = $breadcrumb['url'] ?? null;
                            $breadcrumbIcon = $breadcrumb['icon'] ?? null;
                            $isCurrentPage = $loop->last;
                        @endphp

                        @if (!$loop->first)
                            <span
                                class="
                                    inline-flex
                                    h-6
                                    w-6
                                    shrink-0
                                    items-center
                                    justify-center
                                    text-gray-300
                                    dark:text-gray-600
                                "
                                aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        @endif

                        @if ($breadcrumbUrl && !$isCurrentPage)
                            <a href="{{ $breadcrumbUrl }}"
                                class="
                                    inline-flex
                                    min-h-8
                                    items-center
                                    gap-1.5
                                    rounded-lg
                                    px-2
                                    py-1
                                    text-sm
                                    font-medium
                                    text-gray-500
                                    transition-colors
                                    duration-200
                                    hover:bg-gray-100
                                    hover:text-brand-600
                                    focus:outline-none
                                    focus:ring-2
                                    focus:ring-brand-500/20
                                    dark:text-gray-400
                                    dark:hover:bg-white/[0.05]
                                    dark:hover:text-brand-400
                                ">
                                @if ($breadcrumbIcon)
                                    <i class="{{ $breadcrumbIcon }} text-base" aria-hidden="true"></i>
                                @endif

                                <span>
                                    {{ $breadcrumbLabel }}
                                </span>
                            </a>
                        @else
                            <span
                                class="
                                    inline-flex
                                    min-h-8
                                    items-center
                                    gap-1.5
                                    rounded-lg
                                    px-2
                                    py-1
                                    text-sm
                                    font-semibold
                                    {{ $isCurrentPage
                                        ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400'
                                        : 'text-gray-600 dark:text-gray-300' }}
                                "
                                @if ($isCurrentPage) aria-current="page" @endif>
                                @if ($breadcrumbIcon)
                                    <i class="{{ $breadcrumbIcon }} text-base" aria-hidden="true"></i>
                                @endif

                                <span>
                                    {{ $breadcrumbLabel }}
                                </span>
                            </span>
                        @endif
                    @endforeach
                </nav>
            @endif

            {{-- Judul dan ikon --}}
            <div class="flex items-start gap-3 sm:gap-4">
                <div class="
                        mt-0.5
                        flex
                        h-11
                        w-11
                        shrink-0
                        items-center
                        justify-center
                        rounded-xl
                        bg-brand-50
                        text-brand-600
                        ring-1
                        ring-brand-100
                        dark:bg-brand-500/10
                        dark:text-brand-400
                        dark:ring-brand-500/20
                        sm:h-12
                        sm:w-12
                    "
                    aria-hidden="true">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <h1
                        class="
                            break-words
                            text-2xl
                            font-bold
                            tracking-tight
                            text-gray-900
                            dark:text-white
                            sm:text-3xl
                        ">
                        {{ $title }}
                    </h1>

                    @if ($description)
                        <p
                            class="
                                mt-2
                                max-w-3xl
                                text-sm
                                leading-6
                                text-gray-500
                                dark:text-gray-400
                                sm:text-[15px]
                            ">
                            {{ $description }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tombol aksi halaman --}}
        @isset($actions)
            <div
                class="
                    flex
                    w-full
                    shrink-0
                    flex-wrap
                    items-center
                    gap-2
                    border-t
                    border-gray-100
                    pt-4
                    dark:border-gray-800
                    lg:w-auto
                    lg:justify-end
                    lg:border-l
                    lg:border-t-0
                    lg:pl-6
                    lg:pt-0
                ">
                {{ $actions }}
            </div>
        @endisset
    </div>
</header>
