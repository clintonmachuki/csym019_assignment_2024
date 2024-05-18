-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: premier_league
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin1','$2y$10$h0kI5Alkxf2S7JIpXi9XiezV7cVud0Dr4h/0Mcfhsj4u29m0SSZI6'),(2,'admin2','$2y$10$PejODtfZR6bCBbxWIDwTm./iNlA5Fyg67VGQ0J3GqiCJKYgiCTF7q'),(3,'clinton','$2y$10$aCXBtq7ESbR6.NM2XcB7EeeCEkyqGnqPgmUK.pNq3/0XnxVAr5DSu'),(4,'chuxx','$2y$10$N3tmPWcH.5eqi37BbEQ6BOM02ANlDBK0dRJ5yQ0hW3H.24lzAmUb.'),(5,'nyamweya','$2y$10$55ZKY4IeYOoSvrS94D5h5eVk632Bi.yry/ha6J59zeWJNoGEPFs2i'),(6,'dan','$2y$10$dsUpxvFKxdsQY9.egi5pNeo4QBWqHAY/gMSwVLQMNR2GGd0TLh3PC');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fixtures`
--

DROP TABLE IF EXISTS `fixtures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fixtures` (
  `FixtureID` int(11) NOT NULL AUTO_INCREMENT,
  `HomeTeamID` int(11) DEFAULT NULL,
  `AwayTeamID` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Result` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`FixtureID`),
  KEY `HomeTeamID` (`HomeTeamID`),
  KEY `AwayTeamID` (`AwayTeamID`),
  CONSTRAINT `fixtures_ibfk_1` FOREIGN KEY (`HomeTeamID`) REFERENCES `teams` (`TeamID`),
  CONSTRAINT `fixtures_ibfk_2` FOREIGN KEY (`AwayTeamID`) REFERENCES `teams` (`TeamID`)
) ENGINE=InnoDB AUTO_INCREMENT=568 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fixtures`
--

LOCK TABLES `fixtures` WRITE;
/*!40000 ALTER TABLE `fixtures` DISABLE KEYS */;
INSERT INTO `fixtures` VALUES (418,11,18,'2024-01-20','5-0'),(419,30,11,'2024-01-30','1-2'),(420,11,21,'2024-02-04','3-1'),(421,28,11,'2024-02-11','0-6'),(422,16,11,'2024-02-17','0-5'),(423,11,25,'2024-02-24','4-1'),(424,26,11,'2024-03-04','0-6'),(425,11,14,'2024-03-09','2-1'),(426,11,22,'2024-04-03','2-0'),(427,15,11,'2024-04-06','0-3'),(428,29,11,'2024-04-20','0-2'),(429,11,17,'2024-04-23','5-0'),(430,27,11,'2024-04-28','2-3'),(431,26,12,'2024-02-03','0-5'),(432,20,12,'2024-02-17','1-2'),(433,12,30,'2024-02-24','4-2'),(434,22,12,'2024-03-02','2-3'),(435,12,29,'2024-03-30','2-0'),(436,11,12,'2024-04-14','0-2'),(437,12,13,'2024-04-21','3-1'),(438,16,13,'2024-03-03','0-2'),(439,13,22,'2024-03-13','4-3'),(440,13,19,'2024-03-30','2-1'),(441,13,18,'2024-04-02','1-0'),(442,29,13,'2024-04-24','0-1'),(443,13,15,'2024-04-28','3-0'),(444,14,30,'2024-01-20','3-2'),(445,29,14,'2024-02-10','0-2'),(446,14,26,'2024-04-13','2-0'),(447,22,14,'2024-04-20','1-5'),(448,15,18,'2024-02-03','4-1'),(449,26,15,'2024-02-18','0-5'),(450,15,30,'2024-03-10','1-0'),(451,16,14,'2024-03-16','2-1'),(452,26,16,'2024-04-20','1-4'),(453,17,20,'2024-01-13','1-0'),(454,18,17,'2024-02-12','1-3'),(455,17,25,'2024-03-11','3-2'),(456,17,24,'2024-04-04','4-3'),(457,17,19,'2024-04-15','6-0'),(458,17,27,'2024-05-02','2-0'),(459,18,26,'2024-01-30','3-2'),(460,18,16,'2024-02-24','3-0'),(461,21,18,'2024-04-14','0-1'),(462,18,28,'2024-04-21','5-2'),(463,18,25,'2024-04-24','2-0'),(464,19,16,'2024-04-06','1-0'),(465,19,30,'2024-04-21','2-0'),(466,19,21,'2024-04-24','2-0'),(467,19,14,'2024-04-27','1-0'),(468,20,13,'2024-02-10','3-1'),(469,24,20,'2024-02-24','1-2'),(470,20,15,'2024-03-02','3-0'),(471,20,27,'2024-03-16','3-0'),(472,28,20,'2024-04-14','0-2'),(473,21,25,'2024-01-01','4-2'),(474,13,21,'2024-01-21','0-4'),(475,21,17,'2024-01-31','4-1'),(476,21,16,'2024-02-10','3-1'),(477,14,21,'2024-02-17','1-4'),(478,21,22,'2024-02-21','4-1'),(479,30,21,'2024-03-02','0-1'),(480,21,15,'2024-03-31','2-1'),(481,21,26,'2024-04-04','3-1'),(482,20,21,'2024-04-21','1-3'),(483,22,15,'2024-01-30','4-0'),(484,22,13,'2024-04-06','2-1'),(485,25,23,'2024-01-13','2-3'),(486,23,16,'2024-01-31','3-1'),(487,14,23,'2024-02-05','1-3'),(488,23,19,'2024-02-10','2-0'),(489,23,14,'2024-02-20','1-0'),(490,13,23,'2024-02-24','0-1'),(491,23,24,'2024-03-03','3-1'),(492,23,12,'2024-04-03','4-1'),(493,18,23,'2024-04-06','2-4'),(494,23,22,'2024-04-13','5-1'),(495,15,23,'2024-04-25','0-4'),(496,30,23,'2024-04-28','0-2'),(497,29,24,'2024-02-01','3-4'),(498,24,28,'2024-02-04','3-0'),(499,12,24,'2024-02-11','1-2'),(500,22,24,'2024-02-18','1-2'),(501,24,19,'2024-03-09','2-0'),(502,24,26,'2024-04-24','4-2'),(503,12,25,'2024-01-30','1-3'),(504,30,25,'2024-02-10','2-3'),(505,25,29,'2024-03-02','3-0'),(506,25,28,'2024-03-30','4-3'),(507,20,25,'2024-04-06','0-1'),(508,25,27,'2024-04-13','4-0'),(509,25,26,'2024-04-27','5-1'),(510,30,28,'2024-02-17','2-0'),(511,30,20,'2024-04-02','3-1'),(512,22,26,'2024-02-10','1-3'),(513,27,14,'2024-01-31','3-2'),(514,27,15,'2024-02-10','2-1'),(515,27,18,'2024-03-02','3-1'),(516,12,27,'2024-03-10','0-4'),(517,27,22,'2024-03-30','2-1'),(518,27,30,'2024-04-07','3-1'),(519,28,14,'2024-02-26','4-2'),(520,19,28,'2024-03-02','1-3'),(521,29,28,'2024-04-06','1-2'),(522,17,29,'2024-02-04','2-4'),(523,27,29,'2024-02-17','1-2'),(524,29,26,'2024-02-25','1-0'),(525,29,20,'2024-03-09','2-1'),(526,29,22,'2024-04-27','2-1'),(527,16,22,'2024-01-12','1-1'),(528,28,15,'2024-01-02','0-0'),(529,19,12,'2024-01-14','0-0'),(530,24,27,'2024-01-14','2-2'),(531,26,28,'2024-01-21','2-2'),(532,15,29,'2024-01-22','0-0'),(533,20,19,'2024-01-30','0-0'),(535,28,13,'2024-02-01','1-1'),(536,16,20,'2024-02-03','2-2'),(537,25,22,'2024-02-03','4-4'),(538,19,27,'2024-02-03','2-2'),(539,13,30,'2024-02-04','1-1'),(541,25,14,'2024-02-17','2-2'),(542,23,17,'2024-02-17','1-1'),(543,19,18,'2024-02-19','1-1'),(544,18,16,'2024-02-24','3-0'),(545,14,17,'2024-03-02','2-2'),(546,18,22,'2024-03-09','1-1'),(547,13,26,'2024-03-09','2-2'),(548,28,16,'2024-03-10','2-2'),(549,21,23,'2024-03-10','1-1'),(550,22,30,'2024-03-16','1-1'),(551,28,12,'2024-03-17','1-1'),(552,26,20,'2024-03-30','3-3'),(553,30,18,'2024-03-30','1-1'),(554,17,16,'2024-03-30','2-2'),(555,14,24,'2024-03-30','1-1'),(556,23,11,'2024-03-31','0-0'),(557,25,19,'2024-04-02','1-1'),(558,16,29,'2024-04-02','1-1'),(559,28,27,'2024-04-02','1-1'),(560,14,15,'2024-04-03','0-0'),(561,12,14,'2024-04-06','3-3'),(562,26,17,'2024-04-07','2-2'),(563,24,21,'2024-04-07','2-2'),(564,16,15,'2024-04-13','1-1'),(566,30,29,'2024-04-13','2-2'),(567,24,11,'2024-05-12','');
/*!40000 ALTER TABLE `fixtures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goals`
--

DROP TABLE IF EXISTS `goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `goals` (
  `GoalID` int(11) NOT NULL AUTO_INCREMENT,
  `FixtureID` int(11) DEFAULT NULL,
  `PlayerID` int(11) DEFAULT NULL,
  `goals` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`GoalID`),
  KEY `FixtureID` (`FixtureID`),
  KEY `PlayerID` (`PlayerID`),
  CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`FixtureID`) REFERENCES `fixtures` (`FixtureID`),
  CONSTRAINT `goals_ibfk_2` FOREIGN KEY (`PlayerID`) REFERENCES `players` (`PlayerID`)
) ENGINE=InnoDB AUTO_INCREMENT=403 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goals`
--

LOCK TABLES `goals` WRITE;
/*!40000 ALTER TABLE `goals` DISABLE KEYS */;
INSERT INTO `goals` VALUES (5,418,85,1),(6,418,78,1),(7,418,81,1),(8,419,83,1),(9,419,76,1),(10,419,303,1),(11,420,76,1),(12,420,85,1),(13,420,81,1),(14,420,78,1),(15,421,86,1),(16,421,76,2),(17,421,85,1),(18,421,78,1),(19,421,80,1),(20,422,79,1),(21,422,76,2),(22,422,78,1),(23,422,77,1),(24,423,294,1),(25,423,77,1),(26,423,76,1),(27,423,87,1),(28,423,295,1),(29,424,79,1),(30,424,318,1),(31,424,81,1),(32,424,77,1),(33,424,80,1),(34,424,84,1),(35,425,80,1),(36,425,120,1),(37,425,77,1),(38,426,79,1),(39,427,76,1),(40,427,77,1),(41,427,78,1),(42,428,78,1),(43,428,79,1),(44,429,78,1),(45,429,84,2),(46,429,77,2),(47,430,76,1),(48,430,77,1),(49,430,327,1),(50,430,324,1),(51,431,94,1),(52,431,91,1),(53,431,92,1),(54,431,105,1),(55,431,98,1),(56,432,91,2),(57,432,205,1),(58,433,91,1),(59,433,93,2),(60,433,310,1),(61,433,305,1),(62,433,92,1),(63,434,91,2),(64,434,240,1),(65,434,237,1),(66,434,106,1),(67,435,95,1),(68,435,104,1),(69,436,92,1),(70,436,91,1),(71,437,107,1),(72,437,96,1),(73,437,95,1),(74,437,92,1),(75,438,109,1),(76,438,108,1),(77,439,240,1),(78,439,241,1),(79,439,239,1),(80,439,107,1),(81,439,117,1),(82,439,108,2),(83,440,107,1),(84,440,191,1),(85,441,109,1),(86,442,108,1),(87,443,110,1),(88,443,112,1),(89,443,109,1),(90,444,308,1),(91,444,123,1),(92,444,126,1),(93,444,302,1),(94,444,122,1),(95,445,129,1),(96,445,123,1),(97,446,131,1),(98,447,120,2),(99,447,127,1),(100,447,124,1),(101,447,130,1),(102,447,243,1),(103,448,144,1),(104,448,142,1),(105,448,143,1),(106,448,179,1),(107,448,137,1),(108,449,143,1),(109,449,141,1),(110,449,323,1),(111,449,139,2),(112,451,151,1),(113,451,152,1),(114,451,128,1),(115,452,151,1),(116,452,157,1),(117,452,317,1),(118,452,149,1),(119,452,159,1),(120,453,164,1),(121,454,187,1),(122,454,168,2),(123,454,171,1),(124,455,165,1),(125,455,283,1),(126,455,164,1),(127,455,169,1),(128,455,293,1),(129,456,168,1),(130,456,164,3),(131,456,271,2),(132,456,267,1),(133,457,164,4),(134,457,165,1),(135,457,174,1),(136,458,175,1),(137,458,165,1),(138,459,315,1),(139,459,180,2),(140,459,319,1),(141,459,181,1),(142,460,186,1),(143,460,183,1),(144,460,179,1),(145,461,180,1),(146,462,181,1),(147,462,180,1),(148,462,352,1),(149,462,179,2),(150,462,344,1),(151,463,179,2),(152,464,189,1),(153,465,197,1),(154,465,192,1),(155,466,194,1),(156,466,189,1),(157,467,198,1),(158,468,206,1),(159,468,205,2),(160,468,110,1),(161,469,217,1),(162,469,273,1),(163,469,207,1),(164,470,211,1),(165,470,205,1),(166,470,216,1),(167,471,205,2),(168,471,218,1),(169,472,212,2),(170,473,222,2),(171,473,283,1),(172,473,236,1),(173,473,226,1),(174,473,294,1),(175,474,223,2),(176,474,224,2),(177,475,224,1),(178,475,234,1),(179,475,228,1),(180,475,172,1),(181,475,225,1),(182,476,224,1),(183,476,156,1),(184,476,225,1),(185,476,223,1),(186,477,223,1),(187,477,227,1),(188,477,222,1),(189,477,123,1),(190,477,226,1),(191,478,241,1),(192,478,231,1),(193,478,226,1),(194,478,225,1),(195,478,232,1),(196,479,223,1),(197,480,141,1),(198,480,225,1),(199,480,222,1),(200,481,223,1),(201,481,234,1),(202,481,227,1),(203,481,226,1),(204,482,229,1),(205,482,215,1),(206,482,233,1),(207,482,224,1),(208,483,238,3),(209,483,241,1),(210,484,111,1),(211,484,247,1),(212,484,237,1),(213,485,256,1),(214,485,283,1),(215,485,284,1),(216,485,257,1),(217,485,264,1),(218,486,254,2),(219,486,255,1),(220,486,161,1),(221,487,122,1),(222,487,253,3),(223,488,252,2),(224,489,252,1),(225,490,253,1),(226,491,270,1),(227,491,253,2),(228,491,252,1),(229,492,255,1),(230,492,97,1),(231,492,253,3),(232,493,179,1),(233,493,257,2),(234,493,262,1),(235,493,252,1),(236,493,182,1),(237,494,266,1),(238,494,252,1),(239,494,239,1),(240,494,258,1),(241,494,261,1),(242,495,257,1),(243,495,253,2),(244,495,254,1),(245,496,261,1),(246,496,252,1),(247,497,270,1),(248,497,268,1),(249,497,355,1),(250,497,269,1),(251,497,358,1),(252,497,361,1),(253,497,272,1),(254,498,268,1),(255,498,271,2),(256,499,268,1),(257,499,93,1),(258,499,269,1),(259,500,268,2),(260,500,237,1),(261,501,267,1),(262,501,270,1),(263,502,318,1),(264,502,273,1),(265,502,315,1),(266,502,267,2),(267,502,268,1),(268,503,287,2),(269,503,98,1),(270,503,91,1),(271,504,286,2),(272,504,306,1),(273,504,287,1),(274,504,304,1),(275,505,283,1),(276,505,284,1),(277,505,299,1),(278,506,283,2),(279,506,344,1),(280,506,341,1),(281,506,340,1),(282,506,289,2),(283,507,286,1),(284,508,283,2),(285,508,284,1),(286,508,287,1),(287,509,320,1),(288,509,283,2),(289,509,286,1),(290,509,285,1),(291,510,303,1),(292,510,304,1),(293,511,304,1),(294,511,302,1),(295,511,305,1),(296,511,213,1),(297,512,316,1),(298,512,319,1),(299,512,237,1),(300,512,322,1),(301,513,122,1),(302,513,333,1),(303,513,328,1),(304,513,325,1),(305,513,123,1),(306,514,140,1),(307,514,330,1),(308,514,328,1),(309,515,180,1),(310,515,331,1),(311,515,327,1),(312,515,324,1),(313,516,329,1),(314,516,328,1),(315,516,324,1),(316,516,331,1),(317,517,240,1),(318,517,324,1),(319,518,302,1),(320,518,334,1),(321,518,337,1),(322,519,340,3),(323,519,122,1),(324,519,352,1),(325,519,120,1),(326,520,191,1),(327,520,346,1),(328,520,343,1),(329,520,348,1),(330,521,355,1),(331,521,345,1),(332,521,342,1),(333,522,164,1),(334,522,353,3),(335,522,173,1),(336,522,170,1),(337,523,360,2),(338,523,326,1),(339,524,355,1),(340,525,362,1),(341,525,220,1),(342,525,207,1),(343,526,354,1),(344,526,364,1),(345,526,237,1),(346,527,150,1),(347,527,237,1),(348,530,268,1),(349,530,270,1),(350,530,325,1),(351,530,335,1),(352,531,315,1),(353,531,314,1),(354,531,350,1),(355,531,342,1),(356,535,342,1),(357,535,107,1),(358,536,152,2),(359,536,209,1),(360,536,205,1),(361,537,288,2),(362,537,300,1),(363,537,292,1),(364,537,242,1),(365,537,239,1),(366,537,237,1),(367,537,238,1),(368,538,193,1),(369,538,194,1),(370,538,325,2),(371,542,255,1),(372,542,166,1),(373,543,196,1),(374,543,183,1),(375,544,183,1),(376,544,179,1),(377,544,186,1),(378,545,120,1),(379,545,136,1),(380,545,165,1),(381,545,173,1),(382,555,280,1),(383,555,128,1),(384,553,302,1),(385,553,179,1),(386,557,189,1),(387,557,283,1),(388,558,151,1),(389,558,362,1),(390,559,328,1),(391,559,346,1),(392,561,91,2),(393,561,96,1),(394,561,121,1),(395,561,120,1),(396,563,267,1),(397,563,272,1),(398,554,164,2),(399,554,160,1),(400,554,156,1),(401,551,99,1),(402,551,344,1);
/*!40000 ALTER TABLE `goals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `players` (
  `PlayerID` int(11) NOT NULL AUTO_INCREMENT,
  `PlayerName` varchar(100) DEFAULT NULL,
  `TeamID` int(11) DEFAULT NULL,
  PRIMARY KEY (`PlayerID`),
  KEY `TeamID` (`TeamID`),
  CONSTRAINT `players_ibfk_1` FOREIGN KEY (`TeamID`) REFERENCES `teams` (`TeamID`)
) ENGINE=InnoDB AUTO_INCREMENT=366 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
INSERT INTO `players` VALUES (76,'Bukayo Saka',11),(77,'Kai Havertz',11),(78,'Leandro Trossard',11),(79,'Martin Ødegaard',11),(80,'Declan Rice',11),(81,'Gabriel Martinelli',11),(82,'Edward Nketiah',11),(83,'Gabriel Jesus',11),(84,'Benjamin White',11),(85,'Gabriel Magalhães',11),(86,'William Saliba',11),(87,'Jakub Kiwior',11),(88,'Fábio Vieira',11),(89,'Oleksandr Zinchenko',11),(90,'Takehiro Tomiyasu',11),(91,'Ollie Watkins',12),(92,'Leon Bailey',12),(93,'Douglas Luiz',12),(94,'John McGinn',12),(95,'Moussa Diaby',12),(96,'Morgan Rogers',12),(97,'Jhon Durán',12),(98,'Álex Moreno',12),(99,'Nicolò Zaniolo',12),(100,'Pau Torres',12),(101,'Matty Cash',12),(102,'Leander Dendoncker',12),(103,'Jacob Ramsey',12),(104,'Ezri Konsa',12),(105,'Youri Tielemans',12),(106,'Lucas Digne',12),(107,'Dominic Solanke',13),(108,'Antoine Semenyo',13),(109,'Justin Kluivert',13),(110,'Marcos Senesi',13),(111,'Marcus Tavernier',13),(112,'Enes Ünal',13),(113,'Luis Sinisterra',13),(114,'Philip Billing',13),(115,'Dango Ouattara',13),(116,'Alex Scott',13),(117,'Illia Zabarnyi',13),(118,'David Brooks',13),(119,'Kieffer Moore',13),(120,'Yoane Wissa',14),(121,'Bryan Mbeumo',14),(122,'Neal Maupay',14),(123,'Ivan Toney',14),(124,'Keane LewisPotter',14),(125,'Mathias Jensen',14),(126,'Ben Mee',14),(127,'Ethan Pinnock',14),(128,'Kristoffer Ajer',14),(129,'Christian Nörgaard',14),(130,'Kevin Schade',14),(131,'Frank Onyeka',14),(132,'Saman Ghoddos',14),(133,'Nathan Collins',14),(134,'Zanka',14),(135,'Shandon Baptiste',14),(136,'Mads Roerslev',14),(137,'João Pedro',15),(138,'Evan Ferguson',15),(139,'Simon Adingra',15),(140,'Pascal Groß',15),(141,'Danny Welbeck',15),(142,'Jack Hinshelwood',15),(143,'Facundo Buonanotte',15),(144,'Lewis Dunk',15),(145,'Kaoru Mitoma',15),(146,'Solly March',15),(147,'Ansu Fati',15),(148,'Pervis Estupiñán',15),(149,'Lyle Foster',16),(150,'Zeki Amdouni',16),(151,'Jacob Bruun Larsen',16),(152,'David Datro Fofana',16),(153,'Josh Brownhill',16),(154,'Wilson Odobert',16),(155,'Jay Rodríguez',16),(156,'Dara O`Shea',16),(157,'Lorenz Assignon',16),(158,'Charlie Taylor',16),(159,'J. Gudmundsson',16),(160,'Josh Cullen',16),(161,'Ameen AlDakhil',16),(162,'Sander Berge',16),(163,'Luca Koleosho',16),(164,'Cole Palmer',17),(165,'Nicolas Jackson',17),(166,'Raheem Sterling',17),(167,'Noni Madueke',17),(168,'Conor Gallagher',17),(169,'Mykhaylo Mudryk',17),(170,'Thiago Silva',17),(171,'Enzo Fernández',17),(172,'Christopher Nkunku',17),(173,'Axel Disasi',17),(174,'Alfie Gilchrist',17),(175,'Trevoh Chalobah',17),(176,'Armando Broja',17),(177,'Levi Colwill',17),(178,'Carney Chukwuemeka',17),(179,'JeanPhilippe Mateta',18),(180,'Eberechi Eze',18),(181,'Michael Olise',18),(182,'Odsonne Édouard',18),(183,'Jordan Ayew',18),(184,'Jeffrey Schlupp',18),(185,'Joachim Andersen',18),(186,'Chris Richards',18),(187,'Jefferson Lerma',18),(188,'Tyrick Mitchell',18),(189,'D. CalvertLewin',19),(190,'Abdoulaye Doucouré',19),(191,'Beto',19),(192,'Dwight McNeil',19),(193,'Jack Harrison',19),(194,'Jarrad Branthwaite',19),(195,'Vitaliy Mykolenko',19),(196,'Amadou Onana',19),(197,'Idrissa Gueye',19),(198,'Idrissa Guèye',19),(199,'André Gomes',19),(200,'Michael Keane',19),(201,'James Tarkowski',19),(202,'Lewis Dobbin',19),(203,'James Garner',19),(204,'Arnaut Danjuma',19),(205,'Rodrigo Muniz',20),(206,'Bobby Reid',20),(207,'Alex Iwobi',20),(208,'Raúl Jiménez',20),(209,'João Palhinha',20),(210,'Willian',20),(211,'Harry Wilson',20),(212,'Andreas Pereira',20),(213,'Tosin Adarabioyo',20),(214,'Carlos Vinícius',20),(215,'Timothy Castagne',20),(216,'Adama Traoré',20),(217,'Calvin Bassey',20),(218,'Saa Lukic',20),(219,'Tim Ream',20),(220,'Tom Cairney',20),(221,'Kenny Tete',20),(222,'Mohamed Salah',21),(223,'Darwin Núñez',21),(224,'Diogo Jota',21),(225,'Luis Díaz',21),(226,'Cody Gakpo',21),(227,'Alexis Mac Allister',21),(228,'Dominik Szoboszlai',21),(229,'T. AlexanderArnold',21),(230,'Andrew Robertson',21),(231,'Virgil van Dijk',21),(232,'Harvey Elliott',21),(233,'Ryan Gravenberch',21),(234,'Conor Bradley',21),(235,'Wataru Endo',21),(236,'Curtis Jones',21),(237,'Carlton Morris',22),(238,'Elijah Adebayo',22),(239,'Ross Barkley',22),(240,'Tahith Chong',22),(241,'Chiedozie Ogbene',22),(242,'Gabriel Osho',22),(243,'Luke Berry',22),(244,'Jacob Brown',22),(245,'Andros Townsend',22),(246,'Teden Mengi',22),(247,'Jordan Clark',22),(248,'Tom Lockyer',22),(249,'Cauley Woodrow',22),(250,'Alfie Doughty',22),(251,'Mads Juel Andersen',22),(252,'Erling Haaland',23),(253,'Phil Foden',23),(254,'Julián Álvarez',23),(255,'Rodri',23),(256,'Bernardo Silva',23),(257,'Kevin De Bruyne',23),(258,'Jeremy Doku',23),(259,'Jack Grealish',23),(260,'Manuel Akanji',23),(261,'Joko Gvardiol',23),(262,'Rico Lewis',23),(263,'Nathan Aké',23),(264,'Oscar Bobb',23),(265,'John Stones',23),(266,'Mateo Kovacic',23),(267,'Bruno Fernandes',24),(268,'Rasmus Højlund',24),(269,'Scott McTominay',24),(270,'Marcus Rashford',24),(271,'Alejandro Garnacho',24),(272,'Kobbie Mainoo',24),(273,'Harry Maguire',24),(274,'Hannibal Mejbri',24),(275,'Anthony Martial',24),(276,'Diogo Dalot',24),(277,'Casemiro',24),(278,'Antony',24),(279,'Christian Eriksen',24),(280,'Mason Mount',24),(281,'Victor Lindelöf',24),(282,'Raphaël Varane',24),(283,'Alexander Isak',25),(284,'Anthony Gordon',25),(285,'Callum Wilson',25),(286,'Bruno Guimarães',25),(287,'Fabian Schär',25),(288,'Sean Longstaff',25),(289,'Harvey Barnes',25),(290,'Miguel Almirón',25),(291,'Joelinton',25),(292,'Dan Burn',25),(293,'Jacob Murphy',25),(294,'Sven Botman',25),(295,'Joseph Willock',25),(296,'Lewis Miley',25),(297,'Matt Ritchie',25),(298,'Jamaal Lascelles',25),(299,'Valentino Livramento',25),(300,'Kieran Trippier',25),(301,'Sandro Tonali',25),(302,'Chris Wood',30),(303,'Taiwo Awoniyi',30),(304,'CallumHudsonOdoi',30),(305,'Morgan GibbsWhite',30),(306,'Anthony Elanga',30),(307,'Nicolás Domínguez',30),(308,'Danilo',30),(309,'Harry Toffolo',30),(310,'Moussa Niakhaté',30),(311,'Willy Boly',30),(312,'Orel Mangala',30),(313,'Ola Aina',30),(314,'Oliver McBurnie',26),(315,'Ben Brereton Díaz',26),(316,'Cameron Archer',26),(317,'Gustavo Hamer',26),(318,'Jayden Bogle',26),(319,'James McAtee',26),(320,'Anel Ahmedhodic',26),(321,'Oliver Norwood',26),(322,'Vinícius Souza',26),(323,'Jack Robinson',26),(324,'Heungmin Son',27),(325,'Richarlison',27),(326,'Dejan Kulusevski',27),(327,'Cristian Romero',27),(328,'Brennan Johnson',27),(329,'James Maddison',27),(330,'Pape Matar Sarr',27),(331,'Timo Werner',27),(332,'Giovani Lo Celso',27),(333,'Destiny Udogie',27),(334,'Micky van de Ven',27),(335,'Rodrigo Bentancur',27),(336,'Alejo Véliz',27),(337,'Pedro Porro',27),(338,'Ben Davies',27),(339,'Emerson Royal',27),(340,'Jarrod Bowen',28),(341,'Mohammed Kudus',28),(342,'James WardProwse',28),(343,'Tomá Soucek',28),(344,'Michail Antonio',28),(345,'Lucas Paquetá',28),(346,'Kurt Zouma',28),(347,'K. Mavropanos',28),(348,'Edson Álvarez',28),(349,'Nayef Aguerd',28),(350,'Maxwel Cornet',28),(351,'Danny Ings',28),(352,'Emerson Palmieri',28),(353,'Matheus Cunha',29),(354,'Hwang HeeChan',29),(355,'Pablo Sarabia',29),(356,'M. Lemina',29),(357,'J. Bellegarde',29),(358,'Max Kilman',29),(359,'S. Kalajdzic',29),(360,'João Gomes',29),(361,'Pedro Neto',29),(362,'R. AïtNouri',29),(363,'Matt Doherty',29),(364,'Toti',29),(365,'Craig Dawson',29);
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `TeamID` int(11) NOT NULL AUTO_INCREMENT,
  `TeamName` varchar(100) DEFAULT NULL,
  `TeamLogo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`TeamID`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (11,'Arsenal',''),(12,'Aston Villa',''),(13,'Bournemouth',''),(14,'Brentford',''),(15,'Brighton',''),(16,'Burnley',''),(17,'Chelsea',''),(18,'Crystal Palace',''),(19,'Everton',''),(20,'Fulham',''),(21,'Liverpool',''),(22,'Luton',''),(23,'Man City',''),(24,'Man United',''),(25,'Newcastle',''),(26,'Sheffield United',''),(27,'Tottenham',''),(28,'West Ham',''),(29,'Wolves',''),(30,'Nottingham forest','');
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-18 15:55:09
