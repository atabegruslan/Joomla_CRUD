SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__tripblog_likes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__tripblog_likes` ;

CREATE TABLE IF NOT EXISTS `#__tripblog_likes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tripblog_id` int(10) UNSIGNED NOT NULL,
  `tripblog_user_id` int(10) UNSIGNED NOT NULL,
  `likes` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET FOREIGN_KEY_CHECKS=1;

