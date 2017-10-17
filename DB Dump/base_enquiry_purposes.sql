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
-- Table structure for table `enquiry_purposes`
--

DROP TABLE IF EXISTS `enquiry_purposes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enquiry_purposes` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `target_email` varchar(150) NOT NULL,
  `reciever_name` varchar(100) NOT NULL,
  `email_template_id` tinyint(4) NOT NULL,
  `success_message` text NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1=active,2=blocked',
  PRIMARY KEY (`id`),
  KEY `fk_enquiry_purposes_enquiry_purpose_status1_idx` (`status`),
  CONSTRAINT `fk_enquiry_purposes_enquiry_purpose_status1` FOREIGN KEY (`status`) REFERENCES `enquiry_purpose_statuses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enquiry_purposes`
--

LOCK TABLES `enquiry_purposes` WRITE;
/*!40000 ALTER TABLE `enquiry_purposes` DISABLE KEYS */;
INSERT INTO `enquiry_purposes` VALUES (3,'general','General','','general@gmail.com','Admin',0,'Thank you for contacting us',1);
/*!40000 ALTER TABLE `enquiry_purposes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-10-17 10:23:50
