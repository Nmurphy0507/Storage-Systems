-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 30, 2024 at 04:06 PM
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
-- Database: `Dormroom_Keys`
--

-- --------------------------------------------------------

--
-- Table structure for table `Checkin-out`
--

CREATE TABLE `Checkin-out` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `building` varchar(255) NOT NULL,
  `room` varchar(255) NOT NULL,
  `floor` varchar(255) DEFAULT NULL,
  `barcode` varchar(255) NOT NULL,
  `key_number` varchar(255) NOT NULL,
  `checkin_signature` varchar(255) DEFAULT NULL,
  `Checked_in_out` varchar(255) DEFAULT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Checkin-out`
--

INSERT INTO `Checkin-out` (`id`, `first_name`, `last_name`, `building`, `room`, `floor`, `barcode`, `key_number`, `checkin_signature`, `Checked_in_out`, `Date`) VALUES
(16, 'Nathan', 'Murphy', 'Cumberland', '201 / 201A', '2', 'W-125-3D', 'WASD-45-4', 'NathanAMurphy', 'Checked In', '2024-07-26'),
(18, 'Nathan', 'Murphy', 'Cumberland', '201 / 201A', '2', 'W-125-3D', 'WASD-45-3', 'NathanAMurphy', 'Checked In', '2024-07-26'),
(19, 'Nathan', 'Murphy', 'Cumberland', '422 / 422C', '2', 'W-125-3D', 'WASD-45-3', 'NathanAMurphy', 'Checked In', '2024-07-27'),
(20, 'Nathan', 'Murphy', 'Diehl', '422 / 422C', '4', 'W-125-3D', 'WASD-45-3', 'NathanAMurphy', 'Checked In', '2024-07-31'),
(22, 'Sheri', 'Murphy', 'Frederick', '201 / 201A', '2', 'arsetdrytfuygibuohin', 'WASD-65-1', 'Sheri R Murphy', 'Checked Out', '2024-08-26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Checkin-out`
--
ALTER TABLE `Checkin-out`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Checkin-out`
--
ALTER TABLE `Checkin-out`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
