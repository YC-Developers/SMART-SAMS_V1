-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 09:42 AM
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
-- Database: `attendancemsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `absents`
--

CREATE TABLE `absents` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `className` varchar(255) NOT NULL,
  `regNumber` varchar(255) NOT NULL,
  `mode` varchar(255) NOT NULL,
  `day` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `absents`
--

INSERT INTO `absents` (`id`, `name`, `gender`, `className`, `regNumber`, `mode`, `day`, `reason`, `school`, `date`) VALUES
(99, 'AHIMBAZWE Angelo', 'MALE', 'L4 SOFTWARE DEVELOPMENT B', 'L4SODB01', 'morning', '4', 'Absent', 'ETS', '2025-02-20'),
(100, 'DUSABIKIZA KING', 'MALE', 'L4 SOFTWARE DEVELOPMENT B', 'L4SODB04', 'morning', '4', 'Sick', 'ETS', '2025-02-20'),
(101, 'ISHIMWE GHISLAIN', 'MALE', 'L4 SOFTWARE DEVELOPMENT B', 'L4SODB14', 'morning', '4', 'Absent', 'ETS', '2025-02-20'),
(102, 'AHIMBAZWE Angelo', 'MALE', 'L4 SOFTWARE DEVELOPMENT B', 'L4SODB01', 'morning', '5', 'Sick', 'ETS', '2025-02-28'),
(103, 'DUSABIKIZA KING', 'MALE', 'L4 SOFTWARE DEVELOPMENT B', 'L4SODB04', 'morning', '5', 'Absent', 'ETS', '2025-02-28'),
(104, 'ANGE Noella', 'FEMALE', 'S4 ACCOUNTING', '17001S40022025', 'evening', '1', 'Absent', 'GS Rilima', '2025-03-24');

-- --------------------------------------------------------

--
-- Table structure for table `atdetails`
--

CREATE TABLE `atdetails` (
  `id` int(255) NOT NULL,
  `className` text NOT NULL,
  `mode` varchar(50) NOT NULL,
  `presentboys` int(80) NOT NULL,
  `absentboys` int(80) NOT NULL,
  `presentgirls` int(80) NOT NULL,
  `absentgirls` int(80) NOT NULL,
  `day` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atdetails`
--

INSERT INTO `atdetails` (`id`, `className`, `mode`, `presentboys`, `absentboys`, `presentgirls`, `absentgirls`, `day`, `school`, `date`) VALUES
(205, 'L4 SOFTWARE DEVELOPMENT B', 'morning', 20, 2, 12, 0, '4', 'ETS', '2025-02-20'),
(206, 'L4 SOFTWARE DEVELOPMENT B', 'morning', 21, 1, 12, 0, '4', 'ETS', '2025-02-20'),
(207, 'L4 SOFTWARE DEVELOPMENT B', 'morning', 20, 2, 12, 0, '5', 'ETS', '2025-02-28'),
(208, 'S4 ACCOUNTING', 'evening', 10, 0, 9, 1, '1', 'GS Rilima', '2025-03-24');

-- --------------------------------------------------------

--
-- Table structure for table `class_lessons`
--

CREATE TABLE `class_lessons` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_lessons`
--

INSERT INTO `class_lessons` (`id`, `lesson_id`, `class_id`) VALUES
(9, 10, 12),
(12, 3, 27),
(13, 13, 28);

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(10) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `lesson_title` varchar(255) NOT NULL,
  `lesson_description` text DEFAULT NULL,
  `school` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `teacher_id`, `lesson_title`, `lesson_description`, `school`, `created_at`, `updated_at`) VALUES
(3, 3, 'English', 'bbdbbdjksksjk', 'ETS', '2025-03-04 16:33:10', '2025-03-04 16:33:10'),
(4, 3, 'php', 'ckjscskcsjkjkjsjssj', 'ETS', '2025-03-04 16:33:28', '2025-03-04 16:33:28'),
(6, 3, 'mongo db', 'bckjsbajka', 'ETS', '2025-03-04 16:34:06', '2025-03-04 16:34:06'),
(10, 3, 'Programming Basics', 'Intro to coding', 'ETS', '2025-03-04 17:01:58', '2025-03-04 17:01:58'),
(11, 3, 'Database Systems', 'SQL fundamentals', 'ETS', '2025-03-04 17:02:18', '2025-03-04 17:02:18'),
(13, 7, 'english', 'fvvvvfvf', 'GS Rilima', '2025-03-24 17:31:00', '2025-03-24 17:31:00'),
(21, 9, 'BACKEND WITH NODE JS', 'moomomoomomomomo', 'SAINT JOSEPH NYANZA', '2025-03-31 10:39:34', '2025-03-31 10:39:34');

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` int(255) NOT NULL,
  `levelName` varchar(255) NOT NULL,
  `levelS` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `levelName`, `levelS`, `school`) VALUES
