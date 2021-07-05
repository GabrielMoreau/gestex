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

DROP TABLE IF EXISTS `intervention`;
CREATE TABLE IF NOT EXISTS `interventions` (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    f_supplier INT NOT NULL,
    f_listing INT NOT NULL,
    description TINYTEXT DEFAULT NULL,
    check_on DATE DEFAULT NULL,
    FOREIGN KEY (f_supplier) REFERENCES fournisseurs(id),
    FOREIGN KEY (f_listing) REFERENCES listing(id)
) ENGINE=MyISAM CHARSET=utf8;