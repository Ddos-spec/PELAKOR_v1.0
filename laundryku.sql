-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Feb 2025 pada 12.51
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laundryku`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `agen`
--

CREATE TABLE `agen` (
  `id_agen` int(11) NOT NULL,
  `nama_laundry` varchar(30) DEFAULT NULL,
  `nama_pemilik` varchar(30) DEFAULT NULL,
  `telp` varchar(13) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `kota` varchar(20) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `plat_driver` varchar(12) DEFAULT NULL,
  `foto` text NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `agen`
--

INSERT INTO `agen` (`id_agen`, `nama_laundry`, `nama_pemilik`, `telp`, `email`, `kota`, `alamat`, `plat_driver`, `foto`, `password`) VALUES
(17, 'pelakor kilat', 'guntur', '083457682536', 'kilat@gmail.com', 'sdfffd', 'esafcfecef', '21312', '679f3ff7cbdcf.jpg', '$2y$10$LVamXEoKr3.9QG1am8XF2uQXS9pIYZoLXt7xF2To.JSvWawTTO/Nm'),
(18, 'Pelakor Fresh', 'levi', '083457682536', 'fresh@gmail.com', 'hbvabdabh', 'gyjdchgd', '1231', '679f40108cb18.jpg', '$2y$10$xbte90xvoI516x7UhdAKnO9KDdJos6we5.XrGB3aIB46J990xz11S'),
(19, 'Pelakor bubble', 'king', '082346273412', 'bubble@gmail.com', 'dgvssdvd', 'gvdsbvsvs', '2313', '679f4029e0c7a.jpg', '$2y$10$lCuRaDBW6KxvbkKnBtkc.up6Jku8kyTxQBFC78Ca3qWWTzdwBKCwW'),
(20, 'Pelakor Clean', 'coki', '096216372151', 'clean@gmail.com', 'scaca', 'fcsacfadcsa', '123213', '679f404468194.jpg', '$2y$10$mCk62rD4.rU9Vyamb5CeFOJ5CgEALNza4QU02aNRfPob3cRniroBS'),
(21, 'cemerlang ', 'cemer', '1243141431', 'cemerlang@gmail.com', 'fafcafqwaf', 'cafafadfwa', '1231', '679f9d4f98daf.jpg', '$2y$10$paRwh.AZjljwp6QD7b3CJeX78Gbxk13ZcAuC9eaABzXeOPt1SlQPy'),
(22, 'bersinar', 'bersinar', '0865276316', 'bersinar@gmail.com', 'afadwwad', 'dawdwad', 'adafvaf', '679f9d9c64589.jpg', '$2y$10$gUS7.F8jX24EmUp/SJUg2OYQzT6I8m8cUwODrFhTZZJl20ZXcG.f2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cucian`
--

