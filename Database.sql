-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: friendpixapp
-- ------------------------------------------------------
-- Server version	8.0.33

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accessibility`
--

DROP TABLE IF EXISTS `accessibility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accessibility` (
  `Accessibility_Code` varchar(16) NOT NULL,
  `Description` varchar(128) NOT NULL,
  PRIMARY KEY (`Accessibility_Code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accessibility`
--

LOCK TABLES `accessibility` WRITE;
/*!40000 ALTER TABLE `accessibility` DISABLE KEYS */;
INSERT INTO `accessibility` VALUES ('Private','Accessible Only By User'),('Public','Accessible By Owner and Friends');
/*!40000 ALTER TABLE `accessibility` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `album`
--

DROP TABLE IF EXISTS `album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `album` (
  `Album_Id` int NOT NULL AUTO_INCREMENT,
  `Title` varchar(256) NOT NULL,
  `Description` varchar(3000) DEFAULT NULL,
  `Owner_Id` varchar(16) NOT NULL,
  `Accessibility_Code` varchar(16) NOT NULL,
  PRIMARY KEY (`Album_Id`),
  KEY `Owner` (`Owner_Id`),
  KEY `Accessibility` (`Accessibility_Code`),
  CONSTRAINT `Album_Accessibility_FK` FOREIGN KEY (`Accessibility_Code`) REFERENCES `accessibility` (`Accessibility_Code`),
  CONSTRAINT `Album_User_FK` FOREIGN KEY (`Owner_Id`) REFERENCES `user` (`UserId`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album`
--

LOCK TABLES `album` WRITE;
/*!40000 ALTER TABLE `album` DISABLE KEYS */;
INSERT INTO `album` VALUES (26,'AlbumOne','First','Iden-2','Public'),(27,'AlbumTwo','Twos','Iden-2','Public'),(28,'Hi','asd','Iden-3','Public'),(29,'AlbumThree','3r','Iden-2','Private');
/*!40000 ALTER TABLE `album` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comment` (
  `Comment_Id` int NOT NULL AUTO_INCREMENT,
  `Author_Id` varchar(16) NOT NULL,
  `Picture_Id` int NOT NULL,
  `Comment_Text` varchar(3000) NOT NULL,
  PRIMARY KEY (`Comment_Id`),
  KEY `Author_Index` (`Author_Id`),
  KEY `Comment_Picture_Index` (`Picture_Id`),
  CONSTRAINT `Comment_Picture_FK` FOREIGN KEY (`Picture_Id`) REFERENCES `picture` (`Picture_Id`),
  CONSTRAINT `Comment_User_FK` FOREIGN KEY (`Author_Id`) REFERENCES `user` (`UserId`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (85,'Iden-3',166,'hi'),(86,'Iden-3',166,'23edwddd'),(87,'Iden-2',169,'dredd'),(88,'Iden-3',167,'Hi9383uyhdjddjd');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `friendship`
--

DROP TABLE IF EXISTS `friendship`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `friendship` (
  `Friend_RequesterId` varchar(16) NOT NULL,
  `Friend_RequesteeId` varchar(16) NOT NULL,
  `Status` varchar(16) NOT NULL,
  PRIMARY KEY (`Friend_RequesterId`,`Friend_RequesteeId`),
  KEY `FriendShip_Student_FK2` (`Friend_RequesteeId`),
  KEY `Status` (`Status`),
  CONSTRAINT `Friendship_Status_FK` FOREIGN KEY (`Status`) REFERENCES `friendshipstatus` (`Status_Code`),
  CONSTRAINT `FriendShip_User_FK1` FOREIGN KEY (`Friend_RequesterId`) REFERENCES `user` (`UserId`),
  CONSTRAINT `FriendShip_User_FK2` FOREIGN KEY (`Friend_RequesteeId`) REFERENCES `user` (`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friendship`
--

LOCK TABLES `friendship` WRITE;
/*!40000 ALTER TABLE `friendship` DISABLE KEYS */;
INSERT INTO `friendship` VALUES ('Iden-2','Iden-1','accepted'),('Iden-2','Iden-3','accepted'),('Iden-3','Iden-1','accepted');
/*!40000 ALTER TABLE `friendship` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `friendshipstatus`
--

DROP TABLE IF EXISTS `friendshipstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `friendshipstatus` (
  `Status_Code` varchar(16) NOT NULL,
  `Description` varchar(120) NOT NULL,
  PRIMARY KEY (`Status_Code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `friendshipstatus`
--

LOCK TABLES `friendshipstatus` WRITE;
/*!40000 ALTER TABLE `friendshipstatus` DISABLE KEYS */;
INSERT INTO `friendshipstatus` VALUES ('accepted','The request to become a friend has been accepted'),('request','A request has been sent to become a friend');
/*!40000 ALTER TABLE `friendshipstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `picture`
--

DROP TABLE IF EXISTS `picture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `picture` (
  `Picture_Id` int NOT NULL AUTO_INCREMENT,
  `Album_Id` int NOT NULL,
  `File_Name` varchar(256) NOT NULL,
  `Title` varchar(256) NOT NULL,
  `Description` varchar(3000) DEFAULT NULL,
  PRIMARY KEY (`Picture_Id`),
  KEY `Album_Id_Index` (`Album_Id`),
  CONSTRAINT `Picture_Album_FK` FOREIGN KEY (`Album_Id`) REFERENCES `album` (`Album_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `picture`
--

LOCK TABLES `picture` WRITE;
/*!40000 ALTER TABLE `picture` DISABLE KEYS */;
INSERT INTO `picture` VALUES (166,26,'wallpaperflare.com_wallpaper.jpg','hi','123'),(167,26,'Frame 1 (4).png','2344','23333444'),(168,27,'Screenshot_2024-06-21_171353-transformed (1).png','ntg','sdd'),(169,28,'wallpaperflare.com_wallpaper.jpg','123','2wss');
/*!40000 ALTER TABLE `picture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `UserId` varchar(16) NOT NULL,
  `Name` varchar(256) NOT NULL,
  `Phone` varchar(16) NOT NULL,
  `Password` varchar(256) NOT NULL,
  PRIMARY KEY (`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('Elnaz 35','Elna','613-869-4254','$2y$10$IT//K93ngfPOqzba7YklDuYq561Olz9JVtBCQvOV4Wkz36NiXeUuK'),('Iden-1','IdenOne','613-869-4254','$2y$10$3OxYIQ2iKYJQlrC9ueJj3uklpNmMJachkCVDxWKP.yBUdQbtF7hCm'),('Iden-2','Iden','613-869-4254','$2y$10$b77hbtidgRuwy3wqWb4f5Oi9nUwFqXb3pVuxRACom1UKLkttwttk2'),('Iden-3','Iden223','613-869-4254','$2y$10$.PT2nPxKmkPeIzUsah4fu.qP65BfCIQvMAGJWYH4RfBBxXYlYcVeK');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-22 14:08:02
