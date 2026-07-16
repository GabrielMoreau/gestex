--
-- VERSION: 7, DATE: 2026/07/15
--

--
-- Upgrade table `equipment`
--

ALTER TABLE `equipment` RENAME COLUMN `manager_id` TO `manager_user_id`;
ALTER TABLE `equipment` RENAME COLUMN `accessoires` TO `accessories`;
ALTER TABLE `equipment` RENAME COLUMN `inventaire` TO `inventory_number`;
ALTER TABLE `equipment` RENAME COLUMN `max_day` TO `max_loan_days`;
ALTER TABLE `equipment` RENAME COLUMN `loanable` TO `is_loanable`;
ALTER TABLE `equipment` RENAME COLUMN `nom` TO `name`;
ALTER TABLE `equipment` RENAME COLUMN `modele` TO `model`;
ALTER TABLE `equipment` RENAME COLUMN `gamme` TO `feature`;
ALTER TABLE `equipment` RENAME COLUMN `achat` TO `date_of_purchase`;
ALTER TABLE `equipment` RENAME COLUMN `reparation` TO `repair_comment`;

ALTER TABLE `equipment` ADD UNIQUE KEY `uk_equipment_inventory_number` (`inventory_number`);

-- SHOW CREATE TABLE equipment\G
-- SHOW INDEX FROM equipment;


--
-- Upgrade table `team`
--

ALTER TABLE `team` RENAME COLUMN `manager_id` TO `manager_user_id`;

--
-- Fix global DB version
--

UPDATE `version` SET `version` = 8 WHERE `soft` = 'database';
