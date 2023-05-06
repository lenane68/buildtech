-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: מאי 06, 2023 בזמן 07:57 AM
-- גרסת שרת: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `buildtech`
--

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `account`
--

CREATE TABLE `account` (
  `userName` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `account`
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
-- מבנה טבלה עבור טבלה `car`
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
-- הוצאת מידע עבור טבלה `car`
--

INSERT INTO `car` (`number`, `type`, `year`, `color`, `testDate`, `insuranceDate`, `careDate`, `fuelType`) VALUES
('200613', 'מחפר זחלי זעיר - גי.סי.בי במפורד', 2022, 'צהוב', '2024-02-13', '2023-04-29', '2023-04-29', 'סולר'),
('2397266', 'יונדאי קוריאה', 2008, 'אפור מטל', '2023-03-05', '2023-03-05', '2023-03-05', 'בנזין'),
('25201401', 'איסוזו תאילנד', 2018, 'אפור מטל', '2023-01-28', '2023-04-29', '2023-04-29', 'דיזל'),
('4845338', 'פולקסווגן מכסי', 2016, 'כסף מטלי', '2022-09-13', '2023-04-29', '2023-04-29', 'בנזין'),
('5998334', 'קרייזלר מכסיקו', 2015, 'לבן', '2022-07-25', '2023-04-29', '2023-04-29', 'דיזל טורבו'),
('61537001', 'WRANGLER UNLIM', 2019, 'לבן', '2024-04-13', '2024-04-13', '2023-04-26', 'בנזין'),
('63273801', 'אאודי', 2019, 'לבן', '2023-01-26', '2023-04-29', '2023-04-29', 'בנזין'),
('67565302', 'ב מ וו ארה״ב', 2021, 'שחור מטלי', '2022-12-26', '2023-04-29', '2023-04-29', 'בנזין'),
('80347801', 'מרכבי נוע בעמ', 2019, 'אפור', '2023-05-28', '2023-05-28', '2023-04-29', '--'),
('9348352', 'פולקסווגן גרמנ', 2014, 'אפור כהה', '2023-01-14', '2023-04-29', '2023-04-29', 'דיזל טורבו'),
('96286', ' מחפר זחלי זעיר - טרקס', 2010, 'לבן', '2023-07-03', '2023-07-03', '2023-04-29', 'סולר');

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `carfile`
--

CREATE TABLE `carfile` (
  `file_name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` varchar(100) NOT NULL,
  `carNumber` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `carfile`
--

INSERT INTO `carfile` (`file_name`, `type`, `size`, `carNumber`, `category`) VALUES
('642c19b040ef40.41342621.pdf', 'application/pdf', '288284', '454543t6', 'ביטוח'),
('642c19b0408092.82226197.pdf', 'application/pdf', '239634', '454543t6', 'רישיון'),
('642ad02e8c76b3.14978907.pdf', 'application/pdf', '276503', '67657', 'רישיון');

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `checks`
--

CREATE TABLE `checks` (
  `id` int(11) NOT NULL,
  `forName` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `checkDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `client`
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
-- הוצאת מידע עבור טבלה `client`
--

INSERT INTO `client` (`fullName`, `address`, `id`, `gender`, `phone`, `phone2`, `email`) VALUES
('שחר טייק', 'אורי בר און 28 אריאל', '31542129', 'זכר', '0522994208', '', ''),
('עומר שמאי הכהן', 'רחוב שטורמן10 הרצליה', '37213097', 'זכר', '', '', ''),
('פרוגקט פרו בע״מ', 'רחוב האורגים 27 אשדוד', '514867563', 'זכר', '088521444', '', 'jobs@projectpro.co.il'),
('ברזילי יוסי', 'שזר 18 הרצליה', '54010392', 'זכר', '', '', 'el_br@walla.co.il'),
('ברזילי אלישבע', 'שזר 18 הרצליה', '55111553', 'זכר', '', '', 'el_br@walla.co.il');

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `employee`
--

CREATE TABLE `employee` (
  `id` int(50) NOT NULL,
  `fullName` varchar(128) NOT NULL,
  `Address` varchar(128) NOT NULL,
  `phoneNumber` varchar(10) NOT NULL,
  `job` varchar(50) NOT NULL,
  `startDate` date NOT NULL,
  `Gender` varchar(20) NOT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT 1,
  `salary` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `employee`
--

INSERT INTO `employee` (`id`, `fullName`, `Address`, `phoneNumber`, `job`, `startDate`, `Gender`, `Active`, `salary`) VALUES
(10, 'נרמין גבארין2', 'מעלה עירון', '0548050114', 'עובד', '2023-04-14', 'female', 1, 659);

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `exception`
--

CREATE TABLE `exception` (
  `id` int(11) NOT NULL,
  `projectName` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `exception`
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
-- מבנה טבלה עבור טבלה `fixing`
--

CREATE TABLE `fixing` (
  `serialNumber` int(11) NOT NULL,
  `carNumber` varchar(20) NOT NULL,
  `price` double NOT NULL,
  `fixingDetails` text NOT NULL,
  `fixingDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `fixing`
--

INSERT INTO `fixing` (`serialNumber`, `carNumber`, `price`, `fixingDetails`, `fixingDate`) VALUES
(1, '3478', 100, 'יש הרבה הערות לכתוב לצורך בדיקה', '2023-03-29');

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `fuel`
--

CREATE TABLE `fuel` (
  `serialNumber` int(11) NOT NULL,
  `carNumber` varchar(20) NOT NULL,
  `amount` double NOT NULL,
  `price` double NOT NULL,
  `fullDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `fuel`
--

INSERT INTO `fuel` (`serialNumber`, `carNumber`, `amount`, `price`, `fullDate`) VALUES
(1, '543215', 567.8, 66.6, '2023-03-29'),
(2, '67657', 556, 20021, '2023-04-16'),
(3, '200613', 55, 100, '2023-05-05');

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `material`
--

CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `price` double NOT NULL,
  `amount` int(11) NOT NULL,
  `metrics` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `material`
--

INSERT INTO `material` (`id`, `name`, `price`, `amount`, `metrics`) VALUES
(4, 'ברזל34', 200, 50004, 'קילו');

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `project`
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
-- הוצאת מידע עבור טבלה `project`
--

INSERT INTO `project` (`id`, `name`, `address`, `startDate`, `finishDate`, `clientName`, `type`, `floorsNum`, `pool`, `basement`, `parking`, `roomsNum`, `space`, `cup`) VALUES
(19, 'פרוגקט פרו בע״מ', 'רחוב האורגים 27 אשדוד', '2021-09-01', '2023-04-29', 'פרוגקט פרו בע״מ', '', 0, 0, 0, 0, 0, 0, 0),
(20, 'עומר שמאי הכהן', 'רחוב שטורמן 10 הרצליה', '2022-12-05', '2023-04-29', 'עומר שמאי הכהן', '', 0, 0, 0, 0, 0, 0, 0),
(21, 'ברזילי יוסי וברזילי אלישבע', 'שזר 18 הרצליה', '2022-04-29', '2023-04-29', 'ברזילי יוסי', '', 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `projectfile`
--

CREATE TABLE `projectfile` (
  `fileName` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` varchar(100) NOT NULL,
  `projectName` varchar(128) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `projectfile`
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
-- מבנה טבלה עבור טבלה `projectprograms`
--

CREATE TABLE `projectprograms` (
  `fileName` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` varchar(100) NOT NULL,
  `projectName` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `report`
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
-- הוצאת מידע עבור טבלה `report`
--

INSERT INTO `report` (`reportNumber`, `carNumber`, `reportDate`, `price`, `paid`, `notes`) VALUES
('08012001', '123456789', '2023-03-29', 65.5, 1, 'אין'),
('812001', '66666', '2023-04-17', 2000, 1, 'יש הערות');

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `supplier`
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
-- הוצאת מידע עבור טבלה `supplier`
--

INSERT INTO `supplier` (`serialNumber`, `name`, `address`, `id`, `email`, `phone`, `phone2`) VALUES
(1, 'בטון מואסי', 'ערערה1', '21345677', 'nrmeen@gmail.com', '0547909437', '');

--
-- Indexes for dumped tables
--

--
-- אינדקסים לטבלה `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`userName`),
  ADD UNIQUE KEY `UNIQUE` (`email`);

--
-- אינדקסים לטבלה `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`number`);

--
-- אינדקסים לטבלה `carfile`
--
ALTER TABLE `carfile`
  ADD PRIMARY KEY (`carNumber`,`category`),
  ADD UNIQUE KEY `file_name` (`file_name`);

--
-- אינדקסים לטבלה `checks`
--
ALTER TABLE `checks`
  ADD PRIMARY KEY (`id`);

--
-- אינדקסים לטבלה `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fullName` (`fullName`);

--
-- אינדקסים לטבלה `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fullName` (`fullName`);

--
-- אינדקסים לטבלה `exception`
--
ALTER TABLE `exception`
  ADD PRIMARY KEY (`id`);

--
-- אינדקסים לטבלה `fixing`
--
ALTER TABLE `fixing`
  ADD PRIMARY KEY (`serialNumber`);

--
-- אינדקסים לטבלה `fuel`
--
ALTER TABLE `fuel`
  ADD PRIMARY KEY (`serialNumber`);

--
-- אינדקסים לטבלה `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- אינדקסים לטבלה `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- אינדקסים לטבלה `projectfile`
--
ALTER TABLE `projectfile`
  ADD PRIMARY KEY (`projectName`,`category`),
  ADD UNIQUE KEY `fileName` (`fileName`);

--
-- אינדקסים לטבלה `projectprograms`
--
ALTER TABLE `projectprograms`
  ADD PRIMARY KEY (`fileName`);

--
-- אינדקסים לטבלה `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`reportNumber`);

--
-- אינדקסים לטבלה `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`serialNumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
