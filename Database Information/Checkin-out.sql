-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 07, 2025 at 09:34 PM
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
  `room` varchar(255) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `mealcard` varchar(255) DEFAULT NULL,
  `key_number` varchar(255) DEFAULT NULL,
  `checkin_signature` varchar(255) DEFAULT NULL,
  `Checked_in_out` varchar(255) DEFAULT NULL,
  `key_returned` varchar(255) DEFAULT NULL,
  `mealcard_returned` varchar(255) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `notes` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Checkin-out`
--

INSERT INTO `Checkin-out` (`id`, `first_name`, `last_name`, `building`, `room`, `group`, `mealcard`, `key_number`, `checkin_signature`, `Checked_in_out`, `key_returned`, `mealcard_returned`, `Date`, `notes`) VALUES
(26, 'Jeff', 'Sontaro', 'Frost', '222 / 222D', '2', 'W-125', 'WASD-45-3', 'Nathan A Murphy', 'Checked In', 'Yes', 'Yes', '2025-02-05', NULL),
(36, 'Five', 'Murphy', 'Frederick', '422 / 422C', '2', 'W-125', 'WASD-45-3, 345678', 'NathanAMurphy', 'Checked In', NULL, NULL, '2025-02-06', NULL),
(37, 'Five', 'Sontaro', 'Gray', '422 / 422D', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'Allen', 'Flan', 'Frost', '422 / 422C', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'Liam', 'Murphy', 'Simpson', '201 / 201D', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'Jim', 'Murphy', 'Sowers', '222 / 222D', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 'Abby', 'Murphy', 'Westminster', '422 / 422D', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 'Josh', 'Smith', 'Diehl', '201 / 201A', '', '', '', '', NULL, NULL, NULL, '2025-02-03', 'this is a test archive'),
(53, 'Arnold', 'Murphy', 'Diehl', '201 / 201A', '2', 'Commuter', 'WASD-45-3', 'Nathan', 'Checked In', 'Yes', 'Yes', '2025-02-07', NULL),
(54, 'Arnold', 'Duck', 'Cumberland', '201 / 201A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 'Abby', 'Duck', 'Annapolis', '201 / 201A', '2', 'W-125', 'WASD-45-3', 'NathanAMurphy', 'Checked In', 'Yes', 'Yes', '2025-02-07', NULL),
(56, 'Abby', 'Sontaro', 'Annapolis', '201 / 201A', '2', 'W-125', 'WASD-45-3', 'NathanAMurphy', ' Checked In', 'Yes', 'Yes', '2025-02-12', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
