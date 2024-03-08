-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 04, 2023 at 05:20 AM
-- Server version: 8.0.31
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sr`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

DROP TABLE IF EXISTS `auth`;
CREATE TABLE IF NOT EXISTS `auth` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `auth`
--

INSERT INTO `auth` (`id`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `inventory_id` varchar(100) NOT NULL,
  `quantity` int NOT NULL,
  `cashier_id` int NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=MyISAM AUTO_INCREMENT=178 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `inventory_id`, `quantity`, `cashier_id`) VALUES
(177, '1c4s6q', 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `cashier`
--

DROP TABLE IF EXISTS `cashier`;
CREATE TABLE IF NOT EXISTS `cashier` (
  `cashier_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `first_name` varchar(225) NOT NULL,
  `last_name` varchar(225) NOT NULL,
  `middle_name` varchar(225) NOT NULL,
  `address` varchar(225) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`cashier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cashier`
--

INSERT INTO `cashier` (`cashier_id`, `username`, `password`, `first_name`, `last_name`, `middle_name`, `address`, `phone`, `status`) VALUES
(4, 'fusingan_anj', '6ac2470ed8ccf204fd5ff89b32a355cf', 'anjelly', 'fusingan', 'Nalla', 'dasdasdas', '09301791280', 0),
(5, 'decosta_joh', '6ac2470ed8ccf204fd5ff89b32a355cf', 'john rey', 'decosta', 'asf', 'fdsfsdfds', '0930715645', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cashonhand`
--

DROP TABLE IF EXISTS `cashonhand`;
CREATE TABLE IF NOT EXISTS `cashonhand` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cashier_id` int NOT NULL,
  `amount` double(50,2) NOT NULL,
  `date_added` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cashonhand`
--

INSERT INTO `cashonhand` (`id`, `cashier_id`, `amount`, `date_added`) VALUES
(5, 4, 5000.00, '2023-06-02');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `description`) VALUES
(1, 'Pens'),
(3, 'Paper');

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

DROP TABLE IF EXISTS `discount`;
CREATE TABLE IF NOT EXISTS `discount` (
  `discount_id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `percent` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`discount_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`discount_id`, `description`, `percent`, `status`) VALUES
(2, 'Student Permit', 20, 0),
(3, 'Senior Citizen', 20, 0);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `inventory_id` varchar(100) NOT NULL,
  `category_id` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int NOT NULL,
  `price` double(50,2) NOT NULL,
  `status` int NOT NULL,
  `photo` varchar(200) NOT NULL,
  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`inventory_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `category_id`, `description`, `item_name`, `quantity`, `price`, `status`, `photo`, `date_updated`) VALUES
