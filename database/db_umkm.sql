-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3007
-- Generation Time: Apr 14, 2026 at 03:40 AM
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
(2, 2, 2, 'Monitor 144hz BRAND', 1212112.00, 1, 1212112.00),
(3, 3, 2, 'Monitor 144hz BRAND', 1212112.00, 1, 1212112.00);

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
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `no_wa` varchar(20) NOT NULL,
  `expired_at` datetime NOT NULL,
  `attempts` tinyint(4) NOT NULL DEFAULT 0,
  `is_used` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `user_id`, `kode`, `no_wa`, `expired_at`, `attempts`, `is_used`, `created_at`) VALUES
(1, 4, '$2y$10$OQwrfEFiWkDCnOUsTHcAseLKzUK6/vsnBOwlsPJyA2hj/5FjUXdbG', '62805727285761', '2026-04-13 03:45:21', 0, 0, '2026-04-13 01:40:21'),
(2, 1, '$2y$10$ws2zEqxC97N3O3Tw/a.JkePOm4XyUII0CXHbFT/b4bgItbDFqM2Iu', '6283824032435', '2026-04-14 03:01:35', 0, 1, '2026-04-14 00:56:35'),
(3, 1, '$2y$10$qAKNSgpRYr8TrjMh1J9ZnuhCi/DvOjXUSJy5VWd2dd71I/Vt0Ow46', '628123456789', '2026-04-14 03:05:48', 0, 0, '2026-04-14 01:00:48');

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
(2, 'ORD-C0363C', 3, 1, 'Ahmad Saiful', '628382403243', 'Jalan Jalan', 1212112.00, 'selesai', NULL, '2026-04-10 02:32:28', '2026-04-14 01:36:35'),
(3, 'ORD-A31FF3', 3, 1, 'Ahmad Saiful', '628382403243', 'Jalan Jalan', 1212112.00, 'dikonfirmasi', NULL, '2026-04-10 02:33:30', '2026-04-14 01:36:27');

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
(2, 1, 5, 'Monitor 144hz BRAND', 1212112.00, 212, 'produk_69d30f1b39fef.jpg', '1212', 1, '2026-04-06 01:24:41', '2026-04-10 01:59:54');

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
(1, 2, 'Toko Nomor 1', 'Toko berwarna merah dan kuning ', 'Jalan Jalan lingkar putih', '62882882882388', NULL, NULL, '1775100405_tokomerahkuning.jpg', 1, '2026-04-02 03:26:45', '2026-04-02 03:27:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_wa` varchar(20) NOT NULL,
  `role` enum('admin','umkm','user') NOT NULL DEFAULT 'user',
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expired` datetime DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `login_attempts` tinyint(4) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `email`, `password`, `no_wa`, `role`, `otp_code`, `otp_expired`, `is_verified`, `login_attempts`, `locked_until`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin', 'admin@umkm.com', '$2y$12$nCxUe5CMGbIs3BVt4hT8qeUyCKGDJcmCnGiVkmRxMJAdHIZmB/vHW', '628123456789', 'admin', NULL, NULL, 1, 0, NULL, NULL, '2026-04-02 01:01:42', '2026-04-13 06:42:49'),
(2, 'Penjual Nomor 1', 'umkm1', 'umkm1@gmail.com', '$2y$10$QYNIUFF/NjkpJTN90qTASeuKZO5z2sSfetbWv6USjdv0MC7l6QpvG', '6283824032435', 'umkm', NULL, NULL, 1, 0, NULL, NULL, '2026-04-02 03:11:17', '2026-04-14 01:24:13'),
(3, 'Ahmad Saiful', 'user1', 'user1@gmail.com', '$2y$10$QYNIUFF/NjkpJTN90qTASeuKZO5z2sSfetbWv6USjdv0MC7l6QpvG', '62881818181823', 'user', NULL, NULL, 1, 0, NULL, NULL, '2026-04-07 02:10:04', '2026-04-14 01:23:32'),
(4, 'Ahmad Saiful', 'ahmadumkm', 'sickmamalefemale@gmail.com', '$2y$12$Oud5JAqdMt3SeCvAVkH96.itRYQnrBQphSOlEJ4OGGNiRzPJ1zznS', '', 'umkm', NULL, NULL, 0, 0, NULL, NULL, '2026-04-13 01:40:04', '2026-04-13 01:40:04');

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
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_expired` (`expired_at`);

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
  ADD UNIQUE KEY `no_wa` (`no_wa`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

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
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `umkm`
--
ALTER TABLE `umkm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Constraints for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD CONSTRAINT `otp_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
