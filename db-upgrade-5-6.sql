--
-- VERSION: 6, DATE: 2026/07/10
--

--
-- Upgrade table
--

ALTER TABLE `users` MODIFY password VARCHAR(255) NOT NULL;
ALTER TABLE `users` MODIFY tel VARCHAR(25) NOT NULL DEFAULT 'Na';
ALTER TABLE `supplier` MODIFY tel VARCHAR(25) NOT NULL DEFAULT 'Na';

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
DROP TABLE IF EXISTS `old_intervention`;

--
-- Add foreign key
--

ALTER TABLE `users` ADD CONSTRAINT `fk_users_team` FOREIGN KEY (`equipe`) REFERENCES `team` (`id`);
ALTER TABLE `equipment` ADD CONSTRAINT `fk_equipment_team` FOREIGN KEY (`equipe`) REFERENCES `team` (`id`);
ALTER TABLE `equipment` ADD CONSTRAINT `fk_equipment_supplier` FOREIGN KEY (`fournisseur`) REFERENCES `supplier` (`id`);
ALTER TABLE `equipment` ADD CONSTRAINT `fk_equipment_manager` FOREIGN KEY (`responsable`) REFERENCES `users` (`id`);
ALTER TABLE `datasheet` ADD CONSTRAINT `fk_datasheet_equipment` FOREIGN KEY (`id_equipment`) REFERENCES `equipment` (`id`);
ALTER TABLE `intervention` ADD CONSTRAINT `fk_intervention_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`);
ALTER TABLE `intervention` ADD CONSTRAINT `fk_intervention_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`);
ALTER TABLE `loan` ADD CONSTRAINT `fk_loan_team` FOREIGN KEY (`equipe`) REFERENCES `team` (`id`);
ALTER TABLE `recipe` ADD CONSTRAINT `fk_recipe_intervention` FOREIGN KEY (`intervention_id`) REFERENCES `intervention` (`id`);
ALTER TABLE `team` ADD CONSTRAINT `fk_team_chief` FOREIGN KEY (`chef`) REFERENCES `users` (`id`);

--
-- Fix global DB version
--

UPDATE `version` SET `version` = 6 WHERE `soft` = 'database';
