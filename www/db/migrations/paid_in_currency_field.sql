-- MySQL Workbench Synchronization
-- Generated: 2018-11-30 11:51
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Max

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `inue`.`registration`
ADD COLUMN `paid_in_currency` DECIMAL(10,2) UNSIGNED NULL DEFAULT NULL AFTER `step`;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
