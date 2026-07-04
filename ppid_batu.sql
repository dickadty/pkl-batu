-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 04, 2026 at 08:25 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ppid_batu`
--

-- --------------------------------------------------------

--
-- Table structure for table `authorization`
--

CREATE TABLE `authorization` (
  `id` int(10) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(500) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` int(1) NOT NULL,
  `user_publikid` int(10) DEFAULT NULL,
  `ppid_pembantuid` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authorization`
--

INSERT INTO `authorization` (`id`, `username`, `password`, `email`, `role`, `user_publikid`, `ppid_pembantuid`) VALUES
(1, 'adminutama', '$2y$12$1u7QB4by64tY4fHFeobNf.JjEUUZESt2cdUi6KlntTbI/9V8py3G2', 'adminutama@ppid.test', 1, NULL, NULL),
(2, 'adminpembantu', '$2y$12$/uj7.0w3E5mpHkpm3C1McOWGoIMP8tH2IerHh3FII9uDKI/jrcqRy', 'adminpembantu@ppid.test', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `balas_pesan`
--

CREATE TABLE `balas_pesan` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subjek` varchar(100) DEFAULT NULL,
  `pesan` varchar(1000) DEFAULT NULL,
  `tanggal` int(10) DEFAULT NULL,
  `pesan_masukid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `judul` varchar(500) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `gambar` varchar(250) DEFAULT NULL,
  `tanggal` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokumentasi`
--

