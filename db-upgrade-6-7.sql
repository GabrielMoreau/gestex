--
-- VERSION: 7, DATE: 2026/07/13
--

--
-- Upgrade table `equipment`
--

ALTER TABLE `equipment` MODIFY COLUMN `categorie` INT(11) DEFAULT NULL;
ALTER TABLE `equipment` RENAME COLUMN `categorie` TO `category_id`;
ALTER TABLE `equipment` RENAME COLUMN `equipe` TO `team_id`;
ALTER TABLE `equipment` RENAME COLUMN `fournisseur` TO `supplier_id`;
ALTER TABLE `equipment` RENAME COLUMN `responsable` TO `manager_id`;
-- ALTER TABLE `equipment` RENAME COLUMN `nom` TO `name`;

ALTER TABLE `equipment` RENAME INDEX `equipe` TO `idx_equipment_team`;
ALTER TABLE `equipment` RENAME INDEX `fournisseur` TO `idx_equipment_supplier`;
ALTER TABLE `equipment` RENAME INDEX `responsable` TO `idx_equipment_manager`;
ALTER TABLE `equipment` RENAME INDEX `barcode` TO `idx_equipment_barcode`;
ALTER TABLE `equipment` RENAME INDEX `nom` TO `idx_equipment_name`;
ALTER TABLE `equipment` RENAME INDEX `fk_equipment_category` TO `idx_equipment_category`;

ALTER TABLE `equipment` ADD CONSTRAINT `fk_equipment_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

-- SHOW CREATE TABLE equipment\G
-- SHOW INDEX FROM equipment;


--
-- Upgrade table `category`
--

ALTER TABLE `category` RENAME COLUMN `nom` TO `name`;

-- SHOW CREATE TABLE category\G


--
-- Upgrade table `team`
--

ALTER TABLE `team` RENAME COLUMN `nom` TO `name`;
ALTER TABLE `team` RENAME COLUMN `descr` TO `description`;
ALTER TABLE `team` RENAME COLUMN `compte` TO `accounting`;
ALTER TABLE `team` RENAME COLUMN `chef` TO `manager_id`;

ALTER TABLE `team` DROP FOREIGN KEY `fk_team_chief`;
ALTER TABLE `team` ADD CONSTRAINT `fk_team_manager` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`);

-- SHOW CREATE TABLE team\G
-- SHOW INDEX FROM team;


--
-- Upgrade table `loan`
--

ALTER TABLE `loan` MODIFY COLUMN `nom` INT(11) DEFAULT NULL;
ALTER TABLE `loan` RENAME COLUMN `nom` TO `equipment_id`;
ALTER TABLE `loan` RENAME COLUMN `equipe` TO `team_id`;

ALTER TABLE `loan` ADD CONSTRAINT `fk_loan_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`);
ALTER TABLE `loan` ADD CONSTRAINT `fk_loan_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`);

-- SHOW CREATE TABLE loan\G
-- SHOW INDEX FROM loan;
-- SELECT l.* FROM loan l LEFT JOIN equipment e ON l.equipment_id = e.id WHERE e.id IS NULL;


--
-- Upgrade table `user`
--

ALTER TABLE `users` RENAME COLUMN `nom` TO `familyname`;
ALTER TABLE `users` RENAME COLUMN `prenom` TO `firstname`;
ALTER TABLE `users` RENAME COLUMN `equipe` TO `team_id`;
RENAME TABLE `users` TO `user`;

-- SHOW CREATE TABLE user\G
-- SHOW INDEX FROM user;


--
-- Upgrade table `supplier`
--

ALTER TABLE `supplier` RENAME COLUMN `nom` TO `name`;
ALTER TABLE `supplier` RENAME COLUMN `adresse` TO `address`;
ALTER TABLE `supplier` RENAME COLUMN `mail` TO `email`;
ALTER TABLE `supplier` RENAME COLUMN `tel` TO `phone`;
ALTER TABLE `supplier` RENAME COLUMN `descr` TO `description`;

ALTER TABLE `supplier` MODIFY COLUMN `address` VARCHAR(100) NOT NULL DEFAULT '';

-- SHOW CREATE TABLE supplier\G


--
-- Fix global DB version
--

UPDATE `version` SET `version` = 7 WHERE `soft` = 'database';
