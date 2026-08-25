<section class="relative -mt-px overflow-hidden bg-linear-to-br from-green-950 via-green-900 to-emerald-700 pb-20 pt-24 lg:pb-24 lg:pt-32">

    {{-- Wave --}}
    <div class="absolute -top-px left-0 z-30 w-full overflow-hidden leading-none">
        <svg
            class="block h-24 w-full rotate-180"
            viewBox="0 0 1200 120"
            preserveAspectRatio="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M0,60 C200,120 350,0 600,60 C850,120 1000,0 1200,60 V120 H0 Z"
                class="fill-white"
            />
        </svg>
    </div>

    <div class="relative z-20 mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <span
                    class="inline-flex items-center rounded-full border border-white bg-white/10 px-3 py-1 text-xs font-medium text-white">
                    FAQ
                </span>

                <h2 class="mt-3 text-2xl font-bold text-white md:text-3xl">
                    Pertanyaan yang Sering Diajukan
                </h2>

                <p class="mt-2 text-sm leading-6 text-emerald-100">
                    Temukan jawaban atas pertanyaan umum terkait layanan informasi publik.
                </p>
            </div>

            <a href="{{ route('public.faq.index') }}"
                class="hidden items-center gap-2 rounded-full border border-white bg-white/10 px-4 py-2 text-sm font-medium text-white transition hover:bg-white hover:text-green-950 md:inline-flex">
                Lihat Semua
            </a>
        </div>

        <div class="mx-auto max-w-5xl space-y-3">
            @foreach ($faq->take(4) as $item)
                <div
                    class="overflow-hidden rounded-xl border border-white/20 bg-white shadow-sm">
                    <details class="group">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between gap-4 p-4 sm:p-5">
                            <span class="text-sm font-semibold text-slate-900">
                                {{ $item->pertanyaan }}
                            </span>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-emerald-700 transition duration-300 group-open:rotate-180"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>

                        <div class="px-4 pb-4 text-sm leading-6 text-slate-600 sm:px-5 sm:pb-5">
                            {{ $item->jawaban }}
                        </div>
                    </details>
                </div>
            @endforeach
        </div>

        <div class="mt-8 text-center md:hidden">
            <a href="{{ route('public.faq.index') }}"
                class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-2 text-sm font-medium text-white transition hover:bg-white hover:text-green-950">
                Lihat Semua FAQ
            </a>
        </div>

    </div>

    {{-- Wave bawah tipis --}}
    <div class="absolute bottom-0 left-0 z-10 w-full overflow-hidden leading-0">
        <svg
            class="block h-10 w-full"
            viewBox="0 0 1200 120"
            preserveAspectRatio="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M0,60 C200,100 400,20 600,60 C800,100 1000,20 1200,60 V120 H0 Z"
                class="fill-white"
            />
        </svg>
    </div>
</section>