(3, 'SINIOR FOUR', 'S4', 'GS Rilima'),
(4, 'Level5', 'L5', 'ETS'),
(5, 'LEVEL3', 'L3', 'SAINT JOSEPH NYANZA'),
(6, 'LEVEL4', 'L4', 'SAINT JOSEPH NYANZA');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `districtName` varchar(100) NOT NULL,
  `province` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `districtName`, `province`) VALUES
(1, 'Nyarugenge', 'Kigali City'),
(2, 'Gasabo', 'Kigali City'),
(3, 'Kicukiro', 'Kigali City'),
(4, 'Burera', 'Northern'),
(5, 'Gakenke', 'Northern'),
(6, 'Gicumbi', 'Northern'),
(7, 'Musanze', 'Northern'),
(8, 'Rulindo', 'Northern'),
(9, 'Gisagara', 'Southern'),
(10, 'Huye', 'Southern'),
(11, 'Kamonyi', 'Southern'),
(12, 'Muhanga', 'Southern'),
(13, 'Nyamagabe', 'Southern'),
(14, 'Nyanza', 'Southern'),
(15, 'Nyaruguru', 'Southern'),
(16, 'Ruhango', 'Southern'),
(17, 'Bugesera', 'Eastern'),
(18, 'Gatsibo', 'Eastern'),
(19, 'Kayonza', 'Eastern'),
(20, 'Kirehe', 'Eastern'),
(21, 'Ngoma', 'Eastern'),
(22, 'Nyagatare', 'Eastern'),
(23, 'Rwamagana', 'Eastern'),
(24, 'Karongi', 'Western'),
(25, 'Ngororero', 'Western'),
(26, 'Nyabihu', 'Western'),
(27, 'Nyamasheke', 'Western'),
(28, 'Rubavu', 'Western'),
(29, 'Rusizi', 'Western'),
(30, 'Rutsiro', 'Western');

-- --------------------------------------------------------

--
-- Table structure for table `own`
--

CREATE TABLE `own` (
  `id` int(10) NOT NULL,
  `code` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `own`
--

INSERT INTO `own` (`id`, `code`) VALUES
(1, 2024123);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(255) NOT NULL,
  `roleName` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL,
  `dateCreated` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `roleName`, `school`, `dateCreated`) VALUES
(1, 'chef', 'ETS', '2024-11-23'),
(2, 'head', 'SAINT JOSEPH NYANZA', '2025-03-31');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `schoolName` varchar(50) NOT NULL,
  `district` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `schoolName`, `district`, `province`, `code`, `type`, `date`) VALUES
(8, 'ETS', 'Gisagara', 'Southern', '9002', 'TVET', '2024-11-16'),
(11, 'GS Rilima', 'Bugesera', 'Eastern', '17001', 'REB', '2025-03-24'),
(13, 'SAINT JOSEPH NYANZA', 'Nyanza', 'Southern', '14001', 'TVET', '2025-03-31');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `type` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`Id`, `firstName`, `lastName`, `username`, `password`, `type`, `school`) VALUES
(1, 'Dod', 'Ets', 'dodets@mail.com', '123', 'displine', 'ETS'),
(2, 'Dos', 'Ets', 'dos2024@ets', '1234', 'study', 'ETS'),
(3, 'munda', 'kalisa', 'kalisa rilima', '123', 'displine', 'GS Rilima'),
(4, 'nkusi', 'inock', 'nkusi', '123', 'study', 'GS Rilima'),
(5, 'Default', 'DOD', 'dod.saintjosephnyanza', '9e38b060', 'displine', 'SAINT JOSEPH NYANZA'),
(6, 'Default', 'DOS', 'dos.saintjosephnyanza', 'a2b2125b', 'study', 'SAINT JOSEPH NYANZA');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  `lessonId` int(10) NOT NULL,
  `studentId` int(10) NOT NULL,
  `classId` int(10) NOT NULL,
  `sessionTermId` int(10) NOT NULL,
  `attendanceDate` date NOT NULL,
  `hour` varchar(20) NOT NULL,
  `status` enum('Present','Absent') NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`Id`),
  KEY `studentId` (`studentId`),
  KEY `classId` (`classId`),
  KEY `sessionTermId` (`sessionTermId`),
  CONSTRAINT `tblattendance_ibfk_1` FOREIGN KEY (`studentId`) REFERENCES `tblstudents` (`Id`),
  CONSTRAINT `tblattendance_ibfk_2` FOREIGN KEY (`classId`) REFERENCES `tblclass` (`Id`),
  CONSTRAINT `tblattendance_ibfk_3` FOREIGN KEY (`sessionTermId`) REFERENCES `tblsessionterm` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`Id`, `lessonId`, `studentId`, `classId`, `sessionTermId`, `attendanceDate`, `hour`, `status`, `createdAt`, `updatedAt`) VALUES
