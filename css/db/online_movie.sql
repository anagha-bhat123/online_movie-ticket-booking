-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2025 at 04:22 PM
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
-- Database: `online_movie`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '123', '2025-06-04 08:37:46');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_name` varchar(255) NOT NULL,
  `theater_name` varchar(255) NOT NULL,
  `show_date` date NOT NULL,
  `show_time` varchar(50) NOT NULL,
  `seat_number` varchar(255) NOT NULL,
  `food_items` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `booking_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','cancelled') DEFAULT 'active',
  `cancel_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `movie_name`, `theater_name`, `show_date`, `show_time`, `seat_number`, `food_items`, `total_amount`, `booking_time`, `status`, `cancel_token`) VALUES
(1, 1, 'Gant Kalver', 'Kalpana', '2025-06-07', '10:00:00', 'A1', 'None', 150.00, '2025-06-04 17:23:49', 'active', NULL),
(2, 1, 'School Leader', 'Bharath Cinemas', '2025-06-06', '13:45:00', 'A1', 'Sprite x1', 200.00, '2025-06-05 03:39:58', 'active', NULL),
(3, 1, 'Karate Kid: Legends', 'Bharath Cinemas', '2025-06-07', '17:45:00', 'A1', 'None', 150.00, '2025-06-05 15:01:03', 'active', NULL),
(4, 1, 'Gant Kalver', 'Kalpana', '2025-06-07', '13:00:00', 'A1', 'None', 150.00, '2025-06-06 15:15:41', 'active', NULL),
(5, 1, 'Mission: Impossible The Final Reckoning', 'Bharath Cinemas', '2025-06-11', '22:00:00', 'A1', 'None', 150.00, '2025-06-09 13:52:46', 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coming_soon`
--

