@extends('layouts.public')

@section('title', 'FAQ | PPID Kota Batu')

@section('content')
    <section class="bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h1 class="text-3xl font-bold text-slate-900">
                FAQ
            </h1>

            <p class="mt-3 text-slate-600">
                Pertanyaan yang sering diajukan terkait layanan PPID Kota Batu.
            </p>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @forelse ($faq as $item)
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm mb-4 overflow-hidden">
                <details class="group">
                    <summary class="cursor-pointer list-none p-5 flex items-center justify-between">
                        <span class="font-semibold text-slate-900">
                            {{ $item->pertanyaan }}
                        </span>

                        <span class="text-blue-700 group-open:rotate-45 transition">
                            +
                        </span>
                    </summary>

                    <div class="px-5 pb-5 text-slate-600 leading-relaxed whitespace-pre-line">
                        {{ $item->jawaban }}
                    </div>
                </details>
            </div>
        @empty
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 text-center text-slate-500">
                Belum ada FAQ yang tersedia.
            </div>
        @endforelse
    </section>
@endsection
