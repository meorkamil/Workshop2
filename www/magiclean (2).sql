-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2019 at 01:43 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `magiclean`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminId` int(11) NOT NULL,
  `Username` varchar(32) NOT NULL,
  `Password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminId`, `Username`, `Password`) VALUES
(1, 'admin', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `household`
--

CREATE TABLE `household` (
  `HouseholdId` int(11) NOT NULL,
  `Email` varchar(32) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Name` varchar(32) NOT NULL,
  `PhoneNo` varchar(12) NOT NULL,
  `Balance` decimal(7,2) NOT NULL DEFAULT 0.00,
  `Address` varchar(100) NOT NULL,
  `State` varchar(20) NOT NULL,
  `Postcode` char(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `household`
--

INSERT INTO `household` (`HouseholdId`, `Email`, `Password`, `Name`, `PhoneNo`, `Balance`, `Address`, `State`, `Postcode`) VALUES
(1, 'meorkamil97@gmail.com', '123', 'meorkamil', '0135071614', '1000.00', 'NO 197 PERSIARAN SENTOSA 3\r\nTAMAN SENTOSA PERDANA', 'PERAK', '31100'),
(3, 'asdew@hotmail.com', 'asd', 'Household Name Here', '9876543210', '38.00', '39, Jalan ABBC', 'Melaka', '75450'),
(4, '123rq@hotmail.com', 'asdee', 'omg', '123412', '0.00', '39, Jalan ABB123C', 'Melaka', '75450');

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `JobId` int(11) NOT NULL,
  `JobType` varchar(32) NOT NULL,
  `Rate` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`JobId`, `JobType`, `Rate`) VALUES
(1, 'Cleaning', '10.00'),
(2, 'Cooking', '8.00'),
(3, 'Gardening', '12.00');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `RequestId` int(11) NOT NULL,
  `HouseholdId` int(11) NOT NULL,
  `VendorId` int(11) NOT NULL,
  `JobId` int(11) NOT NULL,
  `StatusId` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Time` time NOT NULL,
  `Duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`RequestId`, `HouseholdId`, `VendorId`, `JobId`, `StatusId`, `Date`, `Time`, `Duration`) VALUES
(1, 1, 1, 2, 4, '2019-11-10', '12:15:00', 4),
(2, 1, 1, 3, 1, '2019-11-10', '11:12:00', 2),
(4, 1, 1, 1, 1, '2019-11-11', '13:25:00', 5);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `StatusId` int(11) NOT NULL,
  `StatusType` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`StatusId`, `StatusType`) VALUES
(1, 'Waiting for Pick Up'),
(2, 'Pick Up by Vendor'),
(3, 'Job In Progress'),
(4, 'Waiting Customer to do Payment'),
(5, 'Waiting Vendor to Confirm Paymen'),
(6, 'Request Done Successfully');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `VendorId` int(11) NOT NULL,
  `JobId` int(11) NOT NULL,
  `Email` varchar(32) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Name` varchar(32) NOT NULL,
  `PhoneNo` varchar(12) NOT NULL,
  `Balance` decimal(7,2) NOT NULL,
  `ProfileImage` mediumblob NOT NULL,
  `Verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`VendorId`, `JobId`, `Email`, `Password`, `Name`, `PhoneNo`, `Balance`, `ProfileImage`, `Verified`) VALUES
(1, 1, 'kami.sulaiman@gmail.com', '123', 'kamilsulaiman', '0124343180', '12.00', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `WalletId` int(11) NOT NULL,
  `HouseholdId` int(11) NOT NULL,
  `VendorId` int(11) NOT NULL,
  `Debit` decimal(7,2) NOT NULL,
  `Credit` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`WalletId`, `HouseholdId`, `VendorId`, `Debit`, `Credit`) VALUES
(7, 1, 1, '0.00', '12.00'),
(8, 1, 1, '0.00', '12.00'),
(9, 1, 1, '0.00', '50.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminId`);

--
-- Indexes for table `household`
--
ALTER TABLE `household`
  ADD PRIMARY KEY (`HouseholdId`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`JobId`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`RequestId`),
  ADD KEY `HouseholdId` (`HouseholdId`),
  ADD KEY `VendorId` (`VendorId`),
  ADD KEY `JobId` (`JobId`),
  ADD KEY `StatusId` (`StatusId`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`StatusId`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`VendorId`),
  ADD KEY `JobId` (`JobId`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`WalletId`),
  ADD KEY `HouseholdId` (`HouseholdId`),
  ADD KEY `VendorId` (`VendorId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `household`
--
ALTER TABLE `household`
  MODIFY `HouseholdId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `JobId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `RequestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `StatusId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `VendorId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `WalletId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`HouseholdId`) REFERENCES `household` (`HouseholdId`),
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`VendorId`) REFERENCES `vendor` (`VendorId`),
  ADD CONSTRAINT `request_ibfk_3` FOREIGN KEY (`JobId`) REFERENCES `job` (`JobId`),
  ADD CONSTRAINT `request_ibfk_4` FOREIGN KEY (`StatusId`) REFERENCES `status` (`StatusId`);

--
-- Constraints for table `vendor`
--
ALTER TABLE `vendor`
  ADD CONSTRAINT `vendor_ibfk_1` FOREIGN KEY (`JobId`) REFERENCES `job` (`JobId`);

--
-- Constraints for table `wallet`
--
ALTER TABLE `wallet`
  ADD CONSTRAINT `wallet_ibfk_1` FOREIGN KEY (`HouseholdId`) REFERENCES `household` (`HouseholdId`),
  ADD CONSTRAINT `wallet_ibfk_2` FOREIGN KEY (`VendorId`) REFERENCES `vendor` (`VendorId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
