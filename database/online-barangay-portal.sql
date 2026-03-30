-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2026 at 09:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online-barangay-portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcement_post`
--

CREATE TABLE `announcement_post` (
  `post_id` int(11) NOT NULL,
  `post_title` varchar(50) NOT NULL,
  `post_body` text NOT NULL,
  `post_image` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `author_type` enum('admin','resident') DEFAULT NULL,
  `post_date_time` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement_post`
--

INSERT INTO `announcement_post` (`post_id`, `post_title`, `post_body`, `post_image`, `author_id`, `author_type`, `post_date_time`) VALUES
(3, 'Sample Title Announcement', 'All of the residents are advised to stay at home until further notice as there is an infected person that has gone into the area and to avoid infection, residents must practice precautionary measures agains the said virus. Thank you.', NULL, NULL, NULL, '12/04/2020 1:17 PM'),
(4, 'Voter Registration Advisory', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\r\n', NULL, NULL, NULL, '12/04/2020 1:29 PM'),
(5, 'Weather Advisory', 'ORY, PURPOSE AND USAGE\r\nLorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs. The passage is attributed to an unknown typesetter in the 15th century who is thought to have scrambled parts of Cicero\'s De Finibus Bonorum et Malorum for use in a type specimen book. It usually begins with:\r\n\r\n“Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.”\r\nThe purpose of lorem ipsum is to create a natural looking block of text (sentence, paragraph, page, etc.) that doesn\'t distract from the layout. A practice not without controversy, laying out pages with meaningless filler text can be very useful when the focus is meant to be on design, not content.\r\n\r\nThe passage experienced a surge in popularity during the 1960s when Letraset used it on their dry-transfer sheets, and again during the 90s as desktop publishers bundled the text with their software. Today it\'s seen all around th', NULL, NULL, NULL, '12/04/2020 1:30 PM'),
(6, 'Curfew Advisory', 'What is Lorem Ipsum?\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type edit test ', NULL, NULL, NULL, '12/04/2020 3:12 PM'),
(12, 'hakdog', 'fiesta', 'uploads/announcements/1774778699_ChatGPT Image Mar 28, 2026, 08_53_12 PM.png', NULL, 'resident', '03/29/2026 6:04 PM');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `upload_date_time` varchar(50) NOT NULL,
  `directory` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `name`, `description`, `upload_date_time`, `directory`) VALUES
(32, 'Certificate of Residency.pdf', 'This document contains Certificate of Residency', '12/19/2020 1:07 PM', 'documents/'),
(34, 'Business Permit.pdf', 'This document contains Business Permit', '12/19/2020 2:41 PM', 'documents/');

-- --------------------------------------------------------

--
-- Table structure for table `official_info`
--

CREATE TABLE `official_info` (
  `official_id` int(11) NOT NULL,
  `official_position` varchar(50) NOT NULL,
  `official_first_name` varchar(50) NOT NULL,
  `official_middle_name` varchar(50) NOT NULL,
  `official_last_name` varchar(50) NOT NULL,
  `official_sex` varchar(50) NOT NULL,
  `official_contact_info` varchar(50) NOT NULL,
  `official_username` varchar(50) NOT NULL,
  `official_password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `official_info`
--

INSERT INTO `official_info` (`official_id`, `official_position`, `official_first_name`, `official_middle_name`, `official_last_name`, `official_sex`, `official_contact_info`, `official_username`, `official_password`) VALUES
(10, 'Barangay Chairman/Chairwoman', 'John', 'Garcia', 'Mercado', 'Male', '09541252362', 'admin1', 'admin1');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `position_id` int(11) NOT NULL,
  `position_name` varchar(100) NOT NULL,
  `position_code` varchar(20) NOT NULL,
  `position_level` int(11) NOT NULL DEFAULT 1,
  `position_description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`position_id`, `position_name`, `position_code`, `position_level`, `position_description`, `is_active`, `created_at`) VALUES
(1, 'Barangay Captain', 'CAPTAIN', 10, 'Overall head of the barangay with full administrative authority', 1, '2026-03-29 10:14:31'),
(2, 'Barangay Secretary', 'SECRETARY', 9, 'Manages barangay records, documents, and official communications', 1, '2026-03-29 10:14:31'),
(3, 'Barangay Treasurer', 'TREASURER', 8, 'Handles barangay finances, budget, and financial reports', 1, '2026-03-29 10:14:31'),
(4, 'Barangay Councilor', 'COUNCILOR', 7, 'Legislative body member, represents constituents', 1, '2026-03-29 10:14:31'),
(5, 'Barangay Tanod', 'TANOD', 5, 'Peace and order officer, maintains barangay security', 1, '2026-03-29 10:14:31'),
(6, 'Barangay Health Worker', 'HEALTH_WORKER', 6, 'Provides basic health services and education', 1, '2026-03-29 10:14:31'),
(7, 'Barangay Nutritionist', 'NUTRITIONIST', 6, 'Manages nutrition programs and feeding services', 1, '2026-03-29 10:14:31'),
(8, 'Barangay Engineer', 'ENGINEER', 7, 'Oversees infrastructure projects and maintenance', 1, '2026-03-29 10:14:31'),
(9, 'SK Chairman', 'SK_CHAIRMAN', 6, 'Head of Sangguniang Kabataan (Youth Council)', 1, '2026-03-29 10:14:31'),
(10, 'Barangay Administrative Assistant', 'ADMIN_ASST', 4, 'Provides administrative support services', 1, '2026-03-29 10:14:31'),
(11, 'Barangay Cleaner', 'CLEANER', 3, 'Maintains barangay cleanliness and sanitation', 1, '2026-03-29 10:14:31'),
(12, 'Barangay Driver', 'DRIVER', 3, 'Provides transportation services for official duties', 1, '2026-03-29 10:14:31');

