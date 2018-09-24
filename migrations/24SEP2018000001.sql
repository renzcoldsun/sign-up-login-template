-- 24SEP2018000001.sql
-- Migration for BVZ modification

DROP TABLE IF EXISTS `dlpclienttable_temp`;
CREATE TABLE IF NOT EXISTS `dlpclienttable_temp` (
  `email` char(255) NOT NULL PRIMARY KEY,
  `phone_number` char(255) NOT NULL,
  `password` char(255) NOT NULL,
  `first_name` char(255) NOT NULL,
  `last_name` char(255) NOT NULL,
  `middle_name` char(255) DEFAULT NULL,
  `address1` char(255) DEFAULT NULL,
  `address2` char(255) DEFAULT NULL,
  `address3` char(255) DEFAULT NULL,
  `address4` char(255) DEFAULT NULL,
  `city` char(255) DEFAULT NULL,
  `state` char(255) DEFAULT NULL,
  `zip_code` char(255) DEFAULT NULL,
  `occupation` char(255) DEFAULT NULL,
  `source_of_funds` char(255) DEFAULT NULL,
  `usage_of_funds` char(255) DEFAULT NULL,
  `employer` char(255) DEFAULT NULL,
  `ss_id_number` char(255) DEFAULT NULL,
  `key1` char(255) DEFAULT NULL,
  `key2` char(255) DEFAULT NULL,
  `account_number` bigint(255) NOT NULL,
  `domain` char(255) NOT NULL,
  `backoffice` boolean DEFAULT FALSE
) ENGINE=InnoDB;

SET UNIQUE_CHECKS=0;
INSERT INTO `dlpclienttable_temp`
  SELECT 
  `email`,
  `phone_number`,
  `password`,
  `first_name`,
  `last_name`,
  `middle_name`,
  `address1`,
  `address2`,
  `address3`,
  `address4`,
  `city`,
  `state`,
  `zip_code`,
  `occupation`,
  `source_of_funds`,
  `usage_of_funds`,
  `employer`,
  `ss_id_number`,
  `key1`,
  `key2`,
  `account_number`,
  `domain`,
  FALSE from `dlpclienttable`;
SET UNIQUE_CHECKS=1;
DROP TABLE `dlpclienttable`;
RENAME TABLE `dlpclienttable_temp` to `dlpclienttable`;
