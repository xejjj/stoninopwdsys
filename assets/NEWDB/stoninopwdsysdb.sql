-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2026 at 08:10 PM
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
  `full_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','encoder') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admincreds`
--

INSERT INTO `admincreds` (`ID`, `full_name`, `username`, `password`, `role`) VALUES
(1, 'Test Creds', 'test', 'test', 'admin'),
(10, 'Test Creds 2', 'teste', '$2y$10$1JBWrkD/KcxO2L7NOTBjX.Z2M6r2ML2Vb4z0M57AB77KFysxs6Uz.', 'encoder');

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
  `status` enum('Active','Pending','Expired','Under Review','Needs Correction','Rejected') DEFAULT 'Pending',
  `med_cert` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `admin_name` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `module` varchar(100) NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `admin_id`, `admin_name`, `role`, `action`, `module`, `record_id`, `description`, `created_at`) VALUES
(5, 1, '', 'admin', 'LOGIN', 'Authentication', 1, ' logged in', '2026-05-13 18:04:19'),
(6, 1, '', 'admin', 'ARCHIVE', 'Residents', 150, 'Archived resident: RYAN CARLO SESE', '2026-05-13 18:06:52'),
(7, 1, '', 'admin', 'UPDATE', 'Accounts', 1, 'Updated account:  (test) to Test Creds (test), role: admin', '2026-05-13 18:07:08'),
(8, 1, '', 'admin', 'CREATE', 'Accounts', 10, 'Added account: Test Creds 2 (teste) as encoder', '2026-05-13 18:07:17'),
(9, 1, '', 'admin', 'DELETE', 'Archive', 38, 'Permanently deleted archived resident: RYAN CARLO SESE', '2026-05-13 18:07:38'),
(10, 1, '', 'admin', 'RESTORE', 'System', NULL, 'Restored database backup', '2026-05-13 18:07:51'),
(11, 1, '', 'admin', 'RESTORE', 'System', NULL, 'Restored database backup', '2026-05-13 18:08:24');

-- --------------------------------------------------------

--
-- Table structure for table `rejected`
--

