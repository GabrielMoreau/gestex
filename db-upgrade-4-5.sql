--
-- VERSION : 5, DATE : 09/06/2021
-- AUTHOR : RISTICH Estéban	
--

--
-- Upgrade table
--

ALTER TABLE `listing` MODIFY COLUMN `loanable` BOOLEAN NOT NULL DEFAULT FALSE;

--
-- Fix global DB version
--

UPDATE version SET version = 5 WHERE soft = 'database';
