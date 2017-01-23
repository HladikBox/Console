CREATE DATABASE  IF NOT EXISTS `applink` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `applink`;
-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: applink
-- ------------------------------------------------------
-- Server version	5.7.10-enterprise-commercial-advanced-log

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
-- Table structure for table `tb_app_submit`
--

DROP TABLE IF EXISTS `tb_app_submit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_app_submit` (
  `id` int(11) NOT NULL,
  `app_id` int(11) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `remarks` varchar(4000) DEFAULT NULL,
  `return_reason` varchar(4000) DEFAULT NULL,
  `approved_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_app_submit`
--

LOCK TABLES `tb_app_submit` WRITE;
/*!40000 ALTER TABLE `tb_app_submit` DISABLE KEYS */;
INSERT INTO `tb_app_submit` VALUES (1,7,'D','2017-01-23 22:36:38','这是一个应用评分系统',NULL,'2017-01-23 22:42:20'),(2,7,'D','2017-01-23 23:06:47','',NULL,'2017-01-23 23:07:06'),(3,6,'D','2017-01-23 23:08:44','',NULL,'2017-01-23 23:09:07');
/*!40000 ALTER TABLE `tb_app_submit` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-24  1:30:27
