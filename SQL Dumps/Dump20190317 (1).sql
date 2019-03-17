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
-- Table structure for table `app_status`
--

DROP TABLE IF EXISTS `app_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_status` (
  `STATUS_ID` int(1) NOT NULL,
  `STATUS` varchar(45) NOT NULL,
  PRIMARY KEY (`STATUS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_status`
--

LOCK TABLES `app_status` WRITE;
/*!40000 ALTER TABLE `app_status` DISABLE KEYS */;
INSERT INTO `app_status` VALUES (1,'PENDING'),(2,'APPROVED'),(3,'REJECTED');
/*!40000 ALTER TABLE `app_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banks` (
  `BANK_ID` int(3) NOT NULL AUTO_INCREMENT,
  `BANK_NAME` varchar(45) NOT NULL,
  `BANK_ABBV` varchar(6) NOT NULL,
  `STATUS` tinyint(4) NOT NULL,
  `EMP_ID_ADDED` int(8) NOT NULL,
  `DATE_ADDED` datetime NOT NULL,
  `DATE_REMOVED` datetime DEFAULT NULL,
  PRIMARY KEY (`BANK_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banks`
--

LOCK TABLES `banks` WRITE;
/*!40000 ALTER TABLE `banks` DISABLE KEYS */;
/*!40000 ALTER TABLE `banks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `children`
--

DROP TABLE IF EXISTS `children`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `children` (
  `CHILD_ID` int(11) NOT NULL,
  `MEMBER_ID` int(8) NOT NULL,
  `RECORD_ID` int(9) NOT NULL,
  `LASTNAME` varchar(45) NOT NULL,
  `FIRSTNAME` varchar(45) NOT NULL,
  `MIDDLENAME` varchar(45) NOT NULL,
  `BIRTHDATE` date NOT NULL,
  `STATUS` int(1) NOT NULL,
  `SEX` tinyint(4) NOT NULL,
  PRIMARY KEY (`CHILD_ID`,`MEMBER_ID`,`RECORD_ID`),
  KEY `fk_siblings_health_aid1_idx` (`RECORD_ID`,`MEMBER_ID`),
  CONSTRAINT `fk_siblings_health_aid10` FOREIGN KEY (`RECORD_ID`, `MEMBER_ID`) REFERENCES `health_aid` (`RECORD_ID`, `MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `children`
--

LOCK TABLES `children` WRITE;
/*!40000 ALTER TABLE `children` DISABLE KEYS */;
/*!40000 ALTER TABLE `children` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `civ_status`
--

DROP TABLE IF EXISTS `civ_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `civ_status` (
  `STATUS_ID` int(1) NOT NULL,
  `STATUS` varchar(45) NOT NULL,
  PRIMARY KEY (`STATUS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `civ_status`
--

LOCK TABLES `civ_status` WRITE;
/*!40000 ALTER TABLE `civ_status` DISABLE KEYS */;
INSERT INTO `civ_status` VALUES (1,'SINGLE'),(2,'MARRIED'),(3,'DIVORCED'),(4,'WIDOWED');
/*!40000 ALTER TABLE `civ_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_roles`
--

DROP TABLE IF EXISTS `cms_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleName` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_roles`
--

LOCK TABLES `cms_roles` WRITE;
/*!40000 ALTER TABLE `cms_roles` DISABLE KEYS */;
INSERT INTO `cms_roles` VALUES (1,'READER'),(2,'CONTRIBUTOR'),(3,'REVIEWER'),(4,'PUBLISHER');
/*!40000 ALTER TABLE `cms_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doc_availability`
--

DROP TABLE IF EXISTS `doc_availability`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_availability` (
  `id` int(11) NOT NULL,
  `availability` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_availability`
--

LOCK TABLES `doc_availability` WRITE;
/*!40000 ALTER TABLE `doc_availability` DISABLE KEYS */;
INSERT INTO `doc_availability` VALUES (1,'Locked'),(2,'Available');
/*!40000 ALTER TABLE `doc_availability` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doc_comments`
--

DROP TABLE IF EXISTS `doc_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentCommentId` int(11) NOT NULL DEFAULT '0',
  `commenterId` int(8) NOT NULL,
  `commenterName` varchar(100) DEFAULT NULL,
  `content` longtext,
  `versionNo` int(11) DEFAULT NULL,
  `documentId` int(11) DEFAULT NULL,
  `timePosted` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_doc_comments_doc_versions1_idx` (`versionNo`,`documentId`),
  KEY `fk_doc_comments_employee1_idx` (`commenterId`),
  CONSTRAINT `fk_doc_comments_doc_versions1` FOREIGN KEY (`versionNo`, `documentId`) REFERENCES `doc_versions` (`versionId`, `documentId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_doc_comments_employee1` FOREIGN KEY (`commenterId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_comments`
--

LOCK TABLES `doc_comments` WRITE;
/*!40000 ALTER TABLE `doc_comments` DISABLE KEYS */;
INSERT INTO `doc_comments` VALUES (12,0,11223344,NULL,'xzczxcz',NULL,108,'2019-03-08 21:36:25'),(13,12,99999999,NULL,'Hello',NULL,108,'2019-03-13 10:26:11'),(14,0,11540761,NULL,'kmokmo',NULL,108,'2019-03-13 17:30:34');
/*!40000 ALTER TABLE `doc_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doc_ref_versions`
--

DROP TABLE IF EXISTS `doc_ref_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_ref_versions` (
  `refVersionId` int(11) NOT NULL,
  `refDocumentId` int(11) NOT NULL,
  `documentId` int(11) NOT NULL,
  KEY `fk_doc_ref_versions_doc_versions1_idx` (`refVersionId`,`refDocumentId`),
  KEY `fk_doc_ref_versions_documents1_idx` (`documentId`),
  CONSTRAINT `fk_doc_ref_versions_doc_versions1` FOREIGN KEY (`refVersionId`, `refDocumentId`) REFERENCES `doc_versions` (`versionId`, `documentId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_doc_ref_versions_documents1` FOREIGN KEY (`documentId`) REFERENCES `documents` (`documentId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_ref_versions`
--

LOCK TABLES `doc_ref_versions` WRITE;
/*!40000 ALTER TABLE `doc_ref_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `doc_ref_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doc_type`
--

DROP TABLE IF EXISTS `doc_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_type` (
  `id` int(11) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_type`
--

LOCK TABLES `doc_type` WRITE;
/*!40000 ALTER TABLE `doc_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `doc_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doc_version_status`
--

DROP TABLE IF EXISTS `doc_version_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_version_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_version_status`
--

LOCK TABLES `doc_version_status` WRITE;
/*!40000 ALTER TABLE `doc_version_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `doc_version_status` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_versions`
--

LOCK TABLES `doc_versions` WRITE;
/*!40000 ALTER TABLE `doc_versions` DISABLE KEYS */;
INSERT INTO `doc_versions` VALUES (117,108,'1.0',99999999,'Legal Document',NULL,'2019-03-08 21:12:56','EDMS_Documents/1552050777.pdf',NULL),(118,108,'1.2',99999999,'Legal Document',NULL,'2019-03-09 09:21:42','EDMS_Documents/1552050777.pdf',NULL),(119,108,'1.3',11223344,'DOCX3',NULL,'2019-03-09 09:44:32','EDMS_Documents/1552095873.png',NULL),(120,109,'1.0',11540761,'Legal Document',NULL,'2019-03-09 17:36:18','EDMS_Documents/1552124178.png',NULL),(121,110,'1.0',11223344,'Legal Document',NULL,'2019-03-09 21:38:35','EDMS_Documents/1552138716.png',NULL),(122,108,'1.4',99999999,'DOCX3',NULL,'2019-03-13 10:37:47','EDMS_Documents/1552444668.pdf',NULL),(123,108,'1.5',11540761,'Hello',NULL,'2019-03-13 17:34:45','EDMS_Documents/1552469686.jpg',NULL),(124,108,'1.6',11540761,'Helo',NULL,'2019-03-13 17:34:59','EDMS_Documents/1552469700.png',NULL),(125,111,'1.0',11540761,'efwere',NULL,'2019-03-13 17:45:15','EDMS_Documents/1552470316.png',NULL),(126,111,'2',11540761,'Heloo',NULL,'2019-03-13 17:45:45','EDMS_Documents/1552470345.PNG',NULL),(127,112,'1.0',99999999,'Legal Document',NULL,'2019-03-13 19:53:38','EDMS_Documents/1552478018.PNG',NULL),(128,113,'1.0',11540761,'Legal Document',NULL,'2019-03-13 20:29:09','EDMS_Documents/1552480149.PNG',NULL),(129,113,'1.1',99999999,'DOCX3',NULL,'2019-03-13 20:32:57','EDMS_Documents/1552480377.PNG',NULL),(130,113,'1.2',99999999,'DOCX3',NULL,'2019-03-13 20:33:27','EDMS_Documents/1552480407.PNG',NULL),(131,113,'1.3',99999999,'DOCX3',NULL,'2019-03-13 20:35:45','EDMS_Documents/1552480545.PNG',NULL),(132,114,'1.0',99999999,'Legal Document',NULL,'2019-03-14 12:41:45','EDMS_Documents/1552538506.PNG',NULL);
/*!40000 ALTER TABLE `doc_versions` ENABLE KEYS */;
UNLOCK TABLES;

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
  `availabilityId` int(11) NOT NULL DEFAULT '2',
  `stepId` int(11) NOT NULL,
  `processId` int(11) NOT NULL,
  `statusedById` int(8) NOT NULL,
  `statusId` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`documentId`),
  KEY `fk_documents_employee1_idx` (`firstAuthorId`),
  KEY `fk_documents_doc_availability1_idx` (`availabilityId`),
  KEY `fk_documents_employee4_idx` (`lockedById`),
  KEY `fk_documents_steps1_idx` (`stepId`,`processId`),
  KEY `fk_documents_employee2_idx` (`statusedById`),
  KEY `fk_documents_status_dictionary1_idx` (`statusId`),
  CONSTRAINT `availabilityId` FOREIGN KEY (`availabilityId`) REFERENCES `doc_availability` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_employee1` FOREIGN KEY (`firstAuthorId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_employee2` FOREIGN KEY (`statusedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_employee4` FOREIGN KEY (`lockedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_status_dictionary1` FOREIGN KEY (`statusId`) REFERENCES `status_dictionary` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_steps1` FOREIGN KEY (`stepId`, `processId`) REFERENCES `steps` (`id`, `processId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (108,99999999,NULL,'2019-03-08 21:12:56',NULL,2,2,1,99999999,1),(109,11540761,NULL,'2019-03-09 17:36:18',NULL,2,2,1,11540761,1),(110,11223344,NULL,'2019-03-09 21:38:35',NULL,2,2,1,11223344,1),(111,11540761,NULL,'2019-03-13 17:45:15',11540761,1,2,1,11540761,1),(112,99999999,NULL,'2019-03-13 19:53:38',NULL,2,2,1,99999999,1),(113,11540761,NULL,'2019-03-13 20:29:09',NULL,2,3,1,11540761,2),(114,99999999,NULL,'2019-03-14 12:41:45',NULL,2,3,1,99999999,1);
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `edit_lock`
--

DROP TABLE IF EXISTS `edit_lock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edit_lock` (
  `id` int(2) NOT NULL DEFAULT '2',
  `availability` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edit_lock`
--

LOCK TABLES `edit_lock` WRITE;
/*!40000 ALTER TABLE `edit_lock` DISABLE KEYS */;
INSERT INTO `edit_lock` VALUES (1,'Locked'),(2,'Available');
/*!40000 ALTER TABLE `edit_lock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `edit_post_comments`
--

DROP TABLE IF EXISTS `edit_post_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edit_post_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentCommentId` int(11) DEFAULT NULL,
  `content` longtext,
  `timePosted` datetime DEFAULT CURRENT_TIMESTAMP,
  `postId` int(11) NOT NULL,
  `commenterId` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_edit_post_comments_posts1_idx` (`postId`),
  KEY `fk_edit_post_comments_employee1_idx` (`commenterId`),
  CONSTRAINT `fk_edit_post_comments_employee1` FOREIGN KEY (`commenterId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edit_post_comments_posts1` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edit_post_comments`
--

LOCK TABLES `edit_post_comments` WRITE;
/*!40000 ALTER TABLE `edit_post_comments` DISABLE KEYS */;
INSERT INTO `edit_post_comments` VALUES (45,0,'asdsad','2019-03-08 20:38:45',131,11223344),(46,45,'gdfgfdg','2019-03-08 20:40:17',131,11540761),(47,0,'dasdad','2019-03-09 15:39:21',134,11223344),(48,0,'jhgjg','2019-03-09 18:49:57',130,99999999),(49,48,'Hey, I want to change some stuff can you unpublish for me!','2019-03-09 18:50:14',130,99999999),(50,0,'dsasd','2019-03-09 18:50:45',130,99999999),(51,0,'yay thanls!','2019-03-09 18:51:46',130,99999999),(52,0,'Hellow\r\n','2019-03-10 22:00:12',132,11223344),(53,0,'Nice post','2019-03-10 22:32:38',138,11223344),(54,53,'Thanks\r\n','2019-03-10 22:32:43',138,99999999),(55,52,'hellow','2019-03-10 23:17:26',132,11540761),(56,0,'Hello','2019-03-11 13:32:17',133,11540761),(57,56,'hello gain\r\n','2019-03-11 13:32:24',133,11540761),(58,47,'jnjk','2019-03-16 15:09:04',134,99999999);
/*!40000 ALTER TABLE `edit_post_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `edms_roles`
--

DROP TABLE IF EXISTS `edms_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edms_roles` (
  `id` int(11) NOT NULL,
  `roleName` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edms_roles`
--

LOCK TABLES `edms_roles` WRITE;
/*!40000 ALTER TABLE `edms_roles` DISABLE KEYS */;
INSERT INTO `edms_roles` VALUES (1,'READER'),(2,'SECRETARY'),(3,'EXECUTIVE BOARD'),(4,'PRESIDENT');
/*!40000 ALTER TABLE `edms_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee` (
  `EMP_ID` int(8) NOT NULL,
  `PASS_WORD` varchar(45) NOT NULL,
  `FIRSTNAME` varchar(45) NOT NULL,
  `LASTNAME` varchar(45) NOT NULL,
  `DATE_CREATED` datetime NOT NULL,
  `DATE_REMOVED` datetime DEFAULT NULL,
  `ACC_STATUS` int(1) NOT NULL,
  `FIRST_CHANGE_PW` tinyint(4) NOT NULL,
  `MEMBER_ID` int(11) DEFAULT NULL,
  `PART_TIME_LOANED` tinytext,
  `EDMS_ROLE` int(11) NOT NULL DEFAULT '1',
  `FRAP_ROLE` int(11) NOT NULL DEFAULT '1',
  `CMS_ROLE` int(11) NOT NULL DEFAULT '1',
  `SYS_ROLE` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`EMP_ID`),
  KEY `fk_employee_user_status1_idx` (`ACC_STATUS`),
  KEY `fk_member_id_idx` (`MEMBER_ID`),
  KEY `fk_employee_edms_roles1_idx` (`EDMS_ROLE`),
  KEY `fk_employee_frap_roles1_idx` (`FRAP_ROLE`),
  KEY `fk_employee_cms_roles1_idx` (`CMS_ROLE`),
  KEY `fk_employee_sys_roles1_idx` (`SYS_ROLE`),
  CONSTRAINT `fk_employee_cms_roles1` FOREIGN KEY (`CMS_ROLE`) REFERENCES `cms_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_employee_edms_roles1` FOREIGN KEY (`EDMS_ROLE`) REFERENCES `edms_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_employee_frap_roles1` FOREIGN KEY (`FRAP_ROLE`) REFERENCES `frap_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_employee_sys_roles1` FOREIGN KEY (`SYS_ROLE`) REFERENCES `sys_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_employee_user_status1` FOREIGN KEY (`ACC_STATUS`) REFERENCES `user_status` (`STATUS_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_member_id` FOREIGN KEY (`MEMBER_ID`) REFERENCES `member` (`MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (11223344,'*A4B6157319038724E3560894F7F932C8886EBFCF','Samuel','Malijan','1990-04-03 00:00:00',NULL,1,1,11223344,'N/A',2,1,3,1),(11540761,'*A4B6157319038724E3560894F7F932C8886EBFCF','Christian Nicole','Alderite','1990-04-03 00:00:00',NULL,1,1,11540761,'N/A',3,1,4,1),(99999999,'*A4B6157319038724E3560894F7F932C8886EBFCF','Jo','Melton','1990-04-03 00:00:00',NULL,1,1,99999999,'N/A',2,2,2,1);
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_status`
--

DROP TABLE IF EXISTS `event_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_status`
--

LOCK TABLES `event_status` WRITE;
/*!40000 ALTER TABLE `event_status` DISABLE KEYS */;
INSERT INTO `event_status` VALUES (1,'Pending'),(2,'Approved'),(3,'On Going'),(4,'Finished'),(5,'Cancelled');
/*!40000 ALTER TABLE `event_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posterId` int(8) NOT NULL,
  `statusId` int(11) NOT NULL DEFAULT '1',
  `title` varchar(45) DEFAULT NULL,
  `location` varchar(45) DEFAULT NULL,
  `description` blob,
  `startTime` varchar(100) DEFAULT NULL,
  `endTime` varchar(100) DEFAULT NULL,
  `goingCount` int(11) DEFAULT NULL,
  `GOOGLE_EVENTID` varchar(100) DEFAULT NULL,
  `GOOGLE_EVENTLINK` longtext,
  `firstCreated` datetime DEFAULT CURRENT_TIMESTAMP,
  `lastUpdated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_events_employee1_idx` (`posterId`),
  KEY `fk_events_event_status1_idx` (`statusId`),
  CONSTRAINT `fk_events_employee1` FOREIGN KEY (`posterId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_events_event_status1` FOREIGN KEY (`statusId`) REFERENCES `event_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events_rsvp`
--

DROP TABLE IF EXISTS `events_rsvp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events_rsvp` (
  `eventId` int(11) NOT NULL,
  `attendeeId` int(8) NOT NULL,
  `rsvp_status` int(11) NOT NULL DEFAULT '1',
  KEY `fk_events_has_employee_employee1_idx` (`attendeeId`),
  KEY `fk_events_has_employee_events1_idx` (`eventId`),
  KEY `fk_events_rsvp_rsvp_status1` (`rsvp_status`),
  CONSTRAINT `fk_events_has_employee_employee1` FOREIGN KEY (`attendeeId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_events_has_employee_events1` FOREIGN KEY (`eventId`) REFERENCES `events` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_events_rsvp_rsvp_status1` FOREIGN KEY (`rsvp_status`) REFERENCES `rsvp_status` (`rsvpId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events_rsvp`
--

LOCK TABLES `events_rsvp` WRITE;
/*!40000 ALTER TABLE `events_rsvp` DISABLE KEYS */;
/*!40000 ALTER TABLE `events_rsvp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faculty_manual`
--

DROP TABLE IF EXISTS `faculty_manual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faculty_manual` (
  `id` int(3) NOT NULL,
  `statusId` int(11) NOT NULL,
  `year` varchar(45) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `timePublished` datetime DEFAULT NULL,
  `revisionsStarted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `revisionsEnded` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_faculty_manual_manual_status1_idx` (`statusId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculty_manual`
--

LOCK TABLES `faculty_manual` WRITE;
/*!40000 ALTER TABLE `faculty_manual` DISABLE KEYS */;
INSERT INTO `faculty_manual` VALUES (1,2,'2015','Faculty Manual 2015',NULL,'2019-03-13 14:33:21',NULL),(2,2,'2018','Faculty Manual 2018',NULL,'2019-03-13 14:33:21',NULL),(3,1,'2021','Faculty Manual 2021',NULL,'2019-03-13 14:33:21',NULL);
/*!40000 ALTER TABLE `faculty_manual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `falp_requirements`
--

DROP TABLE IF EXISTS `falp_requirements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `falp_requirements` (
  `REQ_ID` int(9) NOT NULL AUTO_INCREMENT,
  `LOAN_ID` int(9) NOT NULL,
  `MEMBER_ID` int(8) NOT NULL,
  `ICR_DIR` varchar(255) NOT NULL,
  `PAYSLIP_DIR` varchar(255) NOT NULL,
  `EMP_ID_DIR` varchar(255) NOT NULL,
  `GOV_ID_DIR` varchar(255) NOT NULL,
  PRIMARY KEY (`REQ_ID`,`LOAN_ID`,`MEMBER_ID`),
  KEY `fk_bank_requirements_loans1_idx` (`LOAN_ID`,`MEMBER_ID`),
  CONSTRAINT `fk_bank_requirements_loans1` FOREIGN KEY (`LOAN_ID`, `MEMBER_ID`) REFERENCES `loans` (`LOAN_ID`, `MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `falp_requirements`
--

LOCK TABLES `falp_requirements` WRITE;
/*!40000 ALTER TABLE `falp_requirements` DISABLE KEYS */;
/*!40000 ALTER TABLE `falp_requirements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `father`
--

DROP TABLE IF EXISTS `father`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `father` (
  `RECORD_ID` int(9) NOT NULL,
  `MEMBER_ID` int(8) NOT NULL,
  `LASTNAME` varchar(45) DEFAULT NULL,
  `FIRSTNAME` varchar(45) DEFAULT NULL,
  `MIDDLENAME` varchar(45) DEFAULT NULL,
  `BIRTHDATE` varchar(45) DEFAULT NULL,
  `STATUS` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`RECORD_ID`,`MEMBER_ID`),
  KEY `fk_father_health_aid1_idx` (`RECORD_ID`,`MEMBER_ID`),
  CONSTRAINT `fk_father_health_aid1` FOREIGN KEY (`RECORD_ID`, `MEMBER_ID`) REFERENCES `health_aid` (`RECORD_ID`, `MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `father`
--

LOCK TABLES `father` WRITE;
/*!40000 ALTER TABLE `father` DISABLE KEYS */;
/*!40000 ALTER TABLE `father` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file_audit_table`
--

DROP TABLE IF EXISTS `file_audit_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file_audit_table` (
  `actionID` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `dateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`actionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_audit_table`
--

LOCK TABLES `file_audit_table` WRITE;
/*!40000 ALTER TABLE `file_audit_table` DISABLE KEYS */;
/*!40000 ALTER TABLE `file_audit_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `frap_roles`
--

DROP TABLE IF EXISTS `frap_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `frap_roles` (
  `id` int(11) NOT NULL,
  `roleName` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frap_roles`
--

LOCK TABLES `frap_roles` WRITE;
/*!40000 ALTER TABLE `frap_roles` DISABLE KEYS */;
INSERT INTO `frap_roles` VALUES (1,'MEMBER'),(2,'SECRETARY'),(3,'EXECUTIVE BOARD'),(4,'PRESIDENT');
/*!40000 ALTER TABLE `frap_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gdrive_folderid`
--

DROP TABLE IF EXISTS `gdrive_folderid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gdrive_folderid` (
  `memberID` int(11) NOT NULL,
  `folderID` varchar(245) DEFAULT NULL,
  PRIMARY KEY (`memberID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gdrive_folderid`
--

LOCK TABLES `gdrive_folderid` WRITE;
/*!40000 ALTER TABLE `gdrive_folderid` DISABLE KEYS */;
/*!40000 ALTER TABLE `gdrive_folderid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `health_aid`
--

DROP TABLE IF EXISTS `health_aid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `health_aid` (
  `RECORD_ID` int(9) NOT NULL AUTO_INCREMENT,
  `MEMBER_ID` int(8) NOT NULL,
  `APP_STATUS` int(1) NOT NULL,
  `DATE_APPLIED` datetime NOT NULL,
  `DATE_APPROVED` datetime DEFAULT NULL,
  `EMP_ID` int(8) DEFAULT NULL,
  PRIMARY KEY (`RECORD_ID`,`MEMBER_ID`),
  KEY `fk_health_aid_member1_idx` (`MEMBER_ID`),
  CONSTRAINT `fk_health_aid_member1` FOREIGN KEY (`MEMBER_ID`) REFERENCES `member` (`MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `health_aid`
--

LOCK TABLES `health_aid` WRITE;
/*!40000 ALTER TABLE `health_aid` DISABLE KEYS */;
/*!40000 ALTER TABLE `health_aid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lifetime`
--

DROP TABLE IF EXISTS `lifetime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lifetime` (
  `MEMBER_ID` int(8) NOT NULL,
  `PRIMARY` varchar(135) DEFAULT NULL,
  `SECONDARY` varchar(135) DEFAULT NULL,
  `ORG` varchar(135) DEFAULT NULL,
  `APP_STATUS` int(1) NOT NULL,
  `DATE_ADDED` date NOT NULL,
  `EMP_ID` int(8) NOT NULL,
  PRIMARY KEY (`MEMBER_ID`),
  KEY `fk_lifetime_employee1_idx` (`EMP_ID`),
  CONSTRAINT `fk_lifetime_employee1` FOREIGN KEY (`EMP_ID`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_lifetime_member1` FOREIGN KEY (`MEMBER_ID`) REFERENCES `member` (`MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lifetime`
--

LOCK TABLES `lifetime` WRITE;
/*!40000 ALTER TABLE `lifetime` DISABLE KEYS */;
/*!40000 ALTER TABLE `lifetime` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_plan`
--

DROP TABLE IF EXISTS `loan_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan_plan` (
  `LOAN_ID` int(5) NOT NULL AUTO_INCREMENT,
  `BANK_ID` int(3) NOT NULL,
  `MIN_AMOUNT` decimal(7,2) NOT NULL,
  `MAX_AMOUNT` decimal(7,2) NOT NULL,
  `INTEREST` int(2) NOT NULL,
  `MIN_TERM` int(3) NOT NULL,
  `MAX_TERM` int(3) NOT NULL,
  `MINIMUM_SALARY` decimal(7,2) DEFAULT '0.00',
  `STATUS` tinyint(4) NOT NULL,
  PRIMARY KEY (`LOAN_ID`),
  KEY `fk_bank_loan_plan_banks1_idx` (`BANK_ID`),
  CONSTRAINT `fk_bank_loan_plan_banks1` FOREIGN KEY (`BANK_ID`) REFERENCES `banks` (`BANK_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_plan`
--

LOCK TABLES `loan_plan` WRITE;
/*!40000 ALTER TABLE `loan_plan` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_ref_docs`
--

DROP TABLE IF EXISTS `loan_ref_docs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan_ref_docs` (
  `LOAN_ID` int(9) NOT NULL,
  `MEMBER_ID` int(8) NOT NULL,
  `DOC_ID` int(11) NOT NULL,
  KEY `fk_loan_ref_docs_loans1_idx` (`LOAN_ID`,`MEMBER_ID`),
  KEY `fk_loan_ref_docs_documents1_idx` (`DOC_ID`),
  CONSTRAINT `fk_loan_ref_docs_documents1` FOREIGN KEY (`DOC_ID`) REFERENCES `documents` (`documentId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_loan_ref_docs_loans1` FOREIGN KEY (`LOAN_ID`, `MEMBER_ID`) REFERENCES `loans` (`LOAN_ID`, `MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_ref_docs`
--

LOCK TABLES `loan_ref_docs` WRITE;
/*!40000 ALTER TABLE `loan_ref_docs` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_ref_docs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loan_status`
--

DROP TABLE IF EXISTS `loan_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loan_status` (
  `STATUS_ID` int(11) NOT NULL,
  `STATUS` varchar(45) NOT NULL,
  PRIMARY KEY (`STATUS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_status`
--

LOCK TABLES `loan_status` WRITE;
/*!40000 ALTER TABLE `loan_status` DISABLE KEYS */;
INSERT INTO `loan_status` VALUES (1,'PENDING'),(2,'ACTIVE'),(3,'MATURED');
/*!40000 ALTER TABLE `loan_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loans`
--

DROP TABLE IF EXISTS `loans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loans` (
  `LOAN_ID` int(9) NOT NULL AUTO_INCREMENT,
  `MEMBER_ID` int(8) NOT NULL,
  `LOAN_DETAIL_ID` int(5) NOT NULL,
  `AMOUNT` decimal(8,2) NOT NULL,
  `INTEREST` int(2) NOT NULL,
  `PAYMENT_TERMS` int(3) NOT NULL,
  `PAYABLE` decimal(8,2) NOT NULL,
  `PER_PAYMENT` decimal(8,2) NOT NULL,
  `AMOUNT_PAID` decimal(8,2) DEFAULT '0.00',
  `PAYMENTS_MADE` int(3) DEFAULT '0',
  `MIN_SALARY` decimal(8,2) DEFAULT NULL,
  `APP_STATUS` int(1) NOT NULL,
  `LOAN_STATUS` int(1) NOT NULL,
  `DATE_APPLIED` datetime NOT NULL,
  `DATE_APPROVED` datetime DEFAULT NULL,
  `EMP_ID` int(8) DEFAULT NULL,
  `PICKUP_STATUS` int(1) NOT NULL,
  `DATE_MATURED` date DEFAULT NULL,
  PRIMARY KEY (`LOAN_ID`,`MEMBER_ID`),
  KEY `fk_bank_loans_member1_idx` (`MEMBER_ID`),
  KEY `fk_bank_loans_bank_loan_plan1_idx` (`LOAN_DETAIL_ID`),
  KEY `fk_bank_loans_app_status1_idx` (`APP_STATUS`),
  KEY `fk_bank_loans_loan_status1_idx` (`LOAN_STATUS`),
  KEY `fk_bank_loans_pickup_status1_idx` (`PICKUP_STATUS`),
  CONSTRAINT `fk_bank_loans_app_status1` FOREIGN KEY (`APP_STATUS`) REFERENCES `app_status` (`STATUS_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bank_loans_bank_loan_plan1` FOREIGN KEY (`LOAN_DETAIL_ID`) REFERENCES `loan_plan` (`LOAN_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bank_loans_loan_status1` FOREIGN KEY (`LOAN_STATUS`) REFERENCES `loan_status` (`STATUS_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bank_loans_member1` FOREIGN KEY (`MEMBER_ID`) REFERENCES `member` (`MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bank_loans_pickup_status1` FOREIGN KEY (`PICKUP_STATUS`) REFERENCES `pickup_status` (`STATUS_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loans`
--

LOCK TABLES `loans` WRITE;
/*!40000 ALTER TABLE `loans` DISABLE KEYS */;
/*!40000 ALTER TABLE `loans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manual_categories`
--

DROP TABLE IF EXISTS `manual_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manual_categories` (
  `id` int(3) NOT NULL,
  `category` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manual_categories`
--

LOCK TABLES `manual_categories` WRITE;
/*!40000 ALTER TABLE `manual_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `manual_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manual_categories_has_faculty_manual`
--

DROP TABLE IF EXISTS `manual_categories_has_faculty_manual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manual_categories_has_faculty_manual` (
  `manual_categories_id` int(3) NOT NULL,
  `faculty_manual_id` int(3) NOT NULL,
  `category` varchar(45) DEFAULT NULL,
  UNIQUE KEY `manual_categories_has_faculty_manualcol_UNIQUE` (`category`),
  KEY `fk_manual_categories_has_faculty_manual_faculty_manual1_idx` (`faculty_manual_id`),
  KEY `fk_manual_categories_has_faculty_manual_manual_categories1_idx` (`manual_categories_id`),
  CONSTRAINT `fk_manual_categories_has_faculty_manual_faculty_manual1` FOREIGN KEY (`faculty_manual_id`) REFERENCES `faculty_manual` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_manual_categories_has_faculty_manual_manual_categories1` FOREIGN KEY (`manual_categories_id`) REFERENCES `manual_categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manual_categories_has_faculty_manual`
--

LOCK TABLES `manual_categories_has_faculty_manual` WRITE;
/*!40000 ALTER TABLE `manual_categories_has_faculty_manual` DISABLE KEYS */;
/*!40000 ALTER TABLE `manual_categories_has_faculty_manual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `manual_status`
--

DROP TABLE IF EXISTS `manual_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manual_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `manual_status`
--

LOCK TABLES `manual_status` WRITE;
/*!40000 ALTER TABLE `manual_status` DISABLE KEYS */;
INSERT INTO `manual_status` VALUES (1,'On Going'),(2,'Published'),(3,'Superseded'),(4,'Archived');
/*!40000 ALTER TABLE `manual_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member` (
  `MEMBER_ID` int(8) NOT NULL,
  `LASTNAME` varchar(45) NOT NULL,
  `FIRSTNAME` varchar(45) NOT NULL,
  `MIDDLENAME` varchar(45) NOT NULL,
  `SEX` tinyint(4) NOT NULL,
  `CIV_STATUS` int(1) NOT NULL,
  `DEPT_ID` int(2) NOT NULL,
  `BIRTHDATE` date NOT NULL,
  `DATE_HIRED` date NOT NULL,
  `HOME_ADDRESS` varchar(100) NOT NULL,
  `BUSINESS_ADDRESS` varchar(100) DEFAULT NULL,
  `HOME_NUM` int(11) NOT NULL,
  `BUSINESS_NUM` int(11) DEFAULT NULL,
  `USER_STATUS` int(1) NOT NULL,
  `MEMBERSHIP_STATUS` int(1) NOT NULL,
  `DATE_APPLIED` datetime NOT NULL,
  `DATE_APPROVED` datetime DEFAULT NULL,
  `DATE_REMOVED` datetime DEFAULT NULL,
  `EMP_ID_APPROVE` int(8) DEFAULT NULL,
  `EMP_ID_REMOVE` int(8) DEFAULT NULL,
  `CAMPUS` varchar(255) DEFAULT 'De La Salle University - Manila',
  `PART_TIME_LOANED` varchar(3) DEFAULT 'N/A',
  PRIMARY KEY (`MEMBER_ID`),
  KEY `fk_membership_app_status1_idx` (`MEMBERSHIP_STATUS`),
  KEY `fk_membership_user_status1_idx` (`USER_STATUS`),
  KEY `fk_membership_civ_status1_idx` (`CIV_STATUS`),
  KEY `fk_membership_ref_department1_idx` (`DEPT_ID`),
  CONSTRAINT `fk_membership_app_status1` FOREIGN KEY (`MEMBERSHIP_STATUS`) REFERENCES `app_status` (`STATUS_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_membership_civ_status1` FOREIGN KEY (`CIV_STATUS`) REFERENCES `civ_status` (`STATUS_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_membership_ref_department1` FOREIGN KEY (`DEPT_ID`) REFERENCES `ref_department` (`DEPT_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_membership_user_status1` FOREIGN KEY (`USER_STATUS`) REFERENCES `user_status` (`STATUS_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (11223344,'Malijan','Samuel','Gabrielle',1,1,1,'1990-04-03','1990-04-03','Random','Random',345642234,234324233,1,2,'1990-04-03 00:00:00','1990-04-03 00:00:00',NULL,99999999,NULL,'De La Salle University - Manila','N/A'),(11540761,'Alderite','Christian Nicole','Petallana',1,1,1,'1990-04-03','1990-04-03','Random','Random',345642234,234324233,1,2,'1990-04-03 00:00:00','1990-04-03 00:00:00',NULL,99999999,NULL,'De La Salle University - Manila','N/A'),(99999999,'Melton','Jo','N/A',1,1,1,'1990-04-03','1990-04-03','Manila','Manila',14369,14369,1,2,'1990-04-03 00:00:00','1990-04-03 00:00:00',NULL,NULL,NULL,'De La Salle University - Manila','N/A');
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_account`
--

DROP TABLE IF EXISTS `member_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_account` (
  `MEMBER_ID` int(8) NOT NULL,
  `PASSWORD` varchar(41) NOT NULL,
  `FIRST_CHANGE_PW` tinyint(4) NOT NULL,
  PRIMARY KEY (`MEMBER_ID`),
  UNIQUE KEY `MEMBER_ID_UNIQUE` (`MEMBER_ID`),
  CONSTRAINT `fk_member_membership1` FOREIGN KEY (`MEMBER_ID`) REFERENCES `member` (`MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_account`
--

LOCK TABLES `member_account` WRITE;
/*!40000 ALTER TABLE `member_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `member_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mother`
--

DROP TABLE IF EXISTS `mother`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mother` (
  `RECORD_ID` int(9) NOT NULL,
  `MEMBER_ID` int(8) NOT NULL,
  `LASTNAME` varchar(45) DEFAULT NULL,
  `FIRSTNAME` varchar(45) DEFAULT NULL,
  `MIDDLENAME` varchar(45) DEFAULT NULL,
  `BIRTHDATE` varchar(45) DEFAULT NULL,
  `STATUS` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`RECORD_ID`,`MEMBER_ID`),
  KEY `fk_mother_health_aid1_idx` (`RECORD_ID`,`MEMBER_ID`),
  CONSTRAINT `fk_mother_health_aid1` FOREIGN KEY (`RECORD_ID`, `MEMBER_ID`) REFERENCES `health_aid` (`RECORD_ID`, `MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mother`
--

LOCK TABLES `mother` WRITE;
/*!40000 ALTER TABLE `mother` DISABLE KEYS */;
/*!40000 ALTER TABLE `mother` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pickup_status`
--

DROP TABLE IF EXISTS `pickup_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pickup_status` (
  `STATUS_ID` int(1) NOT NULL,
  `STATUS` varchar(45) NOT NULL,
  PRIMARY KEY (`STATUS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pickup_status`
--

LOCK TABLES `pickup_status` WRITE;
/*!40000 ALTER TABLE `pickup_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `pickup_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll_options`
--

DROP TABLE IF EXISTS `poll_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option` varchar(255) NOT NULL,
  `pollId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pollId_idx` (`pollId`),
  CONSTRAINT `pollId` FOREIGN KEY (`pollId`) REFERENCES `polls` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_options`
--

LOCK TABLES `poll_options` WRITE;
/*!40000 ALTER TABLE `poll_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `poll_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll_response_type`
--

DROP TABLE IF EXISTS `poll_response_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_response_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_response_type`
--

LOCK TABLES `poll_response_type` WRITE;
/*!40000 ALTER TABLE `poll_response_type` DISABLE KEYS */;
INSERT INTO `poll_response_type` VALUES (1,'Single'),(2,'Multiple');
/*!40000 ALTER TABLE `poll_response_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll_responses`
--

DROP TABLE IF EXISTS `poll_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_responses` (
  `respondentId` int(8) NOT NULL,
  `optionId` int(11) NOT NULL,
  KEY `optionId_idx` (`optionId`),
  KEY `respondentId_idx` (`respondentId`),
  CONSTRAINT `optionId` FOREIGN KEY (`optionId`) REFERENCES `poll_options` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `respondentId` FOREIGN KEY (`respondentId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_responses`
--

LOCK TABLES `poll_responses` WRITE;
/*!40000 ALTER TABLE `poll_responses` DISABLE KEYS */;
/*!40000 ALTER TABLE `poll_responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `polls`
--

DROP TABLE IF EXISTS `polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` longtext,
  `approvedById` int(8) DEFAULT NULL,
  `authorId` int(8) DEFAULT NULL,
  `responseType` int(2) DEFAULT NULL,
  `timeCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUpdated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `approvedById_idx` (`approvedById`),
  KEY `authorId_idx` (`authorId`),
  KEY `responseTypeId_idx` (`responseType`),
  CONSTRAINT `approvedById` FOREIGN KEY (`approvedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `authorId` FOREIGN KEY (`authorId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `responseTypeId` FOREIGN KEY (`responseType`) REFERENCES `poll_response_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `polls`
--

LOCK TABLES `polls` WRITE;
/*!40000 ALTER TABLE `polls` DISABLE KEYS */;
INSERT INTO `polls` VALUES (1,'$question',99999999,99999999,1,'2019-03-11 15:01:29','2019-03-11 15:01:29'),(2,'$question',99999999,99999999,1,'2019-03-11 15:03:24','2019-03-11 15:03:24'),(3,'$question',99999999,99999999,1,'2019-03-11 15:03:26','2019-03-11 15:03:26'),(4,'dknsljfn',11540761,11540761,1,'2019-03-11 15:04:36','2019-03-11 15:04:36'),(5,'',99999999,99999999,1,'2019-03-13 10:00:58','2019-03-13 10:00:58'),(6,'What is the question?',99999999,99999999,1,'2019-03-13 10:01:43','2019-03-13 10:01:43'),(7,'Inquiry ',99999999,99999999,1,'2019-03-13 10:03:47','2019-03-13 10:03:47'),(8,'dknsljfn',11540761,11540761,1,'2019-03-13 15:31:55','2019-03-13 15:31:55'),(9,'bhjb',99999999,99999999,1,'2019-03-17 11:18:57','2019-03-17 11:18:57');
/*!40000 ALTER TABLE `polls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_activity`
--

DROP TABLE IF EXISTS `post_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` longtext,
  `displayToId` int(8) DEFAULT NULL,
  `postId` int(11) DEFAULT NULL,
  `timeStamp` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `EMP_ID_idx` (`displayToId`),
  KEY `postId_idx` (`postId`),
  CONSTRAINT `EMP_ID` FOREIGN KEY (`displayToId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `postId` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=335 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_activity`
--

LOCK TABLES `post_activity` WRITE;
/*!40000 ALTER TABLE `post_activity` DISABLE KEYS */;
INSERT INTO `post_activity` VALUES (149,'Your post is now Pending Publication',99999999,130,'2019-03-09 17:34:18'),(150,'Your post is now Published',99999999,130,'2019-03-09 17:34:31'),(151,'Your post \"Announcement\" has been unpublished by Malijan, Samuel',99999999,130,'2019-03-09 18:08:17'),(152,'\"Announcement\" needs publication review',11540761,130,'2019-03-09 18:08:17'),(153,'Your post \"Announcement\" has been unpublished by Alderite, Christian Nicole',99999999,130,'2019-03-09 18:09:53'),(154,'\"Announcement\" needs publication review',11540761,130,'2019-03-09 18:09:53'),(155,'Your post \"Announcement\" has been published by Alderite, Christian Nicole',99999999,130,'2019-03-09 18:10:44'),(156,'Your post \"Hello, I\'m Jo. And this is my post. \" has been reviewed by Malijan, Samuel',11223344,135,'2019-03-09 18:11:25'),(157,'\"Hello, I\'m Jo. And this is my post. \" needs publication review',11540761,135,'2019-03-09 18:11:25'),(158,'Your post \"Hello, I\'m Jo. And this is my post. \" has been published by Alderite, Christian Nicole',11223344,135,'2019-03-09 18:12:14'),(159,'Your post \"Hello, I\'m Jo. And this is my post. \" has been unpublished by Malijan, Samuel',11223344,135,'2019-03-09 18:17:02'),(160,'\"Hello, I\'m Jo. And this is my post. \" needs publication review',11540761,135,'2019-03-09 18:17:02'),(161,'Your post \"Hello, I\'m Jo. And this is my post. \" has been published by Alderite, Christian Nicole',11223344,135,'2019-03-09 18:18:45'),(162,'Your post \"Hello, I\'m Jo. And this is my post. \" has been unpublished by Alderite, Christian Nicole',11223344,135,'2019-03-09 18:19:03'),(163,'\"Hello, I\'m Jo. And this is my post. \" needs publication review',11540761,135,'2019-03-09 18:19:03'),(164,'Your post \"Hello, I\'m Jo. And this is my post. \" has been published by Alderite, Christian Nicole',11223344,135,'2019-03-09 18:22:07'),(188,'Your post \"Hello, I\'m Jo. And this is my post. \" has been rejected by Alderite, Christian Nicole',11223344,135,'2019-03-09 18:25:13'),(189,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:25:18'),(190,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:26:51'),(191,'\"De La Salle Universityf\" needs review',11223344,132,'2019-03-09 18:29:37'),(192,'\"De La Salle Universityf\" needs publication review',11540761,132,'2019-03-09 18:32:05'),(193,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:32:14'),(194,'Your post \"De La Salle Universityf\" has been unpublished by Malijan, Samuel',11540761,132,'2019-03-09 18:32:15'),(195,'\"De La Salle Universityf\" needs publication review',11540761,132,'2019-03-09 18:32:15'),(196,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:34:59'),(197,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:34:59'),(198,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:34:59'),(199,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:34:59'),(200,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:34:59'),(201,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:34:59'),(202,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:34:59'),(203,'Your post \"De La Salle Universityf\" has been unpublished by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:35:00'),(204,'\"De La Salle Universityf\" needs publication review',11540761,132,'2019-03-09 18:35:00'),(205,'\"De La Salle Universityf\" needs review',11223344,132,'2019-03-09 18:35:02'),(215,'Your post \"De La Salle Universityf\" has been reviewed by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:39:08'),(216,'\"De La Salle Universityf\" needs publication review',11540761,132,'2019-03-09 18:39:08'),(217,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:40:21'),(218,'Your post \"De La Salle Universityf\" has been unpublished by Malijan, Samuel',11540761,132,'2019-03-09 18:42:17'),(219,'\"De La Salle Universityf\" needs publication review',11540761,132,'2019-03-09 18:42:17'),(220,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:42:19'),(221,'\"De La Salle Universityf\" needs review',11223344,132,'2019-03-09 18:43:41'),(222,'Your post \"De La Salle Universityf\" has been reviewed by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:43:49'),(223,'\"De La Salle Universityf\" needs publication review',11540761,132,'2019-03-09 18:43:49'),(224,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:44:09'),(225,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:45:29'),(226,'\"De La Salle Universityf\" needs review',11223344,132,'2019-03-09 18:45:33'),(227,'\"De La Salle Universityf\" needs review',11223344,132,'2019-03-09 18:46:04'),(228,'Your post \"De La Salle Universityf\" has been archived by Malijan, Samuel',11540761,132,'2019-03-09 18:46:19'),(229,'\"De La Salle Universityf\" needs review',11223344,132,'2019-03-09 18:47:30'),(230,'Your post \"De La Salle Universityf\" has been restored by Malijan, Samuel',11540761,132,'2019-03-09 18:47:30'),(231,'Your post \"De La Salle Universityf\" has been archived by Malijan, Samuel',11540761,132,'2019-03-09 18:47:32'),(232,'Your post \"De La Salle Universityf\" has been restored by Malijan, Samuel',11540761,132,'2019-03-09 18:47:32'),(233,'\"De La Salle Universityf\" needs review',11223344,132,'2019-03-09 18:47:33'),(234,'Your post \"De La Salle Universityf\" has been restored by Malijan, Samuel',11540761,132,'2019-03-09 18:47:33'),(235,'Your post \"De La Salle Universityf\" has been reviewed by Malijan, Samuel',11540761,132,'2019-03-09 18:48:00'),(236,'\"De La Salle Universityf\" needs publication review',11540761,132,'2019-03-09 18:48:00'),(237,'Your post \"De La Salle Universityf\" has been restored by Malijan, Samuel',11540761,132,'2019-03-09 18:48:00'),(238,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:48:26'),(239,'Your post \"De La Salle Universityf\" has been restored by Malijan, Samuel',11540761,132,'2019-03-09 18:48:26'),(240,'\"De La Salle Universityf\" needs review',11223344,132,'2019-03-09 18:48:27'),(241,'Your post \"De La Salle Universityf\" has been restored by Malijan, Samuel',11540761,132,'2019-03-09 18:48:27'),(242,'Your post \"De La Salle Universityf\" has been reviewed by Malijan, Samuel',11540761,132,'2019-03-09 18:48:33'),(243,'\"De La Salle Universityf\" needs publication review',11540761,132,'2019-03-09 18:48:33'),(244,'Your post \"De La Salle Universityf\" has been restored by Malijan, Samuel',11540761,132,'2019-03-09 18:48:33'),(245,'Your post \"De La Salle Universityf\" has been rejected by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:48:37'),(246,'Your post \"De La Salle Universityf\" has been restored by Malijan, Samuel',11540761,132,'2019-03-09 18:48:37'),(247,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:48:42'),(248,'Your post \"De La Salle Universityf\" has been restored by Malijan, Samuel',11540761,132,'2019-03-09 18:48:42'),(249,'Your post \"De La Salle Universityf\" has been rejected by Malijan, Samuel',11540761,132,'2019-03-09 18:48:44'),(250,'Your post \"De La Salle Universityf\" has been restored by Malijan, Samuel',11540761,132,'2019-03-09 18:48:44'),(251,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-09 18:48:45'),(252,'Your post \"De La Salle Universityf\" has been restored by Malijan, Samuel',11540761,132,'2019-03-09 18:48:45'),(253,'Your post \"De La Salle Universityf\" has been rejected by Malijan, Samuel',11540761,132,'2019-03-09 18:48:47'),(254,'Your post \"De La Salle Universityf\" has been restored by Malijan, Samuel',11540761,132,'2019-03-09 18:48:47'),(255,'\"LA Session\" needs review',11223344,131,'2019-03-09 18:49:40'),(256,'Your post \"LA Session\" has been restored by Malijan, Samuel',99999999,131,'2019-03-09 18:49:40'),(257,'Your post \"Announcement\" has been rejected by Alderite, Christian Nicole',99999999,130,'2019-03-09 18:51:20'),(258,'Your post \"Announcement\" has been restored by Alderite, Christian Nicole',99999999,130,'2019-03-09 18:51:20'),(259,'\"Announcement\" needs review',11223344,130,'2019-03-09 18:51:54'),(260,'Your post \"Announcement\" has been restored by Alderite, Christian Nicole',99999999,130,'2019-03-09 18:51:54'),(261,'Your post \"Announcement\" has been reviewed by Alderite, Christian Nicole',99999999,130,'2019-03-09 18:52:13'),(262,'\"Announcement\" needs publication review',11540761,130,'2019-03-09 18:52:13'),(263,'Your post \"Announcement\" has been restored by Alderite, Christian Nicole',99999999,130,'2019-03-09 18:52:13'),(264,'Your post \"Announcement\" has been published by Alderite, Christian Nicole',99999999,130,'2019-03-09 18:52:24'),(265,'Your post \"Announcement\" has been restored by Malijan, Samuel',99999999,130,'2019-03-09 18:52:24'),(266,'\"SHIT\" needs review',11223344,137,'2019-03-09 18:55:13'),(267,'\"SHIT\" needs review',11223344,137,'2019-03-09 18:58:15'),(268,'\"SHIT\" needs review',11223344,137,'2019-03-09 18:58:49'),(269,'\"SHIT\" needs review',11223344,137,'2019-03-09 18:59:05'),(270,'\"SHIT\" needs review',11223344,137,'2019-03-09 18:59:51'),(271,'Your post \"Announcement\" has been published by Alderite, Christian Nicole',99999999,130,'2019-03-09 19:03:39'),(272,'Your post \"Announcement\" has been restored by Malijan, Samuel',99999999,130,'2019-03-09 19:03:39'),(273,'Your post \"Announcement\" has been archived by Malijan, Samuel',99999999,130,'2019-03-09 19:03:43'),(274,'Your post \"Announcement\" has been published by Alderite, Christian Nicole',99999999,130,'2019-03-09 19:03:44'),(275,'Your post \"Announcement\" has been restored by Malijan, Samuel',99999999,130,'2019-03-09 19:03:44'),(276,'Your post \"Announcement\" has been unpublished by Malijan, Samuel',99999999,130,'2019-03-09 19:05:52'),(277,'\"Announcement\" needs publication review',11540761,130,'2019-03-09 19:05:52'),(278,'Your post \"Announcement\" has been published by Alderite, Christian Nicole',99999999,130,'2019-03-09 19:05:54'),(279,'Your post \"Announcement\" has been unpublished by Alderite, Christian Nicole',99999999,130,'2019-03-09 19:05:56'),(280,'\"Announcement\" needs publication review',11540761,130,'2019-03-09 19:05:56'),(281,'Your post \"Announcement\" has been published by Alderite, Christian Nicole',99999999,130,'2019-03-09 19:06:01'),(282,'\"Announcement\" needs review',11223344,130,'2019-03-09 19:06:02'),(283,'\"SHIT\" needs publication review',11540761,137,'2019-03-09 19:07:12'),(284,'Your post \"SHIT\" has been archived by Malijan, Samuel',99999999,137,'2019-03-09 19:27:02'),(285,'Your post \"SHIT\" has been restored by Malijan, Samuel',99999999,137,'2019-03-09 19:27:04'),(286,'Your post \"SHIT\" has been archived by Alderite, Christian Nicole',99999999,137,'2019-03-09 19:27:06'),(287,'Your post \"SHIT\" has been restored by Alderite, Christian Nicole',99999999,137,'2019-03-09 19:27:11'),(288,'Your post \"SHIT\" has been archived by Alderite, Christian Nicole',99999999,137,'2019-03-09 19:30:03'),(289,'Your post \"SHIT\" has been restored by Alderite, Christian Nicole',99999999,137,'2019-03-09 19:30:05'),(290,'Your post \"SHIT\" has been archived by Alderite, Christian Nicole',99999999,137,'2019-03-09 19:30:14'),(291,'Your post \"SHIT\" has been published by Alderite, Christian Nicole',99999999,137,'2019-03-09 19:30:15'),(292,'Your post \"SHIT\" has been restored by Alderite, Christian Nicole',99999999,137,'2019-03-09 19:30:15'),(293,'Your post \"SHIT\" has been unpublished by Alderite, Christian Nicole',99999999,137,'2019-03-09 19:30:18'),(294,'\"SHIT\" needs publication review',11540761,137,'2019-03-09 19:30:18'),(295,'Your post \"SHIT\" has been published by Alderite, Christian Nicole',99999999,137,'2019-03-09 19:30:20'),(296,'Your post \"SHIT\" has been unpublished by Alderite, Christian Nicole',99999999,137,'2019-03-09 19:30:21'),(297,'\"SHIT\" needs publication review',11540761,137,'2019-03-09 19:30:21'),(298,'Your post \"SHIT\" has been published by Alderite, Christian Nicole',99999999,137,'2019-03-09 19:30:22'),(299,'Your post \"SHIT\" has been archived by Alderite, Christian Nicole',99999999,137,'2019-03-09 20:06:04'),(300,'Your post \"SHIT\" has been published by Alderite, Christian Nicole',99999999,137,'2019-03-09 20:06:05'),(301,'Your post \"SHIT\" has been restored by Alderite, Christian Nicole',99999999,137,'2019-03-09 20:06:05'),(302,'Your post \"LA Session\" has been reviewed by Malijan, Samuel',99999999,131,'2019-03-10 21:18:27'),(303,'\"LA Session\" needs publication review',11540761,131,'2019-03-10 21:18:27'),(304,'Your post \"LA Session\" has been rejected by Alderite, Christian Nicole',99999999,131,'2019-03-10 21:20:52'),(305,'\"LA Session\" needs review',11223344,131,'2019-03-10 21:21:23'),(306,'Your post \"LA Session\" has been rejected by Malijan, Samuel',99999999,131,'2019-03-10 21:21:51'),(307,'Your post \"De La Salle Universityf\" has been published by Alderite, Christian Nicole',11540761,132,'2019-03-10 21:24:08'),(308,'Your post \"Announcement\" has been archived by Alderite, Christian Nicole',99999999,130,'2019-03-10 21:57:38'),(309,'\"Announcement\" needs review',11223344,130,'2019-03-10 21:57:39'),(310,'Your post \"Announcement\" has been restored by Alderite, Christian Nicole',99999999,130,'2019-03-10 21:57:39'),(311,'Your post \"Announcement\" has been reviewed by Alderite, Christian Nicole',99999999,130,'2019-03-10 22:10:01'),(312,'\"Announcement\" needs publication review',11540761,130,'2019-03-10 22:10:01'),(313,'\"Announcement\" needs review',11223344,130,'2019-03-10 22:10:41'),(314,'\"AIRA ORPILLA\'S POST\" needs review',11223344,138,'2019-03-10 22:31:02'),(315,'\"AIRA ORPILLA\'S POST\" needs publication review',11540761,138,'2019-03-10 22:31:27'),(316,'\"AIRA ORPILLA\'S POST\" needs review',11223344,138,'2019-03-10 22:32:26'),(317,'\"SHIT\" needs review',11223344,137,'2019-03-11 13:31:42'),(318,'\"De La Salle University\" needs review',11223344,132,'2019-03-11 13:31:50'),(319,'Your post \"xdfs\" has been published by Alderite, Christian Nicole',11540761,133,'2019-03-11 13:32:06'),(320,'Your post \"TESTSTSTSTSTTSTS\" has been published by Alderite, Christian Nicole',11540761,139,'2019-03-11 16:17:00'),(321,'\"TESTSTSTSTSTTSTS\" needs publication review',11540761,139,'2019-03-11 16:17:02'),(322,'\"TESTSTSTSTSTTSTS\" needs review',11223344,139,'2019-03-11 16:17:03'),(323,'\"LA Session\" needs review',11223344,131,'2019-03-13 10:00:36'),(324,'\"TESTSTSTSTSTTSTS\" needs publication review',11540761,141,'2019-03-13 10:59:54'),(325,'Your post \"TESTSTSTSTSTTSTS\" has been unpublished by Malijan, Samuel',99999999,141,'2019-03-13 11:10:59'),(326,'\"TESTSTSTSTSTTSTS\" needs publication review',11540761,141,'2019-03-13 11:10:59'),(327,'Your post \"TESTSTSTSTSTTSTS\" has been published by Alderite, Christian Nicole',99999999,141,'2019-03-13 19:07:51'),(328,'\"TESTSTSTSTSTTSTS\" needs review',11223344,141,'2019-03-13 19:07:53'),(329,'Your post \"TESTSTSTSTSTTSTS\" has been unpublished by Malijan, Samuel',11223344,136,'2019-03-13 21:30:43'),(330,'\"TESTSTSTSTSTTSTS\" needs publication review',11540761,136,'2019-03-13 21:30:43'),(331,'Your post \"TESTSTSTSTSTTSTS\" has been published by Alderite, Christian Nicole',11223344,136,'2019-03-13 21:30:45'),(332,'Your post \"TESTSTSTSTSTTSTS\" has been unpublished by Alderite, Christian Nicole',11223344,136,'2019-03-13 21:31:58'),(333,'\"TESTSTSTSTSTTSTS\" needs publication review',11540761,136,'2019-03-13 21:31:58'),(334,'\"xcxzccxzcdaddsa dd\" needs review',11223344,142,'2019-03-17 12:24:04');
/*!40000 ALTER TABLE `post_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_comments`
--

DROP TABLE IF EXISTS `post_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postId` int(11) NOT NULL,
  `parentCommentId` int(11) NOT NULL DEFAULT '0',
  `content` longtext,
  `commenterId` int(8) NOT NULL,
  `timePosted` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_post_comments_posts1_idx` (`postId`),
  KEY `fk_post_comments_employee1_idx` (`commenterId`),
  CONSTRAINT `fk_post_comments_employee1` FOREIGN KEY (`commenterId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_comments_posts1` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_comments`
--

LOCK TABLES `post_comments` WRITE;
/*!40000 ALTER TABLE `post_comments` DISABLE KEYS */;
INSERT INTO `post_comments` VALUES (71,130,0,'Hello',11540761,'2019-03-09 11:55:58'),(72,132,0,'gregeg',11540761,'2019-03-09 13:19:22'),(73,132,72,'fdsfs',11540761,'2019-03-09 13:19:27');
/*!40000 ALTER TABLE `post_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_ref_versions`
--

DROP TABLE IF EXISTS `post_ref_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_ref_versions` (
  `postId` int(11) NOT NULL,
  `versionId` int(11) NOT NULL,
  KEY `fk_post_ref_versions_posts1_idx` (`postId`),
  KEY `fk_post_ref_versions_doc_versions1_idx` (`versionId`),
  CONSTRAINT `fk_post_ref_versions_doc_versions1` FOREIGN KEY (`versionId`) REFERENCES `doc_versions` (`versionId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_ref_versions_posts1` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_ref_versions`
--

LOCK TABLES `post_ref_versions` WRITE;
/*!40000 ALTER TABLE `post_ref_versions` DISABLE KEYS */;
INSERT INTO `post_ref_versions` VALUES (150,131),(150,124),(150,126),(150,120),(150,121);
/*!40000 ALTER TABLE `post_ref_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_status`
--

DROP TABLE IF EXISTS `post_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_status`
--

LOCK TABLES `post_status` WRITE;
/*!40000 ALTER TABLE `post_status` DISABLE KEYS */;
INSERT INTO `post_status` VALUES (1,'Draft'),(2,'Pending Review'),(3,'Pending Publication'),(4,'Published'),(5,'Archived'),(6,'Rejected');
/*!40000 ALTER TABLE `post_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_views`
--

DROP TABLE IF EXISTS `post_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_views` (
  `id` int(11) NOT NULL,
  `viewerId` int(8) NOT NULL,
  `typeId` int(2) NOT NULL,
  `timeStamp` datetime DEFAULT CURRENT_TIMESTAMP,
  KEY `fk_post_views_posts1_idx` (`id`),
  KEY `fk_post_views_employee1_idx` (`viewerId`),
  KEY `fk_post_views_post_types1_idx` (`typeId`),
  CONSTRAINT `fk_post_views_employee1` FOREIGN KEY (`viewerId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_views_post_types1` FOREIGN KEY (`typeId`) REFERENCES `view_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_views_posts1` FOREIGN KEY (`id`) REFERENCES `posts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_views`
--

LOCK TABLES `post_views` WRITE;
/*!40000 ALTER TABLE `post_views` DISABLE KEYS */;
INSERT INTO `post_views` VALUES (131,11540761,2,'2019-03-08 20:40:37'),(131,99999999,2,'2019-03-08 20:41:36'),(131,99999999,2,'2019-03-08 20:41:48'),(131,11223344,2,'2019-03-08 20:41:55'),(130,11540761,2,'2019-03-09 11:53:21'),(130,11540761,1,'2019-03-09 11:53:22'),(130,11540761,2,'2019-03-09 11:53:24'),(130,11540761,1,'2019-03-09 11:53:28'),(130,11540761,1,'2019-03-09 11:53:31'),(130,11540761,1,'2019-03-09 11:53:48'),(130,11540761,1,'2019-03-09 11:53:51'),(130,11540761,1,'2019-03-09 11:53:52'),(130,11540761,1,'2019-03-09 11:53:52'),(130,11540761,1,'2019-03-09 11:54:02'),(130,11540761,1,'2019-03-09 11:54:06'),(130,11540761,1,'2019-03-09 11:54:07'),(130,11540761,1,'2019-03-09 11:54:07'),(130,11540761,1,'2019-03-09 11:54:08'),(130,11540761,1,'2019-03-09 11:54:09'),(130,11540761,1,'2019-03-09 11:54:09'),(130,11540761,1,'2019-03-09 11:54:09'),(130,11540761,1,'2019-03-09 11:54:09'),(130,11540761,1,'2019-03-09 11:54:10'),(130,11540761,1,'2019-03-09 11:54:10'),(130,11540761,1,'2019-03-09 11:54:11'),(130,11540761,1,'2019-03-09 11:54:11'),(130,11540761,1,'2019-03-09 11:54:12'),(130,11540761,1,'2019-03-09 11:55:47'),(130,11540761,2,'2019-03-09 11:55:49'),(130,11540761,1,'2019-03-09 11:56:13'),(132,11540761,2,'2019-03-09 12:13:31'),(132,11540761,1,'2019-03-09 12:13:36'),(130,11540761,1,'2019-03-09 12:13:36'),(132,11540761,1,'2019-03-09 12:13:38'),(130,11540761,1,'2019-03-09 12:13:38'),(132,11540761,1,'2019-03-09 12:13:38'),(130,11540761,1,'2019-03-09 12:13:38'),(132,11540761,1,'2019-03-09 12:13:39'),(130,11540761,1,'2019-03-09 12:13:39'),(132,11540761,1,'2019-03-09 12:13:39'),(130,11540761,1,'2019-03-09 12:13:39'),(132,11540761,2,'2019-03-09 12:13:44'),(132,11540761,1,'2019-03-09 12:13:47'),(130,11540761,1,'2019-03-09 12:13:47'),(132,11540761,1,'2019-03-09 12:13:52'),(130,11540761,1,'2019-03-09 12:13:52'),(132,11540761,2,'2019-03-09 12:15:13'),(132,11540761,1,'2019-03-09 12:15:18'),(130,11540761,1,'2019-03-09 12:15:18'),(132,11540761,1,'2019-03-09 12:29:04'),(130,11540761,1,'2019-03-09 12:29:04'),(132,11540761,1,'2019-03-09 12:29:26'),(130,11540761,1,'2019-03-09 12:29:26'),(132,11540761,1,'2019-03-09 12:29:42'),(130,11540761,1,'2019-03-09 12:29:42'),(132,11540761,1,'2019-03-09 12:30:03'),(130,11540761,1,'2019-03-09 12:30:03'),(132,11540761,1,'2019-03-09 12:30:16'),(130,11540761,1,'2019-03-09 12:30:16'),(132,11540761,1,'2019-03-09 12:30:33'),(130,11540761,1,'2019-03-09 12:30:33'),(132,11540761,2,'2019-03-09 12:31:04'),(132,11540761,1,'2019-03-09 12:31:07'),(130,11540761,1,'2019-03-09 12:31:07'),(132,11540761,1,'2019-03-09 12:32:12'),(130,11540761,1,'2019-03-09 12:32:12'),(132,11540761,2,'2019-03-09 12:33:19'),(132,11540761,2,'2019-03-09 12:34:04'),(132,11540761,2,'2019-03-09 12:34:44'),(132,11540761,2,'2019-03-09 12:35:05'),(132,11540761,1,'2019-03-09 12:35:12'),(130,11540761,1,'2019-03-09 12:35:12'),(132,11540761,2,'2019-03-09 12:36:31'),(132,11540761,1,'2019-03-09 12:36:49'),(130,11540761,1,'2019-03-09 12:36:49'),(132,11540761,1,'2019-03-09 12:36:55'),(130,11540761,1,'2019-03-09 12:36:56'),(132,11540761,1,'2019-03-09 12:37:28'),(130,11540761,1,'2019-03-09 12:37:28'),(132,11540761,1,'2019-03-09 12:37:44'),(130,11540761,1,'2019-03-09 12:37:44'),(132,11540761,2,'2019-03-09 12:37:54'),(132,11540761,1,'2019-03-09 12:37:58'),(130,11540761,1,'2019-03-09 12:37:58'),(133,11540761,1,'2019-03-09 12:47:09'),(132,11540761,1,'2019-03-09 12:47:09'),(130,11540761,1,'2019-03-09 12:47:09'),(133,11540761,1,'2019-03-09 12:52:16'),(132,11540761,1,'2019-03-09 12:52:16'),(130,11540761,1,'2019-03-09 12:52:16'),(133,11540761,1,'2019-03-09 12:52:45'),(132,11540761,1,'2019-03-09 12:52:45'),(130,11540761,1,'2019-03-09 12:52:45'),(133,11540761,1,'2019-03-09 12:52:53'),(132,11540761,1,'2019-03-09 12:52:53'),(130,11540761,1,'2019-03-09 12:52:53'),(133,11540761,1,'2019-03-09 12:54:35'),(132,11540761,1,'2019-03-09 12:54:35'),(130,11540761,1,'2019-03-09 12:54:35'),(133,11540761,1,'2019-03-09 12:54:43'),(132,11540761,1,'2019-03-09 12:54:43'),(130,11540761,1,'2019-03-09 12:54:43'),(133,11540761,1,'2019-03-09 12:56:32'),(132,11540761,1,'2019-03-09 12:56:32'),(130,11540761,1,'2019-03-09 12:56:32'),(133,11540761,1,'2019-03-09 12:56:35'),(132,11540761,1,'2019-03-09 12:56:35'),(130,11540761,1,'2019-03-09 12:56:35'),(133,11540761,1,'2019-03-09 12:56:56'),(132,11540761,1,'2019-03-09 12:56:56'),(130,11540761,1,'2019-03-09 12:56:56'),(133,11540761,1,'2019-03-09 12:57:00'),(132,11540761,1,'2019-03-09 12:57:00'),(130,11540761,1,'2019-03-09 12:57:00'),(133,11540761,1,'2019-03-09 12:57:04'),(132,11540761,1,'2019-03-09 12:57:04'),(130,11540761,1,'2019-03-09 12:57:04'),(133,11540761,1,'2019-03-09 12:57:17'),(132,11540761,1,'2019-03-09 12:57:17'),(130,11540761,1,'2019-03-09 12:57:17'),(133,11540761,1,'2019-03-09 12:57:25'),(132,11540761,1,'2019-03-09 12:57:25'),(130,11540761,1,'2019-03-09 12:57:25'),(133,11540761,1,'2019-03-09 12:57:26'),(132,11540761,1,'2019-03-09 12:57:26'),(130,11540761,1,'2019-03-09 12:57:26'),(133,11540761,1,'2019-03-09 13:00:59'),(132,11540761,1,'2019-03-09 13:00:59'),(130,11540761,1,'2019-03-09 13:00:59'),(133,11540761,1,'2019-03-09 13:04:49'),(132,11540761,1,'2019-03-09 13:04:49'),(130,11540761,1,'2019-03-09 13:04:49'),(133,11540761,1,'2019-03-09 13:05:52'),(132,11540761,1,'2019-03-09 13:05:52'),(130,11540761,1,'2019-03-09 13:05:52'),(133,11540761,1,'2019-03-09 13:06:01'),(132,11540761,1,'2019-03-09 13:06:01'),(130,11540761,1,'2019-03-09 13:06:01'),(133,11540761,1,'2019-03-09 13:19:13'),(132,11540761,1,'2019-03-09 13:19:13'),(130,11540761,1,'2019-03-09 13:19:13'),(132,11540761,2,'2019-03-09 13:19:15'),(133,11540761,1,'2019-03-09 13:19:28'),(132,11540761,1,'2019-03-09 13:19:28'),(130,11540761,1,'2019-03-09 13:19:29'),(133,11540761,1,'2019-03-09 13:19:37'),(132,11540761,1,'2019-03-09 13:19:37'),(130,11540761,1,'2019-03-09 13:19:37'),(133,11540761,2,'2019-03-09 13:19:41'),(133,11540761,1,'2019-03-09 13:19:56'),(132,11540761,1,'2019-03-09 13:19:56'),(130,11540761,1,'2019-03-09 13:19:56'),(132,11540761,2,'2019-03-09 13:21:05'),(132,11540761,2,'2019-03-09 13:21:13'),(133,11540761,1,'2019-03-09 13:21:28'),(132,11540761,1,'2019-03-09 13:21:28'),(130,11540761,1,'2019-03-09 13:21:28'),(130,11540761,2,'2019-03-09 13:21:52'),(133,11540761,1,'2019-03-09 13:22:42'),(132,11540761,1,'2019-03-09 13:22:42'),(130,11540761,1,'2019-03-09 13:22:42'),(132,11540761,2,'2019-03-09 13:22:46'),(133,11540761,1,'2019-03-09 13:24:51'),(132,11540761,1,'2019-03-09 13:24:51'),(130,11540761,1,'2019-03-09 13:24:51'),(133,11540761,1,'2019-03-09 13:35:15'),(130,11540761,1,'2019-03-09 13:35:15'),(133,11540761,1,'2019-03-09 13:38:46'),(130,11540761,1,'2019-03-09 13:38:46'),(132,11540761,2,'2019-03-09 14:05:05'),(133,11540761,1,'2019-03-09 14:05:25'),(130,11540761,1,'2019-03-09 14:05:25'),(133,11540761,1,'2019-03-09 14:05:27'),(130,11540761,1,'2019-03-09 14:05:27'),(133,11540761,1,'2019-03-09 14:05:36'),(130,11540761,1,'2019-03-09 14:05:36'),(133,11540761,2,'2019-03-09 14:06:04'),(133,11540761,1,'2019-03-09 14:06:06'),(130,11540761,1,'2019-03-09 14:06:06'),(130,11540761,2,'2019-03-09 14:06:09'),(133,11540761,1,'2019-03-09 14:06:11'),(130,11540761,1,'2019-03-09 14:06:11'),(133,11540761,1,'2019-03-09 14:06:14'),(130,11540761,1,'2019-03-09 14:06:14'),(130,11540761,1,'2019-03-09 14:15:49'),(130,11540761,1,'2019-03-09 14:16:38'),(130,11540761,1,'2019-03-09 14:16:54'),(130,11540761,1,'2019-03-09 14:17:00'),(130,11540761,1,'2019-03-09 14:17:03'),(130,11540761,1,'2019-03-09 14:18:03'),(130,11540761,2,'2019-03-09 14:20:07'),(130,11540761,1,'2019-03-09 14:20:10'),(130,11540761,2,'2019-03-09 14:20:12'),(130,11540761,1,'2019-03-09 14:20:14'),(130,11540761,2,'2019-03-09 14:20:15'),(130,11540761,1,'2019-03-09 14:20:16'),(130,11540761,2,'2019-03-09 14:20:19'),(130,11540761,1,'2019-03-09 14:20:20'),(132,11540761,2,'2019-03-09 14:21:07'),(130,11223344,1,'2019-03-09 14:37:38'),(130,11540761,1,'2019-03-09 15:25:42'),(130,99999999,1,'2019-03-09 15:31:17'),(130,11223344,1,'2019-03-09 15:42:56'),(130,11223344,1,'2019-03-09 16:21:21'),(134,11540761,1,'2019-03-09 17:33:04'),(136,11540761,1,'2019-03-09 17:33:04'),(130,11540761,1,'2019-03-09 17:33:04'),(134,11540761,1,'2019-03-09 18:48:48'),(136,11540761,1,'2019-03-09 18:48:48'),(130,11540761,1,'2019-03-09 18:48:48'),(134,11540761,1,'2019-03-09 18:48:51'),(136,11540761,1,'2019-03-09 18:48:51'),(130,11540761,1,'2019-03-09 18:48:51'),(134,11540761,1,'2019-03-09 18:48:53'),(136,11540761,1,'2019-03-09 18:48:53'),(130,11540761,1,'2019-03-09 18:48:53'),(130,11540761,2,'2019-03-09 18:52:26'),(134,99999999,1,'2019-03-09 18:54:20'),(136,99999999,1,'2019-03-09 18:54:20'),(130,99999999,1,'2019-03-09 18:54:20'),(137,11540761,1,'2019-03-09 20:15:17'),(134,11540761,1,'2019-03-09 20:15:17'),(136,11540761,1,'2019-03-09 20:15:17'),(137,11223344,1,'2019-03-09 21:39:06'),(134,11223344,1,'2019-03-09 21:39:06'),(136,11223344,1,'2019-03-09 21:39:06'),(137,11223344,1,'2019-03-09 21:39:23'),(134,11223344,1,'2019-03-09 21:39:23'),(136,11223344,1,'2019-03-09 21:39:23'),(137,11223344,1,'2019-03-09 21:39:28'),(134,11223344,1,'2019-03-09 21:39:28'),(136,11223344,1,'2019-03-09 21:39:28'),(137,11223344,1,'2019-03-10 21:00:56'),(134,11223344,1,'2019-03-10 21:00:56'),(136,11223344,1,'2019-03-10 21:00:56'),(137,11223344,1,'2019-03-10 21:17:32'),(134,11223344,1,'2019-03-10 21:17:32'),(136,11223344,1,'2019-03-10 21:17:32'),(137,11223344,1,'2019-03-10 21:21:02'),(134,11223344,1,'2019-03-10 21:21:02'),(136,11223344,1,'2019-03-10 21:21:02'),(137,99999999,1,'2019-03-10 21:21:42'),(134,99999999,1,'2019-03-10 21:21:42'),(136,99999999,1,'2019-03-10 21:21:42'),(137,11540761,1,'2019-03-10 21:24:10'),(134,11540761,1,'2019-03-10 21:24:10'),(136,11540761,1,'2019-03-10 21:24:10'),(132,11540761,1,'2019-03-10 21:24:10'),(137,11540761,1,'2019-03-10 21:24:10'),(134,11540761,1,'2019-03-10 21:24:10'),(136,11540761,1,'2019-03-10 21:24:10'),(132,11540761,1,'2019-03-10 21:24:10'),(137,11223344,1,'2019-03-10 21:57:40'),(134,11223344,1,'2019-03-10 21:57:40'),(136,11223344,1,'2019-03-10 21:57:40'),(132,11223344,1,'2019-03-10 21:57:40'),(137,11223344,1,'2019-03-10 22:00:16'),(134,11223344,1,'2019-03-10 22:00:16'),(136,11223344,1,'2019-03-10 22:00:16'),(132,11223344,1,'2019-03-10 22:00:16'),(137,11223344,1,'2019-03-10 22:10:30'),(134,11223344,1,'2019-03-10 22:10:30'),(136,11223344,1,'2019-03-10 22:10:30'),(132,11223344,1,'2019-03-10 22:10:30'),(137,11540761,1,'2019-03-10 22:28:00'),(134,11540761,1,'2019-03-10 22:28:00'),(136,11540761,1,'2019-03-10 22:28:00'),(132,11540761,1,'2019-03-10 22:28:00'),(137,11540761,1,'2019-03-10 22:28:00'),(134,11540761,1,'2019-03-10 22:28:00'),(136,11540761,1,'2019-03-10 22:28:00'),(132,11540761,1,'2019-03-10 22:28:00'),(137,11540761,1,'2019-03-10 23:17:28'),(134,11540761,1,'2019-03-10 23:17:28'),(136,11540761,1,'2019-03-10 23:17:28'),(132,11540761,1,'2019-03-10 23:17:28'),(137,11540761,1,'2019-03-10 23:17:36'),(134,11540761,1,'2019-03-10 23:17:36'),(136,11540761,1,'2019-03-10 23:17:36'),(132,11540761,1,'2019-03-10 23:17:36'),(137,11540761,1,'2019-03-11 11:10:14'),(134,11540761,1,'2019-03-11 11:10:14'),(136,11540761,1,'2019-03-11 11:10:14'),(132,11540761,1,'2019-03-11 11:10:14'),(137,11540761,1,'2019-03-11 11:24:44'),(134,11540761,1,'2019-03-11 11:24:44'),(136,11540761,1,'2019-03-11 11:24:44'),(132,11540761,1,'2019-03-11 11:24:44'),(137,11540761,2,'2019-03-11 11:24:46'),(137,11540761,1,'2019-03-11 11:26:46'),(134,11540761,1,'2019-03-11 11:26:46'),(136,11540761,1,'2019-03-11 11:26:46'),(132,11540761,1,'2019-03-11 11:26:46'),(137,11540761,2,'2019-03-11 11:26:49'),(137,11540761,1,'2019-03-11 11:27:55'),(134,11540761,1,'2019-03-11 11:27:55'),(136,11540761,1,'2019-03-11 11:27:55'),(132,11540761,1,'2019-03-11 11:27:55'),(137,11540761,1,'2019-03-11 11:28:49'),(134,11540761,1,'2019-03-11 11:28:49'),(136,11540761,1,'2019-03-11 11:28:49'),(132,11540761,1,'2019-03-11 11:28:49'),(137,11540761,1,'2019-03-11 13:24:49'),(134,11540761,1,'2019-03-11 13:24:49'),(136,11540761,1,'2019-03-11 13:24:49'),(132,11540761,1,'2019-03-11 13:24:49'),(137,11540761,1,'2019-03-11 13:31:10'),(134,11540761,1,'2019-03-11 13:31:10'),(136,11540761,1,'2019-03-11 13:31:10'),(132,11540761,1,'2019-03-11 13:31:10'),(137,11540761,1,'2019-03-11 13:31:27'),(134,11540761,1,'2019-03-11 13:31:27'),(136,11540761,1,'2019-03-11 13:31:27'),(132,11540761,1,'2019-03-11 13:31:27'),(134,11540761,1,'2019-03-11 15:12:54'),(136,11540761,1,'2019-03-11 15:12:54'),(133,11540761,1,'2019-03-11 15:12:54'),(134,11540761,1,'2019-03-11 15:37:06'),(136,11540761,1,'2019-03-11 15:37:06'),(133,11540761,1,'2019-03-11 15:37:06'),(134,11540761,1,'2019-03-11 16:16:43'),(136,11540761,1,'2019-03-11 16:16:43'),(133,11540761,1,'2019-03-11 16:16:43'),(134,99999999,1,'2019-03-13 10:00:48'),(136,99999999,1,'2019-03-13 10:00:48'),(133,99999999,1,'2019-03-13 10:00:48'),(134,99999999,2,'2019-03-13 10:00:51'),(134,99999999,1,'2019-03-13 10:08:47'),(136,99999999,1,'2019-03-13 10:08:47'),(133,99999999,1,'2019-03-13 10:08:47'),(134,99999999,1,'2019-03-13 10:19:54'),(136,99999999,1,'2019-03-13 10:19:54'),(133,99999999,1,'2019-03-13 10:19:54'),(134,99999999,1,'2019-03-13 10:56:59'),(136,99999999,1,'2019-03-13 10:56:59'),(133,99999999,1,'2019-03-13 10:56:59'),(134,99999999,1,'2019-03-13 10:57:07'),(136,99999999,1,'2019-03-13 10:57:07'),(133,99999999,1,'2019-03-13 10:57:07'),(134,99999999,1,'2019-03-13 10:57:09'),(136,99999999,1,'2019-03-13 10:57:09'),(133,99999999,1,'2019-03-13 10:57:09'),(134,11223344,1,'2019-03-13 10:58:56'),(136,11223344,1,'2019-03-13 10:58:56'),(133,11223344,1,'2019-03-13 10:58:56'),(134,11540761,1,'2019-03-13 11:11:17'),(136,11540761,1,'2019-03-13 11:11:17'),(133,11540761,1,'2019-03-13 11:11:17'),(134,11540761,1,'2019-03-13 13:14:58'),(136,11540761,1,'2019-03-13 13:14:58'),(133,11540761,1,'2019-03-13 13:14:58'),(134,11540761,1,'2019-03-13 13:17:12'),(136,11540761,1,'2019-03-13 13:17:12'),(133,11540761,1,'2019-03-13 13:17:12'),(134,11540761,1,'2019-03-13 13:28:32'),(136,11540761,1,'2019-03-13 13:28:32'),(133,11540761,1,'2019-03-13 13:28:32'),(134,11540761,1,'2019-03-13 15:18:58'),(136,11540761,1,'2019-03-13 15:18:58'),(133,11540761,1,'2019-03-13 15:18:58'),(134,11540761,2,'2019-03-13 15:18:59'),(134,11540761,1,'2019-03-13 15:19:29'),(136,11540761,1,'2019-03-13 15:19:29'),(133,11540761,1,'2019-03-13 15:19:29'),(134,11540761,1,'2019-03-13 16:07:56'),(136,11540761,1,'2019-03-13 16:07:56'),(133,11540761,1,'2019-03-13 16:07:56'),(134,11540761,1,'2019-03-13 16:11:08'),(136,11540761,1,'2019-03-13 16:11:08'),(133,11540761,1,'2019-03-13 16:11:08'),(134,99999999,1,'2019-03-13 21:39:44'),(133,99999999,1,'2019-03-13 21:39:44'),(134,99999999,1,'2019-03-13 21:40:19'),(133,99999999,1,'2019-03-13 21:40:19'),(134,99999999,1,'2019-03-16 21:23:52'),(133,99999999,1,'2019-03-16 21:23:52'),(134,99999999,1,'2019-03-17 11:19:52'),(133,99999999,1,'2019-03-17 11:19:52'),(134,99999999,1,'2019-03-17 11:19:53'),(133,99999999,1,'2019-03-17 11:19:53'),(134,99999999,1,'2019-03-17 11:20:43'),(133,99999999,1,'2019-03-17 11:20:43'),(134,99999999,1,'2019-03-17 11:21:57'),(133,99999999,1,'2019-03-17 11:21:57'),(134,99999999,1,'2019-03-17 11:22:41'),(133,99999999,1,'2019-03-17 11:22:41'),(134,99999999,1,'2019-03-17 11:27:26'),(133,99999999,1,'2019-03-17 11:27:26'),(134,99999999,1,'2019-03-17 11:28:13'),(133,99999999,1,'2019-03-17 11:28:13'),(134,99999999,1,'2019-03-17 11:28:18'),(133,99999999,1,'2019-03-17 11:28:18'),(134,99999999,1,'2019-03-17 11:28:18'),(133,99999999,1,'2019-03-17 11:28:18'),(134,99999999,1,'2019-03-17 11:28:19'),(133,99999999,1,'2019-03-17 11:28:19'),(134,99999999,1,'2019-03-17 11:28:20'),(133,99999999,1,'2019-03-17 11:28:20'),(134,99999999,1,'2019-03-17 11:28:20'),(133,99999999,1,'2019-03-17 11:28:20'),(134,99999999,1,'2019-03-17 11:28:20'),(133,99999999,1,'2019-03-17 11:28:20'),(134,99999999,1,'2019-03-17 12:28:25'),(133,99999999,1,'2019-03-17 12:28:25');
/*!40000 ALTER TABLE `post_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `authorId` int(8) NOT NULL,
  `publisherId` int(8) DEFAULT NULL,
  `reviewedById` int(8) DEFAULT NULL,
  `archivedById` int(8) DEFAULT NULL,
  `rejectedById` int(8) DEFAULT NULL,
  `lockedById` int(8) DEFAULT NULL,
  `statusId` int(11) NOT NULL DEFAULT '1',
  `availabilityId` int(2) NOT NULL DEFAULT '2',
  `previousStatusId` int(11) DEFAULT NULL,
  `title` longtext NOT NULL,
  `body` blob,
  `firstCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUpdated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timePublished` datetime DEFAULT NULL,
  `permalink` longtext,
  PRIMARY KEY (`id`),
  KEY `fk_posts_post_status1_idx` (`statusId`),
  KEY `fk_posts_employee1_idx` (`authorId`),
  KEY `fk_posts_employee2_idx` (`publisherId`),
  KEY `fk_posts_table11_idx` (`availabilityId`),
  CONSTRAINT `fk_posts_employee1` FOREIGN KEY (`authorId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_posts_employee2` FOREIGN KEY (`publisherId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_posts_post_status1` FOREIGN KEY (`statusId`) REFERENCES `post_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_posts_table11` FOREIGN KEY (`availabilityId`) REFERENCES `edit_lock` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (130,99999999,11540761,11223344,11223344,NULL,11540761,2,2,2,'Announcement',_binary '<p>dshfuidshfduisfhuidsf</p>','2019-03-08 19:45:50','2019-03-10 22:10:41','2019-03-09 11:53:20','hello-i-m-jo-and-this-is-my-post-20190309115320'),(131,99999999,11540761,11223344,NULL,NULL,99999999,2,2,NULL,'LA Session',_binary '<p><img src=\"/FRAP_sd/uploads/cms/images/9de988efc15791ff0f6ff950cd251e9b61261901.png\" style=\"width: 566px;\" class=\"fr-fic fr-dib\"></p>','2019-03-08 20:38:25','2019-03-13 10:00:36','2019-03-08 20:40:34','la-session-20190308204034'),(132,11540761,11540761,11223344,11223344,NULL,11540761,2,2,2,'De La Salle University',_binary '<p>dsadadsadasd</p>','2019-03-09 12:13:28','2019-03-11 13:31:50','2019-03-09 12:13:28','de-la-salle-university-20190309121328'),(133,11540761,11540761,NULL,NULL,NULL,11540761,4,1,NULL,'xdfs',_binary '<p>dsfdsfsf</p>','2019-03-09 12:47:07','2019-03-11 13:32:10','2019-03-09 12:47:07','xdfs-20190309124707'),(134,99999999,11540761,11223344,11540761,NULL,11540761,4,1,4,'dvdsf',_binary '<p>fdsfdsfdsfdsfdsf</p>','2019-03-09 15:32:05','2019-03-13 19:08:05','2019-03-09 16:44:15','dvdsf-20190309164415'),(135,11223344,11540761,11540761,NULL,NULL,11540761,1,2,NULL,'Hello, I\'m Jo. And this is my post. ',_binary '<p>hidhuiashdhasdihsaidhsiahdisahdi</p>','2019-03-09 16:17:04','2019-03-09 18:25:13','2019-03-09 16:43:19','hello-i-m-jo-and-this-is-my-post-20190309164319'),(136,11223344,11540761,11540761,NULL,NULL,11540761,3,1,NULL,'TESTSTSTSTSTTSTS',_binary '<p>tetete</p>','2019-03-09 16:21:28','2019-03-13 21:32:03','2019-03-09 16:23:59','testststststtsts-20190309162359'),(137,99999999,11540761,11540761,11540761,NULL,11540761,2,2,4,'SHIT',_binary '<p>gfdgdgdgd</p>','2019-03-09 18:54:30','2019-03-11 13:31:42','2019-03-09 19:30:11','shit-20190309193011'),(138,99999999,NULL,11223344,NULL,NULL,11223344,2,1,NULL,'AIRA ORPILLA\'S POST',_binary '<p>HAHAHAHAHAHAHAHAHAHHAHAHA</p>','2019-03-10 22:30:56','2019-03-10 22:32:31',NULL,NULL),(139,11540761,11540761,11540761,11540761,NULL,11540761,2,2,4,'TESTSTSTSTSTTSTS',_binary '<p>Hello againa gaian agaiana gaian</p>','2019-03-11 16:16:56','2019-03-11 16:17:03','2019-03-11 16:16:56','testststststtsts-20190311161656'),(140,99999999,NULL,NULL,NULL,NULL,NULL,2,2,NULL,'Hello, I\'m Jo. And this is my post. ',_binary '<p>JAYAYAYYAYAYAYAYAAYYAYAY</p>','2019-03-13 10:58:44','2019-03-13 10:58:44',NULL,NULL),(141,99999999,11540761,11540761,NULL,NULL,11540761,2,2,NULL,'TESTSTSTSTSTTSTS','','2019-03-13 10:59:27','2019-03-13 19:07:53','2019-03-13 11:10:57','testststststtsts-20190313111057'),(142,99999999,NULL,NULL,NULL,NULL,99999999,2,2,NULL,'xcxzccxzcdaddsa dd',_binary '<p>zxczczcxzcsdasddasda dddd3</p>','2019-03-17 09:19:49','2019-03-17 12:24:04',NULL,NULL),(143,99999999,NULL,NULL,NULL,NULL,NULL,2,2,NULL,'dada',_binary '<p>asdad</p>','2019-03-17 12:28:41','2019-03-17 12:28:41',NULL,NULL),(144,99999999,NULL,NULL,NULL,NULL,99999999,1,1,NULL,'sdasda',_binary '<p>adadad</p>','2019-03-17 12:28:47','2019-03-17 12:28:50',NULL,NULL),(145,99999999,NULL,NULL,NULL,NULL,NULL,1,2,NULL,'sdasdsad',_binary '<p>sdsadad</p>','2019-03-17 16:09:58','2019-03-17 16:09:58',NULL,NULL),(146,99999999,NULL,NULL,NULL,NULL,NULL,1,2,NULL,'sdasdsad',_binary '<p>sdsadad</p>','2019-03-17 16:10:24','2019-03-17 16:10:24',NULL,NULL),(147,99999999,NULL,NULL,NULL,NULL,NULL,1,2,NULL,'sdasdsad','','2019-03-17 16:10:31','2019-03-17 16:10:31',NULL,NULL),(148,99999999,NULL,NULL,NULL,NULL,NULL,1,2,NULL,'sdasdsad','','2019-03-17 16:10:36','2019-03-17 16:10:36',NULL,NULL),(149,99999999,NULL,NULL,NULL,NULL,NULL,1,2,NULL,'sadasd',_binary '<p>asdsad</p>','2019-03-17 16:13:03','2019-03-17 16:13:03',NULL,NULL),(150,99999999,NULL,NULL,NULL,NULL,NULL,1,2,NULL,'sadsadsa',_binary '<p>asdsadadasd</p>','2019-03-17 16:13:24','2019-03-17 16:13:24',NULL,NULL),(151,99999999,NULL,NULL,NULL,NULL,NULL,1,2,NULL,'sadsadsa',_binary '<p>sdadd</p>','2019-03-17 16:14:50','2019-03-17 16:14:50',NULL,NULL);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `process`
--

DROP TABLE IF EXISTS `process`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `processName` varchar(255) DEFAULT NULL,
  `timeCreated` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process`
--

LOCK TABLES `process` WRITE;
/*!40000 ALTER TABLE `process` DISABLE KEYS */;
INSERT INTO `process` VALUES (1,'FALP Application',NULL),(2,'Post Approval',NULL),(3,'Section Approval',NULL),(4,'By Laws Approval',NULL),(5,'Post Attachment Approval',NULL),(99,'No Process',NULL);
/*!40000 ALTER TABLE `process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `process_for`
--

DROP TABLE IF EXISTS `process_for`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `process_for` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_for` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process_for`
--

LOCK TABLES `process_for` WRITE;
/*!40000 ALTER TABLE `process_for` DISABLE KEYS */;
/*!40000 ALTER TABLE `process_for` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ref_department`
--

DROP TABLE IF EXISTS `ref_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ref_department` (
  `DEPT_ID` int(2) NOT NULL,
  `DEPT_NAME` varchar(45) NOT NULL,
  PRIMARY KEY (`DEPT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ref_department`
--

LOCK TABLES `ref_department` WRITE;
/*!40000 ALTER TABLE `ref_department` DISABLE KEYS */;
INSERT INTO `ref_department` VALUES (1,'ACCOUNTANCY'),(2,'BEHAVIORAL SCIENCES'),(3,'BIOLOGY'),(4,'CHEMICAL ENGINEERING'),(5,'CHEMISTRY'),(6,'CIVIL ENGINEERING'),(7,'COMMERCIAL LAW'),(8,'COMMUNICATION'),(9,'COMPUTER TECHNOLOGY'),(10,'COUNSELING AND EDUCATIONAL PSYCHOLOGY'),(11,'DECISION SCIENCES AND INNOVATION'),(12,'ECONOMICS'),(13,'EDUCATIONAL LEADERSHIP AND MANAGEMENT'),(14,'ELECTRONICS AND COMMUNICATIONS ENGINEERING'),(15,'ENGLISH AND APPLIED LINGUISTICS'),(16,'FILIPINO'),(17,'FINANCIAL MANAGEMENT'),(18,'HISTORY'),(19,'INDUSTRIAL ENGINEERING'),(20,'INFORMATION TECHNOLOGY'),(21,'INTERNATIONAL STUDIES'),(22,'LAW'),(23,'LITERATURE'),(24,'MANAGEMENT AND ORGANIZATION'),(25,'MANUFACTURING ENGINEERING AND MANAGEMENT'),(26,'MARKETING MANAGEMENT'),(27,'MATHEMATICS'),(28,'MECHANICAL ENGINEERING'),(29,'PHILOSOPHY'),(30,'PHYSICAL EDUCATION'),(31,'PHYSICS'),(32,'POLITICAL SCIENCE'),(33,'PSYCHOLOGY'),(34,'SCIENCE EDUCATION'),(35,'SOFTWARE TECHNOLOGY'),(36,'THEOLOGY AND RELIGIOUS STUDIES');
/*!40000 ALTER TABLE `ref_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisions`
--

DROP TABLE IF EXISTS `revisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `revisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `revisionsOpened` datetime DEFAULT CURRENT_TIMESTAMP,
  `revisionsClosed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisions`
--

LOCK TABLES `revisions` WRITE;
/*!40000 ALTER TABLE `revisions` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rsvp_status`
--

DROP TABLE IF EXISTS `rsvp_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rsvp_status` (
  `rsvpId` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`rsvpId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rsvp_status`
--

LOCK TABLES `rsvp_status` WRITE;
/*!40000 ALTER TABLE `rsvp_status` DISABLE KEYS */;
INSERT INTO `rsvp_status` VALUES (1,'Pending'),(2,'Can\'t Go'),(3,'Going');
/*!40000 ALTER TABLE `rsvp_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section_availability`
--

DROP TABLE IF EXISTS `section_availability`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section_availability` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `availability` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section_availability`
--

LOCK TABLES `section_availability` WRITE;
/*!40000 ALTER TABLE `section_availability` DISABLE KEYS */;
/*!40000 ALTER TABLE `section_availability` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section_comments`
--

DROP TABLE IF EXISTS `section_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sectionVersionNo` int(11) NOT NULL,
  `sectionId` int(11) NOT NULL,
  `commenterId` int(8) NOT NULL,
  `body` longtext,
  `timePosted` datetime DEFAULT CURRENT_TIMESTAMP,
  `parentCommentId` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_section_comments_section_versions1_idx` (`sectionVersionNo`,`sectionId`),
  KEY `fk_section_comments_employee1_idx` (`commenterId`),
  CONSTRAINT `fk_section_comments_employee1` FOREIGN KEY (`commenterId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_section_comments_section_versions1` FOREIGN KEY (`sectionVersionNo`, `sectionId`) REFERENCES `section_versions` (`versionNo`, `sectionId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section_comments`
--

LOCK TABLES `section_comments` WRITE;
/*!40000 ALTER TABLE `section_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `section_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section_status`
--

DROP TABLE IF EXISTS `section_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section_status` (
  `id` int(2) NOT NULL,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section_status`
--

LOCK TABLES `section_status` WRITE;
/*!40000 ALTER TABLE `section_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `section_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section_versions`
--

DROP TABLE IF EXISTS `section_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section_versions` (
  `versionNo` int(3) NOT NULL,
  `sectionId` int(11) NOT NULL,
  `authorId` int(8) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` blob,
  `timeCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`versionNo`,`sectionId`),
  KEY `fk_section_versions_sections1_idx` (`sectionId`),
  KEY `fk_section_versions_employee1_idx` (`authorId`),
  CONSTRAINT `fk_section_versions_employee1` FOREIGN KEY (`authorId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_section_versions_sections1` FOREIGN KEY (`sectionId`) REFERENCES `sections` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section_versions`
--

LOCK TABLES `section_versions` WRITE;
/*!40000 ALTER TABLE `section_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `section_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manualId` int(3) NOT NULL,
  `parentSectionId` int(11) NOT NULL DEFAULT '0',
  `sectionNumber` int(3) NOT NULL DEFAULT '9999',
  `firstAuthorId` int(8) NOT NULL,
  `archivedById` int(8) DEFAULT NULL,
  `approvedById` int(8) DEFAULT NULL,
  `timeCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `categoryId` int(3) NOT NULL,
  PRIMARY KEY (`id`,`manualId`),
  KEY `fk_sections_employee1_idx` (`firstAuthorId`),
  KEY `fk_sections_employee2_idx` (`archivedById`),
  KEY `fk_sections_employee3_idx` (`approvedById`),
  KEY `fk_sections_faculty_manual1_idx` (`manualId`),
  KEY `fk_sections_manual_categories1_idx` (`categoryId`),
  CONSTRAINT `fk_sections_employee1` FOREIGN KEY (`firstAuthorId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sections_employee2` FOREIGN KEY (`archivedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sections_employee3` FOREIGN KEY (`approvedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sections_faculty_manual1` FOREIGN KEY (`manualId`) REFERENCES `faculty_manual` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sections_manual_categories1` FOREIGN KEY (`categoryId`) REFERENCES `manual_categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_type`
--

DROP TABLE IF EXISTS `service_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_type` (
  `SERVICE_ID` int(11) NOT NULL,
  `SERVICE` varchar(100) NOT NULL,
  PRIMARY KEY (`SERVICE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_type`
--

LOCK TABLES `service_type` WRITE;
/*!40000 ALTER TABLE `service_type` DISABLE KEYS */;
INSERT INTO `service_type` VALUES (1,'Membership'),(2,'Health Aid'),(3,'Bank Loan'),(4,'FALP Loan');
/*!40000 ALTER TABLE `service_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `siblings`
--

DROP TABLE IF EXISTS `siblings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `siblings` (
  `SIBLING_ID` int(11) NOT NULL,
  `MEMBER_ID` int(8) NOT NULL,
  `RECORD_ID` int(9) NOT NULL,
  `LASTNAME` varchar(45) NOT NULL,
  `FIRSTNAME` varchar(45) NOT NULL,
  `MIDDLENAME` varchar(45) NOT NULL,
  `BIRTHDATE` date NOT NULL,
  `STATUS` int(1) NOT NULL,
  `SEX` tinyint(4) NOT NULL,
  PRIMARY KEY (`SIBLING_ID`,`MEMBER_ID`,`RECORD_ID`),
  KEY `fk_siblings_health_aid1_idx` (`RECORD_ID`,`MEMBER_ID`),
  CONSTRAINT `fk_siblings_health_aid1` FOREIGN KEY (`RECORD_ID`, `MEMBER_ID`) REFERENCES `health_aid` (`RECORD_ID`, `MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `siblings`
--

LOCK TABLES `siblings` WRITE;
/*!40000 ALTER TABLE `siblings` DISABLE KEYS */;
/*!40000 ALTER TABLE `siblings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spouse`
--

DROP TABLE IF EXISTS `spouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spouse` (
  `RECORD_ID` int(9) NOT NULL,
  `MEMBER_ID` int(8) NOT NULL,
  `LASTNAME` varchar(45) NOT NULL,
  `FIRSTNAME` varchar(45) NOT NULL,
  `MIDDLENAME` varchar(45) NOT NULL,
  `BIRTHDATE` date NOT NULL,
  `STATUS` int(1) NOT NULL,
  PRIMARY KEY (`RECORD_ID`,`MEMBER_ID`),
  CONSTRAINT `fk_spouse_health_aid1` FOREIGN KEY (`RECORD_ID`, `MEMBER_ID`) REFERENCES `health_aid` (`RECORD_ID`, `MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spouse`
--

LOCK TABLES `spouse` WRITE;
/*!40000 ALTER TABLE `spouse` DISABLE KEYS */;
/*!40000 ALTER TABLE `spouse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_dictionary`
--

DROP TABLE IF EXISTS `status_dictionary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status_dictionary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `statusName` varchar(45) DEFAULT NULL,
  `processId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_statud_dictionary_process1_idx` (`processId`),
  CONSTRAINT `fk_statud_dictionary_process1` FOREIGN KEY (`processId`) REFERENCES `process` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_dictionary`
--

LOCK TABLES `status_dictionary` WRITE;
/*!40000 ALTER TABLE `status_dictionary` DISABLE KEYS */;
INSERT INTO `status_dictionary` VALUES (1,'Pending',1),(2,'Approved',1),(3,'Rejected',1);
/*!40000 ALTER TABLE `status_dictionary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `step_author`
--

DROP TABLE IF EXISTS `step_author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `step_author` (
  `read` int(1) DEFAULT '1',
  `write` int(1) DEFAULT '1',
  `route` int(1) DEFAULT '1',
  `comment` int(1) DEFAULT '1',
  `stepId` int(11) NOT NULL,
  `processId` int(11) NOT NULL,
  KEY `fk_step_author_steps1_idx` (`stepId`,`processId`),
  CONSTRAINT `fk_step_author_steps1` FOREIGN KEY (`stepId`, `processId`) REFERENCES `steps` (`id`, `processId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `step_author`
--

LOCK TABLES `step_author` WRITE;
/*!40000 ALTER TABLE `step_author` DISABLE KEYS */;
/*!40000 ALTER TABLE `step_author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `step_roles`
--

DROP TABLE IF EXISTS `step_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `step_roles` (
  `stepId` int(11) NOT NULL,
  `processId` int(11) NOT NULL,
  `roleId` int(11) NOT NULL,
  `read` tinyint(4) DEFAULT NULL,
  `write` tinyint(4) DEFAULT NULL,
  `route` tinyint(4) DEFAULT NULL,
  `comment` tinyint(4) DEFAULT NULL,
  KEY `fk_step_roles_steps1_idx` (`stepId`,`processId`),
  KEY `fk_step_roles_edms_roles1_idx` (`roleId`),
  CONSTRAINT `fk_step_roles_edms_roles1` FOREIGN KEY (`roleId`) REFERENCES `edms_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_step_roles_steps1` FOREIGN KEY (`stepId`, `processId`) REFERENCES `steps` (`id`, `processId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `step_roles`
--

LOCK TABLES `step_roles` WRITE;
/*!40000 ALTER TABLE `step_roles` DISABLE KEYS */;
INSERT INTO `step_roles` VALUES (2,1,2,2,2,2,2),(2,1,1,2,2,1,2),(3,1,1,2,1,1,2),(3,1,2,2,1,1,2),(3,1,3,2,1,2,2),(3,1,4,2,1,2,2);
/*!40000 ALTER TABLE `step_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `step_routes`
--

DROP TABLE IF EXISTS `step_routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `step_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `routeName` varchar(255) DEFAULT NULL,
  `processId` int(11) NOT NULL,
  `stepId` int(11) NOT NULL,
  `nextStepId` int(11) NOT NULL,
  `nextProcessId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_routes_steps1_idx` (`processId`,`stepId`),
  KEY `fk_routes_steps2_idx` (`nextStepId`,`nextProcessId`),
  CONSTRAINT `fk_routes_steps1` FOREIGN KEY (`processId`, `stepId`) REFERENCES `steps` (`processId`, `id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_routes_steps2` FOREIGN KEY (`nextStepId`, `nextProcessId`) REFERENCES `steps` (`id`, `processId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `step_routes`
--

LOCK TABLES `step_routes` WRITE;
/*!40000 ALTER TABLE `step_routes` DISABLE KEYS */;
INSERT INTO `step_routes` VALUES (1,'For EB Review',1,2,3,1),(2,'For Reupload',1,3,2,1);
/*!40000 ALTER TABLE `step_routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `step_users`
--

DROP TABLE IF EXISTS `step_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `step_users` (
  `stepId` int(11) NOT NULL,
  `processId` int(11) NOT NULL,
  `userId` int(8) NOT NULL,
  `read` int(1) DEFAULT '1',
  `write` int(1) DEFAULT '1',
  `route` int(1) DEFAULT '1',
  `comment` int(1) DEFAULT '1',
  KEY `fk_steps_has_employee_employee1_idx` (`userId`),
  KEY `fk_steps_has_employee_steps1_idx` (`stepId`,`processId`),
  CONSTRAINT `fk_steps_has_employee_employee1` FOREIGN KEY (`userId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_steps_has_employee_steps1` FOREIGN KEY (`stepId`, `processId`) REFERENCES `steps` (`id`, `processId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `step_users`
--

LOCK TABLES `step_users` WRITE;
/*!40000 ALTER TABLE `step_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `step_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `steps`
--

DROP TABLE IF EXISTS `steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `processId` int(11) NOT NULL,
  `stepName` varchar(255) DEFAULT NULL,
  `stepNo` int(2) DEFAULT NULL,
  `isFinal` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`,`processId`),
  KEY `fk_steps_process1_idx` (`processId`),
  CONSTRAINT `fk_steps_process1` FOREIGN KEY (`processId`) REFERENCES `process` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `steps`
--

LOCK TABLES `steps` WRITE;
/*!40000 ALTER TABLE `steps` DISABLE KEYS */;
INSERT INTO `steps` VALUES (1,99,'No Step',1,1),(2,1,'Review by Secretary',1,1),(3,1,'Review by EB',2,2);
/*!40000 ALTER TABLE `steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_roles`
--

DROP TABLE IF EXISTS `sys_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_roles` (
  `id` int(11) NOT NULL,
  `roleName` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_roles`
--

LOCK TABLES `sys_roles` WRITE;
/*!40000 ALTER TABLE `sys_roles` DISABLE KEYS */;
INSERT INTO `sys_roles` VALUES (1,'REGULAR'),(2,'SUPERADMIN');
/*!40000 ALTER TABLE `sys_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `txn_reference`
--

DROP TABLE IF EXISTS `txn_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `txn_reference` (
  `TXN_ID` int(10) NOT NULL AUTO_INCREMENT,
  `MEMBER_ID` int(8) NOT NULL,
  `TXN_TYPE` int(1) NOT NULL,
  `TXN_DESC` varchar(100) NOT NULL,
  `AMOUNT` decimal(7,2) NOT NULL,
  `TXN_DATE` datetime NOT NULL,
  `LOAN_REF` int(9) DEFAULT NULL,
  `EMP_ID` int(8) DEFAULT NULL,
  `SERVICE_ID` int(11) NOT NULL,
  PRIMARY KEY (`TXN_ID`,`MEMBER_ID`),
  KEY `fk_txn_reference_txn_type1_idx` (`TXN_TYPE`),
  KEY `fk_txn_reference_member1_idx` (`MEMBER_ID`),
  KEY `fk_txn_reference_employee1_idx` (`EMP_ID`),
  KEY `fk_txn_reference_bank_loans1` (`LOAN_REF`),
  KEY `fk_txn_reference_service_type1_idx` (`SERVICE_ID`),
  CONSTRAINT `fk_txn_reference_bank_loans1` FOREIGN KEY (`LOAN_REF`) REFERENCES `loans` (`LOAN_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_txn_reference_employee1` FOREIGN KEY (`EMP_ID`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_txn_reference_member1` FOREIGN KEY (`MEMBER_ID`) REFERENCES `member` (`MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_txn_reference_service_type1` FOREIGN KEY (`SERVICE_ID`) REFERENCES `service_type` (`SERVICE_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_txn_reference_txn_type1` FOREIGN KEY (`TXN_TYPE`) REFERENCES `txn_type` (`TYPE_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `txn_reference`
--

LOCK TABLES `txn_reference` WRITE;
/*!40000 ALTER TABLE `txn_reference` DISABLE KEYS */;
/*!40000 ALTER TABLE `txn_reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `txn_type`
--

DROP TABLE IF EXISTS `txn_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `txn_type` (
  `TYPE_ID` int(1) NOT NULL,
  `TYPE` varchar(45) NOT NULL,
  PRIMARY KEY (`TYPE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `txn_type`
--

LOCK TABLES `txn_type` WRITE;
/*!40000 ALTER TABLE `txn_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `txn_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_status`
--

DROP TABLE IF EXISTS `user_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_status` (
  `STATUS_ID` int(1) NOT NULL,
  `STATUS` varchar(45) NOT NULL,
  PRIMARY KEY (`STATUS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_status`
--

LOCK TABLES `user_status` WRITE;
/*!40000 ALTER TABLE `user_status` DISABLE KEYS */;
INSERT INTO `user_status` VALUES (1,'FULL-TIME'),(2,'PART-TIME'),(3,'DECEASED'),(4,'RETIRED'),(5,'RESIGNED');
/*!40000 ALTER TABLE `user_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `view_type`
--

DROP TABLE IF EXISTS `view_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view_type` (
  `id` int(2) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `view_type`
--

LOCK TABLES `view_type` WRITE;
/*!40000 ALTER TABLE `view_type` DISABLE KEYS */;
INSERT INTO `view_type` VALUES (1,'Preview'),(2,'View');
/*!40000 ALTER TABLE `view_type` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-17 19:37:03
