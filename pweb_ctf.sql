-- Progettazione Web
DROP DATABASE if exists pweb_ctf;
CREATE DATABASE pweb_ctf;
USE pweb_ctf;
-- MySQL dump 10.13  Distrib 5.7.26, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: pweb_ctf
-- ------------------------------------------------------
-- Server version	8.0.15

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
-- Table structure for table `authTokens`
--

DROP TABLE IF EXISTS `authTokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authTokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `authToken` varchar(255) NOT NULL,
  `expireTime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  CONSTRAINT `authTokens_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authTokens`
--

LOCK TABLES `authTokens` WRITE;
/*!40000 ALTER TABLE `authTokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `authTokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `challenges`
--

DROP TABLE IF EXISTS `challenges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `challenges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `categoryName` varchar(32) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `points` int(10) unsigned NOT NULL DEFAULT '1',
  `body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `challenges`
--

LOCK TABLES `challenges` WRITE;
/*!40000 ALTER TABLE `challenges` DISABLE KEYS */;
INSERT INTO `challenges` VALUES (1,'REV #01','REVERSING','flag{REV#01}',50,'This is a sample challenge.<br>\nYou can add here the challenge text.<br>\nYou can add <a href=\"https://google.com\">Links</a> and <em>format</em> <strong>your</strong> <span class=\"color-red\">text</span> using HTML!<br><br>\n\nSolve this challenge if you can!<br><br>\n\n\nThis is just an example! The correct flag is: <strong>flag{REV#01}</strong>'),(2,'REV #02','REVERSING','flag{REV#02}',150,'This is a sample challenge!<br>\nHere goes the challenge text. You should write the the task that the user <strong>needs</strong> to do to find out the correct flag and solve this challenge!<br><br>\n\n<span class=\"color-blue\">This text is blue!</span><br><br>\n\nThis is just an example. The correct flag is: <strong>flag{REV#02}</strong>'),(3,'REV #03','REVERSING','flag{REV#03}',200,'Please solve me!<br><br>\n\nThis is just an example. The correct flag is: <strong>flag{REV#03}</strong>'),(4,'WEB #01','WEB HACKING','flag{WEB#01}',300,'This is a sample challenge but... You still need to solve this!<br><br>\n\nThe flag is: <span style=\"display:none;\">flag{WEB#01}</span>'),(5,'WEB #02','WEB HACKING','flag{WEB#02}',100,'I\'m a sample challenge but... solve me if you can!<br><br>\n\nP.S.: do you now that you can also add HTML comments in the challenge\'s body?\n<!-- The flag is: flag{WEB#02} -->'),(6,'SQL #01','SQL INJECTION','flag{\' OR \'1\'=\'1}',50,'<code>\n&lt;?php<br>\n$id = 1;<br>\n$password = get_password_from_user();<br>\n$statement = \"SELECT * FROM users WHERE id = \" . $id . \" AND password = \'\" . $password . \"\';\";<br>\nlogin($db-&gt;query($statement));\n</code><br>\n<hr><br>\nThe flag is: <strong>flag{xxx}</strong><br>\nWhere <em>xxx</em> is the value of <code>$password</code> that make you log in with user with id=1.'),(7,'Yuor first BOF','HACKING','flag{BOF#01}',500,'The following is running on 203.0.113.55 and listening on port 4343.<br>\n<hr><br>\n<code>\n#include &lt;stdlib.h&gt;<br>\n#include &lt;stdio.h&gt;<br>\n<br>\nint main(int argc, char *argv[]) {<br>\n    int a = 1;<br>\n    char buffer[256];<br>\n    printf(\"buffer address: %p\\n\", buffer);<br>\n    char* command = \"cat flag.txt\";<br>\n    fflush(stdout);<br>\n    gets(buffer);<br>\n    system(\"echo \'7h15 b1n4ry 15 unpwn4bl3!!1!\'\");<br>\n}\n</code><br>\n<hr><br>\nReally... there\'s noting running on that server (the IP address does not exists and you should now it! It is part of the TEST-NET address range :P).<br>\nThe correct flag is: <strong>flag{BOF#01}</strong>'),(8,'CRYPTO #01','CRYPTO','flag{CRYPTO#01}',50,'The correct flag is: <strong>mshn{JYFWAV#01}</strong>'),(9,'Base64','CRYPTO','flag{ThisFlagIsHardToFind}',175,'<code>VGhlIGNvcnJlY3QgZmxhZyBpczogZmxhZ3tUaGlzRmxhZ0lzSGFyZFRvRmluZH0=</code><br><br>\nEasy. Isn\'t it?!'),(10,'WHAT\'S WRONG?!?','CODING','flag{for (int i = 0; i < 5; i++)}',300,'<code>\nint main() {<br>\nint numbers[5] = [5, 3, 1, 6, 3];<br>\nint sum = 0;<br>\nfor (int i = 0; i <= 5; i++)<br>\nsum += numbers[i];<br>\n}\n</code><br>\n<hr><br>\nThe flag is: <strong>flag{xxx}</strong><br>\nWhere <em>xxx</em> is the correct line of code.'),(11,'JUST A SUM','CODING','flag{25}',30,'<code>\nint main() {<br>\nint numbers[5] = [4, 6, 2, 3, 0];<br>\nint sum = 0;<br>\nfor (int i = 0; i < 5; i++) {<br>\nsum += numbers[i] + i;<br>\n}<br>\nprintf(\"flag{%s}\", sum);<br>\n}\n</code>');
/*!40000 ALTER TABLE `challenges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solvedChallenges`
--

DROP TABLE IF EXISTS `solvedChallenges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solvedChallenges` (
  `challengeId` int(10) unsigned NOT NULL,
  `userId` int(10) unsigned NOT NULL,
  PRIMARY KEY (`challengeId`,`userId`),
  KEY `userId` (`userId`),
  CONSTRAINT `solvedChallenges_ibfk_1` FOREIGN KEY (`challengeId`) REFERENCES `challenges` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `solvedChallenges_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solvedChallenges`
--

LOCK TABLES `solvedChallenges` WRITE;
/*!40000 ALTER TABLE `solvedChallenges` DISABLE KEYS */;
INSERT INTO `solvedChallenges` VALUES (2,1),(4,1),(7,1),(9,1),(1,2),(7,2),(8,3),(11,3),(4,4),(6,4),(10,4),(3,5),(5,5),(8,5),(11,5),(8,13),(11,13),(4,14),(5,15),(1,22),(2,22),(3,22),(4,22),(5,22),(6,22),(7,22),(8,22),(9,22),(10,22),(11,22),(3,23),(7,26),(10,26),(9,27),(1,28),(2,28),(3,28),(1,29),(4,29),(7,29),(5,30),(6,30),(11,30),(8,31);
/*!40000 ALTER TABLE `solvedChallenges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `points` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@ctf.org','$2y$10$akIE/orjEdg5qknbLesd1.QpUESzbdNsKx3Ynj2Q.f8hQQUu8o2Pa',1,1125),(2,'user1','user1@ctf.org','$2y$10$2w3aYWhNHE8wEoHqJrJJh.iWMR5eAXzao1bvy522dMK9zVVbp3klu',0,550),(3,'user2','user2@ctf.org','$2y$10$stvIj2K0kGiPP.0kvkN6ZeGH.w36Xg3gqyCnYogR/Fr9sAobQOqca',0,80),(4,'admin2','admin2@ctf.org','$2y$10$BeR9nrfPvkdOyoQvRFey0OWamP0n02HOy9IeOzYuiUoZtBTo//G4a',1,650),(5,'admin3','admin3@ctf.org','$2y$10$SZWjqWa.nmRmZ0/aLS.tTesQN6iMBWmC3Y/yz0EAfAnDsYbSEZic2',1,380),(6,'CiriacoBergamaschi','CiriacoBergamaschi@armyspy.com','$2y$10$mrg6ytXcxZPZ64sNL9qMf.nGaUQYSEYmCLNw94L4.9qvVYZH9i2Wa',0,0),(7,'CarloEsposito','CarloEsposito@armyspy.com','$2y$10$Wtp.zj5e4WYOVE30R/QwLOMSxZuRvf9aVAwPF3RsKHcPFcF6se.Sq',0,0),(8,'Whilks','AmerigoSal@armyspy.com','$2y$10$QK73qTWDB0fvugR/ChH9h.7H6T2PRkHWrJrrQCKUzPv3N/htMhnbq',0,0),(9,'Lebtled','ElsaNucci@dayrep.com','$2y$10$OouaefOVlnVBa2EXKkGMtu3ousss.uRsMPvbwGkZ5ZM6ih6Pdoyh6',0,0),(10,'Mrsices','GuerrinoGreco@jourrapide.com','$2y$10$wJ.hlc/gqdL0c.l0U90b0O3.cZIrlIQmQ8Rbd5z7wyf18VHm1FYiK',0,0),(11,'Seetumbrave','FabianaMazzi@teleworm.us','$2y$10$j1cZAwcwXfvDV293vil/XOuDSyKi6JBnhCcrEyDmSEDcfQFPrCtf.',0,0),(12,'MichelaBruno','MichelaBruno@rhyta.com','$2y$10$KpstDyFb3OnvRBPWmGou0etlylFDe9LGO7.3bumFLv7IFQ01Ku0CO',0,0),(13,'user3','user3@gmail.com','$2y$10$./GHlvygnyNOqK4.dSxbee75Q39XL6o1JuD7LA/PMHfAIh5hcjATi',0,80),(14,'user4','user4@hotmail.it','$2y$10$qaBEp2spGtqcqBm5Tmajz.xC7uTxh9FPaYpJX8AyUP1MFeDB3VJlG',0,300),(15,'user5','user5@gmail.com','$2y$10$Ml7twnXQgMNMTpWzfiO0U.rH32bfSZpCOQjaLaenCJLhdfV2AAoGe',0,100),(16,'user6','user6@inforge.net','$2y$10$u/BVavvF1gHNSZFYzf2gd.AIECPRSVEhUJ4YE/1YCnQZMXR9RSKtq',0,0),(17,'VeryVeryVeryVeryVeryLongUsername','test@ctf.org','$2y$10$RmaRtG7hoJxPQzTmzSXSPOwQkNqwhof5o6blfiLVBPlOHYxS.qxA2',0,0),(18,'ShortUsername','ButThisEmailAddressIsVeryVeyVeryVeryVeryVeryLooooooooooooooooong@AlsoTheDomainIsVeryVeryVeryVeryVeryVeryLooooooooooooooooooooong.AVeryVeryVeryVeryLoooooooooooooooooooooooooooooooooooooooongTld','$2y$10$/gt3L9TOM7p4DYUsHJBQNO/db4HHrL0vp9.wNW7bcSk4OsXhZ5zOu',0,0),(19,'Thring','MacariaLongo@teleworm.us','$2y$10$T3ahwwBJQVQkorqAYOh.7eWN232nxTjUdueItRaAhUVqvlzGEMwfW',0,0),(20,'Miturs','OfeliaDeRose@jourrapide.com','$2y$10$1tqGO7ZxsxAuKlG2uT/Wze8hlHBqjFfDOscD2YW8Oh8y3WBZ0y01G',0,0),(21,'user7','user7@live.it','$2y$10$5n1mtRfagccj.azr4.usKeygEmPQlorMjkkxP4MhIMQbn4RBAFBQm',0,0),(22,'user8','user8@ctf.org','$2y$10$WF6LJR2idhIJrXFo15MFs.NTXtIHUM2Iz6sikJyjtINYz3mOrAcda',0,1905),(23,'user9','user9@inforge.net','$2y$10$xTR1NHLnRaL8lJmG/nZZpOh.fCwsSu1gMgWML/n4uId.u1ZN7dQPe',0,200),(24,'admin4','admin4@inforge.net','$2y$10$/Yff4m51e2VUYx20Kh1tg.gKp6.Io1.7p1.F6cHpHyQzBzLTYfnPe',1,0),(25,'PromotedUser','promoted@to-admin.com','$2y$10$N2N96Pxq6UJPGYMUBqn73up6mWhZi7QgImOV7gmmF63sb9ioy9bzS',1,0),(26,'Hinat1974','VittorioCocci@dayrep.com','$2y$10$H4i4Q8gz4YqUCcY84U54xONiKBlfpV3Mx7CfPe/dC3eMEVNUNroKq',0,800),(27,'Shuman91','OrtensiaRizzo@dayrep.com','$2y$10$Qk0iVZXkwwgP1z382UoyV.KZv9.m3lHtPy7i.EnsruVPEA0ziFiYu',0,175),(28,'ElenaManna','ElenaManna95@jourrapide.com','$2y$10$Qz6NKCl/cdW6tk5aEDhUCO15LOsKHS.r8Fs9UVz82mcaF49Zhw03.',0,400),(29,'SpeedJack','speedjack95@gmail.com','$2y$10$f5GhwtGt79dpj6Axb4iJ1.z6edyi6yIyb.5wo5gRM380kfoJfWWg6',1,850),(30,'n.scatena1','n.scatena1@studenti.unipi.it','$2y$10$gPJFro.ieM/8mTX7aiGj4.CKjP83OSAx4rqoL4GJEFYS02HR5selq',0,180),(31,'Comineve','MargheritaPalerma@rhyta.com','$2y$10$g3d4u4vsqCTifOVqUXbtdu2fbofEKZdLv6DaBYCvg1XkE/NYCpMxK',0,50);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-07-15 18:28:48
