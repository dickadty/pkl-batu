@php
    $averageRating = (float) ($surveyStats['average'] ?? 0);
    $totalRating = (int) ($surveyStats['total'] ?? 0);
@endphp

<section id="survey" class="relative overflow-hidden bg-white py-16 lg:py-24">
    <div class="pointer-events-none absolute -right-24 top-10 h-72 w-72 rounded-full bg-emerald-100/60 blur-3xl"></div>

    <div class="pointer-events-none absolute -left-24 bottom-0 h-64 w-64 rounded-full bg-green-100/70 blur-3xl"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        <div class="mx-auto mb-10 max-w-2xl text-center">
            <span
                class="inline-flex items-center rounded-full border border-emerald-900 bg-emerald-900 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.16em] text-white">
                Survey Pelayanan
            </span>

            <h2 class="mt-4 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl lg:text-4xl">
                Bagaimana pengalaman Anda menggunakan layanan PPID?
            </h2>

            <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                Berikan penilaian untuk membantu kami meningkatkan
                kualitas pelayanan informasi publik PPID Kota Batu.
            </p>
        </div>

        @if (session('survey_success'))
            <div
                class="mx-auto mb-6 flex max-w-4xl items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-900">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <div>
                    <p class="font-semibold">
                        Terima kasih!
                    </p>

                    <p class="mt-1 text-emerald-800">
                        {{ session('survey_success') }}
                    </p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="mx-auto mb-6 max-w-4xl rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <p class="font-semibold">
                    Mohon periksa kembali data berikut:
                </p>

                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div
            class="mx-auto max-w-4xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-200/60">

            <div
                class="border-b border-slate-200 bg-linear-to-r from-white via-emerald-50/50 to-white px-6 py-6 sm:px-8">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-emerald-700">
                            Rating Pelayanan PPID
                        </p>

                        <div class="mt-3 flex flex-wrap items-center gap-x-2 gap-y-2">
                            <span class="text-4xl font-bold tracking-tight text-slate-800">
                                {{ number_format($averageRating, 1, ',', '.') }}
                            </span>

                            <div class="flex items-center"
                                aria-label="Rating {{ number_format($averageRating, 1, ',', '.') }} dari 5">
                                @for ($i = 1; $i <= 5; $i++)
                                    @php
                                        $fill = max(0, min(100, ($averageRating - ($i - 1)) * 100));
                                    @endphp

                                    <span class="relative inline-block h-7 w-7">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="absolute inset-0 h-7 w-7 text-slate-300">
                                            <path
                                                d="M12 2.75l2.79 5.65 6.24.91-4.51 4.4 1.06 6.21L12 17l-5.58 2.92 1.06-6.21-4.51-4.4 6.24-.91L12 2.75z" />
                                        </svg>

                                        <span class="absolute inset-y-0 left-0 overflow-hidden"
                                            style="width: {{ $fill }}%;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="h-7 w-7 min-w-7 text-amber-400">
                                                <path
                                                    d="M12 2.75l2.79 5.65 6.24.91-4.51 4.4 1.06 6.21L12 17l-5.58 2.92 1.06-6.21-4.51-4.4 6.24-.91L12 2.75z" />
                                            </svg>
                                        </span>
                                    </span>
                                @endfor
                            </div>

                            <span class="text-lg font-medium text-slate-500">
                                ({{ number_format($totalRating, 0, ',', '.') }})
                            </span>
                        </div>

                        <p class="mt-2 text-xs leading-5 text-slate-500">
                            Berdasarkan penilaian masyarakat terhadap
                            pelayanan PPID Kota Batu.
                        </p>
                    </div>

                    <div
                        class="inline-flex w-fit items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                        <span class="text-xs font-semibold text-emerald-800">
                            {{ number_format($totalRating, 0, ',', '.') }}
                            responden
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 lg:p-10">

                <form id="surveyForm" action="{{ route('public.survey.store') }}" method="POST">
                    @csrf

                    <div class="grid gap-6 md:grid-cols-2">

                        <div>
                            <label for="survey-name" class="mb-2 block text-sm font-semibold text-slate-800">
                                Nama

                                <span class="font-normal text-slate-400">
                                    (opsional)
                                </span>
                            </label>

                            <input type="text" id="survey-name" name="name" maxlength="100"
                                value="{{ old('name') }}" placeholder="Masukkan nama Anda"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100">

                            @error('name')
                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="survey-service" class="mb-2 block text-sm font-semibold text-slate-800">
                                Layanan yang dinilai

                                <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">

                                <select id="survey-service" name="service" required
                                    class="w-full appearance-none rounded-xl border border-slate-300 bg-white px-4 py-3 pr-10 text-sm text-slate-900 outline-none transition focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100">
                                    <option value="">
                                        Pilih layanan
                                    </option>

                                    <option value="Website PPID" @selected(old('service') === 'Website PPID')>
                                        Website PPID
                                    </option>

                                    <option value="Informasi Publik" @selected(old('service') === 'Informasi Publik')>
                                        Informasi Publik
                                    </option>

                                    <option value="Permohonan Informasi" @selected(old('service') === 'Permohonan Informasi')>
                                        Permohonan Informasi
                                    </option>

                                    <option value="Keberatan Informasi" @selected(old('service') === 'Keberatan Informasi')>
                                        Keberatan Informasi
                                    </option>

                                    <option value="Pelayanan PPID" @selected(old('service') === 'Pelayanan PPID')>
                                        Pelayanan PPID
                                    </option>
                                </select>

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor"
                                    class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>

                            </div>

                            @error('service')
                                <p class="mt-2 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-7 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-6 text-center sm:px-8">
                        <p class="text-sm font-semibold text-slate-900">
                            Bagaimana penilaian Anda?
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Klik bintang sesuai pengalaman Anda.
                        </p>

                        <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating') }}">

                        <div id="starRating" class="mt-4 flex items-center justify-center gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button"
                                    class="rating-star rounded-md transition duration-150 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-amber-300"
                                    data-value="{{ $i }}"
                                    aria-label="Beri rating {{ $i }} bintang">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="star-icon h-11 w-11 text-slate-300 transition duration-150 sm:h-12 sm:w-12">
                                        <path
                                            d="M12 2.75l2.79 5.65 6.24.91-4.51 4.4 1.06 6.21L12 17l-5.58 2.92 1.06-6.21-4.51-4.4 6.24-.91L12 2.75z" />
                                    </svg>
                                </button>
                            @endfor
                        </div>

                        <p id="ratingLabel" class="mt-2 min-h-5 text-sm font-semibold text-slate-600">
                            Pilih 1 sampai 5 bintang
                        </p>

                        @error('rating')
                            <p class="mt-2 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div
                        class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <p id="surveyHelp" class="text-xs leading-5 text-slate-500">
                            Pilih rating terlebih dahulu sebelum
                            mengirim penilaian.
                        </p>

                        <button type="submit" id="mainSurveySubmit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-linear-to-r from-green-950 via-green-900 to-emerald-700 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-emerald-900/15 transition hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-emerald-200">
                            Kirim Penilaian

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 12L3.27 3.125A59.769 59.769 0 0121.485 12 59.768 59.768 0 013.27 20.875L6 12zm0 0h7.5" />
                            </svg>
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

    <div id="feedbackModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4" aria-hidden="true">
        <div id="feedbackOverlay" class="absolute inset-0 bg-slate-950/50 backdrop-blur-[2px]"></div>

        <div class="relative z-10 w-full max-w-sm overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div
                class="flex items-center justify-between bg-linear-to-r from-green-950 via-green-900 to-emerald-700 px-5 py-3.5 text-white">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.14em] text-emerald-200">
                        Masukan Anda
                    </p>

                    <h3 class="mt-0.5 text-sm font-bold sm:text-base">
                        Bantu kami lebih baik
                    </h3>
                </div>

                <button type="button" id="closeFeedbackModal"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/10 transition hover:bg-white/20"
                    aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-5">

                <div class="mb-4 flex items-center gap-3 rounded-xl border border-amber-100 bg-amber-50 px-3 py-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="h-5 w-5 shrink-0 text-amber-400">
                        <path
                            d="M12 2.75l2.79 5.65 6.24.91-4.51 4.4 1.06 6.21L12 17l-5.58 2.92 1.06-6.21-4.51-4.4 6.24-.91L12 2.75z" />
                    </svg>

                    <div>
                        <p class="text-xs font-semibold text-slate-800">
                            Penilaian Anda
                        </p>

                        <p id="modalRatingText" class="mt-0.5 text-xs text-slate-500"></p>
                    </div>
                </div>

                <label for="feedbackTextarea" class="mb-2 block text-sm font-semibold text-slate-800">
                    Kritik dan Saran

                    <span class="text-red-500">*</span>
                </label>

                <textarea id="feedbackTextarea" name="message" form="surveyForm" rows="3" maxlength="1000"
                    placeholder="Tuliskan kritik atau saran Anda..."
                    class="w-full resize-none rounded-xl border border-slate-300 bg-white px-3.5 py-3 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-100">{{ old('message') }}</textarea>

                <div class="mt-1.5 flex items-center justify-between gap-3">
                    <div>
                        <p id="feedbackError" class="hidden text-xs font-medium text-red-600">
                            Kritik atau saran wajib diisi.
                        </p>

                        @error('message')
                            <p class="text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <p id="feedbackCounter" class="ml-auto shrink-0 text-[11px] text-slate-400">
                        0 / 1000
                    </p>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3">

                    <button type="button" id="cancelFeedback"
                        class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-100">
                        Batal
                    </button>

                    <button type="button" id="submitFeedback"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                        Kirim

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 12L3.27 3.125A59.769 59.769 0 0121.485 12 59.768 59.768 0 013.27 20.875L6 12zm0 0h7.5" />
                        </svg>
                    </button>

                </div>

            </div>
        </div>
    </div>

