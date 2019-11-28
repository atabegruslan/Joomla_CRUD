
-- -----------------------------------------------------
-- Table `#__tripblog_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__tripblog_user` ;

CREATE TABLE IF NOT EXISTS `#__tripblog_user` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `joomla_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
