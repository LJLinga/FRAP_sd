CREATE DATABASE  IF NOT EXISTS `facultyassocnew` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `facultyassocnew`;
-- MySQL dump 10.13  Distrib 5.7.25, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: facultyassocnew
-- ------------------------------------------------------
-- Server version	5.7.25-log

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
-- Table structure for table `doc_versions`
--

DROP TABLE IF EXISTS `doc_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_versions` (
  `versionId` int(11) NOT NULL AUTO_INCREMENT,
  `documentId` int(11) NOT NULL,
  `versionNo` varchar(45) DEFAULT NULL,
  `authorId` int(8) NOT NULL,
  `title` longtext,
  `content` longtext,
  `timeCreated` datetime DEFAULT CURRENT_TIMESTAMP,
  `filePath` varchar(255) DEFAULT NULL,
  `fileType` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`versionId`,`documentId`),
  KEY `fk_doc_versions_documents1_idx` (`documentId`),
  KEY `fk_doc_versions_employee1_idx` (`authorId`),
  CONSTRAINT `fk_doc_versions_documents1` FOREIGN KEY (`documentId`) REFERENCES `documents` (`documentId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_doc_versions_employee1` FOREIGN KEY (`authorId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_versions`
--

LOCK TABLES `doc_versions` WRITE;
/*!40000 ALTER TABLE `doc_versions` DISABLE KEYS */;
INSERT INTO `doc_versions` VALUES (143,121,'1.0',11223344,'Legal Document',NULL,'2019-03-20 16:36:40','EDMS_Documents/1553071000.pdf',NULL),(144,122,'1.0',11223344,'Legal Document',NULL,'2019-03-20 17:02:01','EDMS_Documents/1553072521.pdf',NULL),(145,123,'1.0',11223344,'efwere',NULL,'2019-03-20 17:04:54','EDMS_Documents/1553072694.pdf',NULL),(146,123,'1.1',11223344,'efwere',NULL,'2019-03-20 17:26:56','EDMS_Documents/1553074017.pdf',NULL);
/*!40000 ALTER TABLE `doc_versions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-20 18:45:12
