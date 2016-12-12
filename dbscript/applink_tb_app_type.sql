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
-- Table structure for table `tb_app_type`
--

DROP TABLE IF EXISTS `tb_app_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_app_type` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `order_no` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_user` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `updated_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_app_type`
--

LOCK TABLES `tb_app_type` WRITE;
/*!40000 ALTER TABLE `tb_app_type` DISABLE KEYS */;
INSERT INTO `tb_app_type` VALUES (1,'出行','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(2,'音乐','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(3,'财务','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(4,'美食佳饮','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(5,'社交','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(6,'生活','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(7,'游戏','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(8,'旅游','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(9,'新闻','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(10,'音乐','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(11,'教育','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(12,'效率','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(13,'摄影与录像','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(14,'报刊杂志','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(15,'工具','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(16,'导航','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(17,'娱乐','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(18,'天气','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(19,'图书','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(20,'商品指南','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(21,'商务','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(22,'参考','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(23,'医疗','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(24,'健康健美','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(25,'体育','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1),(26,'其它','','A',1,'2016-12-11 23:39:30',1,'2016-12-11 23:39:30',1);
/*!40000 ALTER TABLE `tb_app_type` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-12  0:29:57
