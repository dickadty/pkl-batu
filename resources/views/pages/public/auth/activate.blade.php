@extends('layouts.public.app')

@section('title', 'Aktivasi Akun Warga | PPID Kota Batu')

@section('content')
    <section class="mx-auto max-w-xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-blue-700 px-6 py-6 text-white">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-100">
                    PPID Kota Batu
                </p>

                <h1 class="mt-2 text-2xl font-bold">
                    Aktivasi Akun Warga
                </h1>
            </div>

            <div class="p-6 sm:p-8">
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                        <ul class="list-disc space-y-1 pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (! $isValid)
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 text-amber-800">
                        <h2 class="font-bold">
                            Tautan aktivasi tidak dapat digunakan
                        </h2>

                        <p class="mt-2 text-sm leading-6">
                            Tautan mungkin sudah digunakan, tidak valid, atau telah melewati masa berlaku 24 jam.
                        </p>
                    </div>

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('public.aktivasi.resend.form') }}"
                            class="inline-flex h-11 items-center justify-center rounded-lg bg-blue-700 px-5 text-sm font-semibold text-white hover:bg-blue-800">
                            Kirim Ulang Aktivasi
                        </a>

                        <a href="{{ route('login') }}"
                            class="inline-flex h-11 items-center justify-center rounded-lg border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Masuk ke Akun
                        </a>
                    </div>
                @else
                    <p class="text-sm leading-6 text-slate-600">
                        Buat password untuk mengaktifkan akun dengan username:
                    </p>

                    <div class="mt-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 font-semibold text-blue-800 break-all">
                        {{ $email }}
                    </div>

                    <form action="{{ route('public.aktivasi.store', ['token' => $token]) }}" method="POST"
                        class="mt-6 space-y-5">
                        @csrf

                        <input type="hidden" name="email" value="{{ $email }}">

                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Password Baru <span class="text-red-500">*</span>
                            </label>

                            <input id="password" name="password" type="password" required autocomplete="new-password"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">

                            <p class="mt-1.5 text-xs text-slate-500">
                                Minimal 8 karakter dan harus mengandung huruf serta angka.
                            </p>
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="mb-1.5 block text-sm font-semibold text-slate-700">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>

                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                autocomplete="new-password"
                                class="h-11 w-full rounded-lg border border-slate-300 px-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>

                        <button type="submit"
                            class="inline-flex h-11 w-full items-center justify-center rounded-lg bg-blue-700 px-5 text-sm font-semibold text-white hover:bg-blue-800">
                            Aktifkan Akun
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </section>
@endsection
