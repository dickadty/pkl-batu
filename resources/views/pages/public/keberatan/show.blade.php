@extends('layouts.public.app')

@section('title', 'Detail Keberatan | PPID Kota Batu')

@section('content')
    @php
        $statusClass = match ($keberatan->status) {
            'Diajukan' => 'bg-blue-50 text-blue-700',

            'Diproses' => 'bg-amber-50 text-amber-700',

            'Selesai' => 'bg-green-50 text-green-700',

            'Ditolak' => 'bg-red-50 text-red-700',

            default => 'bg-slate-100 text-slate-700',
        };
    @endphp

    <section
        class="
            mx-auto
            max-w-5xl
            px-4
            py-10
            sm:px-6
            lg:px-8
        ">
        <x-ui.flash-messages />

        <div
            class="
                overflow-hidden
                rounded-2xl
                border
                border-slate-200
                bg-white
                shadow-sm
            ">
            <div
                class="
                    flex
                    flex-col
                    gap-4
                    border-b
                    border-slate-200
                    px-6
                    py-5
                    sm:flex-row
                    sm:items-start
                    sm:justify-between
                ">
                <div>
                    <p
                        class="
                            text-sm
                            font-semibold
                            text-red-700
                        ">
                        {{ $keberatan->no_keberatan }}
                    </p>

                    <h1
                        class="
                            mt-2
                            text-2xl
                            font-bold
                            text-slate-900
                        ">
                        Detail Keberatan
                    </h1>
                </div>

                <span
                    class="
                        inline-flex
                        w-fit
                        rounded-full
                        px-3
                        py-1.5
                        text-xs
                        font-semibold
                        {{ $statusClass }}
                    ">
                    {{ $keberatan->status }}
                </span>
            </div>

            <dl
                class="
                    grid
                    grid-cols-1
                    gap-6
                    p-6
                    md:grid-cols-2
                ">
                <div>
                    <dt
                        class="
                            text-sm
                            font-semibold
                            text-slate-700
                        ">
                        Nomor Permohonan
                    </dt>

                    <dd
                        class="
                            mt-2
                            text-sm
                            text-slate-600
                        ">
                        {{ $keberatan->permohonan?->no_pemohon ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt
                        class="
                            text-sm
                            font-semibold
                            text-slate-700
                        ">
                        Tanggal Pengajuan
                    </dt>

                    <dd
                        class="
                            mt-2
                            text-sm
                            text-slate-600
                        ">
                        {{ $keberatan->tanggal_pengajuan?->locale('id')->translatedFormat('d F Y') ?? '-' }}
                    </dd>
                </div>

                <div class="md:col-span-2">
                    <dt
                        class="
                            text-sm
                            font-semibold
                            text-slate-700
                        ">
                        Alasan Keberatan
                    </dt>

                    <dd
                        class="
                            mt-2
                            whitespace-pre-line
                            rounded-xl
                            bg-slate-50
                            p-4
                            text-sm
                            leading-7
                            text-slate-700
                        ">
                        {{ $keberatan->alasan }}
                    </dd>
                </div>

                <div class="md:col-span-2">
                    <dt
                        class="
                            text-sm
                            font-semibold
                            text-slate-700
                        ">
                        Tanggapan PPID
                    </dt>

                    <dd
                        class="
                            mt-2
                            whitespace-pre-line
                            rounded-xl
                            border
                            border-slate-200
                            p-4
                            text-sm
                            leading-7
                            text-slate-700
                        ">
                        {{ $keberatan->tanggapan ?: 'Belum ada tanggapan.' }}
                    </dd>
                </div>
            </dl>

            <div
                class="
                    border-t
                    border-slate-200
                    bg-slate-50
                    px-6
                    py-4
                ">
                <a href="{{ route('public.keberatan.index') }}"
                    class="
                        inline-flex
                        h-10
                        items-center
                        justify-center
                        rounded-lg
                        border
                        border-slate-300
                        bg-white
                        px-4
                        text-sm
                        font-semibold
                        text-slate-700
                    ">
                    Kembali
                </a>
            </div>
        </div>
    </section>
@endsection
