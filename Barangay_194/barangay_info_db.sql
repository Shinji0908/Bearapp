-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2025 at 11:49 AM
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
-- Database: `barangay_info_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminlogs`
--

CREATE TABLE `adminlogs` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminlogs`
--

INSERT INTO `adminlogs` (`log_id`, `admin_id`, `user_id`, `action`, `remarks`, `created_at`) VALUES
(1, 1, 10, '0', 'Profile approved', '2024-12-19 05:29:58'),
(3, 1, 13, '0', 'Profile approved', '2024-12-19 16:26:22'),
(4, 1, 14, '0', 'Profile approved', '2024-12-19 16:33:09'),
(5, 1, 11, '0', 'Profile approved', '2024-12-19 16:34:09'),
(6, 1, 15, '0', 'Profile approved', '2024-12-21 04:49:58'),
(7, 1, 18, '0', 'Profile approved', '2024-12-21 13:16:32'),
(8, 1, 17, '0', 'Profile rejected', '2024-12-21 13:16:40');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`user_id`, `email`, `password`, `first_name`, `last_name`, `role`) VALUES
(1, 'newadmin@example.com', '$2y$10$8E1.uSjnUKEMtWRlG4v7VOnTYK.PEf4uel6f4CV6J.TJvCb8pCbqy', 'AdminFirstName', 'AdminLastName', 'Admin'),
(2, 'admin@example.com', '$2y$10$I8ciMwrjGwZKjwRVvL863.pfBRH19vaPsRUBvlnvhg7sYrJ7/Sc8.', 'Admin', 'User', ''),
(3, 's.roxiemae@yahoo.com', '$2y$10$ecLIyBn0uxREVQrCdpUxoOJFBXyjW1lmlzLoXVLeqTzu/i7uVc9g2', 'Roxie Mae', 'Inglesa', '');

-- --------------------------------------------------------

--
-- Table structure for table `documentrequests`
--

CREATE TABLE `documentrequests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `document_type` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `documentrequests`
--

