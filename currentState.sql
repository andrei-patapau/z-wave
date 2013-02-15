-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2012 at 01:21 AM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `currentState`
--

-- --------------------------------------------------------

--
-- Table structure for table `associations`
--

CREATE TABLE IF NOT EXISTS `associations` (
  `id` tinyint(2) NOT NULL,
  `reloadNames` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `associations`
--

INSERT INTO `associations` (`id`, `reloadNames`) VALUES
(1, '5bb5b3fd701ad4b8554f88ac8d996f0f');

-- --------------------------------------------------------

--
-- Table structure for table `command_classes`
--

CREATE TABLE IF NOT EXISTS `command_classes` (
  `node_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`node_id`,`id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `command_classes`
--

INSERT INTO `command_classes` (`node_id`, `id`, `name`) VALUES
(1, 33, 'COMMAND_CLASS_CONTROLLER_REPLICATION'),
(10, 38, 'COMMAND_CLASS_SWITCH_MULTILEVEL'),
(10, 39, 'COMMAND_CLASS_SWITCH_ALL'),
(10, 114, 'COMMAND_CLASS_MANUFACTURER_SPECIFIC'),
(10, 115, 'COMMAND_CLASS_POWERLEVEL'),
(10, 119, 'COMMAND_CLASS_NODE_NAMING'),
(10, 130, 'COMMAND_CLASS_HAIL'),
(10, 133, 'COMMAND_CLASS_ASSOCIATION'),
(10, 134, 'COMMAND_CLASS_VERSION'),
(12, 37, 'COMMAND_CLASS_SWITCH_BINARY'),
(12, 39, 'COMMAND_CLASS_SWITCH_ALL'),
(12, 112, 'COMMAND_CLASS_CONFIGURATION'),
(12, 114, 'COMMAND_CLASS_MANUFACTURER_SPECIFIC'),
(12, 115, 'COMMAND_CLASS_POWERLEVEL'),
(12, 117, 'COMMAND_CLASS_PROTECTION'),
(12, 119, 'COMMAND_CLASS_NODE_NAMING'),
(12, 134, 'COMMAND_CLASS_VERSION');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `name`) VALUES
(5, 'Bedroom'),
(10, 'Kitchen');

-- --------------------------------------------------------

--
-- Table structure for table `home`
--

CREATE TABLE IF NOT EXISTS `home` (
  `id` int(1) NOT NULL,
  `home_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `md5_hash_of_xml` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `server_1` tinyint(1) NOT NULL DEFAULT '1',
  `server_1_exit` tinyint(4) NOT NULL DEFAULT '0',
  `reload_nodes` tinyint(1) NOT NULL DEFAULT '1',
  `server_2` tinyint(11) NOT NULL DEFAULT '1',
  `xml_remove` tinyint(11) NOT NULL DEFAULT '0',
  `nodes_md5_db` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT 'NONE',
  PRIMARY KEY (`home_id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `home`
--

INSERT INTO `home` (`id`, `home_id`, `md5_hash_of_xml`, `server_1`, `server_1_exit`, `reload_nodes`, `server_2`, `xml_remove`, `nodes_md5_db`) VALUES
(1, '0x016a099e', '964907586d4d02a7e5497deb35fc642a', 1, 0, 0, 1, 0, 'ab0e0db42e016202d9db498efe0debde');

-- --------------------------------------------------------

--
-- Table structure for table `nodes`
--

CREATE TABLE IF NOT EXISTS `nodes` (
  `node_id` int(11) NOT NULL,
  `manufacturer_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT FOUND',
  `product_type_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT FOUND',
  `product_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT FOUND',
  `manifacturer_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT FOUND',
  `product_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT FOUND',
  `isON` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT FOUND',
  `level` int(11) NOT NULL DEFAULT '0',
  `node_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT DEFINED',
  `rules` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nodes`
--

INSERT INTO `nodes` (`node_id`, `manufacturer_id`, `product_type_id`, `product_id`, `manifacturer_name`, `product_name`, `isON`, `level`, `node_name`, `rules`) VALUES
(1, '0086', '0002', '0001', 'Aeon Labs', 'Z-Stick S2', 'False', 0, 'NOT DEFINED', 0),
(10, '001d', '0401', '0209', 'Leviton', 'VRI06-1LX Multilevel Scene Switch', 'False', 0, 'NOT DEFINED', 0),
(12, '0063', '5252', '3530', 'GE', 'NOT FOUND', 'False', 0, 'NOT DEFINED', 0);

-- --------------------------------------------------------

--
-- Table structure for table `nodes_in_group`
--

CREATE TABLE IF NOT EXISTS `nodes_in_group` (
  `group_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`node_id`),
  KEY `node_id` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nodes_in_group`
--

INSERT INTO `nodes_in_group` (`group_id`, `node_id`) VALUES
(10, 1),
(10, 10),
(5, 12);

-- --------------------------------------------------------

--
-- Table structure for table `node_names`
--

CREATE TABLE IF NOT EXISTS `node_names` (
  `node_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT DEFINED',
  PRIMARY KEY (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `node_names`
--

INSERT INTO `node_names` (`node_id`, `name`) VALUES
(1, 'Controller'),
(10, 'Ceiling Center'),
(12, 'Kaboom PC');

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE IF NOT EXISTS `rules` (
  `id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `if_nid` tinyint(4) NOT NULL,
  `if_isON` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `if_minLevel` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `if_maxLevel` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `then_nid` tinyint(4) NOT NULL,
  `then_isON` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `then_Level` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `index` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index` (`index`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `schedulesEveryDay`
--

CREATE TABLE IF NOT EXISTS `schedulesEveryDay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `timeHH` tinyint(4) NOT NULL,
  `timeMM` tinyint(4) NOT NULL,
  `timeAMPM` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `nid` tinyint(4) NOT NULL,
  `level` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `isON` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `dateExecLast` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `schedulesEveryDay`
--

INSERT INTO `schedulesEveryDay` (`id`, `title`, `timeHH`, `timeMM`, `timeAMPM`, `active`, `nid`, `level`, `isON`, `dateExecLast`) VALUES
(5, '', 6, 24, 'pm', 1, 10, '0', '-', '07/30/2012');

-- --------------------------------------------------------

--
-- Table structure for table `schedulesInterval`
--

CREATE TABLE IF NOT EXISTS `schedulesInterval` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `timeHH` tinyint(4) NOT NULL,
  `timeMM` tinyint(4) NOT NULL,
  `timeAMPM` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `timeHzDD` tinyint(4) NOT NULL,
  `timeHzHH` tinyint(4) NOT NULL,
  `timeHzMM` tinyint(4) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `nid` tinyint(4) NOT NULL,
  `level` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `isON` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `dateTime` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `schedulesInterval`
--

INSERT INTO `schedulesInterval` (`id`, `title`, `timeHH`, `timeMM`, `timeAMPM`, `date`, `timeHzDD`, `timeHzHH`, `timeHzMM`, `active`, `nid`, `level`, `isON`, `dateTime`) VALUES
(20, '', 1, 22, 'am', '07/31/2012', 0, 0, 1, 1, 10, '0', '-', '21/1/07/31/2012');

-- --------------------------------------------------------

--
-- Table structure for table `schedulesOnce`
--

CREATE TABLE IF NOT EXISTS `schedulesOnce` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `timeHH` tinyint(4) NOT NULL,
  `timeMM` tinyint(4) NOT NULL,
  `timeAMPM` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `remove` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `nid` tinyint(4) NOT NULL,
  `level` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `isON` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedulesWeekly`
--

CREATE TABLE IF NOT EXISTS `schedulesWeekly` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `timeHH` tinyint(4) NOT NULL,
  `timeMM` tinyint(4) NOT NULL,
  `timeAMPM` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `mon` tinyint(1) NOT NULL,
  `tue` tinyint(1) NOT NULL,
  `wed` tinyint(1) NOT NULL,
  `thu` tinyint(1) NOT NULL,
  `fri` tinyint(1) NOT NULL,
  `sat` tinyint(1) NOT NULL,
  `sun` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `nid` tinyint(4) NOT NULL,
  `level` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `isON` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `dateExecLast` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `schedulesWeekly`
--

INSERT INTO `schedulesWeekly` (`id`, `title`, `timeHH`, `timeMM`, `timeAMPM`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`, `active`, `nid`, `level`, `isON`, `dateExecLast`) VALUES
(4, '', 10, 1, 'pm', 1, 0, 0, 0, 0, 0, 0, 1, 10, '47', '-', '07/30/2012');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`) VALUES
('root', '63a9f0ea7bb98050796b649e85481845');

-- --------------------------------------------------------

--
-- Table structure for table `_nodes_`
--

CREATE TABLE IF NOT EXISTS `_nodes_` (
  `node_id` int(11) NOT NULL,
  `manufacturer_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT FOUND',
  `product_type_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT FOUND',
  `product_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT FOUND',
  `manifacturer_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT FOUND',
  `product_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT FOUND',
  `isON` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT FOUND',
  `level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `_nodes_`
--

INSERT INTO `_nodes_` (`node_id`, `manufacturer_id`, `product_type_id`, `product_id`, `manifacturer_name`, `product_name`, `isON`, `level`) VALUES
(1, '0086', '0002', '0001', 'Aeon Labs', 'Z-Stick S2', 'False', 0),
(10, '001d', '0401', '0209', 'Leviton', 'VRI06-1LX Multilevel Scene Switch', 'False', 0),
(12, '0063', '5252', '3530', 'GE', 'NOT FOUND', 'False', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `command_classes`
--
ALTER TABLE `command_classes`
  ADD CONSTRAINT `command_classes_ibfk_1` FOREIGN KEY (`node_id`) REFERENCES `nodes` (`node_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nodes_in_group`
--
ALTER TABLE `nodes_in_group`
  ADD CONSTRAINT `nodes_in_group_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nodes_in_group_ibfk_2` FOREIGN KEY (`node_id`) REFERENCES `nodes` (`node_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
