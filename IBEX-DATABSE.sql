
use ibex_ul_panel_database1;
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema ibex_ul_panel_database
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema ibex_ul_panel_database
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ibex_ul_panel_database1` DEFAULT CHARACTER SET utf8 COLLATE utf8mb4_0900_ai_ci ;
USE `ibex_ul_panel_database1` ;

-- -----------------------------------------------------
-- Table `ibex_ul_panel_database`.`ul_panel_components`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ibex_ul_panel_database1`.`ul_panel_components` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `component_name` VARCHAR(255) NOT NULL,
  `component_type` VARCHAR(255) NULL,
  `component_model` VARCHAR(255) NULL,
  `component_rating` INT NULL,
  `component_manufacturer` VARCHAR(255) NULL,
  `component_serial_number` VARCHAR(255) NULL,
  `component_installation_date` DATE NULL,
  `additional_data` JSON NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `component_model_index` (`component_model` ASC) VISIBLE,
  INDEX `component_manufacturer_index` (`component_manufacturer` ASC) VISIBLE,
  INDEX `component_serial_number_index` (`component_serial_number` ASC) VISIBLE,
  INDEX `component_installation_date_index` (`component_installation_date` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `ibex_ul_panel_database`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ibex_ul_panel_database1`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone_number` VARCHAR(255) NOT NULL,
  `google_id` VARCHAR(255) NULL DEFAULT NULL,
  `microsoft_id` VARCHAR(255) NULL DEFAULT NULL,
  `facebook_id` VARCHAR(255) NULL DEFAULT NULL,
  `additional_data` JSON NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username` (`username` ASC) VISIBLE,
  UNIQUE INDEX `email` (`email` ASC) VISIBLE,
  UNIQUE INDEX `google_id` (`google_id` ASC) VISIBLE,
  UNIQUE INDEX `microsoft_id` (`microsoft_id` ASC) VISIBLE,
  UNIQUE INDEX `facebook_id` (`facebook_id` ASC) VISIBLE,
  INDEX `email_index` (`email` ASC) VISIBLE,
  INDEX `username_index` (`username` ASC) VISIBLE,
  INDEX `phone_number_index` (`phone_number` ASC) VISIBLE,
  INDEX `google_id_index` (`google_id` ASC) VISIBLE,
  INDEX `microsoft_id_index` (`microsoft_id` ASC) VISIBLE,
  INDEX `facebook_id_index` (`facebook_id` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `ibex_ul_panel_database`.`installation_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ibex_ul_panel_database1`.`installation_log` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `component_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `installation_date` DATE NULL,
  `additional_data` JSON NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `component_id` (`component_id` ASC) VISIBLE,
  INDEX `user_id` (`user_id` ASC) VISIBLE,
  CONSTRAINT `installation_log_ibfk_1`
    FOREIGN KEY (`component_id`)
    REFERENCES `ibex_ul_panel_database1`.`ul_panel_components` (`id`),
  CONSTRAINT `installation_log_ibfk_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `ibex_ul_panel_database1`.`users` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `ibex_ul_panel_database`.`ul_panels`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ibex_ul_panel_database1`.`ul_panels` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `project_name` VARCHAR(255) NOT NULL,
  `panel_location` VARCHAR(255) NULL,
  `panel_manufacturer` VARCHAR(255) NULL,
  `panel_rating` INT NULL,
  `panel_serial_number` VARCHAR(255) NULL,
  `panel_installation_date` DATE NULL,
  `additional_data` JSON NULL,
  PRIMARY KEY (`id`),
  INDEX `panel_location_index` (`panel_location` ASC) VISIBLE,
  INDEX `panel_manufacturer_index` (`panel_manufacturer` ASC) VISIBLE,
  INDEX `panel_serial_number_index` (`panel_serial_number` ASC) VISIBLE,
  INDEX `panel_installation_date_index` (`panel_installation_date` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `ibex_ul_panel_database`.`part`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ibex_ul_panel_database1`.`part` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `panel_id` INT NOT NULL,
  `component_id` INT NOT NULL,
  `additional_data` JSON NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `panel_id` (`panel_id` ASC) VISIBLE,
  INDEX `component_id` (`component_id` ASC) VISIBLE,
  CONSTRAINT `panel_components_log_ibfk_1`
    FOREIGN KEY (`panel_id`)
    REFERENCES `ibex_ul_panel_database1`.`ul_panels` (`id`),
  CONSTRAINT `panel_components_log_ibfk_2`
    FOREIGN KEY (`component_id`)
    REFERENCES `ibex_ul_panel_database1`.`ul_panel_components` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `ibex_ul_panel_database`.`panel_maintanance_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ibex_ul_panel_database1`.`panel_maintanance_log` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `panel_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `maintanance_date` DATE NULL,
  `additional_data` JSON NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `panel_id` (`panel_id` ASC) VISIBLE,
  INDEX `user_id` (`user_id` ASC) VISIBLE,
  CONSTRAINT `panel_maintanance_log_ibfk_1`
    FOREIGN KEY (`panel_id`)
    REFERENCES `ibex_ul_panel_database1`.`ul_panels` (`id`),
  CONSTRAINT `panel_maintanance_log_ibfk_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `ibex_ul_panel_database1`.`users` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


