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
-- Table structure for table `tb_app_calllog`
--

DROP TABLE IF EXISTS `tb_app_calllog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_app_calllog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) DEFAULT NULL,
  `alias` varchar(45) DEFAULT NULL,
  `model` varchar(45) DEFAULT NULL,
  `func` varchar(45) DEFAULT NULL,
  `output_data_length` int(11) DEFAULT NULL,
  `call_time` datetime DEFAULT NULL,
  `call_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_app_calllog`
--

LOCK TABLES `tb_app_calllog` WRITE;
/*!40000 ALTER TABLE `tb_app_calllog` DISABLE KEYS */;
INSERT INTO `tb_app_calllog` VALUES (1,'alucard263096','dogandcat','example','hello',50,'2017-01-11 12:04:01','2017-01-11'),(2,'alucard263096','dogandcat','example','hello',50,'2017-01-11 12:04:08','2017-01-11'),(3,'alucard263096','dogandcat','download','list',52,'2017-01-11 12:04:28','2017-01-11'),(4,'alucard263096','dogandcat','download','get',52,'2017-01-11 12:04:29','2017-01-11'),(5,'alucard263096','dogandcat','download','update',52,'2017-01-11 12:04:30','2017-01-11'),(6,'alucard263096','dogandcat','download','delete',52,'2017-01-11 12:04:32','2017-01-11'),(7,'alucard263096','dogandcat','download','list',243,'2017-01-11 12:04:36','2017-01-11'),(8,'alucard263096','dogandcat','download','get',406,'2017-01-11 12:04:37','2017-01-11'),(9,'alucard263096','dogandcat','download','update',264,'2017-01-11 12:04:38','2017-01-11'),(10,'alucard263096','dogandcat','download','delete',48,'2017-01-11 12:04:40','2017-01-11'),(11,'alucard263096','dogandcat','downloadcategory','list',116,'2017-01-11 12:04:41','2017-01-11'),(12,'alucard263096','dogandcat','downloadcategory','get',290,'2017-01-11 12:04:42','2017-01-11'),(13,'alucard263096','dogandcat','downloadcategory','update',212,'2017-01-11 12:04:43','2017-01-11'),(14,'alucard263096','dogandcat','downloadcategory','delete',48,'2017-01-11 12:04:44','2017-01-11'),(15,'alucard263096','dogandcat','news','list',2,'2017-01-11 12:04:46','2017-01-11'),(16,'alucard263096','dogandcat','news','get',5,'2017-01-11 12:04:47','2017-01-11'),(17,'alucard263096','dogandcat','news','update',264,'2017-01-11 12:04:48','2017-01-11'),(18,'alucard263096','dogandcat','news','delete',48,'2017-01-11 12:04:49','2017-01-11'),(19,'alucard263096','dogandcat','systemsetting','update',47,'2017-01-11 12:04:50','2017-01-11'),(20,'alucard263096','dogandcat','systemsetting','get',417,'2017-01-11 12:04:51','2017-01-11'),(21,'alucard263096','dogandcat','test','list',655,'2017-01-11 12:04:52','2017-01-11'),(22,'alucard263096','dogandcat','test','get',5,'2017-01-11 12:04:54','2017-01-11'),(23,'alucard263096','dogandcat','test','update',376,'2017-01-11 12:04:55','2017-01-11'),(24,'alucard263096','dogandcat','test','delete',48,'2017-01-11 12:04:56','2017-01-11'),(25,'alucard263096','dogandcat','testcat','list',485,'2017-01-11 12:04:57','2017-01-11'),(26,'alucard263096','dogandcat','testcat','get',169,'2017-01-11 12:04:58','2017-01-11'),(27,'alucard263096','dogandcat','testcat','update',243,'2017-01-11 12:04:59','2017-01-11'),(28,'alucard263096','dogandcat','testcat','delete',48,'2017-01-11 12:05:00','2017-01-11'),(29,'alucard263096','dogandcat','user','list',795,'2017-01-11 12:05:01','2017-01-11'),(30,'alucard263096','dogandcat','user','get',665,'2017-01-11 12:05:02','2017-01-11'),(31,'alucard263096','dogandcat','user','update',429,'2017-01-11 12:05:04','2017-01-11'),(32,'alucard263096','dogandcat','user','delete',48,'2017-01-11 12:05:05','2017-01-11'),(33,'alucard263096','dogandcat','example','hello',62,'2017-01-11 12:05:06','2017-01-11'),(34,'alucard263096','dogandcat','download','list',243,'2017-01-11 12:05:31','2017-01-11'),(35,'alucard263096','dogandcat','download','get',406,'2017-01-11 12:05:32','2017-01-11'),(36,'alucard263096','dogandcat','download','update',264,'2017-01-11 12:05:33','2017-01-11'),(37,'alucard263096','dogandcat','download','delete',48,'2017-01-11 12:05:35','2017-01-11'),(38,'alucard263096','dogandcat','downloadcategory','list',116,'2017-01-11 12:05:37','2017-01-11'),(39,'alucard263096','dogandcat','downloadcategory','get',290,'2017-01-11 12:05:39','2017-01-11'),(40,'alucard263096','dogandcat','downloadcategory','update',212,'2017-01-11 12:05:41','2017-01-11'),(41,'alucard263096','dogandcat','downloadcategory','delete',48,'2017-01-11 12:05:43','2017-01-11'),(42,'alucard263096','dogandcat','news','list',2,'2017-01-11 12:05:45','2017-01-11'),(43,'alucard263096','dogandcat','news','get',5,'2017-01-11 12:05:47','2017-01-11'),(44,'alucard263096','dogandcat','news','update',264,'2017-01-11 12:05:49','2017-01-11'),(45,'alucard263096','dogandcat','news','delete',48,'2017-01-11 12:05:51','2017-01-11'),(46,'alucard263096','dogandcat','systemsetting','update',47,'2017-01-11 12:05:53','2017-01-11'),(47,'alucard263096','dogandcat','systemsetting','get',417,'2017-01-11 12:05:55','2017-01-11'),(48,'alucard263096','dogandcat','test','list',655,'2017-01-11 12:05:57','2017-01-11'),(49,'alucard263096','dogandcat','test','get',5,'2017-01-11 12:05:59','2017-01-11'),(50,'alucard263096','dogandcat','test','update',376,'2017-01-11 12:06:01','2017-01-11'),(51,'alucard263096','dogandcat','test','delete',48,'2017-01-11 12:06:03','2017-01-11'),(52,'alucard263096','dogandcat','testcat','list',485,'2017-01-11 12:06:05','2017-01-11'),(53,'alucard263096','dogandcat','testcat','get',169,'2017-01-11 12:06:07','2017-01-11'),(54,'alucard263096','dogandcat','testcat','update',243,'2017-01-11 12:06:09','2017-01-11'),(55,'alucard263096','dogandcat','testcat','delete',48,'2017-01-11 12:06:11','2017-01-11'),(56,'alucard263096','dogandcat','user','list',795,'2017-01-11 12:06:13','2017-01-11'),(57,'alucard263096','dogandcat','user','get',665,'2017-01-11 12:06:15','2017-01-11'),(58,'alucard263096','dogandcat','user','update',429,'2017-01-11 12:06:17','2017-01-11'),(59,'alucard263096','dogandcat','user','delete',48,'2017-01-11 12:06:19','2017-01-11'),(60,'alucard263096','dogandcat','example','hello',62,'2017-01-11 12:06:21','2017-01-11'),(61,'alucard263096','dogandcat','testcat','list',2,'2017-01-11 12:08:23','2017-01-11'),(62,'alucard263096','dogandcat','testcat','get',5,'2017-01-11 12:08:24','2017-01-11'),(63,'alucard263096','dogandcat','testcat','update',243,'2017-01-11 12:08:25','2017-01-11'),(64,'alucard263096','dogandcat','testcat','delete',48,'2017-01-11 12:08:26','2017-01-11'),(65,'alucard263096','dogandcat','testcat','list',2,'2017-01-11 12:09:56','2017-01-11'),(66,'alucard263096','dogandcat','testcat','get',5,'2017-01-11 12:09:57','2017-01-11'),(67,'alucard263096','dogandcat','testcat','update',243,'2017-01-11 12:09:58','2017-01-11'),(68,'alucard263096','dogandcat','testcat','delete',48,'2017-01-11 12:09:59','2017-01-11'),(69,'alucard263096','dogandcat','example','hello',50,'2017-01-11 12:25:12','2017-01-11'),(70,'alucard263096','dogandcat','example','hello',50,'2017-01-11 12:25:13','2017-01-11'),(71,'alucard263096','dogandcat','example','hello',50,'2017-01-11 12:25:29','2017-01-11');
/*!40000 ALTER TABLE `tb_app_calllog` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-17 17:59:09
