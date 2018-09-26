-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 12, 2018 at 11:08 AM
-- Server version: 5.7.23-0ubuntu0.18.04.1
-- PHP Version: 7.2.7-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `dlpclientdatabase`
--
DROP DATABASE IF EXISTS `dlpclientdatabase`;
CREATE DATABASE IF NOT EXISTS `dlpclientdatabase` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `dlpclientdatabase`;

-- --------------------------------------------------------

--
-- Table structure for table `dlpclienttable`
--

DROP TABLE IF EXISTS `dlpclienttable`;
CREATE TABLE IF NOT EXISTS `dlpclienttable` (
  `username` char(255) NOT NULL,
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
  `email` char(255) DEFAULT NULL,
  `occupation` char(255) DEFAULT NULL,
  `source_of_funds` char(255) DEFAULT NULL,
  `usage_of_funds` char(255) DEFAULT NULL,
  `employer` char(255) DEFAULT NULL,
  `ss_id_number` char(255) DEFAULT NULL,
  `key1` char(255) DEFAULT NULL,
  `key2` char(255) DEFAULT NULL,
  `account_number` bigint(255) NOT NULL,
  `domain` char(255) NOT NULL,
  PRIMARY KEY (`username`,`phone_number`)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `dlpclientserverdetails`;
CREATE TABLE IF NOT EXISTS `dlpclientserverdetails` (
  `server_type` char(255) NOT NULL DEFAULT 'TRADE',
  `domain` char(255) NOT NULL,
  `server_ip` char(255) NOT NULL,
  `server_port` char(255) NOT NULL
) ENGINE=InnoDB;


DROP TABLE IF EXISTS `dlpclientadmin`;
CREATE TABLE IF NOT EXISTS `dlpclientadmin` (
  `id` bigint not null auto_increment primary key,
  `username` char(255) not null unique,
  `secret` text not null,
  `date_created` datetime not null,
  `last_login` datetime not null
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `dlpclientapilog`;
CREATE TABLE IF NOT EXISTS `dlpclientapilog` (
  `id` bigint not null auto_increment primary key,
  `date_created` timestamp DEFAULT current_timestamp,
  `log_details` text not null
) ENGINE=InnoDB;


GRANT ALL ON dlpclientdatabase.* to 'imterader'@'localhost' identified by 'Stomata1968@';
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
ALTER TABLE `dlpclienttable` ADD COLUMN record_sent boolean default false;
