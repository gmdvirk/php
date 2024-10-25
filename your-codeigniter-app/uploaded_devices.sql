-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 14, 2022 at 11:58 PM
-- Server version: 8.0.23
-- PHP Version: 7.1.33-44+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ci_badge`
--

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_devices`
--

CREATE TABLE `uploaded_devices` (
  `id` int NOT NULL,
  `upload_id` int NOT NULL,
  `device_id` varchar(50) NOT NULL,
  `vAssigned_Facility_ID` int NOT NULL,
  `ignored` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `uploaded_devices`
--
ALTER TABLE `uploaded_devices`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `uploaded_devices`
--
ALTER TABLE `uploaded_devices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
