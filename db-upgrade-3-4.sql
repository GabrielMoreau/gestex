--
-- VERSION: 4, DATE: 2020/09/26
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

UPDATE `version` SET `version` = 4 WHERE `soft` = 'database';
