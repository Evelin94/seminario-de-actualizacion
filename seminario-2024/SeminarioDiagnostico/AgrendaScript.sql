SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `mydb` ;
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Contacto`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`Contacto` (
  `Id` INT NOT NULL ,
  `Nombre` VARCHAR(45) NULL ,
  `Contacto` VARCHAR(45) NULL ,
  `Direccion` VARCHAR(45) NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Telefono`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`Telefono` (
  `Id` INT NOT NULL ,
  `Numero` VARCHAR(45) NULL ,
  PRIMARY KEY (`Id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Contacto-Telefono`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`Contacto-Telefono` (
  `idContacto-Telefono` INT NOT NULL ,
  `Contacto_Id` INT NOT NULL ,
  `Telefono_Id` INT NOT NULL ,
  PRIMARY KEY (`idContacto-Telefono`) ,
  CONSTRAINT `fk_Contacto-Telefono_Contacto`
    FOREIGN KEY (`Contacto_Id` )
    REFERENCES `mydb`.`Contacto` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Contacto-Telefono_Telefono1`
    FOREIGN KEY (`Telefono_Id` )
    REFERENCES `mydb`.`Telefono` (`Id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_Contacto-Telefono_Contacto_idx` ON `mydb`.`Contacto-Telefono` (`Contacto_Id` ASC) ;

CREATE INDEX `fk_Contacto-Telefono_Telefono1_idx` ON `mydb`.`Contacto-Telefono` (`Telefono_Id` ASC) ;

USE `mydb` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
