-- MySQL dump 10.13  Distrib 8.0.28, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: eshop
-- ------------------------------------------------------
-- Server version	8.0.28-0ubuntu0.20.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_image` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `PATH` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ITEM_ID` int DEFAULT NULL,
  `IS_MAIN` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `up_image_up_item_ID_fk` (`ITEM_ID`),
  CONSTRAINT `up_image_up_item_ID_fk` FOREIGN KEY (`ITEM_ID`) REFERENCES `up_item` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_image`
--

LOCK TABLES `up_image` WRITE;
/*!40000 ALTER TABLE `up_image` DISABLE KEYS */;
INSERT INTO `up_image` (`ID`, `PATH`, `ITEM_ID`, `IS_MAIN`) VALUES (1,'test',1,1),(2,'test2',2,1),(3,'',3,1),(4,'',4,1),(5,'',5,1),(6,'',6,1),(7,'\'\'',7,1),(8,'\'\'',8,1),(9,'\'\'',9,1),(10,'\'\'',10,1),(11,'\'\'',11,1),(12,'\'\'',12,1),(13,'\'\'',13,1),(14,'\'\'',14,1),(15,'\'\'',15,1),(16,'\'\'',16,1),(17,'\'\'',17,1),(18,'\'\'',18,1),(19,'\'\'',19,1),(20,'\'\'',20,1),(21,'\'\'',21,1),(22,'\'\'',22,1),(23,'\'\'',23,1),(24,'\'\'',24,1),(25,'\'\'',25,1);
/*!40000 ALTER TABLE `up_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_item`
--

DROP TABLE IF EXISTS `up_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_item` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PRICE` int DEFAULT NULL,
  `SHORT_DESC` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `FULL_DESC` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `SORT_ORDER` int DEFAULT NULL,
  `ACTIVE` tinyint(1) DEFAULT NULL,
  `DATE_CREATE` datetime DEFAULT NULL,
  `DATE_UPDATE` datetime DEFAULT NULL,
  `ITEM_TYPE_ID` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_item`
--

LOCK TABLES `up_item` WRITE;
/*!40000 ALTER TABLE `up_item` DISABLE KEYS */;
INSERT INTO `up_item` (`ID`, `TITLE`, `PRICE`, `SHORT_DESC`, `FULL_DESC`, `SORT_ORDER`, `ACTIVE`, `DATE_CREATE`, `DATE_UPDATE`, `ITEM_TYPE_ID`) VALUES (1,'Видеокарта Palit GeForce RTX 3090 GamingPro OC [NED3090019SB-132BA] [PCI-E 4.0, 24 ГБ GDDR6X, 384 бит, 1395 МГц - 1725 МГц, DisplayPort x3, HDMI]',264999,'Разогнанная версия с частотой до 1725 МГц. Обладающий характеристиками высокого класса и стильным оформлением с подсветкой, этот графический адаптер станет мощным дополнением игровой системы.','В основе устройства содержится процессор GA102 с микроархитектурой Ampere. В оснащение представленной модели входят 24 ГБ памяти стандарта GDDR6X с пропускной способностью в пределах 19.5 Гбит/с и 384-разрядной шиной.
Palit GeForce RTX 3090 GamingPro OC стабильно работает при различной нагрузке благодаря тщательно протестированной элементной базе и мощному кулеру с тремя вентиляторами. Светодиодная подсветка RGB предоставляет широкие возможности для настройки световых эффектов с помощью фирменного ПО. Из интерфейсов предусмотрены 3 разъема DisplayPort и разъем HDMI. Среди прочих особенностей отмечается необходимость подключения двух разъемов питания 8-pin.',3,1,'2022-02-10 00:26:15','2022-02-10 00:26:17',1),(2,'Asus GTX 750',7499,'Видеокарта Asus GeForce GTX 750 позволяет расширить рабочие и игровые возможности Вашего ПК.','Видеокарта Asus GeForce GTX 750 позволяет расширить рабочие и игровые возможности Вашего ПК. Производительности устройства достаточно для воспроизведения видео в формате Full HD и для установки наивысших настроек в играх. Процессор с рабочей тактовой частотой 1060 МГц может быть разогнан на 7%, если потребуется больше ресурсов. Два гигабайта памяти эффективно расходуются при многопоточной нагрузке, а разрядность шины в 128 бит позволяет ускорить обмен данными между памятью и GPU.

Asus GeForce GTX 750 оснащен охладительной системой с одним большим вентилятором, который отводит тепло от микрочипов, продлевая срок эксплуатации карты. Потребление энергии адаптером в моменты пиковой нагрузки не превышает 75 Вт. Поэтому подключение дополнительного питания от БП не требуется.',1,1,'2022-02-02 17:36:04','2022-02-02 17:36:11',1),(3,'Видеокарта Palit GeForce RTX 3060 DUAL OC (LHR) [NE63060T19K9-190AD] [PCI-E 4.0, 12 ГБ GDDR6, 192 бит, 1320 МГц - 1837 МГц, DisplayPort x3, HDMI]',70999,'Представляет собой высокопроизводительное решение для профессиональных рабочих станций и игровых систем.','Благодаря технологиям Ampere, мощному видеопроцессору и 12 ГБ памяти данная модель позволяет с легкостью решать любые задачи. Кроме того, графический ускоритель поддерживает трассировку лучей, за счет чего обеспечивается реалистичное изображение в играх. Для отвода тепла предусмотрено 2 осевых вентилятора с особой формой лопастей, благодаря чему рабочая температура не превышает 93°C.
Кроме широких возможностей для работы и развлечений Palit GeForce RTX 3060 DUAL OC (LHR) также отличается лаконичным дизайном и оснащена яркой подсветкой, которая добавит красок компьютерной сборке. Длина корпуса данной модели составляет 245 мм, а толщина – 41 мм, из-за чего для установки необходимо 2 отсека. Подключение выполняется через PCI-E 4.0 и коннектор 8-pin для питания. Кроме того, графический ускоритель оборудован различными видеоразъемами для внешних мониторов.',3,1,'2022-02-02 17:36:04','2022-02-10 00:29:08',1),(4,'Видеокарта Palit GeForce RTX 3080 GameRock (LHR) [NED3080U19IA-1020G]',137999,'Базирующаяся на видеопроцессоре GeForce RTX 3080 видеокарта Palit GeForce RTX 3080 GameRock (LHR) не имеет слабых мест.','Этот видеоадаптер способен удовлетворить потребности самых требовательных любителей видеоигр. Уровень производительности устройства безупречно высок. Подбор сопоставимых по классу компонентов системы (в первую очередь – процессора) будет сопряжен с заметными трудностями.
Видеокарта Palit GeForce RTX 3080 GameRock (LHR) располагает 10 ГБ видеопамяти GDDR6X, пропускная способность которой (760 ГБ/с) поражает воображение. Стабильность функционирования видеоадаптера в пиковых режимах обеспечивает система охлаждения Palit TurboFan 3.0. Вентиляторы могут не только существенно снижать скорость, но и останавливаться.
Максимальное энергопотребление видеокарты составляет 340 Вт. Лимит энергопотребления – 400 Вт. Производителем видеоадаптера рекомендовано использование блока питания с мощностью от 850 Вт. В комплектацию модели входят документация, кабель питания, кабель синхронизации ARGB и кронштейн для поддержки карты.',3,1,'2022-02-02 17:36:04','2022-02-10 00:30:36',1),(5,'Видеокарта Palit GeForce RTX 3080 Ti GamingPro [NED308T019KB-132AA]',174999,'Обеспечивает потрясающую графику, невероятно высокую частоту кадров и ускорение искусственного интеллекта для игр и творческих приложений.','Использование высококачественных алюминиевых пластин для охлаждения компонентов и усовершенстваных вентиляторов TurboFan 3.0 обеспечивает потрясающую эффективность охлаждения. Сочетая черный и серебристо-серый дизайн с ARGB подсветкой, видеокарта позволяет настраивать световые эффекты в соответствии с собственными предпочтениями пользователей.',3,1,'2022-02-02 17:36:04','2022-02-10 00:35:33',1),(6,'Видеокарта GIGABYTE GeForce RTX 3070 Ti GAMING OC [GV-N307TGAMING OC-8GD]',112999,'Видеокарта GIGABYTE GeForce RTX 3070 Ti GAMING OC – игровой видеоадаптер экстра-класса. Устройство оснащено видеопроцессором GeForce RTX 3070 Ti, штатная частота которого равна 1575 МГц. Объем памяти значителен – 8 ГБ.','Видеокарта GIGABYTE GeForce RTX 3070 Ti GAMING OC обладает высокопроизводительной системой охлаждения, представленной тремя осевыми вентиляторами. Предусмотрена возможность остановки вентиляторов. Видеоадаптер, соответственно, может быть полностью бесшумным.',3,1,'2022-02-02 17:36:04','2022-02-10 00:37:43',1),(7,'Видеокарта GIGABYTE GeForce GTX 1660 Ti OC [GV-N166TOC-6GD]',48999,'Видеокарта построена на графическом процессоре Turing.','Оснащена продвинутой системой охлаждения и оптимизированной мощностью для максимальной игровой производительности.',3,1,'2022-02-10 00:46:28','2022-02-10 00:46:30',1),(8,'Видеокарта Palit GeForce RTX 3060 Ti DUAL OC (LHR) [NE6306TS19P2-190AD]',85999,'Обеспечивают оптимальную производительность в топовых играх благодаря возможностям Ampere — архитектуры NVIDIA RTX второго поколения. ','Оптимальная производительность и качество графики благодаря улучшенным ядрам RT и тензорным ядрам, потоковым мультипроцессорам и высокоскоростной памяти G6.
Серия Palit GeForce RTX 3060 Ti DUAL OC (LHR) оснащена двумя большими 90 мм вентиляторами для эффективного охлаждения и вентиляционными отверстиями с достаточной площадью на задней панели для оптимального воздушного потока. Настраиваемая RGB-подсветка на боковых сторонах кожуха системы охлаждения предназначена для пользователей, предпочитающих подсветку с минималистичными световыми эффектами.',3,1,'2022-02-10 00:49:23','2022-02-10 00:49:25',1),(9,'Видеокарта KFA2 GeForce GTX 1650 EX (1-Click OC) [65SQL8DS66EK]',27999,'Может использоваться при сборке или модернизации игрового системного блока начального уровня.','Модель может быть смонтирована в отличающемся компактными размерами корпусе. Данная возможность обеспечивается благодаря незначительной (лишь 181 мм) длине устройства. Не предъявляется сколько-нибудь серьезных требований и к источнику питания. Невысокое пиковое энергопотребление видеокарты (75 Вт) обуславливает наличие блока питания с мощностью, стартующей от 300 Вт. Видеокарта занимает два слота расширения.
Видеоадаптер KFA2 GeForce GTX 1650 EX (1-Click OC) [65SQL8DS66EK] использует для обмена информацией интерфейс PCI-E 3.0. Графический процессор устройства – GeForce GTX 1650. Штатная частота видеочипа – 1410 МГц. В оснащение видеокарты входят 3 видеоразъема – DVI-D, DisplayPort и HDMI. Максимальное разрешение – 7680x4320.',3,1,'2022-02-10 01:03:29','2022-02-10 01:03:30',1),(10,'Видеокарта GIGABYTE GeForce RTX 3090 TURBO [GV-N3090TURBO-24GD]',259999,'Базирующаяся на графическом процессоре GeForce RTX 3090 видеокарта GIGABYTE GeForce RTX 3090 TURBO [GV-N3090TURBO-24GD] соответствует экстра-классу.','Вы сможете обеспечить невероятно высокий уровень производительности игрового ПК. Важной особенностью модели является поддержка мультипроцессорной конфигурации NVLink. Память – 24 ГБ GDDR6X.
Видеокарта GIGABYTE GeForce RTX 3090 TURBO [GV-N3090TURBO-24GD] позволяет подключить до 4 мониторов. В наличии по 2 порта DP и HDMI. Максимальное энергопотребление устройства составляет 350 Вт. Величина данного показателя свидетельствует о необходимости присутствия эффективной системы охлаждения. Излишний нагрев видеоадаптера с легкостью предотвращает единственный центробежный вентилятор. Длина видеокарты равна 267 мм. Количество занимаемых слотов расширения – 2. Подсветка конструкцией устройства не предусмотрена.',3,1,'2022-02-10 01:08:56','2022-02-10 01:09:01',1),(11,'Видеокарта Palit GeForce GTX 1050 Ti STORMX [NE5105T018G1-1070F',22999,'Видеокарта, размер которой составляет 16.6x11.2 сантиметра, занимает пару слотов расширения, являясь при этом настолько эффективной, что может и должна использоваться в системах, где нужна производительная обработка видеоданных. ','Работать чип GeForce GTX 1050 Ti способен в колеблющемся от 1.29 до 1.392 гигагерца частотном диапазоне, что в очередной раз доказывает продуктивность ускорителя. В основе его лежит графика GTX 1050, изготовителем которой является небезызвестная фирма NVIDIA.
128-битная шина памяти, частота 7 гигагерц, а также 4-гигабайтный объем – все это делает кратковременную GDDR5-память описываемого изделия очередным сильным местом. Охлаждается карта активно – воздухом, при этом осевой вентилятор на ней уже есть. При том, что потреблять плата может до 75 ватт, к блоку питания она не требовательна – с 300-ваттным вполне совместима.',3,1,'2022-02-10 01:15:05','2022-02-10 01:15:11',1),(12,'Видеокарта Powercolor AMD Radeon RX 6600 XT Fighter [AXRX 6600XT 8GBD6-3DH]',89999,'Оснащенная 32 мощными улучшенными вычислительными блоками','Видеокарта AMD Radeon RX 6600 XT на базе архитектуры AMD RDNA 2, оснащенная 32 мощными улучшенными вычислительными блоками, кэш-памятью AMD Infinity Cache и выделенной памятью GDDR6 объемом 8 ГБ, обеспечивает сверхвысокую частоту кадров и великолепные ощущения при игре с разрешением 1440p.',3,1,'2022-02-10 01:16:17','2022-02-10 01:16:20',1),(13,'Видеокарта GIGABYTE GeForce RTX 2060 Super WINDFORCE OC [GV-N206SWF2OC-8GD]',85999,'Игровой видеоадаптер высокого класса, который способен удовлетворить потребности большинства любителей видеоигр.','Модель оснащена широко известным графическим процессором GeForce RTX 2060 Super, произведенным с использованием техпроцесса 12 нм. Полную реализацию потенциальных возможностей видеопроцессора помогает обеспечить скоростная память GDDR6, объем которой составляет 8 ГБ.
Видеокарта GIGABYTE GeForce RTX 2060 Super WINDFORCE OC [GV-N206SWF2OC-8GD] допускает одновременное подключение 4 мониторов. Максимальное разрешение стандартно для видеоадаптеров высокого уровня – 7680x4320. В модели реализована система активного воздушного охлаждения, представленная парой производительных, но малошумных вентиляторов. Длина видеокарты равна 265 мм. Устройство занимает лишь 2 слота расширения. Максимальное энергопотребление видеоадаптера – 175 Вт. Вам будет необходим блок питания, номинальная выходная мощность которого равна или превышает 550 Вт.',3,1,'2022-02-10 01:18:28','2022-02-10 01:18:29',1),(14,'Видеокарта GIGABYTE GeForce RTX 3070 GAMING OC (LHR) [GV-N3070GAMING OC-8GD rev2.0]',104999,'Стильное и производительное решение для игровых и рабочих систем. ','Модель разработана на базе архитектуры Ampere с улучшенными тензорными ядрами и мультипроцессорами. Рабочие частоты видеокарты могут варьироваться, что вкупе со скоростной памятью GDDR6 объемом 8 ГБ, поддержкой трассировки лучей, а также технологий OpenGL 4.6 и DirectX 12 обеспечит высокую производительность при работе с фото и видео, при моделировании, проектировании и запуске игр на высоких настройках изображения. Максимальная температура графического ускорителя может достигать 93°C. Для охлаждения предусмотрено 3 осевых вентилятора GIGABYTE WindForce 3X.',3,1,'2022-02-10 01:19:00','2022-02-10 01:19:00',1),(15,'Видеокарта Palit GeForce RTX 3080 Ti GameRock [NED308T019KB-1020G]',182999,'Серия Palit GameRock со свежим дизайном «Ослепительный ангел», специально разработана для требовательных игроков-энтузиастов, которые хотят получить максимальный игровой опыт и оригинальную систему освещения ARGB.','Серия GameRock предоставляет лучшие игровые функции, высочайшую эффективность охлаждения и превосходное качество видеокарты, что позволяет вам чувствовать себя настоящим рокером на игровой сцене.',3,1,'2022-02-10 01:20:43','2022-02-10 01:20:44',1),(16,'Видеокарта GIGABYTE GeForce RTX 3080 GAMING OC (LHR) [GV-N3080GAMING OC-10GD rev2.0]',135999,'Предназначается для модернизации игровой системы. Она использует интерфейс PCI-E 4.0 для подключения к плате. ','Использование памяти 10 ГБ GDDR6X с разрядностью 320 бит. Благодаря системе GIGABYTE WindForce 3X, использующей три осевых вентилятора, обеспечивается высокоэффективное охлаждение нагревающихся компонентов. Особенность центрального вентилятора в альтернативном вращении, уменьшающем турбулентность соседних вентиляторов, повернутых в противоположном направлении. При этом происходит повышение давления воздуха.',3,1,'2022-02-10 01:21:50','2022-02-10 01:21:53',1),(17,'Видеокарта PowerColor AMD Radeon RX 6700 XT Red Devil [AXRX 6700XT 12GBD6-3DHE/OC]',89999,'Призвана обеспечить высокий уровень производительности в играх и требовательных приложениях. ','Графический процессор работает с частотой 2321 МГц, которая способна увеличиваться до показателя 2622 МГц. Кэш-память AMD Infinity Cache обеспечивает высокую пропускную способность в играх и низкие задержки графики.
Из интерфейсов предлагаются видеовыход HDMI и 3 разъема Display Port. Кулер с тремя вентиляторами эффективно рассеивает тепло от компонентов графического адаптера при любых нагрузках, предотвращая перегрев. С обратной стороны кулера расположена металлическая пластина, повышающая жесткость и устойчивость адаптера к неблагоприятным воздействиям. Среди прочих особенностей PowerColor AMD Radeon RX 6700 XT Red Devil отмечается система светодиодной подсветки с широкими возможностями персонализации.',3,1,'2022-02-10 01:22:02','2022-02-10 01:22:00',1),(18,'Видеокарта Gigabyte GeForce RTX 3060 Ti AORUS ELITE (LHR) [ GV-N306TAORUS E-8GD rev2.0]',91999,'Модель с графическим процессором GeForce RTX 3060 Ti и 8-нанометровым технологическим процессом',' Модель с графическим процессором GeForce RTX 3060 Ti и 8-нанометровым технологическим процессом получила поддержку стандартов CUDA 8.6, DirectX 12 Ultimate, OpenCL 1.2, OpenGL 4.6 и Vulkan 1.2. Штатная частота работы видеочипа достигает 1410 МГц, при этом предусмотрен разгон до 1785 МГц.
Видеокарта Gigabyte GeForce RTX 3060 Ti AORUS ELITE (LHR) располагает четырьмя видеоразъемами (DisplayPort 2 шт. и HDMI 2 шт.) с поддержкой разрешения до 7680x4320 пикселей. За эффективное охлаждение компонентов устройства отвечают три осевых вентилятора GIGABYTE WindForce 3X. Максимальный показатель энергопотребления видеокарты равен 200 Вт.',3,1,'2022-02-10 01:23:08','2022-02-10 01:23:11',1),(19,'Видеокарта MSI GeForce GTX 1660 SUPER Gaming X [GTX 1660 SUPER GAMING X]',51999,'Модель может удовлетворить потребности очень большого количества ценителей ресурсоемких видеоигр. Видеоадаптер рассчитан на работу в сингл-режиме.','Видеоадаптер MSI GeForce GTX 1660 SUPER Gaming X [GTX 1660 SUPER GAMING X] отличается эффектным дизайном. В наличии многоцветная подсветка. Для охлаждения устройства используются 2 крупных вентилятора.
Главным конструктивным элементом адаптера является видеопроцессор GeForce GTX 1660 SUPER. Объем памяти устройства равен 6 ГБ. Пропускная способность памяти – 336 ГБ/с.
Несмотря на высокий уровень производительности, модель не отличается значительной потребляемой мощностью: пиковое значение этого показателя равно 125 Вт. Вам будет нужен как минимум 450-ваттный блок питания. Максимально поддерживаемое видеокартой количество мониторов равно 4.',3,1,'2022-02-10 01:25:22','2022-02-10 01:25:22',1),(20,'Видеокарта GIGABYTE GeForce GTX 1660 SUPER OC [GV-N166SOC-6GD 1.0]',47999,'Модель базируется на широко распространенном графическом процессоре GTX 1660 SUPER. ','Поддерживаются стандарты Vulkan 1.2, OpenGL 4.6 и DirectX 12. Совокупность технических характеристик делает оправданным использование устройства в составе игрового компьютера среднего уровня. Вы сможете использовать большинство популярных видеоигр.
Видеокарта GIGABYTE GeForce GTX 1660 SUPER OC [GV-N166SOC-6GD 1.0] отличается компактностью. Длина устройства, равная лишь 225.65 мм, дает возможность использовать компактный корпус. Вам не потребуется большое количество свободных слотов расширения: адаптеру, толщина которого равна 40.5 мм, требуются только 2 слота. Потребляемая мощность модели может достигать 150 Вт. Минимально допустимая выходная мощность источника питания – 450 Вт. За охлаждение видеоадаптера отвечают два крупных, но не отличающихся существенным уровнем шума осевых вентилятора. Подсветка видеокарты отсутствует.',3,1,'2022-02-10 01:26:41','2022-02-10 01:26:44',1),(21,'Видеокарта GIGABYTE AMD Radeon RX 6600 EAGLE [GV-R66EAGLE-8GD]',53999,'Оснащенная мощными улучшенными вычислительными блоками, ','Видеокарта AMD Radeon RX 6600 на базе архитектуры AMD RDNA 2',3,1,'2022-02-10 01:28:40','2022-02-10 01:28:44',1),(22,'Видеокарта ASUS GeForce RTX 3080 TUF OC GAMING (LHR) [TUF-RTX3080-O10G-V2-GAMING]',146999,'Обеспечивает рекордную производительность для геймеров, работая на базе Ampere — архитектуры NVIDIA RTX второго поколения.','Видеокарта TUF Gaming GeForce RTX 3080 полна инновационных решений, направленных на улучшение электропитания и охлаждения. Под металлическим кожухом новой конструкции работают три мощных вентилятора Axial-tech с долговечными двойными шарикоподшипниками. При низкой температуре они останавливаются, делая видеокарту бесшумной. Массивные радиаторы, отборная элементная база, автоматизированный процесс изготовления и множество дополнительных улучшений, таких как вентиляционное отверстие в усилительной пластине – на такое оптимизированное до мелочей устройство можно положиться при сборке любого компьютера.',3,1,'2022-02-10 01:29:42','2022-02-10 01:29:45',1),(23,'Видеокарта MSI GeForce RTX 3080 GAMING Z TRIO (LHR) [RTX 3080 GAMING Z TRIO 10G LHR]',142999,'Она оснащена улучшенными ядрами RT и тензорными ядрами, потоковыми мультипроцессорами и высокоскоростной памятью G6X для потрясающих игровых возможностей.','Видеокарта MSI GeForce RTX 3080 GAMING Z TRIO (LHR) обеспечивает рекордную производительность для геймеров, работая на базе Ampere — архитектуры NVIDIA RTX второго поколения. Она оснащена улучшенными ядрами RT и тензорными ядрами, потоковыми мультипроцессорами и высокоскоростной памятью G6X для потрясающих игровых возможностей.',3,1,'2022-02-10 01:30:34','2022-02-10 01:29:50',1),(24,'Видеокарта GIGABYTE GeForce GTX 1650 D6 OC (rev. 2.0) [GV-N1656OC-4GD rev2.0]',27999,'Подходит для установки в компактный системный блок. А ее технические характеристики обеспечивают реалистичную графику в большинстве игр. ','Она оборудована видеопроцессором Turing, который поддерживает передовые спецэффекты. За счет чего каждая игра будет увлекательной и интенсивной. Видеопамять формата GDDR6 и объемом 4 ГБ позволит воспроизводить изображения в высоком разрешении (8K UHD (Ultra HD)).
Для поддержания мощности и стабильной работы видеокарты GIGABYTE GeForce GTX 1650 D6 OC (rev. 2.0) используется подключение к дополнительному источнику питания через разъем 6-pin. А интерфейс PCI-E 3.0 поддерживает стабильное соединение видеокарты с материнской платой. Наличие на корпусе карты видеоразъемов предусматривает возможность одновременного подключения к ней до 3 мониторов. Активное воздушное охлаждение обеспечивает осевой вентилятор со специальной формой лопастей. Благодаря этому поддерживается высокая производительность видеокарты.',3,1,'2022-02-10 01:31:15','2022-02-10 01:31:19',1),(25,'Видеокарта GIGABYTE GeForce RTX 3060 Ti GAMING OC PRO (LHR) [GV-N306TGAMINGOC PRO-8GD rev3]',88999,'Станет одним из важнейших элементов мощного игрового компьютера. ','Видеокарта GIGABYTE GeForce RTX 3060 Ti GAMING OC PRO (LHR) станет одним из важнейших элементов мощного игрового компьютера. Система охлаждения WINDFORCE 3X в этой модели представлена тремя 80-миллиметровыми вентиляторами и пятью медными тепловыми трубками, имеющими прямой контакт с графическим процессором, для наилучшего охлаждения компонентов. Благодаря удлиненной конструкции радиатора обеспечивается лучший отвод тепла.',3,1,'2022-02-10 01:32:59','2022-02-10 01:33:02',1);
/*!40000 ALTER TABLE `up_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_item_spec`
--

DROP TABLE IF EXISTS `up_item_spec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_item_spec` (
  `ITEM_ID` int NOT NULL,
  `SPEC_TYPE_ID` int NOT NULL,
  `VALUE` varchar(500) DEFAULT '',
  PRIMARY KEY (`ITEM_ID`,`SPEC_TYPE_ID`),
  KEY `FK_IS_ST` (`SPEC_TYPE_ID`),
  CONSTRAINT `up_item_spec_ibfk_1` FOREIGN KEY (`ITEM_ID`) REFERENCES `up_item` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `up_item_spec_ibfk_2` FOREIGN KEY (`SPEC_TYPE_ID`) REFERENCES `up_spec_type` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_item_spec`
