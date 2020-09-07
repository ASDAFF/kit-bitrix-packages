<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<?php $APPLICATION->IncludeComponent(
	'intec.universe:main.brands', 
	'template.2', 
	[
		'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
		'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
		'FILTER' => [
			'ID' => $arBlock['IBLOCK']['ELEMENTS']
		],
		'ELEMENTS_COUNT' => '',
		'HEADER_SHOW' => 'Y',
		'HEADER_POSITION' => 'center',
		'HEADER_TEXT' => $arBlock['HEADER'],
		'DESCRIPTION_SHOW' => 'N',
		'COLUMNS' => '5',
		'LINK_USE' => 'Y',
		'BACKGROUND_USE' => 'Y',
		'BACKGROUND_THEME' => 'dark',
		'OPACITY' => '50',
		'GRAYSCALE' => 'N',
		'FOOTER_SHOW' => 'Y',
        "FOOTER_POSITION" => "center",
        "FOOTER_BUTTON_SHOW" => "Y",
        "FOOTER_BUTTON_TEXT" => $arBlock['FOOTER']['BUTTON_TEXT'],
		'LIST_PAGE_URL' => $arBlock['FOOTER']['LIST_PAGE_URL'],
		'SECTION_URL' => '',
		'DETAIL_URL' => '',
		'CACHE_TYPE' => 'N',
		'SORT_BY' => 'SORT',
		'ORDER_BY' => 'ASC',
        'SETTINGS_USE' => 'N',
        'LAZYLOAD_USE' => $arResult['LAZYLOAD']['USE'] ? 'Y' : 'N'
	],
	$component
) ?>
