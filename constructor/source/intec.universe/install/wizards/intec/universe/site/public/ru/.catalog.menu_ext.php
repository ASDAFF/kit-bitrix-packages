<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php
/**
 * @var array $aMenuLinks
 */

global $APPLICATION;

$aMenuLinksExt = $APPLICATION->IncludeComponent(
	"intec.universe:menu.sections", 
	"", 
	array(
		"IS_SEF" => "Y",
		"SEF_BASE_URL" => "#SITE_DIR#catalog/",
		"SECTION_PAGE_URL" => "#SECTION_CODE#/",
		"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_ID#",
		"IBLOCK_TYPE" => "#CATALOGS_PRODUCTS_IBLOCK_TYPE#",
		"IBLOCK_ID" => "#CATALOGS_PRODUCTS_IBLOCK_ID#",
		"DEPTH_LEVEL" => "4",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"ID" => $_REQUEST["ID"],
		"SECTION_URL" => "#SITE_DIR#/catalog/?SECTION_ID=#ID#"
	),
	false
);

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