(69, 11, 73, 12, 9, '2025-03-23', 'Hour 1', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(70, 11, 74, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(71, 11, 75, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(72, 11, 76, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(73, 11, 77, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(74, 11, 78, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(75, 11, 79, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(76, 11, 80, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(77, 11, 81, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(78, 11, 82, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(79, 11, 83, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(80, 11, 84, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(81, 11, 85, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(82, 11, 86, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(83, 11, 87, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(84, 11, 88, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(85, 11, 89, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(86, 11, 90, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(87, 11, 91, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(88, 11, 92, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(89, 11, 93, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(90, 11, 94, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(91, 11, 95, 12, 9, '2025-03-23', 'Hour 1', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(92, 11, 96, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(93, 11, 97, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(94, 11, 98, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(95, 11, 99, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(96, 11, 108, 12, 9, '2025-03-23', 'Hour 1', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(97, 11, 100, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(98, 11, 101, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(99, 11, 102, 12, 9, '2025-03-23', 'Hour 1', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(100, 11, 103, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(101, 11, 104, 12, 9, '2025-03-23', 'Hour 1', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(102, 11, 105, 12, 9, '2025-03-23', 'Hour 1', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(103, 3, 73, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(104, 3, 74, 12, 9, '2025-03-23', 'Hour 3', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(105, 3, 75, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(106, 3, 76, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(107, 3, 77, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(108, 3, 78, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(109, 3, 79, 12, 9, '2025-03-23', 'Hour 3', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(110, 3, 80, 12, 9, '2025-03-23', 'Hour 3', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(111, 3, 81, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(112, 3, 82, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(113, 3, 83, 12, 9, '2025-03-23', 'Hour 3', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(114, 3, 84, 12, 9, '2025-03-23', 'Hour 3', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(115, 3, 85, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(116, 3, 86, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(117, 3, 87, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(118, 3, 88, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(119, 3, 89, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(120, 3, 90, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(121, 3, 91, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(122, 3, 92, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(123, 3, 93, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(124, 3, 94, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(125, 3, 95, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(126, 3, 96, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(127, 3, 97, 12, 9, '2025-03-23', 'Hour 3', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(128, 3, 98, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(129, 3, 99, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(130, 3, 108, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(131, 3, 100, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(132, 3, 101, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(133, 3, 102, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(134, 3, 103, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(135, 3, 104, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(136, 3, 105, 12, 9, '2025-03-23', 'Hour 3', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(137, 4, 73, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(138, 4, 74, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(139, 4, 75, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(140, 4, 76, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(141, 4, 77, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(142, 4, 78, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(143, 4, 79, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(144, 4, 80, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(145, 4, 81, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(146, 4, 82, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(147, 4, 83, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(148, 4, 84, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(149, 4, 85, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(150, 4, 86, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(151, 4, 87, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(152, 4, 88, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(153, 4, 89, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(154, 4, 90, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(155, 4, 91, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(156, 4, 92, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(157, 4, 93, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(158, 4, 94, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(159, 4, 95, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(160, 4, 96, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(161, 4, 97, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(162, 4, 98, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(163, 4, 99, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(164, 4, 108, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(165, 4, 100, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(166, 4, 101, 12, 9, '2025-03-23', 'Hour 10', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(167, 4, 102, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(168, 4, 103, 12, 9, '2025-03-23', 'Hour 10', 'Present', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(169, 4, 104, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(170, 4, 105, 12, 9, '2025-03-23', 'Hour 10', 'Absent', '2025-03-23 17:23:23', '2025-03-23 17:23:23'),
(171, 13, 116, 28, 11, '2025-03-24', 'Hour 1', 'Present', '2025-03-24 17:31:00', '2025-03-24 17:31:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblclass`
--

CREATE TABLE `tblclass` (
  `Id` int(10) NOT NULL,
  `level` varchar(255) NOT NULL,
  `className` varchar(255) NOT NULL,
  `totalStudents` int(255) NOT NULL,
  `boys` int(255) NOT NULL,
  `girls` int(255) NOT NULL,
  `school` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclass`
--

INSERT INTO `tblclass` (`Id`, `level`, `className`, `totalStudents`, `boys`, `girls`, `school`) VALUES
(8, '', 'L4 wood technology', 34, 22, 12, ''),
(9, '', 'L4 software development A', 30, 21, 9, ''),
(10, '', 'L4 BUILDING CONSTRUCTION ', 20, 9, 11, ''),
(12, '', 'L4 SOFTWARE DEVELOPMENT B', 34, 22, 12, 'ETS'),
(27, 'L5', 'L5 SOFTWARE DEVELOPMENT', 30, 20, 10, 'ETS'),
(28, 'S4', 'S4 ACCOUNTING', 20, 10, 10, 'GS Rilima'),
(31, 'L3', 'L3 computer system and architecture', 33, 23, 10, 'SAINT JOSEPH NYANZA'),
(32, 'L4', 'L4 software development A', 30, 20, 10, 'SAINT JOSEPH NYANZA');

-- --------------------------------------------------------

--
-- Table structure for table `tblclassarms`
--

CREATE TABLE `tblclassarms` (
  `Id` int(10) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmName` varchar(255) NOT NULL,
  `isAssigned` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclassarms`
--

INSERT INTO `tblclassarms` (`Id`, `classId`, `classArmName`, `isAssigned`) VALUES
(15, '13', 'ACC', '0'),
(14, '12', 'L4SODB', '0'),
(13, '11', 'L4 SOD B', '0'),
(12, '10', 'L4BDC', '0'),
(10, '8', 'L4WOT', '0'),
(11, '9', 'L4SODA', '0'),
(18, '14', 'L3SWDA', '0'),
(19, '28', 'S4ACC', '0'),
(20, '31', 'L3 CSA', '0'),
(21, '32', 'L4 SODA', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tblclassteacher`
--

CREATE TABLE `tblclassteacher` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `school` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclassteacher`
--

INSERT INTO `tblclassteacher` (`Id`, `firstName`, `lastName`, `username`, `password`, `school`) VALUES
(7, '', '', 'Anima@123', '123', 'ETS');

-- --------------------------------------------------------

--
-- Table structure for table `tblsessionterm`
--

CREATE TABLE `tblsessionterm` (
  `Id` int(10) NOT NULL,
  `sessionName` varchar(50) NOT NULL,
  `termId` varchar(50) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `school` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblsessionterm`
--

INSERT INTO `tblsessionterm` (`Id`, `sessionName`, `termId`, `start`, `end`, `school`) VALUES
(9, '2024/2025', '2', '2025-01-07', '2025-04-03', 'ETS'),
(10, '2024/2025', '1', '2024-10-24', '2025-03-24', ''),
(11, '2024/2025', '2', '2025-01-07', '2025-04-04', 'GS Rilima');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `Id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `admissionNumber` varchar(255) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`Id`, `name`, `gender`, `admissionNumber`, `classId`, `classArmId`, `dateCreated`, `image_path`) VALUES
(73, 'AHIMBAZWE Angelo', 'MALE', 'L4SODB01', '12', '14', '2024-11-22', NULL),
(74, 'BENIMANA DIANE', 'FEMALE', 'L4SODB02', '12', '14', '2024-11-22', NULL),
(75, 'BIRANEJEJE NIYIKIZA LOIS', 'FEMALE', 'L4SODB03', '12', '14', '2024-11-22', NULL),
(76, 'DUSABIKIZA KING', 'MALE', 'L4SODB04', '12', '14', '2024-11-22', NULL),
(77, 'GASHEMA Yvan', 'MALE', 'L4SODB05', '12', '14', '2024-11-22', NULL),
(78, 'GATAMBIYE Jairus', 'MALE', 'L4SODB06', '12', '14', '2024-11-22', NULL),
(79, 'GISUBIZO SHIMA AIME YANNICK', 'MALE', 'L4SODB07', '12', '14', '2024-11-22', NULL),
(80, 'IMANISHIMWE AIME EMMANUEL', 'MALE', 'L4SODB08', '12', '14', '2024-11-22', NULL),
(81, 'INEMA Arnaud', 'MALE', 'L4SODB09', '12', '14', '2024-11-22', NULL),
(82, 'INEZA SIMBI BELLA', 'FEMALE', 'L4SODB10', '12', '14', '2024-11-22', NULL),
(83, 'IRADUKUNDA RAFIKI Kellen', 'FEMALE', 'L4SODB11', '12', '14', '2024-11-22', NULL),
(84, 'IRIHO SABIH', 'MALE', 'L4SODB12', '12', '14', '2024-11-22', NULL),
(85, 'IRIZA ELLA LUXINE', 'FEMALE', 'L4SODB13', '12', '14', '2024-11-22', NULL),
(86, 'ISHIMWE GHISLAIN', 'MALE', 'L4SODB14', '12', '14', '2024-11-22', NULL),
(87, 'ISHIMWE MUGISHA HUGUE', 'MALE', 'L4SODB15', '12', '14', '2024-11-22', NULL),
(88, 'ITWITAYEHO MUGISHA Jean De Dieu', 'MALE', 'L4SODB16', '12', '14', '2024-11-22', NULL),
(89, 'KABUYE GABY PRINCE', 'MALE', 'L4SODB17', '12', '14', '2024-11-22', NULL),
(90, 'MASENGESHO ISAAC', 'MALE', 'L4SODB18', '12', '14', '2024-11-22', NULL),
(91, 'MIZERO DENYSE', 'FEMALE', 'L4SODB19', '12', '14', '2024-11-22', NULL),
(92, 'MUNYANEZA Robeni', 'MALE', 'L4SODB20', '12', '14', '2024-11-22', NULL),
(93, 'MURANGWA ABDUL KARIM', 'MALE', 'L4SODB21', '12', '14', '2024-11-22', NULL),
(94, 'MWEBAZE GOD', 'MALE', 'L4SODB22', '12', '14', '2024-11-22', NULL),
(95, 'NDAYISABA MUGARURA Elysé', 'MALE', 'L4SODB23', '12', '14', '2024-11-22', NULL),
(96, 'NIYITANGA LEONIDAS', 'MALE', 'L4SODB24', '12', '14', '2024-11-22', NULL),
(97, 'NIYONSENGA Dieume', 'MALE', 'L4SODB25', '12', '14', '2024-11-22', NULL),
(98, 'NIZEYIMANA PLACIDE', 'MALE', 'L4SODB26', '12', '14', '2024-11-22', NULL),
(99, 'NKUNDIMFURA YVAN', 'MALE', 'L4SODB27', '12', '14', '2024-11-22', NULL),
(100, 'NZEYIMANA  GISUBIZO  MERVEILLE', 'FEMALE', 'L4SODB28', '12', '14', '2024-11-22', NULL),
(101, 'RUGAMBWA  Aliane', 'FEMALE', 'L4SODB29', '12', '14', '2024-11-22', NULL),
(102, 'SANGWA INEZA NIELLA', 'FEMALE', 'L4SODB30', '12', '14', '2024-11-22', NULL),
(103, 'TUYIZERE Boncoeur', 'MALE', 'L4SODB31', '12', '14', '2024-11-22', NULL),
(104, 'UWANJYE  ISHIMWE Bernisse', 'FEMALE', 'L4SODB32', '12', '14', '2024-11-22', NULL),
(105, 'UWIJURU Neema Kerenny', 'FEMALE', 'L4SODB33', '12', '14', '2024-11-22', NULL),
(108, 'noella', 'FEMALE', 'L4SODB34', '12', '14', '2024-11-22', NULL),
(116, 'KWIZERA Deb', 'MALE', '17001S40032025', '28', '19', '2025-03-24', NULL),
(117, 'ANGE Noella', 'FEMALE', '17001S40022025', '28', '19', '2025-03-24', NULL),
(118, 'GISUBIZO SHIMA Aime Yannick', 'MALE', '14001L40012025', '32', '21', '2025-04-05', '118_1743872521.JPG');

-- --------------------------------------------------------

--
-- Table structure for table `tblteachers`
--

CREATE TABLE `tblteachers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNo` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `roleId` int(11) NOT NULL,
  `school` varchar(255) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblteachers`
--

INSERT INTO `tblteachers` (`id`, `name`, `gender`, `username`, `password`, `phoneNo`, `address`, `roleId`, `school`, `created_at`) VALUES
(3, 'KUBWIMANA Fred', 'Male', 'fred', '123', '0788662189', 'fred@gmail.com', 1, 'ETS', '2025-03-04'),
(6, 'NIZEYIMANA Placide', 'Male', 'placide', '123', '0788662189', 'bugesera', 1, 'ETS', '2025-03-04'),
(7, 'MIKA Sano', 'Male', 'sano', '123', '23456789', 'sano@gmail.com', 1, 'GS Rilima', '2025-03-24'),
(9, 'GISUBIZO SHIMA Aime Yannick', 'Male', 'Yannick', '123', '0791285293', 'huye,butare st 21', 2, 'SAINT JOSEPH NYANZA', '2025-03-31');

-- --------------------------------------------------------

--
-- Table structure for table `tblterm`
--

CREATE TABLE `tblterm` (
  `Id` int(10) NOT NULL,
  `termName` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblterm`
--

INSERT INTO `tblterm` (`Id`, `termName`) VALUES
(1, 'First'),
(3, 'third'),
(2, 'second ');

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday') NOT NULL,
  `time_slot` enum('08:00-09:00','09:00-10:00','10:00-11:00','11:00-12:00','13:00-14:00','14:00-15:00','15:00-16:00') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `class_id`, `teacher_id`, `lesson_id`, `day`, `time_slot`, `created_at`) VALUES
(1, 27, 3, 11, 'Monday', '08:00-09:00', '2025-03-04 17:50:27'),
(2, 12, 3, 10, 'Monday', '09:00-10:00', '2025-03-04 17:50:28'),
(3, 12, 3, 10, 'Monday', '10:00-11:00', '2025-03-04 17:50:28'),
(4, 27, 3, 3, 'Monday', '11:00-12:00', '2025-03-04 17:50:28'),
(5, 12, 3, 3, 'Monday', '13:00-14:00', '2025-03-04 17:50:28'),
(6, 27, 3, 11, 'Monday', '14:00-15:00', '2025-03-04 17:50:28'),
(7, 12, 3, 10, 'Monday', '15:00-16:00', '2025-03-04 17:50:28'),
(8, 12, 3, 3, 'Tuesday', '08:00-09:00', '2025-03-04 17:50:28'),
(9, 27, 3, 3, 'Tuesday', '09:00-10:00', '2025-03-04 17:50:28'),
(10, 27, 3, 11, 'Tuesday', '10:00-11:00', '2025-03-04 17:50:28'),
(11, 27, 3, 11, 'Tuesday', '11:00-12:00', '2025-03-04 17:50:28'),
(12, 27, 3, 11, 'Tuesday', '13:00-14:00', '2025-03-04 17:50:28'),
(13, 12, 3, 10, 'Tuesday', '14:00-15:00', '2025-03-04 17:50:28'),
(14, 12, 3, 10, 'Tuesday', '15:00-16:00', '2025-03-04 17:50:28'),
(15, 12, 3, 3, 'Wednesday', '08:00-09:00', '2025-03-04 17:50:28'),
(16, 27, 3, 3, 'Wednesday', '09:00-10:00', '2025-03-04 17:50:28'),
(17, 12, 3, 10, 'Wednesday', '10:00-11:00', '2025-03-04 17:50:28'),
(18, 27, 3, 3, 'Wednesday', '11:00-12:00', '2025-03-04 17:50:28'),
(19, 12, 3, 10, 'Wednesday', '13:00-14:00', '2025-03-04 17:50:28'),
(20, 27, 3, 3, 'Wednesday', '14:00-15:00', '2025-03-04 17:50:29'),
(21, 12, 3, 10, 'Wednesday', '15:00-16:00', '2025-03-04 17:50:29'),
(22, 27, 3, 11, 'Thursday', '08:00-09:00', '2025-03-04 17:50:29'),
(23, 12, 3, 3, 'Thursday', '09:00-10:00', '2025-03-04 17:50:29'),
(24, 12, 3, 10, 'Thursday', '10:00-11:00', '2025-03-04 17:50:29'),
(25, 12, 3, 3, 'Thursday', '11:00-12:00', '2025-03-04 17:50:29'),
(26, 12, 3, 10, 'Thursday', '13:00-14:00', '2025-03-04 17:50:29'),
(27, 27, 3, 3, 'Thursday', '14:00-15:00', '2025-03-04 17:50:29'),
(28, 12, 3, 3, 'Thursday', '15:00-16:00', '2025-03-04 17:50:29'),
(29, 27, 3, 11, 'Friday', '08:00-09:00', '2025-03-04 17:50:29'),
(30, 27, 3, 11, 'Friday', '09:00-10:00', '2025-03-04 17:50:29'),
(31, 27, 3, 3, 'Friday', '10:00-11:00', '2025-03-04 17:50:29'),
(32, 27, 3, 3, 'Friday', '11:00-12:00', '2025-03-04 17:50:29'),
(33, 27, 3, 3, 'Friday', '13:00-14:00', '2025-03-04 17:50:29'),
(34, 27, 3, 3, 'Friday', '14:00-15:00', '2025-03-04 17:50:29'),
(35, 12, 3, 10, 'Friday', '15:00-16:00', '2025-03-04 17:50:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absents`
--
ALTER TABLE `absents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `atdetails`
--
ALTER TABLE `atdetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_lessons`
--
ALTER TABLE `class_lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `studentId` (`studentId`),
  ADD KEY `classId` (`classId`),
  ADD KEY `sessionTermId` (`sessionTermId`);

--
-- Indexes for table `tblclass`
--
ALTER TABLE `tblclass`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclassteacher`
--
ALTER TABLE `tblclassteacher`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblteachers`
--
ALTER TABLE `tblteachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblterm`
--
ALTER TABLE `tblterm`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absents`
--
ALTER TABLE `absents`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `atdetails`
--
ALTER TABLE `atdetails`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `class_lessons`
--
ALTER TABLE `class_lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `tblclass`
--
ALTER TABLE `tblclass`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tblclassteacher`
--
ALTER TABLE `tblclassteacher`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `tblteachers`
--
ALTER TABLE `tblteachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblterm`
--
ALTER TABLE `tblterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_lessons`
--
ALTER TABLE `class_lessons`
  ADD CONSTRAINT `class_lessons_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_lessons_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `tblclass` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `tblteachers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD CONSTRAINT `tblattendance_ibfk_1` FOREIGN KEY (`studentId`) REFERENCES `tblstudents` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblattendance_ibfk_2` FOREIGN KEY (`classId`) REFERENCES `tblclass` (`Id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblattendance_ibfk_3` FOREIGN KEY (`sessionTermId`) REFERENCES `tblsessionterm` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `timetable`
--
ALTER TABLE `timetable`

CREATE EVENT mark_absent_students
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_DATE + INTERVAL 1 DAY
DO
BEGIN
    INSERT INTO tblattendance (studentId, classId, sessionTermId, attendanceDate, hour, status)
    SELECT s.Id, s.classId, st.Id, CURRENT_DATE, 'Hour 1', 'Absent'
    FROM tblstudents s
    CROSS JOIN tblsessionterm st
    WHERE st.isActive = 1
    AND NOT EXISTS (
        SELECT 1 FROM tblattendance a
        WHERE a.studentId = s.Id
        AND a.attendanceDate = CURRENT_DATE
        AND a.sessionTermId = st.Id
    );
END;

CREATE VIEW attendance_report AS
SELECT
    s.firstName,
    s.lastName,
    s.admissionNumber,
    c.className,
    a.attendanceDate,
    a.status,
    st.termName,
    st.sessionName
FROM tblattendance a
JOIN tblstudents s ON a.studentId = s.Id
JOIN tblclass c ON a.classId = c.Id
JOIN tblsessionterm st ON a.sessionTermId = st.Id;
