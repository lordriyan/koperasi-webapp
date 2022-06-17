-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Jun 2022 pada 10.08
-- Versi server: 10.4.14-MariaDB
-- Versi PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_koperasi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pembayaran`
--

CREATE TABLE `tb_pembayaran` (
  `pembayaran_id` int(11) NOT NULL,
  `pinjaman_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_pembayaran`
--

INSERT INTO `tb_pembayaran` (`pembayaran_id`, `pinjaman_id`, `jumlah`, `tanggal`) VALUES
(27, 2, 300000, '2022-05-17 10:08:38'),
(28, 3, 90000, '2022-06-17 10:09:20'),
(29, 4, 250000, '2022-06-17 10:15:27'),
(30, 2, 50000, '2022-06-17 10:18:52'),
(31, 8, 200000, '2022-06-17 14:45:11'),
(32, 1, 12232, '2022-06-17 14:50:03'),
(33, 5, 60000, '2022-05-17 15:01:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pinjaman`
--

CREATE TABLE `tb_pinjaman` (
  `pinjaman_id` int(11) NOT NULL,
  `agen_user_id` int(11) DEFAULT NULL,
  `anggota_user_id` int(11) NOT NULL,
  `jumlah_permintaan` bigint(20) NOT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('diterima','ditolak','menunggu') NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_pinjaman`
--

INSERT INTO `tb_pinjaman` (`pinjaman_id`, `agen_user_id`, `anggota_user_id`, `jumlah_permintaan`, `catatan`, `status`, `tanggal`) VALUES
(1, 2, 3, 120000, 'Untuk membuka usaha kecil-kecilan', 'diterima', '2022-01-16 20:33:32'),
(2, 2, 3, 500000, 'Untuk membeli tanah dan rumah', 'diterima', '2022-04-16 09:15:04'),
(3, 2, 3, 150000, 'Untuk membeli rokok surya', 'diterima', '2022-02-16 20:34:29'),
(4, 1, 3, 600000, 'Untuk perbaikan kendaraan', 'diterima', '2022-02-17 09:38:27'),
(5, 1, 3, 900000, 'Untuk berobat ke dokter', 'diterima', '2022-06-17 09:38:44'),
(6, NULL, 3, 800000, 'Malakukan Pesta Pernikahan', 'menunggu', '2022-06-17 09:40:35'),
(7, NULL, 4, 800000, 'Buat beli laptop baru', 'menunggu', '2022-06-17 14:43:26'),
(8, 5, 4, 300000, 'Untuk beli action figure', 'diterima', '2022-06-17 14:43:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `roles` enum('admin','agen','anggota') NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`user_id`, `fullname`, `username`, `password`, `roles`, `last_login`) VALUES
(1, 'Riyan Saputra', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', '2022-06-17 13:58:04'),
(2, 'Mamat', 'agen', '941730a7089d81c58c743a7577a51640', 'agen', '2022-06-17 14:49:47'),
(3, 'Pablo', 'anggota', 'dfb9e85bc0da607ff76e0559c62537e8', 'anggota', '2022-06-17 14:48:58'),
(4, 'Riki', 'anggota2', 'e7dd32332153b9a2e903ae3b164e1630', 'anggota', '2022-06-17 14:43:08'),
(5, 'Jacob', 'agen2', '8b3748ad0ca1f71aaeac73344cb14dfa', 'agen', '2022-06-17 14:43:49');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD PRIMARY KEY (`pembayaran_id`),
  ADD KEY `pinjaman_id` (`pinjaman_id`);

--
-- Indeks untuk tabel `tb_pinjaman`
--
ALTER TABLE `tb_pinjaman`
  ADD PRIMARY KEY (`pinjaman_id`),
  ADD KEY `agen_user_id` (`agen_user_id`),
  ADD KEY `anggota_user_id` (`anggota_user_id`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  MODIFY `pembayaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `tb_pinjaman`
--
ALTER TABLE `tb_pinjaman`
  MODIFY `pinjaman_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
