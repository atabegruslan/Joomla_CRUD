SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__tripblog_trip`
-- -----------------------------------------------------

ALTER TABLE `#__tripblog_trip`
  ADD COLUMN `created` int(11) NOT NULL AFTER `checked_out_time`;

SET FOREIGN_KEY_CHECKS = 1;
