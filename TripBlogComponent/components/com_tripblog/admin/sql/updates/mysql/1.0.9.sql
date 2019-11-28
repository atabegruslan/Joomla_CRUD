SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__tripblog_trip`
-- -----------------------------------------------------

ALTER TABLE `#__tripblog_trip`
  ADD COLUMN `image` varchar(255) NULL DEFAULT NULL AFTER `review`;

SET FOREIGN_KEY_CHECKS = 1;
