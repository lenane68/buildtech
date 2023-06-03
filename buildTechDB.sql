-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 06, 2023 at 10:16 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `BuildTech`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `userName` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`userName`, `email`, `password`) VALUES
('b', 'b@gmail.com', '$2y$10$4hkdjddBnkhMK'),
('f', 'f@gmail.com', '12345678a'),
('n', 'n@gmail.com', '$2y$10$bkOYfr7qEH/I.'),
('ni', 'nrmeen.ra.123@gmail.com', '$2y$10$k2AFNdhUvYeX3'),
('no', 'test@gmail.com', '$2y$10$vmX6MpHWOGtrQ'),
('s', 's@gmail.com', '12345678a'),
('רפיק גבארין', 'rafeekja@gmail.com', '12345678a');

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `number` varchar(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `year` year(4) NOT NULL,
  `color` varchar(20) NOT NULL,
  `testDate` date NOT NULL,
  `insuranceDate` date NOT NULL,
  `careDate` date NOT NULL,
  `fuelType` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`number`, `type`, `year`, `color`, `testDate`, `insuranceDate`, `careDate`, `fuelType`) VALUES
('200613', 'מחפר זחלי זעיר - גי.סי.בי במפורד', '2022', 'צהוב', '2024-02-13', '2023-04-29', '2023-04-29', 'סולר'),
('2397266', 'יונדאי קוריאה', '2008', 'אפור מטל', '2023-03-05', '2023-03-05', '2023-03-05', 'בנזין'),
('25201401', 'איסוזו תאילנד', '2018', 'אפור מטל', '2023-01-28', '2023-04-29', '2023-04-29', 'דיזל'),
('4845338', 'פולקסווגן מכסי', '2016', 'כסף מטלי', '2022-09-13', '2023-04-29', '2023-04-29', 'בנזין'),
('5998334', 'קרייזלר מכסיקו', '2015', 'לבן', '2022-07-25', '2023-04-29', '2023-04-29', 'דיזל טורבו'),
('61537001', 'WRANGLER UNLIM', '2019', 'לבן', '2024-04-13', '2024-04-13', '2023-04-26', 'בנזין'),
('63273801', 'אאודי', '2019', 'לבן', '2023-01-26', '2023-04-29', '2023-04-29', 'בנזין'),
('67565302', 'ב מ וו ארה״ב', '2021', 'שחור מטלי', '2022-12-26', '2023-04-29', '2023-04-29', 'בנזין'),
('80347801', 'מרכבי נוע בעמ', '2019', 'אפור', '2023-05-28', '2023-05-28', '2023-04-29', '--'),
('9348352', 'פולקסווגן גרמנ', '2014', 'אפור כהה', '2023-01-14', '2023-04-29', '2023-04-29', 'דיזל טורבו'),
('96286', ' מחפר זחלי זעיר - טרקס', '2010', 'לבן', '2023-07-03', '2023-07-03', '2023-04-29', 'סולר');

-- --------------------------------------------------------

--
-- Table structure for table `carfile`
--

