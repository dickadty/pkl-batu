@props(['rowId', 'label' => 'Pilih data'])

@php
    /*
    |--------------------------------------------------------------------------
    | Normalisasi ID
    |--------------------------------------------------------------------------
    */

    $normalizedRowId = (string) $rowId;
@endphp

<div class="flex items-center gap-3">
    <button type="button" role="checkbox" x-init="registerRow(
        @js($normalizedRowId)
    )"
        @click.prevent.stop="
            handleRowSelect(
                @js($normalizedRowId)
            )
        "
        :aria-checked="isRowSelected(
                @js($normalizedRowId)
            ) ?
            'true' :
            'false'"
        class="
            flex
            h-5
            w-5
            cursor-pointer
            items-center
            justify-center
            rounded-md
            border-[1.25px]
            transition
            focus:outline-none
            focus:ring-4
            focus:ring-blue-100
            dark:focus:ring-blue-900/30
        "
        :class="isRowSelected(
                @js($normalizedRowId)
            ) ?
            'border-blue-500 bg-blue-500 dark:border-blue-500 dark:bg-blue-500' :
            'border-gray-300 bg-white dark:border-gray-700 dark:bg-transparent'"
        aria-label="{{ $label }}">
        <svg x-cloak
            x-show="
                isRowSelected(
                    @js($normalizedRowId)
                )
            "
            width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
            <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white" stroke-width="1.94437" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
    </button>
</div>
