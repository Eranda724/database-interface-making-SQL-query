-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2024 at 06:14 AM
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
-- Database: `course_manager`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateLectureHours` ()   BEGIN
    UPDATE course
    SET `Lecture Hours` = CASE
        WHEN Credit = 2 THEN 30
        WHEN Credit = 3 THEN 45
        ELSE `Lecture Hours`
    END;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `Cname` varchar(50) NOT NULL,
  `Credit` int(1) NOT NULL,
  `Code` varchar(6) NOT NULL,
  `Lecture Hours` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`Cname`, `Credit`, `Code`, `Lecture Hours`) VALUES
('Metrology', 3, 'CE1020', 45),
('Applied Electricity', 3, 'EC1020', 45),
('Electronics', 3, 'EC2010', 45),
('Operating System', 3, 'EC6010', 45),
('Embedded system design', 3, 'EC6020', 45),
('Software Engineering', 3, 'EC6030', 45),
('Computer Engineering Research Project 1', 3, 'EC6070', 45),
('Operating System', 3, 'EC6110', 45),
('Human Computer interraction', 2, 'EC9540', 30),
('Data Mining', 2, 'EC9560', 30),
('Mathematics', 3, 'ID1010', 45),
('English', 3, 'ID1020', 45),
('Drawings', 3, 'ID1030', 45),
('Drawings', 3, 'MC1020', 45);

-- --------------------------------------------------------

--
-- Table structure for table `ma`
--

CREATE TABLE `ma` (
  `MAID` varchar(10) NOT NULL,
  `Mname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ma`
--

INSERT INTO `ma` (`MAID`, `Mname`) VALUES
('2021E001', 'supun'),
('2021E075', 'Eranda'),
('2021E076', 'John'),
('2021E119', 'Thilina'),
('2021E120', 'mendis'),
('2021E190', 'Kusal');

-- --------------------------------------------------------

--
-- Table structure for table `manage`
--

CREATE TABLE `manage` (
  `MaID` varchar(10) NOT NULL,
  `Ccode` varchar(6) NOT NULL,
  `Semester` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manage`
--

INSERT INTO `manage` (`MaID`, `Ccode`, `Semester`) VALUES
('2021E001', 'EC1020', 2),
('2021E001', 'EC2010', 2),
('2021E001', 'EC6010', 6),
('2021E001', 'EC6020', 6),
('2021E001', 'EC6030', 6),
('2021E001', 'EC9540', 6),
('2021E001', 'EC9560', 6),
('2021E075', 'EC6020', 6),
('2021E076', 'ID1030', 5),
('2021E119', 'ID1010', 1),
('2021E119', 'MC1020', 1),
('2021E120', 'ID1020', 4),
('2021E190', 'CE1020', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`Code`);

--
-- Indexes for table `ma`
--
ALTER TABLE `ma`
  ADD PRIMARY KEY (`MAID`);

--
-- Indexes for table `manage`
--
ALTER TABLE `manage`
  ADD PRIMARY KEY (`MaID`,`Ccode`),
  ADD KEY `Ccode` (`Ccode`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `manage`
--
ALTER TABLE `manage`
  ADD CONSTRAINT `manage_ibfk_1` FOREIGN KEY (`MaID`) REFERENCES `ma` (`MAID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `manage_ibfk_2` FOREIGN KEY (`Ccode`) REFERENCES `course` (`Code`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
