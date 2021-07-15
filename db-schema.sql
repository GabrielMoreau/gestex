
--
-- Table structure for table `Listing` (equipment)
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
  `loanable` boolean NOT NULL DEFAULT false,
  `barcode` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`equipe`) REFERENCES `equipe` (`id`);
  FOREIGN KEY (`fournisseur`) REFERENCES `fournisseurs` (`id`);
  FOREIGN KEY (`responsable`) REFERENCES `users` (`id`);
  INDEX `nom` (`nom`),
  INDEX `barcode` (`barcode`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `equipe`
--

-- liste des equipes

DROP TABLE IF EXISTS `equipe`;
CREATE TABLE `equipe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text,
  `descr` varchar(255) NOT NULL DEFAULT '',
  `compte` int(11) NOT NULL DEFAULT '0',
  `chef` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
  FOREIGN KEY (`chef`) REFERENCES `users` (`id`);
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `intervention`
--

DROP TABLE IF EXISTS `intervention`;
CREATE TABLE `intervention` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` varchar(255) DEFAULT NULL,
  `tech` int(11) NOT NULL DEFAULT '0',
  `fournisseur` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `facture` varchar(30) DEFAULT NULL,
  `appareil` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `manip`
--

-- liste des manips

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
  `date` date NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


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
  PRIMARY KEY (`id`),
  FOREIGN KEY (`equipe`) REFERENCES `equipe` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `projet`
--

-- liste des projets d''une manip

DROP TABLE IF EXISTS `projet`;
CREATE TABLE `projet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manip` int(11) NOT NULL DEFAULT '0',
  `nom` varchar(20) NOT NULL DEFAULT '',
  `descr` varchar(255) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `tache`
--

-- liste des taches d''un projet

DROP TABLE IF EXISTS `tache`;
CREATE TABLE `tache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projet` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` varchar(20) NOT NULL DEFAULT '',
  `descr` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(30) DEFAULT NULL,
  `fourniss` varchar(30) DEFAULT NULL,
  `temps` int(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loggin` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `level` int(11) NOT NULL DEFAULT '1',
  `nom` varchar(20) NOT NULL DEFAULT '',
  `prenom` varchar(20) NOT NULL DEFAULT '',
  `tel` int(11) NOT NULL DEFAULT '0',
  `email` varchar(50) NOT NULL DEFAULT '',
  `equipe` int(11) NOT NULL DEFAULT '1',
  `valid` int(11) DEFAULT NULL,
  `theme` VARCHAR (50) DEFAULT 'clair';
  PRIMARY KEY (`id`),
  UNIQUE KEY `loggin` (`loggin`),
  FOREIGN KEY (`equipe`) REFERENCES `equipe` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


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
  FOREIGN KEY (`id_appareil`) REFERENCES `Listing` (`id`);
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `datasheet`
--

DROP TABLE IF EXISTS `datasheet`;
CREATE TABLE `datasheet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pathname` varchar(500) NOT NULL DEFAULT '',
  `description` varchar(150) NOT NULL DEFAULT '',
  `id_equipment` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_equipment`) REFERENCES `Listing` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `datasheet`
--

DROP TABLE IF EXISTS `relation_equipment_datasheet`;
CREATE TABLE `relation_equipment_datasheet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_equipment` int(11) NOT NULL,
  `id_datasheet` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_equipment`) REFERENCES `listing` (`id`),
  FOREIGN KEY (`id_datasheet`) REFERENCES `datasheet` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `version`
--

DROP TABLE IF EXISTS `version`;
CREATE TABLE `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `soft` varchar(20) NOT NULL DEFAULT '',
  `version` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `soft` (`soft`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Fix global DB version
--

INSERT INTO version (soft, version) VALUES ('database',  4);
