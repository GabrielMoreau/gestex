--
-- Version 3 Date : 26/09/2020
--

--
-- Upgrade table
--

ALTER TABLE `Listing` ADD `loanable` boolean NOT NULL DEFAULT false;
ALTER TABLE `Listing` ADD `barcode` int(11) DEFAULT NULL;
ALTER TABLE `Listing` ADD INDEX `barcode` (`barcode`);

--
-- Fix global DB version
--

UPDATE version SET version = 4 WHERE soft = 'database';
