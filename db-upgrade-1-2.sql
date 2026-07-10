--
-- VERSION: 2, DATE: 2020/07/15
--

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


ALTER TABLE `notice` ADD FOREIGN KEY (`id_appareil`) REFERENCES `Listing` (`id`);
-- ALTER TABLE `notice` ENGINE=InnoDB;
-- ALTER TABLE `notice` DROP KEY `id_appareil`;
-- SHOW CREATE TABLE `notice`;

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
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;


--
-- Add some columns
--

ALTER TABLE `users` ADD `theme` VARCHAR (50) DEFAULT 'clair';


--
-- Add some foreign key
--

ALTER TABLE `users` CHANGE `loggin` `loggin` varchar(20);
ALTER TABLE `users` CHANGE `equipe` `equipe` INT(11);
ALTER TABLE `users` ADD FOREIGN KEY (`equipe`) REFERENCES `equipe` (`id`);
SHOW CREATE TABLE `users`;

ALTER TABLE `equipe` ADD FOREIGN KEY (`chef`) REFERENCES `users` (`id`);
SHOW CREATE TABLE `equipe`;

ALTER TABLE `Listing` ADD FOREIGN KEY (`equipe`) REFERENCES `equipe` (`id`);
ALTER TABLE `Listing` ADD FOREIGN KEY (`fournisseur`) REFERENCES `fournisseurs` (`id`);
ALTER TABLE `Listing` ADD FOREIGN KEY (`responsable`) REFERENCES `users` (`id`);
ALTER TABLE `Listing` ADD INDEX `nom` (`nom`);
SHOW CREATE TABLE `Listing`;

ALTER TABLE `pret` ADD FOREIGN KEY (`equipe`) REFERENCES `equipe` (`id`);
SHOW CREATE TABLE `pret`;

--
-- Fix global DB version
--

INSERT INTO `version` (soft, version) VALUES ('database',  2);
