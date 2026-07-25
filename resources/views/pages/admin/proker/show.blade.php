@extends('layouts.admin.app')

@section('title', 'Detail Program Kerja')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header
            title="Detail Program Kerja"
            description="Informasi lengkap program kerja dan dokumen pendukung."
            :breadcrumbs="[
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'icon' => 'ri-dashboard-line',
                ],
                [
                    'label' => 'Program Kerja',
                    'url' => route('admin.proker.index'),
                ],
                [
                    'label' => 'Detail Program Kerja',
                ],
            ]"
        />

        <x-ui.flash-messages />

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 border-b border-gray-100 pb-5 dark:border-gray-800 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $proker->nama_proker }}
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        {{ $proker->ppidPembantu?->nama ?? '-' }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if ($proker->dokumen)
                        <a
                            href="{{ route(
                                'admin.proker.dokumen',
                                ['id' => $proker->id]
                            ) }}"
                            target="_blank"
                            class="inline-flex h-10 items-center justify-center rounded-lg border border-gray-300 px-4 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-300"
                        >
                            Buka Dokumen
                        </a>
                    @endif

                    <a
                        href="{{ route(
                            'admin.proker.edit',
                            ['id' => $proker->id]
                        ) }}"
                        class="inline-flex h-10 items-center justify-center rounded-lg bg-brand-500 px-4 text-sm font-semibold text-white"
                    >
                        Edit
                    </a>
                </div>
            </div>

            <dl class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div>
                    <dt class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Anggaran
                    </dt>

                    <dd class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-600 dark:text-gray-400">
                        {{ $proker->anggaran }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Sumber Dana
                    </dt>

                    <dd class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-600 dark:text-gray-400">
                        {{ $proker->sumber_dana }}
                    </dd>
                </div>

                <div class="lg:col-span-2">
                    <dt class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Target
                    </dt>

                    <dd class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-600 dark:text-gray-400">
                        {{ $proker->target }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Jadwal Pelaksanaan
                    </dt>

                    <dd class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{
                            $proker->jadwal_pelaksanaan
                                ? $proker->jadwal_pelaksanaan
                                    ->locale('id')
                                    ->translatedFormat('d F Y')
                                : '-'
                        }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Penanggung Jawab
                    </dt>

                    <dd class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ $proker->pj }}

                        @if ($proker->telp)
                            <br>
                            {{ $proker->telp }}
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>
@endsection