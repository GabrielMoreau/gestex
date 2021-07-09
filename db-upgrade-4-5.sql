--
-- VERSION : 5, DATE : 09/06/2021
-- AUTHOR : RISTICH Estéban	
--

--
-- ALTER TABLE
--

ALTER TABLE `listing` MODIFY COLUMN `barcode` BIGINT DEFAULT NULL;
ALTER TABLE `listing` ADD COLUMN `max_day` INT(11) NOT NULL DEFAULT 0;
UPDATE `listing` SET `max_day` = 0;


-- TIMESTAMP =< MySQL 5.5.x < DATETIME
ALTER TABLE `version` ADD COLUMN `updated_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `pret` ADD COLUMN `status` ENUM('LOAN_BORROWED', 'LOAN_RESERVED', 'LOAN_RETURNED') NOT NULL;

--
-- UPDATE TABLE
--

UPDATE `pret` SET `status` = 'LOAN_BORROWED';
UPDATE `version` SET `version` = 5 WHERE `soft` = 'database';

--
-- ADD TABLE
--

RENAME TABLE `intervention` TO `old_intervention`;
CREATE TABLE IF NOT EXISTS `intervention` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` INT(11) NOT NULL,
  `equipment_id` INT(11) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `date` DATE NOT NULL DEFAULT CURRENT_DATE,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`supplier_id`) REFERENCES `fournisseurs`(`id`),
  FOREIGN KEY (`equipment_id`) REFERENCES `listing`(`id`)
) ENGINE=MyISAM AUTO_INCREMENT=700 CHARSET=utf8;