('1c4s6q', 3, '1.5mm ', 'Cartolina', 15, 123.23, 0, '../static/productimage/Cartolina1685345006.png', '2023-05-29 05:03:00'),
('rb4yq1', 1, '1 inches tickers', 'Wrist Rulerc', 0, 234.00, 0, '../static/productimage/Wrist Ruler1685345319.png', '2023-05-29 03:51:31'),
('8tenlc', 1, 'Small', 'Mongol 3', 36, 23.00, 0, '../static/images/no-image.png', '2023-05-29 08:07:43'),
('z017cw', 1, '24 colors', 'crayola', 970, 12.23, 0, '../static/productimage/crayola1685347835.png', '2023-05-29 08:10:36'),
('qpick6', 3, 'Black color', 'Pilot Ballpen', 0, 1023.00, 0, '../static/productimage/Pilot Ballpen1685345202.png', '2023-05-29 03:58:26'),
('1tk9j5', 3, 'gfdgdf', 'gfgfd', 0, 12.00, 0, '../static/images/no-image.png', '2023-06-04 02:47:33');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `s_id` int NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(200) NOT NULL,
  `item_name` varchar(200) NOT NULL,
  `description` varchar(220) NOT NULL,
  `quantity` varchar(20) NOT NULL,
  `total_price` double(50,2) NOT NULL,
  `base_price` double(50,2) NOT NULL,
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`s_id`, `invoice_no`, `item_name`, `description`, `quantity`, `total_price`, `base_price`) VALUES
(34, 'INV-00003', 'Wrist Rulerc', '1 inches tickers', '3', 702.00, 234.00),
(33, 'INV-00002', 'Wrist Rulerc', '1 inches tickers', '5', 1170.00, 234.00),
(32, 'INV-00001', 'Wrist Rulerc', '1 inches tickers', '10', 2340.00, 234.00),
(35, 'INV-00004', 'Cartolina', '1.5mm ', '2', 246.46, 123.23),
(36, 'INV-00004', 'Wrist Rulerc', '1 inches tickers', '2', 468.00, 234.00),
(37, 'INV-00005', 'Wrist Rulerc', '1 inches tickers', '10', 2340.00, 234.00),
(38, 'INV-00007', 'Wrist Rulerc', '1 inches tickers', '1', 234.00, 234.00),
(39, 'INV-00008', 'Cartolina', '1.5mm ', '1', 123.23, 123.23),
(40, 'INV-00009', 'Cartolina', '1.5mm ', '1', 123.23, 123.23),
(41, 'INV-00010', 'Pilot Ballpen', 'Black color', '1', 1023.00, 1023.00),
(42, 'INV-00012', 'Cartolina', '1.5mm ', '2', 246.46, 123.23),
(43, 'INV-00014', 'Cartolina', '1.5mm ', '2', 246.46, 123.23),
(44, 'INV-00015', 'Cartolina', '1.5mm ', '1', 123.23, 123.23),
(45, 'INV-00016', 'Cartolina', '1.5mm ', '20', 2464.60, 123.23),
(46, 'INV-00017', 'Mongol 3', 'Small', '2', 46.00, 23.00),
(47, 'INV-00017', 'Cartolina', '1.5mm ', '1', 123.23, 123.23),
(48, 'INV-00018', 'Cartolina', '1.5mm ', '10', 1232.30, 123.23),
(49, 'INV-00018', 'Mongol 3', 'Small', '2', 46.00, 23.00),
(50, 'INV-00019', 'Cartolina', '1.5mm ', '1', 123.23, 123.23),
(51, 'INV-00020', 'Cartolina', '1.5mm ', '1', 123.23, 123.23),
(52, 'INV-00021', 'Mongol 3', 'Small', '1', 23.00, 23.00),
(53, 'INV-00022', 'Cartolina', '1.5mm ', '1', 123.23, 123.23),
(54, 'INV-00023', 'Cartolina', '1.5mm ', '10', 1232.30, 123.23),
(55, 'INV-00024', 'Cartolina', '1.5mm ', '1', 123.23, 123.23),
(56, 'INV-00027', 'Cartolina', '1.5mm ', '1', 123.23, 123.23),
(57, 'INV-00027', 'Wrist Rulerc', '1 inches tickers', '2', 468.00, 234.00),
(58, 'INV-00028', 'Cartolina', '1.5mm ', '2', 246.46, 123.23),
(59, 'INV-00028', 'Wrist Rulerc', '1 inches tickers', '1', 234.00, 234.00),
(60, 'INV-00029', 'Cartolina', '1.5mm ', '2', 246.46, 123.23),
(61, 'INV-00029', 'Mongol 3', 'Small', '3', 69.00, 23.00),
(62, 'INV-00030', 'Cartolina', '1.5mm ', '20', 2464.60, 123.23),
(63, 'INV-00030', 'Mongol 3', 'Small', '1', 23.00, 23.00),
(64, 'INV-00031', 'Cartolina', '1.5mm ', '1', 123.23, 123.23),
(65, 'INV-00031', 'Wrist Rulerc', '1 inches tickers', '1', 234.00, 234.00),
(66, 'INV-00031', 'Mongol 3', 'Small', '2', 46.00, 23.00),
(67, 'INV-00032', 'Cartolina', '1.5mm ', '2', 246.46, 123.23),
(68, 'INV-00032', 'Mongol 3', 'Small', '3', 69.00, 23.00),
(69, 'INV-00033', 'crayola', '24 colors', '30', 366.90, 12.23),
(70, 'INV-00033', 'Cartolina', '1.5mm ', '10', 1232.30, 123.23),
(71, 'INV-00034', 'Cartolina', '1.5mm ', '2', 246.46, 123.23),
(72, 'INV-00035', 'Cartolina', '1.5mm ', '1', 123.23, 123.23);

