--
-- VERSION : 5, DATE : 09/06/2021
-- AUTHOR : RISTICH Estéban	
--

--
-- Upgrade table
--

ALTER TABLE `listing` MODIFY COLUMN `barcode` BIGINT DEFAULT NULL;


-- TIMESTAMP =< MySQL 5.5.x < DATETIME
ALTER TABLE `version` ADD COLUMN `updated_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `pret` ADD COLUMN `status` ENUM('LOAN_BORROWED', 'LOAN_RESERVED', 'LOAN_RETURNED') NOT NULL;
UPDATE `pret` SET `status` = 'LOAN_BORROWED';

--
-- Fix global DB version
--

UPDATE version SET version = 5 WHERE soft = 'database';

RENAME TABLE `intervention` TO `old_intervention`;
CREATE TABLE IF NOT EXISTS `interventions` (
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    supplier_id INT NOT NULL,
    listing_id INT NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    KEY `company_id` (`supplier_id`),
    FOREIGN KEY (f_supplier) REFERENCES fournisseurs(id),
    FOREIGN KEY (f_listing) REFERENCES listing(id)
) ENGINE=MyISAM AUTO_INCREMENT=700 CHARSET=utf8;

ALTER TABLE listing ADD COLUMN max_loan_day INT NOT NULL DEFAULT 0;