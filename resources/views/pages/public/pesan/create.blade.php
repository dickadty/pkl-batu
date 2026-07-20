@extends('layouts.public')

@section('title', 'Kirim Pesan | PPID Kota Batu')

@section('content')
    <main class="min-h-screen bg-slate-50">
        <section
            class="
                relative
                overflow-hidden
                border-b
                border-slate-200
                bg-white
            "
        >
            <div
                class="
                    pointer-events-none
                    absolute
                    -left-28
                    -top-36
                    h-96
                    w-96
                    rounded-full
                    bg-blue-100
                    blur-3xl
                "
            ></div>

            <div
                class="
                    relative
                    mx-auto
                    max-w-4xl
                    px-4
                    py-12
                    sm:px-6
                    lg:px-8
                "
            >
                <div
                    class="
                        inline-flex
                        items-center
                        gap-2
                        rounded-full
                        border
                        border-blue-200
                        bg-blue-50
                        px-3
                        py-1.5
                        text-xs
                        font-semibold
                        text-blue-700
                    "
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5l-2 2V5a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2H9z"
                        />
                    </svg>

                    Layanan Pesan Masyarakat
                </div>

                <h1
                    class="
                        mt-4
                        text-3xl
                        font-bold
                        tracking-tight
                        text-slate-900
                        sm:text-4xl
                    "
                >
                    Kirim Pesan
                </h1>

                <p
                    class="
                        mt-3
                        max-w-2xl
                        text-base
                        leading-7
                        text-slate-600
                    "
                >
                    Sampaikan pertanyaan, saran, masukan, atau kendala kepada PPID Kota Batu.
                    Setelah pesan terkirim, sistem akan memberikan link khusus untuk memantau balasan.
                </p>
            </div>
        </section>

        <section
            class="
                mx-auto
                max-w-4xl
                px-4
                py-8
                sm:px-6
                lg:px-8
                lg:py-10
            "
        >
            @if ($errors->any())
                <div
                    class="
                        mb-6
                        rounded-2xl
                        border
                        border-red-200
                        bg-red-50
                        p-5
                        text-red-700
                    "
                    role="alert"
                >
                    <div class="flex items-start gap-3">
                        <svg
                            class="mt-0.5 h-5 w-5 shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v3m0 4h.01M10.29 3.86l-7.4 12.82A2 2 0 004.62 19h14.76a2 2 0 001.73-3l-7.4-12.14a2 2 0 00-3.42 0z"
                            />
                        </svg>

                        <div>
                            <strong class="block text-sm font-semibold">
                                Data belum valid.
                            </strong>

                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div
                class="
                    grid
                    grid-cols-1
                    gap-6
                    lg:grid-cols-[minmax(0,1fr)_280px]
                "
            >
                <section
                    class="
                        overflow-hidden
                        rounded-2xl
                        border
                        border-slate-200
                        bg-white
                        shadow-sm
                    "
                >
                    <div
                        class="
                            border-b
                            border-slate-100
                            px-5
                            py-5
                            sm:px-6
                        "
                    >
                        <h2 class="text-lg font-bold text-slate-900">
                            Form Pesan
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Lengkapi seluruh field wajib sebelum mengirim pesan.
                        </p>
                    </div>

                    <form
                        id="publicMessageForm"
                        action="{{ route('public.pesan.store') }}"
                        method="POST"
                        class="space-y-5 p-5 sm:p-6"
                    >
                        @csrf

                        <div
                            class="
                                grid
                                grid-cols-1
                                gap-5
                                md:grid-cols-2
                            "
                        >
                            <div>
                                <label
                                    for="nama"
                                    class="
                                        mb-1.5
                                        block
                                        text-sm
                                        font-semibold
                                        text-slate-700
                                    "
                                >
                                    Nama Lengkap

                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <span
                                        class="
                                            pointer-events-none
                                            absolute
                                            inset-y-0
                                            left-0
                                            flex
                                            items-center
                                            pl-3.5
                                            text-slate-400
                                        "
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5.121 17.804A9.004 9.004 0 0112 15c2.21 0 4.23.796 5.879 2.117M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                    </span>

                                    <input
                                        type="text"
                                        id="nama"
                                        name="nama"
                                        value="{{ old('nama') }}"
                                        autocomplete="name"
                                        placeholder="Masukkan nama lengkap"
                                        required
                                        class="
                                            h-12
                                            w-full
                                            rounded-xl
                                            border
                                            border-slate-300
                                            bg-white
                                            py-2.5
                                            pl-11
                                            pr-4
                                            text-sm
                                            text-slate-800
                                            outline-none
                                            transition
                                            placeholder:text-slate-400
                                            focus:border-blue-500
                                            focus:ring-4
                                            focus:ring-blue-100
                                            @error('nama')
                                                border-red-500
                                                focus:border-red-500
                                                focus:ring-red-100
                                            @enderror
                                        "
                                    >
                                </div>

                                @error('nama')
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    for="email"
                                    class="
                                        mb-1.5
                                        block
                                        text-sm
                                        font-semibold
                                        text-slate-700
                                    "
                                >
                                    Email

                                    <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <span
                                        class="
                                            pointer-events-none
                                            absolute
                                            inset-y-0
                                            left-0
                                            flex
                                            items-center
                                            pl-3.5
                                            text-slate-400
                                        "
                                    >
                                        <svg
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </span>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        placeholder="contoh@email.com"
                                        required
                                        class="
                                            h-12
                                            w-full
                                            rounded-xl
                                            border
                                            border-slate-300
                                            bg-white
                                            py-2.5
                                            pl-11
                                            pr-4
                                            text-sm
                                            text-slate-800
                                            outline-none
                                            transition
                                            placeholder:text-slate-400
                                            focus:border-blue-500
                                            focus:ring-4
                                            focus:ring-blue-100
                                            @error('email')
                                                border-red-500
                                                focus:border-red-500
                                                focus:ring-red-100
                                            @enderror
                                        "
                                    >
                                </div>

                                @error('email')
                                    <p class="mt-1.5 text-xs text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label
                                for="subjek"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-semibold
                                    text-slate-700
                                "
                            >
                                Subjek

                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                <span
                                    class="
                                        pointer-events-none
                                        absolute
                                        inset-y-0
                                        left-0
                                        flex
                                        items-center
                                        pl-3.5
                                        text-slate-400
                                    "
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7 8h10M7 12h6m-8 8l-2 2v-4a2 2 0 01-2-2V6a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2H7z"
                                        />
                                    </svg>
                                </span>

                                <input
                                    type="text"
                                    id="subjek"
                                    name="subjek"
                                    value="{{ old('subjek') }}"
                                    placeholder="Contoh: Kendala akses informasi publik"
                                    required
                                    class="
                                        h-12
                                        w-full
                                        rounded-xl
                                        border
                                        border-slate-300
                                        bg-white
                                        py-2.5
                                        pl-11
                                        pr-4
                                        text-sm
                                        text-slate-800
                                        outline-none
                                        transition
                                        placeholder:text-slate-400
                                        focus:border-blue-500
                                        focus:ring-4
                                        focus:ring-blue-100
                                        @error('subjek')
                                            border-red-500
                                            focus:border-red-500
                                            focus:ring-red-100
                                        @enderror
                                    "
                                >
                            </div>

                            @error('subjek')
                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="pesan"
                                class="
                                    mb-1.5
                                    block
                                    text-sm
                                    font-semibold
                                    text-slate-700
                                "
                            >
                                Pesan

                                <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                id="pesan"
                                name="pesan"
                                rows="7"
                                placeholder="Jelaskan pertanyaan, saran, atau kendala Anda secara jelas..."
                                required
                                class="
                                    w-full
                                    resize-y
                                    rounded-xl
                                    border
                                    border-slate-300
                                    bg-white
                                    px-4
                                    py-3
                                    text-sm
                                    leading-7
                                    text-slate-800
                                    outline-none
                                    transition
                                    placeholder:text-slate-400
                                    focus:border-blue-500
                                    focus:ring-4
                                    focus:ring-blue-100
                                    @error('pesan')
                                        border-red-500
                                        focus:border-red-500
                                        focus:ring-red-100
                                    @enderror
                                "
                            >{{ old('pesan') }}</textarea>

                            @error('pesan')
                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div
                            class="
                                flex
                                justify-end
                                border-t
                                border-slate-100
                                pt-5
                            "
                        >
                            <button
                                id="publicMessageButton"
                                type="submit"
                                class="
                                    inline-flex
                                    h-12
                                    min-w-[180px]
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-xl
                                    bg-blue-700
                                    px-6
                                    text-sm
                                    font-semibold
                                    text-white
                                    shadow-sm
                                    transition
                                    hover:bg-blue-800
                                    focus:outline-none
                                    focus:ring-4
                                    focus:ring-blue-200
                                    disabled:cursor-not-allowed
                                    disabled:opacity-60
                                "
                            >
                                <svg
                                    id="publicMessageIcon"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                    />
                                </svg>

                                <span id="publicMessageButtonText">
                                    Kirim Pesan
                                </span>
                            </button>
                        </div>
                    </form>
                </section>

                <aside class="space-y-5">
                    <div
                        class="
                            rounded-2xl
                            border
                            border-blue-200
                            bg-blue-50
                            p-5
                        "
                    >
                        <div
                            class="
                                flex
                                h-10
                                w-10
                                items-center
                                justify-center
                                rounded-xl
                                bg-blue-100
                                text-blue-700
                            "
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>

                        <h3 class="mt-4 text-sm font-bold text-blue-900">
                            Setelah pesan terkirim
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-blue-700">
                            Anda akan diarahkan ke halaman percakapan dengan link khusus untuk melihat balasan Admin PPID.
                        </p>
                    </div>

                    <div
                        class="
                            rounded-2xl
                            border
                            border-slate-200
                            bg-white
                            p-5
                            shadow-sm
                        "
                    >
                        <h3 class="text-sm font-bold text-slate-900">
                            Panduan penulisan pesan
                        </h3>

                        <ul
                            class="
                                mt-3
                                space-y-3
                                text-sm
                                leading-6
                                text-slate-600
                            "
                        >
                            <li class="flex items-start gap-2">
                                <svg
                                    class="
                                        mt-1
                                        h-4
                                        w-4
                                        shrink-0
                                        text-green-600
                                    "
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>

                                Gunakan nama dan email yang benar.
                            </li>

                            <li class="flex items-start gap-2">
                                <svg
                                    class="
                                        mt-1
                                        h-4
                                        w-4
                                        shrink-0
                                        text-green-600
                                    "
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>

                                Tuliskan subjek yang singkat dan jelas.
                            </li>

                            <li class="flex items-start gap-2">
                                <svg
                                    class="
                                        mt-1
                                        h-4
                                        w-4
                                        shrink-0
                                        text-green-600
                                    "
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>

                                Hindari mengirim data sensitif yang tidak diperlukan.
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById(
                'publicMessageForm'
            );

            const button = document.getElementById(
                'publicMessageButton'
            );

            const buttonText = document.getElementById(
                'publicMessageButtonText'
            );

            const buttonIcon = document.getElementById(
                'publicMessageIcon'
            );

            if (!form) {
                return;
            }

            form.addEventListener('submit', () => {
                if (button) {
                    button.disabled = true;
                }

                if (buttonText) {
                    buttonText.textContent =
                        'Mengirim...';
                }

                if (buttonIcon) {
                    buttonIcon.outerHTML = `
                        <svg
                            id="publicMessageIcon"
                            class="h-5 w-5 animate-spin"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z"
                            ></path>
                        </svg>
                    `;
                }
            });
        });
    </script>
@endsection