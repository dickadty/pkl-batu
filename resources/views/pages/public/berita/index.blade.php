@extends('layouts.public.app')

@section('title', 'Berita | PPID Kota Batu')

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
                    Publikasi PPID
                </span>

                <h1 class="mx-auto mt-4 max-w-3xl text-2xl font-bold leading-tight text-white md:text-3xl">
                    Berita PPID<br>
                    <span class="text-yellow-500">Kota Batu</span>
                </h1>

                <p class="mx-auto mt-4 max-w-2xl text-xs leading-6 text-green-100 md:text-sm">
                    Simak informasi terbaru seputar layanan, kegiatan, dan publikasi<br class="hidden sm:inline">
                    PPID Kota Batu secara aktual dan terpercaya.
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
            <div>
            <div class="grid gap-4 md:grid-cols-3">
        @foreach($berita as $item)
            @include('components.public.berita.card', [
                'item' => $item
            ])
        @endforeach
                </div>

                <div class="mt-8">
                    {{ $berita->links() }}
                </div>
            </div>
        </section>
     </div>
@endsection
