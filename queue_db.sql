-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 12:25 PM
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
-- Database: `queue_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `history_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `status` text NOT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_history`
--

INSERT INTO `payment_history` (`history_id`, `student_id`, `transaction_id`, `status`, `date`) VALUES
(1, 2, 1, 'completed', '2025-11-09 16:28:36'),
(2, 3, 2, 'completed', '2025-11-09 16:28:50'),
(3, 1, 3, 'completed', '2025-11-09 16:37:32'),
(4, 1, 4, 'completed', '2025-11-09 20:51:37'),
(5, 2, 5, 'completed', '2025-11-09 21:05:51'),
(6, 1, 6, 'completed', '2025-11-11 21:21:01'),
(7, 1, 10, 'completed', '2025-11-13 00:23:45'),
(8, 2, 13, 'completed', '2025-11-13 00:30:51'),
(9, 3, 14, 'completed', '2025-11-13 17:23:36'),
(10, 1, 15, 'completed', '2025-11-13 18:20:14'),
(11, 3, 16, 'completed', '2025-11-15 00:46:43'),
(12, 3, 17, 'completed', '2025-11-15 00:49:22'),
(13, 3, 18, 'completed', '2025-11-15 00:58:38'),
(14, 2, 19, 'completed', '2025-11-15 01:20:13');

-- --------------------------------------------------------

--
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `queue_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `queue_number` int(11) NOT NULL,
  `status` enum('waiting','serving','served','voided') DEFAULT 'waiting',
  `time_in` datetime DEFAULT current_timestamp(),
  `time_out` int(11) DEFAULT NULL,
  `timer_start` int(11) DEFAULT NULL,
  `estimated_wait_time` int(11) DEFAULT NULL,
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `payment_for` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`queue_id`, `student_id`, `queue_number`, `status`, `time_in`, `time_out`, `timer_start`, `estimated_wait_time`, `payment_amount`, `payment_for`, `amount`) VALUES
(1, 1, 1, 'voided', '2025-11-09 16:09:13', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(2, 2, 2, 'served', '2025-11-09 16:26:10', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(3, 3, 3, 'served', '2025-11-09 16:27:30', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(4, 1, 4, 'served', '2025-11-09 16:36:36', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(5, 1, 5, 'voided', '2025-11-09 20:39:34', 2147483647, 2147483647, NULL, 4000.00, 'Tuition Fee', NULL),
(6, 1, 6, 'served', '2025-11-09 20:51:15', 2147483647, 2147483647, NULL, 1000.00, 'Tuition Fee', NULL),
(7, 2, 7, 'served', '2025-11-09 21:05:23', 2147483647, 2147483647, NULL, 5000.00, 'Tuition Fee', NULL),
(8, 3, 8, 'voided', '2025-11-09 21:11:32', 2147483647, 2147483647, NULL, 1000.00, 'Tuition Fee', NULL),
(9, 1, 1, 'served', '2025-11-11 21:19:12', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(10, 1, 1, 'voided', '2025-11-12 23:34:55', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(11, 1, 1, 'voided', '2025-11-13 00:02:39', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(12, 2, 2, 'voided', '2025-11-13 00:03:31', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(13, 3, 3, 'voided', '2025-11-13 00:06:51', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(14, 3, 4, 'voided', '2025-11-13 00:07:26', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(15, 3, 5, 'voided', '2025-11-13 00:08:50', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(16, 2, 6, 'voided', '2025-11-13 00:08:59', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(17, 3, 7, 'voided', '2025-11-13 00:20:36', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(18, 3, 8, 'voided', '2025-11-13 00:22:24', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(19, 1, 9, 'served', '2025-11-13 00:23:00', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(22, 2, 10, 'served', '2025-11-13 00:28:39', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(23, 2, 11, 'voided', '2025-11-13 00:33:44', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(24, 2, 12, 'voided', '2025-11-13 00:35:04', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(25, 3, 13, 'voided', '2025-11-13 00:35:37', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(26, 3, 14, 'voided', '2025-11-13 00:36:36', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(27, 2, 15, 'voided', '2025-11-13 00:37:14', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(28, 1, 16, 'voided', '2025-11-13 16:45:51', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(29, 1, 17, 'voided', '2025-11-13 16:47:11', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(30, 2, 18, 'voided', '2025-11-13 16:56:50', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(31, 2, 19, 'voided', '2025-11-13 16:58:59', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(32, 3, 20, 'voided', '2025-11-13 17:01:26', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(33, 3, 21, 'served', '2025-11-13 17:23:22', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(34, 3, 22, 'voided', '2025-11-13 17:25:11', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(35, 2, 23, 'voided', '2025-11-13 17:25:34', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(36, 2, 24, 'voided', '2025-11-13 17:26:53', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(37, 3, 25, 'voided', '2025-11-13 17:27:19', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(38, 2, 26, '', '2025-11-13 18:12:03', 2147483647, 2147483647, NULL, 200.00, 'tuition', NULL),
(39, 1, 27, '', '2025-11-13 18:12:26', 2147483647, 2147483647, NULL, 100.00, 'tuition', NULL),
(40, 1, 28, '', '2025-11-13 18:15:16', 2147483647, 2147483647, NULL, 1000.00, 'tuition', NULL),
(41, 2, 29, 'voided', '2025-11-13 18:15:37', 2147483647, 2147483647, NULL, 1000.00, 'tuition', NULL),
(42, 1, 30, 'served', '2025-11-13 18:19:19', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(43, 3, 31, 'voided', '2025-11-13 18:19:31', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(44, 3, 1, 'voided', '2025-11-15 00:32:50', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(45, 3, 2, 'voided', '2025-11-15 00:39:51', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(46, 3, 3, 'voided', '2025-11-15 00:42:52', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(47, 3, 4, 'voided', '2025-11-15 00:43:48', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(48, 3, 5, 'served', '2025-11-15 00:46:24', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(49, 3, 6, 'served', '2025-11-15 00:49:00', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(50, 3, 7, 'served', '2025-11-15 00:58:23', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(51, 2, 8, 'voided', '2025-11-15 01:07:05', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(52, 2, 9, 'served', '2025-11-15 01:19:59', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(53, 2, 10, 'voided', '2025-11-15 01:22:50', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(54, 2, 11, 'voided', '2025-11-15 01:25:39', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(55, 2, 12, 'voided', '2025-11-15 01:28:03', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(56, 2, 13, 'voided', '2025-11-15 01:31:28', 2147483647, 2147483647, NULL, NULL, NULL, NULL),
(57, 2, 14, 'served', '2025-11-15 15:32:38', 1763192241, NULL, NULL, NULL, NULL, NULL),
(58, 2, 15, 'voided', '2025-11-15 15:33:06', NULL, NULL, NULL, NULL, NULL, NULL),
(59, 2, 16, 'voided', '2025-11-15 15:55:44', NULL, NULL, NULL, NULL, NULL, NULL),
(60, 2, 17, 'voided', '2025-11-15 15:55:58', NULL, NULL, NULL, NULL, NULL, NULL),
(61, 2, 18, 'voided', '2025-11-15 16:02:02', NULL, NULL, NULL, NULL, NULL, NULL),
(62, 2, 19, 'voided', '2025-11-15 16:02:58', NULL, NULL, NULL, NULL, NULL, NULL),
(63, 2, 20, 'served', '2025-11-15 16:03:07', 2147483647, NULL, NULL, NULL, NULL, NULL),
(64, 1, 21, 'served', '2025-11-15 16:11:39', 2147483647, NULL, NULL, NULL, NULL, NULL),
(65, 1, 22, 'served', '2025-11-15 16:45:48', 2147483647, NULL, NULL, NULL, NULL, NULL),
(66, 2, 23, 'voided', '2025-11-15 16:46:09', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `report_type` text NOT NULL,
  `generated_by` int(11) NOT NULL,
  `date_generated` datetime DEFAULT current_timestamp(),
  `report_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `year_level` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `course`, `year_level`, `email`, `created_at`, `password`) VALUES
(1, 'Brent Adrian Kyro L. Alabag', 'BSIT', '3rd Year', 'brentalabag@slc.edu', '2025-11-09 15:15:38', '123'),
(2, 'Ardy A. Aquino', 'BSIT', '3rd Year', 'adryaquino@slc.edu', '2025-11-09 15:16:08', '456'),
(3, 'Mark Lester Rivera', 'BSIT', '3rd Year', 'lestermark@slc.edu', '2025-11-09 15:16:48', '789');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` text NOT NULL,
  `setting_value` text NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_id`, `setting_key`, `setting_value`, `description`) VALUES
(1, 'max_students_per_day', '100', 'Maximum number of students that can be served per day'),
(2, 'student_time_limit', '5', 'Time limit in minutes for students to respond when called'),
(3, 'service_start_time', '08:00', 'Service start time'),
(4, 'service_end_time', '17:00', 'Service end time'),
(5, 'cut_off_time', '16:30', 'Last queue entry time');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `queue_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` enum('cash','card','digital') NOT NULL,
  `date_paid` datetime DEFAULT current_timestamp(),
  `cashier_id` int(11) NOT NULL,
  `status` enum('completed','voided') NOT NULL DEFAULT 'completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `queue_id`, `amount`, `payment_type`, `date_paid`, `cashier_id`, `status`) VALUES
(1, 2, 5000.00, 'cash', '2025-11-09 16:28:36', 2, 'completed'),
(2, 3, 1000.00, 'cash', '2025-11-09 16:28:50', 2, 'completed'),
(3, 4, 6000.00, 'cash', '2025-11-09 16:37:32', 2, 'completed'),
(4, 6, 1000.00, 'cash', '2025-11-09 20:51:37', 1, 'completed'),
(5, 7, 5000.00, 'cash', '2025-11-09 21:05:51', 2, 'completed'),
(6, 9, 1000.00, 'cash', '2025-11-11 21:21:01', 2, 'completed'),
(10, 19, 1000.00, 'cash', '2025-11-13 00:23:45', 2, 'completed'),
(13, 22, 2000.00, 'cash', '2025-11-13 00:30:51', 2, 'completed'),
(14, 33, 1000.00, 'cash', '2025-11-13 17:23:36', 2, 'completed'),
(15, 42, 222.00, 'cash', '2025-11-13 18:20:13', 2, 'completed'),
(16, 48, 2000.00, 'cash', '2025-11-15 00:46:43', 2, 'completed'),
(17, 49, 1000.00, 'cash', '2025-11-15 00:49:22', 2, 'completed'),
(18, 50, 1000.00, 'cash', '2025-11-15 00:58:38', 2, 'completed'),
(19, 52, 2000.00, 'cash', '2025-11-15 01:20:13', 2, 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','cashier') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Admin User', 'admin', '123', 'admin', '2025-11-09 15:13:19'),
(2, 'Cashier', 'cashier', '456', 'cashier', '2025-11-09 15:14:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_payment_history_students` (`student_id`),
  ADD KEY `fk_payment_history_transactions` (`transaction_id`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`queue_id`),
  ADD KEY `fk_queue_students` (`student_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `fk_reports_users` (`generated_by`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `fk_transactions_queue` (`queue_id`),
  ADD KEY `fk_transactions_cashier` (`cashier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `queue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD CONSTRAINT `fk_payment_history_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payment_history_transactions` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) ON UPDATE CASCADE;

--
-- Constraints for table `queue`
--
ALTER TABLE `queue`
  ADD CONSTRAINT `fk_queue_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_reports_users` FOREIGN KEY (`generated_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_cashier` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transactions_queue` FOREIGN KEY (`queue_id`) REFERENCES `queue` (`queue_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