CREATE TABLE `dokumentasi` (
  `id` int(11) NOT NULL,
  `nama` varchar(250) NOT NULL,
  `tahun` smallint(6) DEFAULT NULL,
  `ringkasan` text DEFAULT NULL,
  `file` varchar(500) DEFAULT NULL,
  `tanggal` int(10) DEFAULT NULL,
  `sifat` varchar(50) DEFAULT NULL,
  `is_verifikasi` int(11) DEFAULT 0,
  `slug` varchar(500) DEFAULT NULL,
  `ppid_pembantuid` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `download`
--

CREATE TABLE `download` (
  `id` int(11) NOT NULL,
  `tujuan` varchar(500) DEFAULT NULL,
  `tanggal` int(10) DEFAULT NULL,
  `user_publikid` int(10) DEFAULT NULL,
  `dokumentasiid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `tanya` varchar(500) NOT NULL,
  `jawab` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_ppid`
--

CREATE TABLE `kategori_ppid` (
  `id` int(10) NOT NULL,
  `kategori` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_ppid`
--

INSERT INTO `kategori_ppid` (`id`, `kategori`) VALUES
(1, 'Dinas'),
(2, 'Kecamatan'),
(3, 'Kelurahan'),
(4, 'BUMD'),
(5, 'Lainnya'),
(6, 'OPD');

-- --------------------------------------------------------

--
-- Table structure for table `keberatan`
--

CREATE TABLE `keberatan` (
  `id` int(10) NOT NULL,
  `alasan` varchar(500) DEFAULT NULL,
  `permohonanid` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `realisasi` varchar(500) DEFAULT NULL,
  `metode` varchar(250) DEFAULT NULL,
  `cv_pemenang` varchar(200) DEFAULT NULL,
  `no_kontrak` varchar(20) DEFAULT NULL,
  `ppid_pembantuid` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pejabat`
--

CREATE TABLE `pejabat` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) DEFAULT NULL,
  `jabatan` varchar(250) DEFAULT NULL,
  `masa` varchar(50) DEFAULT NULL,
  `tmp_tgl_lahir` varchar(100) DEFAULT NULL,
  `alamat` varchar(250) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `foto` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengadaan`
--

CREATE TABLE `pengadaan` (
  `id` int(11) NOT NULL,
  `nama_paket` varchar(200) DEFAULT NULL,
  `pagu` varchar(250) DEFAULT NULL,
  `sumber_dana` varchar(250) DEFAULT NULL,
  `metode` varchar(250) DEFAULT NULL,
  `rencana_kegiatan` varchar(500) DEFAULT NULL,
  `ppid_pembantuid` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permohonan`
--

CREATE TABLE `permohonan` (
  `id` int(10) NOT NULL,
  `no_pemohon` int(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `rincian` varchar(500) DEFAULT NULL,
  `tujuan` varchar(500) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `user_publikid` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesan_masuk`
--

CREATE TABLE `pesan_masuk` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pesan` varchar(1000) NOT NULL,
  `status` int(11) DEFAULT 0,
  `tanggal` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ppid_pembantu`
--

CREATE TABLE `ppid_pembantu` (
  `id` int(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `keterangan` varchar(500) DEFAULT NULL,
  `kategori_ppidid` int(10) DEFAULT NULL,
  `linkweb` varchar(100) DEFAULT NULL,
  `telp` varchar(15) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `slug` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ppid_pembantu`
--

INSERT INTO `ppid_pembantu` (`id`, `nama`, `keterangan`, `kategori_ppidid`, `linkweb`, `telp`, `alamat`, `icon`, `slug`) VALUES
(1, 'Dinas Kominfo Kota Batu', 'Pengelola PPID Kota Batu', 1, 'https://kominfo.batukota.go.id', '0341511111', 'Jl. Panglima Sudirman', 'kominfo.png', 'dinas-kominfo'),
(2, 'Kecamatan Batu', 'PPID Kecamatan Batu', 2, 'https://kecbatu.batukota.go.id', '0341511112', 'Jl. Diponegoro', 'kecbatu.png', 'kecamatan-batu'),
(3, 'Kecamatan Junrejo', 'PPID Kecamatan Junrejo', 2, 'https://junrejo.batukota.go.id', '0341511113', 'Jl. Raya Junrejo', 'junrejo.png', 'kecamatan-junrejo'),
(4, 'Kecamatan Bumiaji', 'PPID Kecamatan Bumiaji', 2, 'https://bumiaji.batukota.go.id', '0341511114', 'Jl. Raya Bumiaji', 'bumiaji.png', 'kecamatan-bumiaji'),
(5, 'Kelurahan Sisir', 'PPID Kelurahan Sisir', 3, 'https://sisir.batukota.go.id', '0341511115', 'Jl. Sultan Agung', 'sisir.png', 'kelurahan-sisir');

-- --------------------------------------------------------

--
-- Table structure for table `proker`
--

CREATE TABLE `proker` (
  `id` int(11) NOT NULL,
  `nama_proker` varchar(200) DEFAULT NULL,
  `anggaran` varchar(100) DEFAULT NULL,
  `sumber_dana` varchar(250) DEFAULT NULL,
  `target` varchar(500) DEFAULT NULL,
  `jadwal_pelaksanaan` date DEFAULT NULL,
  `pj` varchar(100) DEFAULT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `dokumen` varchar(500) DEFAULT NULL,
  `slug` varchar(250) DEFAULT NULL,
  `ppid_pembantuid` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `banner` varchar(500) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `tanggal` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_publik`
--

CREATE TABLE `user_publik` (
  `id` int(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `scanktp` blob DEFAULT NULL,
  `l_kelamin` varchar(10) DEFAULT NULL,
  `tmp_lahir` varchar(50) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `pekerjaan` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `desa_kel` varchar(50) DEFAULT NULL,
  `kecamatan` varchar(50) DEFAULT NULL,
  `kota_kab` varchar(50) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `provinsi` varchar(50) DEFAULT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `hint` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `is_aktif` int(1) DEFAULT 1,
  `wilayahkode` varchar(13) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_publik`
--

INSERT INTO `user_publik` (`id`, `nama`, `nik`, `scanktp`, `l_kelamin`, `tmp_lahir`, `tgl_lahir`, `pekerjaan`, `alamat`, `desa_kel`, `kecamatan`, `kota_kab`, `kode_pos`, `provinsi`, `telp`, `email`, `hint`, `password`, `is_aktif`, `wilayahkode`) VALUES
(1, 'Andi Saputra', '3579010101010001', NULL, 'Laki-laki', 'Batu', '1998-02-12', 'Mahasiswa', 'Jl. Mawar No.1', 'Sisir', 'Batu', 'Kota Batu', '65311', 'Jawa Timur', '081111111111', 'andi@gmail.com', 'nama ibu', 'admin123', 1, '357901000001'),
(2, 'Budi Santoso', '3579010101010002', NULL, 'Laki-laki', 'Malang', '1995-04-21', 'Wiraswasta', 'Jl. Melati No.5', 'Ngaglik', 'Batu', 'Kota Batu', '65312', 'Jawa Timur', '081111111112', 'budi@gmail.com', 'nama ibu', '123456', 1, '357901000002'),
(3, 'Siti Lestari', '3579010101010003', NULL, 'Perempuan', 'Batu', '1999-07-10', 'Guru', 'Jl. Kenanga No.8', 'Temas', 'Batu', 'Kota Batu', '65313', 'Jawa Timur', '081111111113', 'siti@gmail.com', 'nama ibu', '123456', 1, '357901000003'),
(4, 'Dewi Ayu', '3579010101010004', NULL, 'Perempuan', 'Surabaya', '1996-06-17', 'ASN', 'Jl. Anggrek No.10', 'Junrejo', 'Junrejo', 'Kota Batu', '65314', 'Jawa Timur', '081111111114', 'dewi@gmail.com', 'nama ibu', '123456', 1, '357901000004'),
(5, 'Eko Prasetyo', '3579010101010005', NULL, 'Laki-laki', 'Kediri', '1994-08-25', 'Pegawai Swasta', 'Jl. Dahlia No.15', 'Bumiaji', 'Bumiaji', 'Kota Batu', '65315', 'Jawa Timur', '081111111115', 'eko@gmail.com', 'nama ibu', '123456', 1, '357901000005');

-- --------------------------------------------------------

--
-- Table structure for table `wilayah`
--

CREATE TABLE `wilayah` (
  `kode` varchar(13) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wilayah`
--

INSERT INTO `wilayah` (`kode`, `nama`) VALUES
('357901000001', 'Kota Batu'),
('357901000002', 'Kecamatan Batu'),
('357901000003', 'Kecamatan Junrejo'),
('357901000004', 'Kecamatan Bumiaji'),
('357901000005', 'Kecamatan Sisir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authorization`
--
ALTER TABLE `authorization`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_auth_user` (`user_publikid`),
  ADD KEY `fk_auth_ppid` (`ppid_pembantuid`);

--
-- Indexes for table `balas_pesan`
--
ALTER TABLE `balas_pesan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_balas_pesan_masuk` (`pesan_masukid`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dokumentasi`
--
ALTER TABLE `dokumentasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dokumentasi_ppid` (`ppid_pembantuid`);

--
-- Indexes for table `download`
--
ALTER TABLE `download`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_download_user` (`user_publikid`),
  ADD KEY `fk_download_dokumentasi` (`dokumentasiid`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_ppid`
--
ALTER TABLE `kategori_ppid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keberatan`
--
ALTER TABLE `keberatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_keberatan_permohonan` (`permohonanid`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_laporan_ppid` (`ppid_pembantuid`);

--
-- Indexes for table `pejabat`
--
ALTER TABLE `pejabat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengadaan`
--
ALTER TABLE `pengadaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pengadaan_ppid` (`ppid_pembantuid`);

--
-- Indexes for table `permohonan`
--
ALTER TABLE `permohonan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_pemohon` (`no_pemohon`),
  ADD KEY `fk_permohonan_user` (`user_publikid`);

--
-- Indexes for table `pesan_masuk`
--
ALTER TABLE `pesan_masuk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppid_pembantu`
--
ALTER TABLE `ppid_pembantu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `linkweb` (`linkweb`),
  ADD KEY `fk_ppid_kategori` (`kategori_ppidid`);

--
-- Indexes for table `proker`
--
ALTER TABLE `proker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_proker_ppid` (`ppid_pembantuid`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_publik`
--
ALTER TABLE `user_publik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `telp` (`telp`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_user_wilayah` (`wilayahkode`);

--
-- Indexes for table `wilayah`
--
ALTER TABLE `wilayah`
  ADD PRIMARY KEY (`kode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authorization`
--
ALTER TABLE `authorization`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `balas_pesan`
--
ALTER TABLE `balas_pesan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dokumentasi`
--
ALTER TABLE `dokumentasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `download`
--
ALTER TABLE `download`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_ppid`
--
ALTER TABLE `kategori_ppid`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `keberatan`
--
ALTER TABLE `keberatan`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pejabat`
--
ALTER TABLE `pejabat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengadaan`
--
ALTER TABLE `pengadaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permohonan`
--
ALTER TABLE `permohonan`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesan_masuk`
--
ALTER TABLE `pesan_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppid_pembantu`
--
ALTER TABLE `ppid_pembantu`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `proker`
--
ALTER TABLE `proker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_publik`
--
ALTER TABLE `user_publik`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `authorization`
--
ALTER TABLE `authorization`
  ADD CONSTRAINT `fk_auth_ppid` FOREIGN KEY (`ppid_pembantuid`) REFERENCES `ppid_pembantu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_auth_user` FOREIGN KEY (`user_publikid`) REFERENCES `user_publik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `balas_pesan`
--
ALTER TABLE `balas_pesan`
  ADD CONSTRAINT `fk_balas_pesan_masuk` FOREIGN KEY (`pesan_masukid`) REFERENCES `pesan_masuk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dokumentasi`
--
ALTER TABLE `dokumentasi`
  ADD CONSTRAINT `fk_dokumentasi_ppid` FOREIGN KEY (`ppid_pembantuid`) REFERENCES `ppid_pembantu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `download`
--
ALTER TABLE `download`
  ADD CONSTRAINT `fk_download_dokumentasi` FOREIGN KEY (`dokumentasiid`) REFERENCES `dokumentasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_download_user` FOREIGN KEY (`user_publikid`) REFERENCES `user_publik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `keberatan`
--
ALTER TABLE `keberatan`
  ADD CONSTRAINT `fk_keberatan_permohonan` FOREIGN KEY (`permohonanid`) REFERENCES `permohonan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `fk_laporan_ppid` FOREIGN KEY (`ppid_pembantuid`) REFERENCES `ppid_pembantu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pengadaan`
--
ALTER TABLE `pengadaan`
  ADD CONSTRAINT `fk_pengadaan_ppid` FOREIGN KEY (`ppid_pembantuid`) REFERENCES `ppid_pembantu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `permohonan`
--
ALTER TABLE `permohonan`
  ADD CONSTRAINT `fk_permohonan_user` FOREIGN KEY (`user_publikid`) REFERENCES `user_publik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ppid_pembantu`
--
ALTER TABLE `ppid_pembantu`
  ADD CONSTRAINT `fk_ppid_kategori` FOREIGN KEY (`kategori_ppidid`) REFERENCES `kategori_ppid` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `proker`
--
ALTER TABLE `proker`
  ADD CONSTRAINT `fk_proker_ppid` FOREIGN KEY (`ppid_pembantuid`) REFERENCES `ppid_pembantu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_publik`
--
ALTER TABLE `user_publik`
  ADD CONSTRAINT `fk_user_wilayah` FOREIGN KEY (`wilayahkode`) REFERENCES `wilayah` (`kode`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
