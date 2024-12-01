-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 02, 2024 lúc 12:34 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dinhtuanduy`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(19, 12, 14, 1, 20000.00, '2024-10-28 22:57:10', '2024-10-28 22:57:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(3, 'category', '2024-09-09 06:20:14', '2024-09-09 06:20:14'),
(5, 'Tất cả sản phẩm', '2024-09-09 07:41:23', '2024-09-09 07:41:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
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
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_08_19_075217_create_categories_table', 1),
(6, '2024_08_25_190006_create_products_table', 1),
(7, '2024_08_26_080830_create_users_table', 1),
(8, '2024_08_26_081439_add_role_to_users_table', 1),
(9, '2024_09_11_180922_create_cart_table', 1),
(10, '2024_09_16_072112_create_orders_table', 1),
(11, '2024_09_22_073025_create_order_items_table', 1),
(12, '2024_09_23_071436_add_image_to_products_table', 1),
(13, '2024_09_23_071903_add_address_and_phone_to_users_table', 1),
(14, '2024_09_23_072727_add_status_to_orders_table', 1),
(15, '2024_10_06_165115_add_is_confirmed_to_orders_table', 1),
(16, '2024_10_07_063018_create_payments_table', 1),
(17, '2024_10_28_061722_add_production_date_to_products_table', 1),
(18, '2024_10_28_065209_add_payment_date_to_orders_table', 1),
(24, '2024_10_14_063628_create_products_table', 2),
(25, '2024_10_14_064047_create_categories_table', 3),
(26, '2024_10_28_072643_add_payment_date_and_discount_to_orders_table', 4),
(27, '2024_10_28_075501_add_role_to_users_table', 5),
(28, '2024_10_28_091044_add_otp_to_orders_table', 6);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `payment_date` date DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_code` varchar(255) DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `email`, `phone`, `address`, `payment_method`, `status`, `total_amount`, `created_at`, `updated_at`, `is_confirmed`, `payment_date`, `discount_amount`, `discount_code`, `otp`) VALUES
(87, 12, 'Dinh Tuan Duy', 'dinhtuanduy1@gmail.com', '0392861103', 'Ninh Bình', 'cash', 'shipped', 32000.00, '2024-10-28 01:24:52', '2024-10-28 01:53:19', 0, '2024-11-02', 8000.00, 'SAVE10', NULL),
(88, 12, 'Dinh Tuan Duy', 'dinhtuanduy1@gmail.com', '0392861103', 'ad', 'online', 'shipped', 112000.00, '2024-10-28 01:34:24', '2024-10-28 01:53:21', 0, '2024-11-02', 28000.00, 'SAVE10', NULL),
(89, 12, 'Dinh Tuan Duy', 'dinhtuanduy1@gmail.com', '0392861103', 's', 'cash', 'shipped', 20000.00, '2024-10-28 01:34:47', '2024-10-28 01:53:22', 0, '2024-10-31', 0.00, NULL, NULL),
(90, 12, 'Dinh Tuan Duy', 'dinhtuanduy1@gmail.com', '0392861103', 's', 'online', 'delivered', 18000.00, '2024-10-28 01:35:15', '2024-10-28 01:53:41', 0, '2024-11-02', 2000.00, NULL, NULL),
(91, 12, 'Dinh Tuan Duy', 'dinhtuanduy1@gmail.com', '0392861103', 's', 'online', 'delivered', 18000.00, '2024-10-28 01:35:53', '2024-10-28 01:53:39', 0, '2024-10-28', 2000.00, 'SAVE10', NULL),
(92, 12, 'Dinh Tuan Duy', 'dinhtuanduy1@gmail.com', '0392861103', 'nb', 'PayPal', 'delivered', 16000.00, '2024-10-28 01:43:36', '2024-10-28 01:53:37', 0, '2024-11-03', 4000.00, 'SAVE10', NULL),
(93, 12, 'Dinh Tuan Duy', 'dinhtuanduy1@gmail.com', '0392861103', 'a', 'Thẻ tín dụng', 'pending', 16000.00, '2024-10-28 01:46:02', '2024-10-28 01:46:02', 0, '2024-11-02', 4000.00, 'SAVE10', NULL),
(94, 12, 'Dinh Tuan Duy', 'dinhtuanduy1@gmail.com', '0392861103', 'a', 'Thẻ tín dụng', 'pending', 96000.00, '2024-10-28 01:48:22', '2024-10-28 01:48:22', 0, '2024-11-02', 24000.00, 'SAVE10', NULL),
(95, 12, 'Dinh Tuan Duy', 'dinhtuanduy1@gmail.com', '0392861103', 'a', 'Thẻ tín dụng', 'delivered', 16000.00, '2024-10-28 01:51:33', '2024-10-28 01:53:35', 0, '2024-11-03', 4000.00, 'SAVE10', NULL),
(96, 12, 'Dinh Tuan Duy', 'dinhtuanduy1@gmail.com', '0392861103', 'AD', 'Thẻ tín dụng', 'delivered', 18000.00, '2024-10-28 01:53:00', '2024-10-28 01:53:32', 0, '2024-11-02', 2000.00, 'SAVE10', NULL),
(97, 12, 'Dinh Tuan Duy', 'dinhtuanduy1@gmail.com', '0392861103', 'NB', 'PayPal', 'pending', 18000.00, '2024-10-28 02:44:25', '2024-10-28 02:44:25', 0, '2024-11-02', 2000.00, 'SAVE10', NULL),
(98, 12, 'Đặng Dương', 'admin@example.com', '0292861345', 'hn', 'Thẻ tín dụng', 'pending', 18000.00, '2024-10-28 03:28:48', '2024-10-28 03:28:48', 0, '2024-11-03', 2000.00, 'HAVE11', NULL),
(99, 12, 'Lê Tuấn Dương', 'admin@example.com', '0292861345', 'hn', 'PayPal', 'pending', 18000.00, '2024-10-28 08:00:12', '2024-10-28 08:00:12', 0, '2024-11-02', 2000.00, 'HAVE11', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(91, 87, 14, 2, 20000.00, '2024-10-28 01:24:52', '2024-10-28 01:24:52'),
(92, 88, 14, 1, 20000.00, '2024-10-28 01:34:24', '2024-10-28 01:34:24'),
(94, 89, 15, 1, 20000.00, '2024-10-28 01:34:47', '2024-10-28 01:34:47'),
(95, 90, 15, 1, 20000.00, '2024-10-28 01:35:15', '2024-10-28 01:35:15'),
(96, 91, 14, 1, 20000.00, '2024-10-28 01:35:53', '2024-10-28 01:35:53'),
(97, 92, 14, 1, 20000.00, '2024-10-28 01:43:36', '2024-10-28 01:43:36'),
(98, 93, 15, 1, 20000.00, '2024-10-28 01:46:02', '2024-10-28 01:46:02'),
(100, 95, 15, 1, 20000.00, '2024-10-28 01:51:33', '2024-10-28 01:51:33'),
(101, 96, 14, 1, 20000.00, '2024-10-28 01:53:00', '2024-10-28 01:53:00'),
(102, 97, 14, 1, 20000.00, '2024-10-28 02:44:25', '2024-10-28 02:44:25'),
(103, 98, 14, 1, 20000.00, '2024-10-28 03:28:48', '2024-10-28 03:28:48'),
(104, 99, 17, 1, 20000.00, '2024-10-28 08:00:12', '2024-10-28 08:00:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` enum('COD','online') NOT NULL DEFAULT 'online',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
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
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `production_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `quantity`, `image`, `category_id`, `created_at`, `updated_at`, `production_date`) VALUES
(14, 'Giày Nike', 'đẹp', 20000.00, 4, 'images/products/GMqP6buv6P3qkxnkQNHlV38ScHw5Rr82HyBFIXa9.jpg', 3, '2024-10-28 01:08:25', '2024-10-28 07:58:08', '2003-04-19'),
(15, 'Giày Adidas', 'đẹp', 20000.00, 9, 'images/products/zoKIPAlwMiU7YWqfEeWa2uHg1IncLSfkCTMLH2WQ.jpg', 5, '2024-10-28 01:09:54', '2024-10-28 07:58:20', '2003-04-19'),
(17, 'Giày Balenciaga', 'đẹp vl', 20000.00, 11, 'images/products/l4OY3Xff51B14kknhnyZna00yo8MAhvv2tNOaXCV.jpg', 3, '2024-10-28 03:21:00', '2024-10-28 08:00:12', '2003-04-19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `address`, `phone`, `role`) VALUES
(11, 'Dinh Tuan Duy', 'admin1@example.com', NULL, '$2y$12$Ptb47htwb2AjjREUZhwxueaSTb6NeuBaG3kVsfh6kOF3dRvDymkCe', NULL, '2024-10-28 00:59:56', '2024-10-28 00:59:56', 'admin1@example.com', '0392861103', 'user'),
(12, 'Dinh Tuan Duy', 'dinhtuanduy1@gmail.com', NULL, '$2y$12$/nWmQn5sZ/b5LuVNXRkQ7.zXypPnOJonwytFddi7H4h.XXhltd.sC', NULL, '2024-10-28 01:01:26', '2024-10-28 01:01:26', 'Xã Gia Lập, Tỉnh Ninh Bình', '0392861103', 'admin');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_user_id_foreign` (`user_id`),
  ADD KEY `cart_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
