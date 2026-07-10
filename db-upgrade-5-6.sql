--
-- VERSION: 6, DATE: 2026/07/09
--

--
-- Upgrade table
--

ALTER TABLE `users` MODIFY password VARCHAR(255) NOT NULL;

--
-- Fix global DB version
--

UPDATE `version` SET `version` = 6 WHERE `soft` = 'database';