-- --------------------------------------------------------

--
-- Table structure for table `position_permissions`
--

CREATE TABLE `position_permissions` (
  `position_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `position_permissions`
--

INSERT INTO `position_permissions` (`position_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(3, 1),
(3, 4),
(3, 6),
(3, 12);

-- --------------------------------------------------------

--
-- Table structure for table `resident_info`
--

CREATE TABLE `resident_info` (
  `resident_id` int(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(50) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `birthday` varchar(50) NOT NULL,
  `sex` varchar(50) NOT NULL,
  `mobile_no` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `civil_stat` varchar(50) NOT NULL,
  `voter_stat` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resident_info`
--

INSERT INTO `resident_info` (`resident_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `alias`, `birthday`, `sex`, `mobile_no`, `email`, `religion`, `civil_stat`, `voter_stat`, `username`, `password`) VALUES
(19, 'Johnmark', 'Quimada', 'Delacruz', 'N/A', 'Jm', '2004-06-18', 'Male', '09505173062', 'johnmarkdelacruzii5@gmail.com', 'Roman Catholic', 'Single', 'Registered', 'user1', 'user1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `user_type` enum('captain','official','staff','resident') NOT NULL DEFAULT 'resident',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_permissions`
--

CREATE TABLE `user_permissions` (
  `permission_id` int(11) NOT NULL,
  `permission_name` varchar(100) NOT NULL,
  `permission_code` varchar(50) NOT NULL,
  `permission_description` text DEFAULT NULL,
  `module` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`permission_id`, `permission_name`, `permission_code`, `permission_description`, `module`) VALUES
(1, 'View Dashboard', 'VIEW_DASHBOARD', 'Can view the main dashboard', 'dashboard'),
(2, 'Manage Users', 'MANAGE_USERS', 'Can create, edit, and delete user accounts', 'users'),
(3, 'Manage Announcements', 'MANAGE_ANNOUNCEMENTS', 'Can create, edit, and delete announcements', 'announcements'),
(4, 'Manage Documents', 'MANAGE_DOCUMENTS', 'Can upload and manage barangay documents', 'documents'),
(5, 'Manage Residents', 'MANAGE_RESIDENTS', 'Can manage resident records', 'residents'),
(6, 'View Reports', 'VIEW_REPORTS', 'Can view system reports and analytics', 'reports'),
(7, 'Manage Settings', 'MANAGE_SETTINGS', 'Can change system settings', 'settings'),
(8, 'Manage Appointments', 'MANAGE_APPOINTMENTS', 'Can manage appointment system', 'appointments'),
(9, 'Manage Requests', 'MANAGE_REQUESTS', 'Can process resident requests', 'requests'),
(10, 'Manage Inventory', 'MANAGE_INVENTORY', 'Can manage barangay inventory', 'inventory'),
(11, 'Manage Meetings', 'MANAGE_MEETINGS', 'Can schedule and manage meetings', 'meetings'),
(12, 'Manage Finances', 'MANAGE_FINANCES', 'Can access financial records', 'finances');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcement_post`
--
ALTER TABLE `announcement_post`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `official_info`
--
ALTER TABLE `official_info`
  ADD PRIMARY KEY (`official_id`),
  ADD UNIQUE KEY `UNIQUE` (`official_username`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`position_id`),
  ADD UNIQUE KEY `position_code` (`position_code`);

--
-- Indexes for table `position_permissions`
--
ALTER TABLE `position_permissions`
  ADD PRIMARY KEY (`position_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `resident_info`
--
ALTER TABLE `resident_info`
  ADD PRIMARY KEY (`resident_id`),
  ADD UNIQUE KEY `UNIQUE` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `position_id` (`position_id`),
  ADD KEY `user_type` (`user_type`);

--
-- Indexes for table `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`permission_id`),
  ADD UNIQUE KEY `permission_code` (`permission_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcement_post`
--
ALTER TABLE `announcement_post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `official_info`
--
ALTER TABLE `official_info`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `resident_info`
--
ALTER TABLE `resident_info`
  MODIFY `resident_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `position_permissions`
--
ALTER TABLE `position_permissions`
  ADD CONSTRAINT `position_permissions_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `position_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `user_permissions` (`permission_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
