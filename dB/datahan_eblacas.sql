-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2025 at 09:43 AM
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
-- Database: `datahan_eblacas`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventoryId` int(11) NOT NULL,
  `genericName` varchar(255) NOT NULL,
  `brandName` varchar(255) NOT NULL,
  `milligram` int(20) NOT NULL,
  `dosageForm` varchar(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `price` int(255) NOT NULL,
  `group` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventoryId`, `genericName`, `brandName`, `milligram`, `dosageForm`, `quantity`, `price`, `group`) VALUES
(1, 'Paracetamol', 'Biogesic', 500, 'Tablet', 93, 10, 'Analgesic'),
(2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 76, 6, 'Analgesic'),
(3, 'Paracetamol', 'Tempra', 250, 'Syrup', 52, 10, 'Analgesic'),
(4, 'Paracetamol', 'Calpol', 120, 'Syrup', 84, 12, 'Analgesic'),
(5, 'Paracetamol', 'Dolex', 500, 'Tablet', 110, 7, 'Analgesic'),
(6, 'Ibuprofen', 'Advil', 200, 'Capsule', 50, 8, 'NSAID'),
(7, 'Ibuprofen', 'Motrin', 400, 'Tablet', 70, 9, 'NSAID'),
(8, 'Ibuprofen', 'Nurofen', 200, 'Tablet', 60, 7, 'NSAID'),
(9, 'Ibuprofen', 'Brufen', 600, 'Tablet', 90, 12, 'NSAID'),
(10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 59, 10, 'Antibiotic'),
(11, 'Amoxicillin', 'Himox', 500, 'Capsule', 60, 11, 'Antibiotic'),
(12, 'Amoxicillin', 'Moxatag', 875, 'Tablet', 50, 15, 'Antibiotic'),
(13, 'Amoxicillin', 'Trimox', 250, 'Syrup', 100, 14, 'Antibiotic'),
(14, 'Cetirizine', 'Zyrtec', 10, 'Tablet', 120, 12, 'Antihistamine'),
(15, 'Cetirizine', 'Reactine', 10, 'Tablet', 110, 11, 'Antihistamine'),
(16, 'Cetirizine', 'Allertec', 10, 'Tablet', 90, 10, 'Antihistamine'),
(17, 'Cetirizine', 'Cetzine', 5, 'Syrup', 100, 9, 'Antihistamine'),
(18, 'Metformin', 'Glucophage', 850, 'Tablet', 82, 15, 'Antidiabetic'),
(19, 'Metformin', 'Glycomet', 500, 'Tablet', 92, 14, 'Antidiabetic'),
(20, 'Metformin', 'Formet', 1000, 'Tablet', 74, 18, 'Antidiabetic'),
(21, 'Metformin', 'Riomet', 500, 'Syrup', 70, 16, 'Antidiabetic'),
(22, 'Losartan', 'Cozaar', 50, 'Tablet', 80, 20, 'Antihypertensive'),
(23, 'Losartan', 'Hyzaar', 100, 'Tablet', 90, 25, 'Antihypertensive'),
(24, 'Losartan', 'Angisartan', 50, 'Tablet', 75, 18, 'Antihypertensive'),
(25, 'Losartan', 'Losacar', 100, 'Tablet', 95, 22, 'Antihypertensive');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `orderId` int(11) NOT NULL,
  `datetime` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`orderId`, `datetime`) VALUES
