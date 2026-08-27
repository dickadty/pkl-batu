@extends('layouts.public.app')

@section('title', $page->judul)

@section('content')

    <section class="py-16">
        <div class="max-w-6xl mx-auto px-6">

            <h1 class="text-4xl font-bold mb-8">
                {{ $page->judul }}
            </h1>

            @if($page->gambar)
                <img src="{{ asset('storage/' . $page->gambar) }}" alt="{{ $page->judul }}"
                    class="w-full max-h-[500px] object-cover rounded-2xl shadow-lg mb-10">
            @endif

            @if($page->file)
                @php
                    $fileUrl = route('public.pages.file', $page->slug);
                    $isPdf = strtolower(pathinfo($page->file, PATHINFO_EXTENSION)) === 'pdf';
                @endphp

                @if($isPdf)
                    <div class="mt-8 overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                        <iframe
                            src="{{ $fileUrl }}"
                            title="{{ $page->judul }}"
                            class="h-[900px] w-full"
                            loading="lazy">
                            <p class="p-6 text-slate-600">
                                Browser tidak dapat menampilkan PDF.
                                <a href="{{ $fileUrl }}" target="_blank" rel="noopener"
                                    class="font-medium text-emerald-700 underline">
                                    Buka PDF
                                </a>
                            </p>
                        </iframe>
                    </div>
                @endif

                <div class="mt-4">
                    <a href="{{ $fileUrl }}" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700">
                        <i class="ri-file-pdf-line"></i>
                        {{ $isPdf ? 'Buka PDF' : 'Buka Dokumen' }}
                    </a>
                </div>
            @endif

            <div class="prose prose-lg max-w-none">
                {!! $page->content !!}
            </div>
        </div>
    </section>

@endsection