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
  `versionId` int(11) DEFAULT NULL,
  `documentId` int(11) DEFAULT NULL,
  `timePosted` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_doc_comments_doc_versions1_idx` (`versionId`,`documentId`),
  KEY `fk_doc_comments_employee1_idx` (`commenterId`),
  CONSTRAINT `fk_doc_comments_doc_versions1` FOREIGN KEY (`versionId`, `documentId`) REFERENCES `doc_versions` (`versionId`, `documentId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_doc_comments_employee1` FOREIGN KEY (`commenterId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_comments`
--

LOCK TABLES `doc_comments` WRITE;
/*!40000 ALTER TABLE `doc_comments` DISABLE KEYS */;
INSERT INTO `doc_comments` VALUES (46,0,11223344,NULL,'sdasdadad',145,123,'2019-03-20 17:14:00');
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
-- Table structure for table `doc_status`
--

DROP TABLE IF EXISTS `doc_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_status` (
  `id` int(2) NOT NULL,
  `statusName` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_status`
--

LOCK TABLES `doc_status` WRITE;
/*!40000 ALTER TABLE `doc_status` DISABLE KEYS */;
INSERT INTO `doc_status` VALUES (1,'Pending'),(2,'Approved'),(3,'Rejected'),(4,'Archived'),(99,'None');
/*!40000 ALTER TABLE `doc_status` ENABLE KEYS */;
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
  `processId` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_doc_type_process1_idx` (`processId`),
  CONSTRAINT `fk_doc_type_process1` FOREIGN KEY (`processId`) REFERENCES `process` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_type`
--

