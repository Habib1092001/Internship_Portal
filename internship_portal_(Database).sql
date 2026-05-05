-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2026 at 11:59 AM
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
-- Database: `internship_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'admin@internshipportal.com', 'admin123', '2026-01-06 09:11:47'),
(2, 'shahriar.admin@gmail.com', 'Admin@123', '2026-01-07 09:00:07');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `internship_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `internship_id`, `user_id`, `cv`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '1735903200_65a1b2c3d4e5f.pdf', 'pending', '2026-01-06 09:11:47', '2026-01-06 09:11:47'),
(2, 1, 3, '1767691323_695cd43b9cd40.pdf', 'pending', '2026-01-06 09:22:03', '2026-01-06 09:22:03'),
(3, 2, 3, '1767691827_695cd6333c268.pdf', 'rejected', '2026-01-06 09:30:27', '2026-01-07 09:06:34'),
(4, 14, 3, '1767776588_695e214c11332.pdf', 'accepted', '2026-01-07 09:03:08', '2026-01-07 09:06:16'),
(5, 13, 3, '1767776599_695e215728a07.pdf', 'pending', '2026-01-07 09:03:19', '2026-01-07 09:03:19'),
(6, 12, 3, '1767776610_695e2162b8858.pdf', 'accepted', '2026-01-07 09:03:30', '2026-01-07 09:08:06'),
(7, 11, 3, '1767776625_695e2171b40df.pdf', 'pending', '2026-01-07 09:03:45', '2026-01-07 09:03:45'),
(8, 8, 3, '1767776671_695e219f1a0a1.pdf', 'rejected', '2026-01-07 09:04:31', '2026-01-07 09:08:19'),
(9, 4, 3, '1767776701_695e21bd01614.pdf', 'pending', '2026-01-07 09:05:01', '2026-01-07 09:05:01'),
(10, 3, 3, '1767776714_695e21caeaae9.pdf', 'rejected', '2026-01-07 09:05:14', '2026-01-07 09:07:19'),
(11, 7, 3, '1767776734_695e21de3d81d.pdf', 'accepted', '2026-01-07 09:05:34', '2026-01-07 09:07:08'),
(12, 15, 3, '1769329735_6975d44735f1d.pdf', 'pending', '2026-01-25 08:28:55', '2026-01-25 08:28:55');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `user_id`, `company_name`, `address`, `phone`, `website`, `description`, `logo`, `created_at`, `updated_at`) VALUES
(1, 2, 'Tech Innovations Inc', '456 Tech Ave', '9876543210', 'https://techinnovations.com', 'Leading software development company', NULL, '2026-01-06 09:11:47', '2026-01-06 09:11:47'),
(2, 4, '9 AM Solution', 'jashore', '01123654789', 'https://9amsolution.com/', 'na', '1767777084_Screenshot 2026-01-07 150933.png', '2026-01-06 09:28:35', '2026-01-07 09:11:24'),
(3, 5, 'OS IT Solution Ltd', 'Road-20/A,Town city, main gate,Dhaka', '01123654789', 'https://www.ositsltd.com/', '', '1767777112_Screenshot 2026-01-07 150946.png', '2026-01-07 08:01:02', '2026-01-07 09:11:52'),
(4, 6, 'Soft Tech Innovation Ltd', 'Road-10, House-20, Dhanmondi, Dhaka', '01317597662', 'https://softbd.com/', '', '1767777147_Screenshot 2026-01-07 151001.png', '2026-01-07 08:41:44', '2026-01-07 09:12:27');

-- --------------------------------------------------------

--
-- Table structure for table `internships`
--

