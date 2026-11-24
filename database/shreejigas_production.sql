-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: shreejigas
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (13,'Shree Ji Administrator','shreeji-admin','shreejigasteam@gmail.com','$2y$10$nNvHvlreH4f1LJL6Exj8UOYttXdqdpXhHLRod0gcsQdGLzBNE/bFi','2026-10-10 12:28:42','2026-10-03 21:31:34');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_categories`
--

LOCK TABLES `blog_categories` WRITE;
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
INSERT INTO `blog_categories` VALUES (1,'Equipment planning','equipment-planning',0);
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `thumbnail` text DEFAULT NULL,
  `image_alt` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `is_featured` int(11) DEFAULT 0,
  `status` varchar(30) DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_posts`
--

LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;
INSERT INTO `blog_posts` VALUES (1,1,'A better brief makes a better equipment enquiry','a-better-equipment-enquiry','A few useful details can help our team understand your equipment requirements.','<h2>Start with your requirements</h2><p>Tell your supplier about the setting, the type of equipment you are looking for and the quantity you need. Include any model references or specifications provided by your clinical or technical team.</p><h2>Confirm the details</h2><p>Ask about available configurations, compatible accessories, documentation and lead times. Request written confirmation of the exact model and specifications before placing an order.</p><h2>Plan for ongoing support</h2><p>Discuss delivery arrangements, manufacturer instructions and the support available after supply. For clinical decisions or operating instructions, consult a qualified professional and the manufacturer’s documentation.</p><p><a href=\"/shreejigas/contact\">Contact our team</a> to discuss your requirements.</p>','assets/images/hospital.jpg','Healthcare facility equipment planning and clinical consultation','Shree Ji Gas Team','Equipment,Planning',1,'published','2026-10-02 23:10:52','2026-10-02 17:40:52'),(2,1,'Choosing equipment for your clinical workspace','planning-your-clinical-workspace','Start with your setting, the people using it and the support you need.','<h2>Start with your requirements</h2><p>Tell your supplier about the setting, the type of equipment you are looking for and the quantity you need. Include any model references or specifications provided by your clinical or technical team.</p><h2>Confirm the details</h2><p>Ask about available configurations, compatible accessories, documentation and lead times. Request written confirmation of the exact model and specifications before placing an order.</p><h2>Plan for ongoing support</h2><p>Discuss delivery arrangements, manufacturer instructions and the support available after supply. For clinical decisions or operating instructions, consult a qualified professional and the manufacturer’s documentation.</p><p><a href=\"/shreejigas/contact\">Contact our team</a> to discuss your requirements.</p>','assets/images/surgical-theatre.jpg','Clinical workspace and modern surgical suite equipment','Shree Ji Gas Team','Equipment,Planning',1,'published','2026-10-02 23:10:52','2026-10-02 17:40:52'),(3,1,'What to ask before requesting a product quote','before-requesting-a-product-quote','A practical checklist for a more useful conversation with your supplier.','<h2>Start with your requirements</h2><p>Tell your supplier about the setting, the type of equipment you are looking for and the quantity you need. Include any model references or specifications provided by your clinical or technical team.</p><h2>Confirm the details</h2><p>Ask about available configurations, compatible accessories, documentation and lead times. Request written confirmation of the exact model and specifications before placing an order.</p><h2>Plan for ongoing support</h2><p>Discuss delivery arrangements, manufacturer instructions and the support available after supply. For clinical decisions or operating instructions, consult a qualified professional and the manufacturer’s documentation.</p><p><a href=\"/shreejigas/contact\">Contact our team</a> to discuss your requirements.</p>','assets/images/homecare-oxygen.jpg','Medical oxygen equipment consultation and bedside setup','Shree Ji Gas Team','Equipment,Planning',1,'published','2026-10-02 23:10:52','2026-10-02 17:40:52'),(4,NULL,'Oxygen Safety Standards in UP Hospitals','oxygen-safety-standards-in-up-hospitals','Critical equipment guidelines for hospital medical oxygen manifold installations.','<p>&lt;h2&gt;Standards &amp; Best Practices&lt;/h2&gt;&lt;p&gt;Always verify PESO hydro-testing stamps on high pressure cylinders before connection.&lt;/p&gt;</p>','assets/images/manifold.jpg','Hospital medical oxygen manifold installation and pipeline inspection','Clinical Engineering Team','',0,'published','2026-10-08 23:34:00','2026-10-08 23:34:52'),(6,NULL,'Oxygen Safety Protocol 3696','oxygen-safety-protocol-3696','Critical equipment guidelines for hospital medical oxygen manifold installations.','<p>Oxygen is essential for human life, and in many medical situations, additional oxygen may be required to support breathing. An <strong>oxygen cylinder</strong> is a medical device used to store and supply oxygen to a patient when their body needs extra oxygen.</p><h2>What is an Oxygen Cylinder?</h2><p>An oxygen cylinder is a strong metal container designed to store oxygen under high pressure. It is connected to a regulator, which controls the flow of oxygen before it reaches the patient through a mask or nasal cannula.</p><p>Oxygen cylinders are commonly used in hospitals, clinics, ambulances, healthcare centers, and sometimes at home under medical supervision.</p><h2>Common Uses of Oxygen Cylinders</h2><p>Oxygen cylinders can be used in different medical situations, including:</p><ol><li><span class=\"ql-ui\"></span>Supporting patients who have difficulty breathing</li><li><span class=\"ql-ui\"></span>Emergency medical situations</li><li><span class=\"ql-ui\"></span>Ambulance and patient transportation</li><li><span class=\"ql-ui\"></span>During certain medical procedures</li><li><span class=\"ql-ui\"></span>Providing oxygen therapy at home when prescribed by a doctor</li><li><span class=\"ql-ui\"></span>Supporting patients with low blood oxygen levels</li></ol><h2>Types of Oxygen Cylinders</h2><p>Oxygen cylinders are available in different sizes according to their intended use.</p><h3>1. Portable Oxygen Cylinders</h3><p>These are smaller and easier to carry. They are useful for emergency transportation and patients who require oxygen outside the hospital.</p><h3>2. Large Oxygen Cylinders</h3><p>Large cylinders contain more oxygen and are generally used in hospitals, clinics, and other healthcare facilities where a continuous oxygen supply is required.</p><h3>3. Medical Oxygen Cylinders</h3><p>These cylinders are specifically prepared and supplied for medical oxygen therapy and should be handled according to medical and safety standards.</p><h2>Important Safety Tips</h2><p>Oxygen supports combustion, so it must be handled carefully.</p><ol><li><span class=\"ql-ui\"></span>Keep the cylinder away from fire and heat sources.</li><li><span class=\"ql-ui\"></span>Do not smoke near an oxygen cylinder.</li><li><span class=\"ql-ui\"></span>Keep the cylinder upright and properly secured.</li><li><span class=\"ql-ui\"></span>Use the correct regulator and compatible equipment.</li><li><span class=\"ql-ui\"></span>Never modify or repair the cylinder yourself.</li><li><span class=\"ql-ui\"></span>Check for leaks or damaged equipment before use.</li><li><span class=\"ql-ui\"></span>Store cylinders in a clean, dry and well-ventilated area.</li><li><span class=\"ql-ui\"></span>Use oxygen only according to a healthcare professional\'s instructions.</li></ol><h2>How Long Does an Oxygen Cylinder Last?</h2><p>The duration depends on the <strong>cylinder size, pressure inside the cylinder, and oxygen flow rate</strong>. A higher flow rate will generally make the oxygen supply run out faster.</p><p>For this reason, patients using oxygen therapy should monitor the cylinder pressure regularly and arrange a replacement or refill before the supply becomes critically low.</p><h2>Conclusion</h2><p>An oxygen cylinder can be an important part of medical oxygen therapy, particularly when a patient requires supplemental oxygen. However, oxygen should not be used without proper medical guidance. Correct storage, handling, equipment, and prescribed flow rates are essential for safe use.</p><p>If oxygen therapy has been recommended for a patient, always follow the instructions provided by their doctor or healthcare professional.</p>','assets/images/manifold.jpg','Certified oxygen manifold safety protocol and dual bank regulator setup','Clinical Engineering Team','',0,'published','2026-10-08 23:36:00','2026-10-08 23:36:24');
/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `case_studies`
--

