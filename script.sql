-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema contact
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `contact` ;

-- -----------------------------------------------------
-- Schema contact
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `contact` ;
USE `contact` ;

-- -----------------------------------------------------
-- Table `contact`.`tbl_contact`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact`.`tbl_contact` (
  `idDocument` INT NOT NULL,
  `firstName` VARCHAR(45) NULL,
  `middleName` VARCHAR(45) NULL,
  `lastName` VARCHAR(45) NULL,
  `birthdate` DATETIME NULL,
  `phonePersonal` VARCHAR(45) NULL,
  `phoneWork` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  `idCountry` VARCHAR(3) NULL,
  `idState` VARCHAR(3) NULL,
  `idCity` INT NULL,
  `street` VARCHAR(45) NULL,
  `number` INT NULL,
  `zipCode` INT NULL,
  `companyName` VARCHAR(45) NULL,
  `imageProfile` VARCHAR(45) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`idDocument`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
