-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2024 at 02:46 AM
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
-- Database: `bikerecommendation`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'bibek', '$2y$10$mKD0XTBY7p5s1HCP0X3dPeBnsNR6F5KucQQQA9h1vpoZ5PC7h5BXm'),
(2, 'rohit', '$2y$10$GbnIaUiiJiwf0nqYb7Zr.OQrwnRBiT9bkkgWaPxZJZGjN3xS3AMsy');

-- --------------------------------------------------------

--
-- Table structure for table `bikes`
--

CREATE TABLE `bikes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `mileage` decimal(5,2) DEFAULT NULL,
  `engine_capacity` decimal(5,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bikes`
--

INSERT INTO `bikes` (`id`, `name`, `brand_id`, `category`, `price`, `mileage`, `engine_capacity`, `image`, `rating`) VALUES
(1, 'Yamaha-MT15', 1, 'Sport', 460000.00, 45.00, 155.00, 'mt15.jfif', 5.00),
(2, 'Yamaha YZF-R15', 1, 'Sport', 550000.00, 40.00, 155.00, 'Yamaha YZF-R15.jfif', 5.00),
(3, 'Yamaha FZ-S', 1, 'Street', 400000.00, 45.00, 149.00, 'Yamaha FZ-S.png', 5.00),
(4, 'Yamaha FZ25', 1, 'Street', 600000.00, 40.00, 249.00, 'Yamaha FZ25.png', 5.00),
(5, 'Yamaha R3', 1, 'Sport', 800000.00, 35.00, 321.00, 'Yamaha R3.jfif', 4.00),
(6, 'Yamaha YZF-R6', 1, 'Sport', 1400000.00, 30.00, 599.00, 'Yamaha YZF-R6.jpg', 0.00),
(7, 'Yamaha YZF-R1', 1, 'Sport', 2500000.00, 25.00, 998.00, 'Yamaha YZF-R1.jpg', 5.00),
(8, 'Honda CB Shine', 2, 'Commuter', 220000.00, 65.00, 124.00, 'Honda CB Shine.png', 0.00),
(9, 'Honda Hornet 2.0', 2, 'Street', 325000.00, 40.00, 184.00, 'Honda Hornet 2.0.png', 0.00),
(10, 'Honda CB Unicorn ', 2, 'Commuter', 280000.00, 55.00, 162.00, 'Honda CB Unicorn.png', 0.00),
(11, 'Honda XBlade', 2, 'Street', 320000.00, 47.00, 162.00, 'Honda XBlade.png', 0.00),
(12, 'Honda CBR 250R', 2, 'Sport', 675000.00, 30.00, 249.00, 'Honda CBR 250R.png', 0.00),
(13, 'Honda H’ness CB350', 2, 'Cruiser', 850000.00, 35.00, 348.00, 'Honda H’ness CB350.png', 0.00),
(14, 'Honda Dio', 2, 'Scooter', 210000.00, 55.00, 109.00, 'Honda Dio.png', 0.00),
(15, 'Bajaj Pulsar 150', 3, 'Street', 275000.00, 50.00, 149.00, 'Bajaj Pulsar 150.png', 0.00),
(16, 'Bajaj Pulsar NS200', 3, 'Street', 290000.00, 35.00, 199.00, 'Bajaj Pulsar NS200.png', 0.00),
(17, 'Bajaj Avenger 220', 3, 'Cruiser', 360000.00, 40.00, 220.00, 'Bajaj Avenger 220.png', 0.00),
(18, 'Bajaj Dominar 400', 3, 'Sport', 525000.00, 30.00, 373.00, 'Bajaj Dominar 400.png', 0.00),
(19, 'Bajaj Platina 100', 3, 'Commuter', 160000.00, 80.00, 102.00, 'Bajaj Platina 100.png', 0.00),
(20, 'Bajaj Discover 125', 3, 'Commuter', 185000.00, 70.00, 124.00, 'Bajaj Discover 125.png', 0.00),
(21, 'Bajaj Pulsar RS200', 3, 'Sport', 375000.00, 35.00, 199.00, 'Bajaj Pulsar RS200.png', 0.00),
(25, 'Honda Grazia  125', 2, 'Scooter', 238900.00, 45.00, 124.00, 'Honda Grazia.png', 2.00),
(26, 'Honda Aviator', 2, 'Scooter', 235900.00, 49.00, 109.00, 'honda-aviator.png', 0.00),
(27, 'Yamaha WR-155R', 1, 'Street', 749900.00, 45.00, 155.00, 'Yamaha WR-155R.png', 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `bike_ratings`
--

CREATE TABLE `bike_ratings` (
  `id` int(11) NOT NULL,
  `bike_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bike_ratings`
--

INSERT INTO `bike_ratings` (`id`, `bike_id`, `user_id`, `rating`) VALUES
(1, 1, 9, 5),
(2, 2, 9, 5),
(3, 5, 9, 4),
(4, 10, 9, 2),
(5, 14, 9, 5),
(6, 13, 9, 3),
(7, 21, 9, 5),
(8, 7, 9, 5),
(9, 26, 9, 5),
(10, 25, 9, 2),
(11, 17, 9, 5),
(12, 2, 11, 5),
(13, 7, 12, 5),
(14, 1, 14, 5),
(15, 1, 15, 5),
(16, 1, 16, 5),
(17, 1, 17, 5),
(18, 4, 9, 5),
(19, 3, 9, 5),
(20, 27, 9, 5);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(1, 'Yamaha'),
(2, 'Honda'),
(3, 'Bajaj');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(5, 'bibek', 'bibek430909@gmail.com', '$2y$10$WHAl27C.84kbfs4zwjmJEOGTax3/X7C15JqRktbzUI9xhRkubQjPO'),
(6, 'bibek', 'bibek430909@gmail.com', '$2y$10$Hcdd1nImFHb0s3w178E4i.hyQUpHumCILvA2WdBKtKSYNMKDCjDma'),
(7, 'bibek', 'bibek430909@gmail.com', '$2y$10$4eKSgQ3/Ie9wn1ndfhr9RenaNDxNcu.PGtN48iys6nYbhRP3jnUuW'),
(8, 'surakshya', 'bibek530909@gmail.com', '$2y$10$wBzSZdoN4FB6FT8vgl2I4eQv9YgHcJF3Outh2N8y94RPz.6W.Mu.m'),
(9, 'rohit', 'abc@gmail.com', '$2y$10$sXnfA5C68SU5nbUaTsSn/u/fx40zdUT4v5YuxN3FwYgFeNGDgGV5.'),
(10, 'subin', 'subin@gmail.com', '$2y$10$3MmZuyAwiSwHP2o0MwfqQeDfF06ZqhHI2SbepnCoeASeiaOqkQta2'),
(11, 'deepak', 'deepak@gmail.com', '$2y$10$2FjYDGmbM.oH0zxeMR0rPu9pzjno4CtvtDNltMCoXmIdHOni5LVja'),
(12, 'sadhana', 'sadhana@gmail.com', '$2y$10$g1u9ZvMrWL97vFFBGrWgAebnzuQDT6vN.OGo/nWzamZwjyO7EaANq'),
(13, 'sameer', 'sameer@gmail.com', '$2y$10$3hy6Rgr2xr1.MOgrS/Zm9uqBkcCpTDzdh.JHNEXCtoO7xnFL3TPSm'),
(14, 'shyam', 's@gmail.com', '$2y$10$Oc8iq2pGkRGMO5JhXhiHceA.kEnXoxH2C9Z86iGjC.8yMp4DmD.32'),
(15, 'hari', 'h@gmail.com', '$2y$10$k6cCujgaH7b..hq08x6/tO7XxypkGe.jZWYfwEclE/t6A3UuFP7iK'),
(16, 'ram', 'r@gmail.com', '$2y$10$KVNY.RiFgJYbTV3ja9OTkO9HytTghXFiIpnamH/XGBFGWbZT.jQT6'),
(17, 'krishna', 'k@gmail.com', '$2y$10$aHqFTOPyaFwziX7a5F8E5uy4PedLqMBPSDnuWey3w3oPQgTlLgzBW');

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
-- Indexes for table `bikes`
--
ALTER TABLE `bikes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `bike_ratings`
--
ALTER TABLE `bike_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bike_id` (`bike_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bikes`
--
ALTER TABLE `bikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `bike_ratings`
--
ALTER TABLE `bike_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bikes`
--
ALTER TABLE `bikes`
  ADD CONSTRAINT `bikes_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Constraints for table `bike_ratings`
--
ALTER TABLE `bike_ratings`
  ADD CONSTRAINT `bike_ratings_ibfk_1` FOREIGN KEY (`bike_id`) REFERENCES `bikes` (`id`),
  ADD CONSTRAINT `bike_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
