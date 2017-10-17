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
-- Table structure for table `country_states`
--

DROP TABLE IF EXISTS `country_states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country_states` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `country_alpha_3` char(3) NOT NULL,
  `alpha_code` varchar(10) NOT NULL COMMENT 'unique alphabetical code for representing the state',
  `numeric_code` int(11) DEFAULT NULL COMMENT 'numeric code for representing the district',
  `name` varchar(255) NOT NULL,
  `common_name` varchar(255) DEFAULT NULL,
  `type` varchar(45) NOT NULL COMMENT 'Union territory, State',
  PRIMARY KEY (`id`,`country_alpha_3`),
  UNIQUE KEY `alpha_code_UNIQUE` (`alpha_code`),
  KEY `fk_country_states_countries1_idx` (`country_alpha_3`),
  CONSTRAINT `fk_country_states_countries1` FOREIGN KEY (`country_alpha_3`) REFERENCES `countries` (`alpha_3`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country_states`
--

LOCK TABLES `country_states` WRITE;
/*!40000 ALTER TABLE `country_states` DISABLE KEYS */;
INSERT INTO `country_states` VALUES (1,'IND','AN',NULL,'Andaman and Nicobar Islands',NULL,'Union territory'),(2,'IND','AP',NULL,'Andhra Pradesh',NULL,'State'),(3,'IND','AR',NULL,'Arunachal Pradesh',NULL,'State'),(4,'IND','AS',NULL,'Assam',NULL,'State'),(5,'IND','BR',NULL,'Bihar',NULL,'State'),(6,'IND','CH',NULL,'Chandigarh',NULL,'Union territory'),(7,'IND','CT',NULL,'Chhattisgarh',NULL,'State'),(8,'IND','DD',NULL,'Damen and Diu',NULL,'Union territory'),(9,'IND','DL',NULL,'Delhi',NULL,'Union territory'),(10,'IND','DN',NULL,'Dadra and Nagar Haveli',NULL,'Union territory'),(11,'IND','GA',NULL,'Goa',NULL,'State'),(12,'IND','GJ',NULL,'Gujarat',NULL,'State'),(13,'IND','HP',NULL,'Himachal Pradesh',NULL,'State'),(14,'IND','HR',NULL,'Haryana',NULL,'State'),(15,'IND','JH',NULL,'Jharkhand',NULL,'State'),(16,'IND','JK',NULL,'Jammu and Kashmir',NULL,'State'),(17,'IND','KA',NULL,'Karnataka',NULL,'State'),(18,'IND','KL',NULL,'Kerala',NULL,'State'),(19,'IND','LD',NULL,'Lakshadweep',NULL,'Union territory'),(20,'IND','MH',NULL,'Maharashtra',NULL,'State'),(21,'IND','ML',NULL,'Meghalaya',NULL,'State'),(22,'IND','MN',NULL,'Manipur',NULL,'State'),(23,'IND','MP',NULL,'Madhya Pradesh',NULL,'State'),(24,'IND','MZ',NULL,'Mizoram',NULL,'State'),(25,'IND','NL',NULL,'Nagaland',NULL,'State'),(26,'IND','OR',NULL,'Orissa',NULL,'State'),(27,'IND','PB',NULL,'Punjab',NULL,'State'),(28,'IND','PY',NULL,'Puducherry',NULL,'Union territory'),(29,'IND','RJ',NULL,'Rajasthan',NULL,'State'),(30,'IND','SK',NULL,'Sikkim',NULL,'State'),(31,'IND','TG',NULL,'Telangana',NULL,'State'),(32,'IND','TN',NULL,'Tamil Nadu',NULL,'State'),(33,'IND','TR',NULL,'Tripura',NULL,'State'),(34,'IND','UP',NULL,'Uttar Pradesh',NULL,'State'),(35,'IND','UT',NULL,'Uttarakhand',NULL,'State'),(36,'IND','WB',NULL,'West Bengal',NULL,'State');
/*!40000 ALTER TABLE `country_states` ENABLE KEYS */;
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
