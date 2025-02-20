-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Feb 2025 pada 05.06
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
(15, 'Pelakor Kilat', 'Kilat', '14314123', 'kilat@gmail.com', 'adfqawda', 'awdadawd', '12313', 'default.png', '$2y$10$6us22rh5XtBLOtHV6vhGPe26BOm/M8f/ty9xUyiJBCucfxqDkel5y');

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
  `item_type` varchar(30) DEFAULT NULL,
  `total_item` int(11) DEFAULT NULL,
  `preview_price` decimal(10,2) DEFAULT NULL,
  `berat` double DEFAULT NULL,
  `alamat` varchar(100) NOT NULL,
  `catatan` text NOT NULL,
  `status_cucian` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cucian`
--

INSERT INTO `cucian` (`id_cucian`, `id_agen`, `id_pelanggan`, `tgl_mulai`, `tgl_selesai`, `jenis`, `item_type`, `total_item`, `preview_price`, `berat`, `alamat`, `catatan`, `status_cucian`) VALUES
(11, 15, 15, '2025-02-19', '0000-00-00', 'komplit', 'Baju (1), Jaket (1)', 2, NULL, 10, 'dsfwsfegfweag, awdadawfda', '', 'Selesai'),
(12, 15, 15, '2025-02-19', '0000-00-00', 'komplit', 'Baju (5), Celana (5)', 10, NULL, 10, 'dsfwsfegfweag, awdadawfda', '', 'Selesai'),
(13, 15, 15, '2025-02-20', '0000-00-00', 'komplit', 'Baju (5), Karpet (1)', 6, NULL, 50, 'dsfwsfegfweag, awdadawfda', '', 'Selesai'),
(14, 15, 15, '2025-02-20', '0000-00-00', 'setrika', 'Baju (4), Celana (4)', 8, NULL, 5, 'dsfwsfegfweag, awdadawfda', '', 'Selesai'),
(15, 15, 15, '2025-02-20', '2025-02-20', 'cuci', 'Baju (3), Celana (3)', 6, NULL, 5, 'dsfwsfegfweag, awdadawfda', '', 'Selesai'),
(16, 15, 15, '2025-02-20', '2025-02-20', 'komplit', 'Celana (1), Jaket (1)', 2, NULL, 10, 'dsfwsfegfweag, awdadawfda', '', 'Selesai'),
(17, 15, 15, '2025-02-20', '2025-02-20', 'komplit', 'Karpet (1), Pakaian_khusus (1)', 2, NULL, 5, 'dsfwsfegfweag, awdadawfda', '', 'Selesai'),
(18, 15, 15, '2025-02-20', '2025-02-20', 'komplit', 'Baju (2), Celana (2)', 4, NULL, 100, 'dsfwsfegfweag, awdadawfda', '', 'Selesai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `harga`
--

CREATE TABLE `harga` (
  `id_harga` int(11) NOT NULL,
  `jenis` varchar(30) NOT NULL,
  `id_agen` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `harga_baju` int(11) DEFAULT NULL,
  `harga_celana` int(11) DEFAULT NULL,
  `harga_jaket` int(11) DEFAULT NULL,
  `harga_karpet` int(11) DEFAULT NULL,
  `harga_pakaian_khusus` int(11) DEFAULT NULL
) ;

--
-- Dumping data untuk tabel `harga`
--

INSERT INTO `harga` (`id_harga`, `jenis`, `id_agen`, `harga`, `harga_baju`, `harga_celana`, `harga_jaket`, `harga_karpet`, `harga_pakaian_khusus`) VALUES
(39, 'cuci', 15, 2000, NULL, NULL, NULL, NULL, NULL),
(40, 'setrika', 15, 3000, NULL, NULL, NULL, NULL, NULL),
(41, 'komplit', 15, 5000, NULL, NULL, NULL, NULL, NULL),
(42, 'baju', 15, 2000, NULL, NULL, NULL, NULL, NULL),
(43, 'celana', 15, 3000, NULL, NULL, NULL, NULL, NULL),
(44, 'jaket', 15, 4000, NULL, NULL, NULL, NULL, NULL),
(45, 'karpet', 15, 6000, NULL, NULL, NULL, NULL, NULL),
(46, 'pakaian_khusus', 15, 5000, NULL, NULL, NULL, NULL, NULL);

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
(15, 'sakamoto', 'sakamoto@gmail.com', '079869687685', 'awdadawfda', 'dsfwsfegfweag', 'default.png', '$2y$10$TCqkcBF5R85p/si40iA4FuPpMeN7wdL4AbIRTRWs0v7B04b2/VHM6');

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
  `payment_status` enum('Pending','Paid','Failed') DEFAULT 'Pending',
  `rating` int(11) DEFAULT NULL,
  `komentar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`kode_transaksi`, `id_cucian`, `id_agen`, `id_pelanggan`, `tgl_mulai`, `tgl_selesai`, `total_bayar`, `payment_status`, `rating`, `komentar`) VALUES
(24, 13, 15, 15, '2025-02-20', '0000-00-00', 266000, 'Paid', NULL, '0'),
(25, 14, 15, 15, '2025-02-20', '2025-02-20', 35000, 'Paid', NULL, '0'),
(26, 15, 15, 15, '2025-02-20', '2025-02-20', 25000, 'Paid', NULL, '0'),
(27, 16, 15, 15, '2025-02-20', '2025-02-20', 57000, 'Paid', NULL, '0'),
(28, 17, 15, 15, '2025-02-20', '2025-02-20', 36000, 'Paid', NULL, '0'),
(29, 18, 15, 15, '2025-02-20', '2025-02-20', 510000, 'Paid', NULL, '0');

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
  MODIFY `id_agen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `cucian`
--
ALTER TABLE `cucian`
  MODIFY `id_cucian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `harga`
--
ALTER TABLE `harga`
  MODIFY `id_harga` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `kode_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
