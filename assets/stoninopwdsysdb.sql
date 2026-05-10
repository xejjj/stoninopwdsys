-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2026 at 05:16 PM
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
  `disablity_type` enum('Cognitive','Visual','Physical','Auditory','Speech','Psychosocial') NOT NULL,
  `disability_remarks` varchar(255) DEFAULT NULL,
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

INSERT INTO `residents` (`ID`, `first_name`, `middle_name`, `last_name`, `civil_status`, `birthdate`, `age`, `birthplace`, `sex`, `address`, `contact_num`, `emergency_cont`, `emergency_cont_num`, `emergency_cont_rel`, `socials`, `disablity_type`, `disability_remarks`, `resident_type`, `guardian_name`, `guardian_cont_num`, `guardian_rel`, `father_name`, `mother_name`, `spouse_name`, `pwdid_num`, `control_num`, `idissue_date`, `idexpiration_date`, `profile`, `status`) VALUES
(4, 'PERLITA', 'R', 'ABELLERA', '', '0000-00-00', 0, '', 'female', '63-C SANTOL ST.', '9472622313', '', '', '', '', 'Physical', NULL, 'PWD', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Pending'),
(5, 'BRENT NYLSEN', 'PABLICO', 'AGUILLERA', '', '0000-00-00', 15, '', 'male', '68 WOMEN\'S CLUB ST.', '09158828474', '', '', '', '', 'Cognitive', NULL, 'CWD', 'AGUILLERA, LYN ROSE P.', '', '', '', '', '', '899-217', '', '0000-00-00', '0000-00-00', '', 'Pending'),
(6, 'ARLENE', 'ALFILER', 'AGUINALDO', '', '0000-00-00', 53, '', 'female', '65 SANTOL ST.', '09215751865', '', '', '', '', '', NULL, 'PWD', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Pending'),
(7, 'ALLAN', 'HILANTAGAAN', 'ALABANZA', '', '0000-00-00', 50, '', 'male', '18 BIAK N BATO ST.', '09457821893', '', '', '', '', 'Visual', NULL, 'PWD', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Pending'),
(8, 'NORMA', 'B', 'ALCUINO', '', '0000-00-00', 0, '88 WOMEN\'S CLUB ST.', '', '', '09291019040', '', '', '', '', '', NULL, 'PWD', 'SANTIAGO JR., CARLOS', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Pending'),
(9, 'IISHA KARMEL', 'ULIT', 'ALDAY', '', '0000-00-00', 19, '', 'female', '15 BIAK NA BATO ST.', '09273242992', '', '', '', '', 'Auditory', NULL, 'PWD', 'ALDAY, ALLANDALE M.', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Pending'),
(10, 'EDGILINE ', 'DENOFRA', 'AQUINO', '', '0000-00-00', 33, '', 'female', '93 WOMEN\'S CLUB ST.', '09171749249', '', '', '', '', 'Physical', NULL, 'PWD', 'AQUINO, GILDA', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Pending'),
(11, 'LIZA', 'ARAGON', 'BACSAL', '', '0000-00-00', 32, '', 'female', '106 UNION CIVICA ST.', '09158935460', '', '', '', '', 'Speech', NULL, 'PWD', 'HOMBREBUENO, DOLORES', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Pending'),
(12, 'LAN\'S', 'TAMAYO', 'BARTOLATA', '', '0000-00-00', 26, '', 'male', '6-8 BAGONG BUHAY ST.', '09555432325', '', '', '', '', 'Psychosocial', NULL, 'PWD', 'BARTOLATA, MARYANNE', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Pending'),
(13, 'HARVEIAME', 'JUMAWAN', 'BARRACA', '', '0000-00-00', 2, '', 'male', '75B CUATRO DE JULIO ST.', '09274220847', '', '', '', '', 'Speech', NULL, 'CWD', 'JUMAWAN, SALVE REGINA', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Pending'),
(14, 'NYMPHA', 'PANIZARES', 'BASCONES', '', '0000-00-00', 0, '', 'female', '43 TOMAS PINPIN ST.', '', '', '', '', '', 'Physical', NULL, '', 'BASCONES JR., GUILLERMO', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Pending'),
(15, 'ADRIAN DEXTER', 'ESTEVES', 'BELLA', '', '0000-00-00', 0, '', 'male', '101 CUATRO DE JULIO ST.', '09074101145', '', '', '', '', 'Auditory', NULL, '', 'BELLA, ANNALY', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Pending');

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
