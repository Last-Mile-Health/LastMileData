-- MySQL dump 10.13  Distrib 5.5.36, for Linux (x86_64)
--
-- Host: localhost    Database: lastmiledata
-- ------------------------------------------------------
-- Server version	5.5.36-cll-lve

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
-- Table structure for table `0_testde`
--

DROP TABLE IF EXISTS `0_testde`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `0_testde` (
  `primaryKey` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `var1` varchar(45) DEFAULT NULL,
  `var2` varchar(45) DEFAULT NULL,
  `var3` tinyint(1) DEFAULT NULL COMMENT 'try using BINARY type',
  `var4` tinyint(1) DEFAULT NULL COMMENT 'try using BINARY type',
  `var5` tinyint(1) DEFAULT NULL COMMENT 'try using BINARY type',
  `var6` varchar(45) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`primaryKey`),
  UNIQUE KEY `primaryKey_UNIQUE` (`primaryKey`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `0_testde`
--

LOCK TABLES `0_testde` WRITE;
/*!40000 ALTER TABLE `0_testde` DISABLE KEYS */;
/*!40000 ALTER TABLE `0_testde` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_utility_logins`
--

DROP TABLE IF EXISTS `tbl_utility_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_utility_logins` (
  `primaryKey` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `loginTime` datetime DEFAULT NULL,
  PRIMARY KEY (`primaryKey`),
  UNIQUE KEY `primaryKey_UNIQUE` (`primaryKey`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_utility_logins`
--

LOCK TABLES `tbl_utility_logins` WRITE;
/*!40000 ALTER TABLE `tbl_utility_logins` DISABLE KEYS */;
INSERT INTO `tbl_utility_logins` VALUES (000009,'akenny','2014-08-06 17:49:17'),(000010,'vsathananthan','2014-08-06 20:10:34'),(000011,'akenny','2014-08-06 20:10:54'),(000012,'akenny','2014-08-06 20:11:38'),(000013,'akenny','2014-08-06 21:49:45'),(000014,'vsathananthan','2014-08-07 05:26:41'),(000015,'vsathananthan','2014-08-07 05:26:44'),(000016,'akenny','2014-08-11 11:37:03'),(000017,'akenny','2014-08-12 09:58:08'),(000018,'vsathananthan','2014-08-12 10:22:04'),(000019,'akenny','2014-08-12 10:29:11'),(000020,'vsathananthan','2014-08-14 15:40:33'),(000021,'akenny','2014-08-14 21:39:46'),(000022,'akenny','2014-08-15 00:57:14'),(000023,'jalbert','2014-08-15 11:52:58'),(000024,'knaylor','2014-08-15 12:04:45'),(000025,'sparish','2014-08-15 12:04:50'),(000026,'bvandebogert','2014-08-15 12:05:34'),(000027,'smukherjee','2014-08-15 12:06:13'),(000028,'cocitti','2014-08-15 12:10:21'),(000029,'jalbert','2014-08-15 12:10:35'),(000030,'tslagle','2014-08-15 12:15:53'),(000031,'rpanjabi','2014-08-15 12:16:26'),(000032,'smukherjee','2014-08-15 12:18:02'),(000033,'rpanjabi','2014-08-15 12:20:55'),(000034,'oamir','2014-08-15 13:10:48'),(000035,'oamir','2014-08-15 13:12:22'),(000036,'knaylor','2014-08-15 14:56:37'),(000037,'akenny','2014-08-15 16:58:06'),(000038,'akenny','2014-08-16 16:10:31'),(000039,'akenny','2014-08-16 19:26:53'),(000040,'akenny','2014-08-18 14:25:07'),(000041,'akenny','2014-08-18 16:58:40');
/*!40000 ALTER TABLE `tbl_utility_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_utility_users`
--

DROP TABLE IF EXISTS `tbl_utility_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_utility_users` (
  `primaryKey` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` char(40) NOT NULL DEFAULT 'aabde2d468faec8395fa24ac27e6802e5d6e6297',
  `usertype` varchar(45) DEFAULT 'user',
  PRIMARY KEY (`primaryKey`),
  UNIQUE KEY `primaryKey_UNIQUE` (`primaryKey`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_utility_users`
--

LOCK TABLES `tbl_utility_users` WRITE;
/*!40000 ALTER TABLE `tbl_utility_users` DISABLE KEYS */;
INSERT INTO `tbl_utility_users` VALUES (000002,'akenny','ec550a8708b22601bdab1326bcbeac9b9a915b0c','admin'),(000003,'vsathananthan','aabde2d468faec8395fa24ac27e6802e5d6e6297','admin'),(000004,'msiedner','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000005,'jkraemer','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000006,'gbasu','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000007,'oamir','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000008,'asechler','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000009,'blorenzen','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000010,'cocitti','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000011,'jrabinowich','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000012,'jly','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000013,'jalbert','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000014,'lmccormick','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000015,'mballard','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000016,'rpanjabi','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000017,'sparish','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000018,'tgriffiths','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000019,'tslagle','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000020,'ukarmue','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000021,'zkanjee','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000022,'kerlandson','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000023,'astergakis','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000024,'ajohnson','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000025,'knaylor','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000026,'smukherjee','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000027,'bvandebogert','aabde2d468faec8395fa24ac27e6802e5d6e6297','user');
/*!40000 ALTER TABLE `tbl_utility_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `primaryKey` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `col_1` longtext,
  `col_2` longtext,
  `col_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`primaryKey`),
  UNIQUE KEY `primaryKey_UNIQUE` (`primaryKey`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test`
--

LOCK TABLES `test` WRITE;
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
/*!40000 ALTER TABLE `test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uploads` (
  `primaryKey` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `folder` longtext NOT NULL,
  `filename` varchar(200) NOT NULL,
  `uploadDatetime` datetime DEFAULT NULL,
  `uploadedBy` varchar(45) DEFAULT NULL,
  `notes` longtext,
  `isFolder` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`primaryKey`),
  UNIQUE KEY `primaryKey_UNIQUE` (`primaryKey`),
  UNIQUE KEY `filename_UNIQUE` (`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uploads`
--

LOCK TABLES `uploads` WRITE;
/*!40000 ALTER TABLE `uploads` DISABLE KEYS */;
INSERT INTO `uploads` VALUES (000111,'uploads','Strategy','2014-08-16 19:32:40','akenny','Roadmap, timeline, and other M&E strategy documents',1),(000112,'uploads','FHW Performance Management','2014-08-16 19:32:47','akenny','Our approach to measuring and systematically improving FHW performance',1),(000113,'uploads','Last Mile Survey','2014-08-16 19:33:27','akenny','Contains documents and reports related to the Last Mile Survey, including KBS 2012',1),(000114,'uploads > FHW Performance Management','FHW Performance Evaluation (one-pager).pdf','2014-08-16 19:33:52','akenny','',0),(000115,'uploads > FHW Performance Management','FHW Questionnaire (Liberian English).pdf','2014-08-16 19:34:00','akenny','',0),(000116,'uploads > FHW Performance Management','FHW Scorecard (mock-up).pdf','2014-08-16 19:34:07','akenny','',0),(000117,'uploads > FHW Performance Management','Site-level Dashboard (mock-up).pdf','2014-08-16 19:34:11','akenny','',0),(000118,'uploads > Last Mile Survey','KBS Report (11-8-12).pdf','2014-08-16 19:34:32','akenny','',0),(000119,'uploads > Last Mile Survey','KBS Methodology (7-18-12).doc','2014-08-16 19:34:45','akenny','',0),(000120,'uploads > Last Mile Survey','KBS Questionnaire (final).pdf','2014-08-16 19:34:55','akenny','',0),(000121,'uploads > Last Mile Survey','KBS Questionnaire (final) (002).pdf','2014-08-16 19:34:59','akenny','',0),(000122,'uploads > Last Mile Survey','LMS Questionnaire (Liberian English).pdf','2014-08-16 19:35:12','akenny','',0),(000123,'uploads > Strategy','M&E Roadmap (May 2014).pdf','2014-08-16 19:35:30','akenny','',0),(000124,'uploads > Strategy','M&E Team - Goals and Priorities FY15 Q1-2 (rev. 8-1-14).doc','2014-08-16 19:35:42','akenny','',0),(000125,'uploads > Strategy','M&E Team - Priorities + Goals (FY14 Q4).docx','2014-08-16 19:35:49','akenny','',0),(000126,'uploads > Strategy','Last Mile Log Frames.xlsx','2014-08-16 19:35:59','akenny','',0),(000127,'uploads > Strategy','FY15 M&E Timeline (July update).xlsx','2014-08-16 19:36:03','akenny','',0),(000128,'uploads','Forms','2014-08-16 19:36:24','akenny','All current program forms',1),(000129,'uploads > Forms','FHW first aid form.docx','2014-08-16 19:36:36','akenny','',0),(000130,'uploads > Forms','FHW Form 1 - Registration.pdf','2014-08-16 19:36:41','akenny','',0),(000131,'uploads > Forms','FHW Form 2 - Health Assessment.pdf','2014-08-16 19:36:52','akenny','',0),(000132,'uploads > Forms','FHW Form 4 - Big Belly Tracking.pdf','2014-08-16 19:36:56','akenny','',0),(000133,'uploads > Forms','FHW Form 5 - Infant Assessment.pdf','2014-08-16 19:37:02','akenny','',0),(000134,'uploads > Forms','FHW Form 6 - Movements.pdf','2014-08-16 19:37:06','akenny','',0),(000135,'uploads > Forms','FHW Form 7 - Sick Child (Ebola).pdf','2014-08-16 19:37:10','akenny','',0),(000136,'uploads > Forms','FHW Form 7 - Sick Child.pdf','2014-08-16 19:37:14','akenny','',0),(000137,'uploads > Forms','FHW Form 9 - Family Planning.pdf','2014-08-16 19:37:19','akenny','',0),(000138,'uploads > Forms','FHW Form 11 - Well Adult.pdf','2014-08-16 19:37:23','akenny','',0),(000139,'uploads > Forms','FHW Form 12 - Well Child.pdf','2014-08-16 19:37:27','akenny','',0);
/*!40000 ALTER TABLE `uploads` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-08-20  3:59:36
