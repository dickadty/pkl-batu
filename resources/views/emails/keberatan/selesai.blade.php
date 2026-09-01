<h2>Hasil Keberatan Tersedia</h2>
<p>Keberatan <strong>{{ $keberatan->no_keberatan }}</strong> telah selesai diproses.</p>
<p><strong>Hasil:</strong> {{ $keberatan->hasil ?: '-' }}</p>
<p>Silakan buka tautan berikut untuk melihat tanggapan dan dokumen resmi:</p>
<p><a href="{{ $detailUrl }}">Lihat Hasil Keberatan</a></p>
