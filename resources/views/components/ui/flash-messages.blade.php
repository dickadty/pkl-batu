@php
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi flash message
    |--------------------------------------------------------------------------
    */

    $flashMessages = [
        [
            'type' => 'success',
            'message' => session('success'),
            'title' => 'Berhasil',
            'icon' => 'ri-checkbox-circle-line',
            'wrapper' => '
                border-green-200
                bg-green-50
                text-green-700
                dark:border-green-500/20
                dark:bg-green-500/10
                dark:text-green-400
            ',
            'iconWrapper' => '
                bg-green-100
                dark:bg-green-500/15
            ',
        ],
        [
            'type' => 'error',
            'message' => session('error'),
            'title' => 'Terjadi kesalahan',
            'icon' => 'ri-error-warning-line',
            'wrapper' => '
                border-red-200
                bg-red-50
                text-red-700
                dark:border-red-500/20
                dark:bg-red-500/10
                dark:text-red-400
            ',
            'iconWrapper' => '
                bg-red-100
                dark:bg-red-500/15
            ',
        ],
        [
            'type' => 'warning',
            'message' => session('warning'),
            'title' => 'Perhatian',
            'icon' => 'ri-alert-line',
            'wrapper' => '
                border-yellow-200
                bg-yellow-50
                text-yellow-700
                dark:border-yellow-500/20
                dark:bg-yellow-500/10
                dark:text-yellow-400
            ',
            'iconWrapper' => '
                bg-yellow-100
                dark:bg-yellow-500/15
            ',
        ],
        [
            'type' => 'info',
            'message' => session('info'),
            'title' => 'Informasi',
            'icon' => 'ri-information-line',
            'wrapper' => '
                border-blue-200
                bg-blue-50
                text-blue-700
                dark:border-blue-500/20
                dark:bg-blue-500/10
                dark:text-blue-400
            ',
            'iconWrapper' => '
                bg-blue-100
                dark:bg-blue-500/15
            ',
        ],
    ];
@endphp

<div class="space-y-3">
    {{-- Flash message dari session --}}
    @foreach ($flashMessages as $flash)
        @if (!empty($flash['message']))
            <div
                class="
                    flex
                    items-start
                    gap-3
                    rounded-xl
                    border
                    px-4
                    py-3.5
                    text-sm
                    {{ $flash['wrapper'] }}
                "
                role="alert"
            >
                <div
                    class="
                        flex
                        h-8
                        w-8
                        shrink-0
                        items-center
                        justify-center
                        rounded-full
                        {{ $flash['iconWrapper'] }}
                    "
                >
                    <i
                        class="
                            {{ $flash['icon'] }}
                            text-lg
                        "
                    ></i>
                </div>

                <div class="min-w-0">
                    <p class="font-semibold">
                        {{ $flash['title'] }}
                    </p>

                    <p class="mt-0.5">
                        {{ $flash['message'] }}
                    </p>
                </div>
            </div>
        @endif
    @endforeach

    {{-- Validation error --}}
    @if ($errors->any())
        <div
            class="
                rounded-xl
                border
                border-red-200
                bg-red-50
                px-4
                py-4
                text-sm
                text-red-700
                dark:border-red-500/20
                dark:bg-red-500/10
                dark:text-red-400
            "
            role="alert"
        >
            <div class="flex items-start gap-3">
                <div
                    class="
                        flex
                        h-8
                        w-8
                        shrink-0
                        items-center
                        justify-center
                        rounded-full
                        bg-red-100
                        dark:bg-red-500/15
                    "
                >
                    <i class="ri-error-warning-line text-lg"></i>
                </div>

                <div class="min-w-0">
                    <p class="font-semibold">
                        Data belum dapat diproses
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>