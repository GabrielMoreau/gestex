-- MySQL dump 10.13  Distrib 5.5.60, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: pool
-- ------------------------------------------------------
-- Server version	5.5.60-0+deb7u1
-- Version 1 Date : 22/06/2020

--
-- Table structure for table `Listing`
--

DROP TABLE IF EXISTS `Listing`;
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=342 DEFAULT CHARSET=latin1;

--
-- Table structure for table `appareils`
--

DROP TABLE IF EXISTS `appareils`;
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;


--
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;


--
-- Table structure for table `demandes`
--

DROP TABLE IF EXISTS `demandes`;
CREATE TABLE `demandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tache` varchar(200) DEFAULT NULL,
  `nomdemandeur` varchar(50) DEFAULT NULL,
  `details` text,
  `achat` date DEFAULT NULL,
  `avancement` varchar(255) DEFAULT NULL,
  `termine` varchar(15) DEFAULT NULL,
  `piecesjointes` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;


--
-- Table structure for table `equipe`
--

DROP TABLE IF EXISTS `equipe`;
CREATE TABLE `equipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text,
  `descr` varchar(255) NOT NULL DEFAULT '',
  `compte` int(11) NOT NULL DEFAULT '0',
  `chef` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COMMENT='liste des equipes';


--
-- Table structure for table `fournisseurs`
--

DROP TABLE IF EXISTS `fournisseurs`;
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
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;


--
-- Table structure for table `intervention`
--

DROP TABLE IF EXISTS `intervention`;
CREATE TABLE `intervention` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` varchar(255) DEFAULT NULL,
  `tech` int(11) NOT NULL DEFAULT '0',
  `fournisseur` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `facture` varchar(30) DEFAULT NULL,
  `appareil` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


--
-- Table structure for table `labview`
--

DROP TABLE IF EXISTS `labview`;
CREATE TABLE `labview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manipch` varchar(60) DEFAULT NULL,
  `technicien` varchar(30) DEFAULT NULL,
  `localisation` varchar(30) DEFAULT NULL,
  `matos` varchar(100) DEFAULT NULL,
  `code` text,
  `driver` varchar(60) DEFAULT NULL,
  `module` varchar(60) DEFAULT NULL,
  `ecran` varchar(30) DEFAULT NULL,
  `pdf` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;


--
-- Table structure for table `manip`
--

DROP TABLE IF EXISTS `manip`;
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
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 COMMENT='liste des manips';


--
-- Table structure for table `pret`
--

DROP TABLE IF EXISTS `pret`;
CREATE TABLE `pret` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(60) DEFAULT NULL,
  `equipe` int(11) DEFAULT NULL,
  `emprunt` date DEFAULT NULL,
  `retour` date DEFAULT NULL,
  `commentaire` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=627 DEFAULT CHARSET=latin1;


--
-- Table structure for table `projet`
--

DROP TABLE IF EXISTS `projet`;
CREATE TABLE `projet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manip` int(11) NOT NULL DEFAULT '0',
  `nom` varchar(20) NOT NULL DEFAULT '',
  `descr` varchar(255) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '2000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=latin1 COMMENT='liste des projets d''une manip';


--
-- Table structure for table `tache`
--

DROP TABLE IF EXISTS `tache`;
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
) ENGINE=MyISAM AUTO_INCREMENT=134 DEFAULT CHARSET=latin1 COMMENT='liste des taches d''un projet';


--
-- Table structure for table `temps`
--

DROP TABLE IF EXISTS `temps`;
CREATE TABLE `temps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_tache` int(11) NOT NULL DEFAULT '0',
  `user` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `duree` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=158 DEFAULT CHARSET=latin1;


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loggin` varchar(10) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `level` int(11) NOT NULL DEFAULT '1',
  `nom` varchar(20) NOT NULL DEFAULT '',
  `prenom` varchar(20) NOT NULL DEFAULT '',
  `tel` int(11) NOT NULL DEFAULT '0',
  `email` varchar(30) NOT NULL DEFAULT '',
  `equipe` int(10) NOT NULL DEFAULT '1',
  `valid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loggin` (`loggin`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;


--
-- Table structure for table `notice`
--

DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chemin_notice` varchar(500) NOT NULL DEFAULT '',
  `nom_notice` varchar(150) NOT NULL DEFAULT '',
  `id_appareil` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;