LOCK TABLES `doc_type` WRITE;
/*!40000 ALTER TABLE `doc_type` DISABLE KEYS */;
INSERT INTO `doc_type` VALUES (1,'SEC Permit',2),(2,'Financial Reports',2),(3,'Applications',2),(4,'Techpanel Minutes',7),(5,'Executive Board Minutes',2),(99,'Other Documents',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edit_post_comments`
--

LOCK TABLES `edit_post_comments` WRITE;
/*!40000 ALTER TABLE `edit_post_comments` DISABLE KEYS */;
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
INSERT INTO `edms_roles` VALUES (1,'READER'),(2,'SECRETARY'),(3,'EXECUTIVE BOARD'),(4,'PRESIDENT'),(5,'TECHNICAL PANEL'),(6,'NEGOTIATION HEAD'),(7,'TREASURER'),(8,'TRANSCRIBER');
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
INSERT INTO `employee` VALUES (11223344,'*A4B6157319038724E3560894F7F932C8886EBFCF','Samuel','Malijan','1990-04-03 00:00:00',NULL,1,1,11223344,'N/A',1,1,3,1),(11540761,'*A4B6157319038724E3560894F7F932C8886EBFCF','Christian Nicole','Alderite','1990-04-03 00:00:00',NULL,1,1,11540761,'N/A',3,1,4,1),(99999999,'*A4B6157319038724E3560894F7F932C8886EBFCF','Jo','Melton','1990-04-03 00:00:00',NULL,1,1,99999999,'N/A',2,2,2,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
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
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `year` varchar(45) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `timePublished` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `publishedById` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_faculty_manual_employee1_idx` (`publishedById`),
  CONSTRAINT `fk_faculty_manual_employee1` FOREIGN KEY (`publishedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculty_manual`
--

LOCK TABLES `faculty_manual` WRITE;
/*!40000 ALTER TABLE `faculty_manual` DISABLE KEYS */;
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
-- Table structure for table `minute_status`
--

DROP TABLE IF EXISTS `minute_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `minute_status` (
  `id` tinyint(4) NOT NULL,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `minute_status`
--

LOCK TABLES `minute_status` WRITE;
/*!40000 ALTER TABLE `minute_status` DISABLE KEYS */;
INSERT INTO `minute_status` VALUES (1,'Pending'),(2,'Approved'),(3,'Rejected');
/*!40000 ALTER TABLE `minute_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `minute_versions`
--

DROP TABLE IF EXISTS `minute_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `minute_versions` (
  `id` int(11) NOT NULL,
  `minutesId` int(11) NOT NULL,
  `preparedById` int(8) NOT NULL,
  PRIMARY KEY (`id`,`minutesId`),
  KEY `fk_minute_versions_employee1_idx` (`preparedById`),
  KEY `fk_minute_versions_minutes1_idx` (`minutesId`),
  CONSTRAINT `fk_minute_versions_employee1` FOREIGN KEY (`preparedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_minute_versions_minutes1` FOREIGN KEY (`minutesId`) REFERENCES `minutes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `minute_versions`
--

LOCK TABLES `minute_versions` WRITE;
/*!40000 ALTER TABLE `minute_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `minute_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `minutes`
--

DROP TABLE IF EXISTS `minutes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `minutes` (
  `id` int(11) NOT NULL,
  `content` blob,
  `title` longtext,
  `timeFirstCreated` varchar(45) DEFAULT 'CURRENT_TIMESTAMP',
  `statusId` tinyint(4) NOT NULL,
  `firstPreparedById` int(8) NOT NULL,
  `approvedById` int(8) NOT NULL,
  `availabilityId` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_minutes_minute_status1_idx` (`statusId`),
  KEY `fk_minutes_employee1_idx` (`firstPreparedById`),
  KEY `fk_minutes_employee2_idx` (`approvedById`),
  CONSTRAINT `fk_minutes_employee1` FOREIGN KEY (`firstPreparedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_minutes_employee2` FOREIGN KEY (`approvedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_minutes_minute_status1` FOREIGN KEY (`statusId`) REFERENCES `minute_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `minutes`
--

LOCK TABLES `minutes` WRITE;
/*!40000 ALTER TABLE `minutes` DISABLE KEYS */;
/*!40000 ALTER TABLE `minutes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `minutes_comments`
--

DROP TABLE IF EXISTS `minutes_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `minutes_comments` (
  `id` int(11) NOT NULL,
  `comment` longtext,
  `timeStamp` datetime DEFAULT CURRENT_TIMESTAMP,
  `parentCommentId` int(11) DEFAULT '0',
  `versionId` int(11) NOT NULL,
  `minutesId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_minutes_comments_minute_versions1_idx` (`versionId`,`minutesId`),
  CONSTRAINT `fk_minutes_comments_minute_versions1` FOREIGN KEY (`versionId`, `minutesId`) REFERENCES `minute_versions` (`id`, `minutesId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `minutes_comments`
--

LOCK TABLES `minutes_comments` WRITE;
/*!40000 ALTER TABLE `minutes_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `minutes_comments` ENABLE KEYS */;
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
-- Table structure for table `poll`
--

DROP TABLE IF EXISTS `poll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll` (
  `id` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `typeId` int(11) NOT NULL,
  `question` longtext,
  PRIMARY KEY (`id`),
  KEY `fk_poll_posts1_idx` (`postId`),
  KEY `fk_poll_poll_type1_idx` (`typeId`),
  CONSTRAINT `fk_poll_poll_type1` FOREIGN KEY (`typeId`) REFERENCES `poll_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_poll_posts1` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll`
--

LOCK TABLES `poll` WRITE;
/*!40000 ALTER TABLE `poll` DISABLE KEYS */;
/*!40000 ALTER TABLE `poll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll_options`
--

DROP TABLE IF EXISTS `poll_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_options` (
  `optionId` int(2) NOT NULL AUTO_INCREMENT,
  `pollId` int(11) NOT NULL,
  `option` longtext,
  PRIMARY KEY (`optionId`),
  KEY `fk_table1_poll1_idx` (`pollId`),
  CONSTRAINT `fk_table1_poll1` FOREIGN KEY (`pollId`) REFERENCES `poll` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
-- Table structure for table `poll_type`
--

DROP TABLE IF EXISTS `poll_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_type`
--

LOCK TABLES `poll_type` WRITE;
/*!40000 ALTER TABLE `poll_type` DISABLE KEYS */;
INSERT INTO `poll_type` VALUES (1,'Single Response (Radio)'),(2,'Multiple Response (Checkbox)');
/*!40000 ALTER TABLE `poll_type` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=338 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_activity`
--

LOCK TABLES `post_activity` WRITE;
/*!40000 ALTER TABLE `post_activity` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_comments`
--

LOCK TABLES `post_comments` WRITE;
/*!40000 ALTER TABLE `post_comments` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `facultyassocnew`.`before_permalink_post`
BEFORE UPDATE ON `facultyassocnew`.`posts`
FOR EACH ROW
IF (old.permalink IS NULL) THEN
SET new.permalink = CONCAT(new.permalink,'-', DATE_FORMAT(old.timePublished,'%Y%m%d%H%i%s'));
END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `facultyassocnew`.`before_publish_post`
BEFORE UPDATE ON `facultyassocnew`.`posts`
FOR EACH ROW
IF (new.statusId = 4 AND old.timePublished IS NULL) THEN
	SET new.timePublished = new.lastUpdated;
END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `facultyassocnew`.`before_trash_post`
BEFORE UPDATE ON `facultyassocnew`.`posts`
FOR EACH ROW
BEGIN
    IF (new.statusId = 5 AND old.statusId!=new.statusId) THEN
	SET new.previousStatusId = old.statusId ;
END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `facultyassocnew`.`posts_AFTER_UPDATE`
AFTER UPDATE ON `facultyassocnew`.`posts`
FOR EACH ROW
BEGIN
IF(new.statusId != old.statusId) THEN
	IF(new.statusId = 4) THEN 
		INSERT INTO post_activity
		(displayToId, postId, message, timeStamp)
		SELECT old.authorId, old.id, CONCAT('Your post "',new.title,'" has been published by ', e.LASTNAME,', ', e.FIRSTNAME), new.lastUpdated
		FROM posts p JOIN employee e ON new.publisherId = e.EMP_ID AND p.id = old.id;
    END IF;
    IF(new.statusId = 3 AND old.statusId = 2) THEN 
		INSERT INTO post_activity
		(displayToId, postId, message, timeStamp)
		SELECT old.authorId, old.id, CONCAT('Your post "',new.title,'" has been reviewed by ', e.LASTNAME,', ', e.FIRSTNAME), new.lastUpdated
		FROM posts p JOIN employee e ON new.reviewedById = e.EMP_ID WHERE p.id=old.id;
        INSERT INTO post_activity
		(displayToId, postId, message, timeStamp)
		SELECT e.EMP_ID, old.id, CONCAT('"',new.title,'" needs publication review'), new.lastUpdated
		FROM employee e JOIN cms_roles c ON c.id = e.CMS_ROLE WHERE c.id = 4;
    END IF;
    IF(new.statusId = 3 AND old.statusId = 4) THEN 
		INSERT INTO post_activity
		(displayToId, postId, message, timeStamp)
		SELECT old.authorId, old.id, CONCAT('Your post "',new.title,'" has been unpublished by ', e.LASTNAME,', ', e.FIRSTNAME), new.lastUpdated
		FROM posts p JOIN employee e ON new.reviewedById = e.EMP_ID WHERE p.id=old.id;
		INSERT INTO post_activity
		(displayToId, postId, message, timeStamp)
		SELECT e.EMP_ID, old.id, CONCAT('"',new.title,'" needs publication review'), new.lastUpdated
		FROM employee e JOIN cms_roles c ON c.id = e.CMS_ROLE WHERE c.id = 4;
    END IF;
    IF(new.statusId = 2) THEN 
		INSERT INTO post_activity
		(displayToId, postId, message, timeStamp)
		SELECT e.EMP_ID, old.id, CONCAT('"',new.title,'" needs review'), new.lastUpdated
		FROM employee e JOIN cms_roles c ON c.id = e.CMS_ROLE WHERE c.id = 3;
    END IF;
    IF(new.statusId = 1 AND old.statusId = 2) THEN 
		INSERT INTO post_activity
		(displayToId, postId, message, timeStamp)
		SELECT old.authorId, old.id, CONCAT('Your post "',new.title,'" has been rejected by ', e.LASTNAME,', ', e.FIRSTNAME), new.lastUpdated
		FROM posts p JOIN employee e ON new.reviewedById = e.EMP_ID AND p.id = old.id;
    END IF;
    IF(new.statusId = 1 AND old.statusId = 3) THEN 
		INSERT INTO post_activity
		(displayToId, postId, message, timeStamp)
		SELECT old.authorId, old.id, CONCAT('Your post "',new.title,'" has been rejected by ', e.LASTNAME,', ', e.FIRSTNAME), new.lastUpdated
		FROM posts p JOIN employee e ON new.publisherId = e.EMP_ID AND p.id = old.id;
    END IF;
    IF(new.statusId = 1 AND old.statusId = 4) THEN 
		INSERT INTO post_activity
		(displayToId, postId, message, timeStamp)
		SELECT old.authorId, old.id, CONCAT('Your post "',new.title,'" has been rejected by ', e.LASTNAME,', ', e.FIRSTNAME), new.lastUpdated
		FROM posts p JOIN employee e ON new.reviewedById = e.EMP_ID AND p.id = old.id;
    END IF;
    IF(new.statusId = 5 AND new.archivedById != old.authorId) THEN 
		INSERT INTO post_activity
		(displayToId, postId, message, timeStamp)
		SELECT old.authorId, old.id, CONCAT('Your post "',new.title,'" has been archived by ', e.LASTNAME,', ', e.FIRSTNAME), new.lastUpdated
		FROM posts p JOIN employee e ON new.reviewedById = e.EMP_ID AND p.id = old.id;
    END IF;
    IF(old.statusId = 5) THEN 
		INSERT INTO post_activity
		(displayToId, postId, message, timeStamp)
		SELECT old.authorId, old.id, CONCAT('Your post "',new.title,'" has been restored by ', e.LASTNAME,', ', e.FIRSTNAME), new.lastUpdated
		FROM posts p JOIN employee e ON new.reviewedById = e.EMP_ID AND p.id = old.id;
    END IF;
END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

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
  `lastUpdated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `editableId` tinyint(4) NOT NULL,
  `processForId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_process_process_for1_idx` (`processForId`),
  CONSTRAINT `fk_process_process_for1` FOREIGN KEY (`processForId`) REFERENCES `process_for` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process`
--

LOCK TABLES `process` WRITE;
/*!40000 ALTER TABLE `process` DISABLE KEYS */;
INSERT INTO `process` VALUES (1,'None',NULL,NULL,1,NULL),(2,'Executive Board Review',NULL,'2019-03-20 16:00:05',1,1),(3,'President\'s Review',NULL,'2019-03-20 16:00:05',1,1),(4,'FALP Application Approval',NULL,NULL,1,1),(5,'Health Aid Committee Review',NULL,'2019-03-20 16:00:05',1,1),(6,'AFED Manual Revisions',NULL,'2019-03-20 15:32:39',1,2),(7,'Technical Panel Review',NULL,'2019-03-20 16:00:05',1,1);
/*!40000 ALTER TABLE `process` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `process_for`
--

DROP TABLE IF EXISTS `process_for`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `process_for` (
  `id` int(11) NOT NULL,
  `process_for` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process_for`
--

LOCK TABLES `process_for` WRITE;
/*!40000 ALTER TABLE `process_for` DISABLE KEYS */;
INSERT INTO `process_for` VALUES (1,'Documents'),(2,'AFED Manual');
/*!40000 ALTER TABLE `process_for` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `published_versions`
--

DROP TABLE IF EXISTS `published_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `published_versions` (
  `versionId` int(11) NOT NULL,
  `sectionId` int(11) NOT NULL,
  `manualId` int(3) NOT NULL,
  KEY `fk_section_versions_has_faculty_manual_section_versions1_idx` (`versionId`,`sectionId`),
  KEY `fk_section_versions_has_faculty_manual_faculty_manual1_idx` (`manualId`),
  CONSTRAINT `fk_section_versions_has_faculty_manual_faculty_manual1` FOREIGN KEY (`manualId`) REFERENCES `faculty_manual` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_section_versions_has_faculty_manual_section_versions1` FOREIGN KEY (`versionId`, `sectionId`) REFERENCES `section_versions` (`versionId`, `sectionId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `published_versions`
--

LOCK TABLES `published_versions` WRITE;
/*!40000 ALTER TABLE `published_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `published_versions` ENABLE KEYS */;
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
/*!40000 ALTER TABLE `rsvp_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section_comments`
--

DROP TABLE IF EXISTS `section_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commenterId` int(8) NOT NULL,
  `body` longtext,
  `timePosted` datetime DEFAULT CURRENT_TIMESTAMP,
  `parentCommentId` int(11) DEFAULT '0',
  `versionId` int(11) NOT NULL,
  `sectionId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_section_comments_employee1_idx` (`commenterId`),
  KEY `fk_section_comments_section_versions1_idx` (`versionId`,`sectionId`),
  CONSTRAINT `fk_section_comments_employee1` FOREIGN KEY (`commenterId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_section_comments_section_versions1` FOREIGN KEY (`versionId`, `sectionId`) REFERENCES `section_versions` (`versionId`, `sectionId`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section_status`
--

LOCK TABLES `section_status` WRITE;
/*!40000 ALTER TABLE `section_status` DISABLE KEYS */;
INSERT INTO `section_status` VALUES (1,'Pending'),(2,'Published'),(3,'Rejected'),(5,'Archived');
/*!40000 ALTER TABLE `section_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section_versions`
--

DROP TABLE IF EXISTS `section_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section_versions` (
  `versionId` int(11) NOT NULL AUTO_INCREMENT,
  `sectionId` int(11) NOT NULL,
  `title` longtext,
  `content` longtext,
  `timeCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `authorId` int(8) NOT NULL,
  `approvedById` int(8) DEFAULT NULL,
  `lockedById` int(8) DEFAULT NULL,
  PRIMARY KEY (`versionId`,`sectionId`),
  KEY `fk_section_versions_sections1_idx` (`sectionId`),
  KEY `fk_section_versions_employee1_idx` (`authorId`),
  KEY `fk_section_versions_employee2_idx` (`approvedById`),
  KEY `fk_section_versions_employee3_idx` (`lockedById`),
  CONSTRAINT `fk_section_versions_employee1` FOREIGN KEY (`authorId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_section_versions_employee2` FOREIGN KEY (`approvedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_section_versions_employee3` FOREIGN KEY (`lockedById`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
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
  `parentSectionId` int(11) NOT NULL DEFAULT '0',
  `firstAuthorId` int(8) NOT NULL,
  `timeCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stepId` int(11) NOT NULL,
  `statusId` int(2) NOT NULL,
  `availabilityId` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sections_employee1_idx` (`firstAuthorId`),
  KEY `fk_sections_steps1_idx` (`stepId`),
  KEY `fk_sections_section_status1_idx` (`statusId`),
  CONSTRAINT `fk_sections_employee1` FOREIGN KEY (`firstAuthorId`) REFERENCES `employee` (`EMP_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sections_section_status1` FOREIGN KEY (`statusId`) REFERENCES `section_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sections_steps1` FOREIGN KEY (`stepId`) REFERENCES `steps` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
  KEY `fk_step_author_steps1_idx` (`stepId`),
  CONSTRAINT `fk_step_author_steps1` FOREIGN KEY (`stepId`) REFERENCES `steps` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `step_author`
--

LOCK TABLES `step_author` WRITE;
/*!40000 ALTER TABLE `step_author` DISABLE KEYS */;
INSERT INTO `step_author` VALUES (2,2,2,2,999);
/*!40000 ALTER TABLE `step_author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `step_roles`
--

DROP TABLE IF EXISTS `step_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `step_roles` (
  `roleId` int(11) NOT NULL,
  `stepId` int(11) NOT NULL,
  `read` int(1) DEFAULT NULL,
  `write` int(1) DEFAULT NULL,
  `route` int(1) DEFAULT NULL,
  `comment` int(1) DEFAULT NULL,
  KEY `fk_step_roles_edms_roles1_idx` (`roleId`),
  KEY `fk_step_roles_steps1_idx` (`stepId`),
  CONSTRAINT `fk_step_roles_edms_roles1` FOREIGN KEY (`roleId`) REFERENCES `edms_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_step_roles_steps1` FOREIGN KEY (`stepId`) REFERENCES `steps` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `step_roles`
--

LOCK TABLES `step_roles` WRITE;
/*!40000 ALTER TABLE `step_roles` DISABLE KEYS */;
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
  `currentStepId` int(11) NOT NULL,
  `nextStepId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_step_routes_steps1_idx` (`currentStepId`),
  KEY `fk_step_routes_steps2_idx` (`nextStepId`),
  CONSTRAINT `fk_step_routes_steps1` FOREIGN KEY (`currentStepId`) REFERENCES `steps` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_step_routes_steps2` FOREIGN KEY (`nextStepId`) REFERENCES `steps` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `step_routes`
--

LOCK TABLES `step_routes` WRITE;
/*!40000 ALTER TABLE `step_routes` DISABLE KEYS */;
/*!40000 ALTER TABLE `step_routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `steps`
--

DROP TABLE IF EXISTS `steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stepName` varchar(255) DEFAULT NULL,
  `stepNo` int(2) DEFAULT NULL,
  `isFinal` tinyint(4) DEFAULT NULL,
  `processId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_steps_process1_idx` (`processId`),
  CONSTRAINT `fk_steps_process1` FOREIGN KEY (`processId`) REFERENCES `process` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `steps`
--

LOCK TABLES `steps` WRITE;
/*!40000 ALTER TABLE `steps` DISABLE KEYS */;
INSERT INTO `steps` VALUES (1,'Review',1,2,2),(2,'Review',1,2,3),(3,'Secretary\'s Review',1,1,4),(4,'Treasurer\'s Review',2,2,4),(5,'Review',1,2,5),(6,'Techpanel Review',1,1,6),(7,'Executive Board Review',2,2,6),(8,'Review',1,2,7),(999,'None',1,1,1);
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
  `HA_REF` int(11) DEFAULT NULL,
  PRIMARY KEY (`TXN_ID`,`MEMBER_ID`),
  KEY `fk_txn_reference_txn_type1_idx` (`TXN_TYPE`),
  KEY `fk_txn_reference_member1_idx` (`MEMBER_ID`),
  KEY `fk_txn_reference_employee1_idx` (`EMP_ID`),
  KEY `fk_txn_reference_bank_loans1` (`LOAN_REF`),
  KEY `fk_txn_reference_service_type1_idx` (`SERVICE_ID`),
  KEY `HA_REF_idx` (`HA_REF`),
  CONSTRAINT `HA_REF` FOREIGN KEY (`HA_REF`) REFERENCES `health_aid` (`RECORD_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
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

--
-- Dumping events for database 'facultyassocnew'
--

--
-- Dumping routines for database 'facultyassocnew'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-20 18:26:19
