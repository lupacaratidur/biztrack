-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2024 at 05:47 AM
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
-- Database: `kasirresto`
--

-- --------------------------------------------------------

--
-- Table structure for table `cabangs`
--

CREATE TABLE `cabangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cabang` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `pembelian_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
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
(9, 4, 'Es Susu', 5000, 2, '2024-03-18 21:19:44', '2024-03-18 21:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `makanans`
--

CREATE TABLE `makanans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_makanan` varchar(255) NOT NULL,
  `nama_makanan` varchar(255) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `harga` bigint(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `cabang_id` bigint(20) UNSIGNED NOT NULL,
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
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_minuman` varchar(255) NOT NULL,
  `nama_minuman` varchar(255) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `harga` bigint(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `cabang_id` bigint(20) UNSIGNED NOT NULL,
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
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembelians`
--

CREATE TABLE `pembelians` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_pembelian` varchar(255) NOT NULL,
  `total_harga` bigint(20) NOT NULL,
  `status` enum('paid','unpaid') NOT NULL DEFAULT 'unpaid',
  `tgl_transaksi` date NOT NULL DEFAULT '2024-03-19',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `cabang_id` bigint(20) UNSIGNED NOT NULL,
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
(4, 'TRX-65f9125fc94e8', 100000, 'paid', '2024-03-19', 6, 2, '2024-03-18 21:19:43', '2024-03-18 21:19:48');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'administrator', 'Memiliki semua hak akses', '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(2, 'kepala restoran', 'Memiliki hak akses pada laporan per cabang maupun semua', '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(3, 'kasir', 'Memiliki hak akses pada menu kasir', '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(4, 'admin', 'Memiliki hak akses manajemen produk dan laporan cabang', '2024-03-18 20:39:45', '2024-03-18 20:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `cabang_id` bigint(20) UNSIGNED NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `cabang_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'administrator@gmail.com', NULL, '$2y$10$QEdvYpUvzzOLZVQ06B7yQOybpvGAez4PrajIg4bZasknWIB01jSZ.', 1, 1, NULL, '2024-03-18 20:39:44', '2024-03-18 20:39:44'),
(2, 'Kepala Restoran', 'kepalarestoran@gmail.com', NULL, '$2y$10$R/t92tPzQCTimSxR1HA17.LENTZsi17VgXrQdRbv5DCJuY7pEVj6.', 2, 1, NULL, '2024-03-18 20:39:44', '2024-03-18 20:39:44'),
(3, 'mandono', 'mandono@gmail.com', NULL, '$2y$10$unbfPiVBWaYC4kscm9XBzunxkRS4zKU8gGgzoaTyyO5jMP6fEX0Dm', 4, 1, NULL, '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(4, 'Mujiyono', 'mujiyono@gmail.com', NULL, '$2y$10$Rab9qQhRXIF0dt0dWigyGOLFd2lJ14Xuc7Kll2JkRfD7Yqvs.Kmwu', 3, 1, NULL, '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(5, 'Abdul', 'abdul@gmail.com', NULL, '$2y$10$.o5R./Q/Xa0Qs/p1gHulaOxj/GJr7lXR/8ZvZnzo6qxHSeWUDpp1y', 4, 2, NULL, '2024-03-18 20:39:45', '2024-03-18 20:39:45'),
(6, 'Agung', 'agung@gmail.com', NULL, '$2y$10$5L606lNheQvLrlJvXXG6hOWKCu0y.8TBbu66VIy3Cfc6JHFU3lrda', 3, 2, NULL, '2024-03-18 20:39:45', '2024-03-18 20:39:45');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_pembelians`
--
ALTER TABLE `detail_pembelians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `makanans`
--
ALTER TABLE `makanans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `minumen`
--
ALTER TABLE `minumen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pembelians`
--
ALTER TABLE `pembelians`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
