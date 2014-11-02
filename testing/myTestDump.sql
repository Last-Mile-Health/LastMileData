-- MySQL dump 10.13  Distrib 5.6.14, for Win32 (x86)
--
-- Host: localhost    Database: lastmiledata
-- ------------------------------------------------------
-- Server version	5.6.14

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
INSERT INTO `0_testde` VALUES (000007,'111','222',1,0,0,'333','2014-08-01 20:39:24'),(000008,'9','9',1,0,0,'9','2014-08-01 22:43:48'),(000009,'1','2',1,0,1,'3','2014-08-05 22:36:56');
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_utility_logins`
--

LOCK TABLES `tbl_utility_logins` WRITE;
/*!40000 ALTER TABLE `tbl_utility_logins` DISABLE KEYS */;
INSERT INTO `tbl_utility_logins` VALUES (000003,'akenny','2014-08-05 13:53:04'),(000004,'akenny','2014-08-05 13:54:41'),(000005,'akenny','2014-08-05 14:02:36'),(000006,'akenny','2014-08-05 14:21:08'),(000007,'akenny','2014-08-05 17:58:47'),(000008,'akenny','2014-08-06 15:35:35'),(000009,'akenny','2014-08-06 22:37:45'),(000010,'akenny','2014-08-06 22:37:46'),(000011,'akenny','2014-08-07 08:51:00'),(000012,'akenny','2014-08-07 08:51:22'),(000013,'akenny','2014-08-07 11:33:13'),(000014,'akenny','2014-08-07 12:27:29'),(000015,'akenny','2014-08-07 19:26:24'),(000016,'akenny','2014-08-09 11:59:50'),(000017,'akenny','2014-08-11 12:36:32'),(000018,'akenny','2014-08-11 13:38:01'),(000019,'akenny','2014-08-11 17:53:11'),(000020,'akenny','2014-08-12 14:31:48'),(000021,'akenny','2014-08-13 09:43:55'),(000022,'akenny','2014-08-13 15:55:49'),(000023,'akenny','2014-08-14 09:50:49'),(000024,'akenny','2014-08-14 14:52:43'),(000025,'akenny','2014-08-14 17:42:41'),(000026,'akenny','2014-08-15 09:28:27'),(000027,'akenny','2014-08-15 17:30:40'),(000028,'akenny','2014-08-16 16:16:48'),(000029,'akenny','2014-08-19 07:28:01');
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_utility_users`
--

LOCK TABLES `tbl_utility_users` WRITE;
/*!40000 ALTER TABLE `tbl_utility_users` DISABLE KEYS */;
INSERT INTO `tbl_utility_users` VALUES (000001,'akenny','ec550a8708b22601bdab1326bcbeac9b9a915b0c','admin'),(000002,'msiedner','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000003,'jkraemer','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000004,'gbasu','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000005,'oamir','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000006,'asechler','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000007,'blorenzen','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000008,'cocitti','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000009,'jrabinowich','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000010,'jly','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000011,'jalbert','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000012,'lmccormick','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000013,'mballard','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000014,'rpanjabi','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000015,'sparish','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000016,'tgriffiths','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000017,'tslagle','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000018,'ukarmue','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000019,'zkanjee','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000020,'kerlandson','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000021,'astergakis','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000022,'ajohnson','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000023,'knaylor','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000024,'smukherjee','aabde2d468faec8395fa24ac27e6802e5d6e6297','user'),(000025,'bvandebogert','aabde2d468faec8395fa24ac27e6802e5d6e6297','user');
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
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test`
--

LOCK TABLES `test` WRITE;
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
INSERT INTO `test` VALUES (000085,'C:/Users/Avi/Desktop/Avi/xampp/htdocs/LastMileData/uploads/uploads/test',NULL,NULL),(000086,'C:/Users/Avi/Desktop/Avi/xampp/htdocs/LastMileData/uploads/uploads/test/sub-folder test/sub-sub folder test',NULL,NULL),(000087,'C:/Users/Avi/Desktop/Avi/xampp/htdocs/LastMileData/uploads/test',NULL,NULL),(000088,'C:/Users/Avi/Desktop/Avi/xampp/htdocs/LastMileData/uploads/test/',NULL,NULL),(000089,'uploads/test',NULL,NULL),(000090,'C:/Users/Avi/Desktop/Avi/xampp/htdocs/LastMileData/uploads/test',NULL,NULL),(000091,'hey','you',NULL),(000092,'DELETE FROM uploads WHERE folder LIKE \'uploads > one%\'','DELETE FROM uploads WHERE folder=\'uploads\' AND filename=\'one\'',NULL),(000093,'hey',NULL,NULL),(000094,'INSERT INTO undefined SET timestamp=\'2014-08-19 11:34:09\', type=\'form\', var1=\'1\', var2=\'2\', var3=\'1\', var4=\'0\', var5=\'0\', var6=\'3\';',NULL,NULL),(000095,'INSERT INTO undefined SET timestamp=\'2014-08-19 11:36:38\', type=\'form\', var1=\'7\', var2=\'8\', var3=\'1\', var4=\'0\', var5=\'0\', var6=\'9\';',NULL,NULL),(000096,'INSERT INTO 0_testde SET timestamp=\'2014-08-19 11:39:03\', type=\'form\', var1=\'4\', var2=\'5\', var3=\'1\', var4=\'0\', var5=\'0\', var6=\'6\';',NULL,NULL),(000097,'INSERT INTO 0_testde SET timestamp=\'2014-08-19 11:39:03\', type=\'form\', var1=\'4\', var2=\'5\', var3=\'1\', var4=\'0\', var5=\'0\', var6=\'6\';',NULL,NULL),(000098,'INSERT INTO lastmiledata.0_testde SET timestamp=\'2014-08-19 11:53:59\', type=\'form\', var1=\'hey\', var2=\'there\', var3=\'1\', var4=\'0\', var5=\'0\', var6=\'you\';',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uploads`
--

LOCK TABLES `uploads` WRITE;
/*!40000 ALTER TABLE `uploads` DISABLE KEYS */;
INSERT INTO `uploads` VALUES (000064,'uploads','two','2014-08-16 19:02:33','akenny','',1),(000068,'uploads > two','sub','2014-08-16 19:03:04','akenny','',1),(000069,'uploads > two','(2014-07-31) Lisha, Pershing Square (2).xlsx','2014-08-16 19:03:10','akenny','',0),(000070,'uploads > two > sub','(from LISGIS) Census Data.xlsx','2014-08-16 19:03:18','akenny','',0);
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

-- Dump completed on 2014-08-19 22:41:07
