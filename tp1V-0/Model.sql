-- MySQL Workbench Forward Engineering
SHOW GRANTS FOR 'root'@'localhost';

-- Conceder todos los permisos en la base de datos verduleria
GRANT ALL PRIVILEGES ON verduleria.* TO 'root'@'localhost';
FLUSH PRIVILEGES;

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema Verduleria
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema Verduleria
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `Verduleria` DEFAULT CHARACTER SET utf8 ;
USE `Verduleria` ;
SHOW TABLES;
-- -----------------------------------------------------
-- Table `Verduleria`.`User`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Verduleria`.`User` (
  `idUser` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  `Password` VARCHAR(45) NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE INDEX `idUser_UNIQUE` (`idUser` ASC),
  UNIQUE INDEX `Name_UNIQUE` (`Name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Verduleria`.`Group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Verduleria`.`Group` (
  `idGroup` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idGroup`),
  UNIQUE INDEX `idGroup_UNIQUE` (`idGroup` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Verduleria`.`Group-User`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Verduleria`.`Group-User` (
  `Id` INT NOT NULL AUTO_INCREMENT,
  `Group_idGroup` INT NOT NULL,
  `User_idUser` INT NOT NULL,
  PRIMARY KEY (`Id`, `Group_idGroup`, `User_idUser`),
  INDEX `fk_Group-User_Group1_idx` (`Group_idGroup` ASC),
  INDEX `fk_Group-User_User1_idx` (`User_idUser` ASC),
  CONSTRAINT `fk_Group-User_Group1`
    FOREIGN KEY (`Group_idGroup`)
    REFERENCES `Verduleria`.`Group` (`idGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Group-User_User1`
    FOREIGN KEY (`User_idUser`)
    REFERENCES `Verduleria`.`User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Verduleria`.`Action`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Verduleria`.`Action` (
  `idAction` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idAction`),
  UNIQUE INDEX `idAction_UNIQUE` (`idAction` ASC),
  UNIQUE INDEX `Name_UNIQUE` (`Name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Verduleria`.`GroupAction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Verduleria`.`GroupAction` (
  `Id` INT NOT NULL,
  `Action_idAction` INT NOT NULL,
  `Group_idGroup` INT NOT NULL,
  PRIMARY KEY (`Id`, `Action_idAction`, `Group_idGroup`),
  INDEX `fk_GroupAction_Action_idx` (`Action_idAction` ASC),
  INDEX `fk_GroupAction_Group1_idx` (`Group_idGroup` ASC),
  CONSTRAINT `fk_GroupAction_Action`
    FOREIGN KEY (`Action_idAction`)
    REFERENCES `Verduleria`.`Action` (`idAction`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_GroupAction_Group1`
    FOREIGN KEY (`Group_idGroup`)
    REFERENCES `Verduleria`.`Group` (`idGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `Verduleria` ;

-- -----------------------------------------------------
-- Placeholder table for view `Verduleria`.`view1`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Verduleria`.`view1` (`id` INT);

-- -----------------------------------------------------
-- View `Verduleria`.`view1`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Verduleria`.`view1`;
USE `Verduleria`;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
