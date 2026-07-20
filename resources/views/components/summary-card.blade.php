@props([
    'title',
    'value' => 0,
    'icon' => 'ri-notification-3-line',
    'url' => '#',
    'active' => false,
    'tone' => 'brand',
])

@php
    $toneClasses = match ($tone) {
        'red' => [
            'border' => $active
                ? 'border-red-500'
                : 'border-gray-200 hover:border-red-300 dark:border-gray-800 dark:hover:border-red-500',
            'background' => $active ? 'bg-red-50 dark:bg-red-500/10' : 'bg-white dark:bg-white/[0.03]',
            'iconBackground' => 'bg-red-50 dark:bg-red-500/15',
            'iconText' => 'text-red-600 dark:text-red-400',
        ],

        'green' => [
            'border' => $active
                ? 'border-green-500'
                : 'border-gray-200 hover:border-green-300 dark:border-gray-800 dark:hover:border-green-500',
            'background' => $active ? 'bg-green-50 dark:bg-green-500/10' : 'bg-white dark:bg-white/[0.03]',
            'iconBackground' => 'bg-green-50 dark:bg-green-500/15',
            'iconText' => 'text-green-600 dark:text-green-400',
        ],

        default => [
            'border' => $active
                ? 'border-brand-500'
                : 'border-gray-200 hover:border-brand-300 dark:border-gray-800 dark:hover:border-brand-500',
            'background' => $active ? 'bg-brand-50 dark:bg-brand-500/10' : 'bg-white dark:bg-white/[0.03]',
            'iconBackground' => 'bg-brand-50 dark:bg-brand-500/15',
            'iconText' => 'text-brand-600 dark:text-brand-400',
        ],
    };
@endphp

<a href="{{ $url }}"
    {{ $attributes->class([
        'group rounded-2xl border p-5 shadow-theme-xs transition',
        $toneClasses['border'],
        $toneClasses['background'],
    ]) }}>
    <div class="
            flex
            items-center
            justify-between
            gap-4
        ">
        <div>
            <p
                class="
                    text-sm
                    font-medium
                    text-gray-500
                    dark:text-gray-400
                ">
                {{ $title }}
            </p>

            <p
                class="
                    mt-2
                    text-3xl
                    font-bold
                    text-gray-800
                    dark:text-white/90
                ">
                {{ number_format((int) $value) }}
            </p>
        </div>

        <div
            class="
                flex
                h-12
                w-12
                shrink-0
                items-center
                justify-center
                rounded-xl
                transition
                {{ $toneClasses['iconBackground'] }}
                {{ $toneClasses['iconText'] }}
            ">
            <i class="{{ $icon }} text-2xl"></i>
        </div>
    </div>
</a>
