@props([
    'action',
    'method' => 'POST',
    'faq' => null,
    'title' => 'Informasi FAQ',
    'description' => 'Lengkapi pertanyaan, jawaban, dan status publikasi FAQ.',
    'submitLabel' => 'Simpan FAQ',
    'cancelUrl' => null,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi form
    |--------------------------------------------------------------------------
    */

    $formMethod = strtoupper($method);

    $cancelUrl = $cancelUrl ?? route('admin.faq.index');

    /*
    |--------------------------------------------------------------------------
    | Nilai field
    |--------------------------------------------------------------------------
    */

    $questionValue = old('pertanyaan', data_get($faq, 'pertanyaan', ''));

    $answerValue = old('jawaban', data_get($faq, 'jawaban', ''));

    $statusValue = (int) old('status', data_get($faq, 'status', 1));

    $questionLength = mb_strlen((string) $questionValue);

    $answerLength = mb_strlen(trim(strip_tags((string) $answerValue)));
@endphp

<x-common.component-card :title="$title">
    <div class="space-y-6">
        @if ($description)
            <div
                class="
                    flex
                    items-start
                    gap-3
                    rounded-xl
                    border
                    border-blue-100
                    bg-blue-50/70
                    px-4
                    py-3.5
                    dark:border-blue-500/20
                    dark:bg-blue-500/10
                ">
                <div
                    class="
                        flex
                        h-9
                        w-9
                        shrink-0
                        items-center
                        justify-center
                        rounded-full
                        bg-blue-100
                        text-blue-600
                        dark:bg-blue-500/15
                        dark:text-blue-400
                    ">
                    <i class="ri-information-line text-lg"></i>
                </div>

                <div class="min-w-0">
                    <p
                        class="
                            text-sm
                            font-semibold
                            text-gray-800
                            dark:text-white/90
                        ">
                        Data pertanyaan dan jawaban
                    </p>

                    <p
                        class="
                            mt-0.5
                            text-sm
                            leading-6
                            text-gray-600
                            dark:text-gray-400
                        ">
                        {{ $description }}
                    </p>
                </div>
            </div>
        @endif

        <form action="{{ $action }}" method="POST" x-data="{
            submitting: false,
            statusEnabled: @js($statusValue === 1),
            questionLength: @js($questionLength),
            answerLength: @js($answerLength),
        
            updateQuestionLength(event) {
                this.questionLength =
                    event.target.value.length;
            },
        
            updateAnswerLength(event) {
                this.answerLength =
                    event.target.value.length;
            }
        }" @submit="submitting = true"
            class="space-y-8">
            @csrf

            @if (!in_array($formMethod, ['GET', 'POST'], true))
                @method($formMethod)
            @endif

            <input type="hidden" name="status" :value="statusEnabled ? 1 : 0">

            {{-- ========================================================
                ISI FAQ
            ========================================================= --}}

            <section class="space-y-5">
                <div
                    class="
                        flex
                        items-center
                        gap-3
                        border-b
                        border-gray-100
                        pb-3
                        dark:border-gray-800
                    ">
                    <div
                        class="
                            flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-lg
                            bg-blue-50
                            text-blue-600
                            dark:bg-blue-500/15
                            dark:text-blue-400
                        ">
                        <i class="ri-question-answer-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Pertanyaan dan Jawaban
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Tuliskan pertanyaan yang jelas dan jawaban yang informatif.
                        </p>
                    </div>
                </div>

                {{-- Pertanyaan --}}
                <div>
                    <label for="pertanyaan"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-400
                        ">
                        Pertanyaan

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
                                text-gray-400
                            ">
                            <i class="ri-question-line text-lg"></i>
                        </span>

                        <input id="pertanyaan" type="text" name="pertanyaan" value="{{ $questionValue }}"
                            placeholder="Masukkan pertanyaan yang sering diajukan" autocomplete="off" required autofocus
                            maxlength="255" @input="updateQuestionLength($event)"
                            class="
                                dark:bg-dark-900
                                shadow-theme-xs
                                focus:border-brand-300
                                focus:ring-brand-500/10
                                dark:focus:border-brand-800
                                h-11
                                w-full
                                rounded-lg
                                border
                                border-gray-300
                                bg-transparent
                                py-2.5
                                pl-11
                                pr-4
                                text-sm
                                text-gray-800
                                placeholder:text-gray-400
                                focus:ring-3
                                focus:outline-hidden
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-white/90
                                dark:placeholder:text-white/30
                                @error('pertanyaan')
                                    border-red-500
                                    focus:border-red-500
                                    focus:ring-red-500/10
                                    dark:border-red-500
                                @enderror
                            ">
                    </div>

                    <div
                        class="
                            mt-1.5
                            flex
                            items-start
                            justify-between
                            gap-3
                        ">
                        @error('pertanyaan')
                            <p
                                class="
                                    flex
                                    items-center
                                    gap-1.5
                                    text-xs
                                    text-red-500
                                ">
                                <i class="ri-error-warning-line"></i>

                                <span>{{ $message }}</span>
                            </p>
                        @else
                            <p
                                class="
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Gunakan kalimat pertanyaan yang singkat dan spesifik.
                            </p>
                        @enderror

                        <span
                            class="
                                shrink-0
                                text-xs
                                text-gray-400
                                dark:text-gray-500
                            ">
                            <span x-text="questionLength"></span>/255
                        </span>
                    </div>
                </div>

                {{-- Jawaban --}}
                <div>
                    <label for="jawaban"
                        class="
                            mb-1.5
                            block
                            text-sm
                            font-medium
                            text-gray-700
                            dark:text-gray-400
                        ">
                        Jawaban

                        <span class="text-red-500">*</span>
                    </label>

                    <textarea id="jawaban" name="jawaban" rows="8" placeholder="Masukkan jawaban FAQ secara jelas dan lengkap"
                        required @input="updateAnswerLength($event)"
                        class="
                            dark:bg-dark-900
                            shadow-theme-xs
                            focus:border-brand-300
                            focus:ring-brand-500/10
                            dark:focus:border-brand-800
                            w-full
                            resize-y
                            rounded-lg
                            border
                            border-gray-300
                            bg-transparent
                            px-4
                            py-3
                            text-sm
                            leading-6
                            text-gray-800
                            placeholder:text-gray-400
                            focus:ring-3
                            focus:outline-hidden
                            dark:border-gray-700
                            dark:bg-gray-900
                            dark:text-white/90
                            dark:placeholder:text-white/30
                            @error('jawaban')
                                border-red-500
                                focus:border-red-500
                                focus:ring-red-500/10
                                dark:border-red-500
                            @enderror
                        ">{{ $answerValue }}</textarea>

                    <div
                        class="
                            mt-1.5
                            flex
                            items-start
                            justify-between
                            gap-3
                        ">
                        @error('jawaban')
                            <p
                                class="
                                    flex
                                    items-center
                                    gap-1.5
                                    text-xs
                                    text-red-500
                                ">
                                <i class="ri-error-warning-line"></i>

                                <span>{{ $message }}</span>
                            </p>
                        @else
                            <p
                                class="
                                    text-xs
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                Jelaskan langkah atau informasi yang dibutuhkan pengguna.
                            </p>
                        @enderror

                        <span
                            class="
                                shrink-0
                                text-xs
                                text-gray-400
                                dark:text-gray-500
                            ">
                            <span x-text="answerLength"></span> karakter
                        </span>
                    </div>
                </div>
            </section>

            {{-- ========================================================
                STATUS PUBLIKASI
            ========================================================= --}}

            <section class="space-y-5">
                <div
                    class="
                        flex
                        items-center
                        gap-3
                        border-b
                        border-gray-100
                        pb-3
                        dark:border-gray-800
                    ">
                    <div
                        class="
                            flex
                            h-9
                            w-9
                            items-center
                            justify-center
                            rounded-lg
                            bg-green-50
                            text-green-600
                            dark:bg-green-500/15
                            dark:text-green-400
                        ">
                        <i class="ri-eye-line text-lg"></i>
                    </div>

                    <div>
                        <h3
                            class="
                                text-sm
                                font-semibold
                                text-gray-800
                                dark:text-white/90
                            ">
                            Status Publikasi
                        </h3>

                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-gray-500
                                dark:text-gray-400
                            ">
                            Tentukan apakah FAQ langsung ditampilkan kepada publik.
                        </p>
                    </div>
                </div>

                <div
                    class="
                        flex
                        flex-col
                        gap-4
                        rounded-xl
                        border
                        border-gray-200
                        bg-gray-50
                        p-4
                        dark:border-gray-800
                        dark:bg-gray-900
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
                    ">
                    <div class="flex items-start gap-3">
                        <div
                            class="
                                flex
                                h-10
                                w-10
                                shrink-0
                                items-center
                                justify-center
                                rounded-full
                                bg-white
                                text-gray-500
                                shadow-sm
                                dark:bg-gray-800
                                dark:text-gray-400
                            ">
                            <i :class="statusEnabled
                                ?
                                'ri-eye-line text-green-500' :
                                'ri-eye-off-line'"
                                class="text-lg"></i>
                        </div>

                        <div>
                            <p
                                class="
                                    text-sm
                                    font-semibold
                                    text-gray-800
                                    dark:text-white/90
                                ">
                                Tampilkan FAQ di halaman publik
                            </p>

                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    leading-5
                                    text-gray-500
                                    dark:text-gray-400
                                ">
                                FAQ aktif dapat langsung dibaca oleh pengunjung website.
                            </p>
                        </div>
                    </div>

                    <button type="button" role="switch" @click="statusEnabled = !statusEnabled"
                        :aria-checked="statusEnabled"
                        class="
                            relative
                            inline-flex
                            h-6
                            w-11
                            shrink-0
                            cursor-pointer
                            rounded-full
                            transition-colors
                            duration-200
                            focus:outline-hidden
                            focus:ring-3
                            focus:ring-brand-500/20
                        "
                        :class="statusEnabled
                            ?
                            'bg-brand-500' :
                            'bg-gray-300 dark:bg-gray-700'">
                        <span
                            class="
                                pointer-events-none
                                inline-block
                                h-5
                                w-5
                                translate-y-0.5
                                rounded-full
                                bg-white
                                shadow
                                transition-transform
                                duration-200
                            "
                            :class="statusEnabled
                                ?
                                'translate-x-5' :
                                'translate-x-0.5'"></span>
                    </button>
                </div>
            </section>

            {{-- ========================================================
                TOMBOL AKSI
            ========================================================= --}}

            <div
                class="
                    flex
                    flex-col-reverse
                    gap-3
                    border-t
                    border-gray-100
                    pt-6
                    dark:border-gray-800
                    sm:flex-row
                    sm:items-center
                    sm:justify-end
                ">
                <a href="{{ $cancelUrl }}"
                    class="
                        inline-flex
                        h-11
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        border
                        border-gray-300
                        bg-white
                        px-5
                        text-sm
                        font-medium
                        text-gray-700
                        shadow-theme-xs
                        transition
                        hover:bg-gray-50
                        hover:text-gray-800
                        focus:outline-hidden
                        focus:ring-3
                        focus:ring-gray-500/10
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-300
                        dark:hover:bg-gray-800
                        dark:hover:text-white
                    ">
                    <i class="ri-arrow-left-line text-lg"></i>

                    <span>Kembali</span>
                </a>

                <button type="reset" :disabled="submitting"
                    class="
                        inline-flex
                        h-11
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        border
                        border-gray-300
                        bg-white
                        px-5
                        text-sm
                        font-medium
                        text-gray-700
                        shadow-theme-xs
                        transition
                        hover:bg-gray-50
                        hover:text-gray-800
                        focus:outline-hidden
                        focus:ring-3
                        focus:ring-gray-500/10
                        disabled:cursor-not-allowed
                        disabled:opacity-50
                        dark:border-gray-700
                        dark:bg-gray-900
                        dark:text-gray-300
                        dark:hover:bg-gray-800
                        dark:hover:text-white
                    ">
                    <i class="ri-refresh-line text-lg"></i>

                    <span>Reset</span>
                </button>

                <button type="submit" :disabled="submitting"
                    class="
                        inline-flex
                        h-11
                        min-w-[150px]
                        items-center
                        justify-center
                        gap-2
                        rounded-lg
                        bg-brand-500
                        px-5
                        text-sm
                        font-semibold
                        text-white
                        shadow-theme-xs
                        transition
                        hover:bg-brand-600
                        focus:outline-hidden
                        focus:ring-3
                        focus:ring-brand-500/20
                        disabled:cursor-not-allowed
                        disabled:opacity-60
                    ">
                    <i x-show="!submitting" class="ri-save-line text-lg"></i>

                    <svg x-cloak x-show="submitting" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"
                        aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>

                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4Z"></path>
                    </svg>

                    <span x-show="!submitting">
                        {{ $submitLabel }}
                    </span>

                    <span x-cloak x-show="submitting">
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</x-common.component-card>
