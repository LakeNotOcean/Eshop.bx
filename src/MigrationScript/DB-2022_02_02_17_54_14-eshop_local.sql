-- MySQL dump 10.13  Distrib 5.6.51, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: eshop_local
-- ------------------------------------------------------
-- Server version	5.6.51

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
-- Table structure for table `up_image`
--

DROP TABLE IF EXISTS `up_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `up_image` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PATH` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `WIDTH` int(11) DEFAULT NULL,
  `HEIGHT` int(11) DEFAULT NULL,
  `ITEM_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `up_image_up_item_ID_fk` (`ITEM_ID`),
  CONSTRAINT `up_image_up_item_ID_fk` FOREIGN KEY (`ITEM_ID`) REFERENCES `up_item` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_image`
--

LOCK TABLES `up_image` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `up_item`
--

DROP TABLE IF EXISTS `up_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `up_item` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PRICE` int(11) DEFAULT NULL,
  `SHORT_DESC` text COLLATE utf8mb4_unicode_ci,
  `FULL_DESC` text COLLATE utf8mb4_unicode_ci,
  `SORT_ORDER` int(11) DEFAULT NULL,
  `ACTIVE` tinyint(1) DEFAULT NULL,
  `DATE_CREATE` datetime DEFAULT NULL,
  `DATE_UPDATE` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_item`
--

LOCK TABLES `up_item` WRITE;
INSERT INTO `up_item` VALUES (2,'Asus GTX 750',7499,'Видеокарта Asus GeForce GTX 750 позволяет расширить рабочие и игровые возможности Вашего ПК.','Видеокарта Asus GeForce GTX 750 позволяет расширить рабочие и игровые возможности Вашего ПК. Производительности устройства достаточно для воспроизведения видео в формате Full HD и для установки наивысших настроек в играх. Процессор с рабочей тактовой частотой 1060 МГц может быть разогнан на 7%, если потребуется больше ресурсов. Два гигабайта памяти эффективно расходуются при многопоточной нагрузке, а разрядность шины в 128 бит позволяет ускорить обмен данными между памятью и GPU.\n\nAsus GeForce GTX 750 оснащен охладительной системой с одним большим вентилятором, который отводит тепло от микрочипов, продлевая срок эксплуатации карты. Потребление энергии адаптером в моменты пиковой нагрузки не превышает 75 Вт. Поэтому подключение дополнительного питания от БП не требуется.',NULL,1,'2022-02-02 17:36:04','2022-02-02 17:36:11');
UNLOCK TABLES;

--
-- Table structure for table `up_item_tag`
--

DROP TABLE IF EXISTS `up_item_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `up_item_tag` (
  `ITEM_ID` int(11) DEFAULT NULL,
  `TAG_ID` int(11) DEFAULT NULL,
  KEY `up_item_tag_up_item_ID_fk` (`ITEM_ID`),
  KEY `up_item_tag_up_tag_ID_fk` (`TAG_ID`),
  CONSTRAINT `up_item_tag_up_item_ID_fk` FOREIGN KEY (`ITEM_ID`) REFERENCES `up_item` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `up_item_tag_up_tag_ID_fk` FOREIGN KEY (`TAG_ID`) REFERENCES `up_tag` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_item_tag`
--

LOCK TABLES `up_item_tag` WRITE;
INSERT INTO `up_item_tag` VALUES (2,1),(2,3),(2,7),(2,11),(2,9);
UNLOCK TABLES;

--
-- Table structure for table `up_migration`
--

DROP TABLE IF EXISTS `up_migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `up_migration` (
  `LAST_MIG` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_migration`
--

LOCK TABLES `up_migration` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `up_order`
--

DROP TABLE IF EXISTS `up_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `up_order` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EMAIL` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PHONE` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `COMMENT` text COLLATE utf8mb4_unicode_ci,
  `STATUS` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ITEM_ID` int(11) DEFAULT NULL,
  `DATE_CREATE` datetime DEFAULT NULL,
  `DATE_UPDATE` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `up_order_up_item_ID_fk` (`ITEM_ID`),
  CONSTRAINT `up_order_up_item_ID_fk` FOREIGN KEY (`ITEM_ID`) REFERENCES `up_item` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_order`
--

LOCK TABLES `up_order` WRITE;
UNLOCK TABLES;

--
-- Table structure for table `up_specs`
--

DROP TABLE IF EXISTS `up_specs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `up_specs` (
  `ITEM_ID` int(11) DEFAULT NULL,
  `MANUFACTURER` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `COUNTRY` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `WARRANTY` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RELEASE_YEAR` year(4) DEFAULT NULL,
  `MEMORY_SIZE` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MEMORY_TYPE` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BUS_WIDTH` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TECHNICAL_PROCESS` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CHIP_FREQ` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MEM_FREQ` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MAX_RESOLUTION` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OUT_CONNECTORS` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `INTERFACE` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ADDITIONAL_POWER` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `REQUIRED_POWER` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `FANS_NUM` int(11) DEFAULT NULL,
  `LENGTH` int(11) DEFAULT NULL,
  `THICKNESS` int(11) DEFAULT NULL,
  KEY `up_specs_up_item_ID_fk` (`ITEM_ID`),
  CONSTRAINT `up_specs_up_item_ID_fk` FOREIGN KEY (`ITEM_ID`) REFERENCES `up_item` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_specs`
--

LOCK TABLES `up_specs` WRITE;
INSERT INTO `up_specs` VALUES (2,'Asus','Китай','36 мес.',2012,'2 ГБ','GDDR5','192 бит','28 нм','1059 МГц','5010 МГц','2560х1600','HDMI, VGA (D-Sub), DVI-D','PCI-E','нет','55 Вт',1,185,28);
UNLOCK TABLES;

--
-- Table structure for table `up_tag`
--

DROP TABLE IF EXISTS `up_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `up_tag` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_tag`
--

LOCK TABLES `up_tag` WRITE;
INSERT INTO `up_tag` VALUES (1,'Игровые'),(2,'Топовые'),(3,'Для игр в Full HD'),(4,'Для игр в 2К'),(5,'Для игр в 4К'),(6,'Видеокарты AMD'),(7,'Видеокарты NVidia'),(8,'Бесшумные'),(9,'Компактные'),(10,'Офисные'),(11,'Бюджетная'),(12,'RTX 20 серии'),(13,'RTX 30 серии'),(14,'GTX 10 серии');
UNLOCK TABLES;

--
-- Table structure for table `up_user`
--

DROP TABLE IF EXISTS `up_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `up_user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LOGIN` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PASSWORD` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_user`
--

LOCK TABLES `up_user` WRITE;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-02 17:54:14
