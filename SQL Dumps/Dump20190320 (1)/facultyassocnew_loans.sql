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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-20 18:45:13
