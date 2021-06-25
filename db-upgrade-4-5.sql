--
-- VERSION : 5, DATE : 09/06/2021
-- AUTHOR : RISTICH Estéban	
--

--
-- Upgrade table
--

ALTER TABLE `listing` MODIFY COLUMN `barcode` BIGINT DEFAULT NULL;

ALTER TABLE `version` ADD COLUMN `updated_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
-- TIMESTAMP =< MySQL 5.5.x < DATETIME

ALTER TABLE `pret` ADD COLUMN `state` ENUM('BOOKING', 'LOAN', 'OLD') NOT NULL;
UPDATE `pret` SET `state` = 2;

--
-- Fix global DB version
--

UPDATE version SET version = 5 WHERE soft = 'database';