CREATE TABLE `rejected` (
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
  `disablity_type` varchar(255) DEFAULT NULL,
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
  `status` enum('Active','Pending','Expired','Under Review','Needs Correction','Rejected') DEFAULT 'Pending',
  `med_cert` varchar(255) DEFAULT NULL,
  `correction_remarks` text DEFAULT NULL
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
  `disablity_type` varchar(255) DEFAULT NULL,
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
  `status` enum('Active','Pending','Expired','Under Review','Needs Correction','Rejected') DEFAULT 'Under Review',
  `med_cert` varchar(255) DEFAULT NULL,
  `correction_remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`ID`, `first_name`, `middle_name`, `last_name`, `civil_status`, `birthdate`, `age`, `birthplace`, `sex`, `address`, `contact_num`, `emergency_cont`, `emergency_cont_num`, `emergency_cont_rel`, `socials`, `disablity_type`, `disability_remarks`, `resident_type`, `guardian_name`, `guardian_cont_num`, `guardian_rel`, `father_name`, `mother_name`, `spouse_name`, `pwdid_num`, `control_num`, `idissue_date`, `idexpiration_date`, `profile`, `status`, `med_cert`, `correction_remarks`) VALUES
(1, 'PERLITA', 'R', 'ABELLERA', '', '0000-00-00', 0, '', 'female', '63-C SANTOL ST.', '09472622313', '', '', '', '', 'Physical', NULL, 'PWD', 'MAGCAWAS, JESSIELEN', '', '', '', '', '', '109-525', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(2, 'BRENT NYLSEN', 'PABLICO', 'AGUILLERA', '', '1979-12-24', 46, '', 'male', '68 WOMEN\'S CLUB ST.', '09158828474', '', '', '', '', 'Cognitive', NULL, 'PWD', 'AGUILLERA, LYN ROSE P.', '', '', '', '', '', '899-217', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(3, 'ARLENE', 'ALFILER', 'AGUINALDO', '', '0000-00-00', 53, '', 'female', '65 SANTOL ST.', '09215751865', '', '', '', '', '', NULL, 'PWD', '', '', '', '', '', '', '12400000204300', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(4, 'ALLAN', 'HILANTAGAAN', 'ALABANZA', '', '0000-00-00', 50, '', 'male', '18 BIAK N BATO ST.', '09457821893', '', '', '', '', 'Visual', NULL, 'PWD', '', '', '', '', '', '', '12400000974777', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(5, 'NORMA', 'B', 'ALCUINO', '', '0000-00-00', 0, '', '', '88 WOMEN\'S CLUB ST.', '09291019040', '', '', '', '', '', NULL, 'PWD', 'SANTIAGO JR., CARLOS', '', '', '', '', '', '109-484', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(6, 'IISHA KARMEL', 'ULIT', 'ALDAY', '', '0000-00-00', 19, '', 'female', '15 BIAK NA BATO ST.', '09273242992', '', '', '', '', 'Auditory', NULL, 'PWD', 'ALDAY, ALLANDALE M.', '', '', '', '', '', '21400000652846', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(7, 'EDGILINE ', 'DENOFRA', 'AQUINO', '', '0000-00-00', 33, '', 'female', '93 WOMEN\'S CLUB ST.', '09171749249', '', '', '', '', 'Physical', NULL, 'PWD', 'AQUINO, GILDA', '', '', '', '', '', '1240001464691', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(8, 'LIZA', 'ARAGON', 'BACSAL', '', '0000-00-00', 32, '', 'female', '106 UNION CIVICA ST.', '09158935460', '', '', '', '', 'Speech', NULL, 'PWD', 'HOMBREBUENO, DOLORES', '', '', '', '', '', '12400007487793', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(9, 'LAN\'S', 'TAMAYO', 'BARTOLATA', '', '0000-00-00', 26, '', 'male', '6-8 BAGONG BUHAY ST.', '09555432325', '', '', '', '', 'Psychosocial', NULL, 'PWD', 'BARTOLATA, MARYANNE', '', '', '', '', '', '12400000723870', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(10, 'HARVEIAME', 'JUMAWAN', 'BARRACA', '', '0000-00-00', 2, '', 'male', '75B CUATRO DE JULIO ST.', '09274220847', '', '', '', '', 'Speech', NULL, 'CWD', 'JUMAWAN, SALVE REGINA', '', '', '', '', '', '12400001794303', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(11, 'NYMPHA', 'PANIZARES', 'BASCONES', '', '0000-00-00', 0, '', 'female', '43 TOMAS PINPIN ST.', '', '', '', '', '', 'Physical', '', 'PWD', 'BASCONES JR., GUILLERMO', '', '', '', '', '', '109-555', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(12, 'ADRIAN DEXTER', 'ESTEVES', 'BELLA', '', '0000-00-00', 0, '', 'male', '101 CUATRO DE JULIO ST.', '09074101145', '', '', '', '', 'Auditory', '', 'PWD', 'BELLA, ANNALY', '', '', '', '', '', '109-456', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(16, 'ALEC JOSHUA', 'J', 'BERNARDO', '', '0000-00-00', 27, '', 'male', '62 WOMEN\'S CLUB ST.', '09758566752', '', '', '', '', '', '', 'PWD', '', '', '', '', '', '', '133901060014', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(17, 'VLADY', 'B', 'BORILLO', '', '0000-00-00', 0, '', 'male', '72 BAGONG BUHAY ST.', '09281357524', '', '', '', '', '', '', 'PWD', 'BORILLO, ROLLIE', '', '', '', '', '', '109-589', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(18, 'HENRY', 'MANRIQUE', 'BORNALES III', '', '0000-00-00', 0, '', 'male', '30 BIAK NA BATO ST.', '09956337288', '', '', '', '', 'Cognitive', '', 'PWD', '', '', '', '', '', '', '109-477', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(19, 'JONALYN', 'DIEGA', 'BUCASAS', '', '0000-00-00', 0, '', 'female', '101 CUATRO DE JULIO ST.', '09383706780', '', '', '', '', 'Auditory', '', 'PWD', '', '', '', '', '', '', '109-073', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(20, 'LEICESTER MICHAEL', 'ASIS', 'CADANO', '', '0000-00-00', 44, '', 'male', '43 LIBERATION ST.', '09174967964', '', '', '', '', 'Visual', '', 'PWD', 'CADANO, ARABELLE MICHELLE', '', '', '', '', '', '109-474', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(21, 'KRISTOFFHERSON JUAN CARLOS', 'BATICBATIC', 'CAGUIWA', '', '0000-00-00', 10, '', 'male', '78 WOMENS CLUB ST,', '091760082223', '', '', '', '', 'Psychosocial', '', 'CWD', '', '', '', '', '', '', '12400000569932', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(22, 'ALIHUR', 'P', 'CALEZAR', '', '0000-00-00', 0, '', 'male', '110 BUSTAMANTE ST.', '09212715147', '', '', '', '', '', '', 'PWD', '', '', '', '', '', '', '109-542', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(23, 'ROSEMARIE', '', 'CALING', '', '0000-00-00', 21, '', 'female', '32 UNION CIVICA ST.', '', '', '', '', '', 'Visual', '', 'PWD', '', '', '', '', '', '', '10200000726757', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(24, 'JONATHAN', 'NAKPIL', 'CANDADO', '', '0000-00-00', 0, '', 'male', '92 LIBERATION ST.', '09669764920', '', '', '', '', 'Psychosocial', '', 'PWD', '', '', '', '', '', '', '109-117', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(25, 'MA. MELISSA', 'MANGALUS', 'CANDADO', '', '0000-00-00', 45, '', 'female', '92 LIBERATION ST.', '', '', '', '', '', 'Psychosocial', '', 'PWD', 'CANDADO, JONATHAN N.', '', '', '', '', '', '256342', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(26, 'BONIFACIO', '', 'CANLAS', '', '0000-00-00', 0, '', 'male', '96 LIBERATION ST.', '09453292998', '', '', '', '', '', '', 'PWD', 'CANLAS, MARY JANE', '', '', '', '', '', '109-476', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(27, 'RIZZA JONE', 'EVASCO', 'CASIO', '', '0000-00-00', 36, '', 'female', '81 CUATRO DE JULIO ST.', '09360389313', '', '', '', '', 'Psychosocial', '', 'PWD', '', '', '', '', '', '', '763-033', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(28, 'KATHLEEN ROSE', 'JOCSON', 'CASTILLO', '', '0000-00-00', 17, '', 'female', '15 PHODACA ST.', '', '', '', '', '', 'Physical', '', 'CWD', '', '', '', '', '', '', '12400001018873', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(29, 'DIANNA JAZZETTE', 'DELGADO', 'CENTILLO', '', '0000-00-00', 38, '', 'female', '106 UNION CIVICA ST.', '', '', '', '', '', 'Physical', '', 'PWD', 'NIMIA CENTILLO', '', '', '', '', '', '12400000437294', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(30, 'ANTHONY ALEZER', 'T.', 'CHICA', '', '0000-00-00', 0, '', 'male', '6-B MINDANAO ST.', '09178573537', '', '', '', '', '', '', 'PWD', '', '', '', '', '', '', '109-591', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(31, 'REYNALDO', '', 'CORTEZ', '', '0000-00-00', 0, '', 'male', '30 LIBERATION ST.', '09166942002', '', '', '', '', '', '', 'PWD', '', '', '', '', '', '', '12400000098039', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(32, 'ROMMEL', 'LLAMOSO', 'CRISTAL', '', '0000-00-00', 41, '', 'male', '114 BUSTAMANTE ST.', '09755481852', '', '', '', '', 'Physical', '', 'PWD', 'LUNA, REBECCA L.', '', '', '', '', '', '12400000192431', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(33, 'DARWIN', 'V', 'CRUZ', '', '0000-00-00', 31, '', 'male', '153 BATANES ST.', '', '', '', '', '', '', '', 'PWD', '', '', '', '', '', '', '109-605', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(34, 'JOHN RANDOLF', 'E', 'CRUZ', '', '0000-00-00', 10, '', 'male', '81 CUATRO DE JULIO ST.', '09357421813', '', '', '', '', '', '', 'CWD', '', '', '', '', '', '', '109-500', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(35, 'KURT REY', 'REYES', 'CRUZ', '', '0000-00-00', 22, '', 'male', '7 MADIAS-AS ST.', '09501370574', '', '', '', '', 'Auditory', '', 'PWD', 'CRUZ, RAQUEL R.', '', '', '', '', '', '1135-929', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(36, 'ALLAN', 'P', 'DAMES', '', '0000-00-00', 0, '', 'male', '112 LIBERATION ST.', '09950270700', '', '', '', '', '', '', 'PWD', 'DAMES, MAGDALENA', '', '', '', '', '', '109-030', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(37, 'CHRISTIE', 'RAMOS', 'DANGANAN', '', '0000-00-00', 51, '', 'female', '71B SAN ISIDRO ST.', '', '', '', '', '', 'Physical', '', 'PWD', 'DANGANAN, JOSELITO', '', '', '', '', '', '10900000274205', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(38, 'JOSE', '', 'DAYO JR.', '', '0000-00-00', 0, '', 'female', '82 BAGONG BUHAY ST', '', '', '', '', '', '', '', 'PWD', 'DAYO, BABY', '', '', '', '', '', '109-466', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(39, 'RAUL', 'G', 'DE CASTRO JR.', '', '0000-00-00', 0, '', 'male', '115 BAGONG BUHAY ST.', '09061683132', '', '', '', '', '', NULL, 'PWD', 'DE CASTRO, RAQUEL', '', '', '', '', '', '109-420', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(40, 'ROGEL', 'G', 'DE CASTRO', '', '0000-00-00', 0, '', 'male', '114 BAGONG BUHAY ST.', '09475803830', '', '', '', '', '', NULL, 'PWD', '', '', '', '', '', '', '109-509', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(41, 'FERNANDO', 'FRIAS', 'DE GUZMAN', '', '0000-00-00', 46, '', 'male', '20 VISAYAN LIBERATION', '', '', '', '', '', 'Physical', 'CANCER', 'PWD', '', '', '', '', '', '', '138570', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(42, 'MANUEL', 'GUEVARRA', 'DE GUZMAN', '', '0000-00-00', 0, '', 'male', '82 BAGONG BUHAY ST', '09269903907', '', '', '', '', 'Physical', NULL, 'PWD', 'DE GUZMAN, TERESITA', '', '', '', '', '', '109-116', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(43, 'CATALINA', 'DIZON', 'DE JESUS', '', '0000-00-00', 0, '', 'female', '66 CUATRO DE JULIO ST.', '09497455168', '', '', '', '', 'Psychosocial', NULL, 'PWD', 'DE JESUS, RODOLFO', '', '', '', '', '', '109-389', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(44, 'TAGUMPAY', 'DIZON', 'DE JESUS', '', '0000-00-00', 0, '', 'female', '66 CUATRO DE JULIO ST.', '09497455168', '', '', '', '', 'Cognitive', NULL, 'PWD', 'DE JESUS, RODOLFO', '', '', '', '', '', '109-388', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(45, 'ELISA', 'A', 'DE LEON', '', '0000-00-00', 48, '', 'female', '75 WOMEN\'S CLUB ST.', '', '', '', '', '', '', NULL, 'PWD', '', '', '', '', '', '', '109-647', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(48, 'FELICITY MHAE', 'M', 'DELA VEGA', '', '2018-01-05', 8, '', 'female', '65 UNANG HAKBANG ST.', '', '', '', '', '', 'Others', '', 'CWD', '', '', '', '', '', '', '109-600', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(49, 'ARNOLD', 'S', 'DELGADO', '', '1970-04-20', 56, '', 'male', '79 LIBERATION ST.', '09074830015', '', '', '', '', 'Others', '', 'PWD', 'DELGADO, ALLAN', '', '', '', '', '', '109-604', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(50, 'MARIBETH', 'TRINIDAD', 'DIEZ', '', '1970-08-19', 55, '', 'female', '64 SAN ISIDRO ST.', '09958251845', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '12400001339920', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(51, 'EDWIN', '', 'DIOCTON', '', '0000-00-00', 0, '', 'male', '64 SAN ISIDRO ST.', '09984469990', '', '', '', '', '', NULL, 'PWD', '', '', '', '', '', '', '109-109', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(52, 'ARIEN', 'CAMPO', 'DIVINA', '', '0000-00-00', 0, '', 'female', '93 LIBERATION ST.', '0917874819', '', '', '', '', 'Physical', NULL, 'PWD', 'DIVINA, MA. EURNE BETH CAMPO', '', '', '', '', '', '109-539', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(53, 'AARON GABRIEL', 'DC', 'DOMONDON', '', '0000-00-00', 0, '', 'male', '3 P.D. TAVERA ST.', '', '', '', '', '', '', NULL, 'PWD', '', '', '', '', '', '', '109-418', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(54, 'AEON STEFAN', 'U', 'DORNIDON', '', '0000-00-00', 0, '', 'male', '16 SAN CRISTOBAL ST.', '09173263984', '', '', '', '', '', NULL, 'PWD', '', '', '', '', '', '', '109-382', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(55, 'MICHAEL LOUIS', 'NIEVERA', 'DUCUSIN', '', '0000-00-00', 31, '', 'male', '9-B MINDANAO AVE.', '0918429960', '', '', '', '', 'Cognitive', 'LEARNING', 'PWD', '', '', '', '', '', '', '104-344', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(56, 'JOHN MICHAEL', 'SANTOS', 'DUMANIG', '', '1986-12-28', 39, '', 'male', '68 WOMEN\'S CLUB ST.', '09057008025', '', '', '', '', 'Physical', '', 'PWD', 'NGO, MARY ANN G.', '', '', '', '', '', '656906', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(57, 'MARIA ESTELA', 'SANTOS', 'DUMANIG', '', '0000-00-00', 0, '', 'female', '68 WOMEN\'S CLUB ST.', '09323274574', '', '', '', '', 'Auditory', '', 'PWD', '', '', '', '', '', '', '109-533', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(58, 'ANALYN', 'O', 'EISMA', '', '1972-05-28', 53, '', 'female', '23-A SAN ISIDRO EXT.', '09955427384', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '098-174', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(59, 'ARIEL', '', 'ELIQUEN', '', '0000-00-00', 0, '', 'male', '38 MINDANAO AVE.', '09561578008', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '109-081', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(60, 'JOHN CYREL', 'I', 'ENRIQUEZ', '', '0000-00-00', 0, '', 'male', '38 MINDANAO AVE.', '09561578008', '', '', '', '', 'Others', 'N/A', 'PWD', 'ENRIQUEZ, ARNILO D.', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(61, 'DANICA', 'SISON', 'ESGUERRA', '', '0000-00-00', 0, '', 'female', '89 UNION CIVICA ST.', '09178724747', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '124000026627', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(62, 'DIANA', 'SISON', 'ESGUERRA', '', '0000-00-00', 0, '', 'female', '86 BUSTAMANTE ST.', '09185836268', '', '', '', '', 'Psychosocial', '', 'PWD', 'ESGUERRA, DANILO B.', '', '', '', '', '', '137404', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(63, 'DEJAN KALJEVIC', 'B', 'ESPINOSA', '', '2005-09-18', 20, '', 'male', '24 SANCIANGCO ST.', '09398508515', '', '', '', '', '', '', 'PWD', 'ESPINOSA, TERESA', '', '', '', '', '', '109-552', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(64, 'SOPHIA ALEXA', 'B', 'ESPINOSA', '', '2013-04-15', 13, '', 'female', '24 SANCIANGCO ST.', '09398508515', '', '', '', '', '', '', 'CWD', 'ESPINOSA, TERESA', '', '', '', '', '', '109-563', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(65, 'SETH FAVIO', 'MENDEZ', 'ESTOY', '', '2020-06-11', 5, '', 'male', '65 UNANG HAKBANG ST.', '09275817962', '', '', '', '', 'Physical', '', 'CWD', 'ESTOY, ARLENE M.', '', '', '', '', '', '124000018115111', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(66, 'JOSHUA REN DE', 'CASTRO', 'FAGARAGAN', '', '0000-00-00', 0, '', 'male', '7 SAN ISIDRO ST.', '09956920902', '', '', '', '', 'Cognitive', '', 'PWD', 'FAGARAGAN, ANGELITA', '', '', '', '', '', '109-469', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(67, 'EVA MARICRIS CATHERINE', 'DOCTOR', 'FAMA', '', '0000-00-00', 0, '', 'female', '4 MINDANAO AVE.', '09165138474', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '109-508', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(68, 'GLADYS', 'BENITEZ', 'FULGENCIO', '', '1966-07-04', 59, '', 'female', '93 WOMEN\'S CLUB ST.', '09184180284', '', '', '', '', 'Physical', 'ORTHOPEDIC', 'PWD', 'FULGENCIO, NEIL', '', '', '', '', '', '562-551', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(69, 'JONATHAN', 'BENITEZ', 'FULGENCIO', '', '1990-06-30', 35, '', 'male', '93 WOMEN\'S CLUB ST.', '', '', '', '', '', 'Physical', 'ORTHOPEDIC', 'PWD', '', '', '', '', '', '', '562-552', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(70, 'JOHN ULAP MALAIAH', 'SANTOS', 'FULGENCIO', '', '2020-02-14', 6, '', 'male', '90 BUSTAMANTE ST.', '09562533877', '', '', '', '', 'Psychosocial', '', 'CWD', 'FULGENCIO, JOHN TEDEN', '', '', '', '', '', '12400000816098', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(71, 'LUCIAN', '', 'FULGENCIO', '', '2017-09-11', 8, '', 'male', '93 WOMEN\'S CLUB ST.', '09184180284', '', '', '', '', 'Speech', '', 'CWD', 'FULGENCIO, GLADYS', '', '', '', '', '', '01662079', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(72, 'LANI', 'SIMPORIOS', 'GALAPIN', '', '0000-00-00', 0, '', 'female', '65 WOMEN\'S CLUB ST.', '', '', '', '', '', 'Speech', '', 'PWD', '', '', '', '', '', '', '090-181', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(73, 'ALMIRA MIGEL', 'ALATEIT', 'GALERA', '', '2020-09-13', 5, '', 'female', '74 WOMEN\'S CLUB ST.', '09230902949', '', '', '', '', 'Speech', '', 'CWD', 'GALERA, MICHELLE', '', '', '', '', '', '1402979', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(74, 'FERNANDEZ', 'LAGROSA', 'GALVEZ', '', '0000-00-00', 0, '', 'male', '37 TOMAS PINPIN ST.', '09276036854', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '109-129', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(75, 'ERICSON', 'LUSTADO', 'GATDULA', '', '1965-09-21', 60, '', 'male', '90 BAGONG BUHAY ST.', '', '', '', '', '', 'Physical', '', 'PWD', 'MERCADO, MA. LOURDES', '', '', '', '', '', '12400000137404', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(76, 'MA. THERESA', 'V', 'GUANIZO', '', '0000-00-00', 0, '', 'female', '59 CUATRO DE JULIO ST.', '09293776666', '', '', '', '', 'Others', 'N/A', 'PWD', 'LOZA, EDWIN', '', '', '', '', '', '109-533', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(77, 'ELAIJAH JADE', 'M', 'HATI', '', '0000-00-00', 0, '', 'male', '18 G. SANCIANGCO ST.', '09777785504', '', '', '', '', 'Others', 'N/A', 'PWD', 'HATI, ELAINE', '', '', '', '', '', '109-095', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(78, 'ANNE BERNADETTE', '', 'HUAB', '', '0000-00-00', 0, '', 'female', '89 CUATRO DE JULIO ST.', '09392261464', '', '', '', '', 'Others', 'N/A', 'PWD', 'HUAB, ROSITA', '', '', '', '', '', '109-490', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(79, 'RUDY', 'G', 'HUAB', '', '0000-00-00', 0, '', 'male', '89 CUATRO DE JULIO ST.', '09994167734', '', '', '', '', 'Others', 'N/A', 'PWD', 'HUAB, ROSITA', '', '', '', '', '', '109-565', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(80, 'JOHN MICHAEL', '', 'JINDANI', '', '0000-00-00', 0, '', 'male', '25 G. SANCIANGCO ST.', '09166179589', '', '', '', '', 'Others', 'N/A', 'PWD', 'JINDANI, ALIIN', '', '', '', '', '', '109-402', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(81, 'SALVE REGINA', 'LUCERO', 'JUMAWAN', '', '1991-05-21', 34, '', 'female', '758 CUATRO DE JULIO ST.', '09244220847', '', '', '', '', 'Psychosocial', '', 'PWD', 'BARRACA, JAMES', '', '', '', '', '', '553229', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(82, 'MARIA', 'GUSI', 'JUNIO', '', '1966-12-09', 59, '', 'female', '108 BAGONG BUHAY ST.', '09297466853', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '1240000502151', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(83, 'HONEY FAYE', '', 'LAXAMANA', '', '0000-00-00', 0, '', 'female', '8 SAN CRISTOBAL ST.', '09560636227', '', '', '', '', 'Others', 'N/A', 'PWD', 'LAXAMANA, ROLANDO', '', '', '', '', '', '109-428', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(84, 'SHEIAN IZABEL', 'ANTESCO', 'MAALIW', '', '2017-03-22', 9, '', 'female', '99C -BAGONG BUHAY ST.', '', '', '', '', '', 'Speech', '', 'CWD', '', '', '', '', '', '', '137-404', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(85, 'JUAN GARCIA', '', 'MAGALONG III', '', '2013-10-22', 12, '', 'male', '98-A CUATRO DE JULIO ST.', '', '', '', '', '', 'Cognitive', '', 'CWD', '', '', '', '', '', '', '12400001020497', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(86, 'AILEEN ELEONOR', 'GARCIA', 'MAGALONG', '', '1991-07-24', 34, '', 'female', '98-A CUATRO DE JULIO ST.', '09274019686', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '124000001100213', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(87, 'JACKLYN', 'J', 'MANABAT', '', '0000-00-00', 0, '', 'female', '82 BAGONG BUHAY ST.', '09561081835', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '109-078', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(88, 'CHRISTIAN PAU', 'PLATON', 'MANGILAYA', '', '1984-05-17', 41, '', 'male', '103 BAGONG BUHAY ST.', '', '', '', '', '', 'Psychosocial', '', 'PWD', '', '', '', '', '', '', '109-616', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(89, 'CHRISTEL', 'M', 'MANIPOL', '', '0000-00-00', 0, '', 'female', '72 LIBERATION ST.', '09569431517', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '109-036', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(90, 'CHRIAN', 'M', 'MANIPOL', '', '0000-00-00', 0, '', 'male', '72 LIBERATION ST.', '09260645577', '', '', '', '', 'Others', 'N/A', 'PWD', 'MANIPOL, LETICIA', '', '', '', '', '', '109-014', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(91, 'EXEQUIEL IVAN', 'URIEL', 'MARIÑAS', '', '1994-09-17', 31, '', 'male', '114 BAGONG BUHAY ST.', '09485216800', '', '', '', '', 'Others', 'N/A', 'PWD', 'ELIQUEN, ARIEL', '', '', '', '', '', '109-491', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(92, 'DAVID JOSIAH', 'F', 'MARQUEZ', '', '2016-06-01', 9, '', 'male', '6 SAN CRISTOBAL ST.', '09156423670', '', '', '', '', 'Others', 'N/A', 'CWD', 'FLORES, RUSHELLE', '', '', '', '', '', '0950000574119', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(93, 'MELISSA RIVERA', '', 'MATIAS', '', '2010-12-19', 15, '', 'male', '76-E BAYANI ST.', '092734700159', '', '', '', '', 'Cognitive', '', 'CWD', 'MATIAS, JHON JOSEPH', '', '', '', '', '', '12400000624704', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(94, 'GIL ANTHONY', 'GUEVARRA', 'MEDEL', '', '1977-06-06', 48, '', 'male', '11 BIAK N BATO ST.', '09982030099', '', '', '', '', 'Cognitive', 'MENTAL', 'PWD', 'MEDEL, GIL ANTONIO', '', '', '', '', '', '12400000654198', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(95, 'JEAN AUSTIN', 'JOCSON', 'MEDEL', '', '1998-09-13', 27, '', 'male', '11 BIAK N BATO ST.', '09269588600', '', '', '', '', 'Psychosocial', '', 'PWD', '', '', '', '', '', '', '12400000903116', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(96, 'JONATHAN', 'N', 'MELO', '', '0000-00-00', 0, '', 'male', '82 LIBERATION ST.', '09216365356', '', '', '', '', 'Others', 'N/A', 'PWD', 'PANGAN, KATRINA', '', '', '', '', '', '109-432', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(97, 'JAMES', 'ABELLAR', 'MENDOZA', '', '0000-00-00', 0, '', 'male', '34-D MINDANAO AVE.', '', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '109-442', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(98, 'OSCAR', 'MORAN', 'MESIAS JR.', '', '1965-12-28', 60, '', 'male', '78 WOMEN\'S CLUB ST.', '', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '678-989', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(99, 'STEPHEN DAVID', 'C', 'MESIAS', '', '1995-03-18', 31, '', 'male', '78 WOMEN\'S CLUB ST.', '', '', '', '', '', 'Cognitive', 'LEARNING', 'PWD', 'MESIAS, OSCAR', '', '', '', '', '', '12400000678914', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(100, 'FRANCIS DAVID', 'SISON', 'MOSQUEDA', '', '2019-07-31', 6, '', 'male', '25 G. SANCIANGCO ST.', '09663303899', '', '', '', '', 'Physical', '', 'CWD', 'MOSQUEDA, MICHELLE S.', '', '', '', '', '', '998623', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(101, 'BENEDICT', 'MANGHIRANG', 'MUYONG', '', '1992-06-01', 33, '', 'male', '69 BUSTAMANTE ST.', '090634822667', '', '', '', '', 'Auditory', 'DEAF/HARD OF HEARING', 'PWD', '', '', '', '', '', '', '12400000987895', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(102, 'ERIC', 'BIO', 'NUADA', '', '0000-00-00', 0, '', 'male', '10-A MADIAS-AS ST', '09228846605', '', '', '', '', 'Auditory', 'DEAF/HARD OF HEARING', 'PWD', 'NUADA, ROSALIE', '', '', '', '', '', '109-386', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(103, 'ENRICO', '', 'ORTIZ', '', '0000-00-00', 0, '', 'male', '16 VISAYAN AVE.', '0945345594', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '109-479', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(104, 'YUAN', 'ROA', 'PADILLA', '', '0000-00-00', 0, '', 'male', '85-8 LIBERATION ST.', '09656221956', '', '', '', '', 'Cognitive', 'INTELLECTUAL', 'PWD', 'PADILLA, MICHELLE', '', '', '', '', '', '109-050', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(105, 'EDWIN', 'ESPINOSA', 'PADUA', '', '1978-04-17', 48, '', 'male', '83 LIBERATION ST.', '09561578029', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '12400000137404', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(106, 'JOSHUA', '', 'PAGARAGAN', '', '0000-00-00', 0, '', 'male', '7 SAN ISIDRO ST', '09956920902', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '109-399', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(107, 'DAN AEDHEN', 'A', 'PAJARILLO', '', '0000-00-00', 0, '', 'male', '91 LIBERATION ST.', '09219292642', '', '', '', '', 'Others', 'N/A', 'PWD', 'PAJARILLOLEAH,', '', '', '', '', '', '109-089', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(108, 'MARLON', 'T', 'PAMEL', '', '1980-05-31', 45, '', 'male', '46 TOMAS PINPIN ST.', '09982796255', '', '', '', '', 'Others', 'N/A', 'PWD', 'TUGAY, APRILYN', '', '', '', '', '', '109-387', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(109, 'MARVIN', 'TORRES', 'PAMEL', '', '1978-07-21', 47, '', 'male', '27 G. SANCIANGCO ST.', '09958700496', '', '', '', '', 'Physical', '', 'PWD', 'PAMEL, ANNALYN S.', '', '', '', '', '', '12400000748540', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(110, 'MICHAEL', 'TORRES', 'PAMEL', '', '1974-11-21', 51, '', 'male', '114 BAGONG BUHAY ST.', '09913158053', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '12400001128672', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(111, 'KATRINA', 'IDA', 'PANGAN', '', '0000-00-00', 0, '', 'female', '82 LIBERATION ST.', '09178675026', '', '', '', '', 'Others', 'N/A', 'PWD', 'PANGAN, MILDRED', '', '', '', '', '', '109-481', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(112, 'ROWENA', 'AGUILERA', 'PASCUAL', '', '0000-00-00', 0, '', 'female', 'AGUILERA', '09334719604', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '109-103', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(113, 'VIRGILIO', 'ORTEGA', 'PETALLO JR.', '', '1983-08-22', 42, '', 'male', '75 BUSTAMANTE ST.', '', '', '', '', '', 'Physical', 'ORTHOPEDIC', 'PWD', '', '', '', '', '', '', '12400000503600', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(114, 'DEAN RYLLE', 'GONZALES', 'QUIAMBAO', '', '2015-01-05', 11, '', 'male', '71-C BAGONG BUHAY ST.', '09273611693', '', '', '', '', 'Cognitive', 'LEARNING', 'CWD', 'GONZALES, RAQUEL C.', '', '', '', '', '', '12400000926293', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(115, 'EMMANUEL', 'L', 'RAMOS', '', '0000-00-00', 0, '', 'male', '71 SAN ISIDRO ST.', '09955427384', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '109-386', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(116, 'DWAYNE MATTHEW', 'SANTOS', 'REYES', '', '2017-01-27', 9, '', 'male', '96 BAGONG BUHAY ST.', '09477145183', '', '', '', '', 'Speech', 'SPEECH AND LANGUAGE', 'CWD', 'MAGLENTE, SHIRYL-LYN S.', '', '', '', '', '', '12400001029444', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(117, 'JOYCE', 'GUILLERMO', 'REYES', '', '0000-00-00', 0, '', 'female', '38 MINDANAO AVE.', '09982278335', '', '', '', '', 'Physical', 'CANCER', 'PWD', 'REYES, VICTORIA', '', '', '', '', '', '12400000109537', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(118, 'IVANNA', 'C', 'RODRIGUEZ', '', '0000-00-00', 0, '', 'female', '112 UNION CIVICA ST', '09206881276', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '109-125', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(119, 'HENRY', 'LETRUDO', 'SAJORDA', '', '1981-08-15', 44, '', 'male', '76 BAYANI ST.', '09205909595', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '1093830', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(120, 'JODERIC', 'LETRUDO', 'SAJORDA', '', '1980-10-01', 45, '', 'male', '76-F BAYANI ST...', '09067191026', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '12400008590432', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(121, 'ALEXEUZ JOSIAH', 'LAYUG', 'SALAZAR', '', '2019-03-12', 7, '', 'male', '96 BAGONG BUHAY ST.', '09230675795', '', '', '', '', 'Speech', 'SPEECH AND LANGUAGE', 'CWD', 'SALAZAR, JACQUIELYN', '', '', '', '', '', '124000000214606', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(122, 'DAMIEN ELEAZAR', 'B', 'SALVADOR', '', '2016-09-22', 9, '', 'male', '111 UNION CIVICA ST.', '09773585087', '', '', '', '', 'Speech', 'SPEECH AND LANGUAGE', 'CWD', 'SALVADOR, ELYLOU BALA', '', '', '', '', '', '01320062', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(123, 'LOUISSE DANE', 'B', 'SALVADOR', '', '2003-01-01', 23, '', 'female', '111 UNION CIVICA ST.', '09773585087', '', '', '', '', 'Cognitive', 'PSYCHOLOGICAL', 'PWD', 'SALVADOR, ELYLOU BALA', '', '', '', '', '', '545282', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(124, 'SHANE MARINETH', 'GONZALVO', 'SAMSON', '', '2004-10-11', 21, '', 'female', '65 SANTOL ST', '09175228891', '', '', '', '', 'Visual', '', 'PWD', 'SAMSON, SHENNA', '', '', '', '', '', '12400000109106', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(125, 'SHENNA', 'GONZALVO', 'SAMSON', '', '0000-00-00', 0, '', 'female', '65 SANTOL ST', '', '', '', '', '', 'Visual', '', 'PWD', 'GONZALVO, SERGIO C', '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(126, 'MARCO SANTHINO', 'GONZALVO', 'SAMSON', '', '2009-02-08', 17, '', 'male', '65 SANTOL ST.', '09175228891', '', '', '', '', 'Cognitive', 'LEARNING', 'CWD', 'SAMSON, SHENNA', '', '', '', '', '', '12400000099118', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(127, 'MARCUS SHAWN FRANCIS', 'GONZALVO', 'SAMSON', '', '2013-03-14', 13, '', 'male', '65 SANTOL ST.', '09175228891', '', '', '', '', 'Cognitive', 'LEARNING', 'CWD', 'SAMSON, SHENNA', '', '', '', '', '', '124100000109422', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(128, 'ANGEL', 'B', 'SANTIAGO JR.', '', '0000-00-00', 0, '', 'male', '88 WOMEN\'S CLUB ST.', '09982030099', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '109-454', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(129, 'ALEXANDER', 'MANGALUS', 'SAQUING', '', '1980-07-24', 45, '', 'male', '92 LIBERATION ST.', '09979176520', '', '', '', '', 'Visual', '', 'PWD', 'SAQUING, MICHAEL', '', '', '', '', '', '303813', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(130, 'ANA LIZA', 'V', 'SARSALIJO', '', '0000-00-00', 0, '', 'female', '57 SAN ISIDRO ST.', '09984985385', '', '', '', '', 'Others', 'N/A', 'PWD', 'TABLIGAN, MARY ANN S.', '', '', '', '', '', '109-594', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(131, 'KHEANE ANGELO', 'G', 'SIOSON', '', '0000-00-00', 0, '', 'male', '117-C MADIAS-AS ST.', '09326281495', '', '', '', '', 'Others', 'N/A', 'PWD', 'AUSTRIA, YOLANDA', '', '', '', '', '', '109-384', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(132, 'CANDY', 'SIO', 'SIU', '', '1980-10-19', 45, '', 'female', '118 UNION CIVICA ST', '09176745845', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '12400000610828', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(133, 'MARINA', 'A', 'SY', '', '1975-03-01', 51, '', 'female', '114 UNION CIVICA ST', '09157423993', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '109-638', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(134, 'HIROMI', 'UMALI', 'TAKANO', '', '2014-07-02', 11, '', 'female', '76 SAN ISIDRO ST.', '09515217676', '', '', '', '', 'Visual', '', 'CWD', 'UMALI, CAREN JOY A.', '', '', '', '', '', '12400000553396', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(135, 'LANS', '', 'TAMAYO', '', '2000-02-16', 26, '', 'male', '6-B BAGONG BUHAY ST.', '', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '137-404', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(136, 'JUAN', 'TORCUATOR', 'TEVAR', '', '1955-07-04', 70, '', 'male', '64 WOMEN\'S CLUB ST.', '09169628676', '', '', '', '', 'Physical', '', 'PWD', 'TEVAR, JEFFREY', '', '', '', '', '', '124000007748294', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(137, 'CHARINA', 'CORTEZ', 'TOLENTINO', '', '1976-09-10', 49, '', 'female', '37 SAN ISIDRO ST.', '09218650621', '', '', '', '', 'Psychosocial', '', 'PWD', 'TOLENTINO, ROSARIO C.', '', '', '', '', '', '12400000702565', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(138, 'KERBY', 'E', 'TORNILLA', '', '0000-00-00', 0, '', 'male', '118 LIBERATIONS ST.', '09164940046', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '110-220', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(139, 'RYAN', 'VERANO', 'TORRALBA', '', '1979-12-12', 46, '', 'male', '62 SAN ISIDRO ST.', '09277880489', '', '', '', '', 'Physical', '', 'PWD', '', '', '', '', '', '', '12400001241736', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(140, 'JENALYNE', 'C', 'TRINIDAD', '', '0000-00-00', 0, '', 'female', '75 CUATRO DE JULIO ST.', '09487092827', '', '', '', '', 'Others', 'N/A', 'PWD', 'CARINO JR., GUILLERMO', '', '', '', '', '', '109-483', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(141, 'ALVIN', 'DEXTER', 'TUGAY', '', '0000-00-00', 0, '', 'male', '27 G. SANCIANGCO ST.', '09359343180', '', '', '', '', 'Others', 'N/A', 'PWD', 'TUGAY, APRILYN', '', '', '', '', '', '109-421', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(142, 'AMERIZZA', 'L', 'TUGAY', '', '0000-00-00', 0, '', 'female', '27 G. SANČIANGCO ST.', '09155963303', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '109-406', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(143, 'APRILYN', 'L', 'TUGAY', '', '0000-00-00', 0, '', 'female', '27 G. SANCIANGCO ST.', '09164908709', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '109-390', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(144, 'JOSEFINA', 'GENEROSO', 'VELASCO', '', '1973-01-10', 53, '', 'female', '84 UNION CIVICA ST.', '09067191129', '', '', '', '', 'Physical', '', 'PWD', 'VELASCO, MENCHITA', '', '', '', '', '', '12400000802728', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(145, 'MARCO', 'S', 'VILLACORTA', '', '2006-12-12', 19, '', 'male', '114 UNION CIVICA ST', '', '', '', '', '', 'Physical', '', 'PWD', 'SY, MARINA', '', '', '', '', '', '337-404', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(146, 'SOJIRO', 'DE GUZMAN', 'VILLENA', '', '2012-09-18', 13, '', 'male', '82 BAGONG BUHAY ST.', '09537803625', '', '', '', '', 'Cognitive', 'LEARNING', 'CWD', 'VILLENA, CRISELDA', '', '', '', '', '', '12400001320024', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL),
(147, 'EIJAY', 'C', 'DIVINA', '', '0000-00-00', 0, '', 'male', '93 LIBERATION ST.', '0917874819', '', '', '', '', 'Others', 'N/A', 'PWD', '', '', '', '', '', '', '109-060', '', '0000-00-00', '0000-00-00', '', 'Active', NULL, NULL);

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
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rejected`
--
ALTER TABLE `rejected`
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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `archive`
--
ALTER TABLE `archive`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rejected`
--
ALTER TABLE `rejected`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
