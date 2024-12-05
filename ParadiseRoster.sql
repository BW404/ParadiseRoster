-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 05, 2024 at 05:50 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ParadiseRoster`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `approved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_entries`
--

CREATE TABLE `log_entries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL,
  `action` enum('login','logout') NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `incident_details` text DEFAULT NULL,
  `specific_instructions` text DEFAULT NULL,
  `incident_time` time DEFAULT NULL,
  `incident_location` varchar(255) DEFAULT NULL,
  `calm_time` time DEFAULT NULL,
  `description` text DEFAULT NULL,
  `hurt` enum('yes','no') DEFAULT NULL,
  `current_status` text DEFAULT NULL,
  `staff_name` varchar(255) DEFAULT NULL,
  `staff_contact` varchar(255) DEFAULT NULL,
  `staff_email` varchar(255) DEFAULT NULL,
  `service_location` varchar(255) DEFAULT NULL,
  `support_details` text DEFAULT NULL,
  `medication` varchar(50) DEFAULT NULL,
  `handover` varchar(255) DEFAULT NULL,
  `instructions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_entries`
--

INSERT INTO `log_entries` (`id`, `user_id`, `participant_id`, `action`, `login_time`, `logout_time`, `incident_details`, `specific_instructions`, `incident_time`, `incident_location`, `calm_time`, `description`, `hurt`, `current_status`, `staff_name`, `staff_contact`, `staff_email`, `service_location`, `support_details`, `medication`, `handover`, `instructions`) VALUES
(10, 3, 1, 'logout', '2024-11-21 23:31:38', '2024-11-22 00:15:18', NULL, 'This is Specific Instructions', '14:14:00', 'Bed Room', '00:00:03', 'This is incedent description', 'yes', 'This is current status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 3, 1, 'logout', '2024-11-22 08:06:55', '2024-11-23 14:02:28', NULL, 'spc', '15:03:00', 'Bed Room', '00:00:06', 'Desc', 'yes', 'sat', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 3, 1, 'logout', '2024-11-23 14:03:08', '2024-11-30 16:58:54', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 3, 1, 'logout', '2024-11-30 18:06:55', '2024-12-02 20:39:59', NULL, 'Specific Instructions', NULL, NULL, NULL, NULL, NULL, NULL, 'Staff Name', 'Staff Contact', 'Staff@Email', 'Service Location', 'Details of Today\'s Support', 'Lunch', 'Handover to Support Worker', NULL),
(16, 3, 1, 'logout', '2024-12-02 21:08:27', '2024-12-02 22:08:10', NULL, 'Next support worker instruction', NULL, NULL, NULL, NULL, NULL, NULL, 'staff 1', '01245465', 'jalaluddintaj202@gmail.com', 'sservice location ', 'Do something ', 'Morning  Lunch  Evening  Bedtime', 'Support worker 2', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE `participants` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `participants`
--

INSERT INTO `participants` (`id`, `name`) VALUES
(1, 'adib'),
(2, 'zayn'),
(3, 'maya');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(3, 'staff1', '$2y$10$BqxhBpYNv8KIurzCPJr9UODom1g/fC0LvpRJwerIJSmkbc0MMmhkG');

-- --------------------------------------------------------

--
-- Table structure for table `user_participants`
--

CREATE TABLE `user_participants` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_participants`
--

INSERT INTO `user_participants` (`id`, `user_id`, `participant_id`) VALUES
(6, 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_user`
--
ALTER TABLE `admin_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_entries`
--
ALTER TABLE `log_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `participant_id` (`participant_id`);

--
-- Indexes for table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_participants`
--
ALTER TABLE `user_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `participant_id` (`participant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_entries`
--
ALTER TABLE `log_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `participants`
--
ALTER TABLE `participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_participants`
--
ALTER TABLE `user_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log_entries`
--
ALTER TABLE `log_entries`
  ADD CONSTRAINT `log_entries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `log_entries_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_participants`
--
ALTER TABLE `user_participants`
  ADD CONSTRAINT `user_participants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_participants_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
