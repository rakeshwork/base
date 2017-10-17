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
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_templates` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `name` varchar(100) NOT NULL,
  `subject` varchar(45) DEFAULT NULL,
  `body` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_templates`
--

LOCK TABLES `email_templates` WRITE;
/*!40000 ALTER TABLE `email_templates` DISABLE KEYS */;
INSERT INTO `email_templates` VALUES (1,'Default Mail Template','Default Mail Template','default_mail_template','','<table width=\"720\" align=\"center\">\r\n<tr><td>\r\n<img src=\"{%email_logo_url%}\"/>\r\n</td></tr>\r\n<tr><td>\r\n\r\n\r\n<table style=\"padding:10px 10px;\" width=\"95%\">\r\n<tr>\r\n<td>{%template_email_body%}</td>\r\n</tr>\r\n</table>\r\n\r\n\r\n<table style=\"background:grey;padding: 10px 5px;\">\r\n<tr>\r\n<td width=\"50%\" align=\"left\">\r\n{%website_name%}\r\n</td>\r\n\r\n<td width=\"50%\" align=\"right\">\r\n{%copyright_text%}\r\n</td>\r\n</tr>\r\n</table>\r\n</td></tr>\r\n</table>'),(2,'Contact Us','Contact Us - general template used for all contact purposes','contact_us_general','Contact Us - {%purpose_title%}','<p>Hello {%receiver_name%},</p>\r\n<p>You have been contacted. Following are the details</p>\r\n<p>Purpose : {%purpose_title%}</p>\r\n<p>Name : {%name%}</p>\r\n<p>Email : {%email%}</p>\r\n<p>Telephone Number : {%telephone%}</p>\r\n<p>Organization : {%company%}</p>\r\n<p>Message : {%message%}</p>'),(3,'Registration activation link','Registration activation link','registration_activation_link','{%website_name%} Registration','<p>Hello {%receiver_name%},</p>\r\n<p>You had signed up with <a href=\"{%website_url%}\" target=\"_blank\" title=\"{%website_name%}\">{%website_name%}</a></p>\r\n<p><a title=\"Click here\" target=\"_blank\" href=\"{%activation_url%}\">Click here</a> to confirm your email id.</p>'),(4,'Welcome to the website','Welcome to the website','welcome','Welcome {%receiver_name%}','<p>Hello {%receiver_name%},</p>\r\n<p>Welcome to  {%website_name%}</p>\r\n<p>{%welcome_text%}</p>'),(5,'Password regeneration link','Password regeneration link','password_regeneration_link','Regenerate Password - {%website_title%}','<p>Hello {%receiver_name%},</p>\r\n<p>You had recently opted to regenerate your password with {%website_name%}.</p>\r\n<p>&nbsp;</p>\r\n<p><a href=\"{%password_recovery_link%}\" target=\"_top\" title=\"Start password regenerate process\">Click Here to start the process.</p>\r\n<p>&nbsp;</p>'),(6,'account created	','account created	','account_created','Account Created','<p>Hello {%receiver_name%},</p>\r\n<p>An account has been created for you in <a href=\"{%website_url%}\" target=\"_blank\" title=\"{%website_name%}\">{%website_name%}</a></p>\r\n<p><a title=\"Click here\" target=\"_blank\" href=\"{%login_url%}\">Click here</a> to login into your account.</p>\r\n<p>Your Username is  : <b>{%username%}</b></p>\r\n<p>Your temporary password is : <b>{%temporary_password%}</b></p>\r\n<p>You can change your password from the \"account settings\" section once you log in.</p>');
/*!40000 ALTER TABLE `email_templates` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-10-17 10:23:52