CREATE TABLE `coming_soon` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coming_soon`
--

INSERT INTO `coming_soon` (`id`, `title`, `genre`, `language`, `description`, `poster`, `release_date`, `created_at`) VALUES
(1, 'Son of Sardaar 2', 'Action', 'Hindi', 'Son of Sardaar 2 is an upcoming Hindi-language action-comedy film directed by Vijay Kumar Arora and produced by Ajay Devgn, Jyoti Deshpande, N.R. Pachisia, and Pravin Talreja under the banners of Devgn Films and Jio Studios. The film is scheduled for theatrical release on July 25, 2025.', '1749047440_1748803323_sonofsikander.jpeg', '2026-07-25', '2025-06-04 14:30:40'),
(2, 'Param Sundari', 'Drama', 'Hindi', 'Param Sundari is an upcoming Hindi-language romantic comedy directed by Tushar Jalota and produced by Dinesh Vijan under Maddock Films. The film stars Sidharth Malhotra and Janhvi Kapoor in lead roles and is scheduled for theatrical release on July 25, 2025.', '1749047477_1748803436_param sundari.jpeg', '2026-07-25', '2025-06-04 14:31:17'),
(3, 'Kubera', 'Action', 'Tamil', 'Sekhar Kammula`s Kuberaa features Dhanush, Nagarjuna Akkineni and Rashmika Mandana in prominent roles.', '1749049337_kubera.avif', '2025-06-20', '2025-06-04 15:02:17'),
(4, 'War 2', 'Action', 'Hindi', 'Double the fire. Double the fury. Pick your side.', '1749049425_war2.avif', '2025-08-14', '2025-06-04 15:03:45'),
(5, 'Housefull 5', 'Comedy', 'Hindi', 'India`s Biggest Franchise is back with the 5th instalment, and this time it is not just chaos and comedy.... But a KILLER Comedy with two different climaxes. Which one will you choose - Housefull 5A or Housefull 5B?', '1749049782_housefull5.avif', '2025-06-06', '2025-06-04 15:09:42');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_name`, `user_email`, `message`, `created_at`) VALUES
(1, 'chandana', 'chandanag0508@gmail.com', 'gegg', '2025-06-04 15:44:10');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`id`, `name`, `price`, `image`, `created_at`) VALUES
(1, 'Sprite', 50.00, 'spirte.jpg', '2025-06-04 15:44:50'),
(2, 'Popcorn', 50.00, '1748793332_popcorn.jpg', '2025-06-04 15:45:06'),
(3, 'Coco Cola', 50.00, 'cola.jpg', '2025-06-04 15:45:21'),
(4, 'Fanta', 50.00, 'fanta.jpg', '2025-06-04 15:45:52'),
(5, 'Lays-Blue', 20.00, 'laysB.jpg', '2025-06-04 15:46:23'),
(6, 'Lays-Yellow', 20.00, 'laysY.jpg', '2025-06-04 15:46:47'),
(7, 'Lays-Green', 20.00, 'laysG.jpg', '2025-06-04 15:47:11'),
(8, 'Lays-Red', 20.00, 'laysR.jpg', '2025-06-04 15:47:32');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `genre`, `language`, `description`, `poster`, `release_date`, `created_at`) VALUES
(1, 'Mission: Impossible The Final Reckoning', 'Action', 'English', 'Our lives are a sum of our choices. Every choice, every mission has led to this.\r\nTom Cruise is Ethan Hunt in Mission: Impossible - The Final Reckoning.\r\nGet ready to light the fuse, one last time!', 'Mission-Impossible-The-Final-Reckoning-2025-Tom.webp', '2025-05-17', '2025-06-04 15:07:30'),
(2, 'Final Destination Bloodlines', 'Action', 'English', 'The newest chapter in New Line Cinemas bloody successful franchise takes audiences back to the very beginning of Deaths twisted sense of justice---Final Destination Bloodlines.', 'final-destination-bloodlines.avif', '2025-05-15', '2025-06-04 15:13:25'),
(3, 'School Leader', 'Comedy	', 'Kannada', 'A struggling government high school that was once thriving with over a thousand students has only 150 students and three overburdened teachers. The situation worsens when two rival groups in the SSLC class engage in constant mischief, disrupting the entire school environment.', 'school-leader.avif', '2025-05-30', '2025-06-04 15:14:52'),
(4, 'Gant Kalver', 'Comedy', 'Tulu', 'Naveen D. Padil, the king of ideas, transforms lives with his brilliant solutions. Alongside him, Arvind Bolars cute aggression adds laughter and warmth to every moment with a pinch of mystery.', 'gant-kalver.avif', '2025-05-23', '2025-06-04 15:17:16'),
(5, 'Middle Class Family', 'Drama', 'Tulu', 'Middle Class Family follows the journey of a middle-class boy chasing his dream of becoming a successful builder. With his family and friends by his side, he overcomes challenges, and the arrival of his dream girl seems to resolve his troubles. However, just as success is within reach, a hidden family secret involving his beloved creates new hurdles. Will he achieve his dream and marry the love of his life? This comedy family entertainer unravels it all.', 'middle-class-family.avif', '2025-01-31', '2025-06-04 15:20:16'),
(6, 'Bhool Chuk Maaf', 'Comedy', 'Hindi', 'Titli hai Ranjan ka pyaar, par haldi par atka hai uska sansaar,\r\nToh dekhne zaroor aaiyega inki kahaani with parivaar.\r\n', 'bhool-chuk-maaf.avif', '2025-05-23', '2025-06-04 15:22:19'),
(7, 'Karate Kid: Legends', 'Action', 'English', 'Kung fu prodigy Li Fong is uprooted from his home in Beijing and forced to move to New York City. When a friend needs his help, Li enters a karate competition. Li`s teacher Mr. Han enlists original Karate Kid Daniel LaRusso for help.', 'karate-kid-legends.avif', '2025-05-30', '2025-06-04 15:24:21'),
(8, 'Bhairavam', 'Action', 'Telugu', 'Bhairavam is a Telugu movie starring Bellamkonda Sai Srinivas, Manchu Manoj Kumar and Naara Rohith in prominent roles. It is directed by Vijay Kanakamedala', 'bhairavam-et.avif', '2025-05-30', '2025-06-04 15:31:08');

-- --------------------------------------------------------

--
-- Table structure for table `shows`
--

