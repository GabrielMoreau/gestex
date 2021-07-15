
--
-- Table structure for table `Listing` (equipment)
--

DROP TABLE IF EXISTS `Listing`;
CREATE TABLE `Listing` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `categorie` VARCHAR(255) DEFAULT NULL,
  `nom` VARCHAR(255) DEFAULT NULL,
  `modele` VARCHAR(255) DEFAULT NULL,
  `gamme` VARCHAR(255) DEFAULT NULL,
  `equipe` INT(11) DEFAULT NULL,
  `fournisseur` INT(11) DEFAULT NULL,
  `achat` DATE DEFAULT NULL,
  `responsable` INT(11) DEFAULT NULL,
  `reparation` VARCHAR(30) DEFAULT NULL,
  `accessoires` VARCHAR(255) DEFAULT NULL,
  `notice` VARCHAR(255) DEFAULT NULL,
  `inventaire` VARCHAR(50) DEFAULT NULL,
  `loanable` BOOLEAN NOT NULL DEFAULT FALSE,
  `barcode` INT(11) DEFAULT NULL,
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
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(30) NOT NULL DEFAULT '',
  `descr` VARCHAR(255) DEFAULT NULL,
  `equipe` INT(11) NOT NULL DEFAULT '0',
  `tech` INT(11) NOT NULL DEFAULT '0',
  `fournisseur` INT(11) NOT NULL DEFAULT '0',
  `achat` DATE DEFAULT NULL,
  `facture` VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE `categorie` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `demandes`
--

DROP TABLE IF EXISTS `demandes`;
CREATE TABLE `demandes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tache` VARCHAR(200) DEFAULT NULL,
  `nomdemandeur` VARCHAR(50) DEFAULT NULL,
  `details` TEXT,
  `achat` DATE DEFAULT NULL,
  `avancement` VARCHAR(255) DEFAULT NULL,
  `termine` VARCHAR(15) DEFAULT NULL,
  `piecesjointes` VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `equipe`
--

-- liste des equipes

DROP TABLE IF EXISTS `equipe`;
CREATE TABLE `equipe` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` TEXT,
  `descr` VARCHAR(255) NOT NULL DEFAULT '',
  `compte` INT(11) NOT NULL DEFAULT '0',
  `chef` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
  FOREIGN KEY (`chef`) REFERENCES `users` (`id`);
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `fournisseurs`
--

DROP TABLE IF EXISTS `fournisseurs`;
CREATE TABLE `fournisseurs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(50) DEFAULT NULL,
  `adresse` VARCHAR(50) NOT NULL DEFAULT '',
  `www` VARCHAR(50) DEFAULT NULL,
  `tel` VARCHAR(15) DEFAULT '00 00 00 00 00',
  `fax` VARCHAR(15) DEFAULT '00 00 00 00 00',
  `mail` VARCHAR(50) DEFAULT NULL,
  `contact` VARCHAR(255) DEFAULT NULL,
  `descr` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `intervention`
--

DROP TABLE IF EXISTS `intervention`;
CREATE TABLE `intervention` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `descr` VARCHAR(255) DEFAULT NULL,
  `tech` INT(11) NOT NULL DEFAULT '0',
  `fournisseur` INT(11) NOT NULL DEFAULT '0',
  `date` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `facture` VARCHAR(30) DEFAULT NULL,
  `appareil` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `labview`
--

DROP TABLE IF EXISTS `labview`;
CREATE TABLE `labview` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `manipch` VARCHAR(60) DEFAULT NULL,
  `technicien` VARCHAR(30) DEFAULT NULL,
  `localisation` VARCHAR(30) DEFAULT NULL,
  `matos` VARCHAR(100) DEFAULT NULL,
  `code` TEXT,
  `driver` VARCHAR(60) DEFAULT NULL,
  `module` VARCHAR(60) DEFAULT NULL,
  `ecran` VARCHAR(30) DEFAULT NULL,
  `pdf` VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `manip`
--

-- liste des manips