CREATE TABLE `carfile` (
  `file_name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` varchar(100) NOT NULL,
  `carNumber` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carfile`
--

INSERT INTO `carfile` (`file_name`, `type`, `size`, `carNumber`, `category`) VALUES
('642c19b040ef40.41342621.pdf', 'application/pdf', '288284', '454543t6', 'ביטוח'),
('642c19b0408092.82226197.pdf', 'application/pdf', '239634', '454543t6', 'רישיון'),
('642ad02e8c76b3.14978907.pdf', 'application/pdf', '276503', '67657', 'רישיון');

-- --------------------------------------------------------

--
-- Table structure for table `checks`
--

CREATE TABLE `checks` (
  `id` int(11) NOT NULL,
  `forName` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `checkDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checks`
--

INSERT INTO `checks` (`id`, `forName`, `price`, `checkDate`) VALUES
(5000162, 'محمد اسماعيل', 45000, '2023-06-05'),
(5000163, 'محمد اسماعيل', 45000, '2023-07-05'),
(5000200, '?', 40000, '2023-05-15'),
(5000268, 'אמארה רביע', 23000, '2023-05-30'),
(5000269, 'אמארה רביע', 23000, '2023-06-30'),
(5000283, 'محمد اسماعيل', 27000, '2023-05-20'),
(5000284, 'محمد إسماعيل', 63000, '2023-07-05'),
(5000295, 'محمد إسماعيل', 61000, '2023-08-05'),
(5000309, 'فادي العمر', 47000, '2023-05-30'),
(5000328, 'סארי אספקות בע״ם', 84000, '2023-05-20'),
(5000329, 'علي ابو شتية', 15500, '2023-05-10'),
(5000330, 'مهيب أماره', 10929, '2023-07-15'),
(5000331, 'שתאי', 1200, '2023-07-15'),
(5000332, 'בישן שירותיים ניידים', 2340, '2023-05-20'),
(5000336, 'محمد إسماعيل', 90000, '2023-05-05');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `fullName` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `id` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `phone2` varchar(10) NOT NULL,
  `email` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`fullName`, `address`, `id`, `gender`, `phone`, `phone2`, `email`) VALUES
('שחר טייק', 'אורי בר און 28 אריאל', '31542129', 'זכר', '0522994208', '', ''),
('עומר שמאי הכהן', 'רחוב שטורמן10 הרצליה', '37213097', 'זכר', '', '', ''),
('פרוגקט פרו בע״מ', 'רחוב האורגים 27 אשדוד', '514867563', 'זכר', '088521444', '', 'jobs@projectpro.co.il'),
('ברזילי יוסי', 'שזר 18 הרצליה', '54010392', 'זכר', '', '', 'el_br@walla.co.il'),
('ברזילי אלישבע', 'שזר 18 הרצליה', '55111553', 'זכר', '', '', 'el_br@walla.co.il');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(50) NOT NULL,
  `fullName` varchar(128) NOT NULL,
  `job` varchar(50) NOT NULL,
  `startDate` date NOT NULL,
  `Gender` varchar(20) NOT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT 1,
  `salary` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `fullName`, `job`, `startDate`, `Gender`, `Active`, `salary`) VALUES
(1, 'حمزة', 'בנאי', '2022-10-23', 'זכר', 1, 450),
(2, 'سلطان', 'עובד שטח', '2022-10-25', 'זכר', 1, 350),
(3, 'عميد', 'עובד שטח', '2022-10-23', 'זכר', 1, 350),
(4, 'ماهر', 'עובד שטח', '2022-10-23', 'זכר', 0, 300),
(5, 'خالد السعيد', 'עובד שטח', '2022-10-23', 'זכר', 1, 450),
(6, 'عمر', 'ראש צוות', '2022-10-23', 'זכר', 1, 550),
(7, 'يوسف', 'עובד שטח', '2022-10-23', 'זכר', 1, 350),
(8, 'كمال', 'עובד שטח', '2022-10-23', 'זכר', 0, 500),
(9, 'محمود رفيق', 'עובד ותיק', '2022-10-23', 'זכר', 1, 600),
(10, 'حمودي الخالد', 'עובד שטח', '2022-10-23', 'זכר', 1, 450),
(11, 'شملولي', 'עובד ותיק', '2022-10-23', 'זכר', 1, 550),
(12, 'رافت', 'עובד שטח', '2022-10-23', 'זכר', 1, 450),
(13, 'شدوان', 'ראש צוות', '2022-10-23', 'זכר', 1, 600),
(14, 'احمد الخالد', 'ראש צוות', '2022-10-23', 'זכר', 1, 750),
(15, 'تيسير', 'עובד ותיק', '2022-10-23', 'זכר', 1, 750),
(16, 'صلاح', 'עובד שטח', '2022-11-29', 'זכר', 1, 450),
(17, 'زايد', 'עובד שטח', '2022-11-29', 'זכר', 1, 280),
(18, 'عامر', 'עובד שטח', '2022-11-29', 'זכר', 1, 450),
(19, 'عز', 'עובד שטח', '2022-11-27', 'זכר', 1, 350),
(20, 'دودو', 'עובד ותיק', '2023-03-01', 'זכר', 1, 600),
(21, 'انس', 'עובד שטח', '2023-03-01', 'זכר', 0, 450),
(22, 'شادي', 'עובד שטח', '2023-03-13', 'זכר', 1, 450),
(23, 'احمد خليلي', 'עובד שטח', '2022-12-02', 'זכר', 0, 370),
(24, 'انور', 'עובד שטח', '2023-03-07', 'זכר', 0, 300),
(25, 'زين', 'עובד שטח', '2022-11-13', 'זכר', 1, 300),
(26, 'محمد', 'עובד שטח', '2023-02-24', 'זכר', 1, 300),
(27, 'ايسر', 'עובד שטח', '2023-04-02', 'זכר', 1, 340),
(28, 'ابو جواد', 'עובד שטח', '2023-03-30', 'זכר', 1, 330);

-- --------------------------------------------------------

--
-- Table structure for table `exception`
--

CREATE TABLE `exception` (
  `id` int(11) NOT NULL,
  `projectName` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exception`
--

INSERT INTO `exception` (`id`, `projectName`, `description`, `price`) VALUES
(3, 'ברזילי יוסי וברזילי אלישבע', '55 מטר בטון רזה', 7150),
(4, 'ברזילי יוסי וברזילי אלישבע', 'בורות חלחול 6', 9900),
(5, 'ברזילי יוסי וברזילי אלישבע', 'תקרת מעלון', 2000),
(6, 'ברזילי יוסי וברזילי אלישבע', 'הפרש בטון בכלונסאות 20', 16000),
(7, 'ברזילי יוסי וברזילי אלישבע', 'איטום קירות בריכה וחומות', 4000),
(8, 'ברזילי יוסי וברזילי אלישבע', 'איטום קורות קשר 3', 2400),
(9, 'ברזילי יוסי וברזילי אלישבע', 'הפרש בטון וברזל ברצפות פיתוח 5', 11000),
(10, 'ברזילי יוסי וברזילי אלישבע', 'פינוי 2 משאיות פסולת', 3200),
(11, 'עומר שמאי הכהן', 'חלון', 4000),
(12, 'עומר שמאי הכהן', 'הריסת קיר ופינוי פסולת', 3700),
(13, 'עומר שמאי הכהן', 'הכנת פרגולות', 4500);

-- --------------------------------------------------------

--
-- Table structure for table `fixing`
--

CREATE TABLE `fixing` (
  `serialNumber` int(11) NOT NULL,
  `carNumber` varchar(20) NOT NULL,
  `price` double NOT NULL,
  `fixingDetails` text NOT NULL,
  `fixingDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fixing`
--

INSERT INTO `fixing` (`serialNumber`, `carNumber`, `price`, `fixingDetails`, `fixingDate`) VALUES
(1, '3478', 100, 'יש הרבה הערות לכתוב לצורך בדיקה', '2023-03-29');

-- --------------------------------------------------------

--
-- Table structure for table `fuel`
--

CREATE TABLE `fuel` (
  `serialNumber` int(11) NOT NULL,
  `carNumber` varchar(20) NOT NULL,
  `amount` double NOT NULL,
  `price` double NOT NULL,
  `fullDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fuel`
--

INSERT INTO `fuel` (`serialNumber`, `carNumber`, `amount`, `price`, `fullDate`) VALUES
(1, '543215', 567.8, 66.6, '2023-03-29'),
(2, '67657', 556, 20021, '2023-04-16'),
(3, '200613', 55, 100, '2023-05-05');

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `price` double NOT NULL,
  `amount` int(11) NOT NULL,
  `metrics` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`id`, `name`, `price`, `amount`, `metrics`) VALUES
(4, 'ברזל34', 200, 50004, 'קילו');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `address` varchar(128) NOT NULL,
  `startDate` date NOT NULL,
  `finishDate` date NOT NULL,
  `clientName` varchar(128) NOT NULL,
  `type` varchar(20) NOT NULL,
  `floorsNum` int(11) NOT NULL,
  `pool` tinyint(1) NOT NULL,
  `basement` tinyint(1) NOT NULL,
  `parking` tinyint(1) NOT NULL,
  `roomsNum` int(11) NOT NULL,
  `space` int(11) NOT NULL,
  `cup` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `name`, `address`, `startDate`, `finishDate`, `clientName`, `type`, `floorsNum`, `pool`, `basement`, `parking`, `roomsNum`, `space`, `cup`) VALUES
(19, 'פרוגקט פרו בע״מ', 'רחוב האורגים 27 אשדוד', '2021-09-01', '2023-04-29', 'פרוגקט פרו בע״מ', '', 0, 0, 0, 0, 0, 0, 0),
(20, 'עומר שמאי הכהן', 'רחוב שטורמן 10 הרצליה', '2022-12-05', '2023-04-29', 'עומר שמאי הכהן', '', 0, 0, 0, 0, 0, 0, 0),
(21, 'ברזילי יוסי וברזילי אלישבע', 'שזר 18 הרצליה', '2022-04-29', '2023-04-29', 'ברזילי יוסי', '', 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `projectfile`
--

CREATE TABLE `projectfile` (
  `fileName` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` varchar(100) NOT NULL,
  `projectName` varchar(128) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projectfile`
--

INSERT INTO `projectfile` (`fileName`, `type`, `size`, `projectName`, `category`) VALUES
('642e9de88e8bd0.22142256.pdf', 'application/pdf', '288284', 'c', 'הסכם'),
('642e9de88e2df3.76727787.pdf', 'application/pdf', '288284', 'c', 'הצעת מחיר'),
('642e9de88ea7a9.32280835.pdf', 'application/pdf', '288284', 'c', 'לוח זמנים'),
('642e9de88e63d0.93746690.pdf', 'application/pdf', '288284', 'c', 'תמורה'),
('642e94d3326335.83329360.pdf', 'application/pdf', '288284', 'd', 'הסכם'),
('642e94d3320266.74044671.pdf', 'application/pdf', '288284', 'd', 'הצעת מחיר'),
('642e94d332ba57.02852098.pdf', 'application/pdf', '288284', 'd', 'לוח זמנים'),
('642e94d33231e9.09469381.pdf', 'application/pdf', '288284', 'd', 'תמורה');

-- --------------------------------------------------------

--
-- Table structure for table `projectprograms`
--

CREATE TABLE `projectprograms` (
  `fileName` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` varchar(100) NOT NULL,
  `projectName` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `reportNumber` varchar(20) NOT NULL,
  `carNumber` varchar(11) NOT NULL,
  `reportDate` date NOT NULL,
  `price` double NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`reportNumber`, `carNumber`, `reportDate`, `price`, `paid`, `notes`) VALUES
('08012001', '123456789', '2023-03-29', 65.5, 1, 'אין'),
('812001', '66666', '2023-04-17', 2000, 1, 'יש הערות');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `serialNumber` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `address` varchar(120) NOT NULL,
  `id` varchar(20) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `phone2` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`serialNumber`, `name`, `address`, `id`, `email`, `phone`, `phone2`) VALUES
(1, 'בטון מואסי', 'ערערה1', '21345677', 'nrmeen@gmail.com', '0547909437', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`userName`),
  ADD UNIQUE KEY `UNIQUE` (`email`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`number`);

--
-- Indexes for table `carfile`
--
ALTER TABLE `carfile`
  ADD PRIMARY KEY (`carNumber`,`category`),
  ADD UNIQUE KEY `file_name` (`file_name`);

--
-- Indexes for table `checks`
--
ALTER TABLE `checks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fullName` (`fullName`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fullName` (`fullName`);

--
-- Indexes for table `exception`
--
ALTER TABLE `exception`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fixing`
--
ALTER TABLE `fixing`
  ADD PRIMARY KEY (`serialNumber`);

--
-- Indexes for table `fuel`
--
ALTER TABLE `fuel`
  ADD PRIMARY KEY (`serialNumber`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `projectfile`
--
ALTER TABLE `projectfile`
  ADD PRIMARY KEY (`projectName`,`category`),
  ADD UNIQUE KEY `fileName` (`fileName`);

--
-- Indexes for table `projectprograms`
--
ALTER TABLE `projectprograms`
  ADD PRIMARY KEY (`fileName`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`reportNumber`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`serialNumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exception`
--
ALTER TABLE `exception`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `fixing`
--
ALTER TABLE `fixing`
  MODIFY `serialNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fuel`
--
ALTER TABLE `fuel`
  MODIFY `serialNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `serialNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