CREATE TABLE `shows` (
  `show_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `theater_id` int(11) NOT NULL,
  `show_date` date NOT NULL,
  `show_time` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shows`
--

INSERT INTO `shows` (`show_id`, `movie_id`, `theater_id`, `show_date`, `show_time`, `created_at`) VALUES
(3, 1, 1, '2025-06-08', '10:00 PM', '2025-06-04 16:04:02'),
(4, 1, 1, '2025-06-09', '10:00 PM', '2025-06-04 16:04:23'),
(5, 2, 3, '2025-06-06', '10:30 PM', '2025-06-04 16:05:13'),
(6, 2, 3, '2025-06-07', '10:30 PM', '2025-06-04 16:05:29'),
(7, 2, 3, '2025-06-08', '10:30 PM', '2025-06-04 16:05:49'),
(8, 2, 3, '2025-06-09', '10:30 PM', '2025-06-04 16:06:08'),
(9, 3, 1, '2025-06-06', '07:30 PM', '2025-06-04 16:08:30'),
(10, 3, 1, '2025-06-07', '07:30 PM', '2025-06-04 16:08:58'),
(11, 3, 1, '2025-06-08', '07:30 PM', '2025-06-04 16:09:17'),
(12, 3, 3, '2025-06-06', '11:15 AM', '2025-06-04 16:09:49'),
(13, 3, 3, '2025-06-06', '01:45 PM', '2025-06-04 16:10:14'),
(14, 3, 3, '2025-06-06', '04:30 PM', '2025-06-04 16:10:32'),
(15, 3, 3, '2025-06-06', '07:15 PM', '2025-06-04 16:10:54'),
(16, 3, 3, '2025-06-06', '10:00 PM', '2025-06-04 16:11:47'),
(17, 3, 3, '2025-06-07', '11:15 AM', '2025-06-04 16:12:18'),
(18, 3, 3, '2025-06-07', '01:45 PM', '2025-06-04 16:12:38'),
(19, 3, 3, '2025-06-07', '04:30 PM', '2025-06-04 16:12:56'),
(20, 3, 3, '2025-06-07', '07:15 PM', '2025-06-04 16:13:34'),
(21, 3, 3, '2025-06-07', '10:00 PM', '2025-06-04 16:13:52'),
(22, 3, 3, '2025-06-08', '11:15 AM', '2025-06-04 16:14:22'),
(23, 3, 3, '2025-06-08', '01:45 PM', '2025-06-04 16:14:41'),
(24, 3, 3, '2025-06-08', '04:30 PM', '2025-06-04 16:15:17'),
(25, 3, 3, '2025-06-08', '10:00 PM', '2025-06-04 16:15:40'),
(26, 3, 3, '2025-06-08', '07:15 PM', '2025-06-04 16:15:58'),
(27, 1, 1, '2025-06-06', '06:30 PM', '2025-06-04 16:18:38'),
(28, 1, 1, '2025-06-07', '06:30 PM', '2025-06-04 16:19:51'),
(29, 1, 1, '2025-06-08', '06:30 PM', '2025-06-04 16:20:28'),
(30, 1, 1, '2025-06-09', '06:30 PM', '2025-06-04 16:20:44'),
(31, 4, 2, '2025-06-06', '10:00 AM', '2025-06-04 16:26:13'),
(32, 4, 2, '2025-06-06', '01:00 PM', '2025-06-04 16:26:34'),
(33, 4, 2, '2025-06-06', '04:00 PM', '2025-06-04 16:27:01'),
(34, 4, 2, '2025-06-06', '07:00 PM', '2025-06-04 16:27:28'),
(35, 4, 2, '2025-06-07', '10:00 AM', '2025-06-04 16:27:56'),
(36, 4, 2, '2025-06-07', '01:00 PM', '2025-06-04 16:28:18'),
(37, 4, 2, '2025-06-07', '04:00 PM', '2025-06-04 16:28:36'),
(38, 4, 2, '2025-06-07', '07:00 PM', '2025-06-04 16:28:52'),
(39, 4, 3, '2025-06-06', '10:15 PM', '2025-06-04 16:29:33'),
(40, 5, 3, '2025-06-06', '02:15 PM', '2025-06-04 16:30:40'),
(41, 5, 3, '2025-06-07', '02:15 PM', '2025-06-04 16:31:04'),
(42, 6, 3, '2025-06-06', '01:00 PM', '2025-06-04 16:33:31'),
(43, 6, 3, '2025-06-06', '10:15 PM', '2025-06-04 16:33:56'),
(44, 6, 3, '2025-06-07', '01:00 PM', '2025-06-04 16:34:15'),
(45, 6, 3, '2025-06-07', '10:15 PM', '2025-06-04 16:34:40'),
(46, 6, 1, '2025-06-06', '10:10 AM', '2025-06-04 16:35:16'),
(47, 6, 1, '2025-06-06', '03:05 PM', '2025-06-04 16:35:38'),
(48, 6, 1, '2025-06-06', '10:20 PM', '2025-06-04 16:36:20'),
(49, 6, 1, '2025-06-07', '10:10 AM', '2025-06-04 16:36:41'),
(50, 6, 1, '2025-06-07', '03:05 PM', '2025-06-04 16:37:00'),
(52, 7, 3, '2025-06-08', '05:45 PM', '2025-06-04 16:40:44'),
(53, 7, 3, '2025-06-07', '05:45 PM', '2025-06-04 16:41:39'),
(54, 8, 1, '2025-06-06', '09:50 AM', '2025-06-04 16:42:29'),
(55, 8, 1, '2025-06-07', '09:50 AM', '2025-06-04 16:42:52'),
(56, 8, 1, '2025-06-06', '03:20 PM', '2025-06-04 16:43:45'),
(57, 8, 1, '2025-06-07', '03:20 PM', '2025-06-04 16:44:04'),
(58, 3, 1, '2024-06-09', '06:00 PM', '2025-06-09 13:47:31'),
(59, 3, 1, '2024-06-11', '10:00 PM', '2025-06-09 13:48:24'),
(61, 1, 1, '2025-06-14', '10:00 AM', '2025-06-12 14:11:43'),
(62, 1, 1, '2025-06-15', '07:00 PM', '2025-06-12 14:12:30'),
(63, 2, 3, '2025-06-14', '04:15 PM', '2025-06-12 14:13:01'),
(64, 3, 2, '2025-06-14', '01:00 PM', '2025-06-12 14:13:59'),
(65, 3, 2, '2025-06-15', '01:00 PM', '2025-06-12 14:14:29'),
(66, 4, 4, '2025-06-14', '10:00 AM', '2025-06-12 14:15:16'),
(67, 4, 4, '2025-06-15', '07:00 PM', '2025-06-12 14:15:36'),
(68, 5, 3, '2025-06-15', '07:15 PM', '2025-06-12 14:16:06'),
(69, 6, 1, '2025-06-15', '04:15 PM', '2025-06-12 14:16:32'),
(70, 7, 1, '2025-06-15', '07:10 PM', '2025-06-12 14:17:26'),
(71, 8, 3, '2025-06-15', '01:15 PM', '2025-06-12 14:20:05');

-- --------------------------------------------------------

--
-- Table structure for table `theaters`
--

CREATE TABLE `theaters` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `theaters`
--

INSERT INTO `theaters` (`id`, `name`, `location`, `created_at`) VALUES
(1, 'Inox', 'Manipal', '2025-06-04 14:12:22'),
(2, 'Kalpana', 'Udupi', '2025-06-04 14:12:37'),
(3, 'Bharath Cinemas', 'Udupi', '2025-06-04 14:12:59'),
(4, 'Alankar', 'Udupi', '2025-06-04 14:13:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `otp`, `otp_expiry`, `is_verified`, `created_at`) VALUES
(1, 'chandana', 'chandanag0508@gmail.com', '$2y$10$WPLr8zUMMPdBxo8tZLnpeO7HGusgXNLUTh8.DNFllR/5pc694EkJG', NULL, NULL, 0, '2025-06-04 15:36:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_show` (`movie_name`,`theater_name`,`show_date`,`show_time`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `coming_soon`
--
ALTER TABLE `coming_soon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shows`
--
ALTER TABLE `shows`
  ADD PRIMARY KEY (`show_id`);

--
-- Indexes for table `theaters`
--
ALTER TABLE `theaters`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `coming_soon`
--
ALTER TABLE `coming_soon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shows`
--
ALTER TABLE `shows`
  MODIFY `show_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `theaters`
--
ALTER TABLE `theaters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
