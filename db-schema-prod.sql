-- MySQL dump 10.13  Distrib 5.5.60, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: pool
-- ------------------------------------------------------
-- Server version	5.5.60-0+deb7u1

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
-- Table structure for table `Listing`
--

DROP TABLE IF EXISTS `Listing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Listing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorie` varchar(255) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `modele` varchar(255) DEFAULT NULL,
  `gamme` varchar(255) DEFAULT NULL,
  `equipe` int(11) DEFAULT NULL,
  `fournisseur` int(11) DEFAULT NULL,
  `achat` date DEFAULT NULL,
  `responsable` int(11) DEFAULT NULL,
  `reparation` varchar(30) DEFAULT NULL,
  `accessoires` varchar(255) DEFAULT NULL,
  `notice` varchar(255) DEFAULT NULL,
  `inventaire` varchar(50) DEFAULT NULL,
  `loanable` tinyint(1) NOT NULL DEFAULT '0',
  `barcode` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nom` (`nom`),
  KEY `equipe` (`equipe`),
  KEY `fournisseur` (`fournisseur`),
  KEY `responsable` (`responsable`),
  KEY `barcode` (`barcode`)
) ENGINE=MyISAM AUTO_INCREMENT=375 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `appareils`
--

DROP TABLE IF EXISTS `appareils`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appareils` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL DEFAULT '',
  `descr` varchar(255) DEFAULT NULL,
  `equipe` int(11) NOT NULL DEFAULT '0',
  `tech` int(11) NOT NULL DEFAULT '0',
  `fournisseur` int(11) NOT NULL DEFAULT '0',
  `achat` date DEFAULT NULL,
  `facture` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `datasheet`
--

DROP TABLE IF EXISTS `datasheet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datasheet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pathname` varchar(500) NOT NULL DEFAULT '',
  `description` varchar(150) NOT NULL DEFAULT '',
  `id_equipment` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_equipment` (`id_equipment`)
) ENGINE=MyISAM AUTO_INCREMENT=162 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `demandes`
--

DROP TABLE IF EXISTS `demandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demandes` (
  `tache` varchar(200) DEFAULT NULL,
  `nomdemandeur` varchar(50) DEFAULT NULL,
  `details` mediumtext,
  `achat` date DEFAULT NULL,
  `avancement` varchar(255) DEFAULT NULL,
  `termine` varchar(15) DEFAULT NULL,
  `piecesjointes` varchar(30) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipe`
--

DROP TABLE IF EXISTS `equipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` mediumtext,
  `descr` varchar(255) NOT NULL DEFAULT '',
  `compte` int(11) NOT NULL DEFAULT '0',
  `chef` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `chef` (`chef`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='liste des equipes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fournisseurs`
--

DROP TABLE IF EXISTS `fournisseurs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fournisseurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `adresse` varchar(50) NOT NULL DEFAULT '',
  `www` varchar(50) DEFAULT NULL,
  `tel` varchar(15) DEFAULT '00 00 00 00 00',
  `fax` varchar(15) DEFAULT '00 00 00 00 00',
  `mail` varchar(50) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `descr` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=120 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `intervention`
--

DROP TABLE IF EXISTS `intervention`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `intervention` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` varchar(255) DEFAULT NULL,
  `tech` int(11) NOT NULL DEFAULT '0',
  `fournisseur` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `facture` varchar(30) DEFAULT NULL,
  `appareil` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `labview`
--

DROP TABLE IF EXISTS `labview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `labview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manipch` varchar(60) DEFAULT NULL,
  `technicien` varchar(30) DEFAULT NULL,
  `localisation` varchar(30) DEFAULT NULL,
  `matos` varchar(100) DEFAULT NULL,
  `code` mediumtext,
  `driver` varchar(60) DEFAULT NULL,
  `module` varchar(60) DEFAULT NULL,
  `ecran` varchar(30) DEFAULT NULL,
  `pdf` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manip`
--

DROP TABLE IF EXISTS `manip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(20) NOT NULL DEFAULT '',
  `descr` varchar(255) NOT NULL DEFAULT '',
  `equipe` int(4) NOT NULL DEFAULT '0',
  `chercheur` int(11) NOT NULL DEFAULT '0',
  `chercheur_bis` int(11) DEFAULT NULL,
  `assoc_proj` varchar(10) DEFAULT NULL,
  `local` varchar(5) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='liste des manips';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notice`
--

DROP TABLE IF EXISTS `notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chemin_notice` varchar(500) NOT NULL DEFAULT '',
  `nom_notice` varchar(150) NOT NULL DEFAULT '',
  `id_appareil` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_appareil` (`id_appareil`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pret`
--

DROP TABLE IF EXISTS `pret`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pret` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(60) DEFAULT NULL,
  `equipe` int(11) DEFAULT NULL,
  `emprunt` date DEFAULT NULL,
  `retour` date DEFAULT NULL,
  `commentaire` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `equipe` (`equipe`)
) ENGINE=MyISAM AUTO_INCREMENT=679 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projet`
--

DROP TABLE IF EXISTS `projet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manip` int(11) NOT NULL DEFAULT '0',
  `nom` varchar(20) NOT NULL DEFAULT '',
  `descr` varchar(255) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '2000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 COMMENT="liste des projets d'une manip";
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tache`
--

DROP TABLE IF EXISTS `tache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projet` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `nom` varchar(20) NOT NULL DEFAULT '',
  `descr` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(30) DEFAULT NULL,
  `fourniss` varchar(30) DEFAULT NULL,
  `temps` int(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=134 DEFAULT CHARSET=utf8 COMMENT="liste des taches d'un projet";
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `temps`
--

DROP TABLE IF EXISTS `temps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tache` int(11) NOT NULL DEFAULT '0',
  `user` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `duree` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=158 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loggin` varchar(20) DEFAULT NULL,
  `password` varchar(40) NOT NULL DEFAULT '',
  `level` int(11) NOT NULL DEFAULT '1',
  `nom` varchar(20) NOT NULL DEFAULT '',
  `prenom` varchar(20) NOT NULL DEFAULT '',
  `tel` int(11) NOT NULL DEFAULT '0',
  `email` varchar(50) DEFAULT NULL,
  `equipe` int(11) DEFAULT NULL,
  `valid` int(11) DEFAULT NULL,
  `theme` varchar(50) DEFAULT 'clair',
  PRIMARY KEY (`id`),
  UNIQUE KEY `loggin` (`loggin`),
  KEY `equipe` (`equipe`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `version`
--

DROP TABLE IF EXISTS `version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `soft` varchar(20) NOT NULL DEFAULT '',
  `version` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `soft` (`soft`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-06-07 16:20:22
