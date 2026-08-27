@extends('layouts.public.app')

@section('title', $proker->nama_proker . ' | Program Kerja PPID Kota Batu')

@section('content')
	<section class="relative -mt-8 overflow-hidden bg-linear-to-r from-green-950 via-green-900 to-emerald-800">
		<div class="mx-auto max-w-6xl px-6 pb-24 pt-10 sm:px-8 lg:px-10">
			<a href="{{ route('public.program-kerja.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-green-100 transition hover:text-white"><span aria-hidden="true">&larr;</span> Kembali ke program kerja</a>
			<p class="mt-8 text-xs font-semibold uppercase tracking-widest text-yellow-400">Detail program kerja</p>
			<h1 class="mt-3 max-w-4xl text-3xl font-bold leading-tight text-white md:text-5xl">{{ $proker->nama_proker }}</h1>
		</div>
	</section>

	<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
		<div class="grid gap-5 sm:grid-cols-2">
			<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Anggaran</p><p class="mt-2 text-2xl font-bold text-green-800">{{ $proker->anggaran }}</p><p class="mt-1 text-xs text-slate-500">{{ $proker->sumber_dana }}</p></div>
			<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Jadwal pelaksanaan</p><p class="mt-2 text-lg font-bold text-slate-900">{{ $proker->jadwal_pelaksanaan?->locale('id')->translatedFormat('d F Y') ?? '-' }}</p></div>
		</div>

		<div class="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
			<dl class="divide-y divide-slate-100">
				<div class="grid gap-2 px-6 py-5 sm:grid-cols-3 sm:gap-6"><dt class="text-sm font-semibold text-slate-500">Unit penanggung jawab</dt><dd class="text-sm text-slate-900 sm:col-span-2">{{ $proker->ppidPembantu?->nama ?? 'PPID Kota Batu' }}</dd></div>
				<div class="grid gap-2 px-6 py-5 sm:grid-cols-3 sm:gap-6"><dt class="text-sm font-semibold text-slate-500">Target program</dt><dd class="whitespace-pre-line text-sm leading-7 text-slate-900 sm:col-span-2">{{ $proker->target }}</dd></div>
				<div class="grid gap-2 px-6 py-5 sm:grid-cols-3 sm:gap-6"><dt class="text-sm font-semibold text-slate-500">Penanggung jawab</dt><dd class="text-sm text-slate-900 sm:col-span-2">{{ $proker->pj }}@if ($proker->telp)<span class="block text-slate-500">{{ $proker->telp }}</span>@endif</dd></div>
			</dl>
			@if ($proker->dokumen_url)
				<div class="border-t border-slate-100 px-6 py-5"><a href="{{ $proker->dokumen_url }}" target="_blank" rel="noopener" class="inline-flex items-center rounded-lg bg-green-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-green-900">Buka dokumen pendukung</a></div>
			@endif
		</div>
	</section>
@endsection
