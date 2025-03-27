-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2025 at 04:27 PM
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
(13, 7, 'english', 'fvvvvfvf', 'GS Rilima', '2025-03-24 17:31:00', '2025-03-24 17:31:00');

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
(2, 'LIVEL FIVE', 'L5', 'ETS'),
(3, 'SINIOR FOUR', 'S4', 'GS Rilima');

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
(1, 'chef', 'ETS', '2024-11-23');

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
(11, 'GS Rilima', 'Bugesera', 'Eastern', '17001', 'REB', '2025-03-24');

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
(4, 'nkusi', 'inock', 'nkusi', '123', 'study', 'GS Rilima');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `Id` int(10) NOT NULL,
  `lessonId` int(10) NOT NULL,
  `studentId` int(10) NOT NULL,
  `attendanceDate` date NOT NULL,
  `hour` varchar(20) NOT NULL,
  `status` enum('Present','Absent') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`Id`, `lessonId`, `studentId`, `attendanceDate`, `hour`, `status`) VALUES
(69, 11, 73, '2025-03-23', 'Hour 1', 'Absent'),
(70, 11, 74, '2025-03-23', 'Hour 1', 'Present'),
(71, 11, 75, '2025-03-23', 'Hour 1', 'Present'),
(72, 11, 76, '2025-03-23', 'Hour 1', 'Present'),
(73, 11, 77, '2025-03-23', 'Hour 1', 'Present'),
(74, 11, 78, '2025-03-23', 'Hour 1', 'Present'),
(75, 11, 79, '2025-03-23', 'Hour 1', 'Present'),
(76, 11, 80, '2025-03-23', 'Hour 1', 'Present'),
(77, 11, 81, '2025-03-23', 'Hour 1', 'Present'),
(78, 11, 82, '2025-03-23', 'Hour 1', 'Present'),
(79, 11, 83, '2025-03-23', 'Hour 1', 'Present'),
(80, 11, 84, '2025-03-23', 'Hour 1', 'Present'),
(81, 11, 85, '2025-03-23', 'Hour 1', 'Present'),
(82, 11, 86, '2025-03-23', 'Hour 1', 'Present'),
(83, 11, 87, '2025-03-23', 'Hour 1', 'Present'),
(84, 11, 88, '2025-03-23', 'Hour 1', 'Present'),
(85, 11, 89, '2025-03-23', 'Hour 1', 'Present'),
(86, 11, 90, '2025-03-23', 'Hour 1', 'Present'),
(87, 11, 91, '2025-03-23', 'Hour 1', 'Present'),
(88, 11, 92, '2025-03-23', 'Hour 1', 'Present'),
(89, 11, 93, '2025-03-23', 'Hour 1', 'Present'),
(90, 11, 94, '2025-03-23', 'Hour 1', 'Present'),
(91, 11, 95, '2025-03-23', 'Hour 1', 'Absent'),
(92, 11, 96, '2025-03-23', 'Hour 1', 'Present'),
(93, 11, 97, '2025-03-23', 'Hour 1', 'Present'),
(94, 11, 98, '2025-03-23', 'Hour 1', 'Present'),
(95, 11, 99, '2025-03-23', 'Hour 1', 'Present'),
(96, 11, 108, '2025-03-23', 'Hour 1', 'Absent'),
(97, 11, 100, '2025-03-23', 'Hour 1', 'Present'),
(98, 11, 101, '2025-03-23', 'Hour 1', 'Present'),
(99, 11, 102, '2025-03-23', 'Hour 1', 'Absent'),
(100, 11, 103, '2025-03-23', 'Hour 1', 'Present'),
(101, 11, 104, '2025-03-23', 'Hour 1', 'Absent'),
(102, 11, 105, '2025-03-23', 'Hour 1', 'Present'),
(103, 3, 73, '2025-03-23', 'Hour 3', 'Present'),
(104, 3, 74, '2025-03-23', 'Hour 3', 'Absent'),
(105, 3, 75, '2025-03-23', 'Hour 3', 'Present'),
(106, 3, 76, '2025-03-23', 'Hour 3', 'Present'),
(107, 3, 77, '2025-03-23', 'Hour 3', 'Present'),
(108, 3, 78, '2025-03-23', 'Hour 3', 'Present'),
(109, 3, 79, '2025-03-23', 'Hour 3', 'Absent'),
(110, 3, 80, '2025-03-23', 'Hour 3', 'Absent'),
(111, 3, 81, '2025-03-23', 'Hour 3', 'Present'),
(112, 3, 82, '2025-03-23', 'Hour 3', 'Present'),
(113, 3, 83, '2025-03-23', 'Hour 3', 'Absent'),
(114, 3, 84, '2025-03-23', 'Hour 3', 'Absent'),
(115, 3, 85, '2025-03-23', 'Hour 3', 'Present'),
(116, 3, 86, '2025-03-23', 'Hour 3', 'Present'),
(117, 3, 87, '2025-03-23', 'Hour 3', 'Present'),
(118, 3, 88, '2025-03-23', 'Hour 3', 'Present'),
(119, 3, 89, '2025-03-23', 'Hour 3', 'Present'),
(120, 3, 90, '2025-03-23', 'Hour 3', 'Present'),
(121, 3, 91, '2025-03-23', 'Hour 3', 'Present'),
(122, 3, 92, '2025-03-23', 'Hour 3', 'Present'),
(123, 3, 93, '2025-03-23', 'Hour 3', 'Present'),
(124, 3, 94, '2025-03-23', 'Hour 3', 'Present'),
(125, 3, 95, '2025-03-23', 'Hour 3', 'Present'),
(126, 3, 96, '2025-03-23', 'Hour 3', 'Present'),
(127, 3, 97, '2025-03-23', 'Hour 3', 'Absent'),
(128, 3, 98, '2025-03-23', 'Hour 3', 'Present'),
(129, 3, 99, '2025-03-23', 'Hour 3', 'Present'),
(130, 3, 108, '2025-03-23', 'Hour 3', 'Present'),
(131, 3, 100, '2025-03-23', 'Hour 3', 'Present'),
(132, 3, 101, '2025-03-23', 'Hour 3', 'Present'),
(133, 3, 102, '2025-03-23', 'Hour 3', 'Present'),
(134, 3, 103, '2025-03-23', 'Hour 3', 'Present'),
(135, 3, 104, '2025-03-23', 'Hour 3', 'Present'),
(136, 3, 105, '2025-03-23', 'Hour 3', 'Present'),
(137, 4, 73, '2025-03-23', 'Hour 10', 'Absent'),
(138, 4, 74, '2025-03-23', 'Hour 10', 'Absent'),
(139, 4, 75, '2025-03-23', 'Hour 10', 'Absent'),
(140, 4, 76, '2025-03-23', 'Hour 10', 'Absent'),
(141, 4, 77, '2025-03-23', 'Hour 10', 'Absent'),
(142, 4, 78, '2025-03-23', 'Hour 10', 'Absent'),
(143, 4, 79, '2025-03-23', 'Hour 10', 'Absent'),
(144, 4, 80, '2025-03-23', 'Hour 10', 'Absent'),
(145, 4, 81, '2025-03-23', 'Hour 10', 'Absent'),
(146, 4, 82, '2025-03-23', 'Hour 10', 'Absent'),
(147, 4, 83, '2025-03-23', 'Hour 10', 'Absent'),
(148, 4, 84, '2025-03-23', 'Hour 10', 'Absent'),
(149, 4, 85, '2025-03-23', 'Hour 10', 'Absent'),
(150, 4, 86, '2025-03-23', 'Hour 10', 'Absent'),
(151, 4, 87, '2025-03-23', 'Hour 10', 'Absent'),
(152, 4, 88, '2025-03-23', 'Hour 10', 'Absent'),
(153, 4, 89, '2025-03-23', 'Hour 10', 'Absent'),
(154, 4, 90, '2025-03-23', 'Hour 10', 'Absent'),
(155, 4, 91, '2025-03-23', 'Hour 10', 'Absent'),
(156, 4, 92, '2025-03-23', 'Hour 10', 'Absent'),
(157, 4, 93, '2025-03-23', 'Hour 10', 'Absent'),
(158, 4, 94, '2025-03-23', 'Hour 10', 'Absent'),
(159, 4, 95, '2025-03-23', 'Hour 10', 'Absent'),
(160, 4, 96, '2025-03-23', 'Hour 10', 'Absent'),
(161, 4, 97, '2025-03-23', 'Hour 10', 'Absent'),
(162, 4, 98, '2025-03-23', 'Hour 10', 'Absent'),
(163, 4, 99, '2025-03-23', 'Hour 10', 'Absent'),
(164, 4, 108, '2025-03-23', 'Hour 10', 'Absent'),
(165, 4, 100, '2025-03-23', 'Hour 10', 'Absent'),
(166, 4, 101, '2025-03-23', 'Hour 10', 'Present'),
(167, 4, 102, '2025-03-23', 'Hour 10', 'Absent'),
(168, 4, 103, '2025-03-23', 'Hour 10', 'Present'),
(169, 4, 104, '2025-03-23', 'Hour 10', 'Absent'),
(170, 4, 105, '2025-03-23', 'Hour 10', 'Absent'),
(171, 13, 116, '2025-03-24', 'Hour 1', 'Present');

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
(28, 'S4', 'S4 ACCOUNTING', 20, 10, 10, 'GS Rilima');

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
(19, '28', 'S4ACC', '0');

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
  `dateCreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`Id`, `name`, `gender`, `admissionNumber`, `classId`, `classArmId`, `dateCreated`) VALUES
(73, 'AHIMBAZWE Angelo', 'MALE', 'L4SODB01', '12', '14', '2024-11-22'),
(74, 'BENIMANA DIANE', 'FEMALE', 'L4SODB02', '12', '14', '2024-11-22'),
(75, 'BIRANEJEJE NIYIKIZA LOIS', 'FEMALE', 'L4SODB03', '12', '14', '2024-11-22'),
(76, 'DUSABIKIZA KING', 'MALE', 'L4SODB04', '12', '14', '2024-11-22'),
(77, 'GASHEMA Yvan', 'MALE', 'L4SODB05', '12', '14', '2024-11-22'),
(78, 'GATAMBIYE Jairus', 'MALE', 'L4SODB06', '12', '14', '2024-11-22'),
(79, 'GISUBIZO SHIMA AIME YANNICK', 'MALE', 'L4SODB07', '12', '14', '2024-11-22'),
(80, 'IMANISHIMWE AIME EMMANUEL', 'MALE', 'L4SODB08', '12', '14', '2024-11-22'),
(81, 'INEMA Arnaud', 'MALE', 'L4SODB09', '12', '14', '2024-11-22'),
(82, 'INEZA SIMBI BELLA', 'FEMALE', 'L4SODB10', '12', '14', '2024-11-22'),
(83, 'IRADUKUNDA RAFIKI Kellen', 'FEMALE', 'L4SODB11', '12', '14', '2024-11-22'),
(84, 'IRIHO SABIH', 'MALE', 'L4SODB12', '12', '14', '2024-11-22'),
(85, 'IRIZA ELLA LUXINE', 'FEMALE', 'L4SODB13', '12', '14', '2024-11-22'),
(86, 'ISHIMWE GHISLAIN', 'MALE', 'L4SODB14', '12', '14', '2024-11-22'),
(87, 'ISHIMWE MUGISHA HUGUE', 'MALE', 'L4SODB15', '12', '14', '2024-11-22'),
(88, 'ITWITAYEHO MUGISHA Jean De Dieu', 'MALE', 'L4SODB16', '12', '14', '2024-11-22'),
(89, 'KABUYE GABY PRINCE', 'MALE', 'L4SODB17', '12', '14', '2024-11-22'),
(90, 'MASENGESHO ISAAC', 'MALE', 'L4SODB18', '12', '14', '2024-11-22'),
(91, 'MIZERO DENYSE', 'FEMALE', 'L4SODB19', '12', '14', '2024-11-22'),
(92, 'MUNYANEZA Robeni', 'MALE', 'L4SODB20', '12', '14', '2024-11-22'),
(93, 'MURANGWA ABDUL KARIM', 'MALE', 'L4SODB21', '12', '14', '2024-11-22'),
(94, 'MWEBAZE GOD', 'MALE', 'L4SODB22', '12', '14', '2024-11-22'),
(95, 'NDAYISABA MUGARURA Elysé', 'MALE', 'L4SODB23', '12', '14', '2024-11-22'),
(96, 'NIYITANGA LEONIDAS', 'MALE', 'L4SODB24', '12', '14', '2024-11-22'),
(97, 'NIYONSENGA Dieume', 'MALE', 'L4SODB25', '12', '14', '2024-11-22'),
(98, 'NIZEYIMANA PLACIDE', 'MALE', 'L4SODB26', '12', '14', '2024-11-22'),
(99, 'NKUNDIMFURA YVAN', 'MALE', 'L4SODB27', '12', '14', '2024-11-22'),
(100, 'NZEYIMANA  GISUBIZO  MERVEILLE', 'FEMALE', 'L4SODB28', '12', '14', '2024-11-22'),
(101, 'RUGAMBWA  Aliane', 'FEMALE', 'L4SODB29', '12', '14', '2024-11-22'),
(102, 'SANGWA INEZA NIELLA', 'FEMALE', 'L4SODB30', '12', '14', '2024-11-22'),
(103, 'TUYIZERE Boncoeur', 'MALE', 'L4SODB31', '12', '14', '2024-11-22'),
(104, 'UWANJYE  ISHIMWE Bernisse', 'FEMALE', 'L4SODB32', '12', '14', '2024-11-22'),
(105, 'UWIJURU Neema Kerenny', 'FEMALE', 'L4SODB33', '12', '14', '2024-11-22'),
(108, 'noella', 'FEMALE', 'L4SODB34', '12', '14', '2024-11-22'),
(116, 'KWIZERA Deb', 'MALE', '17001S40032025', '28', '19', '2025-03-24'),
(117, 'ANGE Noella', 'FEMALE', '17001S40022025', '28', '19', '2025-03-24');

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
(7, 'MIKA Sano', 'Male', 'sano', '123', '23456789', 'sano@gmail.com', 1, 'GS Rilima', '2025-03-24');

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
  ADD KEY `lessonId` (`lessonId`),
  ADD KEY `studentId` (`studentId`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `tblclass`
--
ALTER TABLE `tblclass`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `tblteachers`
--
ALTER TABLE `tblteachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `tblattendance_ibfk_1` FOREIGN KEY (`lessonId`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblattendance_ibfk_2` FOREIGN KEY (`studentId`) REFERENCES `tblstudents` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `timetable`
--
ALTER TABLE `timetable`
  ADD CONSTRAINT `timetable_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `tblclass` (`Id`),
  ADD CONSTRAINT `timetable_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `tblteachers` (`id`),
  ADD CONSTRAINT `timetable_ibfk_3` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
