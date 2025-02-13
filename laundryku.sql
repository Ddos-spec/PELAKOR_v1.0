-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Feb 2025 pada 14.23
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
  `password` varchar(255) DEFAULT NULL,
  `harga_kiloan` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `agen`
--

INSERT INTO `agen` (`id_agen`, `nama_laundry`, `nama_pemilik`, `telp`, `email`, `kota`, `alamat`, `plat_driver`, `foto`, `password`, `harga_kiloan`) VALUES
(17, 'pelakor kilat', 'guntur', '083457682536', 'kilat@gmail.com', 'sdfffd', 'esafcfecef', '21312', '679f3ff7cbdcf.jpg', '$2y$10$LVamXEoKr3.9QG1am8XF2uQXS9pIYZoLXt7xF2To.JSvWawTTO/Nm', 0),
(18, 'Pelakor Fresh', 'levi', '083457682536', 'fresh@gmail.com', 'hbvabdabh', 'gyjdchgd', '1231', '679f40108cb18.jpg', '$2y$10$xbte90xvoI516x7UhdAKnO9KDdJos6we5.XrGB3aIB46J990xz11S', 0),
(19, 'Pelakor bubble', 'king', '082346273412', 'bubble@gmail.com', 'dgvssdvd', 'gvdsbvsvs', '2313', '679f4029e0c7a.jpg', '$2y$10$lCuRaDBW6KxvbkKnBtkc.up6Jku8kyTxQBFC78Ca3qWWTzdwBKCwW', 0),
(20, 'Pelakor Clean', 'coki', '096216372151', 'clean@gmail.com', 'scaca', 'fcsacfadcsa', '123213', '679f404468194.jpg', '$2y$10$mCk62rD4.rU9Vyamb5CeFOJ5CgEALNza4QU02aNRfPob3cRniroBS', 0),
(21, 'Pelakor cemerlang ', 'cemer', '1243141431', 'cemerlang@gmail.com', 'fafcafqwaf', 'cafafadfwa', '1231', '679f9d4f98daf.jpg', '$2y$10$Z1/.1kGb8xmqSTQ92kG7AuCMoY8hnC0xdx4y85lXTaPk6f8Tp3NrK', 0),
(22, 'Pelakor bersinar', 'bersinar', '0865276316', 'bersinar@gmail.com', 'afadwwad', 'dawdwad', 'adafvaf', '679f9d9c64589.jpg', '$2y$10$pzATqcMWJaxHFUhzsS1tAeiQfETALGbdiA8bNXApN5wh.g/jI5TM6', 0);

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
(17, 'sakamoto', 'sakamoto@gmail.com', '0837152732167', 'sfcdad', 'fasfcasca', '679f9340e709b.jpg', '$2y$10$Am.V5AeggTtKVoeYKIsJDeZgdJdznDJkzDOHPxt/wU6yf26hf5IyK'),
(18, 'uzuki', 'uzuki@gmail.com', '0812321436', 'adawdwa', 'dwadwad', '679f937d333e1.jpg', '$2y$10$o9d0R4gw3/kbasIlLEp9U.PYGyzurCiCR2VyAOSOFdb3g2bJz8v1i'),
(19, 'sisiba', 'sisiba@gmail.com', '082143162365', 'afcvas', 'fsaasafqwa', '679f93ac689ec.jpg', '$2y$10$sQ.X7qF/0eC.yJi5pt2U2.HT5ran/KNP5lRy2oHujc5DMXA0ForZW'),
(20, 'osaragi', 'osaragi@gmail.com', '081231634', 'afcfvrg', 'sefecef', '679f93ff10102.jpg', '$2y$10$5U/fcEGOWRM3HNHDYmKnEu0r9gbu7ltVXzZpbxgW0.wPr5VzvyO4.'),
(21, 'rion', 'rion@gmail.com', '082143124', 'afcvfsfgew', 'fefescff', '679f97f949134.jpg', '$2y$10$9aCv5ItZuLxzA95qb4QjMehw2Uo6YG.c.5o2jIe.UORc1hgBTGng6');

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
  `komentar` text NOT NULL,
  `tipe_transaksi` enum('kiloan','satuan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `id_cucian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `harga`
--
ALTER TABLE `harga`
  MODIFY `id_harga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `kode_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
