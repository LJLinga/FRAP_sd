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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-20 18:45:14
