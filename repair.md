Anda adalah Senior Laravel Developer yang bertanggung jawab memperbaiki sistem PPID Kota Batu berbasis Laravel.

Saya memiliki fitur Permohonan Informasi Publik. Saat ini admin dapat melakukan pengecekan permohonan, tetapi belum tersedia fitur untuk MENOLAK permohonan apabila data pemohon tidak sesuai, tidak valid, atau tidak lengkap.

Tolong implementasikan fitur penolakan permohonan secara lengkap dan mengikuti struktur project yang sudah ada.

KETENTUAN:

1. Jangan membuat struktur baru yang tidak diperlukan.
2. Ikuti style coding yang sudah digunakan pada project.
3. Gunakan controller, model, migration, route, blade, dan notification yang sudah tersedia.
4. Jangan menghapus fitur lama.
5. Pastikan kompatibel dengan Laravel versi project.
6. Berikan codingan lengkap setiap file yang berubah.

FITUR YANG HARUS DIBUAT:

==================================================
1. ROUTE
==================================================

Tambahkan route admin:

POST:
admin/permohonan/{id}/tolak

name:
permohonan.tolak

Gunakan middleware dan role yang sama dengan aksi validasi/revisi permohonan.

Contoh pola:

admin.role:1

==================================================
2. CONTROLLER
==================================================

Edit:

App\Http\Controllers\Admin\PermohonanController.php

Tambahkan function:

public function tolak(Request $request, $id)

Fungsi harus:

- Validasi alasan penolakan wajib diisi.
- Ambil data permohonan berdasarkan ID.
- Update status menjadi:

"ditolak"

- Simpan:
  - alasan_penolakan
  - tanggal_penolakan
  - ditolak_oleh

- Buat notifikasi kepada pemohon.
- Kirim email pemberitahuan penolakan jika sistem email sudah tersedia.
- Redirect kembali dengan pesan sukses.

Contoh pesan:

"Permohonan berhasil ditolak."

==================================================
3. DATABASE
==================================================

Periksa tabel permohonan.

Jika belum ada field berikut:

alasan_penolakan
tanggal_penolakan
ditolak_oleh

buat migration baru.

Tambahkan relasi jika diperlukan:

ditolak_oleh -> users/admin.

Jangan mengubah field lama.

==================================================
4. MODEL
==================================================

Update:

App\Models\Permohonan.php

Tambahkan field baru ke:

$fillable

Tambahkan casting:

tanggal_penolakan => datetime

==================================================
5. HALAMAN ADMIN DETAIL PERMOHONAN
==================================================

Edit:

resources/views/pages/admin/permohonan/show.blade.php

Tambahkan tombol:

"Tolak Permohonan"

Tombol hanya muncul jika:

- status belum selesai
- user memiliki hak akses admin

Saat diklik tampilkan modal:

Judul:
"Tolak Permohonan"

Input textarea:

Alasan Penolakan

Contoh:
"Data identitas tidak sesuai dengan dokumen yang diberikan."

Submit ke:

route('admin.permohonan.tolak')

atau sesuaikan dengan naming route project.

==================================================
6. TAMPILAN STATUS ADMIN
==================================================

Tambahkan status baru:

DITOLAK

dengan badge berbeda.

Jika status ditolak tampilkan:

Status:
Ditolak

Alasan:
xxxxxxxx

Tanggal:
xxxxxxxx

Oleh:
Admin xxx

==================================================
7. HALAMAN PUBLIK CEK PERMOHONAN
==================================================

Edit halaman:

resources/views/pages/public/permohonan/show.blade.php

Jika status:

ditolak

tampilkan:

================================

Status Permintaan Informasi:
DITOLAK

Alasan Penolakan:
{alasan_penolakan}

Silakan memperbaiki data apabila layanan tersedia.

================================


==================================================
8. EMAIL NOTIFICATION
==================================================

Buat template email:

Subject:

"Permintaan Informasi Publik Tidak Dapat Diproses"

Isi:

Yth. Pemohon,

Permintaan Informasi Publik dengan nomor registrasi:

{nomor_registrasi}

tidak dapat diproses.

Alasan:

{alasan_penolakan}

Terima kasih.

==================================================
9. TESTING
==================================================

Setelah selesai:

Jalankan:

php artisan optimize:clear

php artisan route:list | grep permohonan

Pastikan muncul:

POST admin/permohonan/{id}/tolak

Pastikan tidak ada error:

Route not defined
Undefined method
Column not found


==================================================
OUTPUT YANG SAYA INGINKAN
==================================================

Berikan:

1. File route lengkap yang berubah.
2. Controller lengkap function yang ditambahkan.
3. Migration lengkap.
4. Model lengkap bagian perubahan.
5. Blade admin bagian perubahan.
6. Blade publik bagian perubahan.
7. Notification/email jika diperlukan.

Jangan memberikan potongan kode saja.
Berikan kode final yang siap copy paste.