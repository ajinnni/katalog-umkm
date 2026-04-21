-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3007
-- Generation Time: Apr 21, 2026 at 03:30 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_umkm`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id`, `pesanan_id`, `produk_id`, `nama_produk`, `harga_satuan`, `qty`, `subtotal`) VALUES
(1, 4, 3, 'Monitor 144hz BRAND', 1340000.00, 4, 5360000.00),
(2, 5, 4, 'Kopi Hitam', 15000.00, 1, 15000.00),
(3, 6, 5, 'Susu Putih', 10000.00, 1, 10000.00);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama`, `created_at`) VALUES
(1, 'Makanan', '2026-04-02 01:01:42'),
(2, 'Minuman', '2026-04-02 01:01:42'),
(3, 'Fashion', '2026-04-02 01:01:42'),
(4, 'Kerajinan', '2026-04-02 01:01:42'),
(5, 'Elektronik', '2026-04-02 01:01:42'),
(6, 'Pertanian', '2026-04-02 01:01:42'),
(7, 'Lainnya', '2026-04-02 01:01:42');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `kode_pesanan` varchar(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `umkm_id` int(11) NOT NULL,
  `nama_pemesan` varchar(100) NOT NULL,
  `no_wa_pemesan` varchar(20) NOT NULL,
  `alamat_pengiriman` text NOT NULL,
  `total_harga` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','dikonfirmasi','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `kode_pesanan`, `user_id`, `umkm_id`, `nama_pemesan`, `no_wa_pemesan`, `alamat_pengiriman`, `total_harga`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(4, 'ORD-EEC86F', 12, 3, 'Ahmad Saiful', '62818231242123', 'Di jalan situ', 5360000.00, 'selesai', NULL, '2026-04-20 06:45:02', '2026-04-21 01:02:43'),
(5, 'ORD-8F0D4D', 12, 3, 'Ahmad Saiful', '62818231242123', 'Jalan Jalan', 15000.00, 'pending', NULL, '2026-04-21 01:09:28', '2026-04-21 01:09:28'),
(6, 'ORD-B3B632', 12, 2, 'Ahmad Saiful', '62818231242123', 'Jalan Jalan', 10000.00, 'selesai', NULL, '2026-04-21 01:09:31', '2026-04-21 01:10:43');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `umkm_id` int(11) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stok` int(11) NOT NULL DEFAULT 0,
  `foto` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `umkm_id`, `kategori_id`, `nama`, `harga`, `stok`, `foto`, `deskripsi`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 3, 5, 'Monitor 144hz BRAND', 1340000.00, 1111, 'produk_69e5caeaa4422.jpg', 'Monitor ', 1, '2026-04-20 06:42:50', '2026-04-20 06:42:50'),
(4, 3, 2, 'Kopi Hitam', 15000.00, 999, 'produk_69e5e51cc64c0.jpg', 'Minuman kopi hitam', 1, '2026-04-20 08:34:36', '2026-04-20 08:34:36'),
(5, 2, 2, 'Susu Putih', 10000.00, 999, 'produk_69e5e60a5a28b.jpg', 'Susu putih', 1, '2026-04-20 08:38:34', '2026-04-20 08:38:34');

-- --------------------------------------------------------

--
-- Table structure for table `umkm`
--

CREATE TABLE `umkm` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_toko` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_wa_toko` varchar(20) DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `umkm`
--

INSERT INTO `umkm` (`id`, `user_id`, `nama_toko`, `deskripsi`, `alamat`, `no_wa_toko`, `lat`, `lng`, `foto`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 6, 'Penjual Nomor 1 Store', 'Toko toko', 'Di Jalan jalan', '6283824032436', NULL, NULL, '1776667187_tokomerahkuning.jpg', 1, '2026-04-20 06:36:29', '2026-04-20 06:39:54'),
(3, 10, 'Jeko Jika Store', 'Toko menarik', 'Di jalan sana', '6285727287160', NULL, NULL, NULL, 1, '2026-04-20 06:36:29', '2026-04-20 06:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_wa` varchar(20) NOT NULL,
  `role` enum('admin','umkm','user') NOT NULL DEFAULT 'user',
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expired` datetime DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `password`, `no_wa`, `role`, `otp_code`, `otp_expired`, `is_verified`, `foto`, `created_at`, `updated_at`) VALUES
(5, 'Super Admin', '$2y$12$As2F9HfMZ9/wx.5gn5a7d.VskmUdp8dvzwy/kAcFySLxRHRsyNc4C', '628123456789', 'admin', NULL, NULL, 1, NULL, '2026-04-20 01:05:18', '2026-04-20 01:07:58'),
(6, 'Penjual Nomor 1', '$2y$12$yMOZl/bWa0SZqgZ8.IuqVeay34yOdM/qlRvR/A1zM9yUuJHclZhaC', '6283824032436', 'umkm', NULL, NULL, 1, NULL, '2026-04-20 01:13:44', '2026-04-20 01:13:59'),
(10, 'Jeko Jika', '$2y$12$B3TFrP.wBgMON5XQOidyt.MyQwhFQ4nbmTk9sqMr8IxnpU1AvlBGi', '6285727287160', 'umkm', NULL, NULL, 1, NULL, '2026-04-20 01:35:45', '2026-04-20 01:36:14'),
(11, 'Reno Si', '$2y$12$nAS9wvKAJ.GfAPw297B0dOqJ3Aqj5w3tOgRBzxiXI.Ie7Og9y96uu', '6281232112341', 'umkm', NULL, NULL, 1, NULL, '2026-04-20 06:38:22', '2026-04-20 06:38:28'),
(12, 'Ahmad Saiful', '$2y$12$gsbMM.We7NdDKbNGUnQPdOlTPe8WiGocdrSFR/g3oPrIsPIxw/C9a', '62818231242123', 'user', NULL, NULL, 1, NULL, '2026-04-20 06:41:44', '2026-04-20 06:41:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesanan_id` (`pesanan_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pesanan` (`kode_pesanan`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `umkm_id` (`umkm_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `umkm_id` (`umkm_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `umkm`
--
ALTER TABLE `umkm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_wa` (`no_wa`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `umkm`
--
ALTER TABLE `umkm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`umkm_id`) REFERENCES `umkm` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`umkm_id`) REFERENCES `umkm` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `produk_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `umkm`
--
ALTER TABLE `umkm`
  ADD CONSTRAINT `umkm_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
