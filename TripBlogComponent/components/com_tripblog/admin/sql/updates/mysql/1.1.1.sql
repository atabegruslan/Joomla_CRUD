SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__tripblog_trip`
-- -----------------------------------------------------

ALTER TABLE `#__tripblog_trip`
  ADD COLUMN `published` tinyint(4) NOT NULL DEFAULT '1' AFTER `image`;

SET FOREIGN_KEY_CHECKS = 1;
