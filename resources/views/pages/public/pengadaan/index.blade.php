@extends('layouts.public.app')

@section('title', 'Pengadaan | PPID Kota Batu')

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
				<span class="text-yellow-500">Pengadaan</span>
			</h1>
			<p class="mx-auto mt-4 max-w-2xl text-xs leading-6 text-green-100 md:text-sm">
				Temukan informasi rencana pengadaan PPID Kota Batu secara<br class="hidden sm:inline">
				terbuka, mudah, dan terarah.
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
			<div class="rounded-lg bg-white px-4 pb-5 pt-4 shadow-md transition duration-300 hover:-translate-y-1">
				<div class="flex items-start justify-between">
					<div>
						<p class="text-xs font-medium text-slate-500">Jumlah Paket</p>
						<h2 class="mt-1 text-2xl font-bold text-green-800">{{ $pengadaan->total() }}</h2>
					</div>
					<div class="flex h-9 w-9 items-center justify-center rounded-full bg-linear-to-br from-green-950 via-green-900 to-emerald-700 text-white" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M8 4h7l5 5v11a2 2 0 01-2 2H8a2 2 0 01-2-2V6a2 2 0 012-2z" /></svg>
					</div>
				</div>
			</div>
			<div class="rounded-lg bg-white px-4 pb-5 pt-4 shadow-md transition duration-300 hover:-translate-y-1">
				<div class="flex items-start justify-between">
					<div>
						<p class="text-xs font-medium text-slate-500">Tampil di Halaman Ini</p>
						<h2 class="mt-1 text-2xl font-bold text-green-800">{{ $pengadaan->count() }}</h2>
					</div>
					<div class="flex h-9 w-9 items-center justify-center rounded-full bg-linear-to-br from-green-950 via-green-900 to-emerald-700 text-white" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="py-6">
		<div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
			<div class="rounded-xl bg-white p-3 shadow-sm">
				<label for="pengadaanSearch" class="mb-2 block text-sm font-semibold text-slate-700">Cari Informasi Pengadaan</label>
				<div class="relative">
					<svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.3-4.3m1.3-5.2a7 7 0 11-14 0a7 7 0 0114 0z" /></svg>
					<input id="pengadaanSearch" type="search" placeholder="Cari nama paket, metode, atau sumber dana..." class="w-full rounded-lg border border-slate-200 py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-green-700 focus:ring-1 focus:ring-green-700">
				</div>
			</div>

			<div id="pengadaanList" class="mt-5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
				<div class="overflow-x-auto">
					<table class="w-full min-w-[950px] text-left text-sm">
						<thead class="bg-linear-to-r from-green-950 via-green-900 to-emerald-800 text-xs uppercase tracking-wide text-white">
							<tr>
								<th class="border-r border-white/15 px-5 py-4 font-semibold">Nama Paket</th>
								<th class="border-r border-white/15 px-5 py-4 font-semibold">Pagu Anggaran</th>
								<th class="border-r border-white/15 px-5 py-4 font-semibold">Sumber Dana</th>
								<th class="border-r border-white/15 px-5 py-4 font-semibold">Metode</th>
								<th class="border-r border-white/15 px-5 py-4 font-semibold">Unit Penanggung Jawab</th>
								<th class="px-5 py-4 text-center font-semibold">Aksi</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-slate-100">
							@forelse ($pengadaan as $item)
								<tr data-search="{{ strtolower($item->nama_paket . ' ' . $item->metode . ' ' . $item->sumber_dana . ' ' . $item->rencana_kegiatan . ' ' . ($item->ppidPembantu?->nama ?? '')) }}" class="pengadaan-item align-top transition hover:bg-green-50/50">
									<td class="border-r border-slate-200 px-5 py-4">
										<a href="{{ route('public.pengadaan.show', $item->id) }}" class="font-semibold text-slate-900 hover:text-green-800">{{ $item->nama_paket }}</a>
										<p class="mt-1 max-w-xs text-xs leading-5 text-slate-500">{{ $item->rencana_kegiatan }}</p>
									</td>
									<td class="whitespace-nowrap border-r border-slate-200 px-5 py-4 font-semibold text-green-800">{{ $item->pagu_rupiah }}</td>
									<td class="border-r border-slate-200 px-5 py-4 text-slate-600">{{ $item->sumber_dana }}</td>
									<td class="border-r border-slate-200 px-5 py-4"><span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-800">{{ $item->metode }}</span></td>
									<td class="border-r border-slate-200 px-5 py-4 text-slate-600">{{ $item->ppidPembantu?->nama ?? 'PPID Kota Batu' }}</td>
									<td class="px-5 py-4 text-center"><a href="{{ route('public.pengadaan.show', $item->id) }}" class="inline-flex items-center rounded-lg bg-green-800 px-3 py-2 text-xs font-semibold text-white transition hover:bg-green-900">Detail</a></td>
								</tr>
							@empty
								<tr><td colspan="6" class="px-5 py-12 text-center text-slate-500">Belum ada data pengadaan yang tersedia.</td></tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
			<div id="pengadaanEmpty" class="mt-5 hidden rounded-xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center text-sm text-slate-500">Informasi pengadaan tidak ditemukan.</div>

			@if ($pengadaan->hasPages())
				<div class="mt-8">{{ $pengadaan->links() }}</div>
			@endif
		</div>
	</section>
@endsection

@push('scripts')
	<script>
		document.getElementById('pengadaanSearch')?.addEventListener('input', function () {
			const query = this.value.toLowerCase().trim();
			const items = document.querySelectorAll('.pengadaan-item');
			let visible = 0;

			items.forEach((item) => {
				const matches = item.dataset.search.includes(query);
				item.classList.toggle('hidden', !matches);
				visible += matches ? 1 : 0;
			});

			document.getElementById('pengadaanEmpty')?.classList.toggle('hidden', visible !== 0 || items.length === 0);
		});
	</script>
@endpush
