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
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `documentId` int(11) NOT NULL AUTO_INCREMENT,
  `firstAuthorId` int(8) NOT NULL,
  `timeLastUpdated` datetime DEFAULT NULL,
  `timeFirstPosted` datetime DEFAULT CURRENT_TIMESTAMP,
  `lockedById` int(8) DEFAULT NULL,
  `availabilityId` int(1) NOT NULL DEFAULT '2',
  `statusedById` int(8) NOT NULL,
  `typeId` int(11) NOT NULL,
  `stepId` int(11) NOT NULL,
  `statusId` int(2) DEFAULT NULL,
  PRIMARY KEY (`documentId`),
  KEY `fk_documents_employee1_idx` (`firstAuthorId`),
  KEY `fk_documents_employee4_idx` (`lockedById`),
  KEY `fk_documents_employee2_idx` (`statusedById`),
  KEY `fk_documents_doc_type1_idx` (`typeId`),
  KEY `fk_documents_steps1_idx` (`stepId`),
  KEY `fk_documents_doc_status1_idx` (`statusId`),
  CONSTRAINT `fk_documents_doc_status1` FOREIGN KEY (`statusId`) REFERENCES `doc_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_doc_type1` FOREIGN KEY (`typeId`) REFERENCES `doc_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_employee1` FOREIGN KEY (`firstAuthorId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_employee2` FOREIGN KEY (`statusedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_employee4` FOREIGN KEY (`lockedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_steps1` FOREIGN KEY (`stepId`) REFERENCES `steps` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (121,11223344,NULL,'2019-03-20 16:36:40',NULL,2,11223344,1,1,1),(122,11223344,NULL,'2019-03-20 17:02:01',NULL,2,11223344,2,1,1),(123,11223344,NULL,'2019-03-20 17:04:54',11223344,1,11223344,99,999,99);
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-20 18:45:11