CREATE TABLE `cucian` (
  `id_cucian` int(11) NOT NULL,
  `id_agen` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `jenis` varchar(15) DEFAULT NULL,
  `total_item` int(11) DEFAULT NULL,
  `berat` double DEFAULT NULL,
  `alamat` varchar(100) NOT NULL,
  `catatan` text NOT NULL,
  `status_cucian` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cucian`
--

INSERT INTO `cucian` (`id_cucian`, `id_agen`, `id_pelanggan`, `tgl_mulai`, `tgl_selesai`, `jenis`, `total_item`, `berat`, `alamat`, `catatan`, `status_cucian`) VALUES
(14, 15, 15, '2025-02-02', '0000-00-00', 'komplit', 100, 10, 'ada dimana aja , jepang', 'cepet jadiin kalo ga dihancurin', 'Selesai'),
(15, 17, 17, '2025-02-02', '0000-00-00', 'komplit', 5, NULL, 'fasfcasca, sfcdad', '', 'Selesai'),
(16, 17, 17, '2025-02-02', '0000-00-00', 'komplit', 1000, 100, 'fasfcasca, sfcdad', '', 'Selesai'),
(17, 18, 17, '2025-02-02', '0000-00-00', 'komplit', 1000, NULL, 'fasfcasca, sfcdad', '', 'Selesai'),
(18, 18, 17, '2025-02-02', '0000-00-00', 'komplit', 1666, NULL, 'fasfcasca, sfcdad', '', 'Selesai'),
(19, 18, 17, '2025-02-02', '0000-00-00', 'komplit', 12312, NULL, 'fasfcasca, sfcdad', '', 'Selesai'),
(20, 18, 17, '2025-02-02', '0000-00-00', 'komplit', 1, 100, 'fasfcasca, sfcdad', '', 'Selesai'),
(21, 18, 17, '2025-02-02', '0000-00-00', 'komplit', 1, 100, 'fasfcasca, sfcdad', '', 'Selesai'),
(22, 18, 17, '2025-02-02', '0000-00-00', 'komplit', 1, NULL, 'fasfcasca, sfcdad', '', 'Penjemputan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `harga`
--

CREATE TABLE `harga` (
  `id_harga` int(11) NOT NULL,
  `jenis` varchar(30) NOT NULL,
  `id_agen` int(11) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `harga`
--

INSERT INTO `harga` (`id_harga`, `jenis`, `id_agen`, `harga`) VALUES
(49, 'cuci', 15, 5000),
(50, 'setrika', 15, 3000),
(51, 'komplit', 15, 7000),
(52, 'cuci', 16, 3500),
(53, 'setrika', 16, 5500),
(54, 'komplit', 16, 9000),
(55, 'cuci', 17, 5000),
(56, 'setrika', 17, 6000),
(57, 'komplit', 17, 11000),
(58, 'cuci', 18, 4000),
(59, 'setrika', 18, 3500),
(60, 'komplit', 18, 7500),
(61, 'cuci', 19, 10000),
(62, 'setrika', 19, 11000),
(63, 'komplit', 19, 21000),
(64, 'cuci', 20, 7000),
(65, 'setrika', 20, 8000),
(66, 'komplit', 20, 15000),
(67, 'cuci', 21, 1000),
(68, 'setrika', 21, 2000),
(69, 'komplit', 21, 3000),
(70, 'cuci', 22, 2000),
(71, 'setrika', 22, 2500),
(72, 'komplit', 22, 5000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `telp` varchar(13) DEFAULT NULL,
  `kota` varchar(20) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `foto` text NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `email`, `telp`, `kota`, `alamat`, `foto`, `password`) VALUES
(16, 'nagumo', 'nagumo@gmail.com', '0852416738425', 'sfff', 'dascef', '679f92aaf10e7.jpg', '$2y$10$.mFleJveky0htDVyUP7w9epQMPyJvdJd1.o6Klm1rLow8yI06xZHO'),
(17, 'sakamoto', 'sakamoto@gmail.com', '0837152732167', 'sfcdad', 'fasfcasca', '679f9340e709b.jpg', '$2y$10$8SrLOxH8xdez1GrIFS5bduqs49oLuEkarlrMvyN14A5XGY52ui8J2'),
(18, 'uzuki', 'uzuki@gmail.com', '0812321436', 'adawdwa', 'dwadwad', '679f937d333e1.jpg', '$2y$10$o9d0R4gw3/kbasIlLEp9U.PYGyzurCiCR2VyAOSOFdb3g2bJz8v1i'),
(19, 'sisiba', 'sisiba@gmail.com', '082143162365', 'afcvas', 'fsaasafqwa', '679f93ac689ec.jpg', '$2y$10$sQ.X7qF/0eC.yJi5pt2U2.HT5ran/KNP5lRy2oHujc5DMXA0ForZW'),
(20, 'osaragi', 'osaragi@gmail.com', '081231634', 'afcfvrg', 'sefecef', '679f93ff10102.jpg', '$2y$10$5U/fcEGOWRM3HNHDYmKnEu0r9gbu7ltVXzZpbxgW0.wPr5VzvyO4.'),
(21, 'rion', 'rion@gmail.com', '082143124', 'afcvfsfgew', 'fefescff', '679f97f949134.jpg', '$2y$10$l7B1ezX/ip6VYOwBKuFLv.oHa4cHrRSgK/wlWG6xgXk3QZzt.40di');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `kode_transaksi` int(11) NOT NULL,
  `id_cucian` int(11) NOT NULL,
  `id_agen` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `total_bayar` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `komentar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`kode_transaksi`, `id_cucian`, `id_agen`, `id_pelanggan`, `tgl_mulai`, `tgl_selesai`, `total_bayar`, `rating`, `komentar`) VALUES
(25, 14, 15, 15, '2025-02-02', '2025-02-02', 70000, 10, 'wah sangat bagus'),
(26, 15, 17, 17, '2025-02-02', '2025-02-02', 0, 8, 'mantap\r\n'),
(27, 16, 17, 17, '2025-02-02', '2025-02-02', 700000, 10, 'mantap'),
(28, 16, 17, 17, '2025-02-02', '2025-02-02', 700000, 2, 'gud'),
(29, 16, 17, 17, '2025-02-02', '2025-02-02', 700000, 10, 'gud'),
(30, 16, 17, 17, '2025-02-02', '2025-02-02', 700000, 8, 'gud'),
(31, 16, 17, 17, '2025-02-02', '2025-02-02', 700000, 10, 'gud'),
(32, 16, 17, 17, '2025-02-02', '2025-02-02', 700000, 10, 'gud'),
(33, 16, 17, 17, '2025-02-02', '2025-02-02', 700000, 8, 'gud'),
(34, 16, 17, 17, '2025-02-02', '2025-02-02', 700000, 6, 'gud'),
(35, 16, 17, 17, '2025-02-02', '2025-02-02', 700000, 10, 'gud'),
(36, 16, 17, 17, '2025-02-02', '2025-02-02', 700000, 8, 'gud'),
(37, 16, 17, 17, '2025-02-02', '2025-02-02', 700000, 6, 'gud'),
(38, 16, 17, 17, '2025-02-02', '2025-02-02', 700000, 10, 'gud'),
(39, 17, 18, 17, '2025-02-02', '2025-02-02', 0, 8, 'ad'),
(40, 18, 18, 17, '2025-02-02', '2025-02-02', 0, 6, 'adw'),
(41, 19, 18, 17, '2025-02-02', '2025-02-02', 0, 8, 'adaw'),
(42, 20, 18, 17, '2025-02-02', '2025-02-02', 700000, 0, ''),
(43, 21, 18, 17, '2025-02-02', '2025-02-02', 700000, 0, '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `agen`
--
ALTER TABLE `agen`
  ADD PRIMARY KEY (`id_agen`);

--
-- Indeks untuk tabel `cucian`
--
ALTER TABLE `cucian`
  ADD PRIMARY KEY (`id_cucian`);

--
-- Indeks untuk tabel `harga`
--
ALTER TABLE `harga`
  ADD PRIMARY KEY (`id_harga`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`kode_transaksi`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `agen`
--
ALTER TABLE `agen`
  MODIFY `id_agen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `cucian`
--
ALTER TABLE `cucian`
  MODIFY `id_cucian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `harga`
--
ALTER TABLE `harga`
  MODIFY `id_harga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `kode_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
