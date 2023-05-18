-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: מאי 18, 2023 בזמן 08:43 AM
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
-- מבנה טבלה עבור טבלה `barchart`
--

CREATE TABLE `barchart` (
  `id` int(11) NOT NULL,
  `year` varchar(20) NOT NULL,
  `sale` varchar(20) NOT NULL,
  `expenses` varchar(20) NOT NULL,
  `profit` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `barchart`
--

INSERT INTO `barchart` (`id`, `year`, `sale`, `expenses`, `profit`) VALUES
(1, '2014', '1000', '400', '200'),
(2, '2015', '1170', '460', '250'),
(3, '2016', '660', '100', '300'),
(4, '2017', '1030', '540', '350');

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

--
-- הוצאת מידע עבור טבלה `checks`
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
(5000331, 'שמאי', 1200, '2023-07-15'),
(5000332, 'בישן שירותיים ניידים', 2340, '2023-05-20'),
(5000336, 'محمد إسماعيل', 90000, '2023-05-05');

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
-- מבנה טבלה עבור טבלה `data_location`
--

CREATE TABLE `data_location` (
  `id` int(11) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lon` float(10,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- הוצאת מידע עבור טבלה `data_location`
--

INSERT INTO `data_location` (`id`, `desc`, `lat`, `lon`) VALUES
(1, 'Ibukota Provinsi Aceh', 5.550176, 95.319260),
(2, 'Ibukota Kab.Aceh Jaya', 4.727890, 95.601372),
(3, 'Ibukota Abdya', 3.818570, 96.831841),
(4, 'Ibukota Kotamadya Langsa', 4.476020, 97.952446),
(5, 'Ibukota Kotamadya Sabang', 5.909284, 95.304741);

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `employee`
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
-- הוצאת מידע עבור טבלה `employee`
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
-- מבנה טבלה עבור טבלה `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `price` double NOT NULL,
  `date` date NOT NULL,
  `details` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `projectId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `expense`
--

INSERT INTO `expense` (`id`, `price`, `date`, `details`, `category`, `projectId`) VALUES
(1, 12000, '2023-05-14', 'עבור בנזין', '', 0),
(2, 13567, '2023-05-23', 'עבור שיבוצים', '', 0),
(3, 10000, '2023-04-03', 'לביטוח רכב', '', 0);

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
-- מבנה טבלה עבור טבלה `income`
--

CREATE TABLE `income` (
  `id` int(11) NOT NULL,
  `price` double NOT NULL,
  `date` date NOT NULL,
  `details` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `projectId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `income`
--

INSERT INTO `income` (`id`, `price`, `date`, `details`, `category`, `projectId`) VALUES
(1, 1500, '2023-05-04', 'עבור בניית קיר למשפחה', '', 0),
(2, 13567, '2023-05-23', 'כ', '', 0),
(3, 454545, '2023-05-16', 'ffrgre', 'פרויקטים', 20);

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `markers`
--

CREATE TABLE `markers` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `address` varchar(80) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `type` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `markers`
--

INSERT INTO `markers` (`id`, `name`, `address`, `lat`, `lng`, `type`) VALUES
(1, 'Love.Fish', '580 Darling Street, Rozelle, NSW', -33.861034, 151.171936, 'restaurant'),
(2, 'Young Henrys', '76 Wilford Street, Newtown, NSW', -33.898113, 151.174469, 'bar'),
(3, 'Hunter Gatherer', 'Greenwood Plaza, 36 Blue St, North Sydney NSW', -33.840282, 151.207474, 'bar'),
(4, 'The Potting Shed', '7A, 2 Huntley Street, Alexandria, NSW', -33.910751, 151.194168, 'bar'),
(5, 'Nomad', '16 Foster Street, Surry Hills, NSW', -33.879917, 151.210449, 'bar'),
(6, 'Three Blue Ducks', '43 Macpherson Street, Bronte, NSW', -33.906357, 151.263763, 'restaurant'),
(7, 'Single Origin Roasters', '60-64 Reservoir Street, Surry Hills, NSW', -33.881123, 151.209656, 'restaurant'),
(8, 'Red Lantern', '60 Riley Street, Darlinghurst, NSW', -33.874737, 151.215530, 'restaurant');

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `markers1`
--

CREATE TABLE `markers1` (
  `id` int(11) NOT NULL,
  `projectNumber` int(11) NOT NULL,
  `address` varchar(80) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `markers1`
--

INSERT INTO `markers1` (`id`, `projectNumber`, `address`, `lat`, `lng`) VALUES
(1, 19, '580 Darling Street, Rozelle, NSW', -33.861034, 151.171936),
(6, 20, '43 Macpherson Street, Bronte, NSW', -33.906357, 151.263763),
(8, 21, '60 Riley Street, Darlinghurst, NSW', -33.874737, 151.215530);

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
-- מבנה טבלה עבור טבלה `payments`
--

CREATE TABLE `payments` (
  `serialNumber` int(11) NOT NULL,
  `projectNumber` int(11) NOT NULL,
  `price` double NOT NULL,
  `date` date NOT NULL,
  `forWhat` text NOT NULL,
  `isBill` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `payments`
--

INSERT INTO `payments` (`serialNumber`, `projectNumber`, `price`, `date`, `forWhat`, `isBill`) VALUES
(1, 20, 2000, '2023-05-12', 'בניית קיר', 0),
(2, 20, 2000, '2023-05-16', 'דיפון כלונסאות', 0),
(3, 20, 1320, '2023-05-16', 'הריסת קיר', 0),
(4, 20, 3434, '2023-05-16', 'אאא', 0),
(5, 20, 20021, '2023-05-23', 'ffef', 0),
(6, 20, 1500, '2023-05-25', 'erff', 0),
(7, 20, 2000, '2023-06-08', 'tr', 0),
(8, 20, 999, '2023-06-10', '76uyt', 0);

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
  `cup` int(11) NOT NULL,
  `totalPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `project`
--

INSERT INTO `project` (`id`, `name`, `address`, `startDate`, `finishDate`, `clientName`, `type`, `floorsNum`, `pool`, `basement`, `parking`, `roomsNum`, `space`, `cup`, `totalPrice`) VALUES
(19, 'פרוגקט פרו בע״מ', 'רחוב האורגים 27 אשדוד', '2021-09-01', '2023-04-29', 'פרוגקט פרו בע״מ', '', 0, 0, 0, 0, 0, 0, 0, 0),
(20, 'אחווה 6', 'רחוב שטורמן 10 הרצליה', '2022-12-05', '2023-04-29', 'עומר שמאי הכהן', '', 0, 0, 0, 0, 0, 0, 0, 1230000),
(21, 'יפתח 10', 'שזר 18 הרצליה', '2022-04-29', '2023-04-29', 'ברזילי יוסי וברזילי אלישבע', '', 0, 0, 0, 0, 0, 0, 0, 1070000);

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
-- מבנה טבלה עבור טבלה `projectstep`
--

CREATE TABLE `projectstep` (
  `id` int(11) NOT NULL,
  `projectId` int(11) NOT NULL,
  `projectsPercent` double NOT NULL,
  `description` text NOT NULL,
  `payment` varchar(50) NOT NULL,
  `finish` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `projectstep`
--

INSERT INTO `projectstep` (`id`, `projectId`, `projectsPercent`, `description`, `payment`, `finish`) VALUES
(1, 20, 8, 'גמר דיפון', 'שולם', 'נגמר'),
(2, 20, 5, 'גמר חפירה', 'שולם', 'נגמר'),
(3, 20, 10, 'יציקת ריצפת מרתף', 'שולם', 'נגמר'),
(4, 20, 7, 'גמר קירות מרתף', 'שולם', 'נגמר'),
(5, 20, 7, 'גמר ריצפת קומת קרקע ומילוי חוזר', 'שולם', 'נגמר'),
(6, 20, 5, 'קירות קומת קרקע', 'שולם 80%', 'נגמר'),
(7, 20, 7, 'גמר רצפה קומה א', 'לא שולם', 'לא בוצע'),
(8, 20, 7, 'גמר קירות קומה א', 'לא שולם', 'לא בוצע'),
(9, 20, 6, 'גמר תקרה קומה א (גג)', 'שולם חצי', 'בעבודה'),
(10, 20, 5, 'גמר מחיצות פנים', 'לא שולם', 'לא בוצע'),
(11, 20, 5, 'גמר משטחי פיתוח', 'לא שולם', 'בעבודה'),
(12, 20, 10, 'גמר חומות, חניה ופילרים', 'שולם 75%', 'בעבודה'),
(13, 20, 8, 'גמר בריכה', 'שולם', 'נגמר'),
(14, 20, 10, 'גמר סופי לפי תוכניות, ניקיון השטח ומסירת השלד', 'לא שולם', 'לא בוצע');

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

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `description` text NOT NULL,
  `done` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `tasks`
--

INSERT INTO `tasks` (`id`, `date`, `description`, `done`) VALUES
(1, '2023-05-14', 'כתיבת דרישת תשלום למשפחת ברזילי', 0),
(2, '2023-05-14', 'כתיבת חשבונית לשמאי', 0),
(25, '2023-05-17', 'הפקת דו\"ח', 1);

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
-- אינדקסים לטבלה `barchart`
--
ALTER TABLE `barchart`
  ADD PRIMARY KEY (`id`);

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
-- אינדקסים לטבלה `data_location`
--
ALTER TABLE `data_location`
  ADD PRIMARY KEY (`id`);

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
-- אינדקסים לטבלה `expense`
--
ALTER TABLE `expense`
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
-- אינדקסים לטבלה `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`id`);

--
-- אינדקסים לטבלה `markers`
--
ALTER TABLE `markers`
  ADD PRIMARY KEY (`id`);

--
-- אינדקסים לטבלה `markers1`
--
ALTER TABLE `markers1`
  ADD PRIMARY KEY (`id`);

--
-- אינדקסים לטבלה `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- אינדקסים לטבלה `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`serialNumber`);

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
-- אינדקסים לטבלה `projectstep`
--
ALTER TABLE `projectstep`
  ADD PRIMARY KEY (`id`);

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
-- אינדקסים לטבלה `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barchart`
--
ALTER TABLE `barchart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `data_location`
--
ALTER TABLE `data_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `exception`
--
ALTER TABLE `exception`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `markers`
--
ALTER TABLE `markers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `markers1`
--
ALTER TABLE `markers1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `serialNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `projectstep`
--
ALTER TABLE `projectstep`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `serialNumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
