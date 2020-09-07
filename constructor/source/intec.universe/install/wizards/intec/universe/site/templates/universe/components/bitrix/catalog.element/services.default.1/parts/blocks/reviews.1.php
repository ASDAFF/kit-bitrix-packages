<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<!--noindex-->
<?php $APPLICATION->IncludeComponent(
	'intec.universe:main.reviews', 
	'template.9', 
	[
		'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
		'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
		'FILTER' => [
			'ID' => $arBlock['IBLOCK']['ELEMENTS']
		],
		'ELEMENTS_COUNT' => '',
		'MODE' => 'N',
		'SECTIONS' => [],
		'PROPERTY_POSITION' => $arBlock['IBLOCK']['PROPERTIES']['POSITION'],
		'HEADER_SHOW' => 'Y',
		'HEADER_POSITION' => 'center',
		'HEADER_TEXT' => $arBlock['HEADER'],
		'DESCRIPTION_SHOW' => 'N',
		'LINK_USE' => 'N',
		'SLIDER_LOOP' => 'N',
		'SLIDER_AUTO_USE' => 'N',
		'FOOTER_SHOW' => 'Y',
		'LIST_PAGE_URL' => $arBlock['PAGE'],
		'SECTION_URL' => '',
		'DETAIL_URL' => '',
		'CACHE_TYPE' => 'N',
		'SORT_BY' => 'SORT',
		'ORDER_BY' => 'ASC',
		'FOOTER_POSITION' => 'center',
		'FOOTER_BUTTON_SHOW' => $arBlock['BUTTON']['SHOW'] ? 'Y' : 'N',
		'FOOTER_BUTTON_TEXT' => $arBlock['BUTTON']['TEXT'],
        'SETTINGS_USE' => 'N',
        'LAZYLOAD_USE' => $arResult['LAZYLOAD']['USE'] ? 'Y' : 'N'
	],
	$component
) ?>
<!--/noindex-->