INSERT INTO `documentrequests` (`request_id`, `user_id`, `document_type`, `name`, `age`, `address`, `purpose`, `request_date`, `status`) VALUES
(1, 10, 'Barangay Clearance', NULL, NULL, NULL, 'Test', '2024-12-19 16:59:35', 'Rejected'),
(2, 10, 'Barangay Indigence', NULL, NULL, NULL, 'Test', '2024-12-19 17:00:25', 'Rejected'),
(3, 10, 'Barangay Clearance', NULL, NULL, NULL, 'Test', '2024-12-19 17:03:45', 'Rejected'),
(4, 10, 'Barangay Clearance', NULL, NULL, NULL, 'Test', '2024-12-19 17:15:49', 'Rejected'),
(5, 10, 'Barangay Clearance', 'DocTest', 1, 'Test', 'Test', '2024-12-19 18:04:09', 'Approved'),
(6, 10, 'Barangay Indigence', 'DocTest10', 10, '10 Test', 'TEST #10', '2024-12-19 18:04:56', 'Approved'),
(7, 10, 'Barangay Clearance', 'DocTest10', 10, '10 Test', 'TEST #11', '2024-12-19 18:06:39', 'Rejected'),
(8, 10, 'Barangay Indigence', 'RedirectTest3Secs', 3, '3 Seconds', '3 Seconds', '2024-12-19 18:07:27', 'Rejected'),
(9, 10, 'Barangay Clearance', 'Johnrey Inglesa', 35, 'Earth', 'Finals', '2024-12-19 18:19:34', 'Approved'),
(10, 10, 'Barangay Clearance', 'Johnrey Inglesa', 35, 'Earth', 'Finals', '2024-12-19 18:22:20', 'Approved'),
(11, 10, 'Barangay Indigence', 'Johnrey Inglesa', 35, 'Earth', 'Finals', '2024-12-19 18:23:47', 'Approved'),
(12, 10, 'Barangay Certificate', 'Johnrey Inglesa', 35, 'Earth', '123', '2024-12-19 18:25:03', 'Approved'),
(13, 10, 'Barangay Clearance', 'Allen Sebastian', 16, 'Pacheco Tondo', 'NBI Blotter', '2024-12-19 21:34:05', 'Approved'),
(14, 14, 'Barangay Clearance', 'Johnrey Inglesa', 20, 'Earth', '123123', '2024-12-19 21:35:52', 'Approved'),
(15, 10, 'Barangay Indigence', 'Allen Sebastian', 20, 'Pacheco Tondo', 'NBI Blotter', '2024-12-19 21:37:19', 'Approved'),
(16, 10, 'Barangay Clearance', 'Johnrey Inglesa', 20, 'Earth', '123123\r\n', '2024-12-19 21:50:26', 'Approved'),
(17, 10, 'Barangay Clearance', 'Johnrey Inglesa', 20, 'Earth', '123', '2024-12-19 21:54:14', 'Approved'),
(18, 10, 'Barangay Clearance', 'Johnrey Inglesa', 20, 'Earth', '123', '2024-12-19 22:10:35', 'Approved'),
(19, 10, 'Barangay Clearance', 'Johnrey Inglesa', 20, 'Earth', '123', '2024-12-19 23:02:22', 'Approved'),
(20, 10, 'Barangay Certificate', 'Johnrey Inglesa', 123, 'asd', 'asd', '2024-12-19 23:03:39', 'Approved'),
(21, 10, 'Barangay Clearance', 'Johnrey Inglesa', 20, '123', '123asd', '2024-12-19 23:48:38', 'Approved'),
(22, 10, 'Barangay Clearance', 'Johnrey Inglesa', 123, '123', '123', '2024-12-19 23:50:21', 'Approved'),
(23, 10, 'Barangay Certificate', 'asd', 12, 'asdas', 'dasd', '2024-12-19 23:51:41', 'Rejected'),
(24, 10, 'Barangay Clearance', 'asd', 123, 'asd', 'asd', '2024-12-19 23:56:00', 'Approved'),
(25, 10, 'Barangay Clearance', 'asd', 123, 'asd', 'asd', '2024-12-20 01:11:56', 'Approved'),
(26, 10, 'Barangay Indigence', 'asd', 1, 'as', 'asd', '2024-12-20 01:15:56', 'Approved'),
(27, 10, 'Barangay Certificate', 'asd', 1, 'asd', 'asd', '2024-12-20 01:19:30', 'Approved'),
(28, 13, 'Barangay Clearance', 'Yosi Break', 12, '2435 ORO B STREET STA ANA MANILA', 'HEHE', '2024-12-20 12:17:42', 'Approved'),
(56, 13, 'Barangay Clearance', 'Ronald Asim', 22, '2435 ORO B STREET STA ANA MANILA', '23456', '2024-12-20 14:49:24', 'Rejected'),
(57, 13, 'Barangay Clearance', 'Ronald Asim', 22, '2435 ORO B STREET STA ANA MANILA', '23456', '2024-12-20 14:50:40', 'Approved'),
(58, 15, 'Barangay Clearance', 'Test4', 1, 'Manila', 'Clearance', '2024-12-21 04:07:20', 'Approved'),
(59, 13, 'Barangay Certificate', 'Test4', 12, 'Manila', 'sige', '2024-12-21 07:02:36', 'Approved'),
(60, 18, 'Barangay Indigence', 'John Rey G. Inglesa', 21, 'Barangay 770, 2435 Oro B Street Sta Ana Manila', 'For Job ', '2024-12-21 13:17:28', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `start_date`, `end_date`, `description`, `created_at`) VALUES
(2, 'Christmas', '2024-12-25', '2024-12-25', NULL, '2024-12-20 09:19:05'),
(9, 'NEW YEAR', '2025-01-01', '2025-01-01', '', '2024-12-20 17:49:53'),
(10, 'SENIOR PAYOUT', '2024-12-17', '2024-12-17', '', '2024-12-20 17:51:02'),
(11, 'Example', '2024-12-12', '2024-12-12', '', '2024-12-21 04:48:04'),
(12, 'Example ', '2024-12-13', '2024-12-13', '', '2024-12-21 04:48:28'),
(13, 'BIRTHDAY NI ARGUS', '2024-04-05', '2024-04-05', '', '2024-12-21 07:01:14'),
(14, '123', '2024-12-10', '2024-12-10', '', '2024-12-21 10:20:40'),
(15, 'ENRIK', '2024-12-19', '2024-12-19', '', '2024-12-21 12:04:22'),
(16, 'Clean up drive', '2024-12-29', '2024-12-29', '', '2024-12-21 13:05:10'),
(17, 'Senior party', '2024-12-11', '2024-12-11', '', '2024-12-21 13:12:41');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `message`, `status`, `created_at`) VALUES
(6, 10, 'Your document request has been approved.', 'read', '2024-12-19 23:51:56'),
(7, 10, 'Your document request has been submitted.', 'read', '2024-12-19 23:56:00'),
(8, 10, 'Your document request has been submitted.', 'read', '2024-12-20 01:11:56'),
(9, 10, 'Your document request has been submitted.', 'read', '2024-12-20 01:15:56'),
(10, 10, 'Your document request has been submitted.', 'read', '2024-12-20 01:19:30'),
(11, 10, 'Your document request has been approved.', 'read', '2024-12-20 01:19:45'),
(12, 10, 'Your document request has been rejected.', 'read', '2024-12-20 01:19:50'),
(13, 10, 'Your document request has been approved.', 'unread', '2024-12-20 01:20:39'),
(14, 13, 'Your document request has been submitted.', 'unread', '2024-12-20 12:17:42'),
(23, 10, 'Your document request has been approved.', 'unread', '2024-12-20 12:54:04'),
(24, 13, 'Your document request has been approved.', 'read', '2024-12-20 12:54:11'),
(25, 10, 'Your document request has been approved.', 'unread', '2024-12-20 14:18:04'),
(26, 13, 'Your document request has been submitted.', 'read', '2024-12-20 14:49:24'),
(27, 13, 'Your document request has been submitted.', 'read', '2024-12-20 14:50:40'),
(28, 13, 'Your document request has been approved.', 'read', '2024-12-20 14:50:55'),
(29, 15, 'Your document request has been submitted.', 'unread', '2024-12-21 04:07:20'),
(30, 10, 'Your document request has been approved.', 'unread', '2024-12-21 04:20:41'),
(31, 13, 'Your document request has been rejected.', 'unread', '2024-12-21 04:21:12'),
(32, 15, 'Your document request has been approved.', 'unread', '2024-12-21 04:50:02'),
(33, 13, 'Your document request has been submitted.', 'unread', '2024-12-21 07:02:36'),
(34, 13, 'Your document request has been approved.', 'unread', '2024-12-21 07:03:01'),
(35, 18, 'Your document request has been submitted.', 'unread', '2024-12-21 13:17:28'),
(36, 18, 'Your document request has been approved.', 'unread', '2024-12-21 13:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `residentprofiles`
--

CREATE TABLE `residentprofiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `birthplace` varchar(100) DEFAULT NULL,
  `contact_number` varchar(15) NOT NULL,
  `age` int(11) NOT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) NOT NULL,
  `district` varchar(100) NOT NULL,
  `citizenship` varchar(50) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `work` varchar(100) DEFAULT NULL,
  `precinct_number` int(11) DEFAULT NULL,
  `spouse_name` varchar(255) DEFAULT NULL,
  `spouse_age` int(11) DEFAULT NULL,
  `spouse_birthday` date DEFAULT NULL,
  `spouse_address` varchar(255) DEFAULT NULL,
  `spouse_work` varchar(100) DEFAULT NULL,
  `child_name` varchar(255) DEFAULT NULL,
  `child_age` int(11) DEFAULT NULL,
  `child_grade_degree` varchar(100) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `profile_status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residentprofiles`
--

INSERT INTO `residentprofiles` (`profile_id`, `user_id`, `address`, `birthplace`, `contact_number`, `age`, `birthday`, `gender`, `district`, `citizenship`, `civil_status`, `work`, `precinct_number`, `spouse_name`, `spouse_age`, `spouse_birthday`, `spouse_address`, `spouse_work`, `child_name`, `child_age`, `child_grade_degree`, `first_name`, `middle_name`, `last_name`, `profile_status`) VALUES
(6, 10, '2435 ORO B STREET STA ANA MANILA', 'Manila', '123213', 1, '2024-12-19', 'Male', '5', 'Filipino', 'Single', 'Cybersecurity', 1234, '0', 1, '2024-12-19', 'NA', '0', '1', NULL, '0', 'Test', 'Test', 'Test', 'Approved'),
(7, 11, '2435 ORO B STREET STA ANA MANILA', 'Manila', '09107800214', 18, '2024-12-19', 'Male', '4', 'Filipino', 'Single', 'Cybersecurity', 0, '0', 1, '2024-12-19', 'NA', '0', '', NULL, 'Grade 1', 'Argus', 'Grageda', 'Inglesa', 'Approved'),
(9, 13, 'jan jan lang', 'Manila', '09107800214', 1, '2024-12-20', 'Male', '2', 'Filipino', 'Married', 'App developer', 0, '0', 2, '2024-12-20', '2435 Oro B street', 'Cyber Security', 'Test', 1, '0', 'Test1', 'Test1', 'Test1', 'Approved'),
(10, 14, '2435 ORO B STREET STA ANA MANILA', 'Manila', '123213', 1, '2024-12-20', 'Male', '2', 'Filipino', 'Married', 'App developer', 0, '0', 1, '2024-12-20', 'NA', 'NA', 'NA', 1, '0', 'Test3', 'Test', 'Test2', 'Approved'),
(11, 15, 'Manila', 'Manila', '123213', 21, '2024-12-21', 'Male', '2', 'Filipino', 'Single', 'App developer', 0, '0', 0, '2024-12-21', 'NA', 'NA', 'NA', 0, '0', 'Test4', 'Test4', 'Test4', 'Approved'),
(12, 16, 'Barangay 770, 2435 Oro B Street Sta Ana Manila', 'Manila', '09107800214', 17, '2008-06-09', 'Female', '5', 'Filipino', 'Single', 'Accountant', 0, '0', 0, '2024-12-21', 'N/A', '0', '', NULL, '0', 'Shane', 'Grageda', 'Inglesa', 'Pending'),
(13, 17, 'Barangay 770, 2435 Oro B Street Sta Ana Manila', 'Manila', '09107800214', 22, '2002-08-15', 'Female', '5', 'Filipino', 'Single', 'Accountant', 0, '0', 0, '2024-12-21', 'N/A', '0', '0', NULL, '0', 'Shiela', 'Grageda', 'Inglesa', 'Rejected'),
(14, 18, 'Barangay 770, 2435 Oro B Street Sta Ana Manila', 'Manila', '09107800214', 21, '2024-12-21', 'Male', '5', 'Filipino', 'Single', 'Cyber Security', 0, '0', 0, '2024-12-21', 'N/A', 'N/A', 'N/A', 0, '0', 'John Rey', 'Grageda', 'Inglesa', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `account_status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `created_at`, `first_name`, `last_name`, `account_status`) VALUES
(10, 'Test2@example.com', '$2y$10$QOoM/DiJrbTdT.d8qWSoG.IKCdXbVbM2cbkvysvDzdWQneMZqHMea', '2024-12-19 05:05:54', 'Test', 'Test', 'Approved'),
(11, 'argusinglesa@gmail.com', '$2y$10$nM9uYI/pvCIxY7uzzj1jSek.5XG/hzBoABr4U9IL8YJBHhlEH1DRS', '2024-12-19 10:06:24', 'Argus', 'Inglesa', 'Approved'),
(13, 'Test1@example.com', '$2y$10$LfY8Y4/mYibD2tqchF3WVuZ66Q0fT0zmRFmlkBOVVcC7VYLJ0Q2mu', '2024-12-19 16:24:40', 'Test1', 'Test1', 'Approved'),
(14, 'Test3@example.com', '$2y$10$jPUiCnSX7zT61fwEclP5XuLw57ND8pw6RQkspnijzKFodaVLizWiK', '2024-12-19 16:30:46', 'Test3', 'Test3', 'Approved'),
(15, 'Test4@example.com', '$2y$10$.hRjeXf9lXz4Mk8sc3TWc.QTFWDD7PejLsEqfuOWGAR0uUSlk9nv2', '2024-12-21 03:59:28', 'Test4', 'Test4', 'Approved'),
(16, 'Shaneinglesa@gmail.com', '$2y$10$wQF0pkTdKO9TxTlDmCF.yebxxugyj2tFoJvDJQPm7HRgZlgRuXb8u', '2024-12-21 12:41:44', 'Shane', 'Inglesa', 'Pending'),
(17, 'Shielainglesa@gmail.com', '$2y$10$IiXmmpd22AuDXKcctxve9usH/xEtuCDRrzX7R1hNwezdv6rlAHA9u', '2024-12-21 12:42:18', 'Shiela', 'Inglesa', 'Rejected'),
(18, 'inglesajohnrey@gmail.com', '$2y$10$dO6DrGm.4Nxtc5Ng3iurDuOzbKGx11RADnxfmkH6ONxQkkKZxxNua', '2024-12-21 13:14:00', 'John Rey', 'Inglesa', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `verificationdocuments`
--

CREATE TABLE `verificationdocuments` (
  `document_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_type` enum('Valid ID','Birth Certificate') NOT NULL,
  `document_path` varchar(255) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `admin_remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verificationdocuments`
--

INSERT INTO `verificationdocuments` (`document_id`, `user_id`, `document_type`, `document_path`, `status`, `admin_remarks`) VALUES
(19, 10, 'Birth Certificate', 'uploads/718b02cd-b7e1-4b77-8bf6-30d34de13198.jpg', 'Approved', NULL),
(20, 11, 'Valid ID', 'uploads/718b02cd-b7e1-4b77-8bf6-30d34de13198.jpg', 'Approved', NULL),
(22, 13, 'Valid ID', 'uploads/WIN_20240402_18_17_10_Pro.jpg', 'Approved', NULL),
(23, 14, 'Valid ID', 'uploads/1710045640059.png', 'Approved', NULL),
(24, 15, 'Valid ID', 'uploads/Barangay logo.png', 'Approved', NULL),
(25, 16, 'Valid ID', 'uploads/Example ID.png', 'Pending', NULL),
(26, 17, 'Birth Certificate', 'uploads/IMG_20240707_212452.jpg', 'Rejected', NULL),
(27, 18, 'Valid ID', 'uploads/Example ID.png', 'Approved', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminlogs`
--
ALTER TABLE `adminlogs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `documentrequests`
--
ALTER TABLE `documentrequests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `residentprofiles`
--
ALTER TABLE `residentprofiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `verificationdocuments`
--
ALTER TABLE `verificationdocuments`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adminlogs`
--
ALTER TABLE `adminlogs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `documentrequests`
--
ALTER TABLE `documentrequests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `residentprofiles`
--
ALTER TABLE `residentprofiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `verificationdocuments`
--
ALTER TABLE `verificationdocuments`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adminlogs`
--
ALTER TABLE `adminlogs`
  ADD CONSTRAINT `adminlogs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adminlogs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `residentprofiles` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `residentprofiles`
--
ALTER TABLE `residentprofiles`
  ADD CONSTRAINT `residentprofiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `verificationdocuments`
--
ALTER TABLE `verificationdocuments`
  ADD CONSTRAINT `verificationdocuments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
