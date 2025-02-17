-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Feb 2025 pada 16.16
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
(11, 13, 15, '2025-02-15', '0000-00-00', 'komplit', 'baju 1000 ', 'kiloan', NULL, 100, '', '', NULL, 'Selesai'),
(12, 13, 15, '2025-02-16', '0000-00-00', 'setrika', '86', 'kiloan', NULL, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(13, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 6, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(14, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 6, NULL, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(15, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 6, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(16, 13, 15, '2025-02-16', '0000-00-00', 'setrika', NULL, 'satuan', 10, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(17, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 10, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(18, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 1, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(19, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 3, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(20, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 3, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(21, 13, 15, '2025-02-16', '0000-00-00', 'komplit', '10050', 'kiloan', NULL, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(22, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 3, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(23, 13, 15, '2025-02-16', '0000-00-00', 'setrika', '123', 'kiloan', NULL, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(24, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 3, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(25, 13, 15, '2025-02-16', '0000-00-00', 'cuci', NULL, 'satuan', 3, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(26, 13, 15, '2025-02-16', '0000-00-00', 'setrika', '123', 'kiloan', NULL, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(27, 13, 15, '2025-02-16', '0000-00-00', 'komplit', NULL, 'satuan', NULL, 100, 'mana aja boleh', '', NULL, 'Selesai'),
(28, 13, 15, '2025-02-17', '0000-00-00', 'komplit', '1000', 'kiloan', NULL, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Selesai'),
(29, 13, 15, '2025-02-17', '0000-00-00', NULL, NULL, 'satuan', NULL, 100, 'sfsffdwdwa, adfwfawda', '', NULL, ''),
(30, 13, 15, '2025-02-17', '0000-00-00', NULL, NULL, 'satuan', NULL, 100, 'sfsffdwdwa, adfwfawda', '', NULL, 'Penjemputan');

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
(11, 25, 21, 3, 15000),
(12, 30, 21, 5, 25000);

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
-- Struktur dari tabel `kategori_item`
--

CREATE TABLE `kategori_item` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
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
(15, 'sakamoto', 'sakamoto@gmail.com', '1231231', 'adfwfawda', 'sfsffdwdwa', 'default.png', '$2y$10$ns0WgyETUbOMTKnqsW6fsuxIcA.tbiw6c2NX8ZnuS/EKCO1/bOd6O');

-- --------------------------------------------------------

--
-- Struktur dari tabel `status_history`
--

CREATE TABLE `status_history` (
  `id` int(11) NOT NULL,
  `id_cucian` int(11) NOT NULL,
  `status_lama` varchar(50) DEFAULT NULL,
  `status_baru` varchar(50) DEFAULT NULL,
  `waktu_ubah` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(24, 11, 13, 15, '2025-02-15', '2025-02-17', 600000, 0, ''),
(25, 12, 13, 15, '2025-02-16', '2025-02-17', 300000, 0, ''),
(26, 13, 13, 15, '2025-02-16', '2025-02-17', 500000, 0, ''),
(27, 14, 13, 15, '2025-02-16', '2025-02-17', 0, 0, ''),
(28, 15, 13, 15, '2025-02-16', '2025-02-17', 500000, 0, ''),
(29, 16, 13, 15, '2025-02-16', '2025-02-17', 300000, 0, ''),
(30, 17, 13, 15, '2025-02-16', '2025-02-17', 500000, 0, ''),
(31, 18, 13, 15, '2025-02-16', '2025-02-17', 500000, 0, ''),
(32, 19, 13, 15, '2025-02-16', '2025-02-17', 500000, 0, ''),
(33, 20, 13, 15, '2025-02-16', '2025-02-17', 500000, 0, ''),
(34, 21, 13, 15, '2025-02-16', '2025-02-17', 600000, 0, ''),
(35, 22, 13, 15, '2025-02-16', '2025-02-17', 500000, 0, ''),
(36, 23, 13, 15, '2025-02-16', '2025-02-17', 300000, 0, ''),
(37, 24, 13, 15, '2025-02-16', '2025-02-17', 500000, 0, ''),
(38, 25, 13, 15, '2025-02-16', '2025-02-17', 500000, 0, ''),
(39, 26, 13, 15, '2025-02-16', '2025-02-17', 300000, 0, ''),
(40, 27, 13, 15, '2025-02-16', '2025-02-17', 600000, 0, ''),
(41, 28, 13, 15, '2025-02-17', '2025-02-17', 600000, 0, '');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_cucian_total`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_cucian_total` (
`id_cucian` int(11)
,`id_agen` int(11)
,`id_pelanggan` int(11)
,`tgl_mulai` date
,`tgl_selesai` date
,`jenis` varchar(15)
,`estimasi_item` text
,`tipe_layanan` enum('kiloan','satuan')
,`total_item` int(11)
,`berat` double
,`alamat` varchar(100)
,`catatan` text
,`catatan_berat` text
,`status_cucian` varchar(20)
,`nama_laundry` varchar(30)
,`nama_pelanggan` varchar(30)
,`total_harga` double
,`detail_item` mediumtext
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_status_cucian`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_status_cucian` (
`id_cucian` int(11)
,`id_agen` int(11)
,`id_pelanggan` int(11)
,`tgl_mulai` date
,`tgl_selesai` date
,`jenis` varchar(15)
,`estimasi_item` text
,`tipe_layanan` enum('kiloan','satuan')
,`total_item` int(11)
,`berat` double
,`alamat` varchar(100)
,`catatan` text
,`catatan_berat` text
,`status_cucian` varchar(20)
,`nama_laundry` varchar(30)
,`nama_pelanggan` varchar(30)
,`total_harga` double
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_status_monitoring`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_status_monitoring` (
`id_cucian` int(11)
,`id_agen` int(11)
,`id_pelanggan` int(11)
,`tipe_layanan` enum('kiloan','satuan')
,`berat` double
,`jenis` varchar(15)
,`status_cucian` varchar(20)
,`tgl_mulai` date
,`estimasi_item` text
,`nama_laundry` varchar(30)
,`nama_pelanggan` varchar(30)
,`total_harga` double
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_total_cucian`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_total_cucian` (
`id_cucian` int(11)
,`id_agen` int(11)
,`id_pelanggan` int(11)
,`tgl_mulai` date
,`tgl_selesai` date
,`jenis` varchar(15)
,`estimasi_item` text
,`tipe_layanan` enum('kiloan','satuan')
,`total_item` int(11)
,`berat` double
,`alamat` varchar(100)
,`catatan` text
,`catatan_berat` text
,`status_cucian` varchar(20)
,`total_harga` double
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_transaksi`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_transaksi` (
`kode_transaksi` int(11)
,`id_cucian` int(11)
,`id_agen` int(11)
,`id_pelanggan` int(11)
,`tgl_mulai` date
,`tgl_selesai` date
,`total_bayar` int(11)
,`rating` int(11)
,`komentar` text
,`tipe_layanan` enum('kiloan','satuan')
,`berat` double
,`jenis` varchar(15)
,`nama_laundry` varchar(30)
,`nama_pelanggan` varchar(30)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_cucian_total`
--
DROP TABLE IF EXISTS `v_cucian_total`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_cucian_total`  AS SELECT `c`.`id_cucian` AS `id_cucian`, `c`.`id_agen` AS `id_agen`, `c`.`id_pelanggan` AS `id_pelanggan`, `c`.`tgl_mulai` AS `tgl_mulai`, `c`.`tgl_selesai` AS `tgl_selesai`, `c`.`jenis` AS `jenis`, `c`.`estimasi_item` AS `estimasi_item`, `c`.`tipe_layanan` AS `tipe_layanan`, `c`.`total_item` AS `total_item`, `c`.`berat` AS `berat`, `c`.`alamat` AS `alamat`, `c`.`catatan` AS `catatan`, `c`.`catatan_berat` AS `catatan_berat`, `c`.`status_cucian` AS `status_cucian`, `a`.`nama_laundry` AS `nama_laundry`, `p`.`nama` AS `nama_pelanggan`, CASE WHEN `c`.`tipe_layanan` = 'kiloan' THEN `c`.`berat`* `h`.`harga` ELSE coalesce((select sum(`dc`.`jumlah` * `hs`.`harga`) from (`detail_cucian` `dc` join `harga_satuan` `hs` on(`dc`.`id_harga_satuan` = `hs`.`id_harga_satuan`)) where `dc`.`id_cucian` = `c`.`id_cucian`),0) END AS `total_harga`, coalesce((select group_concat(concat(`hs`.`nama_item`,' (',`dc`.`jumlah`,')') separator ',') from (`detail_cucian` `dc` join `harga_satuan` `hs` on(`dc`.`id_harga_satuan` = `hs`.`id_harga_satuan`)) where `dc`.`id_cucian` = `c`.`id_cucian`),`c`.`estimasi_item`) AS `detail_item` FROM (((`cucian` `c` left join `agen` `a` on(`c`.`id_agen` = `a`.`id_agen`)) left join `pelanggan` `p` on(`c`.`id_pelanggan` = `p`.`id_pelanggan`)) left join `harga` `h` on(`h`.`id_agen` = `c`.`id_agen` and `h`.`jenis` = `c`.`jenis`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_status_cucian`
--
DROP TABLE IF EXISTS `v_status_cucian`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_status_cucian`  AS SELECT `c`.`id_cucian` AS `id_cucian`, `c`.`id_agen` AS `id_agen`, `c`.`id_pelanggan` AS `id_pelanggan`, `c`.`tgl_mulai` AS `tgl_mulai`, `c`.`tgl_selesai` AS `tgl_selesai`, `c`.`jenis` AS `jenis`, `c`.`estimasi_item` AS `estimasi_item`, `c`.`tipe_layanan` AS `tipe_layanan`, `c`.`total_item` AS `total_item`, `c`.`berat` AS `berat`, `c`.`alamat` AS `alamat`, `c`.`catatan` AS `catatan`, `c`.`catatan_berat` AS `catatan_berat`, `c`.`status_cucian` AS `status_cucian`, `a`.`nama_laundry` AS `nama_laundry`, `p`.`nama` AS `nama_pelanggan`, CASE WHEN `c`.`tipe_layanan` = 'kiloan' THEN `c`.`berat`* `h`.`harga` ELSE (select sum(`dc`.`subtotal`) from `detail_cucian` `dc` where `dc`.`id_cucian` = `c`.`id_cucian`) END AS `total_harga` FROM (((`cucian` `c` left join `agen` `a` on(`a`.`id_agen` = `c`.`id_agen`)) left join `pelanggan` `p` on(`p`.`id_pelanggan` = `c`.`id_pelanggan`)) left join `harga` `h` on(`h`.`id_agen` = `c`.`id_agen` and `h`.`jenis` = `c`.`jenis`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_status_monitoring`
--
DROP TABLE IF EXISTS `v_status_monitoring`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_status_monitoring`  AS SELECT `c`.`id_cucian` AS `id_cucian`, `c`.`id_agen` AS `id_agen`, `c`.`id_pelanggan` AS `id_pelanggan`, `c`.`tipe_layanan` AS `tipe_layanan`, `c`.`berat` AS `berat`, `c`.`jenis` AS `jenis`, `c`.`status_cucian` AS `status_cucian`, `c`.`tgl_mulai` AS `tgl_mulai`, `c`.`estimasi_item` AS `estimasi_item`, `a`.`nama_laundry` AS `nama_laundry`, `p`.`nama` AS `nama_pelanggan`, CASE WHEN `c`.`tipe_layanan` = 'kiloan' THEN `c`.`berat`* `h`.`harga` ELSE (select sum(`dc`.`subtotal`) from `detail_cucian` `dc` where `dc`.`id_cucian` = `c`.`id_cucian`) END AS `total_harga` FROM (((`cucian` `c` join `agen` `a` on(`c`.`id_agen` = `a`.`id_agen`)) join `pelanggan` `p` on(`c`.`id_pelanggan` = `p`.`id_pelanggan`)) left join `harga` `h` on(`h`.`id_agen` = `c`.`id_agen` and `h`.`jenis` = `c`.`jenis`)) WHERE `c`.`status_cucian` <> 'Selesai' ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_total_cucian`
--
DROP TABLE IF EXISTS `v_total_cucian`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_total_cucian`  AS SELECT `c`.`id_cucian` AS `id_cucian`, `c`.`id_agen` AS `id_agen`, `c`.`id_pelanggan` AS `id_pelanggan`, `c`.`tgl_mulai` AS `tgl_mulai`, `c`.`tgl_selesai` AS `tgl_selesai`, `c`.`jenis` AS `jenis`, `c`.`estimasi_item` AS `estimasi_item`, `c`.`tipe_layanan` AS `tipe_layanan`, `c`.`total_item` AS `total_item`, `c`.`berat` AS `berat`, `c`.`alamat` AS `alamat`, `c`.`catatan` AS `catatan`, `c`.`catatan_berat` AS `catatan_berat`, `c`.`status_cucian` AS `status_cucian`, CASE WHEN `c`.`tipe_layanan` = 'kiloan' THEN `c`.`berat`* `h`.`harga` ELSE coalesce((select sum(`dc`.`jumlah` * `hs`.`harga`) from (`detail_cucian` `dc` join `harga_satuan` `hs` on(`dc`.`id_harga_satuan` = `hs`.`id_harga_satuan`)) where `dc`.`id_cucian` = `c`.`id_cucian`),0) END AS `total_harga` FROM (`cucian` `c` left join `harga` `h` on(`h`.`id_agen` = `c`.`id_agen` and `h`.`jenis` = `c`.`jenis`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_transaksi`
--
DROP TABLE IF EXISTS `v_transaksi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_transaksi`  AS SELECT `t`.`kode_transaksi` AS `kode_transaksi`, `t`.`id_cucian` AS `id_cucian`, `t`.`id_agen` AS `id_agen`, `t`.`id_pelanggan` AS `id_pelanggan`, `t`.`tgl_mulai` AS `tgl_mulai`, `t`.`tgl_selesai` AS `tgl_selesai`, `t`.`total_bayar` AS `total_bayar`, `t`.`rating` AS `rating`, `t`.`komentar` AS `komentar`, `c`.`tipe_layanan` AS `tipe_layanan`, `c`.`berat` AS `berat`, `c`.`jenis` AS `jenis`, `a`.`nama_laundry` AS `nama_laundry`, `p`.`nama` AS `nama_pelanggan` FROM (((`transaksi` `t` join `cucian` `c` on(`t`.`id_cucian` = `c`.`id_cucian`)) join `agen` `a` on(`t`.`id_agen` = `a`.`id_agen`)) join `pelanggan` `p` on(`t`.`id_pelanggan` = `p`.`id_pelanggan`)) ;

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
  ADD PRIMARY KEY (`id_cucian`),
  ADD KEY `idx_status` (`status_cucian`),
  ADD KEY `idx_cucian_status` (`status_cucian`),
  ADD KEY `idx_cucian_dates` (`tgl_mulai`),
  ADD KEY `idx_tipe_status` (`tipe_layanan`,`status_cucian`);

--
-- Indeks untuk tabel `detail_cucian`
--
ALTER TABLE `detail_cucian`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_harga_satuan` (`id_harga_satuan`),
  ADD KEY `idx_cucian` (`id_cucian`),
  ADD KEY `idx_cucian_harga` (`id_cucian`,`id_harga_satuan`);

--
-- Indeks untuk tabel `harga`
--
ALTER TABLE `harga`
  ADD PRIMARY KEY (`id_harga`),
  ADD KEY `idx_agen_jenis` (`id_agen`,`jenis`);

--
-- Indeks untuk tabel `harga_satuan`
--
ALTER TABLE `harga_satuan`
  ADD PRIMARY KEY (`id_harga_satuan`),
  ADD KEY `idx_agen` (`id_agen`);

--
-- Indeks untuk tabel `kategori_item`
--
ALTER TABLE `kategori_item`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `status_history`
--
ALTER TABLE `status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cucian` (`id_cucian`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`kode_transaksi`),
  ADD KEY `idx_pelanggan_agen` (`id_pelanggan`,`id_agen`),
  ADD KEY `idx_transaksi_customer` (`id_pelanggan`,`id_agen`);

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
  MODIFY `id_cucian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `detail_cucian`
--
ALTER TABLE `detail_cucian`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
-- AUTO_INCREMENT untuk tabel `kategori_item`
--
ALTER TABLE `kategori_item`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `status_history`
--
ALTER TABLE `status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `kode_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

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

--
-- Ketidakleluasaan untuk tabel `status_history`
--
ALTER TABLE `status_history`
  ADD CONSTRAINT `status_history_ibfk_1` FOREIGN KEY (`id_cucian`) REFERENCES `cucian` (`id_cucian`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
