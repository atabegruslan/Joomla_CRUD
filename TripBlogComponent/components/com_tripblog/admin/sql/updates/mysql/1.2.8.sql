SET FOREIGN_KEY_CHECKS = 0;

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

SET FOREIGN_KEY_CHECKS = 1;
