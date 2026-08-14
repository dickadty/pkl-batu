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

<div class="mt-8">

    <iframe
        src="{{ asset('storage/' . $page->file) }}"
        class="w-full h-[900px] rounded-lg border">
    </iframe>

</div>

@endif

@if($page->file)

<div class="mb-4">

    <a
        href="{{ asset('storage/' . $page->file) }}"
        download
        class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-white">

        <i class="ri-download-line"></i>
        Download Dokumen

    </a>

</div>

@endif

<div class="bg-red-100 p-4 rounded mb-4">
    File: {{ $page->file }} <br>
    URL: {{ asset('storage/' . $page->file) }}
</div>

            <div class="prose prose-lg max-w-none">
                {!! $page->content !!}
            </div>
        </div>
    </section>

@endsection