(1, '2025-03-18 10:15:30'),
(2, '2025-03-18 14:22:10'),
(3, '2025-03-19 09:45:00'),
(4, '2025-03-19 11:30:25'),
(5, '2025-03-19 16:10:45'),
(6, '2025-03-20 08:10:15'),
(7, '2025-03-20 13:45:50'),
(8, '2025-03-20 19:20:05'),
(9, '2025-03-21 07:55:40'),
(10, '2025-03-21 12:30:00'),
(11, '2025-03-21 17:50:30'),
(12, '2025-03-22 09:00:20'),
(13, '2025-03-22 14:10:05'),
(14, '2025-03-22 18:40:55'),
(15, '2025-03-23 10:25:15'),
(16, '2025-03-23 13:35:40'),
(17, '2025-03-23 15:55:50'),
(18, '2025-03-23 20:05:30'),
(19, '2025-03-24 09:20:15'),
(20, '2025-03-24 11:50:40'),
(21, '2025-03-24 14:05:55'),
(22, '2025-03-24 16:30:10'),
(23, '2025-03-24 18:45:20'),
(24, '2025-03-24 20:10:35'),
(25, '2025-03-24 22:30:50'),
(26, '2025-03-24 17:05:45'),
(27, '2025-03-25 08:23:49'),
(28, '2025-03-25 08:38:29'),
(29, '2025-03-25 08:38:49'),
(30, '2025-03-25 08:39:19'),
(31, '2025-03-25 08:40:40'),
(32, '2025-03-25 08:40:43'),
(33, '2025-03-25 08:42:01'),
(34, '2025-03-25 08:42:15'),
(35, '2025-03-25 08:42:27'),
(36, '2025-03-25 08:46:43'),
(37, '2025-03-25 08:46:46'),
(38, '2025-03-25 08:46:52'),
(39, '2025-03-25 08:49:57'),
(40, '2025-03-25 08:50:07'),
(41, '2025-03-25 08:50:11'),
(42, '2025-03-25 08:54:28'),
(43, '2025-03-25 08:54:34'),
(44, '2025-03-25 08:58:21'),
(45, '2025-03-25 08:58:24'),
(46, '2025-03-25 08:59:48'),
(47, '2025-03-25 08:59:53'),
(48, '2025-03-25 09:04:20'),
(49, '2025-03-25 09:04:29'),
(50, '2025-03-25 09:08:25'),
(51, '2025-03-25 09:08:37'),
(52, '2025-03-25 09:11:16'),
(53, '2025-03-25 09:11:18'),
(54, '2025-03-25 09:12:42'),
(55, '2025-03-25 09:12:44'),
(56, '2025-03-25 09:12:47'),
(57, '2025-03-25 09:16:17'),
(58, '2025-03-25 09:16:18'),
(59, '2025-03-25 09:17:55'),
(60, '2025-03-25 09:18:04'),
(61, '2025-03-25 09:18:11'),
(62, '2025-03-25 09:19:54'),
(63, '2025-03-25 09:20:04'),
(64, '2025-03-25 09:36:12'),
(65, '2025-03-25 09:36:19'),
(66, '2025-03-25 09:36:25'),
(67, '2025-03-25 09:36:28');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `orderItemId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `inventoryId` int(11) DEFAULT NULL,
  `genericName` varchar(255) DEFAULT NULL,
  `brandName` varchar(255) DEFAULT NULL,
  `milligram` int(11) DEFAULT NULL,
  `dosageForm` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`orderItemId`, `orderId`, `inventoryId`, `genericName`, `brandName`, `milligram`, `dosageForm`, `quantity`, `price`, `group`, `total`) VALUES
(1, 1, 1, 'Paracetamol', 'Biogesic', 500, 'Tablet', 2, 10.00, 'Analgesic', 20.00),
(2, 1, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 1, 10.00, 'Antibiotic', 10.00),
(3, 2, 6, 'Ibuprofen', 'Advil', 200, 'Capsule', 3, 8.00, 'NSAID', 24.00),
(4, 3, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(5, 3, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 2, 10.00, 'Analgesic', 20.00),
(6, 3, 14, 'Cetirizine', 'Zyrtec', 10, 'Tablet', 1, 12.00, 'Antihistamine', 12.00),
(7, 4, 7, 'Ibuprofen', 'Motrin', 400, 'Tablet', 2, 9.00, 'NSAID', 18.00),
(8, 4, 15, 'Cetirizine', 'Reactine', 10, 'Tablet', 1, 11.00, 'Antihistamine', 11.00),
(9, 4, 23, 'Losartan', 'Hyzaar', 100, 'Tablet', 1, 25.00, 'Antihypertensive', 25.00),
(10, 5, 17, 'Cetirizine', 'Cetzine', 5, 'Syrup', 3, 9.00, 'Antihistamine', 27.00),
(11, 5, 24, 'Losartan', 'Angisartan', 50, 'Tablet', 2, 18.00, 'Antihypertensive', 36.00),
(12, 6, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(13, 6, 22, 'Losartan', 'Cozaar', 50, 'Tablet', 1, 20.00, 'Antihypertensive', 20.00),
(14, 7, 5, 'Paracetamol', 'Dolex', 500, 'Tablet', 1, 7.00, 'Analgesic', 7.00),
(15, 7, 21, 'Metformin', 'Riomet', 500, 'Syrup', 2, 16.00, 'Antidiabetic', 32.00),
(16, 8, 8, 'Ibuprofen', 'Nurofen', 200, 'Tablet', 3, 7.00, 'NSAID', 21.00),
(17, 8, 18, 'Metformin', 'Glucophage', 850, 'Tablet', 1, 15.00, 'Antidiabetic', 15.00),
(18, 9, 16, 'Cetirizine', 'Allertec', 10, 'Tablet', 2, 10.00, 'Antihistamine', 20.00),
(19, 9, 25, 'Losartan', 'Losacar', 100, 'Tablet', 1, 22.00, 'Antihypertensive', 22.00),
(20, 10, 9, 'Ibuprofen', 'Brufen', 600, 'Tablet', 1, 12.00, 'NSAID', 12.00),
(21, 10, 13, 'Amoxicillin', 'Trimox', 250, 'Syrup', 1, 14.00, 'Antibiotic', 14.00),
(22, 11, 4, 'Paracetamol', 'Calpol', 120, 'Syrup', 2, 12.00, 'Analgesic', 24.00),
(23, 11, 12, 'Amoxicillin', 'Moxatag', 875, 'Tablet', 1, 15.00, 'Antibiotic', 15.00),
(24, 12, 19, 'Metformin', 'Glycomet', 500, 'Tablet', 2, 14.00, 'Antidiabetic', 28.00),
(25, 12, 20, 'Metformin', 'Formet', 1000, 'Tablet', 1, 18.00, 'Antidiabetic', 18.00),
(26, 13, 14, 'Cetirizine', 'Zyrtec', 10, 'Tablet', 1, 12.00, 'Antihistamine', 12.00),
(27, 13, 23, 'Losartan', 'Hyzaar', 100, 'Tablet', 1, 25.00, 'Antihypertensive', 25.00),
(28, 14, 5, 'Paracetamol', 'Dolex', 500, 'Tablet', 1, 7.00, 'Analgesic', 7.00),
(29, 14, 15, 'Cetirizine', 'Reactine', 10, 'Tablet', 1, 11.00, 'Antihistamine', 11.00),
(30, 15, 9, 'Ibuprofen', 'Brufen', 600, 'Tablet', 2, 12.00, 'NSAID', 24.00),
(31, 15, 22, 'Losartan', 'Cozaar', 50, 'Tablet', 1, 20.00, 'Antihypertensive', 20.00),
(32, 26, 4, 'Paracetamol', 'Calpol', 120, 'Syrup', 1, 12.00, 'Analgesic', 12.00),
(33, 26, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(34, 26, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(35, 26, 12, 'Amoxicillin', 'Moxatag', 875, 'Tablet', 2, 15.00, 'Antibiotic', 30.00),
(36, 27, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(37, 28, 1, 'Paracetamol', 'Biogesic', 500, 'Tablet', 3, 10.00, 'Analgesic', 30.00),
(38, 28, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(39, 29, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 2, 6.00, 'Analgesic', 12.00),
(40, 29, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 1, 10.00, 'Antibiotic', 10.00),
(41, 29, 19, 'Metformin', 'Glycomet', 500, 'Tablet', 1, 14.00, 'Antidiabetic', 14.00),
(42, 29, 20, 'Metformin', 'Formet', 1000, 'Tablet', 2, 18.00, 'Antidiabetic', 36.00),
(43, 30, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 2, 10.00, 'Analgesic', 20.00),
(44, 30, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 2, 11.00, 'Antibiotic', 22.00),
(45, 31, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(46, 31, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(47, 31, 12, 'Amoxicillin', 'Moxatag', 875, 'Tablet', 2, 15.00, 'Antibiotic', 30.00),
(48, 32, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 2, 6.00, 'Analgesic', 12.00),
(49, 32, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 2, 10.00, 'Antibiotic', 20.00),
(50, 33, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 2, 6.00, 'Analgesic', 12.00),
(51, 33, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(52, 34, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 2, 6.00, 'Analgesic', 12.00),
(53, 34, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 1, 10.00, 'Antibiotic', 10.00),
(54, 35, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 2, 6.00, 'Analgesic', 12.00),
(55, 35, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(56, 35, 19, 'Metformin', 'Glycomet', 500, 'Tablet', 1, 14.00, 'Antidiabetic', 14.00),
(57, 35, 18, 'Metformin', 'Glucophage', 850, 'Tablet', 1, 15.00, 'Antidiabetic', 15.00),
(58, 36, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(59, 36, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(60, 36, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 1, 10.00, 'Antibiotic', 10.00),
(61, 37, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(62, 37, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(63, 37, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(64, 38, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(65, 38, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(66, 38, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(67, 39, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(68, 39, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(69, 39, 4, 'Paracetamol', 'Calpol', 120, 'Syrup', 1, 12.00, 'Analgesic', 12.00),
(70, 39, 12, 'Amoxicillin', 'Moxatag', 875, 'Tablet', 2, 15.00, 'Antibiotic', 30.00),
(71, 40, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(72, 40, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(73, 40, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 1, 10.00, 'Antibiotic', 10.00),
(74, 41, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(75, 41, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(76, 41, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 1, 10.00, 'Antibiotic', 10.00),
(77, 42, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(78, 42, 1, 'Paracetamol', 'Biogesic', 500, 'Tablet', 1, 10.00, 'Analgesic', 10.00),
(79, 43, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(80, 43, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(81, 43, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 1, 10.00, 'Antibiotic', 10.00),
(82, 44, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(83, 44, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(84, 45, 12, 'Amoxicillin', 'Moxatag', 875, 'Tablet', 1, 15.00, 'Antibiotic', 15.00),
(85, 45, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(86, 45, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(87, 46, 1, 'Paracetamol', 'Biogesic', 500, 'Tablet', 1, 10.00, 'Analgesic', 10.00),
(88, 46, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(89, 47, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(90, 47, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(91, 48, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 2, 6.00, 'Analgesic', 12.00),
(92, 48, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(93, 48, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(94, 49, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(95, 49, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(96, 50, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(97, 50, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(98, 51, 1, 'Paracetamol', 'Biogesic', 500, 'Tablet', 1, 10.00, 'Analgesic', 10.00),
(99, 51, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(100, 52, 1, 'Paracetamol', 'Biogesic', 500, 'Tablet', 1, 10.00, 'Analgesic', 10.00),
(101, 52, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(102, 53, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(103, 53, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(104, 54, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(105, 55, 12, 'Amoxicillin', 'Moxatag', 875, 'Tablet', 1, 15.00, 'Antibiotic', 15.00),
(106, 56, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 1, 10.00, 'Antibiotic', 10.00),
(107, 56, 19, 'Metformin', 'Glycomet', 500, 'Tablet', 1, 14.00, 'Antidiabetic', 14.00),
(108, 57, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(109, 57, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 1, 10.00, 'Antibiotic', 10.00),
(110, 58, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(111, 59, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(112, 60, 12, 'Amoxicillin', 'Moxatag', 875, 'Tablet', 1, 15.00, 'Antibiotic', 15.00),
(113, 60, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(114, 60, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(115, 61, 18, 'Metformin', 'Glucophage', 850, 'Tablet', 2, 15.00, 'Antidiabetic', 30.00),
(116, 61, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 1, 10.00, 'Antibiotic', 10.00),
(117, 61, 19, 'Metformin', 'Glycomet', 500, 'Tablet', 1, 14.00, 'Antidiabetic', 14.00),
(118, 62, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 1, 6.00, 'Analgesic', 6.00),
(119, 62, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 1, 10.00, 'Antibiotic', 10.00),
(120, 63, 19, 'Metformin', 'Glycomet', 500, 'Tablet', 1, 14.00, 'Antidiabetic', 14.00),
(121, 63, 20, 'Metformin', 'Formet', 1000, 'Tablet', 1, 18.00, 'Antidiabetic', 18.00),
(122, 63, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 2, 11.00, 'Antibiotic', 22.00),
(123, 64, 3, 'Paracetamol', 'Tempra', 250, 'Syrup', 1, 10.00, 'Analgesic', 10.00),
(124, 64, 12, 'Amoxicillin', 'Moxatag', 875, 'Tablet', 1, 15.00, 'Antibiotic', 15.00),
(125, 65, 11, 'Amoxicillin', 'Himox', 500, 'Capsule', 1, 11.00, 'Antibiotic', 11.00),
(126, 65, 19, 'Metformin', 'Glycomet', 500, 'Tablet', 1, 14.00, 'Antidiabetic', 14.00),
(127, 65, 18, 'Metformin', 'Glucophage', 850, 'Tablet', 1, 15.00, 'Antidiabetic', 15.00),
(128, 66, 20, 'Metformin', 'Formet', 1000, 'Tablet', 1, 18.00, 'Antidiabetic', 18.00),
(129, 66, 19, 'Metformin', 'Glycomet', 500, 'Tablet', 1, 14.00, 'Antidiabetic', 14.00),
(130, 66, 18, 'Metformin', 'Glucophage', 850, 'Tablet', 2, 15.00, 'Antidiabetic', 30.00),
(131, 67, 2, 'Paracetamol', 'Tylenol', 500, 'Tablet', 3, 6.00, 'Analgesic', 18.00),
(132, 67, 10, 'Amoxicillin', 'Amoxil', 500, 'Capsule', 1, 10.00, 'Antibiotic', 10.00);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `staff_name` varchar(100) NOT NULL,
  `staff_id` varchar(20) NOT NULL CHECK (`staff_id` regexp '^A-001[0-9]*$' or `staff_id` regexp '^S-001[0-9]*$'),
  `email` varchar(100) NOT NULL,
  `shifts` enum('Day','Afternoon','Night') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `staff_name`, `staff_id`, `email`, `shifts`) VALUES
(1, 'Esther Eblacas', 'A-0011', 'eblacas@gmail.com', 'Afternoon'),
(2, 'John Smith', 'A-0012', 'smith@gmail.com', 'Night'),
(3, 'Marisol Datahan', 'S-0011', 'marisol@gmail.com', 'Day'),
(4, 'Krysel Tiempo', 'S-0012', 'krysel@gmail.com', 'Afternoon'),
(5, 'Ezra Marinas', 'S-0013', 'ezra@gmail.com', 'Night'),
(6, 'Charlene Lusterio', 'S-0014', 'charlene@gmail.com', 'Day'),
(7, 'Therese Solangon', 'S-0015', 'therese@gmail.com', 'Afternoon'),
(8, 'Lordwell Abalde', 'S-0016', 'lordweil@gmail.com', 'Night');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNumber` varchar(255) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `birthday` date NOT NULL,
  `verification` int(11) NOT NULL DEFAULT 0,
  `profilePicture` longblob DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `firstName`, `lastName`, `email`, `password`, `phoneNumber`, `gender`, `birthday`, `verification`, `profilePicture`, `role`, `createdAt`) VALUES
(1, 'Dummy', 'Account', 'dummy@gmail.com', '123123', '091231241', 'Male', '2025-02-17', 0, NULL, 'admin', '2025-02-27 10:33:05'),
(2, 'Esther Beuthel', 'Eblacas', 'eblacas@gmail.com', 'eblacas', '09367813089', 'Female', '2025-03-08', 0, NULL, 'user', '2025-03-08 13:24:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventoryId`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`orderId`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`orderItemId`),
  ADD KEY `inventoryId` (`inventoryId`),
  ADD KEY `order_items_fk_order` (`orderId`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_id` (`staff_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `orderItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_fk_order` FOREIGN KEY (`orderId`) REFERENCES `order` (`orderId`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`inventoryId`) REFERENCES `inventory` (`inventoryId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
