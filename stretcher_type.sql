-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2024 at 07:39 AM
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
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `stretcher_type`
--

CREATE TABLE `stretcher_type` (
  `stretcher_type_id` double DEFAULT NULL,
  `stretcher_type_name` varchar(10) DEFAULT NULL,
  `stretcher_type_active` varchar(10) DEFAULT NULL,
  `stretcher_type_o2tube_chk` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stretcher_type`
--

INSERT INTO `stretcher_type` (`stretcher_type_id`, `stretcher_type_name`, `stretcher_type_active`, `stretcher_type_o2tube_chk`) VALUES
(1, 'นอน', 'Y', ''),
(3, 'นั่ง', 'Y', ''),
(4, 'นั่ง(มีล้อ', 'Y', ''),
(5, 'ล้อเข็นนอน', 'Y', 'Y');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
