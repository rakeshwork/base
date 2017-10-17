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
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `account_no` int(11) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `salt` varchar(45) NOT NULL,
  `hash` varchar(150) NOT NULL,
  `type` int(11) NOT NULL,
  `online_status` int(11) DEFAULT NULL,
  `salutation` int(11) DEFAULT NULL,
  `first_name` varchar(45) NOT NULL,
  `middle_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email_id` varchar(45) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `online_via` int(11) DEFAULT NULL COMMENT 'it cannot be null because we have defined a foreign key on this table, which is refencing a primary key in another table . maybe in future we will have a separate table to indicate online status. and move this field from this table. \n\nuntill then, let this be, because if we separate online via information from this table now, authentication library will have to be refactored. (11th Oct 2017)',
  `created_on` datetime DEFAULT NULL,
  PRIMARY KEY (`account_no`),
  UNIQUE KEY `account_no_UNIQUE` (`account_no`),
  UNIQUE KEY `email_id_UNIQUE` (`email_id`),
  KEY `fk_users_user_types1_idx` (`type`),
  KEY `fk_users_online_statuses1_idx` (`online_status`),
  KEY `fk_users_user_salutations1_idx` (`salutation`),
  KEY `fk_users_user_statuses1_idx` (`status`),
  KEY `fk_users_authenticating_sources1_idx` (`online_via`),
  CONSTRAINT `fk_users_authenticating_sources1` FOREIGN KEY (`online_via`) REFERENCES `authenticating_sources` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_online_statuses1` FOREIGN KEY (`online_status`) REFERENCES `online_statuses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_user_salutations1` FOREIGN KEY (`salutation`) REFERENCES `user_salutations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_user_statuses1` FOREIGN KEY (`status`) REFERENCES `user_statuses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_user_types1` FOREIGN KEY (`type`) REFERENCES `user_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (57874435,'admin','1jOqJcv5o8Ewyer24tT7lR3kHIZCbpdz','300c8a58e0ed401aba9c99313867b7bbfc2a9353411f17ba1afec0341e0c16b87972037d12cd861ea6f0130eb8bde3279e6542723844f00febfd58ac9a35d3de',1,1,2,'Administrator','','','admin@gmail.com',1,'2017-10-17 04:10:36',1,'2017-10-11 09:38:46'),(85514497,NULL,'5wcmip96fBEZjM3s2uUlGxDrh0qaTneI','9de75976666a24e61232403fbf931216e9d04ee9da5803631d9b3052f559c245c8fb49dbd8d716a702e9c5b15535f03b5f7d1bf6d3906f3226b613374869fdcf',2,2,1,'Stafito','yepp','','stafito@gmail.com',1,NULL,NULL,'2017-10-13 07:10:50');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
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
