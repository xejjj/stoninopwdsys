-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2026 at 07:06 AM
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
-- Database: `stoninopwdsysdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admincreds`
--

CREATE TABLE `admincreds` (
  `ID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','encoder') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admincreds`
--

INSERT INTO `admincreds` (`ID`, `username`, `password`, `role`) VALUES
(1, 'test', 'test', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `ID` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `civil_status` enum('Single','Married','Widowed','Separated') NOT NULL,
  `birthdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `birthplace` varchar(255) NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_num` varchar(255) NOT NULL,
  `emergency_cont` varchar(255) NOT NULL,
  `emergency_cont_num` varchar(255) NOT NULL,
  `emergency_cont_rel` varchar(255) NOT NULL,
  `socials` varchar(255) NOT NULL,
  `disablity_type` enum('Cognitive','Visual','Motor','Auditory','Speech','Psychosocial') NOT NULL,
  `resident_type` enum('PWD','CWD') NOT NULL,
  `guardian_name` varchar(255) NOT NULL,
  `guardian_cont_num` varchar(255) NOT NULL,
  `guardian_rel` varchar(255) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `mother_name` varchar(255) NOT NULL,
  `spouse_name` varchar(255) NOT NULL,
  `pwdid_num` varchar(255) NOT NULL,
  `control_num` varchar(255) NOT NULL,
  `idissue_date` date NOT NULL,
  `idexpiration_date` date NOT NULL,
  `profile` varchar(255) NOT NULL,
  `status` enum('Active','Pending','Expired') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`ID`, `first_name`, `middle_name`, `last_name`, `civil_status`, `birthdate`, `age`, `birthplace`, `sex`, `address`, `contact_num`, `emergency_cont`, `emergency_cont_num`, `emergency_cont_rel`, `socials`, `disablity_type`, `resident_type`, `guardian_name`, `guardian_cont_num`, `guardian_rel`, `father_name`, `mother_name`, `spouse_name`, `pwdid_num`, `control_num`, `idissue_date`, `idexpiration_date`, `profile`, `status`) VALUES
(1, 'Test', 'Test mid', 'test last', 'Single', '2026-05-05', 18, 'gaesgd', 'male', 'gasdgdasgas', '1532512351', '12351235123', '315351253125', 'afsdgasdg', 'adsgsdagas', 'Visual', 'CWD', 'gdasgasdgas', 'gasdgasdgasd', 'dgasgasdgasd', 'gasdgasdgasd', 'gadsgsadgasd', 'gasdgasdgasd', 'gasdgasdgasdgas', 'dgasgasdgasdgdas', '2026-05-13', '2026-05-18', '', 'Pending'),
(2, 'gasdgasd', 'gadsgasg', 'paras', '', '2026-05-02', 13, 'gaesgd', 'male', 'gadsgasdgas', 'gdasgdasgdsa', 'gsdagasgas', 'dgasgasdgasd', 'gdasgdsagasd', 'gdasgasdgasdg', 'Cognitive', 'CWD', 'gasdgdasga', 'dasgdasgasd', 'gdasgsda', 'gdsagdas', 'gdsagasg', 'g', 'g', 'g', '2026-04-08', '2026-05-05', '', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admincreds`
--
ALTER TABLE `admincreds`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `username_unique` (`username`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admincreds`
--
ALTER TABLE `admincreds`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
