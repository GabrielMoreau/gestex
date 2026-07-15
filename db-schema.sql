
--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) DEFAULT NULL,
  `description` VARCHAR(255) NOT NULL DEFAULT '',
  `accounting` INT(11) NOT NULL DEFAULT 0,
  `manager_id` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- FOREIGN KEY (`chef`) REFERENCES `user` (`id`);

--
-- Table structure for table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) DEFAULT NULL,
  `address` VARCHAR(100) NOT NULL DEFAULT '',
  `www` VARCHAR(50) DEFAULT NULL,
  `phone` VARCHAR(25) NOT NULL DEFAULT 'Na',
  `fax` VARCHAR(15) DEFAULT '00 00 00 00 00',
  `email` VARCHAR(50) DEFAULT NULL,
  `contact` VARCHAR(255) DEFAULT NULL,
  `description` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(20) NOT NULL DEFAULT '',
  `password` VARCHAR(255) NOT NULL DEFAULT '',
  `level` INT(11) NOT NULL DEFAULT 1,
  `familyname` VARCHAR(20) NOT NULL DEFAULT '',
  `firstname` VARCHAR(20) NOT NULL DEFAULT '',
  `phone` VARCHAR(25) NOT NULL DEFAULT 'Na',
  `email` VARCHAR(50) NOT NULL DEFAULT '',
  `team_id` INT(11) NOT NULL DEFAULT 1,
  `valid` INT(11) DEFAULT NULL,
  `theme` VARCHAR (50) DEFAULT 'clair',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  CONSTRAINT `fk_user_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
CREATE TABLE `equipment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) DEFAULT NULL,
  `nom` VARCHAR(255) DEFAULT NULL,
  `modele` VARCHAR(255) DEFAULT NULL,
  `gamme` VARCHAR(255) DEFAULT NULL,
  `team_id` INT(11) DEFAULT NULL,
  `supplier_id` INT(11) DEFAULT NULL,
  `achat` DATE DEFAULT NULL,
  `manager_id` INT(11) DEFAULT NULL,
  `reparation` VARCHAR(30) DEFAULT NULL,
  `accessoires` VARCHAR(255) DEFAULT NULL,
  `notice` VARCHAR(255) DEFAULT NULL,
  `inventory_number` VARCHAR(50) DEFAULT NULL,
  `loanable` BOOLEAN NOT NULL DEFAULT FALSE,
  `barcode` BIGINT(20) DEFAULT NULL,
  `max_day` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_equipment_inventory_number` (`inventory_number`),
  CONSTRAINT `fk_equipment_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `fk_equipment_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`),
  CONSTRAINT `fk_equipment_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  CONSTRAINT `fk_equipment_manager` FOREIGN KEY (`manager_id`) REFERENCES `user` (`id`),
  INDEX `idx_equipment_name` (`nom`),
  INDEX `idx_equipment_barcode` (`barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `datasheet`
--

DROP TABLE IF EXISTS `datasheet`;
CREATE TABLE `datasheet` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pathname` VARCHAR(500) NOT NULL DEFAULT '',
  `description` VARCHAR(150) NOT NULL DEFAULT '',
  `equipment_id` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `equipment_id` (`equipment_id`),
  CONSTRAINT `fk_datasheet_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `intervention`
--

DROP TABLE IF EXISTS `intervention`;
CREATE TABLE IF NOT EXISTS `intervention` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` INT(11) NOT NULL,
  `equipment_id` INT(11) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `date` DATE NOT NULL DEFAULT CURRENT_DATE,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_intervention_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`),
  CONSTRAINT `fk_intervention_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `loan`
--

DROP TABLE IF EXISTS `loan`;
CREATE TABLE `loan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `equipment_id` INT(11) DEFAULT NULL,
  `team_id` INT(11) DEFAULT NULL,
  `start_date` DATE DEFAULT NULL,
  `end_date` DATE DEFAULT NULL,
  `comment` VARCHAR(100) DEFAULT NULL,
  `status` ENUM('LOAN_BORROWED','LOAN_RESERVED','LOAN_RETURNED') NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_loan_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`)
  CONSTRAINT `fk_loan_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
CREATE TABLE `recipe` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `pathname` VARCHAR(500) DEFAULT NULL,
  `description` VARCHAR(150) NOT NULL,
  `intervention_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_recipe_intervention` FOREIGN KEY (`intervention_id`) REFERENCES `intervention` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `relation_equipment_datasheet`
--

-- DROP TABLE IF EXISTS `relation_equipment_datasheet`;
-- CREATE TABLE `relation_equipment_datasheet` (
--   `id` INT(11) NOT NULL AUTO_INCREMENT,
--   `equipment_id` INT(11) NOT NULL,
--   `datasheet_id` INT(11) NOT NULL,
--   PRIMARY KEY (`id`),
--   CONSTRAINT `fk_red_equipment` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`),
--   CONSTRAINT `fk_red_datasheet` FOREIGN KEY (`datasheet_id`) REFERENCES `datasheet` (`id`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `version`
--

DROP TABLE IF EXISTS `version`;
CREATE TABLE `version` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `soft` VARCHAR(20) NOT NULL DEFAULT '',
  `version` INT(11) NOT NULL DEFAULT 0,
  `updated_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `soft` (`soft`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Special after foreign key
--

ALTER TABLE `team` ADD CONSTRAINT `fk_team_manager` FOREIGN KEY (`manager_id`) REFERENCES `user` (`id`);

--
-- Fix global DB version
--

INSERT INTO version (soft, version) VALUES ('database',  6);
