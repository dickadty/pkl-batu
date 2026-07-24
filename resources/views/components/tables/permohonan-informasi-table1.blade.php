@props([
    'permohonan',
    'ppidPembantuList' => null,
])

@php
    $statusClasses = [
        'Diajukan' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/15 dark:text-blue-400',
        'Diproses' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/15 dark:text-yellow-400',
        'Diteruskan ke PPID Pembantu' => 'bg-purple-50 text-purple-700 ring-purple-600/20 dark:bg-purple-500/15 dark:text-purple-400',
        'Menunggu Validasi Admin Utama' => 'bg-orange-50 text-orange-700 ring-orange-600/20 dark:bg-orange-500/15 dark:text-orange-400',
        'Revisi PPID Pembantu' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/15 dark:text-red-400',
        'Selesai' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/15 dark:text-green-400',
    ];
@endphp

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800 sm:px-6">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-800 dark:text-white/90">Daftar Permohonan</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Total {{ $permohonan->count() }} permohonan informasi.
                </p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Nomor Tiket
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Pemohon
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Rincian
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        PPID Tujuan
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Status
                    </th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($permohonan as $item)
                    @php
                        $status = trim((string) ($item->status ?: 'Diajukan'));
                        $badgeClass = $statusClasses[$status]
                            ?? 'bg-gray-100 text-gray-700 ring-gray-500/20 dark:bg-gray-800 dark:text-gray-300';

                        $namaPemohon = data_get($item, 'userPublic.nama', $item->nama_pemohon ?: '-');
                        $emailPemohon = data_get($item, 'userPublic.email', $item->email_pemohon ?: '-');
                        $ppidNama = data_get($item, 'ppidPembantu.nama', '-');
                    @endphp

                    <tr class="align-top transition hover:bg-gray-50/80 dark:hover:bg-white/[0.02]">
                        <td class="whitespace-nowrap px-5 py-4">
                            <p class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                {{ $item->no_pemohon ?: '#' . $item->id }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ $item->tanggal ? $item->tanggal->format('d-m-Y') : '-' }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            <p class="max-w-[220px] truncate text-sm font-semibold text-gray-800 dark:text-white/90">
                                {{ $namaPemohon }}
                            </p>

                            <p class="mt-1 max-w-[220px] truncate text-xs text-gray-500 dark:text-gray-400">
                                {{ $emailPemohon }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            <p class="max-w-[320px] text-sm leading-6 text-gray-700 dark:text-gray-300">
                                {{ \Illuminate\Support\Str::limit((string) $item->rincian, 110) }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            <p class="max-w-[220px] text-sm text-gray-700 dark:text-gray-300">
                                {{ $ppidNama }}
                            </p>
                        </td>

                        <td class="whitespace-nowrap px-5 py-4">
                            <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset {{ $badgeClass }}">
                                {{ $status }}
                            </span>
                        </td>

                        <td class="whitespace-nowrap px-5 py-4 text-right">
                            <a href="{{ route('admin.permohonan.show', $item->id) }}"
                                class="inline-flex h-9 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-3 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                                <i class="ri-eye-line text-base"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-14 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                <i class="ri-file-search-line text-xl"></i>
                            </div>

                            <p class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Belum ada permohonan informasi.
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
