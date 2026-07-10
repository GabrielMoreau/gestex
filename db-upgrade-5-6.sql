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
-- Fix global DB version
--

UPDATE `version` SET `version` = 6 WHERE `soft` = 'database';
