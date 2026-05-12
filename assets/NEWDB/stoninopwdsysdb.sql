-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2026 at 03:47 PM
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
-- Table structure for table `archive`
--

CREATE TABLE `archive` (
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
  `disablity_type` enum('Cognitive','Visual','Physical','Auditory','Speech','Psychosocial','Others') NOT NULL,
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
  `disablity_type` enum('Cognitive','Visual','Physical','Auditory','Speech','Psychosocial','Others') NOT NULL,
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
(2, 'BRENT NYLSEN', 'PABLICO', 'AGUILLERA', '', '0000-00-00', 15, '', 'male', '68 WOMEN\'S CLUB ST.', '09158828474', '', '', '', '', 'Cognitive', NULL, 'CWD', 'AGUILLERA, LYN ROSE P.', '', '', '', '', '', '899-217', '', '0000-00-00', '0000-00-00', '', 'Active'),
(3, 'ARLENE', 'ALFILER', 'AGUINALDO', '', '0000-00-00', 53, '', 'female', '65 SANTOL ST.', '09215751865', '', '', '', '', '', NULL, 'PWD', '', '', '', '', '', '', '12400000204300', '', '0000-00-00', '0000-00-00', '', 'Active'),
(4, 'ALLAN', 'HILANTAGAAN', 'ALABANZA', '', '0000-00-00', 50, '', 'male', '18 BIAK N BATO ST.', '09457821893', '', '', '', '', 'Visual', NULL, 'PWD', '', '', '', '', '', '', '12400000974777', '', '0000-00-00', '0000-00-00', '', 'Active'),
(5, 'NORMA', 'B', 'ALCUINO', '', '0000-00-00', 0, '', '', '88 WOMEN\'S CLUB ST.', '09291019040', '', '', '', '', '', NULL, 'PWD', 'SANTIAGO JR., CARLOS', '', '', '', '', '', '109-484', '', '0000-00-00', '0000-00-00', '', 'Active'),
(6, 'IISHA KARMEL', 'ULIT', 'ALDAY', '', '0000-00-00', 19, '', 'female', '15 BIAK NA BATO ST.', '09273242992', '', '', '', '', 'Auditory', NULL, 'PWD', 'ALDAY, ALLANDALE M.', '', '', '', '', '', '21400000652846', '', '0000-00-00', '0000-00-00', '', 'Active'),
(7, 'EDGILINE ', 'DENOFRA', 'AQUINO', '', '0000-00-00', 33, '', 'female', '93 WOMEN\'S CLUB ST.', '09171749249', '', '', '', '', 'Physical', NULL, 'PWD', 'AQUINO, GILDA', '', '', '', '', '', '1240001464691', '', '0000-00-00', '0000-00-00', '', 'Active'),
(8, 'LIZA', 'ARAGON', 'BACSAL', '', '0000-00-00', 32, '', 'female', '106 UNION CIVICA ST.', '09158935460', '', '', '', '', 'Speech', NULL, 'PWD', 'HOMBREBUENO, DOLORES', '', '', '', '', '', '12400007487793', '', '0000-00-00', '0000-00-00', '', 'Active'),
(9, 'LAN\'S', 'TAMAYO', 'BARTOLATA', '', '0000-00-00', 26, '', 'male', '6-8 BAGONG BUHAY ST.', '09555432325', '', '', '', '', 'Psychosocial', NULL, 'PWD', 'BARTOLATA, MARYANNE', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(10, 'HARVEIAME', 'JUMAWAN', 'BARRACA', '', '0000-00-00', 2, '', 'male', '75B CUATRO DE JULIO ST.', '09274220847', '', '', '', '', 'Speech', NULL, 'CWD', 'JUMAWAN, SALVE REGINA', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(11, 'NYMPHA', 'PANIZARES', 'BASCONES', '', '0000-00-00', 0, '', 'female', '43 TOMAS PINPIN ST.', '', '', '', '', '', 'Physical', NULL, '', 'BASCONES JR., GUILLERMO', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(12, 'ADRIAN DEXTER', 'ESTEVES', 'BELLA', '', '0000-00-00', 0, '', 'male', '101 CUATRO DE JULIO ST.', '09074101145', '', '', '', '', 'Auditory', NULL, '', 'BELLA, ANNALY', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(16, ' ALEC JOSHUA', 'J', 'BERNARDO', '', '0000-00-00', 27, '', 'male', '62 WOMEN\'S CLUB ST.', '09758566752', '', '', '', '', '', NULL, 'PWD', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(17, 'VLADY', 'B', 'BORILLO', '', '0000-00-00', 0, '', 'male', '72 BAGONG BUHAY ST.', '09281357524', '', '', '', '', '', NULL, '', 'BORILLO, ROLLIE', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(18, 'HENRY', 'MANRIQUE', 'BORNALES III', '', '0000-00-00', 0, '', 'male', '30 BIAK NA BATO ST.', '09956337288', '', '', '', '', 'Cognitive', NULL, '', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(19, 'JONALYN', 'DIEGA', 'BUCASAS', '', '0000-00-00', 0, '', 'female', '101 CUATRO DE JULIO ST.', '09383706780', '', '', '', '', 'Auditory', NULL, '', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(20, 'LEICESTER MICHAEL', 'ASIS', 'CADANO', '', '0000-00-00', 44, '', 'male', '43 LIBERATION ST.', '09174967964', '', '', '', '', 'Visual', NULL, 'PWD', 'CADANO, ARABELLE MICHELLE', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(21, 'KRISTOFFHERSON JUAN CARLOS', 'BATICBATIC', 'CAGUIWA', '', '0000-00-00', 10, '', 'male', '78 WOMENS CLUB ST,', '091760082223', '', '', '', '', 'Psychosocial', NULL, 'CWD', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(22, 'ALIHUR', 'P', 'CALEZAR', '', '0000-00-00', 0, '', 'male', '110 BUSTAMANTE ST.', '09212715147', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(23, 'ROSEMARIE', '', 'CALING', '', '0000-00-00', 21, '', 'female', '32 UNION CIVICA ST.', '', '', '', '', '', 'Visual', NULL, 'PWD', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(24, 'JONATHAN', 'NAKPIL', 'CANDADO', '', '0000-00-00', 0, '', 'male', '92 LIBERATION ST.', '09669764920', '', '', '', '', 'Psychosocial', NULL, '', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(25, 'MA. MELISSA', 'MANGALUS', 'CANDADO', '', '0000-00-00', 45, '', 'female', '92 LIBERATION ST.', '', '', '', '', '', 'Psychosocial', NULL, 'PWD', 'CANDADO, JONATHAN N.', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(26, 'BONIFACIO', '', 'CANLAS', '', '0000-00-00', 0, '', 'male', '96 LIBERATION ST.', '09453292998', '', '', '', '', '', NULL, '', 'CANLAS, MARY JANE', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(27, 'RIZZA JONE', 'EVASCO', 'CASIO', '', '0000-00-00', 36, '', 'female', '81 CUATRO DE JULIO ST.', '09360389313', '', '', '', '', 'Psychosocial', NULL, 'PWD', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(28, 'KATHLEEN ROSE', 'JOCSON', 'CASTILLO', '', '0000-00-00', 17, '', 'female', '15 PHODACA ST.', '', '', '', '', '', 'Physical', NULL, 'CWD', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(29, 'DIANNA JAZZETTE ', 'DELGADO', 'CENTILLO', '', '0000-00-00', 38, '', 'female', '106 UNION CIVICA ST.', '', '', '', '', '', 'Physical', NULL, 'PWD', 'NIMIA CENTILLO', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(30, 'ANTHONY ALEZER', 'T.', 'CHICA', '', '0000-00-00', 0, '', 'male', '6-B MINDANAO ST.', '09178573537', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(31, 'REYNALDO', '', 'CORTEZ', '', '0000-00-00', 0, '', 'male', '30 LIBERATION ST.', '09166942002', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(32, 'ROMMEL', 'LLAMOSO', 'CRISTAL', '', '0000-00-00', 41, '', 'male', '114 BUSTAMANTE ST.', '09755481852', '', '', '', '', 'Physical', NULL, 'PWD', 'LUNA, REBECCA L.', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(33, 'DARWIN', 'V', 'CRUZ', '', '0000-00-00', 31, '', 'male', '153 BATANES ST.', '', '', '', '', '', '', NULL, 'PWD', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(34, 'JOHN RANDOL', 'E', 'CRUZ', '', '0000-00-00', 10, '', 'male', '81 CUATRO DE JULIO ST.', '09357421813', '', '', '', '', '', NULL, 'CWD', '', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(35, 'KURT REY', 'REYES', 'CRUZ', '', '0000-00-00', 22, '', 'male', '7 MADIAS-AS ST.', '09501370574', '', '', '', '', 'Auditory', NULL, 'PWD', 'CRUZ, RAQUEL R.', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(36, 'ALLAN', 'P', 'DAMES', '', '0000-00-00', 0, '', 'male', '112 LIBERATION ST.', '09950270700', '', '', '', '', '', NULL, '', 'DAMES, MAGDALENA', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(37, 'CHRISTIE', 'RAMOS', 'DANGANAN', '', '0000-00-00', 51, '', 'female', '71B SAN ISIDRO ST.', '', '', '', '', '', 'Physical', NULL, 'PWD', 'DANGANAN, JOSELITO', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active'),
(38, 'JOSE', '', 'DAYO JR.', '', '0000-00-00', 0, '', 'female', '82 BAGONG BUHAY ST', '', '', '', '', '', '', NULL, '', 'DAYO, BABY', '', '', '', '', '', '109-466', '', '0000-00-00', '0000-00-00', '', 'Active'),
(39, 'RAUL', 'G', 'DE CASTRO JR.', '', '0000-00-00', 0, '', 'male', '115 BAGONG BUHAY ST.', '09061683132', '', '', '', '', '', NULL, '', 'DE CASTRO, RAQUEL', '', '', '', '', '', '109-420', '', '0000-00-00', '0000-00-00', '', 'Active'),
(40, 'ROGEL', 'G', 'DE CASTRO', '', '0000-00-00', 0, '', 'male', '114 BAGONG BUHAY ST.', '09475803830', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '109-509', '', '0000-00-00', '0000-00-00', '', 'Active'),
(41, 'FERNANDO', 'FRIAS', 'DE GUZMAN', '', '0000-00-00', 46, '', 'male', '20 VISAYAN LIBERATION', '', '', '', '', '', 'Physical', 'CANCER', 'PWD', '', '', '', '', '', '', '138570', '', '0000-00-00', '0000-00-00', '', 'Active'),
(42, 'MANUEL', 'GUEVARRA', 'DE GUZMAN', '', '0000-00-00', 0, '', 'male', '82 BAGONG BUHAY ST', '09269903907', '', '', '', '', 'Physical', NULL, '', 'DE GUZMAN, TERESITA', '', '', '', '', '', '109-116', '', '0000-00-00', '0000-00-00', '', 'Active'),
(43, 'CATALINA', 'DIZON', 'DE JESUS', '', '0000-00-00', 0, '', 'female', '66 CUATRO DE JULIO ST.', '09497455168', '', '', '', '', 'Psychosocial', NULL, '', 'DE JESUS, RODOLFO', '', '', '', '', '', '109-389', '', '0000-00-00', '0000-00-00', '', 'Active'),
(44, 'TAGUMPAY', 'DIZON', 'DE JESUS', '', '0000-00-00', 0, '', 'female', '66 CUATRO DE JULIO ST.', '09497455168', '', '', '', '', 'Cognitive', NULL, '', 'DE JESUS, RODOLFO', '', '', '', '', '', '109-388', '', '0000-00-00', '0000-00-00', '', 'Active'),
(45, 'ELISA', 'A', 'DE LEON', '', '0000-00-00', 48, '', 'female', '75 WOMEN\'S CLUB ST.', '', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '109-647', '', '0000-00-00', '0000-00-00', '', 'Active'),
(48, 'PERLITA', 'R', 'ABELLERA', '', '0000-00-00', 0, '', 'female', '63-C SANTOL ST.', '09472622313', '', '', '', '', 'Physical', NULL, 'PWD', 'MAGCAWAS, JESSIELEN', '', '', '', '', '', '109-525', '', '0000-00-00', '0000-00-00', '', 'Active');

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
-- Indexes for table `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`ID`);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `archive`
--
ALTER TABLE `archive`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
