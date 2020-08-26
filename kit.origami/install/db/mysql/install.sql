CREATE TABLE IF NOT EXISTS `kit_origami_options` (
	`CODE` VARCHAR(50) not null,
	`VALUE` TEXT,
	`SITE_ID` CHAR(2),
	UNIQUE ix_option(CODE, SITE_ID),
	INDEX ix_option_name(CODE)
);
CREATE TABLE IF NOT EXISTS `kit_origami_blocks` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CODE` varchar(255) NOT NULL,
  `PART` varchar(255) NOT NULL,
  `SORT` int(11) NOT NULL,
  `ACTIVE` char(1) NOT NULL,
  `CREATED_BY` int(11) NOT NULL,
  `DATE_CREATE` datetime NOT NULL,
  `MODIFIED_BY` int(11) NOT NULL,
  `DATE_MODIFY` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
)