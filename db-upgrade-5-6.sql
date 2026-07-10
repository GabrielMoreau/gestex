--
-- VERSION: 6, DATE: 2026/07/10
--

--
-- Upgrade table
--

ALTER TABLE `users` MODIFY password VARCHAR(255) NOT NULL;

--
-- Rename table
--

RENAME TABLE `Listing` TO `equipment`;
RENAME TABLE `fournisseurs` TO `supplier`;
RENAME TABLE `equipe` TO `team`;
RENAME TABLE `pret` TO `loan`;
RENAME TABLE `categorie` TO `category`;

--
-- Drop old table
--

DROP TABLE IF EXISTS `appareils`;
DROP TABLE IF EXISTS `demandes`;
DROP TABLE IF EXISTS `labview`;
DROP TABLE IF EXISTS `manip`;
DROP TABLE IF EXISTS `notice`;
DROP TABLE IF EXISTS `projet`;
DROP TABLE IF EXISTS `tache`;
DROP TABLE IF EXISTS `temps`;

--
-- Fix global DB version
--

UPDATE `version` SET `version` = 6 WHERE `soft` = 'database';
