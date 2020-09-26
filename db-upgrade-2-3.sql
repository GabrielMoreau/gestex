--
-- Version 3 Date : 26/09/2020
--

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
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;

--
-- Upgrade table
--

ALTER TABLE `users` CHANGE `email` `email` varchar(50);

--
-- Fix global DB version
--

UPDATE version SET version = 3 WHERE soft = 'database';
INSERT INTO version (soft, version) VALUES ('datasheet',  1);
