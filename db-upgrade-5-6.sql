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

--
-- Fix global DB version
--

UPDATE `version` SET `version` = 6 WHERE `soft` = 'database';
