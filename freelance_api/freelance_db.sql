-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2026 at 09:40 AM
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
(18, 10, 5, 5, 'dd', '2026-11-14 07:54:21'),
(19, 10, 5, 2, 'kuy', '2026-11-14 07:54:41'),
(20, 10, 4, 4, 'ddkub', '2026-11-14 07:55:34'),
(21, 10, 4, 2, 'kuyy', '2026-11-14 07:55:54'),
(22, 11, 5, 5, 'dddddddd', '2026-11-14 07:57:05'),
(23, 11, 4, 4, 'd kub', '2026-11-14 07:57:27'),
(24, 12, 4, 4, 'sadasd', '2026-11-14 08:12:54'),
(25, 12, 4, 7, 'sssss', '2026-11-14 08:13:41');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `freelance_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 5,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `service_id`, `freelance_id`, `customer_id`, `rating`, `comment`, `created_at`) VALUES
(1, 12, 7, 4, 4, 'งานดีครับทำสวยงานเร็ว', '2026-11-14 08:34:13'),
(2, 12, 7, 5, 5, 'ดีครับ', '2026-11-14 08:39:03');

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
(10, 2, 'ฟกฟก', 'กราฟิก & ดีไซน์', 'ฟกฟก', '44614.00', '1794642287_6af8116f392b2.PNG', 'ไม่อนุมัติ', '2026-11-14 07:44:47'),
(11, 2, 'dadadada', 'การตลาด & โฆษณา', 'adadada', '67.00', '1794643000_6af814381d195.PNG', 'ไม่อนุมัติ', '2026-11-14 07:56:40'),
(12, 7, 'รับทำห้องนอน', 'กราฟิก & ดีไซน์', 'ทำสวยมากค่ะ', '75000.00', '1794643311_6af8156f0240a.jpg', 'อนุมัติ', '2026-11-14 08:01:51');

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
(6, 'folk555', '1234', 'folk555', 'freelance'),
(7, 'test1', '1234', 'test1', 'freelance'),
(8, 'test2', '1234', 'test2', 'freelance');

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
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
