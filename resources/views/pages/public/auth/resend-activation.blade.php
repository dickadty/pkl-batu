@extends('layouts.public.app')

@section('title', 'Kirim Ulang Aktivasi | PPID Kota Batu')

@section('content')
    <section class="mx-auto max-w-xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-6">
                <h1 class="text-2xl font-bold text-slate-900">
                    Kirim Ulang Aktivasi Akun
                </h1>

                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Masukkan email yang digunakan pada permohonan pertama. Sistem akan mengirim tautan baru apabila akun tersebut belum aktif.
                </p>
            </div>

            <div class="p-6 sm:p-8">
                @if (session('warning'))
                    <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-800">
                        {{ session('warning') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm leading-6 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                        <ul class="list-disc space-y-1 pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('public.aktivasi.resend') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Email Akun <span class="text-red-500">*</span>
                        </label>

                        <input id="email" name="email" type="email" value="{{ $prefillEmail }}" required
                            maxlength="100" autocomplete="email"
                            class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    <button type="submit"
                        class="inline-flex h-11 w-full items-center justify-center rounded-lg bg-blue-700 px-5 text-sm font-semibold text-white hover:bg-blue-800">
                        Kirim Tautan Aktivasi
                    </button>
                </form>

                <div class="mt-6 border-t border-slate-200 pt-6 text-center text-sm text-slate-600">
                    Akun sudah aktif?
                    <a href="{{ route('login') }}" class="font-semibold text-blue-700 hover:underline">
                        Masuk di sini
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
