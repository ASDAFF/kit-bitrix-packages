CREATE TABLE IF NOT EXISTS `sotbit_crosssell_options` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SITES` text NOT NULL,
  `SORT` int NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `SYMBOL_CODE` varchar(255) ,
  `FOREIGN_CODE` varchar(255),
  `FIRST_IMG` text DEFAULT NULL,
  `SECOND_IMG` text DEFAULT NULL,
  `FIRST_IMG_DESC` text DEFAULT NULL,
  `SECOND_IMG_DESC` text DEFAULT NULL,
  `EXTRA_SETTINGS` text,
  `RULE1` text,
  `RULE2` text,
  `RULE3` text,
  `SORT_ORDER` varchar(10) DEFAULT 'asc',
  `SORT_BY` varchar(10) DEFAULT 'name',
  `NUMBER_PRODUCTS` int DEFAULT '10',
  `CATEGORY_ID` INT(11) NULL,
  `DATE_CREATE` timestamp not null default current_timestamp,
  `Active` varchar(10),
  `TYPE_BLOCK` varchar (50),
  `PRODUCTS` text,
  `NUMBER_OF_MATCHED_PRODUCTS` int(11),
  PRIMARY KEY (`ID`)
);

CREATE TABLE IF NOT EXISTS `sotbit_crosssell_section` (
    `ID` int(11) not null auto_increment,
    `PARENT_ID` INT(11) NULL,
    `TIMESTAMP_X` timestamp not null,
    `DATE_CREATE` timestamp not null default current_timestamp,
    `SITE_ID` VARCHAR(255) null,
    `NAME` VARCHAR(255) null,
    `SORT` VARCHAR(255) not null,
    `EXTRA_SETTINGS` text,
    `SYMBOL_CODE` VARCHAR(255) null,
    primary key (`ID`)
);




