SET FOREIGN_KEY_CHECKS=0;

-- -----------------------------------------------------
-- Table `#__tripblog_trip`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__tripblog_trip`;

CREATE TABLE IF NOT EXISTS `#__tripblog_trip` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `place` varchar(200) NOT NULL DEFAULT '',
    `alias` varchar(200) NOT NULL DEFAULT '',
    `country` int(10) NOT NULL,
    `review` text,
    `image` varchar(255) NULL DEFAULT NULL,
    `published` tinyint(4) NOT NULL DEFAULT '1',
    `checked_out` INT(10) NOT NULL DEFAULT '0',
	`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`created` int(11) NOT NULL,
    PRIMARY KEY (`id`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `#__tripblog_country`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__tripblog_country` ;

CREATE TABLE IF NOT EXISTS `#__tripblog_country` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

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
  `image` varchar(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

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

-- -----------------------------------------------------
-- Table `#__tripblog_country_continent_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__tripblog_country_continent_xref`;

CREATE TABLE IF NOT EXISTS `#__tripblog_country_continent_xref` (
    `country_id` int(10) unsigned NOT NULL,
    `continent_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`country_id`, `continent_id`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

SET FOREIGN_KEY_CHECKS=1;