DROP TABLE IF EXISTS `manip`;
CREATE TABLE `manip` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(20) NOT NULL DEFAULT '',
  `descr` VARCHAR(255) NOT NULL DEFAULT '',
  `equipe` INT(4) NOT NULL DEFAULT '0',
  `chercheur` INT(11) NOT NULL DEFAULT '0',
  `chercheur_bis` INT(11) DEFAULT NULL,
  `assoc_proj` VARCHAR(10) DEFAULT NULL,
  `local` VARCHAR(5) NOT NULL DEFAULT '',
  `date` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `pret`
--

DROP TABLE IF EXISTS `pret`;
CREATE TABLE `pret` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(60) DEFAULT NULL,
  `equipe` INT(11) DEFAULT NULL,
  `emprunt` DATE DEFAULT NULL,
  `retour` DATE DEFAULT NULL,
  `commentaire` VARCHAR(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`equipe`) REFERENCES `equipe` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `projet`
--

-- liste des projets d''une manip

DROP TABLE IF EXISTS `projet`;
CREATE TABLE `projet` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `manip` INT(11) NOT NULL DEFAULT '0',
  `nom` VARCHAR(20) NOT NULL DEFAULT '',
  `descr` VARCHAR(255) NOT NULL DEFAULT '',
  `date` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `tache`
--

-- liste des taches d''un projet

DROP TABLE IF EXISTS `tache`;
CREATE TABLE `tache` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `projet` INT(11) NOT NULL DEFAULT '0',
  `date` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nom` VARCHAR(20) NOT NULL DEFAULT '',
  `descr` VARCHAR(255) NOT NULL DEFAULT '',
  `user` VARCHAR(30) DEFAULT NULL,
  `fourniss` VARCHAR(30) DEFAULT NULL,
  `temps` INT(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `temps`
--

DROP TABLE IF EXISTS `temps`;
CREATE TABLE `temps` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_tache` INT(11) NOT NULL DEFAULT '0',
  `user` INT(11) DEFAULT NULL,
  `date` DATE DEFAULT NULL,
  `duree` INT(11) DEFAULT NULL,
  `remarks` VARCHAR(255) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `loggin` VARCHAR(20) NOT NULL DEFAULT '',
  `password` VARCHAR(40) NOT NULL DEFAULT '',
  `level` INT(11) NOT NULL DEFAULT '1',
  `nom` VARCHAR(20) NOT NULL DEFAULT '',
  `prenom` VARCHAR(20) NOT NULL DEFAULT '',
  `tel` INT(11) NOT NULL DEFAULT '0',
  `email` VARCHAR(50) NOT NULL DEFAULT '',
  `equipe` INT(11) NOT NULL DEFAULT '1',
  `valid` INT(11) DEFAULT NULL,
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
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `chemin_notice` VARCHAR(500) NOT NULL DEFAULT '',
  `nom_notice` VARCHAR(150) NOT NULL DEFAULT '',
  `id_appareil` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
  FOREIGN KEY (`id_appareil`) REFERENCES `Listing` (`id`);
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `datasheet`
--

DROP TABLE IF EXISTS `datasheet`;
CREATE TABLE `datasheet` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pathname` VARCHAR(500) NOT NULL DEFAULT '',
  `description` VARCHAR(150) NOT NULL DEFAULT '',
  `id_equipment` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_equipment`) REFERENCES `Listing` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `datasheet`
--

DROP TABLE IF EXISTS `relation_equipment_datasheet`;
CREATE TABLE `relation_equipment_datasheet` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_equipment` INT(11) NOT NULL,
  `id_datasheet` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_equipment`) REFERENCES `Listing` (`id`),
  FOREIGN KEY (`id_datasheet`) REFERENCES `datasheet` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Table structure for table `version`
--

DROP TABLE IF EXISTS `version`;
CREATE TABLE `version` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `soft` VARCHAR(20) NOT NULL DEFAULT '',
  `version` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `soft` (`soft`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Fix global DB version
--

INSERT INTO version (soft, version) VALUES ('database',  4);