--

LOCK TABLES `up_item_spec` WRITE;
/*!40000 ALTER TABLE `up_item_spec` DISABLE KEYS */;
INSERT INTO `up_item_spec` (`ITEM_ID`, `SPEC_TYPE_ID`, `VALUE`) VALUES (2,1,'24 мес.'),(2,2,'Китай'),(2,3,'2 ГБ'),(2,4,'GDDR5'),(2,5,'5010 МГц'),(2,6,'видеокарта'),(2,8,'185 мм'),(2,10,'нет');
/*!40000 ALTER TABLE `up_item_spec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_item_tag`
--

DROP TABLE IF EXISTS `up_item_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_item_tag` (
  `ITEM_ID` int DEFAULT NULL,
  `TAG_ID` int DEFAULT NULL,
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
/*!40000 ALTER TABLE `up_item_tag` DISABLE KEYS */;
INSERT INTO `up_item_tag` (`ITEM_ID`, `TAG_ID`) VALUES (2,1),(2,3),(2,7),(2,11),(2,9);
/*!40000 ALTER TABLE `up_item_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_item_type`
--

DROP TABLE IF EXISTS `up_item_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_item_type` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAME` varchar(150) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `up_item_type_pk` (`NAME`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_item_type`
--

LOCK TABLES `up_item_type` WRITE;
/*!40000 ALTER TABLE `up_item_type` DISABLE KEYS */;
INSERT INTO `up_item_type` (`ID`, `NAME`) VALUES (1,'Видеокарта'),(2,'Клавиатура');
/*!40000 ALTER TABLE `up_item_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_migration`
--

DROP TABLE IF EXISTS `up_migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_migration` (
  `LAST_MIGRATION` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_migration`
--

LOCK TABLES `up_migration` WRITE;
/*!40000 ALTER TABLE `up_migration` DISABLE KEYS */;
INSERT INTO `up_migration` (`LAST_MIGRATION`) VALUES ('2022_02_10_17-41-00');
/*!40000 ALTER TABLE `up_migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_order`
--

DROP TABLE IF EXISTS `up_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_order` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `COMMENT` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `STATUS` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ITEM_ID` int DEFAULT NULL,
  `DATE_CREATE` datetime DEFAULT NULL,
  `DATE_UPDATE` datetime DEFAULT NULL,
  `USER_ID` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `up_order_up_item_ID_fk` (`ITEM_ID`),
  CONSTRAINT `up_order_up_item_ID_fk` FOREIGN KEY (`ITEM_ID`) REFERENCES `up_item` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_order`
--

LOCK TABLES `up_order` WRITE;
/*!40000 ALTER TABLE `up_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `up_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_review`
--

DROP TABLE IF EXISTS `up_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_review` (
  `ID` int DEFAULT NULL,
  `USER_ID` int DEFAULT NULL,
  `ITEM_ID` int DEFAULT NULL,
  `SCORE` int DEFAULT NULL,
  `COMMENT` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_review`
--

LOCK TABLES `up_review` WRITE;
/*!40000 ALTER TABLE `up_review` DISABLE KEYS */;
/*!40000 ALTER TABLE `up_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_role`
--

DROP TABLE IF EXISTS `up_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_role` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `NAME` (`NAME`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_role`
--

LOCK TABLES `up_role` WRITE;
/*!40000 ALTER TABLE `up_role` DISABLE KEYS */;
INSERT INTO `up_role` (`ID`, `NAME`) VALUES (1,'Admin'),(3,'Moderator'),(2,'User');
/*!40000 ALTER TABLE `up_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_spec_category`
--

DROP TABLE IF EXISTS `up_spec_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_spec_category` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAME` varchar(150) NOT NULL,
  `DISPLAY_ORDER` int DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `up_spec_category_pk` (`NAME`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_spec_category`
--

LOCK TABLES `up_spec_category` WRITE;
/*!40000 ALTER TABLE `up_spec_category` DISABLE KEYS */;
INSERT INTO `up_spec_category` (`ID`, `NAME`, `DISPLAY_ORDER`) VALUES (1,'Внешний вид',2),(2,'Заводские данные',1),(3,'Общие параметры',0),(4,'Спецификации видеопамяти',0),(5,'Спецификации видеопроцессора',0),(6,'Вывод изображения',0),(7,'Подключение',0),(8,'Габариты',0);
/*!40000 ALTER TABLE `up_spec_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_spec_template`
--

DROP TABLE IF EXISTS `up_spec_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_spec_template` (
  `ITEM_TYPE_ID` int NOT NULL,
  `SPEC_TYPE_ID` int NOT NULL,
  PRIMARY KEY (`ITEM_TYPE_ID`,`SPEC_TYPE_ID`),
  KEY `FK_ST_ST` (`SPEC_TYPE_ID`),
  CONSTRAINT `up_spec_template_ibfk_1` FOREIGN KEY (`ITEM_TYPE_ID`) REFERENCES `up_item_type` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `up_spec_template_ibfk_2` FOREIGN KEY (`SPEC_TYPE_ID`) REFERENCES `up_spec_type` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_spec_template`
--

LOCK TABLES `up_spec_template` WRITE;
/*!40000 ALTER TABLE `up_spec_template` DISABLE KEYS */;
/*!40000 ALTER TABLE `up_spec_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_spec_type`
--

DROP TABLE IF EXISTS `up_spec_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_spec_type` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAME` varchar(150) NOT NULL,
  `SPEC_CATEGORY_ID` int NOT NULL,
  `DISPLAY_ORDER` int DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `up_spec_type_pk` (`NAME`,`SPEC_CATEGORY_ID`),
  KEY `FK_ST_SC` (`SPEC_CATEGORY_ID`),
  CONSTRAINT `up_spec_type_ibfk_1` FOREIGN KEY (`SPEC_CATEGORY_ID`) REFERENCES `up_spec_category` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_spec_type`
--

LOCK TABLES `up_spec_type` WRITE;
/*!40000 ALTER TABLE `up_spec_type` DISABLE KEYS */;
INSERT INTO `up_spec_type` (`ID`, `NAME`, `SPEC_CATEGORY_ID`, `DISPLAY_ORDER`) VALUES (1,'Гарантия',1,1),(2,'Страна-производитель',1,0),(3,'Объем видеопамяти',4,1),(4,'Тип памяти',4,0),(5,'Частота памяти',4,0),(6,'Тип',3,1),(7,'Год релиза',3,2),(8,'Длина',2,1),(9,'Толщина',2,2),(10,'Подсветка',2,3);
/*!40000 ALTER TABLE `up_spec_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_tag`
--

DROP TABLE IF EXISTS `up_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_tag` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `TITLE` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `up_tag_TITLE_uindex` (`TITLE`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_tag`
--

LOCK TABLES `up_tag` WRITE;
/*!40000 ALTER TABLE `up_tag` DISABLE KEYS */;
INSERT INTO `up_tag` (`ID`, `TITLE`) VALUES (14,'GTX 10 серии'),(12,'RTX 20 серии'),(13,'RTX 30 серии'),(8,'Бесшумные'),(11,'Бюджетная'),(6,'Видеокарты AMD'),(7,'Видеокарты NVidia'),(4,'Для игр в 2К'),(5,'Для игр в 4К'),(3,'Для игр в Full HD'),(1,'Игровые'),(9,'Компактные'),(10,'Офисные'),(2,'Топовые');
/*!40000 ALTER TABLE `up_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `up_user`
--

DROP TABLE IF EXISTS `up_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `up_user` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `LOGIN` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PASSWORD` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ROLE_ID` int DEFAULT NULL,
  `EMAIL` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `PHONE` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `up_user`
--

LOCK TABLES `up_user` WRITE;
/*!40000 ALTER TABLE `up_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `up_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-02-11 20:13:48
