-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2025 at 02:02 PM
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
-- Database: `new_pos`
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
('c525a5357e97fef8d3db25841c86da1a', 'i:1;', 1733726857),
('c525a5357e97fef8d3db25841c86da1a:timer', 'i:1733726857;', 1733726857);

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
(4, '2024_10_21_065349_add_two_factor_columns_to_users_table', 1),
(5, '2024_10_21_065441_create_personal_access_tokens_table', 1);

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
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('akEZqSG15WtiqTbSv8A37AU3ZVyiZhTK0JdofIn6', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiM09uMnF3NXlXUjNHbHlheHh2MmMwdTJyWUNwdlcxR2lFNTQyNHVsQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEyJG5PMTdKQVA2Wm1FYzAzTHZJZ01YZk9manhuY3F1bnJRNFVweVpsVXZWeHRxcUZHU2pKT2IyIjt9', 1733664935),
('L4qYUreTf2ZXSlMBgtq8xhk2TuW1yBbCKu9ukm71', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOWc4cWhVdXRydWtQbHc0a3NCRVZGZjB1ZDY4OXRHV2xGU0s4OVF4NSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1733753313),
('okYD6DLTWFY16yOdUAHIFnv860Tr2n9IldNDrDUU', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVjhwQnlFODNpZVJsWEZBSFFQRTQ1ajJwQ0VLVWVGWFM5b250T3lsdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kcml2ZXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEyJG5PMTdKQVA2Wm1FYzAzTHZJZ01YZk9manhuY3F1bnJRNFVweVpsVXZWeHRxcUZHU2pKT2IyIjt9', 1733729386),
('uGFyQdlGAWB4qHnGFCEmuhMYFiFZ22OTFPluEivA', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQXFERzFrWUVSb3RlZDR3WHVLV0ZCSFVJZUVjQTRlOGNEaTdRR2U0YSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zZWFyY2gtc2t1L29zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMiRuTzE3SkFQNlptRWMwM0x2SWdNWGZPZmp4bmNxdW5yUTRVcHlabFV2Vnh0cXFGR1NqSk9iMiI7fQ==', 1733558853),
('us2WONQTmPwtjmmuqYoXLU7UTyUY0zHSTRNKWRa4', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiU3o5NDN1aVFnblRGcElMUDRrdTdqRzFvb0hFaGM1RnRua3JzOGFhZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEyJG5PMTdKQVA2Wm1FYzAzTHZJZ01YZk9manhuY3F1bnJRNFVweVpsVXZWeHRxcUZHU2pKT2IyIjt9', 1733666723);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customers`
--

CREATE TABLE `tbl_customers` (
  `CustomerID` int(11) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(255) NOT NULL,
  `Address` text NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `Balance` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_customers`
--

INSERT INTO `tbl_customers` (`CustomerID`, `FirstName`, `LastName`, `Address`, `PhoneNumber`, `Balance`, `created_at`, `updated_at`) VALUES
(1, 'emil', 'aleronar', 'sta cruzz', '099822232', 2332.00, '2024-11-19 02:34:03', '2024-11-19 02:34:03.000000'),
(2, 'kline', 'aleronar', 'sta cruzz', '0992992', 3334.00, '2024-11-19 02:35:41', '2024-11-19 02:35:41.000000'),
(3, 'sherwin', 'aleronar', 'sta cruzz', '09758487524', 4343.00, '2024-11-19 02:43:48', '2024-11-19 02:43:48.000000'),
(4, 'sherwin', 'aleronar', 'sta cruzz', '0992992', 343.00, '2024-11-19 04:33:55', '2024-11-19 04:33:55.000000'),
(5, 'sherwin', 'alronar', 'sta cruzz', '09758487524', 4545.00, '2024-11-19 04:38:25', '2024-11-19 04:38:25.000000'),
(6, 'ben', 'ten', 'zone', '10', 10.00, '2024-11-22 05:06:46', '2024-11-22 05:06:46.000000'),
(7, 'sherwin', 'aleronar', 'sta cruzz', '00999', 33.00, '2024-11-22 22:15:58', '2024-11-22 22:15:58.000000');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_discount_code`
--

CREATE TABLE `tbl_discount_code` (
  `code_id` int(11) NOT NULL,
  `code_name` varchar(50) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_driver`
--

CREATE TABLE `tbl_driver` (
  `driver_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `mobile_no` varchar(100) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `status` enum('AVAILABLE','NOT AVAILABLE') NOT NULL DEFAULT 'AVAILABLE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_driver`
--

INSERT INTO `tbl_driver` (`driver_id`, `fname`, `lname`, `mobile_no`, `updated_at`, `created_at`, `status`) VALUES
(1, 'jonel', 'aleonar', '0992992', '2024-12-03 07:35:58', '2024-11-22 23:06:49', 'AVAILABLE'),
(2, 'enrile', 'ponce', '0992992', '2024-11-24 15:29:26', '2024-11-22 23:07:04', 'AVAILABLE'),
(3, 'maraga', 'tambang', '0912233', '2024-11-24 15:29:55', '2024-11-22 23:08:24', 'AVAILABLE'),
(4, 'jamuel', 'amores', '00999', '2024-11-24 15:28:54', '2024-11-22 23:08:59', 'AVAILABLE'),
(5, 'Anton', 'Sello', '2324232', '2024-12-03 02:52:35', '2024-12-03 02:52:35', 'AVAILABLE');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expenses`
--

CREATE TABLE `tbl_expenses` (
  `id` int(11) NOT NULL,
  `e_description` varchar(150) NOT NULL,
  `e_amount` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `e_withdraw_by` varchar(150) NOT NULL,
  `e_recieve_by` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_expenses`
--

INSERT INTO `tbl_expenses` (`id`, `e_description`, `e_amount`, `updated_at`, `created_at`, `e_withdraw_by`, `e_recieve_by`) VALUES
(5, 'balon emil', 1122, '2024-10-29 21:25:33', '2024-10-29 01:21:14', 'wqe', 'qweqw'),
(6, 'palit mismo', 400, '2024-10-29 21:25:13', '2024-10-29 01:21:52', 'sherwing', 'nesto'),
(7, 'balon ni emil', 45, '2024-10-30 23:15:05', '2024-10-30 23:15:05', 'wqe', 'nesto'),
(8, 'qweq', 100, '2024-11-01 00:54:11', '2024-11-01 00:54:11', 'wqe', 'qweqw'),
(9, 'palit pan', 500, '2024-11-03 12:03:31', '2024-11-03 12:03:31', 'cashier', 'sherwin'),
(10, 'palit tae', 22, '2024-11-22 09:33:45', '2024-11-22 09:33:45', 'cashier', 'sherwin'),
(11, 'buy snack', 500, '2024-12-06 23:20:33', '2024-12-06 23:20:33', 'dee', 'ayessa');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_helper`
--

CREATE TABLE `tbl_helper` (
  `helper_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `mobile_no` varchar(100) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_helper`
--

INSERT INTO `tbl_helper` (`helper_id`, `fname`, `lname`, `mobile_no`, `updated_at`, `created_at`) VALUES
(1, 'sherwin', 'aleonar', '848343', '2024-11-23 09:53:00', '2024-11-23 09:32:21'),
(3, 'john', 'anton', '0123233', '2024-12-03 02:02:27', '2024-12-03 02:02:27'),
(4, 'emilo', 'doa', '2323', '2024-12-03 02:02:45', '2024-12-03 02:02:45'),
(5, 'doe', 'dil', '092332', '2024-12-03 02:03:04', '2024-12-03 02:03:04'),
(6, 'doaa', 'dam', '23442', '2024-12-03 02:03:21', '2024-12-03 02:03:21');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_info`
--

CREATE TABLE `tbl_info` (
  `info_id` int(11) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mobile_no` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_info`
--

INSERT INTO `tbl_info` (`info_id`, `lname`, `fname`, `mobile_no`) VALUES
(1, 'jablos', 'emil', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_master_stock`
--

CREATE TABLE `tbl_master_stock` (
  `master_stock_id` int(11) NOT NULL,
  `total_all_kilos` decimal(10,2) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_master_stock`
--

INSERT INTO `tbl_master_stock` (`master_stock_id`, `total_all_kilos`, `product_id`, `price`, `created_at`) VALUES
(113, 2.00, 40, 10, '2024-12-04 02:28:10'),
(115, 8.50, 62, 100, '2024-12-04 08:25:43'),
(118, 8.00, 41, 10, '2024-12-04 08:30:17'),
(119, 900.00, 39, 165, '2024-12-07 07:08:34'),
(120, 800.00, 40, 160, '2024-12-07 07:11:08'),
(121, 400.00, 44, 160, '2024-12-07 07:11:08'),
(122, 800.00, 45, 165, '2024-12-07 07:11:08'),
(123, 700.00, 50, 160, '2024-12-07 07:11:08'),
(124, 2000.00, 39, 0, '2024-12-07 07:50:14'),
(125, 1500.00, 45, 0, '2024-12-07 07:50:14'),
(126, 2000.00, 54, 0, '2024-12-07 07:50:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `product_id` int(11) NOT NULL,
  `product_sku` varchar(255) DEFAULT NULL,
  `img` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `category` enum('wholesale','byproduct') NOT NULL,
  `p_description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`product_id`, `product_sku`, `img`, `updated_at`, `created_at`, `category`, `p_description`) VALUES
(39, 'P-1', 'products/R6wAJwJSbGVvBgpjJ3nyqAUdyut6NxXEhW3M9vmF.jpg', '2024-11-06 09:52:12', '2024-11-05 03:03:40', 'wholesale', '900GRMS'),
(40, 'BIR', 'products/uskzJobQoSbR3cTCGOqAbQKVzAlFeYfLG64tqOen.png', '2024-11-06 08:56:22', '2024-11-05 03:13:53', 'wholesale', '900GRMS'),
(41, 'USB', 'products/LSSBjoBhqXAw2QEeG8QEcYxrAr0NXWYNf89dQEj3.png', '2024-11-06 00:57:59', '2024-11-06 00:57:59', 'wholesale', '900GRMS'),
(42, 'LSS', 'products/4YXmoCauVHhOiFVQNXiDzI7NL3x3RYE4459aX2uC.png', '2024-11-06 00:59:33', '2024-11-06 00:59:33', 'wholesale', '900GRMS'),
(43, 'EIR', 'products/SCSFZRrQXYtIJnAZugCYyBQGw9jpGFvnLOEdDsop.png', '2024-11-06 01:00:45', '2024-11-06 01:00:45', 'wholesale', '900GRMS'),
(44, 'FCAIBT', 'products/YodXa4I4Mc8tlKRkCNIVPLmjj3y3esDK1WvXBuUG.png', '2024-11-06 01:01:50', '2024-11-06 01:01:50', 'wholesale', '900GRMS'),
(45, 'P-2', 'products/LJaSfh3FIqaeKAp8rGccmgM4YHhwmL7Uu6BQ3DI2.png', '2024-11-06 01:04:07', '2024-11-06 01:04:07', 'wholesale', '1KG.'),
(50, 'PREM', 'products/7jR9HdseJ9STmDlbixoi2kwLK34RVzYavdTnKYYT.png', '2024-11-06 01:21:34', '2024-11-06 01:21:34', 'wholesale', '1KG.'),
(51, 'LS', 'products/QaafM8uHc1xBMFlgQ5GfviEELyIi5rVu4zTYxjT3.png', '2024-11-06 01:22:49', '2024-11-06 01:22:49', 'wholesale', '1KG.'),
(52, 'ECA/BCA', 'products/HUD4yPyBFh3onxQhXhmd41Whk90rB2XkWKyO38XF.png', '2024-11-06 01:23:40', '2024-11-06 01:23:40', 'wholesale', '1KG.'),
(53, 'FCAISS', 'products/bct88Blqnw0Kw9aHekuJR7xCWrRsgRYrjy2sxPPK.png', '2024-11-06 01:24:17', '2024-11-06 01:24:17', 'wholesale', '1KG.'),
(54, 'P-3', 'products/6k1pYFU8dAODcK0Jad8bp730sHOwmCBKtrPx4OvG.png', '2024-11-06 01:25:02', '2024-11-06 01:25:02', 'wholesale', '1.1KGS.'),
(55, 'OSA', 'products/kbHxfK0hsRhnH3iFdxhAC5DHUQgFt6m7kz1tsKOW.png', '2024-11-06 01:25:46', '2024-11-06 01:25:46', 'wholesale', '1.1KGS.'),
(56, 'RS', 'products/DGl2C5bYTnv3pHawB76HbspYrwtSWZ0cEHSQSAOs.png', '2024-11-06 01:26:04', '2024-11-06 01:26:04', 'wholesale', '1.1KGS.'),
(57, 'ESM/BCM', 'products/qLITjRhuqcGHjHNP4S7x9zwjMNAxIZ582fI0VfLi.png', '2024-11-06 01:26:42', '2024-11-06 01:26:42', 'wholesale', '1.1KGS.'),
(58, 'FCAI', 'products/dneHAJXAb9Wgk8Oegk8owayZH1hLbfdgnGieYmit.png', '2024-11-06 01:27:17', '2024-11-06 01:27:17', 'wholesale', '1.1KGS.'),
(59, 'OS1', 'products/VK6e4Teb2mcWlDIEGSM1M5VzNIjMY6zqEVSaG9KY.png', '2024-11-06 01:28:40', '2024-11-06 01:28:40', 'wholesale', '1.2KGS'),
(60, 'PP', 'products/vCejeUljqHweZd29GgX87k8uDBkpzMeqR3So62ht.png', '2024-11-06 01:29:16', '2024-11-06 01:29:16', 'wholesale', '1.2KGS'),
(61, 'BOS', 'products/AeQV5UmiBVAIWFQrBOhLwqs42Uolo6j7hccy6FO3.png', '2024-11-06 01:30:04', '2024-11-06 01:30:04', 'wholesale', '1.2KGS'),
(62, 'OS', 'products/vliWwpZgBDSR78ou5mhQEjwqcfiy94TuYsesGhJG.png', '2024-11-06 01:30:29', '2024-11-06 01:30:29', 'wholesale', '1.2KGS'),
(63, 'ESL', 'products/xFQ8HvVpLzRdXixYeFxdOOPvPkcbLytpLkOtWgUj.png', '2024-11-06 01:31:05', '2024-11-06 01:31:05', 'wholesale', '1.2KGS'),
(64, 'FCA2', 'products/gRLWDNLN5f3vKxpjQETAEUkEhbfqd5yVZZdMRYm9.png', '2024-11-06 01:31:32', '2024-11-06 01:31:32', 'wholesale', '1.2KGS'),
(65, 'OS2', 'products/6sdtWbKSmbicMRpR44Xhm91d1zE8nSCNTABV5F87.png', '2024-11-06 01:32:04', '2024-11-06 01:32:04', 'wholesale', '1.3-1.4KGS.'),
(66, 'FCAX', 'products/50TFhvK4lFLhur7b9t9vGbfoIyb2QHBKhqVP7ksG.png', '2024-11-06 01:33:25', '2024-11-06 01:33:25', 'wholesale', '1.3-1.4KGS.'),
(67, 'OS3', 'products/JSL1zXo5jXVTwfpoCZHGLXK0RpJyf69AQ86EFYov.png', '2024-11-06 01:33:54', '2024-11-06 01:33:54', 'wholesale', '1.3-1.4KGS.'),
(68, 'OSB', 'products/l8jCm7alD2XeoOPRP2MjvCTCGaJRytEY3AMaxlK6.png', '2024-11-06 01:34:32', '2024-11-06 01:34:32', 'wholesale', '1.3-1.4KGS.'),
(69, 'FCX', 'products/gOMi3xU72SJIBZrM5EPkmGjCnSlcVyIzrcDrEwge.png', '2024-11-06 01:34:55', '2024-11-06 01:34:55', 'wholesale', '1.3-1.4KGS.'),
(70, 'ECJ', 'products/hrEnAACXlJkVHF7yVjLOGMs1hRL6fdHdSO6sh9MQ.png', '2024-11-06 01:35:23', '2024-11-06 01:35:23', 'wholesale', '1.3-1.4KGS.'),
(71, 'FCA3', 'products/mKS0cxrg8uxSViHVaGb8ACg1wfY7BLEGeHg2shyh.png', '2024-11-06 01:35:56', '2024-11-06 01:35:56', 'wholesale', '1.3-1.4KGS.'),
(72, 'US', 'products/IPUEMqo2uBPHo9FYHxs6sIRGDJWkMytkgxftscAR.png', '2024-11-06 01:36:50', '2024-11-06 01:36:50', 'wholesale', '800GRMS.'),
(73, 'USA', 'products/sVzWRcHbftDUF2idigCnjYFr6sRwz1WI12ul97cm.png', '2024-11-06 01:37:22', '2024-11-06 01:37:22', 'wholesale', '800GRMS.'),
(74, 'USA', 'products/hYN7c4rysuraWuXCfNhEKDXN30NzjvQMjXV0zUpF.png', '2024-11-06 01:37:23', '2024-11-06 01:37:23', 'wholesale', '800GRMS.'),
(75, '1R', 'products/oXDQKbef3n2LteZu4M3TIbuFWEg0PGI8w4vLOhKX.png', '2024-11-06 01:38:00', '2024-11-06 01:38:00', 'wholesale', '800GRMS.'),
(76, 'ECS', 'products/Tp2Ywn7nnL81esxfKZ8mGILdN8yPxkCejbQ1bhoP.png', '2024-11-06 01:38:20', '2024-11-06 01:38:20', 'wholesale', '800GRMS.'),
(77, 'FCAIR', 'products/p41DAnWlnpsczJbwBNVMQEwRKQ0p9Ra85fYt8Msu.png', '2024-11-06 01:38:46', '2024-11-06 01:38:46', 'wholesale', '800GRMS.'),
(78, 'SQB/ESQ', 'products/uQYzlWRz3iWUXP6hujUJZFzM2C7TCSvHaFk85yg1.png', '2024-11-06 01:39:30', '2024-11-06 01:39:30', 'wholesale', '700GRMS.'),
(79, 'B', 'products/SdsH8dKsd5paiXntEj2ujhf6dTEV9YUXPmQTtJFx.png', '2024-11-06 01:39:46', '2024-11-06 01:39:46', 'wholesale', '700GRMS.'),
(80, 'FCAIS', 'products/ZxdFzNaiNxyeanM3wWwQ3adWdPmFQvMaswMdZYZL.png', '2024-11-06 01:40:02', '2024-11-06 01:40:02', 'wholesale', '700GRMS.'),
(81, 'CB/UCB/UCC', 'products/BO1jiHrrnsvnwZZBHbfV18NGL1SBThYM0w4mchit.png', '2024-11-06 01:40:49', '2024-11-06 01:40:49', 'byproduct', 'ASSRTD'),
(82, 'ACU', 'products/nxBQO8FuAXgV1BFpOOMegx9Rd5GrMI5x2y0UDDH0.png', '2024-11-06 09:49:12', '2024-11-06 01:41:27', 'byproduct', 'ASSRTD'),
(83, 'FRZN ACU', 'products/il6MQYToqMbvSSQmLVw8Xien1r3WczJKatDxVDDx.png', '2024-11-06 01:42:09', '2024-11-06 01:42:09', 'byproduct', 'ASSRTD'),
(84, 'MARINATED', 'products/uJcbRYRFn1df7jw1GuORKTPtpZ94syKwVJ84XpfP.png', '2024-11-06 09:49:24', '2024-11-06 01:42:34', 'byproduct', 'ASSRTD'),
(85, 'RUNTS', 'products/AuWCLazAOOAwUYzR1HIayJp6SGIrWgHCTJp1SnF3.png', '2024-11-06 09:49:36', '2024-11-06 01:42:58', 'byproduct', '600GRMS'),
(86, 'LIVER', 'products/6ttCGA1pA3Yu8Mmnt2j2b7C8NEf05tLtnSjB1ZKs.png', '2024-11-06 09:49:42', '2024-11-06 01:43:35', 'byproduct', 'PCK.'),
(87, 'GIZZARD', 'products/8P3Wt3cLouqZGbsP1HlB82DozVWAtnAFEO0WAnNi.png', '2024-11-06 09:49:47', '2024-11-06 01:44:02', 'byproduct', 'PCK.'),
(88, 'HEAD', 'products/KKvMhai5BxsfrflmK6X9NhRT5PuZvRucuA9E3am7.png', '2024-11-06 09:49:51', '2024-11-06 01:44:32', 'byproduct', 'KG.'),
(89, 'FEET', 'products/TpCvcc3SdMSVW4YXkCA93NLtALlIX59i9k3rf39e.png', '2024-11-06 09:50:07', '2024-11-06 01:44:58', 'byproduct', 'KG.'),
(90, 'S.I', 'products/UjtkQGGBXh5XHOhoniuYdRL16En7VR1SIs79pGAT.png', '2024-11-06 09:50:10', '2024-11-06 01:45:29', 'byproduct', 'PCK.'),
(91, 'L.I', 'products/2jGR377R44I0vMsaFnsYa7lZn70WcWpUYPY9Ce7c.png', '2024-11-06 09:50:14', '2024-11-06 01:46:06', 'byproduct', 'PCK.'),
(92, 'K.I', 'products/Btwu502auxZPGRhHM54bHg7KG2951P681EC0vZCU.png', '2024-11-06 09:50:18', '2024-11-06 01:46:38', 'byproduct', 'PCK.'),
(93, 'CROPS', 'products/Mcd10xQyHHfH3iSLeiX79s6kgbXJWY0Hfnr1239M.png', '2024-11-06 09:50:21', '2024-11-06 01:47:06', 'byproduct', 'PCK.'),
(94, 'SPLEEN', 'products/NoaJ3TOKNs3r9BzMfVYV7B3Jyd8Y08G4Czv3ZYQF.png', '2024-11-06 09:50:24', '2024-11-06 01:47:31', 'byproduct', 'PCK.'),
(95, 'test', 'products/sBnZGEQYKQNogmVwkpvP8H0pdifP2V47HlQb5vIu.png', '2024-11-16 02:06:52', '2024-11-16 02:06:52', 'byproduct', '1KG.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales`
--

CREATE TABLE `tbl_sales` (
  `sales_id` int(11) NOT NULL,
  `cus_name` varchar(150) NOT NULL,
  `cus_phone` int(11) NOT NULL,
  `created_at` int(11) NOT NULL DEFAULT current_timestamp(),
  `updated_at` int(11) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock`
--

CREATE TABLE `tbl_stock` (
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock_kilos` float NOT NULL,
  `price` float NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_stock`
--

INSERT INTO `tbl_stock` (`stock_id`, `product_id`, `stock_kilos`, `price`, `user_id`, `updated_at`, `created_at`) VALUES
(206, 39, 14, 35, NULL, '2024-11-28 05:56:42', '2024-11-28 04:45:13'),
(207, 39, 45, 34, NULL, '2024-11-28 11:33:37', '2024-11-28 04:45:45'),
(208, 39, 2, 36, NULL, '2024-11-28 05:00:40', '2024-11-28 04:53:15'),
(209, 39, 1, 39, NULL, '2024-11-28 04:59:52', '2024-11-28 04:59:52'),
(210, 39, 1, 38, NULL, '2024-11-28 05:00:03', '2024-11-28 05:00:03'),
(211, 39, 10, 0, NULL, '2024-11-28 08:36:09', '2024-11-28 06:36:07'),
(212, 39, 15, 46, NULL, '2024-11-28 07:13:50', '2024-11-28 06:36:45'),
(213, 39, 11, 45, NULL, '2024-11-28 07:13:39', '2024-11-28 07:11:29'),
(214, 39, 1, 6, NULL, '2024-11-28 07:43:05', '2024-11-28 07:43:05'),
(215, 39, 5, 35, NULL, '2024-11-29 01:25:47', '2024-11-29 01:25:47'),
(216, 39, 1, 34, NULL, '2024-11-29 01:31:38', '2024-11-29 01:31:38'),
(217, 40, 1, 33, NULL, '2024-11-30 08:59:09', '2024-11-30 08:59:09'),
(218, 39, 8, 4, NULL, '2024-11-30 09:56:48', '2024-11-30 09:56:42'),
(219, 39, 1, 30, NULL, '2024-11-30 10:16:56', '2024-11-30 10:16:56'),
(220, 39, 2, 0, NULL, '2024-12-01 04:47:00', '2024-11-30 21:48:20'),
(221, 39, 5, 3, NULL, '2024-11-30 21:48:27', '2024-11-30 21:48:27'),
(222, 39, 38, 45, NULL, '2024-12-01 13:32:04', '2024-12-01 04:36:50'),
(223, 39, 12, 30, NULL, '2024-12-01 04:56:45', '2024-12-01 04:56:45'),
(224, 40, 20, 50, NULL, '2024-12-01 05:02:36', '2024-12-01 05:02:36'),
(225, 41, 30, 50, NULL, '2024-12-01 05:02:36', '2024-12-01 05:02:36'),
(226, 39, 12, 56, NULL, '2024-12-01 09:29:00', '2024-12-01 09:29:00'),
(227, 40, 1, 45, NULL, '2024-12-01 13:32:04', '2024-12-01 13:32:04'),
(229, 39, 12, 12, NULL, '2024-12-01 13:57:32', '2024-12-01 13:57:32'),
(230, 39, 1, 12, NULL, '2024-12-01 17:41:36', '2024-12-01 17:41:36'),
(231, 39, 13, 67, NULL, '2024-12-01 17:42:17', '2024-12-01 17:42:17'),
(232, 39, 17, 50, NULL, '2024-12-01 17:43:20', '2024-12-01 17:43:20'),
(233, 39, 30, 49, NULL, '2024-12-02 05:50:14', '2024-12-02 05:50:14'),
(234, 40, 30, 45, NULL, '2024-12-02 05:50:14', '2024-12-02 05:50:14'),
(235, 39, 20, 12, NULL, '2024-12-02 22:47:55', '2024-12-02 17:48:47'),
(236, 39, 5, 5, NULL, '2024-12-02 17:50:03', '2024-12-02 17:50:03'),
(237, 55, 5, 5, NULL, '2024-12-02 18:34:54', '2024-12-02 18:34:54'),
(238, 55, 7, 7, NULL, '2024-12-02 18:35:06', '2024-12-02 18:35:06'),
(239, 50, 12, 12, NULL, '2024-12-02 18:40:40', '2024-12-02 18:40:40'),
(240, 50, 12, 34, NULL, '2024-12-02 18:41:35', '2024-12-02 18:41:35'),
(241, 39, 13, 45, NULL, '2024-12-02 21:56:45', '2024-12-02 21:56:45'),
(242, 40, 14, 45, NULL, '2024-12-02 21:56:45', '2024-12-02 21:56:45'),
(243, 39, 20, 10, NULL, '2024-12-03 11:09:44', '2024-12-02 22:47:34'),
(244, 41, 12, 55, NULL, '2024-12-03 01:47:35', '2024-12-03 01:47:35'),
(245, 40, 12, 44, NULL, '2024-12-03 01:47:35', '2024-12-03 01:47:35'),
(246, 39, 10, 20, NULL, '2024-12-03 18:03:10', '2024-12-03 18:03:10'),
(247, 40, 10, 10, NULL, '2024-12-03 18:28:10', '2024-12-03 18:28:10'),
(248, 45, 10, 100, NULL, '2024-12-04 00:25:26', '2024-12-04 00:25:26'),
(249, 62, 20, 100, NULL, '2024-12-04 00:25:43', '2024-12-04 00:25:43'),
(250, 39, 50, 140, NULL, '2024-12-04 00:28:15', '2024-12-04 00:28:15'),
(251, 45, 20, 150, NULL, '2024-12-04 00:28:15', '2024-12-04 00:28:15'),
(252, 41, 10, 10, NULL, '2024-12-04 00:30:17', '2024-12-04 00:30:17'),
(253, 39, 1900, 165, NULL, '2024-12-06 23:11:08', '2024-12-06 23:08:34'),
(254, 40, 800, 160, NULL, '2024-12-06 23:11:08', '2024-12-06 23:11:08'),
(255, 44, 400, 160, NULL, '2024-12-06 23:11:08', '2024-12-06 23:11:08'),
(256, 45, 800, 165, NULL, '2024-12-06 23:11:08', '2024-12-06 23:11:08'),
(257, 50, 700, 160, NULL, '2024-12-06 23:11:08', '2024-12-06 23:11:08'),
(258, 39, 2000, 0, NULL, '2024-12-06 23:50:14', '2024-12-06 23:50:14'),
(259, 45, 1500, 0, NULL, '2024-12-06 23:50:14', '2024-12-06 23:50:14'),
(260, 54, 2000, 0, NULL, '2024-12-06 23:50:14', '2024-12-06 23:50:14');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock_history`
--

CREATE TABLE `tbl_stock_history` (
  `history_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `created_at` timestamp(5) NOT NULL DEFAULT current_timestamp(5) ON UPDATE current_timestamp(5),
  `updated_at` timestamp(5) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_stock_history`
--

INSERT INTO `tbl_stock_history` (`history_id`, `stock_id`, `created_at`, `updated_at`) VALUES
(10, 45, '2024-10-26 04:17:50.00000', '2024-10-26 04:17:50.00000'),
(11, 46, '2024-10-26 04:46:11.00000', '2024-10-26 04:46:11.00000'),
(12, 47, '2024-10-26 05:46:49.00000', '2024-10-26 05:46:49.00000'),
(13, 48, '2024-10-26 05:48:57.00000', '2024-10-26 05:48:57.00000'),
(14, 49, '2024-10-26 06:35:10.00000', '2024-10-26 06:35:10.00000'),
(15, 50, '2024-10-27 01:34:22.00000', '2024-10-27 01:34:22.00000'),
(16, 51, '2024-10-28 11:46:05.00000', '2024-10-28 11:46:05.00000'),
(17, 52, '2024-10-28 11:46:25.00000', '2024-10-28 11:46:25.00000'),
(18, 53, '2024-10-28 20:08:57.00000', '2024-10-28 20:08:57.00000'),
(19, 56, '2024-10-29 17:57:07.00000', '2024-10-29 17:57:07.00000'),
(20, 57, '2024-10-29 17:57:11.00000', '2024-10-29 17:57:11.00000'),
(21, 58, '2024-10-29 17:57:12.00000', '2024-10-29 17:57:12.00000'),
(22, 59, '2024-10-29 17:57:12.00000', '2024-10-29 17:57:12.00000'),
(23, 60, '2024-10-29 17:57:12.00000', '2024-10-29 17:57:12.00000'),
(24, 61, '2024-10-29 17:57:13.00000', '2024-10-29 17:57:13.00000'),
(25, 62, '2024-10-29 17:57:13.00000', '2024-10-29 17:57:13.00000'),
(26, 63, '2024-10-29 17:57:30.00000', '2024-10-29 17:57:30.00000'),
(27, 166, '2024-11-16 09:39:41.00000', '2024-11-16 09:39:41.00000'),
(28, 166, '2024-11-16 09:40:06.00000', '2024-11-16 09:40:06.00000'),
(29, 166, '2024-11-16 09:41:13.00000', '2024-11-16 09:41:13.00000'),
(30, 166, '2024-11-16 09:42:07.00000', '2024-11-16 09:42:07.00000'),
(31, 166, '2024-11-16 09:42:19.00000', '2024-11-16 09:42:19.00000'),
(32, 169, '2024-11-16 11:32:41.00000', '2024-11-16 11:32:41.00000'),
(33, 170, '2024-11-16 11:32:48.00000', '2024-11-16 11:32:48.00000'),
(34, 172, '2024-11-16 11:43:20.00000', '2024-11-16 11:43:20.00000'),
(35, 173, '2024-11-16 11:43:28.00000', '2024-11-16 11:43:28.00000'),
(36, 171, '2024-11-16 11:43:35.00000', '2024-11-16 11:43:35.00000'),
(37, 172, '2024-11-16 12:11:48.00000', '2024-11-16 12:11:48.00000'),
(38, 172, '2024-11-22 05:07:57.00000', '2024-11-22 05:07:57.00000'),
(39, 172, '2024-11-22 05:08:10.00000', '2024-11-22 05:08:10.00000'),
(40, 178, '2024-11-22 08:24:16.00000', '2024-11-22 08:24:16.00000'),
(41, 178, '2024-11-22 08:26:05.00000', '2024-11-22 08:26:05.00000'),
(42, 204, '2024-11-28 04:41:46.00000', '2024-11-28 04:41:46.00000'),
(43, 206, '2024-11-28 04:47:59.00000', '2024-11-28 04:47:59.00000'),
(44, 208, '2024-11-28 05:00:40.00000', '2024-11-28 05:00:40.00000'),
(45, 207, '2024-11-28 05:24:42.00000', '2024-11-28 05:24:42.00000'),
(46, 206, '2024-11-28 05:24:52.00000', '2024-11-28 05:24:52.00000'),
(47, 207, '2024-11-28 05:26:52.00000', '2024-11-28 05:26:52.00000'),
(48, 206, '2024-11-28 05:26:59.00000', '2024-11-28 05:26:59.00000'),
(49, 207, '2024-11-28 05:56:29.00000', '2024-11-28 05:56:29.00000'),
(50, 206, '2024-11-28 05:56:42.00000', '2024-11-28 05:56:42.00000'),
(51, 213, '2024-11-28 07:13:39.00000', '2024-11-28 07:13:39.00000'),
(52, 212, '2024-11-28 07:13:50.00000', '2024-11-28 07:13:50.00000'),
(53, 211, '2024-11-28 08:36:09.00000', '2024-11-28 08:36:09.00000'),
(54, 207, '2024-11-28 11:33:37.00000', '2024-11-28 11:33:37.00000'),
(55, 218, '2024-11-30 09:56:48.00000', '2024-11-30 09:56:48.00000'),
(56, 222, '2024-12-01 04:43:24.00000', '2024-12-01 04:43:24.00000'),
(57, 220, '2024-12-01 04:47:00.00000', '2024-12-01 04:47:00.00000'),
(58, 222, '2024-12-01 04:48:46.00000', '2024-12-01 04:48:46.00000'),
(59, 222, '2024-12-01 05:02:36.00000', '2024-12-01 05:02:36.00000'),
(60, 222, '2024-12-01 13:32:04.00000', '2024-12-01 13:32:04.00000'),
(62, 235, '2024-12-02 22:47:55.00000', '2024-12-02 22:47:55.00000'),
(63, 243, '2024-12-03 11:09:44.00000', '2024-12-03 11:09:44.00000'),
(64, 253, '2024-12-06 23:11:08.00000', '2024-12-06 23:11:08.00000');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transactions`
--

CREATE TABLE `tbl_transactions` (
  `transaction_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `receipt_id` varchar(255) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT 'N/A',
  `total_amount` decimal(10,2) NOT NULL,
  `subtotal` float NOT NULL,
  `service_type` enum('walkin','deliver') NOT NULL,
  `status` enum('Not Assigned','On Going','Successful','') DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_transactions`
--

INSERT INTO `tbl_transactions` (`transaction_id`, `date`, `receipt_id`, `CustomerID`, `phone`, `total_amount`, `subtotal`, `service_type`, `status`, `updated_at`, `created_at`) VALUES
(305, '2024-12-04', 'RCP-OLEJL', 1, 'N/A', 0.00, 20, 'deliver', 'Successful', '2024-12-04 00:27:24', '2024-12-04 07:57:09'),
(307, '2024-12-04', 'RCP-RKZSS', 2, 'N/A', 0.00, 154, 'deliver', 'Not Assigned', '2024-12-04 00:02:44', '2024-12-04 08:02:23'),
(308, '2024-12-04', 'RCP-MB8M0', 2, 'N/A', 0.00, 34, 'deliver', 'Successful', '2024-12-04 00:05:10', '2024-12-04 08:03:26'),
(310, '2024-12-04', 'RCP-IGG4S', 3, 'N/A', 0.00, 340, 'deliver', 'Not Assigned', '2024-12-04 08:26:30', '2024-12-04 08:26:30'),
(311, '2024-12-04', 'RCP-CG2VQ', 1, 'N/A', 0.00, 10, 'deliver', 'Not Assigned', '2024-12-04 00:31:23', '2024-12-04 08:30:48'),
(313, '2024-12-05', 'RCP-5PEY0', 2, 'N/A', 8.80, 10, 'walkin', NULL, '2024-12-05 14:51:56', '2024-12-05 14:51:56'),
(315, '2024-12-06', 'RCP-DW07Q', 1, 'N/A', 0.00, 130, 'walkin', NULL, '2024-12-05 16:38:35', '2024-12-05 16:38:35'),
(316, '2024-12-06', 'RCP-68WMG', 1, 'N/A', 0.00, 7000, 'walkin', NULL, '2024-12-05 17:50:00', '2024-12-05 17:50:00'),
(317, '2024-12-07', 'RCP-VV57D', 1, 'N/A', 0.00, 4010, 'deliver', 'Not Assigned', '2024-12-07 07:16:31', '2024-12-07 07:16:31'),
(318, '2024-12-07', 'RCP-M074H', 1, 'N/A', 0.00, 1050, 'walkin', NULL, '2024-12-07 07:18:56', '2024-12-07 07:18:56'),
(319, '2024-12-07', 'RCP-LV9W9', 4, 'N/A', 0.00, 165000, 'deliver', 'On Going', '2024-12-06 23:54:41', '2024-12-07 07:53:19'),
(320, '2024-12-07', 'RCP-K59VN', 1, 'N/A', 0.00, 150, 'walkin', NULL, '2024-12-07 08:07:33', '2024-12-07 08:07:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transaction_items`
--

CREATE TABLE `tbl_transaction_items` (
  `item_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(50) NOT NULL,
  `kilos` decimal(10,2) NOT NULL,
  `price_per_kilo` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_transaction_items`
--

INSERT INTO `tbl_transaction_items` (`item_id`, `transaction_id`, `product_id`, `kilos`, `price_per_kilo`, `total`, `created_at`, `updated_at`) VALUES
(257, 305, 39, 1.00, 20.00, 20.00, '2024-12-03 23:57:09', '2024-12-03 23:57:09'),
(258, 307, 50, 10.00, 12.00, 120.00, '2024-12-04 00:02:23', '2024-12-04 00:02:23'),
(259, 307, 50, 1.00, 34.00, 34.00, '2024-12-04 00:02:23', '2024-12-04 00:02:23'),
(260, 308, 50, 1.00, 34.00, 34.00, '2024-12-04 00:03:26', '2024-12-04 00:03:26'),
(261, 310, 50, 10.00, 34.00, 340.00, '2024-12-04 00:26:30', '2024-12-04 00:26:30'),
(262, 311, 41, 1.00, 10.00, 10.00, '2024-12-04 00:30:48', '2024-12-04 00:30:48'),
(263, 313, 40, 1.00, 10.00, 10.00, '2024-12-05 06:51:57', '2024-12-05 06:51:57'),
(264, 315, 39, 6.00, 20.00, 120.00, '2024-12-05 08:38:35', '2024-12-05 08:38:35'),
(265, 315, 40, 1.00, 10.00, 10.00, '2024-12-05 08:38:35', '2024-12-05 08:38:35'),
(266, 316, 39, 50.00, 140.00, 7000.00, '2024-12-05 09:50:00', '2024-12-05 09:50:00'),
(267, 317, 45, 10.00, 100.00, 1000.00, '2024-12-06 23:16:31', '2024-12-06 23:16:31'),
(268, 317, 41, 1.00, 10.00, 10.00, '2024-12-06 23:16:31', '2024-12-06 23:16:31'),
(269, 317, 45, 20.00, 150.00, 3000.00, '2024-12-06 23:16:31', '2024-12-06 23:16:31'),
(270, 318, 40, 5.00, 10.00, 50.00, '2024-12-06 23:18:56', '2024-12-06 23:18:56'),
(271, 318, 62, 10.00, 100.00, 1000.00, '2024-12-06 23:18:56', '2024-12-06 23:18:56'),
(272, 319, 39, 1000.00, 165.00, 165000.00, '2024-12-06 23:53:19', '2024-12-06 23:53:19'),
(273, 320, 62, 1.50, 100.00, 150.00, '2024-12-07 00:07:33', '2024-12-07 00:07:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_truck`
--

CREATE TABLE `tbl_truck` (
  `truck_id` int(11) NOT NULL,
  `truck_name` varchar(150) NOT NULL,
  `truck_type` varchar(150) NOT NULL,
  `truck_status` enum('Available','In Use','Maintenance','Out of Service') NOT NULL DEFAULT 'Available',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_truck`
--

INSERT INTO `tbl_truck` (`truck_id`, `truck_name`, `truck_type`, `truck_status`, `updated_at`) VALUES
(2, 'van', 'Flatbed Truck', 'Available', '2024-12-04 00:27:24'),
(3, 'Panel Yoyo', 'Flatbed Truck', 'In Use', '2024-12-06 23:54:41'),
(5, 'Rusi', 'Box Truck', 'Available', '2024-12-04 00:04:23'),
(6, 'bakho', 'Car Carrier', 'Available', '2024-12-03 23:15:04'),
(7, 'toyota', 'Tanker Truck', 'Available', '2024-12-03 18:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_trucking`
--

CREATE TABLE `tbl_trucking` (
  `trucking_id` int(11) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `helper_id` int(11) NOT NULL,
  `truck_allowance` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL,
  `cus_name` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trucking_info`
--

CREATE TABLE `trucking_info` (
  `id` int(11) NOT NULL,
  `driver_id` int(10) NOT NULL,
  `helper_id` int(11) NOT NULL,
  `truck_id` int(11) NOT NULL,
  `allowance` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `receipt_no` varchar(100) DEFAULT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `total_kilo` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trucking_info`
--

INSERT INTO `trucking_info` (`id`, `driver_id`, `helper_id`, `truck_id`, `allowance`, `destination`, `receipt_no`, `CustomerID`, `total_price`, `total_kilo`, `created_at`, `updated_at`) VALUES
(14, 1, 1, 3, '22', '232', 'RCP-m456m864-6L0UC', 1, 0.00, 0.00, '2024-11-30 21:58:29', '2024-11-30 21:58:29'),
(15, 2, 1, 5, '343', '343', 'RCP-m456oi05-SRDR3', 1, 0.00, 0.00, '2024-12-01 04:26:57', '2024-12-01 04:26:57'),
(16, 2, 1, 2, '454', '43', 'RCP-m456qf2w-0K8DE', 1, 0.00, 0.00, '2024-12-01 04:27:09', '2024-12-01 04:27:09'),
(18, 1, 1, 2, '33', '33', 'RCP-m45l6jzx-J1U5E', 1, 0.00, 0.00, '2024-12-01 04:39:00', '2024-12-01 04:39:00'),
(19, 2, 1, 5, '2', '3', 'RCP-RDXDS', 1, 0.00, 0.00, '2024-12-01 04:54:37', '2024-12-01 04:54:37'),
(20, 2, 1, 3, '1000', 'casinglot', 'RCP-ONIIX', 1, 0.00, 0.00, '2024-12-01 05:07:58', '2024-12-01 05:07:58'),
(21, 2, 1, 6, '1000', 'gogoy street', 'RCP-0RQOU', 2, 0.00, 0.00, '2024-12-01 11:11:29', '2024-12-01 11:11:29'),
(22, 2, 1, 7, '1000', 'gogoy street', 'RCP-GYSS5', 1, 0.00, 0.00, '2024-12-01 14:02:17', '2024-12-01 14:02:17'),
(23, 2, 1, 6, '10000', 'casinglot', 'RCP-0RQOU', 2, 0.00, 0.00, '2024-12-01 19:54:40', '2024-12-01 19:54:40'),
(24, 3, 1, 6, '1000', '1000', 'RCP-7PC53', 3, 0.00, 0.00, '2024-12-01 21:08:31', '2024-12-01 21:08:31'),
(25, 1, 1, 6, '1000', 'casinglot', 'RCP-J2NLY', 1, 0.00, 0.00, '2024-12-01 21:25:14', '2024-12-01 21:25:14'),
(26, 2, 1, 6, '1000', 'casinglot', 'RCP-J8ZA6', 1, 0.00, 0.00, '2024-12-01 21:30:42', '2024-12-01 21:30:42'),
(27, 2, 1, 3, '100', 'casinglot', 'RCP-0RQOU', 2, 0.00, 0.00, '2024-12-01 21:46:16', '2024-12-01 21:46:16'),
(28, 2, 1, 6, '1000', 'casinglot', 'RCP-GYSS5', 1, 0.00, 0.00, '2024-12-01 22:08:53', '2024-12-01 22:08:53'),
(29, 2, 1, 3, '1000', 'casinglot', 'RCP-7PC53', 3, 0.00, 0.00, '2024-12-01 22:19:09', '2024-12-01 22:19:09'),
(30, 2, 1, 6, '10000', 'casinglot', 'RCP-GYSS5', 1, 0.00, 0.00, '2024-12-01 23:08:45', '2024-12-01 23:08:45'),
(31, 3, 1, 3, '1000', 'casinglot', 'RCP-IFX85', 1, 0.00, 0.00, '2024-12-02 05:54:14', '2024-12-02 05:54:14'),
(32, 2, 1, 6, '1000', 'casinglot', 'RCP-IFX85', 1, 0.00, 0.00, '2024-12-02 05:56:13', '2024-12-02 05:56:13'),
(33, 2, 1, 7, '1000', 'casinglot', 'RCP-J2NLY', 1, 0.00, 0.00, '2024-12-02 05:56:31', '2024-12-02 05:56:31'),
(34, 2, 1, 6, '1000', 'casinglot', 'RCP-FORP9', 1, 0.00, 0.00, '2024-12-02 17:40:05', '2024-12-02 17:40:05'),
(35, 3, 1, 3, '1000', 'casinglot', 'RCP-FORP9', 1, 0.00, 0.00, '2024-12-02 21:52:52', '2024-12-02 21:52:52'),
(36, 3, 1, 2, '1000', 'casinglot', 'RCP-02ELQ', 4, 0.00, 0.00, '2024-12-02 21:55:49', '2024-12-02 21:55:49'),
(37, 2, 1, 2, '1000', 'casinglot', 'RCP-33Z10', 2, 0.00, 0.00, '2024-12-02 21:58:03', '2024-12-02 21:58:03'),
(38, 2, 1, 2, '1000', 'casinglot', 'RCP-2RHFF', 1, 0.00, 0.00, '2024-12-02 22:53:11', '2024-12-02 22:53:11'),
(39, 1, 1, 5, '1000', 'casinglot', 'RCP-C86D3', 1, 0.00, 0.00, '2024-12-02 22:53:35', '2024-12-02 22:53:35'),
(40, 3, 1, 3, '100', 'casinglot', 'RCP-NF5Y6', 1, 0.00, 0.00, '2024-12-02 22:54:09', '2024-12-02 22:54:09'),
(41, 2, 1, 6, '1000', 'casinglot', 'RCP-ONIIX', 1, 0.00, 0.00, '2024-12-02 22:54:35', '2024-12-02 22:54:35'),
(42, 1, 1, 7, '1000', 'casinglot', 'RCP-RDXDS', 5, 0.00, 0.00, '2024-12-02 22:55:01', '2024-12-02 22:55:01'),
(43, 3, 4, 2, '1000', 'casinglot', 'RCP-MCP8Q', 1, 0.00, 0.00, '2024-12-03 02:04:42', '2024-12-03 02:04:42'),
(44, 1, 1, 3, '1000', 'casinglot', 'RCP-RDXDS', 1, 0.00, 0.00, '2024-12-03 02:06:22', '2024-12-03 02:06:22'),
(45, 1, 1, 5, '1000', 'casinglot', 'RCP-U17Z1', 1, 0.00, 0.00, '2024-12-03 02:07:30', '2024-12-03 02:07:30'),
(46, 2, 4, 3, '33333', 'assda', 'RCP-34U45', 1, 0.00, 0.00, '2024-12-03 02:49:23', '2024-12-03 02:49:23'),
(47, 3, 3, 2, '3434', '3433', 'RCP-F46XK', 1, 0.00, 0.00, '2024-12-03 02:49:59', '2024-12-03 02:49:59'),
(48, 4, 5, 6, '232324', 'dsfsdf', 'RCP-OSANM', 1, 0.00, 0.00, '2024-12-03 02:50:43', '2024-12-03 02:50:43'),
(49, 5, 6, 7, '323232', 'assda', 'RCP-AUN3Z', 1, 0.00, 0.00, '2024-12-03 02:53:03', '2024-12-03 02:53:03'),
(50, 1, 1, 2, '1000', 'casinglot', 'RCP-2V7IO', 1, NULL, NULL, '2024-12-03 18:25:27', '2024-12-03 18:25:27'),
(51, 1, 1, 2, '100', 'casinglot', 'RCP-NPEB9', 2, NULL, NULL, '2024-12-03 18:26:13', '2024-12-03 18:26:13'),
(52, 2, 3, 3, '1000', 'casinglot', 'RCP-TUENP', 1, NULL, NULL, '2024-12-03 18:30:26', '2024-12-03 18:30:26'),
(53, 3, 4, 5, '1000', 'casinglot', 'RCP-LXOJW', 2, 0.00, 0.00, '2024-12-03 22:12:03', '2024-12-03 22:12:03'),
(54, 4, 5, 6, '1000', 'casinglot', 'RCP-VC2Y4', 2, 0.00, 0.00, '2024-12-03 23:14:49', '2024-12-03 23:14:49'),
(55, 1, 1, 2, '1000', 'casinglot', 'RCP-MB8M0', 2, 0.00, 0.00, '2024-12-04 00:04:49', '2024-12-04 00:04:49'),
(56, 1, 3, 2, '1000', 'maris', 'RCP-OLEJL', 1, 0.00, 0.00, '2024-12-04 00:27:13', '2024-12-04 00:27:13'),
(57, 1, 4, 3, '10000', 'zamboanga', 'RCP-LV9W9', 4, 0.00, 0.00, '2024-12-06 23:54:41', '2024-12-06 23:54:41');

-- --------------------------------------------------------

--
-- Table structure for table `user1`
--

CREATE TABLE `user1` (
  `user_id` int(11) NOT NULL,
  `role` enum('admin','clerk') NOT NULL,
  `info_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `usertype` varchar(255) NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `info_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `usertype`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`, `info_id`) VALUES
(3, 'admin', 'admin@gmail.com', 'admin', NULL, '$2y$12$nO17JAP6ZmEc03LvIgMXfOfjxncqunrQ4UpyZlUvVxtqqFGSjJOb2', NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-28 11:43:54', '2024-10-28 11:43:54', 0),
(4, 'clerk', 'clerk@gmail.com', 'clerk', NULL, '$2y$12$vd2pByLt5lnJ/jikaQplbe6uJKMR9UhmbG9OYmHTx..7SIYnnft/m', NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-29 17:29:36', '2024-10-29 17:29:36', 0);

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `tbl_driver`
--
ALTER TABLE `tbl_driver`
  ADD PRIMARY KEY (`driver_id`);

--
-- Indexes for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_helper`
--
ALTER TABLE `tbl_helper`
  ADD PRIMARY KEY (`helper_id`);

--
-- Indexes for table `tbl_info`
--
ALTER TABLE `tbl_info`
  ADD PRIMARY KEY (`info_id`);

--
-- Indexes for table `tbl_master_stock`
--
ALTER TABLE `tbl_master_stock`
  ADD PRIMARY KEY (`master_stock_id`),
  ADD KEY `tbl_master_stock_ibfk_1` (`product_id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tbl_sales`
--
ALTER TABLE `tbl_sales`
  ADD PRIMARY KEY (`sales_id`);

--
-- Indexes for table `tbl_stock`
--
ALTER TABLE `tbl_stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_stock_history`
--
ALTER TABLE `tbl_stock_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `stock_id` (`stock_id`);

--
-- Indexes for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD UNIQUE KEY `receipt_id` (`receipt_id`),
  ADD UNIQUE KEY `receipt_id_2` (`receipt_id`),
  ADD KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `tbl_transaction_items`
--
ALTER TABLE `tbl_transaction_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `tbl_truck`
--
ALTER TABLE `tbl_truck`
  ADD PRIMARY KEY (`truck_id`);

--
-- Indexes for table `tbl_trucking`
--
ALTER TABLE `tbl_trucking`
  ADD PRIMARY KEY (`trucking_id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`,`driver_id`,`helper_id`),
  ADD KEY `transaction_id_2` (`transaction_id`,`driver_id`,`helper_id`),
  ADD KEY `helper_id` (`helper_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `sales_id` (`sales_id`);

--
-- Indexes for table `trucking_info`
--
ALTER TABLE `trucking_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `helper_id` (`helper_id`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `truck_id` (`truck_id`);

--
-- Indexes for table `user1`
--
ALTER TABLE `user1`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `info_id` (`info_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `info_id` (`info_id`);

--
-- AUTO_INCREMENT for dumped tables
--

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_driver`
--
ALTER TABLE `tbl_driver`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_helper`
--
ALTER TABLE `tbl_helper`
  MODIFY `helper_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_info`
--
ALTER TABLE `tbl_info`
  MODIFY `info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_master_stock`
--
ALTER TABLE `tbl_master_stock`
  MODIFY `master_stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `tbl_stock`
--
ALTER TABLE `tbl_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT for table `tbl_stock_history`
--
ALTER TABLE `tbl_stock_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;

--
-- AUTO_INCREMENT for table `tbl_transaction_items`
--
ALTER TABLE `tbl_transaction_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=274;

--
-- AUTO_INCREMENT for table `tbl_truck`
--
ALTER TABLE `tbl_truck`
  MODIFY `truck_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_trucking`
--
ALTER TABLE `tbl_trucking`
  MODIFY `trucking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trucking_info`
--
ALTER TABLE `trucking_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `user1`
--
ALTER TABLE `user1`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_master_stock`
--
ALTER TABLE `tbl_master_stock`
  ADD CONSTRAINT `tbl_master_stock_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`product_id`);

--
-- Constraints for table `tbl_stock`
--
ALTER TABLE `tbl_stock`
  ADD CONSTRAINT `fk_tbl_stock_product_id` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`product_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_stock_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user1` (`user_id`);

--
-- Constraints for table `tbl_transaction_items`
--
ALTER TABLE `tbl_transaction_items`
  ADD CONSTRAINT `tbl_transaction_items_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `tbl_transactions` (`transaction_id`),
  ADD CONSTRAINT `tbl_transaction_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`product_id`);

--
-- Constraints for table `trucking_info`
--
ALTER TABLE `trucking_info`
  ADD CONSTRAINT `trucking_info_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `tbl_driver` (`driver_id`),
  ADD CONSTRAINT `trucking_info_ibfk_2` FOREIGN KEY (`helper_id`) REFERENCES `tbl_helper` (`helper_id`),
  ADD CONSTRAINT `trucking_info_ibfk_3` FOREIGN KEY (`CustomerID`) REFERENCES `tbl_customers` (`CustomerID`),
  ADD CONSTRAINT `trucking_info_ibfk_4` FOREIGN KEY (`truck_id`) REFERENCES `tbl_truck` (`truck_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
