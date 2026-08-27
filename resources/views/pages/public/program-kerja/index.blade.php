@extends('layouts.public.app')

@section('title', 'Program Kerja | PPID Kota Batu')

@section('content')
	<section class="relative -mt-8 overflow-hidden bg-linear-to-r from-green-950 via-green-900 to-emerald-800">
		<div class="absolute inset-0">
			<div class="absolute -left-20 top-0 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
			<div class="absolute right-0 top-10 h-64 w-64 rounded-full bg-emerald-300/10 blur-3xl"></div>
		</div>

		<div class="relative mx-auto max-w-6xl px-6 pb-28 pt-16 text-center sm:px-8 lg:px-10">
			<span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-white backdrop-blur-sm">
				Transparansi Informasi
			</span>
			<h1 class="mx-auto mt-4 max-w-3xl text-2xl font-bold leading-tight text-white md:text-3xl">
				Informasi Publik<br>
				<span class="text-yellow-500">Program Kerja</span>
			</h1>
			<p class="mx-auto mt-4 max-w-2xl text-xs leading-6 text-green-100 md:text-sm">
				Temukan program kerja PPID Kota Batu beserta target, anggaran,<br class="hidden sm:inline">
				dan jadwal pelaksanaannya secara terbuka.
			</p>
		</div>

		<div class="absolute bottom-0 left-0 z-20 w-full overflow-hidden leading-0">
			<svg class="relative block h-20 w-full" viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M0,60 C200,120 350,0 600,60 C850,120 1000,0 1200,60 V120 H0 Z" class="fill-white"></path>
			</svg>
		</div>
	</section>

	<section class="relative z-30 -mt-18 pb-4">
		<div class="mx-auto grid max-w-4xl grid-cols-1 gap-3 px-4 sm:grid-cols-2 sm:px-6 lg:px-8">
			<div class="rounded-lg bg-white px-4 pb-5 pt-4 shadow-md">
				<p class="text-xs font-medium text-slate-500">Jumlah Program Kerja</p>
				<h2 class="mt-1 text-2xl font-bold text-green-800">{{ $proker->total() }}</h2>
			</div>
			<div class="rounded-lg bg-white px-4 pb-5 pt-4 shadow-md">
				<p class="text-xs font-medium text-slate-500">Tampil di Halaman Ini</p>
				<h2 class="mt-1 text-2xl font-bold text-green-800">{{ $proker->count() }}</h2>
			</div>
		</div>
	</section>

	<section class="py-6">
		<div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
			<div class="rounded-xl bg-white p-3 shadow-sm">
				<label for="prokerSearch" class="mb-2 block text-sm font-semibold text-slate-700">Cari Program Kerja</label>
				<div class="relative">
					<svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.3-4.3m1.3-5.2a7 7 0 11-14 0a7 7 0 0114 0z" /></svg>
					<input id="prokerSearch" type="search" placeholder="Cari nama program, target, atau penanggung jawab..." class="w-full rounded-lg border border-slate-200 py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-green-700 focus:ring-1 focus:ring-green-700">
				</div>
			</div>

			<div class="mt-5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
				<div class="overflow-x-auto">
					<table class="w-full min-w-[900px] text-left text-sm">
						<thead class="bg-linear-to-r from-green-950 via-green-900 to-emerald-800 text-xs uppercase tracking-wide text-white">
							<tr>
								<th class="border-r border-white/15 px-5 py-4 font-semibold">Program Kerja</th>
								<th class="border-r border-white/15 px-5 py-4 font-semibold">Anggaran</th>
								<th class="border-r border-white/15 px-5 py-4 font-semibold">Target</th>
								<th class="border-r border-white/15 px-5 py-4 font-semibold">Jadwal</th>
								<th class="border-r border-white/15 px-5 py-4 font-semibold">Penanggung Jawab</th>
								<th class="px-5 py-4 text-center font-semibold">Aksi</th>
							</tr>
						</thead>
						<tbody id="prokerList" class="divide-y divide-slate-100">
							@forelse ($proker as $item)
								<tr data-search="{{ strtolower($item->nama_proker . ' ' . $item->target . ' ' . $item->pj . ' ' . $item->sumber_dana) }}" class="proker-item align-top transition hover:bg-green-50/50">
									<td class="border-r border-slate-200 px-5 py-4">
										<a href="{{ route('public.program-kerja.show', $item->id) }}" class="font-semibold text-slate-900 hover:text-green-800">{{ $item->nama_proker }}</a>
										<p class="mt-1 text-xs text-slate-500">{{ $item->ppidPembantu?->nama ?? 'PPID Kota Batu' }}</p>
									</td>
									<td class="border-r border-slate-200 px-5 py-4 whitespace-nowrap font-semibold text-green-800">
										{{ $item->anggaran }}
										<span class="block text-xs font-normal text-slate-500">{{ $item->sumber_dana }}</span>
									</td>
									<td class="max-w-xs border-r border-slate-200 px-5 py-4 leading-6 text-slate-600">{{ $item->target }}</td>
									<td class="border-r border-slate-200 px-5 py-4 whitespace-nowrap text-slate-600">
										{{ $item->jadwal_pelaksanaan?->locale('id')->translatedFormat('d F Y') ?? '-' }}
									</td>
									<td class="border-r border-slate-200 px-5 py-4 text-slate-600">{{ $item->pj }}@if ($item->telp)<span class="block text-xs text-slate-400">{{ $item->telp }}</span>@endif</td>
									<td class="px-5 py-4 text-center">
										<a href="{{ route('public.program-kerja.show', $item->id) }}" class="inline-flex items-center rounded-lg bg-green-800 px-3 py-2 text-xs font-semibold text-white transition hover:bg-green-900">Detail</a>
									</td>
								</tr>
							@empty
								<tr><td colspan="6" class="px-5 py-12 text-center text-slate-500">Belum ada program kerja yang tersedia.</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
			<div id="prokerEmpty" class="mt-5 hidden rounded-xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center text-sm text-slate-500">Program kerja tidak ditemukan.</div>

			@if ($proker->hasPages())
				<div class="mt-8">{{ $proker->links() }}</div>
			@endif
		</div>
	</section>
@endsection

@push('scripts')
	<script>
		document.getElementById('prokerSearch')?.addEventListener('input', function () {
			const query = this.value.toLowerCase().trim();
			const items = document.querySelectorAll('.proker-item');
			let visible = 0;

			items.forEach((item) => {
				const matches = item.dataset.search.includes(query);
				item.classList.toggle('hidden', !matches);
				visible += matches ? 1 : 0;
			});

			document.getElementById('prokerEmpty')?.classList.toggle('hidden', visible !== 0 || items.length === 0);
		});
	</script>
@endpush
