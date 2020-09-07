<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<?php $APPLICATION->IncludeComponent(
	'intec.universe:main.stages', 
	'template.3', 
	[
		'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
		'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
		'FILTER' => [
			'ID' => $arBlock['IBLOCK']['ELEMENTS']
		],
		'ELEMENTS_COUNT' => '',
		'PROPERTY_TIME' => '',
		'PROPERTY_TEXT_SOURCE' => 'preview',
		'HEADER_SHOW' => 'Y',
		'HEADER_POSITION' => 'center',
		'HEADER' => $arBlock['HEADER'],
		'DESCRIPTION_SHOW' => 'N',
		'ELEMENT_NAME_SIZE' => 'big',
		'CACHE_TYPE' => 'N',
		'SORT_BY' => 'SORT',
		'ORDER_BY' => 'ASC'
	],
	$component
) ?>
