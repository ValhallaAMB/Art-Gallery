-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 08, 2024 at 03:49 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `art_gallery`
--

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

DROP TABLE IF EXISTS `artists`;
CREATE TABLE IF NOT EXISTS `artists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstName` varchar(80) NOT NULL,
  `lastName` varchar(80) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dateOfShow` date NOT NULL,
  `artPieces` int NOT NULL,
  `allArtStyles` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`id`, `firstName`, `lastName`, `email`, `dateOfShow`, `artPieces`, `allArtStyles`) VALUES
(1, 'Spods', 'Lespodsay', 'lespodsay@gmail.com', '2024-12-31', 11, 'Drawings, Print, New Media and Digital Art'),
(2, 'Koko', 'Knockout', 'knockout@gmail.com', '2024-12-31', 17, 'Print, New Media and Digital Art'),
(3, 'Lumi', 'Lumiere', 'lumorie@gmail.com', '2025-12-12', 20, 'Paintings, Print, New Media and Digital Art'),
(5, 'Meat', 'Rouwan', 'rowan@gmail.com', '2025-05-05', 20, 'Sculptures, Drawings, Print, New Media and Digital Art'),
(6, 'Awanqi', 'Awanqi', 'awanqi@gmail.com', '2024-12-31', 24, 'Paintings, Print, New Media and Digital Art'),
(7, 'Toshia', 'Toshia San', 'toshiasann@gmail.com', '2025-05-05', 11, 'Print, New Media and Digital Art'),
(8, 'Ei', 'Aleikats', 'aleiikats@gmail.com', '2024-12-31', 18, 'Drawings, Print, New Media and Digital Art'),
(9, 'Athena', 'Myrthena', 'myrthenaaa@gmail.com', '2025-12-12', 11, 'Paintings, Drawings, Print, New Media and Digital Art'),
(10, 'Sel', 'Avenoirn', 'avenoirnn@gmail.com', '2024-12-31', 9, 'Drawings, Print, New Media and Digital Art'),
(11, 'Doriane', 'Blibloop', 'blippitybloop@gmail.com', '2025-05-05', 16, 'Print, New Media and Digital Art'),
(12, 'Geneva', 'Gdbeeart', 'gdbeeeeart@gmail.com', '2024-12-31', 7, 'Print, New Media and Digital Art'),
(13, 'Nico', 'PimientosDulces', 'Pimientosnico@gmail.com', '2025-05-05', 9, 'Paintings, Print, New Media and Digital Art');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstName` varchar(80) NOT NULL,
  `lastName` varchar(80) NOT NULL,
  `birthDay` date NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `country` varchar(50) NOT NULL,
  `address` varchar(200) NOT NULL,
  `position` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `storedsalt` varchar(100) NOT NULL,
  `code` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `firstName`, `lastName`, `birthDay`, `gender`, `country`, `address`, `position`, `email`, `password`, `storedsalt`, `code`) VALUES
(1, 'Admin', 'Admin', '2000-01-01', 'Female', 'United States', 'none', 'Manager', 'artgallery23@outlook.com', '280135cc302e48c403eb05b0714c8533a5b77045617a4fb902d856015c49f84e', '9aa342d1bbe3f7bdab6ef9452adfc2ff', NULL),
(2, 'notAdmin', 'notAdmin', '2000-01-01', 'Male', 'Canada', 'none', 'AVTeam', 'notAdmin@gmail.com', 'bf8098e20d5ac5f27b8616a9225dbf2dcb2740bba1164701bb677d0e148e68f9', '7640ae862442fc87f573d97976146d4e', NULL),
(3, 'Nusaiba', 'Mekkaoui', '2001-11-26', 'Female', 'Lebanon', 'Istanbul, Turkey', 'Manager', 'nusaibamekka@gmail.com', 'b61af9e684c1a3f7fd2aa52999c413948b1c64ab7a9323918b8af6360d60a806', 'b6463797fca44562c7dec621f64646f7', NULL),
(4, 'Abdulmohaimin', 'Bashir', '2001-03-10', 'Male', 'Libya', 'Istanbul, Turkey', 'Manager', 'abdomohaiminbashir@hotmail.com', '2fcf8a41e5604981fa3be77ee0d1377cf5529bce03cb097339c86ccc98143660', '27d12fad29222d8337b5ac76d9cbb971', NULL),
(5, 'Muhammed', 'Bilal', '2001-08-01', 'Male', 'Pakistan', 'Istanbul, Turkey', 'Manager', 'rumbiedore@outlook.com', 'b28cd750c8b0a1c1b1f1069ade1242084cf00b972c544b46357238d039574d9c', 'e1444f6c54b94847b5c8ab82a768f459', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

DROP TABLE IF EXISTS `guests`;
CREATE TABLE IF NOT EXISTS `guests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstName` varchar(80) NOT NULL,
  `lastName` varchar(80) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `dateOfAttendance` date NOT NULL,
  `paidOrInvited` enum('Paid','Invited') NOT NULL,
  `plusOne` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `firstName`, `lastName`, `email`, `phoneNumber`, `dateOfAttendance`, `paidOrInvited`, `plusOne`) VALUES
(1, 'Tala', 'Rox', 'tmrocks@gmail.com', '2553446886', '2025-05-05', 'Invited', NULL),
(2, 'Samity', 'Sam', 'samitysam@gmail.com', '246810121', '2025-05-05', 'Invited', NULL),
(3, 'Aloosh', 'Le Moi', '3alooshlemoi@gmail.com', '5527553276', '2024-12-31', 'Invited', 'Yes'),
(4, 'Ameer', 'Amyar', 'ameeramyar@gmail.com', '241598456', '2025-12-12', 'Invited', NULL),
(5, 'Nagham', 'Melodia', 'iamthemelody@gmail.com', '3691472588', '2025-12-12', 'Invited', 'Yes'),
(6, 'Pete', 'The First', 'petetheking@gmail.com', '1239874566', '2024-12-31', 'Paid', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
