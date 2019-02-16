-- MySQL dump 10.13  Distrib 5.7.24, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: facultyassocnew
-- ------------------------------------------------------
-- Server version	5.7.24-log

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banks`
--

LOCK TABLES `banks` WRITE;
/*!40000 ALTER TABLE `banks` DISABLE KEYS */;
INSERT INTO `banks` VALUES (1,'FALP','FALP',1,11443018,'2017-12-17 00:00:00','2017-12-19 00:00:00'),(2,'BDO ','BDO ',2,970121234,'2017-12-12 00:00:00','2017-12-19 05:41:22');
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
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_roles`
--

LOCK TABLES `cms_roles` WRITE;
/*!40000 ALTER TABLE `cms_roles` DISABLE KEYS */;
INSERT INTO `cms_roles` VALUES (1,'Reader'),(2,'Editor'),(3,'Contributor');
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
  `commenterId` int(11) NOT NULL,
  `content` varchar(45) DEFAULT NULL,
  `versionNo` int(11) NOT NULL,
  `documentId` int(11) NOT NULL,
  PRIMARY KEY (`id`,`commenterId`),
  KEY `fk_doc_comments_doc_versions1_idx` (`versionNo`,`documentId`),
  CONSTRAINT `fk_doc_comments_doc_versions1` FOREIGN KEY (`versionNo`, `documentId`) REFERENCES `doc_versions` (`versionNo`, `documentId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_comments`
--

LOCK TABLES `doc_comments` WRITE;
/*!40000 ALTER TABLE `doc_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `doc_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doc_versions`
--

DROP TABLE IF EXISTS `doc_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_versions` (
  `documentId` int(11) NOT NULL,
  `versionNo` int(11) NOT NULL AUTO_INCREMENT,
  `title` longtext,
  `content` longtext,
  `timeCreated` datetime DEFAULT NULL,
  `updatedById` int(11) DEFAULT NULL,
  PRIMARY KEY (`versionNo`,`documentId`),
  KEY `fk_doc_versions_documents1_idx` (`documentId`),
  CONSTRAINT `fk_doc_versions_documents1` FOREIGN KEY (`documentId`) REFERENCES `documents` (`documentId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_versions`
--

LOCK TABLES `doc_versions` WRITE;
/*!40000 ALTER TABLE `doc_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `doc_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doc_versions_references_doc_versions`
--

DROP TABLE IF EXISTS `doc_versions_references_doc_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doc_versions_references_doc_versions` (
  `docA_versionNo` int(11) NOT NULL,
  `docA_documentId` int(11) NOT NULL,
  `docB_versionNo` int(11) NOT NULL,
  `docB_documentId` int(11) NOT NULL,
  KEY `fk_doc_versions_has_doc_versions_doc_versions1_idx` (`docA_versionNo`,`docA_documentId`),
  KEY `fk_doc_versions_has_doc_versions_doc_versions2_idx` (`docB_versionNo`,`docB_documentId`),
  CONSTRAINT `fk_doc_versions_has_doc_versions_doc_versions1` FOREIGN KEY (`docA_versionNo`, `docA_documentId`) REFERENCES `doc_versions` (`versionNo`, `documentId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_doc_versions_has_doc_versions_doc_versions2` FOREIGN KEY (`docB_versionNo`, `docB_documentId`) REFERENCES `doc_versions` (`versionNo`, `documentId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doc_versions_references_doc_versions`
--

LOCK TABLES `doc_versions_references_doc_versions` WRITE;
/*!40000 ALTER TABLE `doc_versions_references_doc_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `doc_versions_references_doc_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `documentId` int(11) NOT NULL AUTO_INCREMENT,
  `latestTitle` longtext,
  `lastestContent` longtext,
  `latestAuthorId` longtext,
  `timeLastUpdated` datetime DEFAULT NULL,
  `firstAuthorId` int(11) DEFAULT NULL,
  `timeFirstPosted` datetime DEFAULT NULL,
  PRIMARY KEY (`documentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `edms_roles`
--

DROP TABLE IF EXISTS `edms_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `edms_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edms_roles`
--

LOCK TABLES `edms_roles` WRITE;
/*!40000 ALTER TABLE `edms_roles` DISABLE KEYS */;
INSERT INTO `edms_roles` VALUES (1,'Reader'),(2,'Board Member'),(3,'President');
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
  `EDMS_ROLE` int(11) DEFAULT NULL,
  `CMS_ROLE` int(11) DEFAULT NULL,
  `FRAP_ROLE` int(11) DEFAULT NULL,
  `MEMBER_ID` int(11) DEFAULT NULL,
  `PART_TIME_LOANED` tinytext,
  PRIMARY KEY (`EMP_ID`),
  KEY `fk_employee_user_status1_idx` (`ACC_STATUS`),
  KEY `fk_edms_role_idx` (`EDMS_ROLE`),
  KEY `fk_cms_role_idx` (`CMS_ROLE`),
  KEY `fk_frap_role_idx` (`FRAP_ROLE`),
  KEY `fk_member_id_idx` (`MEMBER_ID`),
  CONSTRAINT `fk_cms_role` FOREIGN KEY (`CMS_ROLE`) REFERENCES `cms_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_edms_role` FOREIGN KEY (`EDMS_ROLE`) REFERENCES `edms_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_employee_user_status1` FOREIGN KEY (`ACC_STATUS`) REFERENCES `user_status` (`STATUS_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_frap_role` FOREIGN KEY (`FRAP_ROLE`) REFERENCES `frap_roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_member_id` FOREIGN KEY (`MEMBER_ID`) REFERENCES `member` (`MEMBER_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (11443081,'*A4B6157319038724E3560894F7F932C8886EBFCF','Samuel','Malijan ','2017-12-12 00:00:00','9999-12-12 00:00:00',1,1,1,1,1,11443081,'N/A'),(22222222,'*A4B6157319038724E3560894F7F932C8886EBFCF','Guy','Test','2017-12-12 00:00:00','9999-12-12 00:00:00',1,1,1,1,1,22222222,'N/A'),(99999999,'*A4B6157319038724E3560894F7F932C8886EBFCF','Melton','Jo','2017-12-12 00:00:00','9999-12-12 00:00:00',1,1,2,2,2,99999999,'N/A');
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `falp_requirements`
--

LOCK TABLES `falp_requirements` WRITE;
/*!40000 ALTER TABLE `falp_requirements` DISABLE KEYS */;
INSERT INTO `falp_requirements` VALUES (1,1,11443081,'qwe0iuqwe','qowreuqwoeiu','qwoeiuqeoi','qweoiuqwoeiu'),(20,66,11443081,'D:/xampp/htdocs/github2FA/FRAP/Bank_Loan_Requirements/11443081/1/BLRequirements/ICR/Ebralinag-v.-Division-Superintendent.pdf','D:/xampp/htdocs/github2FA/FRAP/Bank_Loan_Requirements/11443081/1/BLRequirements/Payslip/Manifesto.docx','D:/xampp/htdocs/github2FA/FRAP/Bank_Loan_Requirements/11443081/1/BLRequirements/Employee_ID/Chapter11 (1).pdf','D:/xampp/htdocs/github2FA/FRAP/Bank_Loan_Requirements/11443081/1/BLRequirements/Government_ID/Ebralinag-v.-Division-Superintendent.pdf'),(22,68,12345678,'D:/xampp/htdocs/github2FA/FRAP/Bank_Loan_Requirements/12345678/21/BLRequirements/ICR/WEBTECH S15-S16 T1 2017-2018.pdf','D:/xampp/htdocs/github2FA/FRAP/Bank_Loan_Requirements/12345678/21/BLRequirements/Payslip/Ebralinag-v.-Division-Superintendent.pdf','D:/xampp/htdocs/github2FA/FRAP/Bank_Loan_Requirements/12345678/21/BLRequirements/Employee_ID/West-Virginia-v.-Barnette.pdf','D:/xampp/htdocs/github2FA/FRAP/Bank_Loan_Requirements/12345678/21/BLRequirements/Government_ID/Ebralinag-v.-Division-Superintendent.pdf'),(144,75,99999999,'D:/xampp/htdocs/Thesis/FRAP_sd/FRAP_sd/FALP_Requirements/99999999/143/FALPRequirements/ICR/Capture.JPG','D:/xampp/htdocs/Thesis/FRAP_sd/FRAP_sd/FALP_Requirements/99999999/143/FALPRequirements/Payslip/Capture2.JPG','D:/xampp/htdocs/Thesis/FRAP_sd/FRAP_sd/FALP_Requirements/99999999/143/FALPRequirements/Employee_ID/Capture3.JPG','D:/xampp/htdocs/Thesis/FRAP_sd/FRAP_sd/FALP_Requirements/99999999/143/FALPRequirements/Government_ID/DrTr2c_XgAE3FHM.jpg'),(145,78,22222222,'D:/xampp/htdocs/Thesis/FRAP_sd/FRAP_sd/FALP_Requirements/22222222/144/FALPRequirements/ICR/48382484_2044683545625996_6600742530629763072_n.jpg','D:/xampp/htdocs/Thesis/FRAP_sd/FRAP_sd/FALP_Requirements/22222222/144/FALPRequirements/Payslip/mind your own business.png','D:/xampp/htdocs/Thesis/FRAP_sd/FRAP_sd/FALP_Requirements/22222222/144/FALPRequirements/Employee_ID/Nero_Love.jpg','D:/xampp/htdocs/Thesis/FRAP_sd/FRAP_sd/FALP_Requirements/22222222/144/FALPRequirements/Government_ID/god hates me.jpg');
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
INSERT INTO `father` VALUES (1,11443081,'  qweqweqwe','  qweqeqwe','  qeqweqweqwe','1962-01-01',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_audit_table`
--

LOCK TABLES `file_audit_table` WRITE;
/*!40000 ALTER TABLE `file_audit_table` DISABLE KEYS */;
INSERT INTO `file_audit_table` VALUES (1,99999999,'Melton Jo','qweqeqweqweqw','2018-08-24 10:06:20'),(2,99999999,'Melton Jo','qweqeqweqweqw','2018-08-24 10:20:55'),(3,99999999,'MeltonJo','MeltonJo uploadedMinutes of the Meetingto AFED File Repository','2018-08-24 04:55:29'),(4,99999999,'MeltonJo','MeltonJo uploadedto AFED File Repository','2018-08-24 05:21:23'),(5,99999999,'MeltonJo','MeltonJo uploadedMinutes of the Meetingto AFED File Repository','2018-08-24 05:22:08'),(6,99999999,'MeltonJo','MeltonJo uploadedMinutes of the Meetingto AFED File Repository','2018-08-24 09:19:41'),(7,99999999,'MeltonJo','MeltonJo uploadedMinutes of the Meetingto AFED File Repository','2018-08-24 09:19:52');
/*!40000 ALTER TABLE `file_audit_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `frap_roles`
--

DROP TABLE IF EXISTS `frap_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `frap_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frap_roles`
--

LOCK TABLES `frap_roles` WRITE;
/*!40000 ALTER TABLE `frap_roles` DISABLE KEYS */;
INSERT INTO `frap_roles` VALUES (1,'Member'),(2,'Secretary'),(3,'President');
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
INSERT INTO `gdrive_folderid` VALUES (11443081,NULL),(12345678,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `health_aid`
--

LOCK TABLES `health_aid` WRITE;
/*!40000 ALTER TABLE `health_aid` DISABLE KEYS */;
INSERT INTO `health_aid` VALUES (1,11443081,2,'2018-08-24 03:26:47','2018-11-07 19:45:21',99999999);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loan_plan`
--

LOCK TABLES `loan_plan` WRITE;
/*!40000 ALTER TABLE `loan_plan` DISABLE KEYS */;
INSERT INTO `loan_plan` VALUES (1,1,5000.00,10000.00,1,1,3,2000.00,1),(2,2,10000.00,20000.00,6,8,12,5000.00,1);
/*!40000 ALTER TABLE `loan_plan` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loans`
--

LOCK TABLES `loans` WRITE;
/*!40000 ALTER TABLE `loans` DISABLE KEYS */;
INSERT INTO `loans` VALUES (1,11443081,1,15000.00,6,12,15900.00,900.00,0.00,0,5000.00,3,1,'2017-12-12 00:00:00','2017-11-11 00:00:00',11443081,1,'2018-10-12'),(66,11443081,2,15000.00,6,12,15900.00,993.75,1050.00,0,NULL,3,3,'2017-12-18 00:00:00',NULL,NULL,1,NULL),(67,12345678,2,20000.00,6,8,21200.00,1325.00,0.00,0,NULL,1,1,'2017-12-19 00:00:00','2017-12-12 00:00:00',NULL,1,NULL),(68,12345678,2,15000.00,6,8,15900.00,993.75,0.00,0,NULL,1,1,'2017-12-19 00:00:00','2017-12-12 00:00:00',NULL,1,NULL),(70,11111111,1,15000.00,5,10,15750.00,787.50,15750.00,20,NULL,2,2,'2018-10-09 00:00:00',NULL,NULL,1,'2018-10-18'),(71,34567890,1,10000.00,5,10,10500.00,1050.00,10500.00,10,NULL,2,2,'2018-10-09 00:00:00',NULL,NULL,1,'2018-10-12'),(72,12345678,1,15000.00,5,10,15750.00,787.50,3937.50,5,NULL,2,2,'2018-10-10 00:00:00',NULL,NULL,1,'2018-10-12'),(73,11443081,1,15000.00,5,10,15750.00,787.50,6300.00,8,NULL,2,2,'2018-10-12 00:00:00',NULL,NULL,1,NULL),(74,34567890,1,15000.00,5,10,15750.00,1575.00,18900.00,12,NULL,2,2,'2018-10-12 00:00:00','2018-12-12 00:00:00',99999999,1,'2018-10-18'),(75,99999999,1,15000.00,5,5,15750.00,1575.00,0.00,0,NULL,1,1,'2018-11-10 00:00:00',NULL,NULL,1,NULL),(76,11540761,1,25000.00,5,5,26250.00,5250.00,23625.00,5,NULL,2,2,'2018-11-12 00:00:00',NULL,NULL,1,'2018-11-12'),(77,11540761,1,15000.00,5,5,15750.00,3150.00,40950.00,13,NULL,2,2,'2018-11-12 00:00:00',NULL,NULL,1,NULL),(78,22222222,1,20000.00,5,5,21000.00,2100.00,0.00,0,NULL,1,1,'2019-01-24 00:00:00','2019-01-24 07:46:16',99999999,1,NULL),(79,11540761,1,25000.00,500,5,27500.00,5250.00,0.00,0,NULL,2,2,'2019-01-24 00:00:00',NULL,NULL,1,NULL),(80,11540761,1,25000.00,500,5,27500.00,5250.00,0.00,0,NULL,2,2,'2019-01-24 00:00:00',NULL,NULL,1,NULL),(81,97023043,1,25000.00,500,5,27500.00,5250.00,52500.00,10,NULL,2,2,'2019-01-24 00:00:00',NULL,NULL,1,NULL);
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
  `CAMPUS` varchar(255) NOT NULL DEFAULT 'De La Salle University - Manila',
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
INSERT INTO `member` VALUES (11111111,'qweqsadqfa','qweqweqw','qwasdasd',1,1,9,'1986-01-01','2017-01-01','Makati','qweqweqwewqe',8113568,654322123,4,2,'2018-10-09 13:21:38','2018-10-09 13:21:38',NULL,NULL,NULL,'De La Salle University - Manila','N/A'),(11443081,'Malijan','Samuel ','Gabrielle',1,1,1,'1997-11-22','2017-12-12','Makati','Manila',8113838,8113838,1,2,'2017-12-11 00:00:00','2017-12-11 00:00:00','9999-12-12 00:00:00',11443081,NULL,'De La Salle University - Manila','N/A'),(11443322,'Smith','John','Smiddy',1,1,9,'1985-11-22','2017-12-12','Quezon','Manila',8113823,8113232,1,2,'2018-10-09 07:42:32','2018-10-09 07:42:32',NULL,NULL,NULL,'De La Salle University - Manila','N/A'),(11540761,'Alderite','Christian','Pitalyano',1,1,1,'1990-05-19','2010-10-05','2401 Taft Ave. Manila','2401 Taft Ave. Manila',5235247,5235247,1,2,'2025-01-01 00:00:00','2000-10-15 00:00:00',NULL,11540761,NULL,'De La Salle University - Manila','N/A'),(12345678,'Hokuto','Ken','No',1,1,2,'1997-12-12','2007-12-12','Manila','Manila',8111383,8332323,1,2,'2017-12-11 00:00:00','2017-12-11 00:00:00','9999-12-12 00:00:00',11443081,NULL,'De La Salle University - Manila','N/A'),(22222222,'Guy','Test','',1,1,6,'1990-04-20','1979-03-03','Paranaque','Manila',8237333,8237333,1,2,'2017-12-11 00:00:00','2017-12-11 00:00:00',NULL,NULL,NULL,'De La Salle University - Manila','N/A'),(33333333,'Timer','Part','the',2,1,10,'1990-04-20','1979-03-03','Quezon','Manila',8113823,8113232,2,2,'2017-12-11 00:00:00','2017-12-11 00:00:00',NULL,99999999,NULL,'De La Salle University - Manila','N'),(34567890,'MyName','Jeff','Is',1,1,16,'1990-04-20','1979-03-03','random','random',9283723,12341234,1,2,'2018-10-09 13:49:26','2018-10-09 13:49:26',NULL,NULL,NULL,'De La Salle University - Manila','N/A'),(97023043,'Climaco','Julita','And',1,1,1,'1961-08-07','2006-01-01','QC',NULL,3614596,NULL,1,2,'2025-01-01 00:00:00','1983-05-01 00:00:00',NULL,99999999,NULL,'DLSU','N/A'),(99999999,'Melton','Jo','The',1,1,1,'1990-04-20','1979-03-03','Paranaque','Manila',8237333,8237333,1,2,'2017-12-11 00:00:00','2017-12-11 00:00:00',NULL,NULL,NULL,'De La Salle University - Manila','N/A');
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
INSERT INTO `member_account` VALUES (11111111,'*2470C0C06DEE42FD1618BB99005ADCA2EC9D1E19',0),(11443081,'*A4B6157319038724E3560894F7F932C8886EBFCF',1),(11443322,'*2470C0C06DEE42FD1618BB99005ADCA2EC9D1E19',0),(12345678,'*2470C0C06DEE42FD1618BB99005ADCA2EC9D1E19',1),(22222222,'*2470C0C06DEE42FD1618BB99005ADCA2EC9D1E19',0),(34567890,'*A4B6157319038724E3560894F7F932C8886EBFCF',1),(97023043,'*2470C0C06DEE42FD1618BB99005ADCA2EC9D1E19',0);
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
INSERT INTO `mother` VALUES (1,11443081,'  qweqeq','  qweqweqwe','  eqeqweq','1971-10-13',0);
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
INSERT INTO `pickup_status` VALUES (1,'PENDING FOR EVALUATION'),(2,'PROCESSING CHECK'),(3,'READY FOR PICKUP'),(4,'PICKED UP');
/*!40000 ALTER TABLE `pickup_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_comments`
--

DROP TABLE IF EXISTS `post_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_comments` (
  `id` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `content` longtext,
  `commentorId` int(11) DEFAULT NULL,
  `post_commentscol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_post_comments_posts1_idx` (`postId`),
  CONSTRAINT `fk_post_comments_posts1` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_comments`
--

LOCK TABLES `post_comments` WRITE;
/*!40000 ALTER TABLE `post_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_comments` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_status`
--

LOCK TABLES `post_status` WRITE;
/*!40000 ALTER TABLE `post_status` DISABLE KEYS */;
INSERT INTO `post_status` VALUES (1,'Pending'),(2,'Published'),(3,'Archived');
/*!40000 ALTER TABLE `post_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` longtext,
  `timeposted` datetime DEFAULT CURRENT_TIMESTAMP,
  `lastUpdated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timePublished` datetime DEFAULT NULL,
  `statusId` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`,`title`,`statusId`),
  KEY `fk_posts_post_status1_idx` (`statusId`),
  CONSTRAINT `fk_posts_post_status1` FOREIGN KEY (`statusId`) REFERENCES `post_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts_references_documents`
--

DROP TABLE IF EXISTS `posts_references_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts_references_documents` (
  `postId` int(11) NOT NULL,
  `documentId` int(11) NOT NULL,
  PRIMARY KEY (`postId`,`documentId`),
  KEY `fk_posts_has_documents_documents1_idx` (`documentId`),
  KEY `fk_posts_has_documents_posts1_idx` (`postId`),
  CONSTRAINT `fk_posts_has_documents_documents1` FOREIGN KEY (`documentId`) REFERENCES `documents` (`documentId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_posts_has_documents_posts1` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts_references_documents`
--

LOCK TABLES `posts_references_documents` WRITE;
/*!40000 ALTER TABLE `posts_references_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts_references_documents` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `txn_reference`
--

LOCK TABLES `txn_reference` WRITE;
/*!40000 ALTER TABLE `txn_reference` DISABLE KEYS */;
INSERT INTO `txn_reference` VALUES (2,11443081,1,'Pending',15000.00,'2017-12-18 00:00:00',66,NULL,4),(3,11443081,2,'Deduction from Loan',1000.00,'2017-12-19 03:21:27',66,99999999,4),(4,11443081,2,'Deduction from Loan',1000.00,'2017-12-19 03:21:50',66,99999999,4),(5,11443081,2,'Deduction from Loan',1000.00,'2017-12-19 03:21:53',66,99999999,4),(6,11443081,2,'Deduction from Loan',1000.00,'2017-12-19 03:21:53',66,99999999,4),(7,11443081,2,'Deduction from Loan',1000.00,'2017-12-19 03:21:54',66,99999999,4),(8,12345678,1,'Pending',20000.00,'2017-12-19 00:00:00',67,NULL,4),(9,12345678,1,'Pending',15000.00,'2017-12-19 00:00:00',68,NULL,4),(44,11443081,1,'Health Aid Approved',0.00,'2018-11-07 19:45:21',1,99999999,2),(45,99999999,1,'Pending',15000.00,'2018-11-10 00:00:00',75,NULL,4),(46,22222222,1,'Pending',20000.00,'2019-01-24 00:00:00',78,NULL,4),(47,12345678,2,'DEDUCTION FROM LOAN',1500.00,'2019-01-24 11:06:31',78,99999999,4),(48,12345678,2,'DEDUCTION FROM LOAN',25200.00,'2019-01-24 11:29:55',77,99999999,4),(49,11443081,2,'test',1500.00,'2019-01-24 11:42:28',66,99999999,4),(50,34567890,2,'Deduction from Loan',1575.00,'2019-01-24 12:54:39',74,99999999,4),(51,11111111,2,'Deduction from Loan',11812.50,'2019-01-24 13:10:15',70,99999999,4),(52,97023043,2,'Deduction from Loan',10500.00,'2019-01-24 15:43:38',81,99999999,4),(53,97023043,2,'Deduction from Loan',42000.00,'2019-01-24 15:44:17',81,99999999,4);
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
INSERT INTO `txn_type` VALUES (1,'APPLICATION'),(2,'DEDUCTION');
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

-- Dump completed on 2019-01-25 15:14:40
