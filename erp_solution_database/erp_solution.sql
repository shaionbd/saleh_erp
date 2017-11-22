-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 22, 2017 at 09:43 PM
-- Server version: 10.1.22-MariaDB
-- PHP Version: 7.0.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp_solution`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `word_counts` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chunk` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `start_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process_status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `client_id`, `description`, `word_counts`, `chunk`, `start_date`, `end_date`, `price`, `process_status`, `created_at`, `updated_at`) VALUES
(1, 'demo for demo.com', 0, '10000 words', '10000', '0', '14-11-2017', '22-11-2017', '100', 0, '2017-11-10 18:00:00', '2017-11-10 18:00:00'),
(2, 'demo2 for demo.com', 0, '8000 words', '8000', '0', '14-11-2017', '22-11-2017', '80', 0, '2017-11-10 18:00:00', '2017-11-10 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `item_submissions`
--

CREATE TABLE `item_submissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `writter_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `manager_rivision` int(11) DEFAULT '0',
  `admin_rivision` int(11) DEFAULT '0',
  `admin_revision_description` text COLLATE utf8mb4_unicode_ci,
  `manager_revision_description` text COLLATE utf8mb4_unicode_ci,
  `submission_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `re_submission_date` timestamp NULL DEFAULT NULL,
  `is_accepted` int(11) NOT NULL DEFAULT '0',
  `is_rivision_onGoing` int(11) NOT NULL DEFAULT '0',
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_submissions`
--

INSERT INTO `item_submissions` (`id`, `writter_id`, `task_id`, `manager_rivision`, `admin_rivision`, `admin_revision_description`, `manager_revision_description`, `submission_date`, `re_submission_date`, `is_accepted`, `is_rivision_onGoing`, `file`, `created_at`, `updated_at`) VALUES
(5, 1, 1, 2, 0, NULL, 'there are some problem on your  content. Recheck and add 5 keywords', '2017-11-16 18:00:00', '2017-11-12 18:00:00', 0, 0, '1510915491_DESHER.docx', '2017-11-17 04:44:51', '2017-11-17 04:44:51'),
(6, 1, 2, 1, 0, NULL, NULL, '2017-11-16 18:00:00', NULL, 0, 0, '1510915532_ALTER PATHAO.docx', '2017-11-17 04:45:32', '2017-11-17 04:45:32'),
(7, 1, 1, 2, 2, 'there are some problem on your  content. Recheck and add 5 keywords', NULL, '2017-11-17 19:51:24', '2017-11-12 18:00:00', 0, 0, '1510948284_Baiscope.docx', '2017-11-17 13:51:24', '2017-11-17 13:51:24'),
(8, 1, 1, 2, 0, NULL, 'there are some problem on your  content. Recheck and add 5 keywords', '2017-11-17 19:51:24', '2017-11-12 18:00:00', 0, 0, '1510948945_Desherbari.txt', '2017-11-17 14:02:25', '2017-11-17 14:02:25'),
(9, 1, 1, 0, 0, NULL, NULL, '2017-11-20 18:17:28', '2017-11-12 18:00:00', 0, 0, '1511201848_DESHER.docx', '2017-11-20 12:17:28', '2017-11-20 12:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2017_11_08_203613_create_items_table', 1),
(4, '2017_11_08_204018_create_tasks_table', 1),
(5, '2017_11_08_204959_create_item_submissions_table', 1),
(6, '2017_11_20_171456_create_payments_table', 2),
(7, '2017_11_21_163922_create_withdrawals_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(11) NOT NULL,
  `writter_id` int(11) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `writter_share` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager_share` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `task_id`, `writter_id`, `manager_id`, `price`, `writter_share`, `manager_share`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, '100', '60', '10', '2017-11-20 18:00:00', '2017-11-20 18:00:00'),
