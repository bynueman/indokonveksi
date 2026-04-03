-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 04:41 AM
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
-- Database: `indokonveksi`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1749743470),
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1749743470;', 1749743470),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1749781129),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1749781129;', 1749781129);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` bigint(20) UNSIGNED NOT NULL,
  `pesanan_id` varchar(10) NOT NULL,
  `produk_id` varchar(10) NOT NULL,
  `katalog` varchar(50) DEFAULT NULL,
  `material` varchar(50) DEFAULT NULL,
  `warna` varchar(50) DEFAULT NULL,
  `tipe_lengan` enum('pendek','panjang') DEFAULT NULL,
  `jumlah_ukuran` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`jumlah_ukuran`)),
  `jumlah_total` int(11) DEFAULT NULL,
  `file_desain` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `pesanan_id`, `produk_id`, `katalog`, `material`, `warna`, `tipe_lengan`, `jumlah_ukuran`, `jumlah_total`, `file_desain`) VALUES
(11, 'PSN001', 'PRD001', 'KNITTO', 'Cotton Combed 20s', 'Hitam', 'pendek', '{\"xs\":null,\"s\":\"8\",\"m\":\"3\",\"l\":\"22\",\"xl\":\"1\",\"xxl\":null,\"xxxl\":null,\"xxxxl\":null,\"xxxxxl\":null}', 34, 'desain/nuestore.jpeg'),
(12, 'PSN002', 'PRD006', 'Nagata', 'Nagata Drill', 'B-031', 'pendek', '{\"xs\":null,\"s\":null,\"m\":\"22\",\"l\":\"3\",\"xl\":\"5\",\"xxl\":null,\"xxxl\":null,\"xxxxl\":null,\"xxxxxl\":null}', 30, 'desain/workshirt.jpeg'),
(13, 'PSN003', 'PRD009', 'Weva Textile', 'Parasut Taslan', 'Cream', 'panjang', '{\"xs\":null,\"s\":\"2\",\"m\":\"3\",\"l\":\"44\",\"xl\":\"12\",\"xxl\":\"2\",\"xxxl\":null,\"xxxxl\":null,\"xxxxxl\":null}', 63, 'desain/jaket.jpeg');

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
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id_invoice` varchar(10) NOT NULL,
  `pesanan_id` varchar(10) NOT NULL,
  `produk_id` varchar(10) DEFAULT NULL,
  `pelanggan_id` varchar(10) NOT NULL,
  `tanggal_invoice` date NOT NULL,
  `npwp` varchar(30) DEFAULT NULL,
  `biaya_tambahan` decimal(15,2) DEFAULT NULL,
  `diskon` decimal(15,2) DEFAULT NULL,
  `jumlah_bayar` decimal(15,2) DEFAULT NULL,
  `total_tagihan` decimal(15,2) DEFAULT NULL,
  `jumlah_dibayar` decimal(15,2) NOT NULL,
  `kurang` decimal(15,2) DEFAULT NULL,
  `status_pembayaran` enum('dp','lunas') NOT NULL DEFAULT 'dp',
  `metode_pembayaran` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id_invoice`, `pesanan_id`, `produk_id`, `pelanggan_id`, `tanggal_invoice`, `npwp`, `biaya_tambahan`, `diskon`, `jumlah_bayar`, `total_tagihan`, `jumlah_dibayar`, `kurang`, `status_pembayaran`, `metode_pembayaran`, `keterangan`) VALUES
('INV001', 'PSN001', 'PRD001', 'PLN001', '2025-06-12', '09874232', 200000.00, 100000.00, 2210000.00, 2310000.00, 2310000.00, 0.00, 'lunas', 'TF Bank BCA', NULL),
('INV002', 'PSN002', 'PRD006', 'PLN002', '2025-06-12', '213123', NULL, NULL, 3900000.00, 3900000.00, 2000000.00, 1900000.00, 'dp', 'TF Bank Mandiri', NULL),
('INV003', 'PSN003', 'PRD009', 'PLN003', '2025-06-12', '213989234', 200000.00, NULL, 9765000.00, 9965000.00, 9965000.00, 0.00, 'lunas', 'TF Bank BCA', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` varchar(255) NOT NULL,
  `jenis_laporan` enum('Omzet','Laba','Produk Terlaris') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `total_pesanan` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `jenis_laporan`, `tanggal_mulai`, `tanggal_selesai`, `deskripsi`, `total_pesanan`, `created_at`, `updated_at`) VALUES
