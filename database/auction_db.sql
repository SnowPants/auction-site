-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 21, 2025 at 12:52 AM
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
-- Database: `auction_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$PErSSkq.Z.G/BrQFG/4P0.WPUSKSlEKPZvXMAwuomwu8jmSC0x1U.', '2025-07-19 19:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(100) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`id`, `item_id`, `user_id`, `amount`, `created_at`, `full_name`, `shipping_address`) VALUES
(7, 16, 4, 15.00, '2025-07-20 02:40:20', NULL, NULL),
(8, 16, 8, 18.85, '2025-07-20 03:52:24', NULL, NULL),
(9, 25, 6, 101.00, '2025-07-20 19:17:10', 'Kitty Clay', '123 south st. city,state'),
(10, 25, 6, 102.00, '2025-07-20 19:17:32', 'Kitty Clay', '123 south st. city, state 12345'),
(11, 20, 4, 15.00, '2025-07-20 19:29:41', 'Kitty Clay', '123 salmon st. beachy beam, FL 12345'),
(12, 25, 4, 103.00, '2025-07-20 19:30:18', 'sister sam', '1234 holy hock rd. sunrise,mn 12345'),
(13, 24, 4, 26.00, '2025-07-20 19:31:03', 'Dolly Doo', '1234 abc st. key west, fl 54321'),
(14, 24, 8, 29.00, '2025-07-20 19:39:54', 'Test User', 'testing st. MC,NM 12345');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(4, 'Clothing'),
(5, 'Homegoods'),
(7, 'Toys'),
(9, 'Sporting Goods'),
(11, 'Furniture'),
(13, 'Books'),
(15, 'Tools'),
(17, 'Electronics');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_time` datetime DEFAULT current_timestamp(),
  `end_time` datetime DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('active','ended','sold') DEFAULT 'active',
  `winner_user_id` int(11) DEFAULT NULL,
  `minimum_increment` decimal(10,2) DEFAULT 0.01
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `title`, `description`, `category_id`, `price`, `user_id`, `created_at`, `start_time`, `end_time`, `image_path`, `status`, `winner_user_id`, `minimum_increment`) VALUES
(16, 'test', 'test', 7, 11.99, 1, '2025-07-19 02:23:55', '2025-07-18 21:23:55', '2025-07-21 23:23:00', 'uploads/687b01bb853b3_123134_XXX_v1.jpg', 'active', NULL, 0.01),
(19, 'Yellow Chicken Mug', 'Yellow chicken Mug', 5, 22.22, 6, '2025-07-20 03:47:29', '2025-07-19 22:47:29', '2025-07-19 14:45:00', 'uploads/687c66d102c77_99112695820.png', 'active', NULL, 0.01),
(20, 'pup cup', 'great cup for a pup', 5, 14.95, 8, '2025-07-20 03:53:38', '2025-07-19 22:53:38', '2025-07-20 14:34:00', 'uploads/687c684289bc7_0cbf5e05a1ef35e9187854b51cd98ca6-431575447.jpg', 'active', NULL, 0.01),
(21, 'Hot Wheels Set', 'fun hot wheels set', 7, 12.00, 4, '2025-07-20 14:00:26', '2025-07-20 09:00:26', '2025-07-20 09:23:00', 'uploads/687cf67a2c865_910152.jpg', 'active', NULL, 0.01),
(23, 'Hot Wheels', 'Hot Wheels', 7, 23.00, 6, '2025-07-20 15:18:40', '2025-07-20 10:18:40', '2025-07-20 10:25:00', 'uploads/687d08d02bd64_910152.jpg', 'active', NULL, 0.01),
(24, 'hot wheels track', 'track for hot wheels', 7, 25.00, 6, '2025-07-20 15:27:32', '2025-07-20 10:27:32', '2025-07-20 14:40:00', 'uploads/687d0ae42fa10_GUEST_2b2f55f6-e0d4-4a3d-83a0-a0649483d652.png', 'active', NULL, 0.01),
(25, 'toy box', 'toy box', 11, 100.00, 4, '2025-07-20 17:17:51', '2025-07-20 12:17:51', '2025-07-20 14:40:00', 'uploads/687d24bf68b89_95f459c6356be78e315054a942f87591-2911824304.jpg', 'active', NULL, 0.01),
(26, 'Christmas Cookie Cutters', 'Cookie cutters for your holiday parties!', 5, 33.00, 6, '2025-07-20 20:07:36', '2025-07-20 15:07:36', '2025-07-20 15:13:00', 'uploads/687d4c88e4cc8_christmas-cookie-cutters.png', 'active', NULL, 0.01);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `credit_card` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `full_name`, `address`, `credit_card`, `phone`) VALUES
(1, 'testuser', '1234', 'test@test.com', '2025-07-15 01:13:36', 'Dolly Sass', 'ABC Happy St. Sunshine, CA 12345', '1234567891236549', '000-123-1111'),
(4, 'heidi', '$2y$10$c/IoFRq/aDWu57/wBsxwsO8Bxs/ycCsyLdBrA37a9hNtl5GbOIC7y', NULL, '2025-07-19 21:29:32', 'heidi', 'test road s', '1234567891236549', '1231231234'),
(6, 'Moo', '$2y$10$X3u2I9qp7/vAqhWekFGUkep1FAlQSyfIVkDIV40pXvN.EXpIlBu/u', NULL, '2025-07-19 22:55:39', 'Captain Moo', 'moo moo st. ', '1234567891236549', '8152222273'),
(7, 'heidi', '$2y$10$kpHYvm1eoGO12aM.adbT6eV15nQmqXJG9ql08uLg3O..czdsXS4OS', NULL, '2025-07-19 22:56:42', 'heidi', 'this is a street st.', '1234567891236549', '8152222273'),
(8, 'testuser1', '$2y$10$kG0HgNz7oA5.reByBcSnG.ez/L.j4haaLPFl5IFDo0pFDSqI6KEHa', NULL, '2025-07-20 03:51:54', 'testuser1', 'shipping to me south st.', '1234567891236549', '8152222273');

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
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_winner_user` (`winner_user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_winner_user` FOREIGN KEY (`winner_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
