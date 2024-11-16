-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 06, 2024 at 08:04 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `biztrack`
--

-- --------------------------------------------------------

--
-- Table structure for table `cabangs`
--

CREATE TABLE `cabangs` (
  `id` bigint UNSIGNED NOT NULL,
  `cabang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cabangs`
--

INSERT INTO `cabangs` (`id`, `cabang`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'Pusat', 'Cabang Pusat', '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(2, 'Cabang 1', 'Cabang 1', '2024-03-18 20:39:45', '2024-03-18 20:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pembelians`
--

CREATE TABLE `detail_pembelians` (
  `id` bigint UNSIGNED NOT NULL,
  `pembelian_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` int NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_pembelians`
--

INSERT INTO `detail_pembelians` (`id`, `pembelian_id`, `nama`, `harga`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ayam Bakar Madu', 18000, 1, '2024-03-18 21:03:35', '2024-03-18 21:03:35'),
(2, 1, 'Ayam Goreng Lalapan', 20000, 1, '2024-03-18 21:03:35', '2024-03-18 21:03:35'),
(3, 1, 'Teh hangat', 4000, 2, '2024-03-18 21:03:35', '2024-03-18 21:03:35'),
(4, 2, 'Nila Bakar Pedas', 23000, 1, '2024-03-18 21:04:10', '2024-03-18 21:04:10'),
(5, 2, 'Es Jeruk', 5000, 1, '2024-03-18 21:04:10', '2024-03-18 21:04:10'),
(6, 3, 'Bebek Goreng Lamongan', 45000, 1, '2024-03-18 21:19:28', '2024-03-18 21:19:28'),
(7, 3, 'Es Susu', 5000, 1, '2024-03-18 21:19:28', '2024-03-18 21:19:28'),
(8, 4, 'Bebek Goreng Lamongan', 45000, 2, '2024-03-18 21:19:44', '2024-03-18 21:19:44'),
(9, 4, 'Es Susu', 5000, 2, '2024-03-18 21:19:44', '2024-03-18 21:19:44'),
(10, 5, 'Bebek Goreng Lamongan', 45000, 1, '2024-11-01 01:45:09', '2024-11-01 01:45:09'),
(11, 5, 'Nila Bakar Pedas', 23000, 1, '2024-11-01 01:45:09', '2024-11-01 01:45:09'),
(12, 5, 'Soto Lamongan', 15000, 1, '2024-11-01 01:45:09', '2024-11-01 01:45:09'),
(13, 5, 'Ayam Bakar Madu', 18000, 1, '2024-11-01 01:45:09', '2024-11-01 01:45:09'),
(14, 6, 'Ayam Bakar Madu', 18000, 1, '2024-11-01 01:45:58', '2024-11-01 01:45:58'),
(15, 6, 'Ayam Goreng Lalapan', 20000, 1, '2024-11-01 01:45:58', '2024-11-01 01:45:58'),
(16, 6, 'Bebek Goreng Lamongan', 45000, 1, '2024-11-01 01:45:58', '2024-11-01 01:45:58'),
(17, 6, 'Soto Lamongan', 15000, 1, '2024-11-01 01:45:58', '2024-11-01 01:45:58'),
(18, 6, 'Teh hangat', 4000, 1, '2024-11-01 01:45:58', '2024-11-01 01:45:58'),
(19, 6, 'Es Teh', 4000, 1, '2024-11-01 01:45:58', '2024-11-01 01:45:58'),
(20, 6, 'Es Jeruk', 5000, 1, '2024-11-01 01:45:58', '2024-11-01 01:45:58'),
(21, 7, 'Teh hangat', 4000, 14, '2024-11-01 01:46:42', '2024-11-01 01:46:42'),
(22, 8, 'Bebek Goreng Lamongan', 45000, 7, '2024-11-01 01:46:48', '2024-11-01 01:46:48'),
(23, 9, 'Soto Lamongan', 15000, 1, '2024-11-01 01:46:53', '2024-11-01 01:46:53'),
(24, 10, 'Ayam Bakar Madu', 18000, 1, '2024-11-01 01:46:55', '2024-11-01 01:46:55'),
(25, 11, 'Ayam Bakar Madu', 18000, 1, '2024-11-01 01:47:05', '2024-11-01 01:47:05'),
(26, 11, 'Ayam Goreng Lalapan', 20000, 1, '2024-11-01 01:47:05', '2024-11-01 01:47:05'),
(27, 11, 'Soto Lamongan', 15000, 1, '2024-11-01 01:47:05', '2024-11-01 01:47:05'),
(28, 12, 'Es Jeruk', 5000, 1, '2024-11-01 01:47:16', '2024-11-01 01:47:16'),
(29, 12, 'Es Susu', 5000, 1, '2024-11-01 01:47:16', '2024-11-01 01:47:16'),
(30, 12, 'Es Teh', 4000, 1, '2024-11-01 01:47:16', '2024-11-01 01:47:16'),
(31, 12, 'Teh hangat', 4000, 1, '2024-11-01 01:47:16', '2024-11-01 01:47:16'),
(32, 13, 'Es Susu', 5000, 1, '2024-11-03 23:56:18', '2024-11-03 23:56:18'),
(33, 13, 'Es Teh', 4000, 1, '2024-11-03 23:56:18', '2024-11-03 23:56:18'),
(34, 13, 'Teh hangat', 4000, 1, '2024-11-03 23:56:18', '2024-11-03 23:56:18');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `makanans`
--

CREATE TABLE `makanans` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_makanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_makanan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `cabang_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `makanans`
--

INSERT INTO `makanans` (`id`, `kode_makanan`, `nama_makanan`, `deskripsi`, `gambar`, `harga`, `user_id`, `cabang_id`, `created_at`, `updated_at`) VALUES
(1, 'MKN - 7326', 'Ayam Bakar Madu', 'Ayam Bakar Madu', 'gambar/Ayam Bakar Madu.jpg', 18000, 1, 1, '2024-03-18 20:42:13', '2024-03-18 20:42:13'),
(2, 'MKN - 7095', 'Ayam Goreng Lalapan', 'Ayam Goreng Lalapan', 'gambar/Ayam Goreng Lalapan.jpg', 20000, 1, 1, '2024-03-18 20:42:33', '2024-03-18 20:42:33'),
(3, 'MKN - 2940', 'Bebek Goreng Lamongan', 'Bebek Goreng Lamongan', 'gambar/Bebek Goreng Lamongan.jpg', 45000, 1, 2, '2024-03-18 20:43:00', '2024-03-18 21:19:11'),
(4, 'MKN - 3274', 'Nila Bakar Pedas', 'Nila Bakar Pedas', 'gambar/Nila Bakar Pedas.jpeg', 23000, 1, 1, '2024-03-18 20:43:23', '2024-03-18 20:43:23'),
(5, 'MKN - 3884', 'Soto Lamongan', 'Soto Lamongan', 'gambar/Soto Lamongan.jpg', 15000, 1, 1, '2024-03-18 20:43:59', '2024-03-18 20:43:59');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2023_06_04_104641_create_makanans_table', 1),
(7, '2023_06_05_101108_create_minumen_table', 1),
(8, '2023_06_06_121753_create_pembelians_table', 1),
(9, '2023_06_06_233346_create_detail_pembelians_table', 1),
(10, '2023_06_10_012527_create_cabangs_table', 1),
(11, '2023_06_10_113228_create_roles_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `minumen`
--

CREATE TABLE `minumen` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_minuman` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_minuman` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `cabang_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `minumen`
--

INSERT INTO `minumen` (`id`, `kode_minuman`, `nama_minuman`, `deskripsi`, `gambar`, `harga`, `user_id`, `cabang_id`, `created_at`, `updated_at`) VALUES
(1, 'MKN - 4771', 'Es Jeruk', 'Es Jeruk', 'gambar/Es Jeruk.jpg', 5000, 1, 1, '2024-03-18 20:45:36', '2024-03-18 20:45:36'),
(2, 'MKN - 9831', 'Es Susu', 'Es Susu', 'gambar/Es Susu.jpg', 5000, 1, 2, '2024-03-18 20:45:59', '2024-03-18 21:18:57'),
(3, 'MKN - 6659', 'Es Teh', 'Es Teh', 'gambar/Es Teh.jpg', 4000, 1, 1, '2024-03-18 20:46:19', '2024-03-18 20:46:19'),
(4, 'MKN - 2479', 'Teh hangat', 'Teh hangat', 'gambar/Teh Hangat.jpg', 4000, 1, 1, '2024-03-18 20:46:36', '2024-03-18 20:46:36');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembelians`
--

CREATE TABLE `pembelians` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_pembelian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_harga` bigint NOT NULL,
  `status` enum('paid','unpaid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `tgl_transaksi` date NOT NULL DEFAULT '2024-03-19',
  `user_id` bigint UNSIGNED NOT NULL,
  `cabang_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembelians`
--

INSERT INTO `pembelians` (`id`, `kode_pembelian`, `total_harga`, `status`, `tgl_transaksi`, `user_id`, `cabang_id`, `created_at`, `updated_at`) VALUES
(1, 'TRX-65f90e9706daf', 46000, 'paid', '2024-03-19', 1, 1, '2024-03-18 21:03:35', '2024-03-18 21:03:41'),
(2, 'TRX-65f90eb9e7bf8', 28000, 'paid', '2024-03-19', 1, 1, '2024-03-18 21:04:09', '2024-03-18 21:04:18'),
(3, 'TRX-65f9124fbd0e6', 50000, 'paid', '2024-03-19', 6, 2, '2024-03-18 21:19:27', '2024-03-18 21:19:33'),
(4, 'TRX-65f9125fc94e8', 100000, 'paid', '2024-03-19', 6, 2, '2024-03-18 21:19:43', '2024-03-18 21:19:48'),
(5, 'TRX-6724951531949', 101000, 'unpaid', '2024-11-01', 1, 1, '2024-11-01 01:45:09', '2024-11-01 01:45:09'),
(6, 'TRX-6724954641bd0', 111000, 'unpaid', '2024-11-01', 1, 1, '2024-11-01 01:45:58', '2024-11-01 01:45:58'),
(7, 'TRX-67249572d3add', 56000, 'unpaid', '2024-11-01', 1, 1, '2024-11-01 01:46:42', '2024-11-01 01:46:42'),
(8, 'TRX-6724957853d36', 315000, 'unpaid', '2024-11-01', 1, 1, '2024-11-01 01:46:48', '2024-11-01 01:46:48'),
(9, 'TRX-6724957d02f0c', 15000, 'unpaid', '2024-11-01', 1, 1, '2024-11-01 01:46:53', '2024-11-01 01:46:53'),
(10, 'TRX-6724957f58409', 18000, 'unpaid', '2024-11-01', 1, 1, '2024-11-01 01:46:55', '2024-11-01 01:46:55'),
(11, 'TRX-672495893c5b0', 53000, 'unpaid', '2024-11-01', 1, 1, '2024-11-01 01:47:05', '2024-11-01 01:47:05'),
(12, 'TRX-67249594ac120', 18000, 'unpaid', '2024-11-01', 1, 1, '2024-11-01 01:47:16', '2024-11-01 01:47:16'),
(13, 'TRX-67287012c8466', 13000, 'paid', '2024-11-04', 1, 1, '2024-11-03 23:56:18', '2024-11-03 23:56:28');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'administrator', 'Memiliki semua hak akses', '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(2, 'owner', 'Memiliki hak akses pada laporan per cabang maupun semua', '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(3, 'kasir', 'Memiliki hak akses pada menu kasir', '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(4, 'admin', 'Memiliki hak akses manajemen produk dan laporan cabang', '2024-03-18 20:39:45', '2024-03-18 20:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `cabang_id` bigint UNSIGNED NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `cabang_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'administrator@gmail.com', NULL, '$2y$10$wh40LtvnG1Jb7fS2GsRDKeVqef5iwJfPDBRKNqDyN1wNa2/LXc1Sy', 1, 1, NULL, '2024-03-18 20:39:44', '2024-03-18 20:39:44'),
(2, 'owner', 'kepalarestoran@gmail.com', NULL, '$2y$10$wh40LtvnG1Jb7fS2GsRDKeVqef5iwJfPDBRKNqDyN1wNa2/LXc1Sy', 2, 1, NULL, '2024-03-18 20:39:44', '2024-03-18 20:39:44'),
(3, 'mandono', 'mandono@gmail.com', NULL, '$2y$10$wh40LtvnG1Jb7fS2GsRDKeVqef5iwJfPDBRKNqDyN1wNa2/LXc1Sy', 4, 1, NULL, '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(4, 'Mujiyono', 'mujiyono@gmail.com', NULL, '$2b$10$.RAXFAkwgAEifTZthrl0L.DOeN8bIUOs1HO3sJtSbREFoBsKfdhKe\n', 3, 1, NULL, '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(5, 'Abdul', 'abdul@gmail.com', NULL, '$2b$10$.RAXFAkwgAEifTZthrl0L.DOeN8bIUOs1HO3sJtSbREFoBsKfdhKe\n', 4, 2, NULL, '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(6, 'Agung', 'agung@gmail.com', NULL, '$2b$10$.RAXFAkwgAEifTZthrl0L.DOeN8bIUOs1HO3sJtSbREFoBsKfdhKe\n', 3, 2, NULL, '2024-03-18 20:39:45', '2024-03-18 20:39:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cabangs`
--
ALTER TABLE `cabangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_pembelians`
--
ALTER TABLE `detail_pembelians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `makanans`
--
ALTER TABLE `makanans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `minumen`
--
ALTER TABLE `minumen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembelians`
--
ALTER TABLE `pembelians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cabangs`
--
ALTER TABLE `cabangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_pembelians`
--
ALTER TABLE `detail_pembelians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `makanans`
--
ALTER TABLE `makanans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `minumen`
--
ALTER TABLE `minumen`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pembelians`
--
ALTER TABLE `pembelians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
