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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-20 18:45:11
