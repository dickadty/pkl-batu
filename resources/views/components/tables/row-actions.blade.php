@props([
    'viewUrl' => null,
    'viewMethod' => 'GET',
    'viewConfirmation' => null,

    'downloadUrl' => null,
    'editUrl' => null,
    'verifyUrl' => null,
    'deleteUrl' => null,

    'viewLabel' => 'Lihat detail',
    'downloadLabel' => 'Unduh data',
    'editLabel' => 'Edit data',
    'verifyLabel' => 'Verifikasi data',
    'deleteLabel' => 'Hapus data',

    'verifyConfirmation' => 'Apakah Anda yakin ingin memverifikasi data ini?',
    'deleteConfirmation' => 'Apakah Anda yakin ingin menghapus data ini?',
])

@php
    $availableActions = collect([$viewUrl, $downloadUrl, $editUrl, $verifyUrl, $deleteUrl])
        ->filter()
        ->count();

    $normalizedViewMethod = strtoupper(trim((string) $viewMethod));

    $viewUsesLink = $normalizedViewMethod === 'GET';

    $viewFormMethod = $normalizedViewMethod === 'GET' ? 'GET' : 'POST';
@endphp

@if ($availableActions > 0)
    <div
        class="
            inline-flex
            items-center
            justify-center
            divide-x
            divide-gray-200
            overflow-hidden
            rounded-xl
            border
            border-gray-200
            bg-white
            p-1
            shadow-theme-xs
            dark:divide-gray-700
            dark:border-gray-700
            dark:bg-gray-900
        ">
        @if ($viewUrl)
            <div class="px-0.5">
                @if ($viewUsesLink)
                    <a href="{{ $viewUrl }}" title="{{ $viewLabel }}" aria-label="{{ $viewLabel }}"
                        class="
                            group
                            inline-flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-lg
                            text-gray-500
                            transition
                            duration-200
                            hover:bg-gray-100
                            hover:text-gray-800
                            focus:outline-none
                            focus:ring-2
                            focus:ring-gray-500/20
                            dark:text-gray-400
                            dark:hover:bg-gray-800
                            dark:hover:text-white
                        ">
                        <svg class="
                                h-5
                                w-5
                                transition-transform
                                duration-200
                                group-hover:scale-110
                            "
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                @else
                    <form action="{{ $viewUrl }}" method="{{ $viewFormMethod }}" class="m-0 p-0"
                        @if ($viewConfirmation) onsubmit="return confirm(@js($viewConfirmation))" @endif>
                        @csrf

                        @if ($normalizedViewMethod !== 'POST')
                            @method($normalizedViewMethod)
                        @endif

                        <button type="submit" title="{{ $viewLabel }}" aria-label="{{ $viewLabel }}"
                            class="
                                group
                                inline-flex
                                h-9
                                w-9
                                items-center
                                justify-center
                                rounded-lg
                                text-gray-500
                                transition
                                duration-200
                                hover:bg-gray-100
                                hover:text-gray-800
                                focus:outline-none
                                focus:ring-2
                                focus:ring-gray-500/20
                                dark:text-gray-400
                                dark:hover:bg-gray-800
                                dark:hover:text-white
                            ">
                            <svg class="
                                    h-5
                                    w-5
                                    transition-transform
                                    duration-200
                                    group-hover:scale-110
                                "
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </form>
                @endif
            </div>
        @endif

        @if ($downloadUrl)
            <div class="px-0.5">
                <a href="{{ $downloadUrl }}" title="{{ $downloadLabel }}" aria-label="{{ $downloadLabel }}"
                    class="
                        group
                        inline-flex
                        h-9
                        w-9
                        items-center
                        justify-center
                        rounded-lg
                        text-gray-500
                        transition
                        duration-200
                        hover:bg-green-50
                        hover:text-green-600
                        focus:outline-none
                        focus:ring-2
                        focus:ring-green-500/20
                        dark:text-gray-400
                        dark:hover:bg-green-500/15
                        dark:hover:text-green-400
                    ">
                    <svg class="
                            h-5
                            w-5
                            transition-transform
                            duration-200
                            group-hover:scale-110
                        "
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v12m0 0l-4-4m4 4 4-4M5 21h14a2 2 0 002-2v-3M3 16v3a2 2 0 002 2" />
                    </svg>
                </a>
            </div>
        @endif

        @if ($editUrl)
            <div class="px-0.5">
                <a href="{{ $editUrl }}" title="{{ $editLabel }}" aria-label="{{ $editLabel }}"
                    class="
                        group
                        inline-flex
                        h-9
                        w-9
                        items-center
                        justify-center
                        rounded-lg
                        text-gray-500
                        transition
                        duration-200
                        hover:bg-blue-50
                        hover:text-blue-600
                        focus:outline-none
                        focus:ring-2
                        focus:ring-blue-500/20
                        dark:text-gray-400
                        dark:hover:bg-blue-500/15
                        dark:hover:text-blue-400
                    ">
                    <svg class="
                            h-5
                            w-5
                            transition-transform
                            duration-200
                            group-hover:scale-110
                        "
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                </a>
            </div>
        @endif

        @if ($verifyUrl)
            <form action="{{ $verifyUrl }}" method="POST" class="m-0 px-0.5 py-0"
                onsubmit="return confirm(@js($verifyConfirmation))">
                @csrf
                @method('PATCH')

                <button type="submit" title="{{ $verifyLabel }}" aria-label="{{ $verifyLabel }}"
                    class="
                        group
                        inline-flex
                        h-9
                        w-9
                        items-center
                        justify-center
                        rounded-lg
                        text-gray-500
                        transition
                        duration-200
                        hover:bg-emerald-50
                        hover:text-emerald-600
                        focus:outline-none
                        focus:ring-2
                        focus:ring-emerald-500/20
                        dark:text-gray-400
                        dark:hover:bg-emerald-500/15
                        dark:hover:text-emerald-400
                    ">
                    <svg class="
                            h-5
                            w-5
                            transition-transform
                            duration-200
                            group-hover:scale-110
                        "
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </form>
        @endif

        @if ($deleteUrl)
            <form action="{{ $deleteUrl }}" method="POST" class="m-0 px-0.5 py-0"
                onsubmit="return confirm(@js($deleteConfirmation))">
                @csrf
                @method('DELETE')

                <button type="submit" title="{{ $deleteLabel }}" aria-label="{{ $deleteLabel }}"
                    class="
                        group
                        inline-flex
                        h-9
                        w-9
                        items-center
                        justify-center
                        rounded-lg
                        text-gray-500
                        transition
                        duration-200
                        hover:bg-red-50
                        hover:text-red-600
                        focus:outline-none
                        focus:ring-2
                        focus:ring-red-500/20
                        dark:text-gray-400
                        dark:hover:bg-red-500/15
                        dark:hover:text-red-400
                    ">
                    <svg class="
                            h-5
                            w-5
                            transition-transform
                            duration-200
                            group-hover:scale-110
                        "
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11v6m4-6v6" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        @endif
    </div>
@endif