(2, 2, 1, 3, '80', '48', '8', '2017-11-20 18:00:00', '2017-11-20 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_id` int(11) NOT NULL,
  `writter_id` int(11) NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `word_counts` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chunk` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `process_status` int(11) NOT NULL DEFAULT '0',
  `is_accepted` int(11) NOT NULL DEFAULT '0',
  `on_revision` int(11) NOT NULL DEFAULT '0',
  `submission_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `item_id`, `writter_id`, `description`, `start_date`, `end_date`, `word_counts`, `chunk`, `process_status`, `is_accepted`, `on_revision`, `submission_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'u have 2 complete this alone', '17-11-2017', '22-11-2017', '10000', '0', 4, 1, 1, '2017-11-20 18:17:44', NULL, '2017-11-20 12:17:28'),
(2, 2, 1, 'u have 2 complete this alonemndcsm', '17-11-2017', '22-11-2017', '8000', '0', 4, 1, 0, '2017-11-19 18:17:48', NULL, '2017-11-17 04:45:32'),
(3, 2, 1, 'u have 2 complete this alonemndcsm', '17-11-2017', '22-11-2017', '8000', '0', 4, 1, 0, '2017-10-10 18:17:48', NULL, '2017-11-17 04:45:32'),
(4, 2, 1, 'u have 2 complete this alonemndcsm', '17-11-2017', '22-11-2017', '8000', '0', 4, 1, 0, '2017-10-01 18:17:48', NULL, '2017-11-17 04:45:32'),
(5, 2, 1, 'u have 2 complete this alonemndcsm', '17-11-2017', '22-11-2017', '8000', '0', 4, 1, 0, '2017-09-05 18:17:48', NULL, '2017-11-17 04:45:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_me` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skills` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` int(11) NOT NULL DEFAULT '4',
  `is_available` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `current_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supervisor` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_plus_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `designation`, `about_me`, `website`, `skills`, `experience`, `address`, `password`, `role`, `is_available`, `current_status`, `supervisor`, `fb_link`, `google_plus_link`, `linkedin_link`, `twitter_link`, `github_link`, `image`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test', 'test@gmail.com', '01671234567', 'Content Writter', 'Hey - I am Test, a 22 years old Content Writter from Bangladesh. I\'m now studying in CUET at Mechanical Engineering. Good skill in English.', 'http://www.therankwizard.com', 'Content Writter,SEO,Technical Writter', '1 year', 'Dhaka, Bangladesh', '$2y$10$TfPhyrJM8I3t5OOW3wTXKucTfvvGuK4poRKe2dFZjmRuUZZxLxGeG', 3, '1', NULL, '3', 'https://www.facebook.com/TVirus.me', NULL, NULL, 'https://www.twitter.com/ShaionShaion', 'https://github.com/shaionbd', '1511197392_website.jpg', 'cDrQ5fLrVTgCXwutY0BLDEc2JvXiTZjI9jOR5rJ60BYNEaFrcDZAjjS4qqjs', '2017-11-10 01:38:05', '2017-11-20 11:03:42'),
(2, 'Admin', 'admin@gmail.com', NULL, 'CEO & Founder', NULL, NULL, NULL, NULL, NULL, '$2y$10$TfPhyrJM8I3t5OOW3wTXKucTfvvGuK4poRKe2dFZjmRuUZZxLxGeG', 1, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default.png', 'WyuurCjKS21iQ2Jy8ygxfJzR2mffhjMIXOQH42RViXOBqED4FIHISzENiUo3', '2017-11-10 01:38:05', '2017-11-10 01:38:05'),
(3, 'Test Manager', 'manager@gmail.com', NULL, 'Manager', NULL, NULL, NULL, NULL, NULL, '$2y$10$TfPhyrJM8I3t5OOW3wTXKucTfvvGuK4poRKe2dFZjmRuUZZxLxGeG', 2, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default.png', NULL, '2017-11-10 01:38:05', '2017-11-10 01:38:05');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `request_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `user_id`, `amount`, `request_type`, `account_no`, `request_status`, `created_at`, `updated_at`) VALUES
(1, 1, '30', 'bkash', '01679584270', '1', '2017-11-20 18:00:00', '2017-11-20 18:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_submissions`
--
ALTER TABLE `item_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `item_submissions`
--
ALTER TABLE `item_submissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