-- --------------------------------------------------------

--
-- Table structure for table `sales_payment`
--

DROP TABLE IF EXISTS `sales_payment`;
CREATE TABLE IF NOT EXISTS `sales_payment` (
  `invoice_no` varchar(200) NOT NULL,
  `cash_tendered` double(50,2) NOT NULL,
  `subtotal` double(50,2) NOT NULL,
  `total` double(50,2) NOT NULL,
  `change_payment` double(50,2) NOT NULL,
  `discount` varchar(100) NOT NULL,
  `cashier_assign` int NOT NULL,
  `date_transaction` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`invoice_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales_payment`
--

INSERT INTO `sales_payment` (`invoice_no`, `cash_tendered`, `subtotal`, `total`, `change_payment`, `discount`, `cashier_assign`, `date_transaction`) VALUES
('INV-00003', 5000.00, 702.00, 702.00, 4298.00, '0%', 4, '2023-06-03 09:54:06'),
('INV-00002', 10000.00, 1170.00, 1170.00, 8830.00, '0%', 4, '2023-06-03 09:35:27'),
('INV-00001', 5000.00, 2340.00, 2340.00, 2660.00, '0%', 4, '2023-06-02 09:34:33'),
('INV-00004', 1000.00, 714.46, 714.46, 285.54, '0%', 4, '2023-06-02 14:27:23'),
('INV-00005', 5000.00, 2340.00, 2340.00, 2660.00, '0%', 4, '2023-06-03 06:34:36'),
('INV-00006', 5000.00, 2340.00, 2340.00, 2660.00, '0%', 4, '2023-06-03 06:34:38'),
('INV-00007', 500.00, 234.00, 234.00, 266.00, '0%', 4, '2023-06-03 06:35:38'),
('INV-00008', 1200.00, 123.23, 123.23, 1076.77, '0%', 4, '2023-06-03 06:37:59'),
('INV-00009', 1000.00, 123.23, 123.23, 876.77, '0%', 4, '2023-06-03 06:39:07'),
('INV-00010', 10000.00, 1023.00, 1023.00, 8977.00, '0%', 4, '2023-06-03 06:40:30'),
('INV-00011', 10000.00, 1023.00, 1023.00, 8977.00, '0%', 4, '2023-06-03 06:40:36'),
('INV-00012', 300.00, 246.46, 246.46, 53.54, '0%', 4, '2023-06-03 06:44:13'),
('INV-00013', 300.00, 246.46, 246.46, 53.54, '0%', 4, '2023-06-03 06:44:15'),
('INV-00014', 500.00, 246.46, 246.46, 253.54, '0%', 4, '2023-06-03 06:50:51'),
('INV-00015', 200.00, 123.23, 123.23, 76.77, '0%', 4, '2023-06-03 06:51:20'),
('INV-00016', 3000.00, 2464.60, 2464.60, 535.40, '0%', 4, '2023-06-03 06:53:06'),
('INV-00017', 300.00, 169.23, 169.23, 130.77, '0%', 4, '2023-06-03 06:53:50'),
('INV-00018', 5000.00, 1278.30, 1278.30, 3721.70, '0%', 4, '2023-06-03 07:00:44'),
('INV-00019', 200.00, 123.23, 123.23, 76.77, '0%', 4, '2023-06-03 07:01:17'),
('INV-00020', 200.00, 123.23, 123.23, 76.77, '0%', 4, '2023-06-03 07:02:12'),
('INV-00021', 30.00, 23.00, 23.00, 7.00, '0%', 4, '2023-06-03 07:02:39'),
('INV-00022', 500.00, 123.23, 123.23, 376.77, '0%', 4, '2023-06-03 07:05:47'),
('INV-00023', 2000.00, 1232.30, 1232.30, 767.70, '0%', 4, '2023-06-03 07:06:21'),
('INV-00024', 200.00, 123.23, 123.23, 76.77, '0%', 4, '2023-06-03 07:06:38'),
('INV-00025', 200.00, 0.00, 0.00, 200.00, '0%', 4, '2023-06-03 07:06:54'),
('INV-00026', 200.00, 0.00, 0.00, 200.00, '0%', 4, '2023-06-03 07:07:05'),
('INV-00027', 1000.00, 591.23, 591.23, 408.77, '0%', 4, '2023-06-03 07:28:44'),
('INV-00028', 3000.00, 480.46, 480.46, 2519.54, '0%', 4, '2023-06-03 07:29:07'),
('INV-00029', 400.00, 315.46, 315.46, 84.54, '0%', 4, '2023-06-03 07:29:24'),
('INV-00030', 3000.00, 2487.60, 2487.60, 512.40, '0%', 4, '2023-06-03 07:31:48'),
('INV-00031', 450.00, 403.23, 403.23, 46.77, '0%', 4, '2023-06-03 07:32:25'),
('INV-00032', 400.00, 315.46, 315.46, 84.54, '0%', 4, '2023-06-03 07:32:47'),
('INV-00033', 2000.00, 1599.20, 1599.20, 400.80, '0%', 4, '2023-06-03 07:39:51'),
('INV-00034', 500.00, 246.46, 246.46, 253.54, '0%', 4, '2023-06-03 12:42:07'),
('INV-00035', 200.00, 123.23, 123.23, 76.77, '0%', 4, '2023-06-03 13:13:32'),
('INV-00036', 123123.00, 0.00, 0.00, 123123.00, '0%', 4, '2023-06-04 03:21:25'),
('INV-00037', 2.00, 0.00, 0.00, 2.00, '0%', 4, '2023-06-04 03:29:41');

-- --------------------------------------------------------

--
-- Table structure for table `sales_reconcilation`
--

DROP TABLE IF EXISTS `sales_reconcilation`;
CREATE TABLE IF NOT EXISTS `sales_reconcilation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cashier_id` int NOT NULL,
  `cash_drawer` double(50,2) NOT NULL,
  `balance` double(50,2) NOT NULL,
  `status` int NOT NULL,
  `date_issued` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
CREATE TABLE IF NOT EXISTS `stock` (
  `stock_id` int NOT NULL AUTO_INCREMENT,
  `supplier_id` varchar(110) NOT NULL,
  `inventory_id` varchar(110) NOT NULL,
  `quantity` varchar(50) NOT NULL,
  `later_quantity` varchar(50) NOT NULL,
  `date_delivered` date NOT NULL,
  PRIMARY KEY (`stock_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock_id`, `supplier_id`, `inventory_id`, `quantity`, `later_quantity`, `date_delivered`) VALUES
(3, '2', '1c4s6q', '50', '0', '2023-06-01'),
(4, '2', '1c4s6q', '100', '1', '2023-06-03'),
(5, '2', '8tenlc', '50', '0', '2023-06-03'),
(6, '2', 'z017cw', '1000', '0', '2023-06-03');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `supplier_id` int NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(200) NOT NULL,
  `supplier_description` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`supplier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `supplier_description`, `phone`, `address`, `status`, `date_added`) VALUES
(2, 'supplier 1x', 'gfdgfd', '09301791280', 'gfdgfd', 0, '2023-06-01 05:55:02');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
