-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2026 at 09:13 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `freelance_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `service_id`, `customer_id`, `sender_id`, `message`, `created_at`) VALUES
(1, 7, 0, 3, 'hello', '2026-11-14 07:34:50'),
(2, 7, 0, 2, 'hello kub', '2026-11-14 07:37:06'),
(3, 7, 0, 2, 'ฟหกฟกห', '2026-11-14 07:39:12'),
(4, 7, 0, 2, 'หกหก้ห่ก', '2026-11-14 07:39:48'),
(5, 7, 0, 3, 'sssss', '2026-11-14 07:40:00'),
(6, 7, 0, 3, '1223', '2026-11-14 07:42:43'),
(7, 7, 0, 3, 'ssss', '2026-11-14 07:43:34'),
(8, 7, 0, 3, 'ssswwwwwwww', '2026-11-14 07:43:57'),
(9, 7, 0, 3, 'ssssssqq', '2026-11-14 07:44:34'),
(10, 7, 0, 4, 'sadsad', '2026-11-14 07:45:46'),
(11, 7, 4, 4, 'hello', '2026-11-14 07:50:48'),
(12, 7, 5, 5, 'jiiii', '2026-11-14 07:51:27'),
(13, 7, 4, 4, 'ssss', '2026-11-14 07:56:20'),
(14, 7, 4, 4, 'wwwwwwwwwwwwww', '2026-11-14 07:57:09'),
(15, 7, 5, 5, 'klosd', '2026-11-14 07:58:59'),
(16, 7, 4, 4, '322323', '2026-11-14 08:10:20');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `freelance_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('รออนุมัติ','อนุมัติ','ไม่อนุมัติ') DEFAULT 'รออนุมัติ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `freelance_id`, `title`, `category`, `description`, `price`, `image_path`, `status`, `created_at`) VALUES
(7, 2, 'asd', 'ตัดต่อ & วิดีโอ', 'sadasd', '5000.00', '1794641405_6af80dfd69611.jpg', 'อนุมัติ', '2026-11-14 07:30:05'),
(8, 2, 'ssss', 'กราฟิก & ดีไซน์', 'sdd', '10000000.00', '1794643641_6af816b9f1203.jpg', 'อนุมัติ', '2026-11-14 08:07:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `role` enum('admin','freelance','customer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `role`) VALUES
(1, 'admin', '1234', 'ผู้ดูแลระบบ', 'admin'),
(2, 'freelance1', '1234', 'ช่างภาพมือโปร สมชาย', 'freelance'),
(3, 'customer1', '1234', 'คุณลูกค้า ใจดี', 'customer'),
(4, 'folk', '1234', 'folk', 'customer'),
(5, 'mon', '1234', 'mon', 'customer'),
(6, 'folk555', '1234', 'folk555', 'freelance');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `freelance_id` (`freelance_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`freelance_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
