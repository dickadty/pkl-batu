@extends('layouts.public.app')

@section('title', $page->judul)

@section('content')

<section class="py-16">
    <div class="max-w-6xl mx-auto px-6">

        <h1 class="text-4xl font-bold mb-8">
            {{ $page->judul }}
        </h1>

        @if($page->gambar)
            <img
                src="{{ asset('storage/' . $page->gambar) }}"
                alt="{{ $page->judul }}"
                class="w-full max-h-[500px] object-cover rounded-2xl shadow-lg mb-10">
        @endif

        <div class="prose prose-lg max-w-none">
            {!! $page->content !!}
        </div>

    </div>
</section>

@endsection