DROP TABLE IF EXISTS `case_studies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `case_studies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `challenge` text DEFAULT NULL,
  `solution` text DEFAULT NULL,
  `results` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`results`)),
  `products_used` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`products_used`)),
  `thumbnail` text DEFAULT NULL,
  `image_alt` varchar(255) DEFAULT NULL,
  `status` varchar(30) DEFAULT 'draft',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case_studies`
--

LOCK TABLES `case_studies` WRITE;
/*!40000 ALTER TABLE `case_studies` DISABLE KEYS */;
INSERT INTO `case_studies` VALUES (1,'Complete Oxygen Supply Setup for Agra Multispecialty Hospital','oxygen-supply-agra-multispecialty-hospital','Agra Multispecialty Hospital','Healthcare','Agra Multispecialty Hospital was expanding its ICU and general wards but lacked a reliable, centralized medical oxygen supply system. The existing cylinder-based setup led to frequent supply interruptions, higher operational costs, and safety concerns during peak patient loads. The hospital needed a dependable partner who could design, deliver, and maintain a complete oxygen infrastructure within a tight timeline.','Shree Ji Gas Industries conducted a thorough site assessment and designed a tailored oxygen distribution plan. We supplied high-capacity medical oxygen cylinders, installed flow meters and humidifiers across 40 beds, and provided medical gas regulators for the ICU. Our team also set up a cylinder bank with automatic changeover to ensure uninterrupted supply. Post-installation training was provided to nursing staff for safe handling and monitoring.','{\"40+ beds\":\"Equipped with oxygen points\",\"99.8%\":\"Uptime achieved in first year\",\"35%\":\"Cost reduction vs previous setup\"}','[1,3,5]','assets/images/manifold.jpg','Central oxygen manifold and pipeline installation at Agra multispecialty hospital','published'),(2,'Surgical Instrument Upgrade for District Health Centre','surgical-instrument-upgrade-district-health-centre','District Health Centre, Firozabad','Government Healthcare','The District Health Centre in Firozabad was operating with outdated and worn surgical instruments, creating risks during minor procedures and emergency surgeries. Budget constraints and limited supplier access in the region made procurement challenging. The centre needed a cost-effective solution that met quality standards while fitting within government procurement guidelines.','We worked closely with the procurement committee to curate a comprehensive surgical instrument package. Our stainless steel surgical kits and instrument trays were supplied with full documentation for government audit compliance. Each instrument was individually inspected, sterilization-tested, and delivered with warranty documentation. We also provided replacement schedules and maintenance guidance to extend instrument lifespan.','{\"120+\":\"Instruments supplied\",\"3 OTs\":\"Fully re-equipped\",\"Zero defects\":\"In quality inspection\"}','[2,6]','assets/images/surgical-theatre.jpg','Surgical instrument upgrade and operating theatre suite at District Health Centre','published'),(3,'Emergency Portable Oxygen Deployment for Rural Clinic Network','portable-oxygen-rural-clinic-network','Rural Health Initiative, Mathura','Community Healthcare','A network of 8 rural clinics across Mathura district had no access to medical oxygen, critically limiting their ability to stabilize patients before hospital transfer. During seasonal respiratory illness surges, patients often deteriorated during transit. The initiative needed portable, easy-to-use oxygen solutions that could be maintained by minimally trained staff across remote locations.','Shree Ji Gas Industries designed a portable oxygen kit solution specifically for low-resource settings. Each clinic received portable oxygen cylinder kits with integrated regulators and carrying cases, along with simplified operating guides in Hindi. We established a cylinder exchange and refill route covering all 8 locations with guaranteed 48-hour turnaround. Quarterly maintenance visits ensure equipment reliability.','{\"8 clinics\":\"Now oxygen-equipped\",\"200+\":\"Patients stabilized in Year 1\",\"48-hour\":\"Cylinder refill guarantee\"}','[1,4,5]','assets/images/rural-clinic.jpg','Emergency portable oxygen cylinder setup at rural clinic network','published');
/*!40000 ALTER TABLE `case_studies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(100) DEFAULT 'first-aid-kit-line',
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,'Medical Gases','medical-gases','lungs-line','Certified Medical Oxygen and Clinical Respiratory Gas Supplies',1),(2,NULL,'Industrial Gases','industrial-gases','fire-line','Nitrogen, Argon, Carbon Dioxide & Acetylene for Manufacturing',2),(3,NULL,'Specialty & Pure Gases','specialty-gases','flask-line','Helium, Hydrogen, Zero Air & High-Purity Calibration Gases',3),(4,NULL,'Gas Equipment & Cylinders','gas-equipment','dashboard-3-line','PESO Certified High-Pressure Cylinders, Regulators & Manifolds',4);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_logos`
--

DROP TABLE IF EXISTS `client_logos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client_logos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `logo_path` text DEFAULT NULL,
  `image_alt` varchar(255) DEFAULT NULL,
  `website_url` text DEFAULT NULL,
  `is_active` int(11) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_logos`
--

LOCK TABLES `client_logos` WRITE;
/*!40000 ALTER TABLE `client_logos` DISABLE KEYS */;
/*!40000 ALTER TABLE `client_logos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enquiries`
--

DROP TABLE IF EXISTS `enquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enquiries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `product` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` varchar(30) DEFAULT 'new',
  `email_sent` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enquiries`
--

