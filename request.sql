-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2024 at 04:18 PM
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
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `Info_id` int(250) NOT NULL,
  `ผู้เรียก` text NOT NULL,
  `สถานที่รับ` text NOT NULL,
  `ความเร่งด่วน` text NOT NULL,
  `ประเภทเปล` text NOT NULL,
  `เวลา` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `สถานะ` tinyint(2) NOT NULL,
  `ผู้รับ` text DEFAULT NULL,
  `สถานที่ส่ง` text NOT NULL,
  `ชื่อผู้ป่วย` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`Info_id`, `ผู้เรียก`, `สถานที่รับ`, `ความเร่งด่วน`, `ประเภทเปล`, `เวลา`, `สถานะ`, `ผู้รับ`, `สถานที่ส่ง`, `ชื่อผู้ป่วย`) VALUES
(1, 'นางสาวพิงค์', 'ห้องฉุกเฉิน', 'ด่วนมาก', 'เปลนอน', '2024-05-21 07:02:44', 2, 'Ping', 'ICU', 'นายพิงค์'),
(2, 'นางสาวพิงค์', 'ห้องฉุกเฉิน', 'ด่วน', 'เปลนอน', '2024-05-16 05:55:29', 0, '', 'ICU', 'นายพิงค์'),
(5, 'นาย จิ๋วเเก้ว', 'ห้องผ่าตัด', 'ด่วนมาก', 'วีลเเช', '2024-05-21 07:20:36', 2, 'S.UTUMPORN_10', 'ICU', 'นายพิงค์'),
(9, 'นายสาว หฤษฎ์', 'ICU', 'ด่วนมาก', 'เปลนอน', '2024-06-30 16:33:43', 2, 'Ping', 'ICU', 'นายพิงค์'),
(10, 'นายสาว หฤษฎ์', 'ICU', 'ด่วนมาก', 'เปลนอน', '2024-07-11 15:10:05', 2, 'Ping', 'ICU', 'นายพิงค์'),
(11, 'นายเกี๊ยว', 'ห้องทำเเผล', 'ด่วน', 'วีลเเช', '2024-05-16 05:55:35', 0, '', 'ICU', 'นายพิงค์'),
(12, 'นายเกี๊ยว', 'ห้องทำเเผล', 'ด่วน', 'วีลเเช', '2024-05-16 05:55:36', 0, '', 'ICU', 'นายพิงค์'),
(13, 'นางพร', 'ห้องตรวจ', 'ด่วน', 'วีลเเช', '2024-05-20 08:27:44', 0, '', 'ICU', 'นายพิงค์'),
(14, 'นายพร', 'ห้องฉุกเฉิน', 'ด่วนมาก', 'เปลนอน', '2024-05-20 14:14:38', 0, '', 'ICU', 'นายพิงค์'),
(15, 'นายพร', 'ห้องฉุกเฉิน', 'ด่วนมาก', 'เปลนอน', '2024-05-20 08:27:47', 0, '', 'ICU', 'นายพิงค์'),
(16, 'นางเเจ็ค', 'ห้องฉุกเฉิน', 'ด่วนมาก', 'เปลนอน', '2024-05-20 08:27:49', 0, '', 'ICU', 'นายพิงค์'),
(17, 'นางเเจ็ค', 'ห้องฉุกเฉิน', 'ด่วนมาก', 'เปลนอน', '2024-05-20 08:27:51', 0, '', 'ICU', 'นายพิงค์');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`Info_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `Info_id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
