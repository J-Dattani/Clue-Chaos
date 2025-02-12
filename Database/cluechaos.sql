-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 13, 2024 at 03:51 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cluechaos`
--

-- --------------------------------------------------------

--
-- Table structure for table `game_results`
--

CREATE TABLE `game_results` (
  `result_id` int NOT NULL,
  `member_id` int NOT NULL,
  `group_id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `score` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `game_results`
--

INSERT INTO `game_results` (`result_id`, `member_id`, `group_id`, `name`, `score`) VALUES
(96009, 11, 97, 'q', 2),
(96010, 11, 97, 'd', 2),
(96011, 11, 97, 'c', 3),
(96012, 11, 97, 'z', 5),
(96017, 11, 98, 'qq', 5),
(96018, 11, 98, 'cdc', 2),
(96019, 11, 98, 're', 2),
(96020, 11, 98, 'aa', 3),
(96021, 11, 98, 'qxs', 2),
(96022, 11, 101, 'qq', 5),
(96023, 11, 101, 'cdc', 2),
(96024, 11, 101, 're', 2),
(96025, 11, 101, 'aa', 3),
(96026, 12, 102, 's', 2),
(96027, 12, 102, 'e', 2),
(96028, 12, 102, 'x', 5),
(96029, 12, 102, 'y', 3),
(96036, 13, 103, 'a', 3),
(96037, 13, 103, 'b', 2),
(96038, 13, 103, 'c', 2),
(96039, 13, 103, 'd', 5),
(96040, 12, 104, 'qq', 2),
(96041, 12, 104, 'cdc', 2),
(96042, 12, 104, 're', 5),
(96044, 12, 104, 's', 3);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `user_id`, `group_name`, `created_at`) VALUES
(97, 11, 'Group 1', '2024-11-28 15:57:41'),
(98, 11, 'Group 2', '2024-11-29 02:01:59'),
(99, 11, 'Group 3', '2024-11-29 02:05:12'),
(102, 12, 'Group 4', '2024-12-03 03:36:03'),
(103, 13, 'Group 5', '2024-12-03 07:40:23'),
(105, 12, 'Group 6', '2024-12-13 03:50:55');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int NOT NULL,
  `group_id` int DEFAULT NULL,
  `member_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `group_id`, `member_name`) VALUES
(466, 97, 'q'),
(467, 97, 'd'),
(468, 97, 'c'),
(469, 97, 'z'),
(470, 98, 'qq'),
(471, 98, 'cdc'),
(472, 98, 're'),
(473, 98, 'aa'),
(474, 98, 'qxs'),
(475, 99, 'qq'),
(476, 99, 'cdc'),
(477, 99, 're'),
(478, 99, 'aa'),
(487, 102, 's'),
(488, 102, 'e'),
(489, 102, 'x'),
(490, 102, 'y'),
(491, 102, 's'),
(492, 103, 'a'),
(493, 103, 'b'),
(494, 103, 'c'),
(495, 103, 'd'),
(501, 105, 'ete'),
(502, 105, 'ee'),
(503, 105, 'tt'),
(504, 105, 'yy'),
(505, 105, 'gg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `reset_token`, `reset_token_expiry`) VALUES
(11, 'JD', 'jaimindattani343@gmail.com', '$2y$10$g88R/rIuctV8HSnPLLjkzeSlB3cF9IOzo2bSWB3ncUPN261wonsJS', '2024-11-26 10:56:10', NULL, NULL),
(12, 'Jaymin Dattani', 'work.jdattani@gmail.com', '$2y$10$626Uc.5eZnHtqTHfxS97SOIkhICaUa0oRfzyoTHs7iF3HoLkCx/UW', '2024-11-29 02:37:19', NULL, NULL),
(13, 'Jaymin Dattani', 'jaymin.dattani115967@marwadiuniversity.ac.in', '$2y$10$TLmHoK2EWVoAljfcqCx99eYarbv8bhGQcImYjbCRbZOS5y9meFJky', '2024-12-03 07:38:48', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `game_results`
--
ALTER TABLE `game_results`
  ADD PRIMARY KEY (`result_id`),
  ADD UNIQUE KEY `unique_player` (`member_id`,`group_id`,`name`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `game_results`
--
ALTER TABLE `game_results`
  MODIFY `result_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96045;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=506;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
