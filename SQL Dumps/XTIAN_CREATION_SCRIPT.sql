-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema facultyassocnew
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `facultyassocnew` ;

-- -----------------------------------------------------
-- Schema facultyassocnew
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `facultyassocnew` DEFAULT CHARACTER SET latin1 ;
USE `facultyassocnew` ;

-- -----------------------------------------------------
-- Table `facultyassocnew`.`app_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`app_status` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`app_status` (
  `STATUS_ID` INT(1) NOT NULL,
  `STATUS` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`STATUS_ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`banks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`banks` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`banks` (
  `BANK_ID` INT(3) NOT NULL AUTO_INCREMENT,
  `BANK_NAME` VARCHAR(45) NOT NULL,
  `BANK_ABBV` VARCHAR(6) NOT NULL,
  `STATUS` TINYINT(4) NOT NULL,
  `EMP_ID_ADDED` INT(8) NOT NULL,
  `DATE_ADDED` DATETIME NOT NULL,
  `DATE_REMOVED` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`BANK_ID`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`civ_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`civ_status` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`civ_status` (
  `STATUS_ID` INT(1) NOT NULL,
  `STATUS` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`STATUS_ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`ref_department`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`ref_department` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`ref_department` (
  `DEPT_ID` INT(2) NOT NULL,
  `DEPT_NAME` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`DEPT_ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`user_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`user_status` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`user_status` (
  `STATUS_ID` INT(1) NOT NULL,
  `STATUS` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`STATUS_ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`member`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`member` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`member` (
  `MEMBER_ID` INT(8) NOT NULL,
  `LASTNAME` VARCHAR(45) NOT NULL,
  `FIRSTNAME` VARCHAR(45) NOT NULL,
  `MIDDLENAME` VARCHAR(45) NOT NULL,
  `SEX` TINYINT(4) NOT NULL,
  `CIV_STATUS` INT(1) NOT NULL,
  `DEPT_ID` INT(2) NOT NULL,
  `BIRTHDATE` DATE NOT NULL,
  `DATE_HIRED` DATE NOT NULL,
  `HOME_ADDRESS` VARCHAR(100) NOT NULL,
  `BUSINESS_ADDRESS` VARCHAR(100) NULL DEFAULT NULL,
  `HOME_NUM` INT(11) NOT NULL,
  `BUSINESS_NUM` INT(11) NULL DEFAULT NULL,
  `USER_STATUS` INT(1) NOT NULL,
  `MEMBERSHIP_STATUS` INT(1) NOT NULL,
  `DATE_APPLIED` DATETIME NOT NULL,
  `DATE_APPROVED` DATETIME NULL DEFAULT NULL,
  `DATE_REMOVED` DATETIME NULL DEFAULT NULL,
  `EMP_ID_APPROVE` INT(8) NULL DEFAULT NULL,
  `EMP_ID_REMOVE` INT(8) NULL DEFAULT NULL,
  `CAMPUS` VARCHAR(255) NOT NULL DEFAULT 'De La Salle University - Manila',
  `PART_TIME_LOANED` VARCHAR(3) NULL DEFAULT 'N/A',
  PRIMARY KEY (`MEMBER_ID`),
  CONSTRAINT `fk_membership_app_status1`
    FOREIGN KEY (`MEMBERSHIP_STATUS`)
    REFERENCES `facultyassocnew`.`app_status` (`STATUS_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_membership_civ_status1`
    FOREIGN KEY (`CIV_STATUS`)
    REFERENCES `facultyassocnew`.`civ_status` (`STATUS_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_membership_ref_department1`
    FOREIGN KEY (`DEPT_ID`)
    REFERENCES `facultyassocnew`.`ref_department` (`DEPT_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_membership_user_status1`
    FOREIGN KEY (`USER_STATUS`)
    REFERENCES `facultyassocnew`.`user_status` (`STATUS_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_membership_app_status1_idx` ON `facultyassocnew`.`member` (`MEMBERSHIP_STATUS` ASC);

CREATE INDEX `fk_membership_user_status1_idx` ON `facultyassocnew`.`member` (`USER_STATUS` ASC);

CREATE INDEX `fk_membership_civ_status1_idx` ON `facultyassocnew`.`member` (`CIV_STATUS` ASC);

CREATE INDEX `fk_membership_ref_department1_idx` ON `facultyassocnew`.`member` (`DEPT_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`health_aid`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`health_aid` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`health_aid` (
  `RECORD_ID` INT(9) NOT NULL AUTO_INCREMENT,
  `MEMBER_ID` INT(8) NOT NULL,
  `APP_STATUS` INT(1) NOT NULL,
  `DATE_APPLIED` DATETIME NOT NULL,
  `DATE_APPROVED` DATETIME NULL DEFAULT NULL,
  `EMP_ID` INT(8) NULL DEFAULT NULL,
  PRIMARY KEY (`RECORD_ID`, `MEMBER_ID`),
  CONSTRAINT `fk_health_aid_member1`
    FOREIGN KEY (`MEMBER_ID`)
    REFERENCES `facultyassocnew`.`member` (`MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_health_aid_member1_idx` ON `facultyassocnew`.`health_aid` (`MEMBER_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`children`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`children` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`children` (
  `CHILD_ID` INT(11) NOT NULL,
  `MEMBER_ID` INT(8) NOT NULL,
  `RECORD_ID` INT(9) NOT NULL,
  `LASTNAME` VARCHAR(45) NOT NULL,
  `FIRSTNAME` VARCHAR(45) NOT NULL,
  `MIDDLENAME` VARCHAR(45) NOT NULL,
  `BIRTHDATE` DATE NOT NULL,
  `STATUS` INT(1) NOT NULL,
  `SEX` TINYINT(4) NOT NULL,
  PRIMARY KEY (`CHILD_ID`, `MEMBER_ID`, `RECORD_ID`),
  CONSTRAINT `fk_siblings_health_aid10`
    FOREIGN KEY (`RECORD_ID` , `MEMBER_ID`)
    REFERENCES `facultyassocnew`.`health_aid` (`RECORD_ID` , `MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_siblings_health_aid1_idx` ON `facultyassocnew`.`children` (`RECORD_ID` ASC, `MEMBER_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`cms_roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`cms_roles` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`cms_roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`doc_availability`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`doc_availability` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`doc_availability` (
  `id` INT(11) NOT NULL,
  `availability` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`employee`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`employee` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`employee` (
  `EMP_ID` INT(8) NOT NULL,
  `PASS_WORD` VARCHAR(45) NOT NULL,
  `FIRSTNAME` VARCHAR(45) NOT NULL,
  `LASTNAME` VARCHAR(45) NOT NULL,
  `DATE_CREATED` DATETIME NOT NULL,
  `DATE_REMOVED` DATETIME NULL DEFAULT NULL,
  `ACC_STATUS` INT(1) NOT NULL,
  `FIRST_CHANGE_PW` TINYINT(4) NOT NULL,
  `MEMBER_ID` INT(11) NULL DEFAULT NULL,
  `PART_TIME_LOANED` TINYTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`EMP_ID`),
  CONSTRAINT `fk_employee_user_status1`
    FOREIGN KEY (`ACC_STATUS`)
    REFERENCES `facultyassocnew`.`user_status` (`STATUS_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_member_id`
    FOREIGN KEY (`MEMBER_ID`)
    REFERENCES `facultyassocnew`.`member` (`MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_employee_user_status1_idx` ON `facultyassocnew`.`employee` (`ACC_STATUS` ASC);

CREATE INDEX `fk_member_id_idx` ON `facultyassocnew`.`employee` (`MEMBER_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`process`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`process` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`process` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `processName` VARCHAR(255) NULL DEFAULT NULL,
  `timeCreated` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`steps`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`steps` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`steps` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `processId` INT(11) NOT NULL,
  `stepName` VARCHAR(255) NULL DEFAULT NULL,
  `stepNo` INT(2) NULL DEFAULT NULL,
  `isFinal` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`, `processId`),
  CONSTRAINT `fk_steps_process1`
    FOREIGN KEY (`processId`)
    REFERENCES `facultyassocnew`.`process` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_steps_process1_idx` ON `facultyassocnew`.`steps` (`processId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`status_dictionary`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`status_dictionary` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`status_dictionary` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `statusName` VARCHAR(45) NULL,
  `processId` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_statud_dictionary_process1`
    FOREIGN KEY (`processId`)
    REFERENCES `facultyassocnew`.`process` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_statud_dictionary_process1_idx` ON `facultyassocnew`.`status_dictionary` (`processId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`documents`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`documents` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`documents` (
  `documentId` INT(11) NOT NULL AUTO_INCREMENT,
  `firstAuthorId` INT(8) NOT NULL,
  `timeLastUpdated` DATETIME NULL DEFAULT NULL,
  `timeFirstPosted` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `lockedById` INT(8) NULL DEFAULT NULL,
  `availabilityId` INT(11) NOT NULL DEFAULT '2',
  `stepId` INT(11) NOT NULL,
  `processId` INT(11) NOT NULL,
  `statusedById` INT(8) NOT NULL,
  `statusId` INT NOT NULL,
  PRIMARY KEY (`documentId`),
  CONSTRAINT `availabilityId`
    FOREIGN KEY (`availabilityId`)
    REFERENCES `facultyassocnew`.`doc_availability` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_employee1`
    FOREIGN KEY (`firstAuthorId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_employee4`
    FOREIGN KEY (`lockedById`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_steps1`
    FOREIGN KEY (`stepId` , `processId`)
    REFERENCES `facultyassocnew`.`steps` (`id` , `processId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_employee2`
    FOREIGN KEY (`statusedById`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_documents_status_dictionary1`
    FOREIGN KEY (`statusId`)
    REFERENCES `facultyassocnew`.`status_dictionary` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 108
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_documents_employee1_idx` ON `facultyassocnew`.`documents` (`firstAuthorId` ASC);

CREATE INDEX `fk_documents_doc_availability1_idx` ON `facultyassocnew`.`documents` (`availabilityId` ASC);

CREATE INDEX `fk_documents_employee4_idx` ON `facultyassocnew`.`documents` (`lockedById` ASC);

CREATE INDEX `fk_documents_steps1_idx` ON `facultyassocnew`.`documents` (`stepId` ASC, `processId` ASC);

CREATE INDEX `fk_documents_employee2_idx` ON `facultyassocnew`.`documents` (`statusedById` ASC);

CREATE INDEX `fk_documents_status_dictionary1_idx` ON `facultyassocnew`.`documents` (`statusId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`doc_versions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`doc_versions` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`doc_versions` (
  `versionId` INT(11) NOT NULL AUTO_INCREMENT,
  `documentId` INT(11) NOT NULL,
  `versionNo` VARCHAR(45) NULL DEFAULT NULL,
  `authorId` INT(8) NOT NULL,
  `title` LONGTEXT NULL DEFAULT NULL,
  `content` LONGTEXT NULL DEFAULT NULL,
  `timeCreated` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `filePath` VARCHAR(255) NULL DEFAULT NULL,
  `fileType` VARCHAR(45) NULL DEFAULT NULL,
  `employee_EMP_ID` INT(8) NOT NULL,
  PRIMARY KEY (`versionId`, `documentId`),
  CONSTRAINT `fk_doc_versions_documents1`
    FOREIGN KEY (`documentId`)
    REFERENCES `facultyassocnew`.`documents` (`documentId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_doc_versions_employee1`
    FOREIGN KEY (`authorId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_doc_versions_employee2`
    FOREIGN KEY (`employee_EMP_ID`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 117
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_doc_versions_documents1_idx` ON `facultyassocnew`.`doc_versions` (`documentId` ASC);

CREATE INDEX `fk_doc_versions_employee1_idx` ON `facultyassocnew`.`doc_versions` (`authorId` ASC);

CREATE INDEX `fk_doc_versions_employee2_idx` ON `facultyassocnew`.`doc_versions` (`employee_EMP_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`doc_comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`doc_comments` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`doc_comments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `parentCommentId` INT(11) NOT NULL DEFAULT '0',
  `commenterId` INT(8) NOT NULL,
  `commenterName` VARCHAR(100) NULL DEFAULT NULL,
  `content` LONGTEXT NULL DEFAULT NULL,
  `versionNo` INT(11) NULL DEFAULT NULL,
  `documentId` INT(11) NULL DEFAULT NULL,
  `timePosted` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_doc_comments_doc_versions1`
    FOREIGN KEY (`versionNo` , `documentId`)
    REFERENCES `facultyassocnew`.`doc_versions` (`versionId` , `documentId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_doc_comments_employee1`
    FOREIGN KEY (`commenterId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_doc_comments_doc_versions1_idx` ON `facultyassocnew`.`doc_comments` (`versionNo` ASC, `documentId` ASC);

CREATE INDEX `fk_doc_comments_employee1_idx` ON `facultyassocnew`.`doc_comments` (`commenterId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`doc_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`doc_type` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`doc_type` (
  `id` INT(11) NOT NULL,
  `type` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`doc_version_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`doc_version_status` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`doc_version_status` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `status` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`edit_lock`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`edit_lock` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`edit_lock` (
  `id` INT(2) NOT NULL DEFAULT '2',
  `availability` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`post_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`post_status` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`post_status` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`posts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`posts` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`posts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `authorId` INT(8) NOT NULL,
  `publisherId` INT(8) NULL DEFAULT NULL,
  `reviewedById` INT(8) NULL DEFAULT NULL,
  `archivedById` INT(8) NULL DEFAULT NULL,
  `rejectedById` INT(8) NULL DEFAULT NULL,
  `lockedById` INT(8) NULL DEFAULT NULL,
  `statusId` INT(11) NOT NULL DEFAULT '1',
  `availabilityId` INT(2) NOT NULL DEFAULT '2',
  `previousStatusId` INT(11) NULL DEFAULT NULL,
  `title` LONGTEXT NOT NULL,
  `body` BLOB NULL DEFAULT NULL,
  `firstCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUpdated` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `timePublished` DATETIME NULL DEFAULT NULL,
  `permalink` LONGTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_posts_employee1`
    FOREIGN KEY (`authorId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_posts_employee2`
    FOREIGN KEY (`publisherId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_posts_post_status1`
    FOREIGN KEY (`statusId`)
    REFERENCES `facultyassocnew`.`post_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_posts_table11`
    FOREIGN KEY (`availabilityId`)
    REFERENCES `facultyassocnew`.`edit_lock` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 130
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_posts_post_status1_idx` ON `facultyassocnew`.`posts` (`statusId` ASC);

CREATE INDEX `fk_posts_employee1_idx` ON `facultyassocnew`.`posts` (`authorId` ASC);

CREATE INDEX `fk_posts_employee2_idx` ON `facultyassocnew`.`posts` (`publisherId` ASC);

CREATE INDEX `fk_posts_table11_idx` ON `facultyassocnew`.`posts` (`availabilityId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`edit_post_comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`edit_post_comments` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`edit_post_comments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `parentCommentId` INT(11) NULL DEFAULT NULL,
  `content` LONGTEXT NULL DEFAULT NULL,
  `timePosted` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `postId` INT(11) NOT NULL,
  `commenterId` INT(8) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_edit_post_comments_employee1`
    FOREIGN KEY (`commenterId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_edit_post_comments_posts1`
    FOREIGN KEY (`postId`)
    REFERENCES `facultyassocnew`.`posts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 45
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_edit_post_comments_posts1_idx` ON `facultyassocnew`.`edit_post_comments` (`postId` ASC);

CREATE INDEX `fk_edit_post_comments_employee1_idx` ON `facultyassocnew`.`edit_post_comments` (`commenterId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`edms_roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`edms_roles` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`edms_roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`event_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`event_status` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`event_status` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`events`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`events` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`events` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `posterId` INT(8) NOT NULL,
  `statusId` INT(11) NOT NULL DEFAULT '1',
  `title` VARCHAR(45) NULL DEFAULT NULL,
  `location` VARCHAR(45) NULL DEFAULT NULL,
  `description` BLOB NULL DEFAULT NULL,
  `startTime` VARCHAR(100) NULL DEFAULT NULL,
  `endTime` VARCHAR(100) NULL DEFAULT NULL,
  `goingCount` INT(11) NULL DEFAULT NULL,
  `GOOGLE_EVENTID` VARCHAR(100) NULL DEFAULT NULL,
  `GOOGLE_EVENTLINK` LONGTEXT NULL DEFAULT NULL,
  `firstCreated` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `lastUpdated` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_events_employee1`
    FOREIGN KEY (`posterId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_events_event_status1`
    FOREIGN KEY (`statusId`)
    REFERENCES `facultyassocnew`.`event_status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 37
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_events_employee1_idx` ON `facultyassocnew`.`events` (`posterId` ASC);

CREATE INDEX `fk_events_event_status1_idx` ON `facultyassocnew`.`events` (`statusId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`rsvp_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`rsvp_status` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`rsvp_status` (
  `rsvpId` INT(11) NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`rsvpId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`events_rsvp`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`events_rsvp` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`events_rsvp` (
  `eventId` INT(11) NOT NULL,
  `attendeeId` INT(8) NOT NULL,
  `rsvp_status` INT(11) NOT NULL DEFAULT '1',
  CONSTRAINT `fk_events_has_employee_employee1`
    FOREIGN KEY (`attendeeId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_events_has_employee_events1`
    FOREIGN KEY (`eventId`)
    REFERENCES `facultyassocnew`.`events` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_events_rsvp_rsvp_status1`
    FOREIGN KEY (`rsvp_status`)
    REFERENCES `facultyassocnew`.`rsvp_status` (`rsvpId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_events_has_employee_employee1_idx` ON `facultyassocnew`.`events_rsvp` (`attendeeId` ASC);

CREATE INDEX `fk_events_has_employee_events1_idx` ON `facultyassocnew`.`events_rsvp` (`eventId` ASC);

CREATE INDEX `fk_events_rsvp_rsvp_status1` ON `facultyassocnew`.`events_rsvp` (`rsvp_status` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`faculty_manual`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`faculty_manual` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`faculty_manual` (
  `id` INT(3) NOT NULL,
  `statusId` INT(11) NOT NULL,
  `year` VARCHAR(45) NULL DEFAULT NULL,
  `title` VARCHAR(45) NULL DEFAULT NULL,
  `timePublished` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_faculty_manual_manual_status1_idx` ON `facultyassocnew`.`faculty_manual` (`statusId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`loan_plan`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`loan_plan` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`loan_plan` (
  `LOAN_ID` INT(5) NOT NULL AUTO_INCREMENT,
  `BANK_ID` INT(3) NOT NULL,
  `MIN_AMOUNT` DECIMAL(7,2) NOT NULL,
  `MAX_AMOUNT` DECIMAL(7,2) NOT NULL,
  `INTEREST` INT(2) NOT NULL,
  `MIN_TERM` INT(3) NOT NULL,
  `MAX_TERM` INT(3) NOT NULL,
  `MINIMUM_SALARY` DECIMAL(7,2) NULL DEFAULT '0.00',
  `STATUS` TINYINT(4) NOT NULL,
  PRIMARY KEY (`LOAN_ID`),
  CONSTRAINT `fk_bank_loan_plan_banks1`
    FOREIGN KEY (`BANK_ID`)
    REFERENCES `facultyassocnew`.`banks` (`BANK_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_bank_loan_plan_banks1_idx` ON `facultyassocnew`.`loan_plan` (`BANK_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`loan_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`loan_status` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`loan_status` (
  `STATUS_ID` INT(11) NOT NULL,
  `STATUS` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`STATUS_ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`pickup_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`pickup_status` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`pickup_status` (
  `STATUS_ID` INT(1) NOT NULL,
  `STATUS` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`STATUS_ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`loans`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`loans` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`loans` (
  `LOAN_ID` INT(9) NOT NULL AUTO_INCREMENT,
  `MEMBER_ID` INT(8) NOT NULL,
  `LOAN_DETAIL_ID` INT(5) NOT NULL,
  `AMOUNT` DECIMAL(8,2) NOT NULL,
  `INTEREST` INT(2) NOT NULL,
  `PAYMENT_TERMS` INT(3) NOT NULL,
  `PAYABLE` DECIMAL(8,2) NOT NULL,
  `PER_PAYMENT` DECIMAL(8,2) NOT NULL,
  `AMOUNT_PAID` DECIMAL(8,2) NULL DEFAULT '0.00',
  `PAYMENTS_MADE` INT(3) NULL DEFAULT '0',
  `MIN_SALARY` DECIMAL(8,2) NULL DEFAULT NULL,
  `APP_STATUS` INT(1) NOT NULL,
  `LOAN_STATUS` INT(1) NOT NULL,
  `DATE_APPLIED` DATETIME NOT NULL,
  `DATE_APPROVED` DATETIME NULL DEFAULT NULL,
  `EMP_ID` INT(8) NULL DEFAULT NULL,
  `PICKUP_STATUS` INT(1) NOT NULL,
  `DATE_MATURED` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`LOAN_ID`, `MEMBER_ID`),
  CONSTRAINT `fk_bank_loans_app_status1`
    FOREIGN KEY (`APP_STATUS`)
    REFERENCES `facultyassocnew`.`app_status` (`STATUS_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bank_loans_bank_loan_plan1`
    FOREIGN KEY (`LOAN_DETAIL_ID`)
    REFERENCES `facultyassocnew`.`loan_plan` (`LOAN_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bank_loans_loan_status1`
    FOREIGN KEY (`LOAN_STATUS`)
    REFERENCES `facultyassocnew`.`loan_status` (`STATUS_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bank_loans_member1`
    FOREIGN KEY (`MEMBER_ID`)
    REFERENCES `facultyassocnew`.`member` (`MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bank_loans_pickup_status1`
    FOREIGN KEY (`PICKUP_STATUS`)
    REFERENCES `facultyassocnew`.`pickup_status` (`STATUS_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 82
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_bank_loans_member1_idx` ON `facultyassocnew`.`loans` (`MEMBER_ID` ASC);

CREATE INDEX `fk_bank_loans_bank_loan_plan1_idx` ON `facultyassocnew`.`loans` (`LOAN_DETAIL_ID` ASC);

CREATE INDEX `fk_bank_loans_app_status1_idx` ON `facultyassocnew`.`loans` (`APP_STATUS` ASC);

CREATE INDEX `fk_bank_loans_loan_status1_idx` ON `facultyassocnew`.`loans` (`LOAN_STATUS` ASC);

CREATE INDEX `fk_bank_loans_pickup_status1_idx` ON `facultyassocnew`.`loans` (`PICKUP_STATUS` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`falp_requirements`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`falp_requirements` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`falp_requirements` (
  `REQ_ID` INT(9) NOT NULL AUTO_INCREMENT,
  `LOAN_ID` INT(9) NOT NULL,
  `MEMBER_ID` INT(8) NOT NULL,
  `ICR_DIR` VARCHAR(255) NOT NULL,
  `PAYSLIP_DIR` VARCHAR(255) NOT NULL,
  `EMP_ID_DIR` VARCHAR(255) NOT NULL,
  `GOV_ID_DIR` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`REQ_ID`, `LOAN_ID`, `MEMBER_ID`),
  CONSTRAINT `fk_bank_requirements_loans1`
    FOREIGN KEY (`LOAN_ID` , `MEMBER_ID`)
    REFERENCES `facultyassocnew`.`loans` (`LOAN_ID` , `MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 146
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_bank_requirements_loans1_idx` ON `facultyassocnew`.`falp_requirements` (`LOAN_ID` ASC, `MEMBER_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`father`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`father` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`father` (
  `RECORD_ID` INT(9) NOT NULL,
  `MEMBER_ID` INT(8) NOT NULL,
  `LASTNAME` VARCHAR(45) NULL DEFAULT NULL,
  `FIRSTNAME` VARCHAR(45) NULL DEFAULT NULL,
  `MIDDLENAME` VARCHAR(45) NULL DEFAULT NULL,
  `BIRTHDATE` VARCHAR(45) NULL DEFAULT NULL,
  `STATUS` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`RECORD_ID`, `MEMBER_ID`),
  CONSTRAINT `fk_father_health_aid1`
    FOREIGN KEY (`RECORD_ID` , `MEMBER_ID`)
    REFERENCES `facultyassocnew`.`health_aid` (`RECORD_ID` , `MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_father_health_aid1_idx` ON `facultyassocnew`.`father` (`RECORD_ID` ASC, `MEMBER_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`file_audit_table`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`file_audit_table` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`file_audit_table` (
  `actionID` INT(11) NOT NULL AUTO_INCREMENT,
  `id` INT(11) NULL DEFAULT NULL,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `dateTime` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`actionID`))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`frap_roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`frap_roles` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`frap_roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`gdrive_folderid`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`gdrive_folderid` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`gdrive_folderid` (
  `memberID` INT(11) NOT NULL,
  `folderID` VARCHAR(245) NULL DEFAULT NULL,
  PRIMARY KEY (`memberID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`groups` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`groups` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `groupName` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`lifetime`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`lifetime` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`lifetime` (
  `MEMBER_ID` INT(8) NOT NULL,
  `PRIMARY` VARCHAR(135) NULL DEFAULT NULL,
  `SECONDARY` VARCHAR(135) NULL DEFAULT NULL,
  `ORG` VARCHAR(135) NULL DEFAULT NULL,
  `APP_STATUS` INT(1) NOT NULL,
  `DATE_ADDED` DATE NOT NULL,
  `EMP_ID` INT(8) NOT NULL,
  PRIMARY KEY (`MEMBER_ID`),
  CONSTRAINT `fk_lifetime_employee1`
    FOREIGN KEY (`EMP_ID`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lifetime_member1`
    FOREIGN KEY (`MEMBER_ID`)
    REFERENCES `facultyassocnew`.`member` (`MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_lifetime_employee1_idx` ON `facultyassocnew`.`lifetime` (`EMP_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`manual_categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`manual_categories` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`manual_categories` (
  `id` INT(3) NOT NULL,
  `category` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`manual_categories_has_faculty_manual`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`manual_categories_has_faculty_manual` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`manual_categories_has_faculty_manual` (
  `manual_categories_id` INT(3) NOT NULL,
  `faculty_manual_id` INT(3) NOT NULL,
  `category` VARCHAR(45) NULL DEFAULT NULL,
  CONSTRAINT `fk_manual_categories_has_faculty_manual_faculty_manual1`
    FOREIGN KEY (`faculty_manual_id`)
    REFERENCES `facultyassocnew`.`faculty_manual` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_manual_categories_has_faculty_manual_manual_categories1`
    FOREIGN KEY (`manual_categories_id`)
    REFERENCES `facultyassocnew`.`manual_categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE UNIQUE INDEX `manual_categories_has_faculty_manualcol_UNIQUE` ON `facultyassocnew`.`manual_categories_has_faculty_manual` (`category` ASC);

CREATE INDEX `fk_manual_categories_has_faculty_manual_faculty_manual1_idx` ON `facultyassocnew`.`manual_categories_has_faculty_manual` (`faculty_manual_id` ASC);

CREATE INDEX `fk_manual_categories_has_faculty_manual_manual_categories1_idx` ON `facultyassocnew`.`manual_categories_has_faculty_manual` (`manual_categories_id` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`manual_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`manual_status` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`manual_status` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `status` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`member_account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`member_account` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`member_account` (
  `MEMBER_ID` INT(8) NOT NULL,
  `PASSWORD` VARCHAR(41) NOT NULL,
  `FIRST_CHANGE_PW` TINYINT(4) NOT NULL,
  PRIMARY KEY (`MEMBER_ID`),
  CONSTRAINT `fk_member_membership1`
    FOREIGN KEY (`MEMBER_ID`)
    REFERENCES `facultyassocnew`.`member` (`MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE UNIQUE INDEX `MEMBER_ID_UNIQUE` ON `facultyassocnew`.`member_account` (`MEMBER_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`mother`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`mother` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`mother` (
  `RECORD_ID` INT(9) NOT NULL,
  `MEMBER_ID` INT(8) NOT NULL,
  `LASTNAME` VARCHAR(45) NULL DEFAULT NULL,
  `FIRSTNAME` VARCHAR(45) NULL DEFAULT NULL,
  `MIDDLENAME` VARCHAR(45) NULL DEFAULT NULL,
  `BIRTHDATE` VARCHAR(45) NULL DEFAULT NULL,
  `STATUS` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`RECORD_ID`, `MEMBER_ID`),
  CONSTRAINT `fk_mother_health_aid1`
    FOREIGN KEY (`RECORD_ID` , `MEMBER_ID`)
    REFERENCES `facultyassocnew`.`health_aid` (`RECORD_ID` , `MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_mother_health_aid1_idx` ON `facultyassocnew`.`mother` (`RECORD_ID` ASC, `MEMBER_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`post_comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`post_comments` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`post_comments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `postId` INT(11) NOT NULL,
  `parentCommentId` INT(11) NOT NULL DEFAULT '0',
  `content` LONGTEXT NULL DEFAULT NULL,
  `commenterId` INT(8) NOT NULL,
  `timePosted` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_post_comments_employee1`
    FOREIGN KEY (`commenterId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_comments_posts1`
    FOREIGN KEY (`postId`)
    REFERENCES `facultyassocnew`.`posts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 71
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_post_comments_posts1_idx` ON `facultyassocnew`.`post_comments` (`postId` ASC);

CREATE INDEX `fk_post_comments_employee1_idx` ON `facultyassocnew`.`post_comments` (`commenterId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`view_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`view_type` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`view_type` (
  `id` INT(2) NOT NULL,
  `type` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`post_views`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`post_views` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`post_views` (
  `id` INT(11) NOT NULL,
  `viewerId` INT(8) NOT NULL,
  `typeId` INT(2) NOT NULL,
  `timeStamp` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_post_views_employee1`
    FOREIGN KEY (`viewerId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_views_post_types1`
    FOREIGN KEY (`typeId`)
    REFERENCES `facultyassocnew`.`view_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_views_posts1`
    FOREIGN KEY (`id`)
    REFERENCES `facultyassocnew`.`posts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_post_views_posts1_idx` ON `facultyassocnew`.`post_views` (`id` ASC);

CREATE INDEX `fk_post_views_employee1_idx` ON `facultyassocnew`.`post_views` (`viewerId` ASC);

CREATE INDEX `fk_post_views_post_types1_idx` ON `facultyassocnew`.`post_views` (`typeId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`process_for`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`process_for` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`process_for` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `process_for` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`step_routes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`step_routes` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`step_routes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `routeName` VARCHAR(255) NULL DEFAULT NULL,
  `processId` INT(11) NOT NULL,
  `stepId` INT(11) NOT NULL,
  `nextStepId` INT(11) NOT NULL,
  `nextProcessId` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_routes_steps1`
    FOREIGN KEY (`processId` , `stepId`)
    REFERENCES `facultyassocnew`.`steps` (`processId` , `id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_routes_steps2`
    FOREIGN KEY (`nextStepId` , `nextProcessId`)
    REFERENCES `facultyassocnew`.`steps` (`id` , `processId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_routes_steps1_idx` ON `facultyassocnew`.`step_routes` (`processId` ASC, `stepId` ASC);

CREATE INDEX `fk_routes_steps2_idx` ON `facultyassocnew`.`step_routes` (`nextStepId` ASC, `nextProcessId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`section_availability`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`section_availability` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`section_availability` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `availability` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`sections`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`sections` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`sections` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `manualId` INT(3) NOT NULL,
  `parentSectionId` INT(11) NOT NULL DEFAULT '0',
  `sectionNumber` INT(3) NOT NULL DEFAULT '9999',
  `firstAuthorId` INT(8) NOT NULL,
  `archivedById` INT(8) NULL DEFAULT NULL,
  `approvedById` INT(8) NULL DEFAULT NULL,
  `timeCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `categoryId` INT(3) NOT NULL,
  PRIMARY KEY (`id`, `manualId`),
  CONSTRAINT `fk_sections_employee1`
    FOREIGN KEY (`firstAuthorId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sections_employee2`
    FOREIGN KEY (`archivedById`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sections_employee3`
    FOREIGN KEY (`approvedById`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sections_faculty_manual1`
    FOREIGN KEY (`manualId`)
    REFERENCES `facultyassocnew`.`faculty_manual` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sections_manual_categories1`
    FOREIGN KEY (`categoryId`)
    REFERENCES `facultyassocnew`.`manual_categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_sections_employee1_idx` ON `facultyassocnew`.`sections` (`firstAuthorId` ASC);

CREATE INDEX `fk_sections_employee2_idx` ON `facultyassocnew`.`sections` (`archivedById` ASC);

CREATE INDEX `fk_sections_employee3_idx` ON `facultyassocnew`.`sections` (`approvedById` ASC);

CREATE INDEX `fk_sections_faculty_manual1_idx` ON `facultyassocnew`.`sections` (`manualId` ASC);

CREATE INDEX `fk_sections_manual_categories1_idx` ON `facultyassocnew`.`sections` (`categoryId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`section_versions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`section_versions` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`section_versions` (
  `versionNo` INT(3) NOT NULL,
  `sectionId` INT(11) NOT NULL,
  `authorId` INT(8) NOT NULL,
  `title` VARCHAR(255) NULL DEFAULT NULL,
  `body` BLOB NULL DEFAULT NULL,
  `timeCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`versionNo`, `sectionId`),
  CONSTRAINT `fk_section_versions_employee1`
    FOREIGN KEY (`authorId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_section_versions_sections1`
    FOREIGN KEY (`sectionId`)
    REFERENCES `facultyassocnew`.`sections` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_section_versions_sections1_idx` ON `facultyassocnew`.`section_versions` (`sectionId` ASC);

CREATE INDEX `fk_section_versions_employee1_idx` ON `facultyassocnew`.`section_versions` (`authorId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`section_comments`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`section_comments` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`section_comments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sectionVersionNo` INT(11) NOT NULL,
  `sectionId` INT(11) NOT NULL,
  `commenterId` INT(8) NOT NULL,
  `body` LONGTEXT NULL DEFAULT NULL,
  `timePosted` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `parentCommentId` INT(11) NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_section_comments_employee1`
    FOREIGN KEY (`commenterId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_section_comments_section_versions1`
    FOREIGN KEY (`sectionVersionNo` , `sectionId`)
    REFERENCES `facultyassocnew`.`section_versions` (`versionNo` , `sectionId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_section_comments_section_versions1_idx` ON `facultyassocnew`.`section_comments` (`sectionVersionNo` ASC, `sectionId` ASC);

CREATE INDEX `fk_section_comments_employee1_idx` ON `facultyassocnew`.`section_comments` (`commenterId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`section_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`section_status` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`section_status` (
  `id` INT(2) NOT NULL,
  `status` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`service_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`service_type` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`service_type` (
  `SERVICE_ID` INT(11) NOT NULL,
  `SERVICE` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`SERVICE_ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`siblings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`siblings` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`siblings` (
  `SIBLING_ID` INT(11) NOT NULL,
  `MEMBER_ID` INT(8) NOT NULL,
  `RECORD_ID` INT(9) NOT NULL,
  `LASTNAME` VARCHAR(45) NOT NULL,
  `FIRSTNAME` VARCHAR(45) NOT NULL,
  `MIDDLENAME` VARCHAR(45) NOT NULL,
  `BIRTHDATE` DATE NOT NULL,
  `STATUS` INT(1) NOT NULL,
  `SEX` TINYINT(4) NOT NULL,
  PRIMARY KEY (`SIBLING_ID`, `MEMBER_ID`, `RECORD_ID`),
  CONSTRAINT `fk_siblings_health_aid1`
    FOREIGN KEY (`RECORD_ID` , `MEMBER_ID`)
    REFERENCES `facultyassocnew`.`health_aid` (`RECORD_ID` , `MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_siblings_health_aid1_idx` ON `facultyassocnew`.`siblings` (`RECORD_ID` ASC, `MEMBER_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`spouse`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`spouse` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`spouse` (
  `RECORD_ID` INT(9) NOT NULL,
  `MEMBER_ID` INT(8) NOT NULL,
  `LASTNAME` VARCHAR(45) NOT NULL,
  `FIRSTNAME` VARCHAR(45) NOT NULL,
  `MIDDLENAME` VARCHAR(45) NOT NULL,
  `BIRTHDATE` DATE NOT NULL,
  `STATUS` INT(1) NOT NULL,
  PRIMARY KEY (`RECORD_ID`, `MEMBER_ID`),
  CONSTRAINT `fk_spouse_health_aid1`
    FOREIGN KEY (`RECORD_ID` , `MEMBER_ID`)
    REFERENCES `facultyassocnew`.`health_aid` (`RECORD_ID` , `MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`step_author`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`step_author` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`step_author` (
  `read` INT(1) NULL DEFAULT '1',
  `write` INT(1) NULL DEFAULT '1',
  `route` INT(1) NULL DEFAULT '1',
  `comment` INT(1) NULL DEFAULT '1',
  `stepId` INT(11) NOT NULL,
  `processId` INT(11) NOT NULL,
  CONSTRAINT `fk_step_author_steps1`
    FOREIGN KEY (`stepId` , `processId`)
    REFERENCES `facultyassocnew`.`steps` (`id` , `processId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_step_author_steps1_idx` ON `facultyassocnew`.`step_author` (`stepId` ASC, `processId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`step_groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`step_groups` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`step_groups` (
  `read` INT(1) NULL DEFAULT '1',
  `write` INT(1) NULL DEFAULT '1',
  `route` INT(1) NULL DEFAULT '1',
  `comment` INT(1) NULL DEFAULT '1',
  `processId` INT(11) NOT NULL,
  `stepId` INT(11) NOT NULL,
  `groupId` INT(11) NOT NULL,
  CONSTRAINT `fk_step_personnel_groups1`
    FOREIGN KEY (`groupId`)
    REFERENCES `facultyassocnew`.`groups` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_step_personnel_steps1`
    FOREIGN KEY (`processId` , `stepId`)
    REFERENCES `facultyassocnew`.`steps` (`processId` , `id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_step_personnel_steps1_idx` ON `facultyassocnew`.`step_groups` (`processId` ASC, `stepId` ASC);

CREATE INDEX `fk_step_personnel_groups1_idx` ON `facultyassocnew`.`step_groups` (`groupId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`step_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`step_users` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`step_users` (
  `stepId` INT(11) NOT NULL,
  `processId` INT(11) NOT NULL,
  `userId` INT(8) NOT NULL,
  `read` INT(1) NULL DEFAULT '1',
  `write` INT(1) NULL DEFAULT '1',
  `route` INT(1) NULL DEFAULT '1',
  `comment` INT(1) NULL DEFAULT '1',
  CONSTRAINT `fk_steps_has_employee_employee1`
    FOREIGN KEY (`userId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_steps_has_employee_steps1`
    FOREIGN KEY (`stepId` , `processId`)
    REFERENCES `facultyassocnew`.`steps` (`id` , `processId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE INDEX `fk_steps_has_employee_employee1_idx` ON `facultyassocnew`.`step_users` (`userId` ASC);

CREATE INDEX `fk_steps_has_employee_steps1_idx` ON `facultyassocnew`.`step_users` (`stepId` ASC, `processId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`sys_roles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`sys_roles` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`sys_roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`txn_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`txn_type` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`txn_type` (
  `TYPE_ID` INT(1) NOT NULL,
  `TYPE` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`TYPE_ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `facultyassocnew`.`txn_reference`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`txn_reference` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`txn_reference` (
  `TXN_ID` INT(10) NOT NULL AUTO_INCREMENT,
  `MEMBER_ID` INT(8) NOT NULL,
  `TXN_TYPE` INT(1) NOT NULL,
  `TXN_DESC` VARCHAR(100) NOT NULL,
  `AMOUNT` DECIMAL(7,2) NOT NULL,
  `TXN_DATE` DATETIME NOT NULL,
  `LOAN_REF` INT(9) NULL DEFAULT NULL,
  `EMP_ID` INT(8) NULL DEFAULT NULL,
  `SERVICE_ID` INT(11) NOT NULL,
  PRIMARY KEY (`TXN_ID`, `MEMBER_ID`),
  CONSTRAINT `fk_txn_reference_bank_loans1`
    FOREIGN KEY (`LOAN_REF`)
    REFERENCES `facultyassocnew`.`loans` (`LOAN_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_txn_reference_employee1`
    FOREIGN KEY (`EMP_ID`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_txn_reference_member1`
    FOREIGN KEY (`MEMBER_ID`)
    REFERENCES `facultyassocnew`.`member` (`MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_txn_reference_service_type1`
    FOREIGN KEY (`SERVICE_ID`)
    REFERENCES `facultyassocnew`.`service_type` (`SERVICE_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_txn_reference_txn_type1`
    FOREIGN KEY (`TXN_TYPE`)
    REFERENCES `facultyassocnew`.`txn_type` (`TYPE_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 56
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_txn_reference_txn_type1_idx` ON `facultyassocnew`.`txn_reference` (`TXN_TYPE` ASC);

CREATE INDEX `fk_txn_reference_member1_idx` ON `facultyassocnew`.`txn_reference` (`MEMBER_ID` ASC);

CREATE INDEX `fk_txn_reference_employee1_idx` ON `facultyassocnew`.`txn_reference` (`EMP_ID` ASC);

CREATE INDEX `fk_txn_reference_bank_loans1` ON `facultyassocnew`.`txn_reference` (`LOAN_REF` ASC);

CREATE INDEX `fk_txn_reference_service_type1_idx` ON `facultyassocnew`.`txn_reference` (`SERVICE_ID` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`user_groups`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`user_groups` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`user_groups` (
  `employeeId` INT(8) NOT NULL,
  `groupId` INT(11) NOT NULL,
  CONSTRAINT `fk_employee_has_groups_employee1`
    FOREIGN KEY (`employeeId`)
    REFERENCES `facultyassocnew`.`employee` (`EMP_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_employee_has_groups_groups1`
    FOREIGN KEY (`groupId`)
    REFERENCES `facultyassocnew`.`groups` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_employee_has_groups_groups1_idx` ON `facultyassocnew`.`user_groups` (`groupId` ASC);

CREATE INDEX `fk_employee_has_groups_employee1_idx` ON `facultyassocnew`.`user_groups` (`employeeId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`doc_ref_versions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`doc_ref_versions` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`doc_ref_versions` (
  `refVersionId` INT(11) NOT NULL,
  `refDocumentId` INT(11) NOT NULL,
  `documentId` INT(11) NOT NULL,
  CONSTRAINT `fk_doc_ref_versions_doc_versions1`
    FOREIGN KEY (`refVersionId` , `refDocumentId`)
    REFERENCES `facultyassocnew`.`doc_versions` (`versionId` , `documentId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_doc_ref_versions_documents1`
    FOREIGN KEY (`documentId`)
    REFERENCES `facultyassocnew`.`documents` (`documentId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_doc_ref_versions_doc_versions1_idx` ON `facultyassocnew`.`doc_ref_versions` (`refVersionId` ASC, `refDocumentId` ASC);

CREATE INDEX `fk_doc_ref_versions_documents1_idx` ON `facultyassocnew`.`doc_ref_versions` (`documentId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`post_ref_versions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`post_ref_versions` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`post_ref_versions` (
  `postId` INT(11) NOT NULL,
  `versionId` INT(11) NOT NULL,
  `documentId` INT(11) NOT NULL,
  CONSTRAINT `fk_post_ref_versions_posts1`
    FOREIGN KEY (`postId`)
    REFERENCES `facultyassocnew`.`posts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_ref_versions_doc_versions1`
    FOREIGN KEY (`versionId` , `documentId`)
    REFERENCES `facultyassocnew`.`doc_versions` (`versionId` , `documentId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_post_ref_versions_posts1_idx` ON `facultyassocnew`.`post_ref_versions` (`postId` ASC);

CREATE INDEX `fk_post_ref_versions_doc_versions1_idx` ON `facultyassocnew`.`post_ref_versions` (`versionId` ASC, `documentId` ASC);


-- -----------------------------------------------------
-- Table `facultyassocnew`.`loan_ref_docs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `facultyassocnew`.`loan_ref_docs` ;

CREATE TABLE IF NOT EXISTS `facultyassocnew`.`loan_ref_docs` (
  `LOAN_ID` INT(9) NOT NULL,
  `MEMBER_ID` INT(8) NOT NULL,
  `DOC_ID` INT(11) NOT NULL,
  CONSTRAINT `fk_loan_ref_docs_loans1`
    FOREIGN KEY (`LOAN_ID` , `MEMBER_ID`)
    REFERENCES `facultyassocnew`.`loans` (`LOAN_ID` , `MEMBER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_loan_ref_docs_documents1`
    FOREIGN KEY (`DOC_ID`)
    REFERENCES `facultyassocnew`.`documents` (`documentId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_loan_ref_docs_loans1_idx` ON `facultyassocnew`.`loan_ref_docs` (`LOAN_ID` ASC, `MEMBER_ID` ASC);

CREATE INDEX `fk_loan_ref_docs_documents1_idx` ON `facultyassocnew`.`loan_ref_docs` (`DOC_ID` ASC);

USE `facultyassocnew`;

DELIMITER $$

USE `facultyassocnew`$$
DROP TRIGGER IF EXISTS `facultyassocnew`.`before_permalink_post` $$
USE `facultyassocnew`$$
CREATE
DEFINER=`root`@`localhost`
TRIGGER `facultyassocnew`.`before_permalink_post`
BEFORE UPDATE ON `facultyassocnew`.`posts`
FOR EACH ROW
IF (old.permalink IS NULL) THEN
SET new.permalink = CONCAT(new.permalink,'-', DATE_FORMAT(old.timePublished,'%Y%m%d%H%i%s'));
END IF$$


USE `facultyassocnew`$$
DROP TRIGGER IF EXISTS `facultyassocnew`.`before_publish_post` $$
USE `facultyassocnew`$$
CREATE
DEFINER=`root`@`localhost`
TRIGGER `facultyassocnew`.`before_publish_post`
BEFORE UPDATE ON `facultyassocnew`.`posts`
FOR EACH ROW
IF (new.statusId = 4 AND old.timePublished IS NULL) THEN
	SET new.timePublished = new.lastUpdated;
END IF$$


USE `facultyassocnew`$$
DROP TRIGGER IF EXISTS `facultyassocnew`.`before_trash_post` $$
USE `facultyassocnew`$$
CREATE
DEFINER=`root`@`localhost`
TRIGGER `facultyassocnew`.`before_trash_post`
BEFORE UPDATE ON `facultyassocnew`.`posts`
FOR EACH ROW
BEGIN
    IF (new.statusId = 5 AND old.statusId!=new.statusId) THEN
	SET new.previousStatusId = old.statusId ;
END IF;
END$$


DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `facultyassocnew`.`doc_availability`
-- -----------------------------------------------------
START TRANSACTION;
USE `facultyassocnew`;
INSERT INTO `facultyassocnew`.`doc_availability` (`id`, `availability`) VALUES (1, 'Locked');
INSERT INTO `facultyassocnew`.`doc_availability` (`id`, `availability`) VALUES (2, 'Available');

COMMIT;


-- -----------------------------------------------------
-- Data for table `facultyassocnew`.`process`
-- -----------------------------------------------------
START TRANSACTION;
USE `facultyassocnew`;
INSERT INTO `facultyassocnew`.`process` (`id`, `processName`, `timeCreated`) VALUES (1, 'FALP Application', NULL);
INSERT INTO `facultyassocnew`.`process` (`id`, `processName`, `timeCreated`) VALUES (2, 'Post Approval', NULL);
INSERT INTO `facultyassocnew`.`process` (`id`, `processName`, `timeCreated`) VALUES (3, 'Section Approval', NULL);
INSERT INTO `facultyassocnew`.`process` (`id`, `processName`, `timeCreated`) VALUES (4, 'By Laws Approval', NULL);
INSERT INTO `facultyassocnew`.`process` (`id`, `processName`, `timeCreated`) VALUES (5, 'Post Attachment Approval', NULL);
INSERT INTO `facultyassocnew`.`process` (`id`, `processName`, `timeCreated`) VALUES (99, 'No Process', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `facultyassocnew`.`steps`
-- -----------------------------------------------------
START TRANSACTION;
USE `facultyassocnew`;
INSERT INTO `facultyassocnew`.`steps` (`id`, `processId`, `stepName`, `stepNo`, `isFinal`) VALUES (1, 99, 'No Step', 0, 1);
INSERT INTO `facultyassocnew`.`steps` (`id`, `processId`, `stepName`, `stepNo`, `isFinal`) VALUES (2, 1, 'Review by Secretary', 1, 1);
INSERT INTO `facultyassocnew`.`steps` (`id`, `processId`, `stepName`, `stepNo`, `isFinal`) VALUES (3, 1, 'Review by President', 2, 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `facultyassocnew`.`status_dictionary`
-- -----------------------------------------------------
START TRANSACTION;
USE `facultyassocnew`;
INSERT INTO `facultyassocnew`.`status_dictionary` (`id`, `statusName`, `processId`) VALUES (1, 'Pending', 1);
INSERT INTO `facultyassocnew`.`status_dictionary` (`id`, `statusName`, `processId`) VALUES (2, 'Approved', 1);
INSERT INTO `facultyassocnew`.`status_dictionary` (`id`, `statusName`, `processId`) VALUES (3, 'Rejected', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `facultyassocnew`.`step_routes`
-- -----------------------------------------------------
START TRANSACTION;
USE `facultyassocnew`;
INSERT INTO `facultyassocnew`.`step_routes` (`id`, `routeName`, `processId`, `stepId`, `nextStepId`, `nextProcessId`) VALUES (1, 'For EB Review', 1, 2, 3, 1);
INSERT INTO `facultyassocnew`.`step_routes` (`id`, `routeName`, `processId`, `stepId`, `nextStepId`, `nextProcessId`) VALUES (2, 'For Reupload', 1, 3, 2, 1);

COMMIT;

