@extends('layouts.admin.app')

@section('title', 'Dashboard Survey')

@section('content')

    @php
        $statCards = [
            [
                'label' => 'Total Responden',
                'value' => $stats['total'] ?? 0,
                'description' => 'Seluruh penilaian yang masuk',
                'icon' => 'ri-user-star-line',
                'icon_class' => 'bg-blue-100 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                'value_class' => 'text-gray-900 dark:text-white',
            ],
            [
                'label' => 'Rata-rata Rating',
                'value' => number_format((float) ($stats['average'] ?? 0), 1, ',', '.'),
                'description' => 'Dari maksimal 5 bintang',
                'icon' => 'ri-star-fill',
                'icon_class' => 'bg-yellow-100 text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400',
                'value_class' => 'text-yellow-500 dark:text-yellow-400',
            ],
            [
                'label' => 'Rating 5 Bintang',
                'value' => $stats['five_star'] ?? 0,
                'description' => 'Responden sangat puas',
                'icon' => 'ri-emotion-happy-line',
                'icon_class' => 'bg-green-100 text-green-600 dark:bg-green-500/15 dark:text-green-400',
                'value_class' => 'text-green-600 dark:text-green-400',
            ],
            [
                'label' => 'Kritik & Saran',
                'value' => $stats['feedback'] ?? 0,
                'description' => 'Masukan untuk evaluasi',
                'icon' => 'ri-feedback-line',
                'icon_class' => 'bg-orange-100 text-orange-600 dark:bg-orange-500/15 dark:text-orange-400',
                'value_class' => 'text-orange-600 dark:text-orange-400',
            ],
        ];
    @endphp

    <div class="space-y-6">

        <section class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                    <i class="ri-star-line"></i>

                    <span>
                        Survey Pelayanan
                    </span>
                </div>

                <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                    Dashboard Survey
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-500 dark:text-gray-400">
                    Pantau tingkat kepuasan masyarakat,
                    distribusi rating, serta kritik dan saran
                    terhadap pelayanan PPID Kota Batu.
                </p>
            </div>

            <div
                class="flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm dark:border-gray-800 dark:bg-gray-800">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-600 dark:bg-green-500/15 dark:text-green-400">
                    <i class="ri-calendar-check-line text-lg"></i>
                </div>

                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Survey Hari Ini
                    </p>

                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ number_format((int) ($stats['today'] ?? 0), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div
                class="flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-500/20 dark:bg-green-500/10 dark:text-green-400">
                <i class="ri-checkbox-circle-line mt-0.5 text-lg"></i>

                <span>
                    {{ session('success') }}
                </span>
            </div>
        @endif

        <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

            @foreach ($statCards as $card)
                <article
                    class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-gray-800 dark:shadow-none">
                    <div class="flex items-start justify-between gap-4">

                        <div class="min-w-0">

                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $card['label'] }}
                            </p>

                            <h2 class="mt-3 text-3xl font-bold tracking-tight {{ $card['value_class'] }}">
                                @if ($card['label'] === 'Rata-rata Rating')
                                    {{ $card['value'] }}

                                    <span class="text-base font-medium text-gray-400">
                                        / 5
                                    </span>
                                @else
                                    {{ number_format((int) $card['value'], 0, ',', '.') }}
                                @endif
                            </h2>

                            <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                {{ $card['description'] }}
                            </p>

                        </div>

                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $card['icon_class'] }}">
                            <i class="{{ $card['icon'] }} text-xl"></i>
                        </div>

                    </div>
                </article>
            @endforeach

        </section>

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">

            <article
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-800 dark:shadow-none sm:p-6">

                <div class="flex items-start justify-between gap-4">

                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                            Distribusi Rating
                        </h2>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Sebaran rating dari seluruh responden.
                        </p>
                    </div>

                    <div class="flex items-center gap-1 rounded-lg bg-yellow-50 px-3 py-2 dark:bg-yellow-500/10">

                        <i class="ri-star-fill text-yellow-400"></i>

                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                            {{ number_format((float) ($stats['average'] ?? 0), 1, ',', '.') }}
                        </span>

                    </div>

                </div>

                <div class="mt-6 space-y-4">

                    @foreach ($ratingDistribution as $rating => $ratingData)
                        <div>

                            <div class="mb-1.5 flex items-center justify-between gap-4">

                                <div class="flex w-16 shrink-0 items-center gap-1">

                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ $rating }}
                                    </span>

                                    <i class="ri-star-fill text-sm text-yellow-400"></i>

                                </div>

                                <div class="flex items-center gap-2">

                                    <span class="text-xs text-gray-400">
                                        {{ number_format((float) $ratingData['percentage'], 1, ',', '.') }}%
                                    </span>

                                    <span class="min-w-8 text-right text-xs font-semibold text-gray-600 dark:text-gray-300">
                                        {{ number_format((int) $ratingData['count'], 0, ',', '.') }}
                                    </span>

                                </div>

                            </div>

                            <div class="h-2.5 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">

                                <div class="h-full rounded-full bg-yellow-400 transition-all duration-500"
                                    style="width: {{ min(100, max(0, (float) $ratingData['percentage'])) }}%;">
                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

            </article>

            <article
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-800 dark:shadow-none sm:p-6">

                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                        Penilaian Per Layanan
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Perbandingan penilaian berdasarkan layanan.
                    </p>
                </div>

                <div class="mt-6 space-y-4">

                    @forelse ($serviceStats as $service)
                        <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700">

                            <div class="flex items-start justify-between gap-4">

                                <div class="min-w-0">

                                    <p class="truncate text-sm font-semibold text-gray-800 dark:text-gray-200">
                                        {{ $service['service'] }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ number_format($service['total'], 0, ',', '.') }}
                                        responden
                                    </p>

                                </div>

                                <div
                                    class="flex shrink-0 items-center gap-1 rounded-lg bg-yellow-50 px-2.5 py-1.5 dark:bg-yellow-500/10">

                                    <i class="ri-star-fill text-sm text-yellow-400"></i>

                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-200">
                                        {{ number_format($service['average'], 1, ',', '.') }}
                                    </span>

                                </div>

                            </div>

                            <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">

                                <div class="h-full rounded-full bg-blue-500"
                                    style="width: {{ min(100, max(0, $service['percentage'])) }}%;">
                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="py-12 text-center">
                            <div
                                class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700">
                                <i class="ri-bar-chart-grouped-line text-xl"></i>
                            </div>

                            <p class="mt-3 text-sm text-gray-500">
                                Belum ada data layanan.
                            </p>
                        </div>
                    @endforelse

                </div>

            </article>

        </section>

        <section
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-800 dark:shadow-none">

            <div class="border-b border-gray-200 p-5 dark:border-gray-700 sm:p-6">

                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

                    <div>

                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                            Hasil Survey
                        </h2>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Daftar penilaian yang diberikan masyarakat.
                        </p>

                    </div>

                    <div class="flex items-center gap-2 text-xs text-gray-500">

                        <span class="rounded-lg bg-gray-100 px-3 py-2 dark:bg-gray-700 dark:text-gray-300">
                            Hari ini:
                            <strong>
                                {{ number_format((int) ($stats['today'] ?? 0), 0, ',', '.') }}
                            </strong>
                        </span>

                        <span class="rounded-lg bg-gray-100 px-3 py-2 dark:bg-gray-700 dark:text-gray-300">
                            Bulan ini:
                            <strong>
                                {{ number_format((int) ($stats['month'] ?? 0), 0, ',', '.') }}
                            </strong>
                        </span>

                    </div>

                </div>

                <form action="{{ route('admin.survey.index') }}" method="GET"
                    class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-6">

                    <div class="xl:col-span-2">

                        <label for="search" class="sr-only">
                            Cari
                        </label>

                        <div class="relative">

                            <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama, layanan, atau masukan..."
                                class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">

                        </div>

                    </div>

                    <div>

                        <select name="service"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">

                            <option value="">
                                Semua Layanan
                            </option>

                            @foreach ($services as $service)
                                <option value="{{ $service }}" @selected(request('service') === $service)>
                                    {{ $service }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div>

                        <select name="rating"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">

                            <option value="">
                                Semua Rating
                            </option>

                            @for ($rating = 5; $rating >= 1; $rating--)
                                <option value="{{ $rating }}" @selected((string) request('rating') === (string) $rating)>
                                    {{ $rating }} Bintang
                                </option>
                            @endfor

                        </select>

                    </div>

                    <div>

                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">

                    </div>

                    <div class="flex items-center gap-2">

                        <button type="submit"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-blue-600 px-3 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                            <i class="ri-filter-3-line"></i>

                            Filter
                        </button>

                        <a href="{{ route('admin.survey.index') }}"
                            class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-300 text-gray-500 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700"
                            title="Reset filter">
                            <i class="ri-refresh-line"></i>
                        </a>

                    </div>

                </form>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-[1100px] w-full">

                    <thead class="bg-gray-50 dark:bg-gray-900/50">

                        <tr>

                            <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                No
                            </th>

                            <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                Responden
                            </th>

                            <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                Layanan
                            </th>

                            <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                Rating
                            </th>

                            <th
                                class="min-w-[300px] px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                Kritik & Saran
                            </th>

                            <th class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                                Tanggal
                            </th>

                            <th class="px-6 py-3.5 text-center text-xs font-medium text-gray-500 dark:text-gray-400">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse ($surveys as $survey)

                            <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.03]">

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $surveys->firstItem() + $loop->index }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                                            {{ mb_strtoupper(mb_substr($survey->name ?: 'A', 0, 1)) }}
                                        </div>

                                        <div>

                                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $survey->name ?: 'Anonim' }}
                                            </p>

                                            <p class="mt-0.5 text-xs text-gray-400">
                                                #{{ $survey->id }}
                                            </p>

                                        </div>

                                    </div>

                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $survey->service ?: '-' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="flex items-center gap-1">

                                        @for ($star = 1; $star <= 5; $star++)
                                            <i
                                                class="ri-star-fill text-base {{ $star <= (int) $survey->rating ? 'text-yellow-400' : 'text-gray-200 dark:text-gray-700' }}"></i>
                                        @endfor

                                    </div>

                                    <p class="mt-1 text-xs font-semibold text-gray-500">
                                        {{ $survey->rating }}/5
                                    </p>

                                </td>

                                <td class="px-6 py-4">

                                    @if (!empty($survey->message))
                                        <p class="line-clamp-3 text-sm leading-6 text-gray-600 dark:text-gray-300">
                                            {{ $survey->message }}
                                        </p>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-500/15 dark:text-green-400">
                                            <i class="ri-checkbox-circle-line"></i>

                                            Tanpa kritik
                                        </span>
                                    @endif

                                </td>

                                <td class="whitespace-nowrap px-6 py-4">

                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ optional($survey->created_at)->format('d M Y') }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ optional($survey->created_at)->format('H:i') }}
                                    </p>

                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-center">

                                    <form action="{{ route('admin.survey.destroy', $survey) }}" method="POST"
                                        onsubmit="return confirm('Hapus hasil survey ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/15 dark:hover:text-red-400"
                                            title="Hapus survey">
                                            <i class="ri-delete-bin-line text-lg"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="px-6 py-16 text-center">

                                    <div
                                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500">
                                        <i class="ri-survey-line text-2xl"></i>
                                    </div>

                                    <p class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        Belum ada hasil survey
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Penilaian masyarakat akan tampil
                                        di halaman ini.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if ($surveys->hasPages())
                <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                    {{ $surveys->links() }}
                </div>
            @endif

        </section>

    </div>

@endsection
