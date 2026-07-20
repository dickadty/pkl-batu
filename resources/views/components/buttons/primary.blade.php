@props([
    'href' => null,
    'type' => 'button',
    'icon' => null,
])

@php
    $classes = '
        inline-flex
        items-center
        justify-center
        gap-2
        rounded-lg
        bg-blue-600
        px-4
        py-3
        text-sm
        font-semibold
        text-white
        shadow-sm
        transition
        hover:bg-blue-700
        focus:outline-none
        focus:ring-4
        focus:ring-blue-200
        disabled:cursor-not-allowed
        disabled:opacity-50
        dark:bg-blue-500
        dark:hover:bg-blue-600
        dark:focus:ring-blue-900/50
    ';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        @if ($icon)
            <i class="{{ $icon }} text-lg"></i>
        @endif

        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        @if ($icon)
            <i class="{{ $icon }} text-lg"></i>
        @endif

        {{ $slot }}
    </button>
@endif
