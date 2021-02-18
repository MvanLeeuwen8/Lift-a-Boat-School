-- MySQL dump 10.16  Distrib 10.2.36-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: lift_a_boat
-- ------------------------------------------------------
-- Server version	10.2.36-MariaDB

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
-- Table structure for table `bestemmingen`
--

DROP TABLE IF EXISTS `bestemmingen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bestemmingen` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `destination` varchar(100) NOT NULL,
  `description_long` varchar(1500) DEFAULT NULL,
  `destination_image_names` mediumtext DEFAULT NULL,
  `add_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `departure_date` date DEFAULT NULL,
  `price_9_12` decimal(10,2) DEFAULT NULL,
  `description_short` varchar(500) DEFAULT NULL,
  `price_13` decimal(10,2) DEFAULT NULL,
  `price_14` decimal(10,2) DEFAULT NULL,
  `price_15` decimal(10,2) DEFAULT NULL,
  `price_16` decimal(10,2) DEFAULT NULL,
  `price_17` decimal(10,2) DEFAULT NULL,
  `price_18` decimal(10,2) DEFAULT NULL,
  `price_19` decimal(10,2) DEFAULT NULL,
  `price_20` decimal(10,2) DEFAULT NULL,
  `destination_image_text` varchar(10000) DEFAULT NULL,
  `plaatsen_over` int(11) NOT NULL DEFAULT 12,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `boten`
--

DROP TABLE IF EXISTS `boten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boten` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `ship_name` varchar(100) NOT NULL DEFAULT '',
  `ship_length` float NOT NULL,
  `ship_width` float NOT NULL,
  `ship_height` float NOT NULL,
  `ship_depth` float NOT NULL,
  `ship_weight` int(10) NOT NULL,
  `ship_power` int(10) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `email` varchar(320) NOT NULL,
  `vraag` varchar(1000) NOT NULL,
  `send_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inschrijven`
--

DROP TABLE IF EXISTS `inschrijven`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inschrijven` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `naam` varchar(150) NOT NULL,
  `adres` varchar(150) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `woonplaats` varchar(150) NOT NULL,
  `telefoon` varchar(30) NOT NULL,
  `email` varchar(320) NOT NULL,
  `naam_schip` varchar(150) DEFAULT '',
  `type` varchar(150) NOT NULL,
  `merk` varchar(150) NOT NULL,
  `thuishaven` varchar(150) DEFAULT '',
  `lengte` float NOT NULL,
  `breedte` float NOT NULL,
  `diepgang` float NOT NULL,
  `hoogte_boven_water` float NOT NULL,
  `kiel` varchar(150) NOT NULL,
  `bouwjaar` varchar(4) DEFAULT '',
  `gewicht` float NOT NULL,
  `bouwmateriaal` varchar(150) NOT NULL,
  `bestemming` varchar(150) NOT NULL,
  `vertrekdatum` date NOT NULL,
  `vragen_opmerkingen` varchar(1000) DEFAULT NULL,
  `bedrijfsnaam` varchar(320) DEFAULT NULL,
  `btw_nummer` varchar(50) DEFAULT NULL,
  `kvk_nummer` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nieuws`
--

DROP TABLE IF EXISTS `nieuws`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nieuws` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(1000) NOT NULL,
  `news_article` longtext DEFAULT NULL,
  `news_images` varchar(3000) DEFAULT NULL,
  `news_images_text` varchar(3000) DEFAULT NULL,
  `add_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `send_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `trips`
--

DROP TABLE IF EXISTS `trips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trips` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `trip_depature_place` varchar(100) NOT NULL,
  `trip_destination` varchar(100) NOT NULL,
  `trip_date` date NOT NULL,
  `trip_price` float NOT NULL,
  `trip_comment` varchar(1000) DEFAULT NULL,
  `send_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(320) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'zws_lab_test','m.vanleeuwen@zuidwestsoftware.nl',1,'$2y$10$98Ho8oYEt/olGSaHdcB4UeFX4Y7dfDwsa7mqBGgRupa5dX.YTvg4e');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'lift_a_boat'
--

--
-- Dumping routines for database 'lift_a_boat'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-02-16 15:47:08