</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('surveyForm');
            const serviceInput = document.getElementById('survey-service');
            const ratingInput = document.getElementById('ratingInput');
            const starRating = document.getElementById('starRating');
            const stars = document.querySelectorAll('.rating-star');
            const ratingLabel = document.getElementById('ratingLabel');
            const surveyHelp = document.getElementById('surveyHelp');

            const modal = document.getElementById('feedbackModal');
            const overlay = document.getElementById('feedbackOverlay');
            const modalRatingText = document.getElementById('modalRatingText');
            const feedbackTextarea = document.getElementById('feedbackTextarea');
            const feedbackError = document.getElementById('feedbackError');
            const feedbackCounter = document.getElementById('feedbackCounter');

            const closeButton = document.getElementById('closeFeedbackModal');
            const cancelButton = document.getElementById('cancelFeedback');
            const submitFeedbackButton = document.getElementById('submitFeedback');

            if (
                !form ||
                !ratingInput ||
                !starRating ||
                !ratingLabel ||
                !surveyHelp ||
                !modal ||
                !feedbackTextarea ||
                !submitFeedbackButton
            ) {
                return;
            }

            const labels = {
                1: 'Sangat Kurang',
                2: 'Kurang',
                3: 'Cukup',
                4: 'Baik',
                5: 'Sangat Baik'
            };

            const hasMessageError = @json($errors->has('message'));

            let allowLowRatingSubmit = false;

            function paintStars(value) {
                stars.forEach(function(star) {
                    const starValue = Number(star.dataset.value);
                    const icon = star.querySelector('.star-icon');

                    if (!icon) {
                        return;
                    }

                    if (starValue <= value) {
                        icon.classList.remove('text-slate-300');
                        icon.classList.add('text-amber-400');
                    } else {
                        icon.classList.remove('text-amber-400');
                        icon.classList.add('text-slate-300');
                    }
                });
            }

            function updateCounter() {
                if (!feedbackCounter) {
                    return;
                }

                feedbackCounter.textContent =
                    feedbackTextarea.value.length + ' / 1000';
            }

            function setRatingLabel(value) {
                ratingLabel.textContent =
                    value + ' dari 5 - ' + labels[value];

                ratingLabel.classList.remove(
                    'text-slate-600',
                    'text-red-600',
                    'text-amber-600',
                    'text-emerald-700'
                );

                if (value === 5) {
                    ratingLabel.classList.add('text-emerald-700');
                } else {
                    ratingLabel.classList.add('text-amber-600');
                }
            }

            function openModal(value) {
                allowLowRatingSubmit = false;

                if (modalRatingText) {
                    modalRatingText.textContent =
                        value + ' dari 5 - ' + labels[value];
                }

                if (feedbackError) {
                    feedbackError.classList.add('hidden');
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');

                modal.setAttribute(
                    'aria-hidden',
                    'false'
                );

                document.body.classList.add(
                    'overflow-hidden'
                );

                updateCounter();

                setTimeout(function() {
                    feedbackTextarea.focus();
                }, 100);
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');

                modal.setAttribute(
                    'aria-hidden',
                    'true'
                );

                document.body.classList.remove(
                    'overflow-hidden'
                );
            }

            function selectRating(value) {
                allowLowRatingSubmit = false;

                ratingInput.value = value;

                paintStars(value);
                setRatingLabel(value);

                if (value === 5) {
                    feedbackTextarea.value = '';

                    surveyHelp.textContent =
                        'Terima kasih. Anda dapat langsung mengirim penilaian.';

                    closeModal();

                    return;
                }

                surveyHelp.textContent =
                    'Berikan kritik atau saran untuk membantu kami meningkatkan pelayanan.';

                openModal(value);
            }

            stars.forEach(function(star) {
                star.addEventListener('click', function() {
                    const value = Number(
                        this.dataset.value
                    );

                    selectRating(value);
                });

                star.addEventListener('mouseenter', function() {
                    paintStars(
                        Number(this.dataset.value)
                    );
                });

                star.addEventListener('focus', function() {
                    paintStars(
                        Number(this.dataset.value)
                    );
                });
            });

            starRating.addEventListener(
                'mouseleave',
                function() {
                    paintStars(
                        Number(ratingInput.value || 0)
                    );
                }
            );

            starRating.addEventListener(
                'focusout',
                function() {
                    paintStars(
                        Number(ratingInput.value || 0)
                    );
                }
            );

            feedbackTextarea.addEventListener(
                'input',
                function() {
                    updateCounter();

                    if (
                        feedbackError &&
                        feedbackTextarea.value.trim() !== ''
                    ) {
                        feedbackError.classList.add(
                            'hidden'
                        );
                    }
                }
            );

            submitFeedbackButton.addEventListener(
                'click',
                function() {
                    const rating =
                        Number(ratingInput.value);

                    const message =
                        feedbackTextarea.value.trim();

                    if (
                        rating < 1 ||
                        rating > 4
                    ) {
                        closeModal();
                        return;
                    }

                    if (message === '') {
                        if (feedbackError) {
                            feedbackError.classList.remove(
                                'hidden'
                            );
                        }

                        feedbackTextarea.focus();

                        return;
                    }

                    if (
                        serviceInput &&
                        serviceInput.value === ''
                    ) {
                        closeModal();

                        serviceInput.focus();
                        serviceInput.reportValidity();

                        return;
                    }

                    if (feedbackError) {
                        feedbackError.classList.add(
                            'hidden'
                        );
                    }

                    allowLowRatingSubmit = true;

                    closeModal();

                    form.requestSubmit();
                }
            );

            form.addEventListener(
                'submit',
                function(event) {
                    const rating =
                        Number(ratingInput.value);

                    if (!rating) {
                        event.preventDefault();

                        ratingLabel.textContent =
                            'Silakan pilih rating terlebih dahulu.';

                        ratingLabel.classList.remove(
                            'text-slate-600',
                            'text-emerald-700',
                            'text-amber-600'
                        );

                        ratingLabel.classList.add(
                            'text-red-600'
                        );

                        starRating.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        return;
                    }

                    if (
                        rating >= 1 &&
                        rating <= 4
                    ) {
                        if (
                            feedbackTextarea.value.trim() === ''
                        ) {
                            event.preventDefault();

                            openModal(rating);

                            if (feedbackError) {
                                feedbackError.classList.remove(
                                    'hidden'
                                );
                            }

                            return;
                        }

                        if (!allowLowRatingSubmit) {
                            event.preventDefault();

                            openModal(rating);

                            return;
                        }
                    }
                }
            );

            if (closeButton) {
                closeButton.addEventListener(
                    'click',
                    closeModal
                );
            }

            if (cancelButton) {
                cancelButton.addEventListener(
                    'click',
                    closeModal
                );
            }

            if (overlay) {
                overlay.addEventListener(
                    'click',
                    closeModal
                );
            }

            document.addEventListener(
                'keydown',
                function(event) {
                    if (
                        event.key === 'Escape' &&
                        !modal.classList.contains('hidden')
                    ) {
                        closeModal();
                    }
                }
            );

            const oldRating =
                Number(ratingInput.value || 0);

            if (oldRating > 0) {
                paintStars(oldRating);
                setRatingLabel(oldRating);

                if (oldRating === 5) {
                    surveyHelp.textContent =
                        'Terima kasih. Anda dapat langsung mengirim penilaian.';
                }

                if (
                    oldRating >= 1 &&
                    oldRating <= 4
                ) {
                    surveyHelp.textContent =
                        'Berikan kritik atau saran untuk membantu kami meningkatkan pelayanan.';
                }
            }

            updateCounter();

            if (
                hasMessageError &&
                oldRating >= 1 &&
                oldRating <= 4
            ) {
                openModal(oldRating);

                if (feedbackError) {
                    feedbackError.classList.remove(
                        'hidden'
                    );
                }
            }
        });
    </script>
@endpush
