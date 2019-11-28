SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__tripblog_trip`
-- -----------------------------------------------------

ALTER TABLE `#__tripblog_trip`
  ADD COLUMN `alias` varchar(200) NOT NULL DEFAULT '' AFTER `place`;

SET FOREIGN_KEY_CHECKS = 1;
