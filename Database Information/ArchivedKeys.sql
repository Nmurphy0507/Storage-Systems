-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 07, 2025 at 09:33 PM
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
-- Table structure for table `ArchivedKeys`
--

CREATE TABLE `ArchivedKeys` (
  `id` int(100) NOT NULL,
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
  `ArchivedDate` date NOT NULL DEFAULT current_timestamp(),
  `notes` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ArchivedKeys`
--

INSERT INTO `ArchivedKeys` (`id`, `first_name`, `last_name`, `building`, `room`, `group`, `mealcard`, `key_number`, `checkin_signature`, `Checked_in_out`, `key_returned`, `mealcard_returned`, `Date`, `ArchivedDate`, `notes`) VALUES
(82, 'Nathan', 'Murphy', 'Cumberland', '201 / 201A', '2', 'W-125', 'WASD-45-4', 'NathanAMurphy', 'Checked Out', NULL, NULL, '2024-07-26', '2025-02-05', NULL),
(83, 'Nathan', 'Murphy', 'Cumberland', '201 / 201A', '2', 'W-125-3D', 'WASD-45-3', 'NathanAMurphy', 'Checked Out', NULL, NULL, '2025-02-03', '2025-02-05', NULL),
(84, 'john', 'Murphy', 'Cumberland', '422 / 422C', '2', 'W-125-3D', 'WASD-45-3', 'NathanAMurph', 'Checked Out', NULL, NULL, '2025-02-02', '2025-02-05', NULL),
(88, 'Sheri', 'Murphy', 'Frederick', '201 / 201A', '2', 'arsetdrytfuygibuohin', 'WASD-65-1', 'Sheri R Murphy', 'Checked Out', NULL, NULL, '2025-02-02', '2025-02-06', NULL),
(93, 'Sheri', 'Murphy', 'Cumberland', '201 / 201A', '2', '1234567', 'WASD-65-1', 'Sheri R Murphy', 'Checked Out', NULL, NULL, '2024-11-09', '2025-02-07', ''),
(94, 'Abby', 'Sontaro', 'Frederick', '222 / 222D', '', '', '', '', 'Checked Out', NULL, NULL, '2025-01-29', '2025-02-07', 'did not turn in key'),
(99, 'Murphy', 'Murphy FSU', 'Allen', '422 / 422D', 'FSY', 'Com', 'WASD-45-5', 'Nathan A Murphy', 'Checked Out', 'Yes', 'Yes', '2025-02-04', '2025-02-07', 'wedrfghujikolp');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ArchivedKeys`
--
ALTER TABLE `ArchivedKeys`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ArchivedKeys`
--
ALTER TABLE `ArchivedKeys`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
