@extends('layouts.public.app')

@section('title', 'Login | PPID Kota Batu')

@section('content')
    <x-public.sections.page-hero
        eyebrow="Sistem Login Terpadu"
        title="Login"
        highlight="PPID Kota Batu"
        description="Gunakan email untuk login sebagai warga atau username untuk login sebagai administrator. Sistem akan menentukan jenis akun secara otomatis."
    />

    <section
        class="
            mx-auto
            max-w-6xl
            px-4
            py-12
            sm:px-6
            lg:px-8
        ">
        <div
            class="
                grid
                grid-cols-1
                gap-8
                lg:grid-cols-[minmax(0,1fr)_320px]
            ">
            <div
                class="
                    overflow-hidden
                    rounded-2xl
                    border
                    border-slate-200
                    bg-white
                    shadow-sm
                ">
                <div
                    class="
                        border-b
                        border-slate-200
                        px-6
                        py-5
                    ">
                    <h2
                        class="
                            text-xl
                            font-bold
                            text-slate-900
                        ">
                        Masuk ke Sistem
                    </h2>

                    <p
                        class="
                            mt-1
                            text-sm
                            text-slate-500
                        ">
                        Masukkan email warga atau username administrator.
                    </p>
                </div>

                <div class="p-6">
                    @if (session('success'))
                        <div
                            class="
                                mb-5
                                rounded-xl
                                border
                                border-green-200
                                bg-green-50
                                p-4
                                text-sm
                                leading-6
                                text-green-800
                            ">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('status'))
                        <div
                            class="
                                mb-5
                                rounded-xl
                                border
                                border-green-200
                                bg-green-50
                                p-4
                                text-sm
                                leading-6
                                text-green-800
                            ">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('warning'))
                        <div
                            class="
                                mb-5
                                rounded-xl
                                border
                                border-amber-200
                                bg-amber-50
                                p-4
                                text-sm
                                leading-6
                                text-amber-900
                            ">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div
                            class="
                                mb-5
                                rounded-xl
                                border
                                border-red-200
                                bg-red-50
                                p-4
                                text-red-700
                            ">
                            <p class="text-sm font-semibold">
                                Login belum berhasil
                            </p>

                            <ul
                                class="
                                    mt-2
                                    list-disc
                                    space-y-1
                                    pl-5
                                    text-sm
                                    leading-6
                                ">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label for="identifier"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-semibold
                                    text-slate-700
                                ">
                                Email Warga atau Username Admin

                                <span class="text-red-500">*</span>
                            </label>

                            <input id="identifier" name="identifier" type="text" value="{{ old('identifier') }}" required
                                autofocus maxlength="100" autocomplete="username"
                                placeholder="Email warga atau username admin"
                                class="
                                    h-11
                                    w-full
                                    rounded-lg
                                    border
                                    border-slate-300
                                    bg-white
                                    px-3
                                    text-sm
                                    text-slate-900
                                    outline-none
                                    transition
                                    placeholder:text-slate-400
                                    focus:border-emerald-600
                                    focus:ring-2
                                    focus:ring-emerald-500/20
                                    @error('identifier')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-500/20
                                    @enderror
                                ">

                            <p
                                class="
                                    mt-1.5
                                    text-xs
                                    leading-5
                                    text-slate-500
                                ">
                                Warga menggunakan alamat email. Administrator menggunakan username.
                            </p>
                        </div>

                        <div>
                            <label for="password"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-semibold
                                    text-slate-700
                                ">
                                Password

                                <span class="text-red-500">*</span>
                            </label>

                            <div x-data="{ visible: false }" class="relative">
                                <input id="password" name="password" :type="visible ? 'text' : 'password'" required
                                    autocomplete="current-password" placeholder="Masukkan password"
                                    class="
                                        h-11
                                        w-full
                                        rounded-lg
                                        border
                                        border-slate-300
                                        bg-white
                                        px-3
                                        pr-12
                                        text-sm
                                        text-slate-900
                                        outline-none
                                        transition
                                        placeholder:text-slate-400
                                        focus:border-emerald-600
                                        focus:ring-2
                                        focus:ring-emerald-500/20
                                        @error('password')
                                            border-red-500
                                            focus:border-red-500
                                            focus:ring-red-500/20
                                        @enderror
                                    ">

                                <button type="button" @click="visible = !visible"
                                    class="
                                        absolute
                                        inset-y-0
                                        right-0
                                        inline-flex
                                        w-11
                                        items-center
                                        justify-center
                                        text-slate-500
                                        transition
                                        hover:text-emerald-700
                                    "
                                    :aria-label="visible ? 'Sembunyikan password' : 'Tampilkan password'">
                                    <svg x-show="!visible" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c-1.5 4-4.5 6-9 6s-7.5-2-9-6c1.5-4 4.5-6 9-6s7.5 2 9 6z" />
                                    </svg>

                                    <svg x-cloak x-show="visible" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3l18 18M10.6 10.6A2 2 0 0012 14a2 2 0 001.4-.6M9.9 4.2A10.7 10.7 0 0112 4c4.5 0 7.5 2 9 6a10.5 10.5 0 01-2.1 3.6M6.6 6.6A10.6 10.6 0 003 10c1.5 4 4.5 6 9 6a10.9 10.9 0 004-.7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="
                                inline-flex
                                h-11
                                w-full
                                items-center
                                justify-center
                                gap-2
                                rounded-lg
                                bg-emerald-800
                                px-5
                                text-sm
                                font-semibold
                                text-white
                                shadow-sm
                                transition
                                hover:bg-emerald-900
                                focus:outline-none
                                focus:ring-3
                                focus:ring-emerald-500/30
                            ">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5m5 5H3" />
                            </svg>

                            Masuk
                        </button>
                    </form>
                </div>
            </div>

            <aside class="space-y-5">
                <section
                    class="
                        rounded-2xl
                        border
                        border-emerald-200
                        bg-emerald-50
                        p-5
                    ">
                    <h2
                        class="
                            text-base
                            font-bold
                            text-emerald-950
                        ">
                        Login Warga
                    </h2>

                    <p
                        class="
                            mt-2
                            text-sm
                            leading-6
                            text-emerald-800
                        ">
                        Gunakan alamat email yang didaftarkan ketika mengajukan permohonan informasi pertama.
                    </p>

                    <a href="{{ route('public.aktivasi.resend.form') }}"
                        class="
                            mt-4
                            inline-flex
                            h-10
                            w-full
                            items-center
                            justify-center
                            rounded-lg
                            border
                            border-emerald-300
                            bg-white
                            px-4
                            text-sm
                            font-semibold
                            text-emerald-800
                            transition
                            hover:bg-emerald-100
                        ">
                        Kirim Ulang Aktivasi
                    </a>
                </section>

                <section
                    class="
                        rounded-2xl
                        border
                        border-slate-200
                        bg-white
                        p-5
                    ">
                    <h2
                        class="
                            text-base
                            font-bold
                            text-slate-900
                        ">
                        Belum Memiliki Akun?
                    </h2>

                    <p
                        class="
                            mt-2
                            text-sm
                            leading-6
                            text-slate-600
                        ">
                        Akun warga dibuat otomatis ketika permohonan pertama diajukan tanpa login.
                    </p>

                    <a href="{{ route('public.permohonan.create') }}"
                        class="
                            mt-4
                            inline-flex
                            h-10
                            w-full
                            items-center
                            justify-center
                            rounded-lg
                            bg-emerald-950
                            px-4
                            text-sm
                            font-semibold
                            text-white
                            transition
                            hover:bg-emerald-900
                        ">
                        Ajukan Permohonan Pertama
                    </a>
                </section>

                <section
                    class="
                        rounded-2xl
                        border
                        border-slate-200
                        bg-slate-50
                        p-5
                    ">
                    <h2
                        class="
                            text-base
                            font-bold
                            text-slate-900
                        ">
                        Login Administrator
                    </h2>

                    <p
                        class="
                            mt-2
                            text-sm
                            leading-6
                            text-slate-600
                        ">
                        Administrator menggunakan username dan password akun internal PPID.
                    </p>
                </section>
            </aside>
        </div>
    </section>
@endsection
