CREATE TABLE IF NOT EXISTS `b_sotbit_opengraph_page_meta` (
    `ID` int(11) not null auto_increment,
    `NAME` VARCHAR(255) not null,
    `ACTIVE_OG` char(1) DEFAULT 'N',
    `ACTIVE_TW` char(1) DEFAULT 'N',
    `OG_TITLE` VARCHAR(255) not null,
    `OG_IMAGE` int(11) null,
    `OG_TYPE` VARCHAR(255) not null,
    `OG_URL` VARCHAR(255) not null,
    `OG_IMAGE_TYPE` VARCHAR(255) null,
    `OG_IMAGE_WIDTH` VARCHAR(255) null,
    `OG_IMAGE_HEIGHT` VARCHAR(255) null,
    `OG_IMAGE_SECURE_URL` VARCHAR(255) null,
    `OG_DESCRIPTION` VARCHAR(255) null,
    `OG_PROPS_ACTIVE` TEXT null,
    `TW_CARD` VARCHAR(255) not null,
    `TW_TITLE` VARCHAR(255) not null,
    `TW_SITE` VARCHAR(255) null,
    `TW_IMAGE` int(11) null,
    `TW_DESCRIPTION` VARCHAR(255) null,
    `TW_IMAGE_ALT` VARCHAR(255) null,
    `TW_CREATOR` VARCHAR(255) null,
    `TW_PROPS_ACTIVE` TEXT null,
    `CATEGORY_ID` INT(11) NULL,
    `ORDER` VARCHAR(255) NULL,
    `TIMESTAMP_X` timestamp not null default current_timestamp on update current_timestamp,
    `DATE_CREATE` timestamp not null default current_timestamp,
    `SITE_ID` VARCHAR(255) null,
    `SORT` VARCHAR(255) null,
    primary key (`ID`)
);

CREATE TABLE IF NOT EXISTS `b_sotbit_opengraph_section` (
    `ID` int(11) not null auto_increment,
    `PARENT_ID` INT(11) NULL,
    `TIMESTAMP_X` timestamp not null default current_timestamp on update current_timestamp,
    `DATE_CREATE` timestamp not null default current_timestamp,
    `SITE_ID` VARCHAR(255) null,
    `NAME` VARCHAR(255) null,
    `SORT` VARCHAR(255) not null,
    primary key (`ID`)
);