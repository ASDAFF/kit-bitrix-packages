<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<?php $APPLICATION->IncludeComponent(
	'intec.universe:main.videos', 
	'template.2', 
	[
		'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
		'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
		'FILTER' => [
			'ID' => $arBlock['IBLOCK']['ELEMENTS']
		],
		'ELEMENTS_COUNT' => '',
		'PICTURE_SOURCES' => [
			'service',
			'preview',
			'detail',
		],
		'PICTURE_SERVICE_QUALITY' => 'sddefault',
		'PROPERTY_URL' => $arBlock['IBLOCK']['PROPERTIES']['LINK'],
		'HEADER_SHOW' => 'Y',
		'HEADER_POSITION' => 'center',
		'HEADER' => $arBlock['HEADER'],
		'DESCRIPTION_SHOW' => 'N',
		'FOOTER_SHOW' => 'N',
		'CACHE_TYPE' => 'N',
		'SORT_BY' => 'sort',
		'ORDER_BY' => 'asc',
        'SETTINGS_USE' => 'N',
        'LAZYLOAD_USE' => $arResult['LAZYLOAD']['USE'] ? 'Y' : 'N'
	],
	$component
) ?>
