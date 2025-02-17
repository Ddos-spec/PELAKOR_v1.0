-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Feb 2025 pada 08.01
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
(13, 'Pelakor Kilat', 'kilat', '524352342', 'kilat@gmail.com', 'nbasdkbasjkhgdjk', 'jkahbvkdada', '2143131', 'default.png', '$2y$10$K7H3PqwyVNhZUrFuIF3ix.xpa9LeA77mHZ2S7r23v9IXOKhHIOGGC'),
(14, 'bersinar', 'bersinar', '1412313131', 'bersinar@gmail.com', 'dsvsfvsfefsfe', 'ffggjfdhthjdrhfd', '12313', 'default.png', '$2y$10$.iA6arWAfxPVhq4PeiUv5.T0Rh5GuBnOLiaXSYvlyohqEErzv/oiG');

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
  `estimasi_item` text DEFAULT NULL,
  `tipe_layanan` enum('kiloan','satuan') NOT NULL DEFAULT 'kiloan',
  `total_item` int(11) DEFAULT NULL,
  `berat` double DEFAULT NULL,
  `alamat` varchar(100) NOT NULL,
  `catatan` text NOT NULL,
  `catatan_berat` text DEFAULT NULL,
  `status_cucian` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cucian`
--

INSERT INTO `cucian` (`id_cucian`, `id_agen`, `id_pelanggan`, `tgl_mulai`, `tgl_selesai`, `jenis`, `estimasi_item`, `tipe_layanan`, `total_item`, `berat`, `alamat`, `catatan`, `catatan_berat`, `status_cucian`) VALUES
(11, 13, 15, '2025-02-15', '0000-00-00', 'komplit', 'baju 1000 ', 'kiloan', NULL, NULL, '', '', NULL, 'Penjemputan'),
(12, 13, 15, '2025-02-16', '0000-00-00', 'setrika', '86', 'kiloan', NULL, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(13, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 6, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(14, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 6, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(15, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 6, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(16, 13, 15, '2025-02-16', '0000-00-00', 'setrika', NULL, 'satuan', 10, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(17, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 10, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(18, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 1, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(19, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 3, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(20, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 3, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(21, 13, 15, '2025-02-16', '0000-00-00', 'komplit', '10050', 'kiloan', NULL, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(22, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 3, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(23, 13, 15, '2025-02-16', '0000-00-00', 'setrika', '123', 'kiloan', NULL, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(24, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 3, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(25, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 3, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(26, 13, 15, '2025-02-16', '0000-00-00', 'setrika', '123', 'kiloan', NULL, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan'),
(27, 13, 15, '2025-02-16', '0000-00-00', 'komplit', NULL, 'satuan', NULL, NULL, 'mana aja boleh', '', NULL, 'Penjemputan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_cucian`
--

CREATE TABLE `detail_cucian` (
  `id_detail` int(11) NOT NULL,
  `id_cucian` int(11) NOT NULL,
  `id_harga_satuan` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_cucian`
--

INSERT INTO `detail_cucian` (`id_detail`, `id_cucian`, `id_harga_satuan`, `jumlah`, `subtotal`) VALUES
(1, 13, 21, 6, 30000),
(2, 14, 21, 6, 30000),
(3, 15, 21, 6, 30000),
(4, 16, 21, 10, 40000),
(5, 17, 21, 10, 50000),
(6, 18, 22, 1, 5000),
(7, 19, 21, 3, 15000),
(8, 20, 21, 3, 15000),
(9, 22, 21, 3, 15000),
(10, 24, 21, 3, 15000),
(11, 25, 21, 3, 15000);

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
(26, 'setrika', 10, 3000),
(27, 'komplit', 10, 6000),
(28, 'cuci', 10, 5000),
(29, 'setrika', 10, 3000),
(30, 'komplit', 10, 6000),
(31, 'cuci', 13, 3000),
(32, 'setrika', 13, 5000),
(33, 'komplit', 13, 6000),
(34, 'cuci', 14, 5000),
(35, 'setrika', 14, 10000),
(36, 'komplit', 14, 7000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `harga_satuan`
--

CREATE TABLE `harga_satuan` (
  `id_harga_satuan` int(11) NOT NULL,
  `id_agen` int(11) NOT NULL,
  `nama_item` varchar(50) NOT NULL,
  `jenis` varchar(30) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `harga_satuan`
--

INSERT INTO `harga_satuan` (`id_harga_satuan`, `id_agen`, `nama_item`, `jenis`, `harga`) VALUES
(21, 13, 'Baju', '', 5000),
(22, 13, 'Celana', '', 5000),
(23, 13, 'Jaket/Sweater', '', 5000),
(24, 13, 'Pakaian Khusus', '', 5000),
(25, 13, 'Selimut', '', 5000),
(26, 13, 'Karpet', '', 5000);

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
(15, 'sakamoto', 'sakamoto@gmail.com', '1231231', 'adfwfawda', 'sfsffdwdwa', 'default.png', '$2y$10$ns0WgyETUbOMTKnqsW6fsuxIcA.tbiw6c2NX8ZnuS/EKCO1/bOd6O');

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
-- Indeks untuk tabel `detail_cucian`
--
ALTER TABLE `detail_cucian`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_cucian` (`id_cucian`),
  ADD KEY `id_harga_satuan` (`id_harga_satuan`);

--
-- Indeks untuk tabel `harga`
--
ALTER TABLE `harga`
  ADD PRIMARY KEY (`id_harga`);

--
-- Indeks untuk tabel `harga_satuan`
--
ALTER TABLE `harga_satuan`
  ADD PRIMARY KEY (`id_harga_satuan`),
  ADD KEY `id_agen` (`id_agen`);

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
  MODIFY `id_agen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `cucian`
--
ALTER TABLE `cucian`
  MODIFY `id_cucian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `detail_cucian`
--
ALTER TABLE `detail_cucian`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `harga`
--
ALTER TABLE `harga`
  MODIFY `id_harga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `harga_satuan`
--
ALTER TABLE `harga_satuan`
  MODIFY `id_harga_satuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `kode_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_cucian`
--
ALTER TABLE `detail_cucian`
  ADD CONSTRAINT `detail_cucian_ibfk_1` FOREIGN KEY (`id_cucian`) REFERENCES `cucian` (`id_cucian`),
  ADD CONSTRAINT `detail_cucian_ibfk_2` FOREIGN KEY (`id_harga_satuan`) REFERENCES `harga_satuan` (`id_harga_satuan`);

--
-- Ketidakleluasaan untuk tabel `harga_satuan`
--
ALTER TABLE `harga_satuan`
  ADD CONSTRAINT `harga_satuan_ibfk_1` FOREIGN KEY (`id_agen`) REFERENCES `agen` (`id_agen`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
