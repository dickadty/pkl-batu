@extends('layouts.public.app')

@section('title', 'FAQ | PPID Kota Batu')

@section('content')
    <div class="min-h-screen border-slate-200">
        <section class="relative -mt-8 overflow-hidden bg-linear-to-r from-green-950 via-green-900 to-emerald-800">

            <div class="absolute inset-0">
                <div class="absolute -left-20 top-0 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute right-0 top-10 h-64 w-64 rounded-full bg-emerald-300/10 blur-3xl"></div>
            </div>

            <div class="relative mx-auto max-w-6xl px-6 pb-28 pt-10 text-center sm:px-8 lg:px-10">
                <span
                    class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-white backdrop-blur-sm">
                    Bantuan PPID
                </span>

                <h1 class="mx-auto mt-4 max-w-3xl text-2xl font-bold leading-tight text-white md:text-3xl">
                    Pertanyaan yang Sering<br>
                    <span class="text-yellow-500">Diajukan</span>
                </h1>

                <p class="mx-auto mt-4 max-w-2xl text-xs leading-6 text-green-100 md:text-sm">
                    Temukan jawaban atas pertanyaan umum seputar layanan dan<br class="hidden sm:inline">
                    keterbukaan informasi publik PPID Kota Batu.
                </p>
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

    <section class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-6">
            <div class="space-y-3">
        @forelse ($faq as $item)
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <details class="group">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 p-4 sm:p-5">
                        <span class="text-sm font-semibold text-slate-900">
                            {{ $item->pertanyaan }}
                        </span>

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 shrink-0 text-emerald-700 transition duration-300 group-open:rotate-180"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </summary>

                    <div class="whitespace-pre-line px-4 pb-4 text-sm leading-6 text-slate-600 sm:px-5 sm:pb-5">
                        {{ $item->jawaban }}
                    </div>
                </details>
            </div>
        @empty
            <div class="rounded-xl border border-slate-200 bg-white p-6 text-center text-sm text-slate-500">
                Belum ada FAQ yang tersedia.
            </div>
        @endforelse
            </div>
        </div>
    </section>
    </div>
@endsection
