-- Permisos para usuario root
SHOW GRANTS FOR 'root'@'localhost';
GRANT ALL PRIVILEGES ON verduleria.* TO 'root'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


CREATE SCHEMA IF NOT EXISTS `Verduleria` DEFAULT CHARACTER SET utf8;
USE `Verduleria`;


CREATE TABLE IF NOT EXISTS `User` (
  `idUser` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `Password` VARCHAR(45) NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE INDEX `Name_UNIQUE` (`Name` ASC)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `UserGroup` (
  `idGroup` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idGroup`)
) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `UserGroup_User` (
  `Id` INT NOT NULL,
  `Group_idGroup` INT NOT NULL,
  `User_idUser` INT NOT NULL,
  PRIMARY KEY (`Id`, `Group_idGroup`, `User_idUser`),
  INDEX `fk_UserGroup_User_Group_idx` (`Group_idGroup` ASC),
  INDEX `fk_UserGroup_User_User_idx` (`User_idUser` ASC),
  CONSTRAINT `fk_UserGroup_User_Group`
    FOREIGN KEY (`Group_idGroup`)
    REFERENCES `UserGroup` (`idGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_UserGroup_User_User`
    FOREIGN KEY (`User_idUser`)
    REFERENCES `User` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `Action` (
  `idAction` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idAction`),
  UNIQUE INDEX `Name_UNIQUE` (`Name` ASC)
) ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `GroupAction` (
  `Id` INT NOT NULL,
  `Action_idAction` INT NOT NULL,
  `Group_idGroup` INT NOT NULL,
  PRIMARY KEY (`Id`, `Action_idAction`, `Group_idGroup`),
  INDEX `fk_GroupAction_Action_idx` (`Action_idAction` ASC),
  INDEX `fk_GroupAction_Group_idx` (`Group_idGroup` ASC),
  CONSTRAINT `fk_GroupAction_Action`
    FOREIGN KEY (`Action_idAction`)
    REFERENCES `Action` (`idAction`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_GroupAction_Group`
    FOREIGN KEY (`Group_idGroup`)
    REFERENCES `UserGroup` (`idGroup`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;


CREATE VIEW `view1` AS
SELECT `User`.`idUser`, `User`.`Name` FROM `User`;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
