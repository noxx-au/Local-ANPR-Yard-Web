-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2021 at 07:48 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `local_anpr_yard_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_log`
--

CREATE TABLE `api_log` (
  `id` int(11) NOT NULL,
  `api_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `api_method` varchar(100) CHARACTER SET utf8 NOT NULL,
  `api_data` text CHARACTER SET utf8 NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `car_plates`
--

CREATE TABLE `car_plates` (
  `cp_id` varchar(36) NOT NULL,
  `rego` varchar(255) NOT NULL,
  `container_no` varchar(500) DEFAULT NULL,
  `axel_group_weight` varchar(100) DEFAULT NULL,
  `total_weight` varchar(100) DEFAULT NULL,
  `plate_c` decimal(18,4) NOT NULL DEFAULT 0.0000,
  `driver_name` varchar(255) DEFAULT NULL,
  `driver_mobile_no` varchar(20) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `vehicle_type` varchar(255) DEFAULT NULL,
  `site_entry` datetime DEFAULT NULL,
  `site_exit` datetime DEFAULT NULL,
  `exit` int(11) NOT NULL DEFAULT 0,
  `parked` int(1) NOT NULL DEFAULT 0,
  `reparked` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL,
  `is_sync` tinyint(1) NOT NULL DEFAULT 0,
  `trailer_rego` varchar(100) DEFAULT NULL,
  `manully_exit` tinyint(4) NOT NULL DEFAULT 0,
  `car_plates_id` int(10) NOT NULL,
  `rapid_id` int(11) DEFAULT NULL,
  `order_number` varchar(250) DEFAULT NULL,
  `cie_id` int(11) DEFAULT NULL,
  `vehicle_image` text DEFAULT NULL,
  `drivers_dec` int(11) NOT NULL DEFAULT 0,
  `driver_image` text DEFAULT NULL,
  `live_sync` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_log`
--
ALTER TABLE `api_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car_plates`
--
ALTER TABLE `car_plates`
  ADD PRIMARY KEY (`car_plates_id`),
  ADD UNIQUE KEY `cp_id_2` (`cp_id`),
  ADD KEY `cp_id` (`cp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_log`
--
ALTER TABLE `api_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car_plates`
--
ALTER TABLE `car_plates`
  MODIFY `car_plates_id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
