--
-- VERSION : 6, DATE : 09/07/2026
--

--
-- Upgrade table
--

ALTER TABLE `users` MODIFY password VARCHAR(255) NOT NULL;

--
-- Fix global DB version
--

UPDATE `version` SET `version` = 6 WHERE `soft` = 'database';
