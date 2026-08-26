@props([
    'eyebrow',
    'title',
    'highlight' => null,
    'description',
    'actionUrl' => null,
    'actionLabel' => null,
    'actionIcon' => null,
])

<section class="relative -mt-8 overflow-hidden bg-linear-to-r from-green-950 via-green-900 to-emerald-800">
    <div class="absolute inset-0">
        <div class="absolute -left-20 top-0 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute right-0 top-10 h-64 w-64 rounded-full bg-emerald-300/10 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-6xl px-6 pb-28 pt-16 text-center sm:px-8 lg:px-10">
        <span
            class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-white backdrop-blur-sm">
            {{ $eyebrow }}
        </span>

        <h1 class="mx-auto mt-4 max-w-3xl text-2xl font-bold leading-tight text-white md:text-3xl">
            {{ $title }}@if ($highlight)<br><span class="text-yellow-500">{{ $highlight }}</span>@endif
        </h1>

        <p class="mx-auto mt-4 max-w-2xl text-xs leading-6 text-green-100 md:text-sm">
            {{ $description }}
        </p>

        @if ($actionUrl && $actionLabel)
            <a href="{{ $actionUrl }}"
                class="mt-6 inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-white px-5 text-sm font-semibold text-green-950 shadow-sm transition hover:bg-green-50 focus:outline-none focus:ring-4 focus:ring-white/30">
                @if ($actionIcon)
                    <i class="{{ $actionIcon }} text-lg"></i>
                @endif
                {{ $actionLabel }}
            </a>
        @endif
    </div>

    <div class="absolute bottom-0 left-0 z-20 w-full overflow-hidden leading-0">
        <svg
            class="relative block h-20 w-full"
            viewBox="0 0 1200 120"
            preserveAspectRatio="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M0,60 C200,120 350,0 600,60 C850,120 1000,0 1200,60 V120 H0 Z"
                class="fill-white"
            ></path>
        </svg>
    </div>
</section>