('LPR001', 'Omzet', '2025-06-01', '2025-06-30', NULL, NULL, '2025-06-12 09:17:47', '2025-06-12 09:17:47');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_20_060856_create_pelanggan_table', 1),
(5, '2025_05_20_060857_create_produk_table', 1),
(6, '2025_05_20_060858_create_pesanan_table', 1),
(7, '2025_05_20_060859_create_invoice_table', 1),
(8, '2025_05_20_060900_create_tracking_table', 1),
(9, '2025_05_20_060901_create_laporan_omzet_table', 1),
(10, '2025_05_20_065605_create_sessions_table', 1),
(11, '2025_05_25_093806_add_timestamps_to_pesanan_table', 1),
(12, '2025_05_20_060860_create_detail_pesanan_table', 2),
(13, '2025_05_27_041013_update_detail_pesanan_table', 3),
(14, '2025_05_27_041014_update_detail_pesanan_table', 4),
(15, '2025_05_28_081416_add_jumlah_total_to_detail_pesanan_table', 5),
(16, '2025_05_28_155353_update_invoice_table', 6),
(17, '2025_05_28_161509_add_total_tagihan_and_kurang_to_invoice_table', 7),
(18, '2025_05_30_124623_add_produk_id_to_invoice_table', 8),
(19, '2025_06_01_130419_update_invoice_table_remove_redundant_columns', 9),
(20, '2025_06_03_053155_add_timestamps_to_tracking_table', 10),
(22, '2025_06_03_065048_update_laporan_table', 11),
(23, '2025_06_04_142620_create_laporan_table', 12),
(25, '2025_06_10_061445_update_bulan_to_range_on_laporan_table', 13),
(26, '2025_06_11_132508_add_biaya_produksi_to_produk_table', 14),
(27, '2025_06_11_181138_create_permission_tables', 15);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
('PLN001', 'Chrisnu', '082285472381', 'Bantul', '2025-06-12 08:27:40', NULL),
('PLN002', 'Acka', '08123712893', 'Indramayu', '2025-06-12 08:28:02', NULL),
('PLN003', 'Firman', '0882007207715', 'Sleman', '2025-06-12 08:28:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` varchar(255) NOT NULL,
  `pelanggan_id` varchar(10) NOT NULL,
  `nama_pesanan` varchar(100) NOT NULL,
  `tanggal_pesanan` date NOT NULL,
  `deadline` date NOT NULL,
  `status` enum('diproses','selesai','dibatalkan') NOT NULL DEFAULT 'diproses',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `pelanggan_id`, `nama_pesanan`, `tanggal_pesanan`, `deadline`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
('PSN001', 'PLN001', 'Kaos NueStore', '2025-06-12', '2025-07-03', 'selesai', NULL, NULL, NULL),
('PSN002', 'PLN002', 'Kemeja Weisten', '2025-06-12', '2025-06-19', 'diproses', 'URGENT', NULL, NULL),
('PSN003', 'PLN003', 'Jaket Human Made', '2025-06-12', '2025-07-10', 'diproses', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` varchar(10) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `biaya_produksi` decimal(15,2) DEFAULT NULL,
  `harga_bahan_baku` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `harga`, `biaya_produksi`, `harga_bahan_baku`, `created_at`, `updated_at`) VALUES
('PRD001', 'Kaos Cotton Combed 20s', 65000.00, 15000.00, 20000.00, NULL, NULL),
('PRD002', 'Kaos Cotton Combed 24s', 60000.00, 15000.00, 20000.00, NULL, NULL),
('PRD003', 'Kaos Cotton Combed 30s', 55000.00, 15000.00, 20000.00, NULL, NULL),
('PRD004', 'Kemeja Ribstok', 110000.00, 25000.00, 35000.00, NULL, NULL),
('PRD005', 'Kemeja American Drill', 115000.00, 25000.00, 35000.00, NULL, NULL),
('PRD006', 'Kemeja Nagara Drill', 130000.00, 25000.00, 35000.00, NULL, NULL),
('PRD007', 'Jaket Fleece CVC', 150000.00, 40000.00, 40000.00, NULL, NULL),
('PRD008', 'Jaket Fleece Cotton', 155000.00, 40000.00, 40000.00, NULL, NULL),
('PRD009', 'Jaket Parasut', 155000.00, 40000.00, 40000.00, NULL, NULL),
('PRD010', 'Polo Polyester', 65000.00, 15000.00, 20000.00, NULL, NULL),
('PRD011', 'Polo CVC Soft', 75000.00, 15000.00, 20000.00, NULL, NULL),
('PRD012', 'Polo Cotton Soft', 85000.00, 15000.00, 20000.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('edrsLiBEYKMWj776RhsGED7BnM5LXNc24cuitzJs', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiWFU4ZW5KaFZWd0hXTGtka0loTG1BQU94cFdZbFpWNzJVVzZLdlRXdyI7czozOiJ1cmwiO2E6MDp7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJHRSOFJuN3R6azA5YkdCZ2ZHS3dLSnVlaHZGdmxLL29ZaGg5cnJ2RUcvYVBhTVlVVm5HZnBxIjt9', 1749781122),
('qMfKeHJXQfHenNSvijXTWVrydl066vHkNJSsPaLe', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTVZ5MmFjYTZhU3BxMW1ZVHNaMGRHMGdPeFk4Y243QzR3cEtYQVZvTSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi90cmFja2luZyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRzSk8vNEVQNzNFSVp2MnpFTkZUaXllU2JCQm1TN1U1dS9mTXJZajhhTUZCS210SzZjVW1HSyI7fQ==', 1749748203);

-- --------------------------------------------------------

--
-- Table structure for table `tracking`
--

CREATE TABLE `tracking` (
  `id_tracking` varchar(10) NOT NULL,
  `pesanan_id` varchar(10) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('beli bahan','dijahit','disablon/bordir','QC','packing','selesai') NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tracking`
--

INSERT INTO `tracking` (`id_tracking`, `pesanan_id`, `tanggal`, `status`, `deskripsi`, `created_at`, `updated_at`) VALUES
('TR-0001-IK', 'PSN002', '2025-06-12', 'packing', NULL, NULL, NULL),
('TR-0002-IK', 'PSN003', '2025-06-12', 'beli bahan', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','owner','produksi') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin1@gmail.com', '$2y$12$gchZe0vzNW4WrM41zafPd.ifj0iLPCef9bmCTw7XHxc2T5ZC7dVbe', 'admin', '2025-05-26 03:26:32', '2025-05-26 03:26:32'),
(2, 'owner', 'owner1@gmail.com', '$2y$12$tR8Rn7tzk09bGBgfGKwKJuehvFvlK/oYhh9rrvEG/aPaMYUVnGfpq', 'owner', '2025-06-12 06:33:55', '2025-06-12 06:33:55'),
(3, 'produksi', 'produksi1@gmail.com', '$2y$12$sJO/4EP73EIZv2zENFTiyeSbBBmS7U5u/fMrYj8aMFBKmtK6cUmGK', 'produksi', '2025-06-12 06:34:24', '2025-06-12 06:34:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `detail_pesanan_pesanan_id_foreign` (`pesanan_id`),
  ADD KEY `detail_pesanan_produk_id_foreign` (`produk_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id_invoice`),
  ADD KEY `invoice_pesanan_id_foreign` (`pesanan_id`),
  ADD KEY `invoice_pelanggan_id_foreign` (`pelanggan_id`),
  ADD KEY `invoice_produk_id_foreign` (`produk_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD UNIQUE KEY `laporan_id_laporan_unique` (`id_laporan`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `pesanan_pelanggan_id_foreign` (`pelanggan_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tracking`
--
ALTER TABLE `tracking`
  ADD PRIMARY KEY (`id_tracking`),
  ADD KEY `tracking_pesanan_id_foreign` (`pesanan_id`);

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
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id_produk`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `invoice_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id_pesanan`),
  ADD CONSTRAINT `invoice_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id_produk`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id_pelanggan`);

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tracking`
--
ALTER TABLE `tracking`
  ADD CONSTRAINT `tracking_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id_pesanan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
