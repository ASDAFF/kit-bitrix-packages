<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\collections\Arrays;
use intec\core\io\Path;

/**
 * @var Arrays $blocks
 * @var array $block
 * @var array $data
 * @var string $page
 * @var Path $path
 * @global CMain $APPLICATION
 */

?>
<?php $APPLICATION->IncludeComponent(
	"intec.universe:main.services", 
	"template.4", 
	array(
        "IBLOCK_TYPE" => "#CATALOGS_SERVICES_IBLOCK_TYPE#",
        "IBLOCK_ID" => "#CATALOGS_SERVICES_IBLOCK_ID#",
        "ELEMENTS_COUNT" => "4",
        "SETTINGS_USE" => "Y",
        "LAZYLOAD_USE" => "N",
        "HEADER_BLOCK_SHOW" => "N",
        "DESCRIPTION_BLOCK_SHOW" => "N",
        "NUMBER_SHOW" => "Y",
        "DESCRIPTION_SHOW" => "Y",
        "DETAIL_SHOW" => "Y",
        "DETAIL_TEXT" => "Подробнее",
        "PARALLAX_USE" => "N",
        "FOOTER_SHOW" => "N",
        "SECTION_URL" => "",
        "DETAIL_URL" => "",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => 3600000,
        "SORT_BY" => "SORT",
        "ORDER_BY" => "ASC"
	),
	false
); ?>