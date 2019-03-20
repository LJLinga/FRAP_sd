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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-20 18:45:08
