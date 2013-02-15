-- MySQL dump 10.13  Distrib 5.5.22, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: currentState
-- ------------------------------------------------------
-- Server version	5.5.22-0ubuntu1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `_nodes_`
--

DROP TABLE IF EXISTS `_nodes_`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `_nodes_` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `_nodes_`
--

LOCK TABLES `_nodes_` WRITE;
/*!40000 ALTER TABLE `_nodes_` DISABLE KEYS */;
INSERT INTO `_nodes_` VALUES (1,'0086','0002','0001','Aeon Labs','Z-Stick S2','False',0),(5,'0063','4457','3033','GE','45613 3-Way Dimmer Switch','False',99);
/*!40000 ALTER TABLE `_nodes_` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `associations`
--

DROP TABLE IF EXISTS `associations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `associations` (
  `id` tinyint(2) NOT NULL,
  `reloadNames` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `associations`
--

LOCK TABLES `associations` WRITE;
/*!40000 ALTER TABLE `associations` DISABLE KEYS */;
INSERT INTO `associations` VALUES (1,'eab9ab990ceb20f2b3e80387951e27ef');
/*!40000 ALTER TABLE `associations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `command_classes`
--

DROP TABLE IF EXISTS `command_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `command_classes` (
  `node_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`node_id`,`id`,`name`),
  CONSTRAINT `command_classes_ibfk_1` FOREIGN KEY (`node_id`) REFERENCES `nodes` (`node_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `command_classes`
--

LOCK TABLES `command_classes` WRITE;
/*!40000 ALTER TABLE `command_classes` DISABLE KEYS */;
INSERT INTO `command_classes` VALUES (5,38,'COMMAND_CLASS_SWITCH_MULTILEVEL'),(5,39,'COMMAND_CLASS_SWITCH_ALL'),(5,112,'COMMAND_CLASS_CONFIGURATION'),(5,114,'COMMAND_CLASS_MANUFACTURER_SPECIFIC'),(5,115,'COMMAND_CLASS_POWERLEVEL'),(5,134,'COMMAND_CLASS_VERSION');
/*!40000 ALTER TABLE `command_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (5,'Bedroom'),(10,'Kitchen');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `home`
--

DROP TABLE IF EXISTS `home`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `home` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `home`
--

LOCK TABLES `home` WRITE;
/*!40000 ALTER TABLE `home` DISABLE KEYS */;
INSERT INTO `home` VALUES (1,'0x016a0a44','fbf124a097d95f8b0e48682ac9f144b2',1,0,0,1,0,'ab0e0db42e016202d9db498efe0debde');
/*!40000 ALTER TABLE `home` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `node_names`
--

DROP TABLE IF EXISTS `node_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `node_names` (
  `node_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NOT DEFINED',
  PRIMARY KEY (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `node_names`
--

LOCK TABLES `node_names` WRITE;
/*!40000 ALTER TABLE `node_names` DISABLE KEYS */;
INSERT INTO `node_names` VALUES (1,'Switch/Cooler'),(2,'2nd floor'),(3,'Socket'),(4,'Light'),(5,'2nd Floor Lights'),(6,'Cooler'),(10,'Lampshade'),(12,'Kitchen');
/*!40000 ALTER TABLE `node_names` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nodes`
--

DROP TABLE IF EXISTS `nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nodes` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nodes`
--

LOCK TABLES `nodes` WRITE;
/*!40000 ALTER TABLE `nodes` DISABLE KEYS */;
INSERT INTO `nodes` VALUES (1,'0086','0002','0001','Aeon Labs','Z-Stick S2','False',0,'NOT DEFINED',0),(5,'0063','4457','3033','GE','45613 3-Way Dimmer Switch','False',99,'NOT DEFINED',0);
/*!40000 ALTER TABLE `nodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nodes_in_group`
--

DROP TABLE IF EXISTS `nodes_in_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nodes_in_group` (
  `group_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`node_id`),
  KEY `node_id` (`node_id`),
  CONSTRAINT `nodes_in_group_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `nodes_in_group_ibfk_2` FOREIGN KEY (`node_id`) REFERENCES `nodes` (`node_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nodes_in_group`
--

LOCK TABLES `nodes_in_group` WRITE;
/*!40000 ALTER TABLE `nodes_in_group` DISABLE KEYS */;
INSERT INTO `nodes_in_group` VALUES (5,1),(10,5);
/*!40000 ALTER TABLE `nodes_in_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rules`
--

DROP TABLE IF EXISTS `rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rules` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rules`
--

LOCK TABLES `rules` WRITE;
/*!40000 ALTER TABLE `rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedulesEveryDay`
--

DROP TABLE IF EXISTS `schedulesEveryDay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedulesEveryDay` (
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedulesEveryDay`
--

LOCK TABLES `schedulesEveryDay` WRITE;
/*!40000 ALTER TABLE `schedulesEveryDay` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedulesEveryDay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedulesInterval`
--

DROP TABLE IF EXISTS `schedulesInterval`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedulesInterval` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedulesInterval`
--

LOCK TABLES `schedulesInterval` WRITE;
/*!40000 ALTER TABLE `schedulesInterval` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedulesInterval` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedulesOnce`
--

DROP TABLE IF EXISTS `schedulesOnce`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedulesOnce` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedulesOnce`
--

LOCK TABLES `schedulesOnce` WRITE;
/*!40000 ALTER TABLE `schedulesOnce` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedulesOnce` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedulesWeekly`
--

DROP TABLE IF EXISTS `schedulesWeekly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedulesWeekly` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedulesWeekly`
--

LOCK TABLES `schedulesWeekly` WRITE;
/*!40000 ALTER TABLE `schedulesWeekly` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedulesWeekly` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `root` tinyint(1) NOT NULL DEFAULT '0',
  `salt` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('andrei','05c32d046a6b49c3087485b60a79b93eaa43b9ce327f26d429df67d12b7b89ca',1,0,'pXt4pE#mUm?zUjCo?yNR8f8uF6&cMGZonp$Km5bEXtN?riG@&iyzH4Lkp4M@Me1m'),('root','a50d74d97346be3a9dc97b52c026125fb68489c847db1109f3e87db6aac552c9',1,1,'ny#*1*H!MTHmCy9yAt2AVpIBfD7B5qe5WMG*EzN6Sb?nO0QZuvimX#hr1NS$#QNK'),('someone','1bdb4eaa345078c6cb619092f4af15248ef4aac54305a2978d539ce240f6ed0e',0,0,'QmS1pHahJHB?%AZOJj3a%MI2gmgc?JNRN?ZwvlvzF9vY$qmZJT?Nt*16LLEUmdhg');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-02-14 22:39:01
