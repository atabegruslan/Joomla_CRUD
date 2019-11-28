SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__tripblog_trip`
-- -----------------------------------------------------

ALTER TABLE `#__tripblog_trip`
  ADD COLUMN `checked_out` INT(10) NOT NULL DEFAULT '0' AFTER `published`;

ALTER TABLE `#__tripblog_trip`
  ADD COLUMN `checked_out_time` INT(10) NOT NULL DEFAULT '0' AFTER `checked_out`;

SET FOREIGN_KEY_CHECKS = 1;
