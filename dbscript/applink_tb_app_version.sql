CREATE DATABASE  IF NOT EXISTS `applink` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `applink`;
-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: localhost    Database: applink
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
-- Table structure for table `tb_app_version`
--

DROP TABLE IF EXISTS `tb_app_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_app_version` (
  `id` int(11) NOT NULL,
  `app_id` int(11) DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `committed_date` datetime DEFAULT NULL,
  `comment` varchar(4000) DEFAULT NULL,
  `is_tag` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_app_version`
--

LOCK TABLES `tb_app_version` WRITE;
/*!40000 ALTER TABLE `tb_app_version` DISABLE KEYS */;
INSERT INTO `tb_app_version` VALUES (1,5,1,'2017-01-04 06:09:17','啊啊',NULL),(2,5,2,'2017-01-04 06:12:25','苯苯二的测试，胜利的提交',NULL),(3,5,3,'2017-01-04 06:14:03','苯苯二的测试，胜利的提交','Y'),(4,5,4,'2017-01-04 06:15:44','再来一个','Y'),(5,5,5,'2017-01-04 06:15:51','text-warning','Y'),(6,5,6,'2017-01-04 06:32:56','备份上一个版本，防止回滚失败','N'),(7,5,7,'2017-01-04 06:34:15','备份上一个版本，防止回滚失败','N'),(8,5,8,'2017-01-04 06:36:33','备份上一个版本，防止回滚失败','N'),(9,5,9,'2017-01-04 06:37:27','备份上一个版本，防止回滚失败','N'),(10,5,10,'2017-01-04 06:39:03','备份上一个版本，防止回滚失败','N'),(11,5,11,'2017-01-04 06:40:13','备份上一个版本，防止回滚失败','N'),(12,5,12,'2017-01-04 06:44:20','备份上一个版本，防止回滚失败','N'),(13,5,13,'2017-01-04 06:44:54','测试','Y'),(14,5,14,'2017-01-04 06:45:02','备份上一个版本，防止回滚失败','N'),(15,5,15,'2017-01-04 06:46:55','备份上一个版本，防止回滚失败','N'),(16,5,16,'2017-01-04 06:47:57','备份上一个版本，防止回滚失败','N'),(17,5,17,'2017-01-04 06:49:02','备份上一个版本，防止回滚失败','N'),(18,5,18,'2017-01-04 06:50:07','备份上一个版本，防止回滚失败','N'),(19,5,19,'2017-01-04 06:50:39','备份上一个版本，防止回滚失败','N'),(20,5,20,'2017-01-04 06:51:35','备份上一个版本，防止回滚失败','N'),(21,5,21,'2017-01-04 06:51:57','备份上一个版本，防止回滚失败','N'),(22,5,22,'2017-01-04 06:52:09','备份上一个版本，防止回滚失败','N'),(23,5,23,'2017-01-04 06:52:24','备份上一个版本，防止回滚失败','N'),(24,5,24,'2017-01-04 06:52:40','备份上一个版本，防止回滚失败','N'),(25,5,25,'2017-01-04 06:52:52','备份上一个版本，防止回滚失败','N'),(26,5,26,'2017-01-04 06:53:27','备份上一个版本，防止回滚失败','N'),(27,5,27,'2017-01-04 06:53:41','备份上一个版本，防止回滚失败','N'),(28,5,28,'2017-01-04 06:53:49','备份上一个版本，防止回滚失败','N'),(29,5,29,'2017-01-04 06:53:54','备份上一个版本，防止回滚失败','N'),(30,5,30,'2017-01-04 06:54:01','备份上一个版本，防止回滚失败','N'),(31,6,1,'2017-01-09 14:08:02','初始化','Y'),(32,7,1,'2017-01-09 14:23:07','初始化','Y'),(33,7,2,'2017-01-09 14:23:19','备份上一个版本，防止回滚失败','N');
/*!40000 ALTER TABLE `tb_app_version` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-17 17:59:08