LOCK TABLES `enquiries` WRITE;
/*!40000 ALTER TABLE `enquiries` DISABLE KEYS */;
INSERT INTO `enquiries` VALUES (1,'Milind Ratan Saugat','+919411404446','milindsaugat1122@gmail.com','Medical Oxygen Cylinder','ssfdfsadfdsfdsfsfdsff','new',1,'2026-10-09 22:09:08'),(2,'Milind Ratan Saugat','+919411404446','milindsaugat1122@gmail.com','Medical Oxygen Cylinder','Testing medical oxygen cylinder supply enquiry.','new',1,'2026-10-09 22:14:41'),(3,'Milind Ratan Saugat','+919411404446','milindsaugat1122@gmail.com','Medical Oxygen Cylinder','Testing medical oxygen cylinder supply enquiry.','new',1,'2026-10-09 22:15:53'),(4,'Milind Ratan Saugat','+919411404446','milindsaugat1122@gmail.com','Medical Oxygen Cylinder','Testing medical oxygen cylinder supply enquiry.','new',1,'2026-10-09 22:16:35'),(5,'Milind Ratan Saugat','+919411404446','milindsaugat1122@gmail.com','Stainless Steel Surgical Kit','dbcjhdvcdsvcvdsh','new',1,'2026-10-09 22:19:50'),(6,'Milind Ratan Saugat','+919411404446','milindsaugat1122@gmail.com','Medical Oxygen Cylinder','Testing medical oxygen cylinder supply enquiry.','new',1,'2026-10-09 22:27:56'),(7,'Milind Ratan Saugat','+919411404446','milindsaugat1122@gmail.com','Medical Oxygen Cylinder','Testing medical oxygen cylinder supply enquiry.','new',1,'2026-10-09 22:43:05'),(8,'Milind Ratan Saugat','+919411404446','milindsaugat1122@gmail.com','Stainless Steel Surgical Kit','vxcuyvasvascbcvcuydsdy','new',1,'2026-10-09 22:49:37'),(9,'Milind Ratan Saugat','+919411404446','milindsaugat1122@gmail.com','Portable Oxygen Cylinder Kit','fhgjuifytdrtzdgxfh','new',1,'2026-10-09 23:23:19'),(10,'Milind Ratan Saugat','+919411404446','milindsaugat1122@gmail.com','','vdvdewgggrdfvrggrggrg','new',1,'2026-10-10 11:07:23');
/*!40000 ALTER TABLE `enquiries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `path` text DEFAULT NULL,
  `image_alt` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (29,'manifold.jpg','manifold.jpg','assets/images/manifold.jpg','Hospital oxygen manifold installation with dual cylinder banks and pressure regulators',795723,'image/jpeg','2026-10-08 23:47:39'),(30,'rural-clinic.jpg','rural-clinic.jpg','assets/images/rural-clinic.jpg','Portable medical oxygen cylinder trolley at rural healthcare clinic',895763,'image/jpeg','2026-10-08 23:47:39'),(31,'surgical-theatre.jpg','surgical-theatre.jpg','assets/images/surgical-theatre.jpg','Modern surgical operating theatre suite with precision instruments',837325,'image/jpeg','2026-10-08 23:47:39'),(32,'homecare-oxygen.jpg','homecare-oxygen.jpg','assets/images/homecare-oxygen.jpg','Supervised home care medical oxygen cylinder setup with humidifier',767919,'image/jpeg','2026-10-08 23:47:39'),(33,'hospital.jpg','hospital.jpg','assets/images/hospital.jpg','Modern healthcare hospital corridor and clinical care facility',134312,'image/jpeg','2026-10-08 23:47:39'),(34,'surgery.jpg','surgery.jpg','assets/images/surgery.jpg','Clinical surgical instruments and theatre illumination',134618,'image/jpeg','2026-10-08 23:47:39'),(35,'homecare.jpg','homecare.jpg','assets/images/homecare.jpg','Healthcare professional consultation',82016,'image/jpeg','2026-10-08 23:47:39'),(36,'facility.jpg','facility.jpg','assets/images/facility.jpg','Shree Ji Gas Industries Agra depot logistics infrastructure',1026377,'image/jpeg','2026-10-08 23:47:39'),(37,'oxygen.webp','oxygen.webp','assets/images/oxygen.webp','Medical oxygen cylinder with safety valve',39536,'image/webp','2026-10-08 23:47:39'),(38,'regulator.webp','regulator.webp','assets/images/regulator.webp','Medical gas pressure regulator and flowmeter',53122,'image/webp','2026-10-08 23:47:39'),(39,'surgical.webp','surgical.webp','assets/images/surgical.webp','Precision stainless steel surgical instrument set',116584,'image/webp','2026-10-08 23:47:39'),(40,'equipment.webp','equipment.webp','assets/images/equipment.webp','Medical gas equipment and cylinder accessories catalog visual',53920,'image/webp','2026-10-08 23:47:39'),(41,'testimonial-dr-rajesh.jpg','testimonial-dr-rajesh.jpg','assets/images/testimonial-dr-rajesh.jpg','Dr. Rajesh Sharma, Medical Director portrait',657159,'image/jpeg','2026-10-09 00:07:42'),(42,'testimonial-dr-meenakshi.jpg','testimonial-dr-meenakshi.jpg','assets/images/testimonial-dr-meenakshi.jpg','Dr. Meenakshi Verma, Head of Surgery portrait',623659,'image/jpeg','2026-10-09 00:07:42'),(43,'testimonial-er-vikram.jpg','testimonial-er-vikram.jpg','assets/images/testimonial-er-vikram.jpg','Er. Vikramaditya Singh, Biomedical Engineering In-charge portrait',679905,'image/jpeg','2026-10-09 00:07:43'),(44,'testimonial-dr-sanjay.jpg','testimonial-dr-sanjay.jpg','assets/images/testimonial-dr-sanjay.jpg','Dr. Sanjay Gupta, Senior Consultant portrait',665389,'image/jpeg','2026-10-09 00:07:43');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `thumbnail` text DEFAULT NULL,
  `image_alt` varchar(255) DEFAULT NULL,
  `status` varchar(30) DEFAULT 'published',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Care begins with the right partner.','about','We’re Shree Ji Gas Industries. Your local connection to medical, surgical and gas equipment in Agra.','<h2>Here for the people who care</h2><p>Based at 33/103, Pratappura, Agra, Shree Ji Gas Industries supplies medical oxygen equipment, surgical instruments, gas accessories and hospital essentials.</p><p>We believe equipment sourcing should begin with a conversation. We take time to understand your needs and help you explore suitable options.</p><h2>A practical, personal approach</h2><p>Whether you’re planning equipment for a hospital, a clinic or supervised home care, speak to our team about available products, configurations and support.</p>','assets/images/hospital.jpg','Hospital care environment','published'),(3,'Your privacy matters.','privacy','How we handle information you share with us.','<h2>Information you provide</h2><p>When you submit an enquiry, we store your name, phone number, optional email address, product interest and message so our team can respond.</p><h2>How we use it</h2><p>Your enquiry is used for responding to your requirements and maintaining our enquiry records. It is not sold. Contact shreejigasteam@gmail.com to request access, correction or deletion.</p><h2>Cookies and external services</h2><p>This website uses an essential session cookie for form security and administrator sign-in. Google Fonts and CDN-hosted libraries may receive basic connection information. Maps load only when you choose to load the map. WhatsApp opens only when you choose its contact option.</p>',NULL,NULL,'published'),(4,'Terms & Conditions of Gas Supply & Equipment Services','terms','Official terms governing cylinder allocation, equipment sourcing, clinical safety protocols, and commercial transactions with Shree Ji Gas Industries.','<h2>1. Scope of Supply & Engagement</h2>\n<p>These terms and conditions govern all equipment purchases, medical and industrial gas cylinder allocations, refilling arrangements, and manifold technical services provided by <strong>Shree Ji Gas Industries</strong> (registered office: 33/103, Pratappura, Agra, Uttar Pradesh).</p>\n<p>By placing an enquiry, issuing a purchase order, or accepting cylinder delivery, healthcare institutions, clinics, and industrial clients agree to be bound by these operating terms.</p>\n\n<h2>2. Medical Gas Purity & Statutory Standards</h2>\n<p>All medical oxygen cylinders supplied comply with the standards prescribed under the <em>Drugs & Cosmetics Act</em> and <em>Indian Pharmacopoeia (IP)</em> standards for medical-grade oxygen (minimum 99.5% purity). Every cylinder undergoes valve inspection, pressure validation, and statutory tare-weight verification before dispatch.</p>\n\n<h2>3. Cylinder Handling, Ownership & Security Deposit</h2>\n<ul>\n    <li><strong>Cylinder Ownership:</strong> Unless explicitly purchased as outright equipment, all high-pressure gas cylinders remain the sole property of Shree Ji Gas Industries.</li>\n    <li><strong>Holding Period:</strong> Cylinders are provided on a refundable security deposit or rental allocation basis for clinical rotation.</li>\n    <li><strong>Return of Empties:</strong> Empty cylinders must be returned in sound physical condition, with valves completely closed and protective safety caps intact.</li>\n    <li><strong>Damage or Loss:</strong> Any physical damage to valves, spindle rounding, contamination, or total loss of cylinder shell will result in forfeiture of deposit or invoice at prevailing replacement costs.</li>\n</ul>\n\n<h2>4. Clinical Application & Medical Supervision</h2>\n<p>Oxygen therapy and medical gas equipment must strictly be administered under the instruction and supervision of qualified medical practitioners. Shree Ji Gas Industries supplies certified equipment and gas volume but does not provide clinical diagnosis, patient prescription, or direct clinical treatment advice.</p>\n\n<h2>5. Payment Terms & Invoicing</h2>\n<p>Invoices are issued upon dispatch or contract cycle. Payment terms for institutional accounts are net 15 days unless otherwise agreed in writing. For retail and urgent emergency refills, payment is due upon delivery via approved UPI, NEFT, or bank transfer.</p>\n\n<h2>6. Jurisdiction & Dispute Resolution</h2>\n<p>Any disputes arising out of contracts, deliveries, or supply agreements with Shree Ji Gas Industries shall be subject to the exclusive jurisdiction of the competent courts in <strong>Agra, Uttar Pradesh</strong>.</p>','assets/images/hospital.jpg','Shree Ji Gas Industries facility and equipment terms','published'),(5,'Safety & Medical Gas Regulatory Compliance','compliance','Our strict adherence to Petroleum & Explosives Safety Organisation (PESO) rules, Indian Pharmacopoeia standards, and hospital manifold safety guidelines.','<h2>1. Regulatory Framework & Statutory Approvals</h2>\n<p>High-pressure medical and industrial gases require rigorous regulatory governance. Shree Ji Gas Industries operates in strict compliance with the following statutory frameworks:</p>\n<ul>\n    <li><strong>Gas Cylinders Rules, 2016</strong> administered by the <em>Petroleum and Explosives Safety Organisation (PESO)</em>, Ministry of Commerce and Industry, Government of India.</li>\n    <li><strong>Static & Mobile Pressure Vessels (SMPV) Rules</strong> for bulk storage and manifold decanting safety.</li>\n    <li><strong>Indian Pharmacopoeia (IP) / Bureau of Indian Standards (BIS)</strong> specifications for medical gases and valve fittings (IS:3224).</li>\n</ul>\n\n<h2>2. Mandatory Hydro-Static Testing (5-Year Cycle)</h2>\n<p>Under PESO mandate, every seamless steel cylinder must undergo statutory hydrostatic stretch testing every five (5) years at an authorized testing station. Shree Ji Gas Industries maintains an active digital hydro-testing register:</p>\n<ul>\n    <li>Every dispatched cylinder features a clear neck-ring stamp showing the last test date and due expiry date.</li>\n    <li>Cylinders due for hydro-testing are systematically quarantined and routed to testing facilities before any refilling cycle.</li>\n    <li>Batch test certificates and burst-pressure compliance test reports are available on request for institutional clinical audits.</li>\n</ul>\n\n<h2>3. Standard Colour Coding & Pin-Index Compatibility</h2>\n<p>To eliminate clinical hazard or cross-contamination risk, all cylinders strictly adhere to statutory colour coding:</p>\n<ul>\n    <li><strong>Medical Oxygen:</strong> Black body with brilliant White shoulder.</li>\n    <li><strong>Nitrous Oxide:</strong> French Blue body and shoulder.</li>\n    <li><strong>Carbon Dioxide:</strong> Black body with Silver shoulder.</li>\n    <li><strong>Medical Air:</strong> French Grey body with White and Black quartered shoulder.</li>\n</ul>\n<p>Pin-index and valve outlets conform strictly to IS:3224 standards to prevent incorrect gas regulator coupling.</p>\n\n<h2>4. Emergency Hospital Manifold Protocols</h2>\n<p>For healthcare institutions with central pipeline manifold installations, our technical safety team provides scheduled leak audits, dual-bank changeover switch inspections, line pressure drop assessments, and emergency buffer storage planning.</p>','assets/images/manifold.jpg','Central medical gas manifold regulatory safety installation','published'),(6,'Emergency Delivery, Safe Transport & Cylinder Deposit Policy','shipping-policy','Fast-response hospital oxygen delivery across Agra & Western UP, statutory hazardous cargo logistics, and transparent cylinder return guidelines.','<h2>1. 24/7 Priority Emergency Hospital Dispatch</h2>\n<p>Critical care healthcare cannot wait. Shree Ji Gas Industries maintains a dedicated <strong>24/7 Priority Dispatch Desk</strong> for hospitals, ICUs, and nursing homes across Agra, Mathura, Firozabad, and Western Uttar Pradesh:</p>\n<ul>\n    <li><strong>Emergency Hospital Deliveries:</strong> Dispatched within 45 to 90 minutes of receiving distress alert or ward requisition.</li>\n    <li><strong>Scheduled Ward Replenishments:</strong> Daily morning and evening scheduled delivery routes for contracted facilities.</li>\n    <li><strong>Home Care Support:</strong> Same-day doorstep delivery across Agra municipal limits for doctor-prescribed patient setups.</li>\n</ul>\n\n<h2>2. Hazardous Material Transport Protocols</h2>\n<p>Compressed gas cylinders are classified as hazardous cargo. All Shree Ji Gas transport vehicles adhere to strict transport safety norms:</p>\n<ul>\n    <li>Vehicles are well-ventilated, equipped with certified fire suppression extinguishers, and operated by HAZMAT-trained drivers.</li>\n    <li>Cylinders are transported upright, securely chained or slotted in custom delivery racks to prevent collision during transit.</li>\n    <li>Valves remain fitted with protective screw caps throughout the journey until handover at the hospital manifold room.</li>\n</ul>\n\n<h2>3. Handover & Delivery Verification Protocol</h2>\n<p>Upon delivery, the receiving hospital biomedical engineer or facility representative must inspect:</p>\n<ul>\n    <li>Valve seal and tamper-evident cap integrity.</li>\n    <li>Static pressure reading on the test regulator gauge.</li>\n    <li>Tare weight vs. filled weight on delivery challan.</li>\n</ul>\n\n<h2>4. Cylinder Security Deposit & Refund Process</h2>\n<p>For institutions and individuals utilizing our rotating cylinder stock:</p>\n<ul>\n    <li>A refundable security deposit is collected per cylinder at the time of initial issue.</li>\n    <li>Upon conclusion of supply requirement and return of sound, undamaged cylinder shell with original valve intact, 100% of the security deposit is refunded within 2 business days via original payment mode or bank transfer.</li>\n    <li>A digital return receipt is issued immediately upon physical pickup by our delivery crew.</li>\n</ul>','assets/images/homecare-oxygen.jpg','Shree Ji Gas delivery and mobile cylinder dispatch','published');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `token_hash` varchar(128) NOT NULL,
  `otp_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `attempts` int(11) DEFAULT 0,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_hash` (`token_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_desc` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `specs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specs`)),
  `tags` text DEFAULT NULL,
  `thumbnail` text DEFAULT NULL,
  `image_alt` varchar(255) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `brochure_pdf` text DEFAULT NULL,
  `applications` text DEFAULT NULL,
  `faqs` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `is_featured` int(11) DEFAULT 0,
  `badge_label` varchar(100) DEFAULT NULL,
  `badge_type` varchar(40) DEFAULT NULL,
  `mfc_date` varchar(100) DEFAULT NULL,
  `exp_date` varchar(100) DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `status` varchar(30) DEFAULT 'draft',
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'Oxygen Gas','oxygen-gas','High-purity medical and industrial oxygen gas in PESO-certified high-pressure cylinders and bulk supply for healthcare facilities, metal fabrication, and industrial processes.','<h2>High-Purity Oxygen Gas for Medical & Industrial Applications</h2><p>Oxygen Gas is an essential element across critical healthcare environments, metal cutting and welding, chemical synthesis, and water treatment. Sourced and processed under stringent quality standards, our Oxygen Gas complies with Indian Pharmacopoeia (IP/BP) standards for medical usage and commercial specifications for industrial cutting, brazing, and manufacturing.</p><p>Available in a full range of high-pressure seamless steel cylinders (10L portable to 47L bulk jumbo) hydrostatically tested to 250 bar with statutory PESO certification. All batches undergo rigorous gas chromatography purity testing to guarantee 99.5% to 99.999% clinical and industrial grade purity.</p>','{\"Purity\":\"99.0% - 99.999% (Clinical & Industrial Grade)\",\"Standard\":\"IS:7285 / IS:3224 / IP Grade\",\"Physical State\":\"Compressed Gas\",\"Cylinder Pressure\":\"150 bar (Working) / 250 bar (Test)\",\"Water Capacity\":\"10L / 47L D-Type Bulk / Liquid Cryogenic\",\"Valve Type\":\"High-Pressure Brass Pin-Index / Bullnose (IS:3224)\",\"Grade\":\"Medical Grade / Industrial Grade / Beverage Grade\",\"Supply Mode\":\"Cylinders, Quads, Manifold Banks & Bulk Tankers\"}','Oxygen gas, Medical oxygen, Industrial oxygen, PESO certified cylinder, Hospital gas supply, Agra oxygen supplier','assets/images/products/oxygen-gas.jpg','Oxygen Gas Cylinder — High Purity Medical and Industrial Supply','[]','','<p>Hospitals & Critical Care (ICU, OT, Ambulances), Metal Cutting & Welding, Steel & Glass Manufacturing, Chemical Oxidation, Wastewater Treatment, and Aquaculture.</p>',NULL,'<ul><li><strong>Clinical Purity (99.5%+):</strong> Strictly adheres to Indian Pharmacopoeia standards for ICU, ventilator support, and anesthesia.</li><li><strong>Industrial Efficiency:</strong> Delivers rapid preheating and sharp flame cutting when combined with fuel gases like Acetylene or LPG.</li><li><strong>Certified Cylinders:</strong> Permanent neck collar date stamping with statutory 5-year PESO hydraulic stretch re-test.</li><li><strong>Seamless Integration:</strong> Compatible with hospital central MGPS manifolds, bullnose regulators, and portable kits.</li></ul>',1,'Bestseller · PESO Certified','popular','Current Production Batch','5-Yr PESO Hydro-Test Cycle','oxygen gas, medical oxygen cylinder, industrial oxygen gas, Agra oxygen supplier, high purity oxygen 99.9%','published',1,'2026-11-24 23:31:57'),(2,2,'Nitrogen Gas','nitrogen-gas','High-purity compressed nitrogen gas for industrial inerting, pharmaceutical blanketing, food packaging, chemical processing, laser cutting, and tyre inflation.','<h2>High-Purity Inert Nitrogen Gas for Industrial & Commercial Use</h2><p>Nitrogen Gas is a dry, non-reactive inert gas widely utilized across food packaging, pharmaceutical synthesis, electronics manufacturing, laser cutting, and heat treatment. It prevents oxidation, moisture contamination, and combustion in critical process environments.</p><p>We supply commercial grade, ultra-high purity (UHP), and zero-grade nitrogen in standard 47-liter high-pressure cylinders (150 bar to 200 bar working pressure) and manifold cylinder banks with prompt delivery across Agra and Western UP.</p>','{\"Purity\":\"99.5% - 99.999% (Ultra-High Purity Grade)\",\"Density\":\"0.500 - 1.000 g/ml (Gas)\",\"Working Pressure\":\"150 kg/cm² to 300 kg/cm² (150 - 200 bar)\",\"Moisture Content\":\"< 5 ppm (Dry Gas)\",\"Packaging Type\":\"High-Pressure Seamless Steel Cylinder\",\"Water Capacity\":\"47 Litre / 50 Litre Jumbo\",\"Valve Standard\":\"IS:3224 Right-Hand External Thread\",\"Supply Mode\":\"Cylinders, Manifold Quads & Liquid Dewars\"}','Nitrogen gas, Industrial nitrogen, UHP nitrogen, Food grade nitrogen, Blanketing gas, Laser cutting nitrogen','assets/images/products/nitrogen-gas.jpg','Nitrogen Gas Cylinder — Industrial and Laboratory High Purity Supply','[]','','<p>Food & Beverage Packaging (MAP), Pharmaceutical Blanketing, Laser Cutting, Heat Treatment & Metallurgy, Tyre Inflation, Chemical Plant Purging, and Electronics Assembly.</p>',NULL,'<ul><li><strong>Complete Inertness:</strong> Prevents rapid oxidation, combustion, and microbial deterioration in sealed environments.</li><li><strong>Ultra-Dry (Moisture < 5 ppm):</strong> Ideal for purging refrigeration systems, electrical transformers, and pipelines.</li><li><strong>Laser & Plasma Cutting:</strong> Yields oxide-free, burr-free cuts on stainless steel and aluminum components.</li><li><strong>Modified Atmosphere Packaging (MAP):</strong> Extends shelf-life of snacks, dry foods, and pharmaceutical blister packs.</li></ul>',1,'High Purity 99.99%','verified','Current Production Batch','5-Yr PESO Hydro-Test Cycle','nitrogen gas, nitrogen cylinder Agra, industrial nitrogen supplier, UHP nitrogen gas, food grade nitrogen','published',2,'2026-11-24 23:31:57'),(3,2,'Argon Gas','argon-gas','Premium shielding Argon Gas for TIG/MIG welding, specialty metallurgy, insulating glass window filling, and precision analytical laboratory instrumentation.','<h2>Ultra-Pure Shielding Argon Gas for Precision Welding & Fabrication</h2><p>Argon is an inert, noble gas indispensable for high-integrity GTAW (TIG) and GMAW (MIG) welding of stainless steel, titanium, aluminum, and nickel alloys. Its complete non-reactivity shields molten weld pools from atmospheric oxygen, nitrogen, and moisture, preventing porosity and slag inclusion.</p><p>Also used in double-glazed insulating glass units for superior thermal efficiency and sound insulation, as well as optical emission spectrometers (OES) in metallurgical laboratories.</p>','{\"Purity\":\"99.99% - 99.999% (Welding & Grade-1 Analytical)\",\"Working Pressure\":\"150 kg/cm² (15 MPa)\",\"State\":\"Compressed Gas\",\"Molecular Weight\":\"39.948 g/mol\",\"Packaging Type\":\"PESO Certified Seamless High-Pressure Cylinder\",\"Water Capacity\":\"47 Litre / 7 Cubic Metre Gas Volume\",\"Valve Outlet\":\"IS:3224 Female / Bullnose (Left-Hand Thread)\",\"Supply Ability\":\"Bulk Fleet & Ready Inventory in Agra\"}','Argon gas, TIG welding gas, MIG shielding gas, Insulating glass gas, Purity 99.99%, Agra argon supplier','assets/images/products/argon-gas.jpg','Argon Gas Cylinder — Premium TIG MIG Shielding Gas','[]','','<p>TIG / MIG Precision Welding, Stainless Steel & Aluminum Fabrication, Double-Glazed Insulating Glass, Spectrometry & Lab Testing, Titanium Casting, and Electronics.</p>',NULL,'<ul><li><strong>Flawless Weld Shielding:</strong> Eliminates weld puddle spatter, porosity, and atmospheric contamination in TIG/MIG setups.</li><li><strong>Insulating Glass Filling:</strong> Lower thermal conductivity significantly reduces heat transfer and condensation in architectural glass.</li><li><strong>Analytical Grade:</strong> Consistent spectral clarity for ICP, GC, and spark emission spectrometry.</li><li><strong>High Pressure Fleet:</strong> 150 bar certified cylinders inspected with safety burst discs and pressure testing.</li></ul>',1,'Shielding Grade','popular','Current Production Batch','5-Yr PESO Hydro-Test Cycle','argon gas, TIG welding gas cylinder, argon supplier Agra, pure argon gas 99.99%, shielding gas','published',3,'2026-11-24 23:31:57'),(4,2,'Carbon Dioxide Gas','carbon-dioxide-gas','Industrial and food-grade Carbon Dioxide Gas for MAG welding, carbonation in beverage processing, PH water neutralization, greenhouse growth, and fire suppression.','<h2>Industrial & Food Grade Carbon Dioxide Gas (CO2)</h2><p>Carbon Dioxide (CO2) is a versatile gas supplied in liquid-gas equilibrium inside heavy-duty high-pressure cylinders with siphon dip-tubes or vapor-draw valves. Extensively used in metal fabrication (MIG/MAG welding of carbon steels for deep weld penetration), carbonated beverage bottling, food preservation, chemical neutralization, and industrial fire protection.</p><p>We provide tested, certified food-grade and industrial-grade CO2 cylinders filled by accurate weight with strict moisture and impurity control.</p>','{\"Purity\":\"99.5% - 99.9% (Industrial & Food/Beverage Grade)\",\"Form\":\"Liquefied Compressed Gas\",\"Cylinder Capacity\":\"30 kg / 45 kg Net Gas Weight\",\"Test Pressure\":\"250 bar (Working Pressure ~ 60 - 70 bar at 25°C)\",\"Valve Fitting\":\"BS:341 No. 8 / IS:3224 with Safety Rupture Disc\",\"Grade\":\"Commercial / Beverage IS:307 / Welding Grade\",\"Moisture Content\":\"< 20 ppm\",\"Packaging Type\":\"High-Pressure Seamless Carbon Dioxide Cylinder\"}','Carbon dioxide gas, CO2 cylinder, Beverage CO2, MIG welding CO2, Industrial CO2 Agra, Fire suppression gas','assets/images/products/carbon-dioxide-gas.jpeg','Carbon Dioxide Gas Cylinder (CO2) — Industrial and Beverage Grade','[]','','<p>MAG Welding (Carbon Steel), Soft Drink & Beverage Carbonation, Fire Suppression Systems, Greenhouse Plant Growth, Foundry Core Hardening, and Water Treatment.</p>',NULL,'<ul><li><strong>Deep Weld Penetration:</strong> Outstanding arc stabilization and bead penetration for heavy structural carbon steel welding.</li><li><strong>Food & Beverage Quality:</strong> Odorless, sterile carbonation for soda, soft drinks, beer dispensing, and food freezing.</li><li><strong>Clean Agent Fire Safety:</strong> Non-conductive, residue-free suppression for electrical rooms and fuel storage.</li><li><strong>Dual Phase Delivery:</strong> Available with standard vapor valve or internal siphon tube for liquid CO2 draw.</li></ul>',1,'Food & Industrial Grade','verified','Current Production Batch','5-Yr PESO Hydro-Test Cycle','carbon dioxide gas, CO2 gas cylinder, beverage CO2 supplier Agra, MIG welding CO2 gas, liquid CO2','published',4,'2026-11-24 23:31:57'),(5,3,'Hydrogen Gas','hydrogen-gas','High-purity Hydrogen Gas for gas chromatography, chemical hydrogenation, thermal heat treatment, glass manufacturing, and fuel cell research.','<h2>High-Purity Hydrogen Gas for Industrial & Laboratory Applications</h2><p>Hydrogen Gas (H2) is an ultra-light, highly flammable gas with remarkable reducing and thermal properties. Widely used as a fuel and carrier gas in Gas Chromatography (FID detectors), bright annealing and heat treating of stainless steel, transformer cooling, glass flame polishing, and organic hydrogenation reactions.</p><p>Packaged in specialized red-collared cylinders with red-band safety indicators, equipped with specialized left-hand threaded valves and flash-back protection protocols under PESO licensing.</p>','{\"Purity\":\"99.9% - 99.999% (Zero / UHP Grade)\",\"Specific Gravity\":\"0.0696 (Air = 1)\",\"Working Pressure\":\"150 kg/cm² (150 bar)\",\"Packaging Type\":\"PESO Approved Hydrogen Cylinders (Red Band)\",\"Valve Standard\":\"IS:3224 Left-Hand Thread with Safety Disc\",\"Water Capacity\":\"47 Litre High Pressure Steel\",\"Moisture\":\"< 5 ppm (Ultra-Dry)\",\"Supply Mode\":\"Single Cylinders & High-Pressure Packs\"}','Hydrogen gas, H2 cylinder, Gas chromatography carrier, UHP hydrogen, High purity hydrogen Agra, Fuel gas','assets/images/products/hydrogen-gas.jpg','Hydrogen Gas Cylinder (H2) — High Purity Laboratory and Industrial Gas','[]','','<p>Gas Chromatography (Carrier & Fuel Gas), Heat Treatment & Bright Annealing, Chemical Hydrogenation, Glass & Quartz Fabrication, Power Plant Generator Cooling, and Fuel Cells.</p>',NULL,'<ul><li><strong>Ultra-High Thermal Conductivity:</strong> Ideal heat-transfer medium for generator cooling and metal heat treatment.</li><li><strong>Clean Reducing Atmosphere:</strong> Prevents oxidation and scaling during high-temperature bright annealing of metals.</li><li><strong>High Precision Chromatography:</strong> Minimum baseline noise and maximum sensitivity in FID, FPD, and Hall detectors.</li><li><strong>Safety Standard Compliance:</strong> Strict adherence to statutory Indian Gas Cylinder Rules 2016 for flammable gas handling.</li></ul>',1,'Specialty Gas · 99.99%','popular','Current Production Batch','5-Yr PESO Hydro-Test Cycle','hydrogen gas, hydrogen cylinder Agra, GC carrier hydrogen gas, high purity hydrogen, UHP hydrogen 99.99%','published',5,'2026-11-24 23:31:57'),(6,3,'Helium Gas','helium-gas','Ultra-high purity Helium Gas for cryogenics, MRI cooling, gas chromatography, leak detection, semiconductor manufacturing, and balloon inflation.','<h2>Ultra-High Purity Helium Gas for Scientific, Medical & Industrial Uses</h2><p>Helium Gas is an inert, monoatomic noble gas with the lowest boiling point of any element (-268.9°C). It is critical for superconducting magnets in medical MRI scanners, high-resolution Gas Chromatography-Mass Spectrometry (GC-MS), nuclear reactor leak detection, and specialized shielding in aerospace welding.</p><p>We provide commercial balloon-grade helium as well as 99.999% ultra-pure laboratory grade helium in high-pressure cylinders with rapid Agra dispatch.</p>','{\"Purity\":\"99.99% - 99.999% (UHP / Grade 5.0)\",\"Atomic Mass\":\"4.002602 u\",\"Density\":\"0.1786 g/L (0°C, 1 atm)\",\"Working Pressure\":\"150 kg/cm² - 200 kg/cm²\",\"Water Capacity\":\"47 Litre / 50 Litre Jumbo\",\"Packaging\":\"PESO Certified High-Pressure Cylinder\",\"Non-Flammable\":\"Class 2.2 Inert Compressed Gas\",\"Boiling Point\":\"-268.9°C (4.2 K)\"}','Helium gas, Helium cylinder, MRI helium, Lab carrier gas, Balloon helium Agra, Grade 5 helium','assets/images/products/helium-gas.jpeg','Helium Gas Cylinder — Ultra High Purity Scientific and Commercial Grade','[]','','<p>Hospital MRI Superconducting Magnets, Gas Chromatography (GC-MS), Mass Spectrometer Leak Detection, Fiber Optics & Semiconductors, Balloon Inflation, and Aerospace Welding.</p>',NULL,'<ul><li><strong>Lowest Boiling Point:</strong> Superconducting magnet cooling for healthcare MRI and NMR installations.</li><li><strong>Small Molecular Size:</strong> Industry-standard gas for precision vacuum and pressure leak detection (MSLD).</li><li><strong>Carrier Gas of Choice:</strong> Exceptional chromatographic separation and carrier efficiency in analytical GC-MS.</li><li><strong>Ultra-Safe & Non-Toxic:</strong> Completely non-flammable, non-corrosive, and chemically inert in all environments.</li></ul>',1,'Ultra-Pure 99.999%','verified','Current Production Batch','5-Yr PESO Hydro-Test Cycle','helium gas, helium gas cylinder Agra, MRI helium supplier, balloon helium gas, ultra pure helium 99.999%','published',6,'2026-11-24 23:31:57'),(7,2,'Acetylene Gas','acetylene-gas','Dissolved Acetylene (DA) Gas for oxy-acetylene welding, heavy structural steel cutting, brazing, thermal gouging, and glass blowing with hottest flame temperatures.','<h2>Dissolved Acetylene Gas (DA) for High-Temperature Cutting & Welding</h2><p>Acetylene (C2H2) produces the highest flame temperature (up to 3,160°C) and the most concentrated heat release of all commercial fuel gases when burned with oxygen. This delivers the fastest preheat times, reduced oxygen consumption, and superior cut quality on thick steel plates, structural beams, and heavy fabrication.</p><p>Our Dissolved Acetylene is dissolved in acetone within a monolithic porous mass inside maroon cylinders, ensuring maximum stability, safety, and continuous gas release according to IS:308 and PESO statutory requirements.</p>','{\"Purity\":\"98.5% - 99.5%\",\"Flame Temperature\":\"3,160°C (with Pure Oxygen)\",\"Working Pressure\":\"15 kg/cm² at 15°C (Dissolved in Acetone)\",\"Density\":\"1.097 kg/m³ (Gas)\",\"Packaging Type\":\"Maroon DA Cylinder with Porous Mass & Fusible Plugs\",\"Cylinder Capacity\":\"6.0 m³ to 7.0 m³ Gas Content (~7 kg net)\",\"Standard\":\"IS:308 / IS:7312 Compliance\",\"Valve Fitting\":\"Left-Hand Thread G 5/8 or Clamp Fitting\"}','Acetylene gas, Dissolved acetylene, DA gas cylinder, Oxy-fuel cutting, Welding fuel gas, Agra industrial gas','assets/images/products/acetylene-gas.jpeg','Dissolved Acetylene Gas Cylinder (DA) — High Temperature Oxy-Fuel Cutting','[]','','<p>Oxy-Acetylene Cutting & Welding, Heavy Steel Fabrication, Scrap Dismantling, Flame Hardening & Gouging, Brazing & Soldering, and Glass Blowing.</p>',NULL,'<ul><li><strong>Hottest Flame (3,160°C):</strong> 30% faster piercing and preheat times compared to propane or LPG.</li><li><strong>Reduced Heat Affected Zone (HAZ):</strong> Concentrated flame minimizes distortion and warping in structural plates.</li><li><strong>High Precision Beveling:</strong> Produces smooth, slag-free cut edges requiring minimal post-weld grinding.</li><li><strong>Monolithic Safety Mass:</strong> Tested porous fill with safety fusible plugs for thermal emergency relief.</li></ul>',1,'High Calorific Flame','popular','Current Production Batch','5-Yr PESO Hydro-Test Cycle','acetylene gas, DA gas cylinder Agra, dissolved acetylene supplier, oxy acetylene welding, industrial fuel gas','published',7,'2026-11-24 23:31:57'),(8,3,'Zero Air Gas','zero-air-gas','Synthetic Zero Air Gas containing less than 0.1 ppm total hydrocarbons (THC), formulated as oxidizer gas for GC-FID, environmental monitoring, and emission testing.','<h2>Ultra-Pure Synthetic Zero Air Gas for Analytical Instrumentation</h2><p>Zero Air Gas is a precisely blended mixture of high-purity Oxygen (approx. 20-21%) and Nitrogen (79-80%) rigorously purified to contain less than 0.1 ppm total hydrocarbons (THC as methane), zero moisture, and zero carbon monoxide. It serves as the clean oxidizer flame gas for Flame Ionization Detectors (FID), Hydrocarbon Analyzers (FIDs), and CEM environmental emission monitors.</p><p>Supplied in 47-liter high-pressure cylinders with batch analytical certificates verifying background noise-free chromatographic operations.</p>','{\"Total Hydrocarbons (THC)\":\"< 0.1 ppm (as Methane)\",\"Oxygen Content\":\"20.5% - 21.5%\",\"Nitrogen Balance\":\"78.5% - 79.5%\",\"Moisture (H2O)\":\"< 2 ppm\",\"Carbon Monoxide (CO)\":\"< 1 ppm\",\"Carbon Dioxide (CO2)\":\"< 1 ppm\",\"Water Capacity\":\"47 Litre Seamless Steel Cylinder\",\"Working Pressure\":\"150 kg/cm² (150 bar)\"}','Zero air gas, GC-FID oxidizer, Synthetic air, Ultra pure zero air, Laboratory gas Agra, Hydrocarbon free air','assets/images/products/zero-air-gas.jpg','Zero Air Gas Cylinder — Ultra Pure Hydrocarbon Free Synthetic Air','[]','','<p>Gas Chromatography Flame Ionization Detectors (GC-FID), Continuous Emission Monitoring Systems (CEMS), Automotive Emission Testing, Air Quality & Pollution Stations, and Lab Calibration.</p>',NULL,'<ul><li><strong>Ultra-Low Hydrocarbon Background (< 0.1 ppm):</strong> Eliminates baseline noise and drift in GC-FID and FPD detectors.</li><li><strong>Consistent Blend Accuracy:</strong> Precise 21% O2 / 79% N2 synthetic blend prevents flame instability.</li><li><strong>Sub-PPM Contaminants:</strong> Guaranteed CO, CO2, NOx, and SO2 below 1 ppm for continuous emission monitoring.</li><li><strong>Certificate of Analysis:</strong> Every cylinder batch is validated with gas chromatography test documentation.</li></ul>',1,'Analytical Grade (<0.1ppm THC)','verified','Current Production Batch','5-Yr PESO Hydro-Test Cycle','zero air gas, zero air cylinder Agra, GC FID zero air, synthetic zero air gas, hydrocarbon free air','published',8,'2026-11-24 23:31:57');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rate_limits`
--

DROP TABLE IF EXISTS `rate_limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rate_limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_key` varchar(255) NOT NULL,
  `attempts` int(11) DEFAULT 0,
  `window_start` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `action_key` (`action_key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rate_limits`
--

LOCK TABLES `rate_limits` WRITE;
/*!40000 ALTER TABLE `rate_limits` DISABLE KEYS */;
/*!40000 ALTER TABLE `rate_limits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_key` varchar(80) NOT NULL,
  `title` varchar(255) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `is_active` int(11) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_key` (`section_key`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,'hero','Hero','{\"eyebrow\":\"YOUR PARTNER IN BETTER CARE\",\"heading\":\"Dependable equipment.\",\"accent\":\"Exceptional care.\",\"body\":\"From medical oxygen to precision surgical instruments, we bring the essentials of better healthcare closer to you.\",\"button_label\":\"Explore products\",\"button_url\":\"products\",\"secondary_label\":\"Talk to our team\",\"secondary_url\":\"contact\",\"image\":\"assets\\/images\\/equipment.webp\",\"image_alt\":\"Medical oxygen cylinders and surgical equipment, illustrative studio image\",\"badge\":\"MEDICAL & GAS EQUIPMENT\",\"items\":[{\"icon\":\"shield-check-line\",\"title\":\"Quality-focused\",\"body\":\"Carefully sourced equipment\"},{\"icon\":\"customer-service-2-line\",\"title\":\"Personal support\",\"body\":\"A team you can talk to\"},{\"icon\":\"map-pin-2-line\",\"title\":\"Based in Agra\",\"body\":\"Here for your requirements\"}],\"background\":\"\"}',1,0),(2,'stats','At a glance','{\"items\":[{\"value\":\"04\",\"label\":\"Equipment categories\",\"icon\":\"apps-2-line\"},{\"value\":\"01\",\"label\":\"Partner for your needs\",\"icon\":\"hand-heart-line\"},{\"value\":\"Agra\",\"label\":\"Locally based. Personally connected.\",\"icon\":\"map-pin-2-line\"},{\"value\":\"Let’s talk\",\"label\":\"Support starts with a conversation\",\"icon\":\"customer-service-2-line\"}],\"background\":\"\"}',1,1),(3,'categories','Product categories','{\"eyebrow\":\"FIND YOUR ESSENTIALS\",\"heading\":\"The right equipment. Every application.\",\"body\":\"Thoughtfully selected solutions for the people who care for others.\"}',1,2),(4,'products','Featured products','{\"eyebrow\":\"OUR PRODUCT RANGE\",\"heading\":\"Essential to care. Built to perform.\",\"body\":\"Explore medical and gas equipment for your everyday requirements.\",\"button_label\":\"View all products\",\"button_url\":\"products\"}',1,3),(5,'why','Why choose us','{\"eyebrow\":\"MORE THAN A SUPPLIER\",\"heading\":\"Your work matters. So does your equipment.\",\"body\":\"Behind every piece of equipment is a person who depends on it. That’s why we make understanding your needs our first priority.\",\"image\":\"assets\\/images\\/hospital.jpg\",\"image_alt\":\"Bright, thoughtfully equipped hospital corridor\",\"items\":[{\"icon\":\"shield-check-line\",\"title\":\"A focus on dependable quality\",\"body\":\"Equipment selected with clinical needs and everyday reliability in mind.\"},{\"icon\":\"tools-line\",\"title\":\"Guidance that makes a difference\",\"body\":\"Help with product selection, configurations and compatibility.\"},{\"icon\":\"customer-service-2-line\",\"title\":\"People you can reach\",\"body\":\"Speak directly with our Agra team, from first enquiry to follow-up.\"}],\"background\":\"\"}',1,4),(6,'use_cases','Care settings','{\"eyebrow\":\"WHERE OUR PRODUCTS HELP\",\"heading\":\"Supporting care, wherever it happens.\",\"body\":\"Equipment solutions for different settings. One commitment to your needs.\"}',1,5),(7,'case_studies','Case studies','{\"eyebrow\":\"OUR WORK IN PRACTICE\",\"heading\":\"Real requirements. Thoughtful solutions.\",\"body\":\"Discover how equipment can support different care environments.\"}',0,6),(8,'blog','Knowledge centre','{\"eyebrow\":\"INSIGHTS & RESOURCES\",\"heading\":\"A little knowledge. A lot of confidence.\",\"body\":\"Practical perspectives to help you make informed equipment enquiries.\",\"button_label\":\"Explore our journal\",\"button_url\":\"blog\"}',1,7),(9,'testimonials','Testimonials','{\"eyebrow\":\"OUR CUSTOMERS\",\"heading\":\"Trusted partnerships start with people.\"}',1,11),(10,'logos','Client logos','{\"heading\":\"Working together for better care\"}',0,13),(11,'cta','Call to action','{\"eyebrow\":\"LET’S EQUIP BETTER CARE\",\"heading\":\"The right solution starts with a conversation.\",\"body\":\"Tell us what you need. We’ll help you take the next step.\",\"button_label\":\"Get in touch\",\"button_url\":\"contact\",\"secondary_label\":\"View catalogue\",\"secondary_url\":\"catalogue\"}',1,15),(12,'contact','Contact strip','{\"heading\":\"Local roots. A personal connection.\",\"body\":\"Visit us in Pratappura, Agra, or give our team a call.\"}',0,14),(13,'footer','Footer','{\"heading\":\"Equipping care. Enabling confidence.\",\"body\":\"Your local partner for medical oxygen, surgical instruments and dependable healthcare equipment. Based in Agra, built around your needs.\",\"quick_links\":[{\"title\":\"Products\",\"url\":\"products\"},{\"title\":\"About us\",\"url\":\"about\"},{\"title\":\"Our journal\",\"url\":\"blog\"},{\"title\":\"Case studies\",\"url\":\"case-studies\"},{\"title\":\"Contact\",\"url\":\"contact\"}]}',1,16),(14,'faq','Frequently asked questions','{\"eyebrow\":\"CLEAR ANSWERS\",\"heading\":\"Questions are part of good care.\",\"body\":\"A few practical answers to help you move forward with confidence.\",\"items\":[{\"question\":\"Can you help us choose the right equipment?\",\"answer\":\"Yes. Share your application, flow requirements and delivery location and our team will guide you to a suitable range.\"},{\"question\":\"Do you support hospitals and clinics?\",\"answer\":\"We support clinical spaces with dependable equipment, clear documentation and responsive after-sales guidance.\"},{\"question\":\"How do I request a quote?\",\"answer\":\"Use the enquiry form or WhatsApp link and tell us what you need. We will get back with the next practical step.\"}]}',1,12),(15,'process','Our approach','{\"eyebrow\":\"A CLEARER PATH FORWARD\",\"heading\":\"From your question to a considered choice.\",\"body\":\"A useful equipment conversation starts with understanding the setting, the people using it and the details that matter.\",\"items\":[{\"number\":\"01\",\"title\":\"Tell us about your need\",\"body\":\"Share the care setting, application, quantity and any product details you already have.\"},{\"number\":\"02\",\"title\":\"Explore suitable options\",\"body\":\"We help you review available products, configurations and compatibility questions.\"},{\"number\":\"03\",\"title\":\"Decide with clarity\",\"body\":\"Get clear information to help you choose the next step for your requirements.\"}]}',1,8),(16,'guidance','Practical guidance','{\"eyebrow\":\"THOUGHTFUL SUPPORT\",\"heading\":\"Small details make equipment planning easier.\",\"body\":\"Our team can help you ask the right questions before you move ahead.\",\"items\":[{\"icon\":\"hospital-line\",\"title\":\"Care setting\",\"body\":\"Describe where and how the equipment will be used.\"},{\"icon\":\"tools-line\",\"title\":\"Compatibility\",\"body\":\"Check fittings, accessories and configuration requirements with the team.\"},{\"icon\":\"file-list-3-line\",\"title\":\"Product information\",\"body\":\"Request available specifications and documentation for the options you are considering.\"},{\"icon\":\"customer-service-2-line\",\"title\":\"Local conversation\",\"body\":\"Speak directly with our Agra team about your equipment enquiry.\"}]}',1,9),(17,'partnership','A local partner','{\"eyebrow\":\"ROOTED IN AGRA\",\"heading\":\"Equipment matters. A helpful conversation does too.\",\"body\":\"Shree Ji Gas Industries connects care teams with medical oxygen equipment, surgical instruments, gas accessories and hospital essentials. Tell us what you are planning and we will help you explore the available options.\",\"button_label\":\"Talk to our team\",\"button_url\":\"contact\",\"secondary_label\":\"Explore products\",\"secondary_url\":\"products\"}',0,10);
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_meta`
--

DROP TABLE IF EXISTS `seo_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_key` varchar(255) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_desc` text DEFAULT NULL,
  `focus_keyword` varchar(255) DEFAULT NULL,
  `og_title` varchar(255) DEFAULT NULL,
  `og_desc` text DEFAULT NULL,
  `og_image` text DEFAULT NULL,
  `canonical_url` text DEFAULT NULL,
  `robots` varchar(40) DEFAULT 'index,follow',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_key` (`page_key`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_meta`
--

LOCK TABLES `seo_meta` WRITE;
/*!40000 ALTER TABLE `seo_meta` DISABLE KEYS */;
INSERT INTO `seo_meta` VALUES (4,'/terms','Terms & Conditions of Gas Supply | Shree Ji Gas Industries','Standard terms governing cylinder rotation, refundable security deposits, tare-weight verification, and institutional supply agreements in Agra.','terms of supply, cylinder deposit rules, medical gas agreement Agra, Shree Ji Gas terms','Terms & Conditions of Gas Supply & Services','Operating terms governing high-pressure cylinder supply, safety responsibilities, and commercial agreements.','assets/images/hospital.jpg','http://localhost/shreejigas/terms','index,follow','2026-10-09 00:29:52'),(5,'/compliance','Safety & Medical Gas Regulatory Compliance | Shree Ji Gas','Statutory compliance details covering PESO Gas Cylinders Rules 2016, 5-year hydro-testing certifications, and Indian Pharmacopoeia purity standards.','PESO compliance, gas cylinder hydro testing, Indian Pharmacopoeia oxygen, cylinder safety standards','Safety & Regulatory Compliance · Certified Excellence','Rigorous PESO standards, digital hydro-testing logs, and 99.5% medical oxygen purity validation.','assets/images/factory.jpg','http://localhost/shreejigas/compliance','index,follow','2026-10-09 00:29:52'),(6,'/shipping-policy','Emergency Delivery & Cylinder Transport Policy | Shree Ji Gas','Emergency 2-hour hospital SLA, hazmat transport protocols, cylinder deposit guidelines, and empty cylinder return procedures in Agra & NCR.','cylinder delivery policy, emergency oxygen delivery Agra, safe gas transport, cylinder deposit refund','Emergency Delivery & Cylinder Transport Policy','Rapid clinical response, certified transport vehicles, and transparent cylinder security deposit management.','assets/images/hospital.jpg','http://localhost/shreejigas/shipping-policy','index,follow','2026-10-09 00:29:52'),(7,'/','Shree Ji Gas Industries | Medical & Industrial Gas Supply in Agra','Leading PESO-approved manufacturer & distributor of medical oxygen, nitrous oxide, liquid gas installations, and cylinder manifolds across Agra & NCR.','medical oxygen Agra, oxygen cylinder supplier, hospital gas manifold, Shree Ji Gas','Shree Ji Gas Industries · Medical & Industrial Gas Equipment','Equipping healthcare facilities and industrial plants with certified medical gases, emergency cylinder refills, and 24/7 priority dispatch.','assets/images/factory.jpg','http://localhost/shreejigas/','index,follow','2026-10-09 01:13:35'),(8,'/about','About Shree Ji Gas Industries | 30+ Years of Medical Gas Excellence','Discover our heritage, PESO-compliant filling facilities, stringent quality testing, and dedicated biomedical gas engineering team in Agra.','about Shree Ji Gas, medical gas plant Agra, oxygen supplier history, PESO gas plant','About Shree Ji Gas Industries · 30+ Years of Trust & Care','From clinical oxygen manifolds to high-purity industrial supply, learn how Shree Ji Gas powers healthcare across Uttar Pradesh.','assets/images/hospital.jpg','http://localhost/shreejigas/about','index,follow','2026-10-09 01:13:35'),(9,'/contact','Contact Us & Emergency Oxygen Dispatch | Shree Ji Gas Industries','24/7 priority emergency medical oxygen dispatch & direct procurement desks. Call +91 94122 56214 or connect with our engineering team in Agra.','contact Shree Ji Gas, emergency oxygen Agra, hospital oxygen helpline, medical gas procurement','Contact Shree Ji Gas · 24/7 Priority Emergency Dispatch','Direct access to biomedical engineers and cylinder dispatch officers for urgent hospital refills and bulk industrial orders.','assets/images/oxygen.webp','http://localhost/shreejigas/contact','index,follow','2026-10-09 01:13:35'),(10,'/products','Our Product Range | Medical & Industrial Gas Equipment Catalogue','Explore certified medical oxygen cylinders, B-type/D-type cylinders, gas regulators, flowmeters, and manifold pipeline systems in Agra.','medical oxygen cylinders, gas equipment catalogue, oxygen regulator Agra, hospital gas manifold','Our Product Range · Essential Medical & Gas Equipment','High-pressure certified cylinders, testing kits, regulators, and clinical manifolds built to perform for life-saving care.','assets/images/oxygen.webp','http://localhost/shreejigas/products','index,follow','2026-10-09 01:13:35'),(11,'/use-cases','Care Settings & Industrial Applications | Shree Ji Gas Industries','Tailored medical and industrial gas delivery configurations for intensive care units, emergency trauma, dental clinics, and industrial workshops.','ICU oxygen supply, care settings, dental gas delivery, emergency medical gas, industrial gas uses','Care Settings · Specialized Gas Solutions for Every Context','From bedside oxygen therapy to critical surgery wards, see how our gas systems adapt to specialized clinical environments.','assets/images/hospital.jpg','http://localhost/shreejigas/use-cases','index,follow','2026-10-09 01:13:35'),(12,'/case-studies','Clinical Case Studies & Hospital Installations | Shree Ji Gas','Real-world deployment records showing how Shree Ji Gas delivered continuous oxygen reliability and manifold modernization for UP hospitals.','medical gas case studies, hospital oxygen setup, manifold installation case study Agra','Case Studies · Mission-Critical Gas Engineering in Practice','Explore how leading hospitals achieved 100% uptime and regulatory compliance with Shree Ji Gas equipment and delivery.','assets/images/hospital.jpg','http://localhost/shreejigas/case-studies','index,follow','2026-10-09 01:13:35'),(13,'/blog','The Care Journal | Medical Gas Safety & Industry Insights','Expert guides on PESO cylinder safety, 5-year hydrostatic testing, medical oxygen purity standards, and manifold management protocols.','medical gas safety blog, PESO cylinder rules, oxygen purity standards, medical gas news Agra','The Care Journal · Medical Gas Safety, Technology & Compliance','Practical clinical insights, statutory standards, and safe handling guidelines for medical oxygen and compressed gas systems.','assets/images/factory.jpg','http://localhost/shreejigas/blog','index,follow','2026-10-09 01:13:35'),(14,'/privacy','Privacy Policy | Shree Ji Gas Industries (DPDP Act 2023)','Our comprehensive data protection policy detailing procurement data handling, encryption, and statutory consent in compliance with the DPDP Act 2023.','privacy policy, DPDP Act 2023, data protection Shree Ji Gas','Privacy Policy · Shree Ji Gas Industries','Transparency, explicit consent, and security in handling customer and healthcare partner information.','assets/images/hospital.jpg','http://localhost/shreejigas/privacy','index,follow','2026-10-09 01:13:35'),(15,'/products/medical-oxygen-cylinder','','','','','','','','index,follow','2026-10-09 23:00:52');
/*!40000 ALTER TABLE `seo_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `group` varchar(100) DEFAULT 'site',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'phone','+91 7060069159','site'),(2,'email','shreejigasteam@gmail.com','site'),(3,'address','33/103, Pratappura, Agra','site'),(4,'whatsapp','917060069159','site'),(5,'tagline','Equipping care. Enabling confidence.','site'),(6,'description','Your local partner for medical oxygen, surgical instruments and dependable healthcare equipment. Based in Agra, built around your needs.','site'),(7,'logo','assets/images/logo.png','site'),(8,'meta_title','Medical & Gas Equipment in Agra | Shree Ji Gas Industries','site'),(9,'meta_description','Explore oxygen cylinders, surgical instruments and gas equipment from Shree Ji Gas Industries, Agra. Speak to our team for product details and a quote.','site'),(10,'catalogue_pdf','','site'),(11,'facebook','','site'),(12,'instagram','','site'),(13,'linkedin','','site'),(14,'preview_notice','1','site'),(15,'operating_hours','Open 24/7 for Medical Oxygen Emergency Deliveries · Hospital Line: +91 94122 56068','site'),(16,'gst_number','09AAECF1234F1Z5 · PESO License #AGR/GAS/2018','site'),(17,'smtp_enabled','1','site'),(18,'smtp_host','smtp.gmail.com','site'),(19,'smtp_port','587','site'),(20,'smtp_encryption','tls','site'),(21,'smtp_user','devmilind12@gmail.com','site'),(22,'smtp_pass','gach bwzl bgmo fzkw','site'),(23,'smtp_from_email','shreejigasteam@gmail.com','site'),(24,'smtp_from_name','Shree Ji Gas Industries','site'),(25,'focus_keywords','medical oxygen cylinder, hospital gas pipeline, manifold system, Agra oxygen supplier, PESO certified gas','site'),(26,'alert_email','','site'),(27,'google_analytics_id','','site'),(28,'gtm_container_id','','site'),(29,'gtm_head','','site'),(30,'gtm_body','','site');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote` text NOT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `rating` int(11) DEFAULT 5,
  `is_active` int(11) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,'Shree Ji Gas Industries has been our trusted partner for medical oxygen manifolds and emergency cylinder supply. Their turnaround time in Agra is exceptional, even during critical night shifts.','Dr. Rajesh Sharma','Medical Director, Apex Multi-Speciality Hospital, Agra','assets/images/testimonial-dr-rajesh.jpg',5,1,1),(2,'The precision and finish of their surgical instrument sets are top-grade. Having a local supplier in Pratappura who understands clinical specifications makes procurement completely hassle-free.','Dr. Meenakshi Verma','Head of Surgery, Verma Care Nursing Home','assets/images/testimonial-dr-meenakshi.jpg',5,1,2),(3,'From certified medical gas regulators to pediatric flowmeters, Shree Ji delivers tested equipment with proper batch documentation. Their team\'s technical guidance is truly commendable.','Er. Vikramaditya Singh','Biomedical Engineering In-charge, Shanti Mission Hospital','assets/images/testimonial-er-vikram.jpg',5,1,3),(4,'We set up our post-op recovery ward with oxygen delivery accessories from Shree Ji. Prompt dispatch, genuine PESO-certified cylinders, and supportive customer desk.','Dr. Sanjay Gupta','Senior Consultant, Heritage Health Care Clinic','assets/images/testimonial-dr-sanjay.jpg',5,1,4);
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `use_cases`
--

DROP TABLE IF EXISTS `use_cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `use_cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `application_context` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `related_products` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`related_products`)),
  `thumbnail` text DEFAULT NULL,
  `image_alt` varchar(255) DEFAULT NULL,
  `status` varchar(30) DEFAULT 'draft',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `use_cases`
--

LOCK TABLES `use_cases` WRITE;
/*!40000 ALTER TABLE `use_cases` DISABLE KEYS */;
INSERT INTO `use_cases` VALUES (1,'Hospitals & Critical Care','hospitals-critical-care','<p>From central equipment planning to individual care spaces, explore essential oxygen and gas equipment for hospital teams.</p>','<ul><li>Guidance on available equipment options</li><li>Assistance with configuration enquiries</li><li>A local point of contact for product support</li></ul>','[1,3,5]','assets/images/manifold.jpg','Hospital and critical care oxygen manifold supply system','published'),(2,'Clinics & Surgical Centres','clinics-surgical-centres','<p>Equip your clinical workspace with practical surgical instruments, gas accessories and hospital essentials.</p>','<ul><li>Guidance on available equipment options</li><li>Assistance with configuration enquiries</li><li>A local point of contact for product support</li></ul>','[2,3,6]','assets/images/surgical-theatre.jpg','Operating theatre surgical instruments and clinical gas supply','published'),(3,'Supervised Home Care','supervised-home-care','<p>Discuss compact equipment options for care at home, selected in consultation with your healthcare professional.</p>','<ul><li>Guidance on available equipment options</li><li>Assistance with configuration enquiries</li><li>A local point of contact for product support</li></ul>','[1,4]','assets/images/homecare-oxygen.jpg','Supervised home care medical oxygen cylinder and respiratory support','published');
/*!40000 ALTER TABLE `use_cases` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-24 23:44:31
