-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: cinema_db
-- ------------------------------------------------------
-- Server version	8.0.43

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
-- Table structure for table `seats`
--

DROP TABLE IF EXISTS `seats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_id` int NOT NULL,
  `row_label` varchar(5) NOT NULL,
  `seat_number` int NOT NULL,
  `seat_type` enum('STANDARD','VIP','COUPLE') DEFAULT 'STANDARD',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_seat` (`room_id`,`row_label`,`seat_number`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seats`
--

LOCK TABLES `seats` WRITE;
/*!40000 ALTER TABLE `seats` DISABLE KEYS */;
INSERT INTO `seats` VALUES (1,1,'A',1,'STANDARD'),(2,1,'A',2,'STANDARD'),(3,1,'A',3,'STANDARD'),(4,1,'A',4,'STANDARD'),(5,1,'A',5,'STANDARD'),(6,1,'A',6,'STANDARD'),(7,1,'A',7,'STANDARD'),(8,1,'A',8,'STANDARD'),(9,1,'B',1,'STANDARD'),(10,1,'B',2,'STANDARD'),(11,1,'B',3,'STANDARD'),(12,1,'B',4,'STANDARD'),(13,1,'B',5,'STANDARD'),(14,1,'B',6,'STANDARD'),(15,1,'B',7,'STANDARD'),(16,1,'B',8,'STANDARD'),(17,1,'C',1,'STANDARD'),(18,1,'C',2,'STANDARD'),(19,1,'C',3,'STANDARD'),(20,1,'C',4,'STANDARD'),(21,1,'C',5,'STANDARD'),(22,1,'C',6,'STANDARD'),(23,1,'C',7,'STANDARD'),(24,1,'C',8,'STANDARD'),(25,1,'D',1,'STANDARD'),(26,1,'D',2,'STANDARD'),(27,1,'D',3,'STANDARD'),(28,1,'D',4,'STANDARD'),(29,1,'D',5,'STANDARD'),(30,1,'D',6,'STANDARD'),(31,1,'D',7,'STANDARD'),(32,1,'D',8,'STANDARD'),(33,1,'E',1,'STANDARD'),(34,1,'E',2,'STANDARD'),(35,1,'E',3,'STANDARD'),(36,1,'E',4,'STANDARD'),(37,1,'E',5,'STANDARD'),(38,1,'E',6,'STANDARD'),(39,1,'E',7,'STANDARD'),(40,1,'E',8,'STANDARD');
/*!40000 ALTER TABLE `seats` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-05  8:23:15
