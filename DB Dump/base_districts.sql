-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: base
-- ------------------------------------------------------
-- Server version	5.7.11

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
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `districts` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `state_alpha_code` varchar(10) NOT NULL,
  `alpha_code` varchar(10) DEFAULT NULL COMMENT 'unique alphabetical code for representing the district',
  `numeric_code` int(5) NOT NULL COMMENT 'numeric code for representing the district',
  `name` varchar(255) NOT NULL,
  `common_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`state_alpha_code`),
  UNIQUE KEY `numeric_code_UNIQUE` (`numeric_code`),
  KEY `fk_districts_states_copy11_idx` (`state_alpha_code`),
  CONSTRAINT `fk_districts_states_copy11` FOREIGN KEY (`state_alpha_code`) REFERENCES `country_states` (`alpha_code`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `districts`
--

LOCK TABLES `districts` WRITE;
/*!40000 ALTER TABLE `districts` DISABLE KEYS */;
INSERT INTO `districts` VALUES (1,'KL',NULL,588,'Kasaragod','Kasaragod'),(2,'KL',NULL,589,'Kannur','Kannur'),(3,'KL',NULL,590,'Wayanad','Wayanad'),(4,'KL',NULL,591,'Kozhikode','Kozhikode'),(5,'KL',NULL,592,'Malappuram','Malappuram'),(6,'KL',NULL,593,'Palakkad','Palakkad'),(7,'KL',NULL,594,'Thrissur','Thrissur'),(8,'KL',NULL,595,'Ernakulam','Ernakulam'),(9,'KL',NULL,596,'Idukki','Idukki'),(10,'KL',NULL,597,'Kottayam','Kottayam'),(11,'KL',NULL,598,'Alappuzha','Alappuzha'),(12,'KL',NULL,599,'Pathanamthitta','Pathanamthitta'),(13,'KL',NULL,600,'Kollam','Kollam'),(14,'KL',NULL,601,'Thiruvananthapuram','Thiruvananthapuram');
/*!40000 ALTER TABLE `districts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-10-17 10:23:51