CREATE TABLE `internships` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `salary` varchar(100) DEFAULT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `stack` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internships`
--

INSERT INTO `internships` (`id`, `company_id`, `title`, `location`, `duration`, `salary`, `skills`, `stack`, `description`, `deadline`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Web Development Intern', 'New York, NY', '3 months', '$15/hour', 'React, Node.js, MongoDB', 'MERN Stack', 'Join our team to work on cutting-edge web applications', '2026-02-28', 'approved', '2026-01-06 09:11:47', '2026-01-06 09:11:47'),
(2, 2, 'software Engineer', 'Uttara', '2.5 months', 'Nagotiable', 'Java Script (Basic)', 'MERN', '', '2026-01-01', 'approved', '2026-01-06 09:29:20', '2026-01-06 09:30:04'),
(3, 3, 'Full-Stack Web Developer', 'House- 5A,202/D, Haji SolimUddin Ln, Middle Badda, Dhaka-1212', '4 months', 'Nagotiable', 'HTML, CSS, JavaScript(Basic)', 'JavaScript', '', '2026-01-04', 'approved', '2026-01-07 08:07:13', '2026-01-07 09:01:36'),
(4, 3, 'Software Engineer', 'Uttara, Sector-5, Road-10/B, House-36, Dhaka-1230', '3 months', 'Nagotiable', '1. C, C++, Java or c#.  2. OOP Concepts', 'Programming', '', '2026-01-15', 'approved', '2026-01-07 08:10:29', '2026-01-07 09:01:44'),
(5, 3, 'Backend Developer', 'Purana Paltan, Dhaka', '2.5 months', 'Nagotiable', '1. PHP/Node.js. 2. Handle Database.', 'PHP', '', '2026-01-23', 'approved', '2026-01-07 08:36:49', '2026-01-07 09:01:49'),
(6, 3, 'Front-End Engineer', 'House- 5A,202/D, Haji SolimUddin Ln, Middle Badda, Dhaka-1212', '4 months', 'Nagotiable', 'Java Script (Basic), HTML, CSS, Tailwind-CSS, PHP (Optional)', 'MERN', '', '2026-01-23', 'approved', '2026-01-07 08:38:08', '2026-01-07 09:01:58'),
(7, 3, 'Mobile App Developer', 'Purana Paltan, Dhaka', '4 months', 'Nagotiable', '1.Fewer bugs (Must) 2. Consistent standards (Must) 3. Better maintainability (Optional) 4.Smooth development workflow', 'Flutter', '', '2026-01-11', 'approved', '2026-01-07 08:39:28', '2026-01-07 09:01:56'),
(8, 4, 'Data Analyst', 'Uttara', '2.5 months', 'Nagotiable', 'Python, Excel, Power BI (Basic)', 'Python', '', '2026-01-10', 'approved', '2026-01-07 08:44:55', '2026-01-07 09:01:53'),
(9, 4, 'Machine Learning Engineer', 'Purana Paltan, Dhaka', '3 months', 'Nagotiable', 'Python, Excel, Power BI (Basic), NumPy, Pandas (optional)', 'ML', '', '2026-01-15', 'approved', '2026-01-07 08:46:34', '2026-01-07 09:01:24'),
(10, 4, 'Cyber Security Engineer', 'House-20/A, Road-7/A, Sector-2, Mohakhali, Dhaka', '3 months', 'Nagotiable', 'Networking (Basic), Linux, Ethical hacking.', 'Cyber Security', '', '2026-01-27', 'approved', '2026-01-07 08:49:00', '2026-01-07 09:01:06'),
(11, 4, 'DevOps Enginner', 'Middle Badda, Road-6/A, Dhaka', '4 months', 'Nagotiable', 'Linux, CD/CI, AWS', 'Dev', '', '2026-01-22', 'approved', '2026-01-07 08:50:16', '2026-01-07 09:01:51'),
(12, 4, 'Database Administrator ', 'Uttara, Sector-5, Road-10/B, House-36, Dhaka-1230', '3 months', 'Nagotiable', 'MySQL, Oracle (optional)', 'DBA', '', '2026-01-23', 'approved', '2026-01-07 08:52:25', '2026-01-07 09:01:09'),
(13, 2, 'Game Developer', 'Uttara', '4 months', 'Nagotiable', 'C#/C++, Game physics', 'Game', '', '2026-01-28', 'approved', '2026-01-07 08:54:21', '2026-01-07 09:00:59'),
(14, 2, 'AI Engineer', 'House- 5A,202/D, Haji SolimUddin Ln, Middle Badda, Dhaka-1212', '4 months', 'Nagotiable', 'Python, TensorFlow / PyTorch', 'AI', '', '2026-02-03', 'approved', '2026-01-07 08:56:35', '2026-01-07 09:01:01'),
(15, 3, 'Software Eng', 'Uttara', '2.5 months', 'Nagotiable', 'Java Script (Basic)', 'MERN', 'N/A', '2026-01-25', 'approved', '2026-01-25 08:22:31', '2026-01-25 08:24:07'),
(16, 3, 'Software Eng', 'Uttara', '2.5 months', 'Nagotiable', 'Java Script (Basic)', 'MERN', 'N/A', '2026-01-25', 'pending', '2026-01-25 08:24:19', '2026-01-25 08:24:19'),
(17, 3, 'Software Eng', 'Uttara', '2.5 months', 'Nagotiable', 'Java Script (Basic)', 'MERN', 'N/A', '2026-01-25', 'pending', '2026-01-25 08:24:25', '2026-01-25 08:24:25');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'Your application has been submitted successfully.', 0, '2026-01-06 09:11:47'),
(2, 3, 'Your application has been submitted successfully.', 0, '2026-01-06 09:22:03'),
(3, 3, 'Your application has been submitted successfully.', 0, '2026-01-06 09:30:27'),
(4, 3, 'Congratulations! 9 AM Solution accepted your application for \'software Engineer\'.', 0, '2026-01-06 09:31:51'),
(5, 3, 'Your application has been submitted successfully.', 0, '2026-01-07 09:03:08'),
(6, 3, 'Your application has been submitted successfully.', 0, '2026-01-07 09:03:19'),
(7, 3, 'Your application has been submitted successfully.', 0, '2026-01-07 09:03:30'),
(8, 3, 'Your application has been submitted successfully.', 0, '2026-01-07 09:03:45'),
(9, 3, 'Your application has been submitted successfully.', 0, '2026-01-07 09:04:31'),
(10, 3, 'Your application has been submitted successfully.', 0, '2026-01-07 09:05:01'),
(11, 3, 'Your application has been submitted successfully.', 0, '2026-01-07 09:05:14'),
(12, 3, 'Your application has been submitted successfully.', 0, '2026-01-07 09:05:34'),
(13, 3, 'Congratulations! 9 AM Solution accepted your application for \'AI Engineer\'.', 0, '2026-01-07 09:06:16'),
(14, 3, 'Congratulations! OS IT Solution Ltd accepted your application for \'Mobile App Developer\'.', 0, '2026-01-07 09:07:08'),
(15, 3, 'Congratulations! Soft Tech Innovation Ltd accepted your application for \'Database Administrator \'.', 0, '2026-01-07 09:08:06'),
(16, 3, 'Your application has been submitted successfully.', 0, '2026-01-25 08:28:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `cgpa` decimal(3,2) DEFAULT NULL,
  `role` enum('user','company','admin') NOT NULL DEFAULT 'user',
  `password` varchar(255) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `cgpa`, `role`, `password`, `profile_photo`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'student@example.com', '1234567890', '123 Main St', 3.85, 'user', '$2y$10$nOQm8.xVKYrYQIIhvMuJyeYHmL.5qLvFvZkKLZ1vC1VZpLbKK8Omu', NULL, '2026-01-06 09:11:47', '2026-01-06 09:11:47'),
(2, 'Tech Innovations Inc', 'company@techinnovations.com', '9876543210', '456 Tech Ave', NULL, 'company', '$2y$10$nOQm8.xVKYrYQIIhvMuJyeYHmL.5qLvFvZkKLZ1vC1VZpLbKK8Omu', NULL, '2026-01-06 09:11:47', '2026-01-06 09:11:47'),
(3, 'Shahriar Habib Anik', 'anikhabib6666@gmail.com', '01994670851', 'Japan', 3.59, 'user', '$2y$10$9E9Loaa8/iu2Lwdm8qAed.TTBdbVE0GTDjN6tYMoZeiFS.ziLCn5.', '1767691291_WhatsApp Image 2025-11-27 at 16.00.50_d6da7787.jpg', '2026-01-06 09:19:09', '2026-01-06 09:21:31'),
(4, 'Ariful Islam', 'arif@gmail.com', '01123654789', 'jashore', 3.54, 'company', '$2y$10$bflAqaFXf7AY5NYyTOzoLOswrxR88//WlIajDs0.WcnLIJHLQ4DNa', 'uploads/695cd55c6f68a.jpg', '2026-01-06 09:26:52', '2026-01-06 09:26:52'),
(5, 'Shahriar Habib Anik', 'info.osit@gmail.com', '01994670851', 'Road-20/A, Dhanmondi, Dhaka, Bangladesh', NULL, 'company', '$2y$10$znYrE.5oFQZa3tpceK.7fOmV3VKQpi3W1PqWmtBP1mNX6HjJSb5d6', NULL, '2026-01-06 11:22:04', '2026-01-06 11:22:04'),
(6, 'Ariful Islam', 'shahriaranik6666@gmail.com', '01123654789', 'jashore', NULL, 'company', '$2y$10$QNe6Ptdm9uld5K3V3cLOEutLcR3GwPbkHDNEMYF3NmJO7RPjYecRO', NULL, '2026-01-06 11:23:16', '2026-01-06 11:23:16'),
(7, 'a', '221032@gamil.com', '01776597745', 'Road-10, House-20', 3.54, 'user', '$2y$10$JSr5lyOkPpvs4kocceLTmuMVQw.4HoE.UGghP/bUXGmtIZs0ctTv.', NULL, '2026-01-25 08:32:40', '2026-01-25 08:32:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_application` (`internship_id`,`user_id`),
  ADD KEY `idx_internship_id` (`internship_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_company_name` (`company_name`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `internships`
--
ALTER TABLE `internships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_company_id` (`company_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_deadline` (`deadline`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `internships`
--
ALTER TABLE `internships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`internship_id`) REFERENCES `internships` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `internships`
--
ALTER TABLE `internships`
  ADD CONSTRAINT `internships_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
