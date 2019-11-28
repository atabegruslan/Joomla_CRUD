SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__tripblog_user`
-- -----------------------------------------------------

ALTER TABLE `#__tripblog_user`
  ADD COLUMN `image` varchar(255) NULL DEFAULT NULL AFTER `password`;

SET FOREIGN_KEY_CHECKS = 1;
