<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<div class="catalog-element-projects">
	<?php $APPLICATION->IncludeComponent(
		'intec.universe:main.projects',
		'template.1',
		[
			'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
			'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
			'SECTIONS' => [],
			'FILTER' => [
				'ID' => $arBlock['IBLOCK']['ELEMENTS']
			],
			'ELEMENTS_COUNT' => '',
			'HEADER_SHOW' => 'Y',
			'HEADER_POSITION' => $arBlock['HEADER']['POSITION'],
			'HEADER_TEXT' => $arBlock['HEADER']['VALUE'],
			'DESCRIPTION_SHOW' => 'N',
			'WIDE' => 'Y',
			'COLUMNS' => '4',
			'SLIDER_USE' => 'N',
			'TABS_USE' => 'N',
			'LINK_USE' => 'Y',
			'FOOTER_SHOW' => 'N',
			'LIST_PAGE_URL' => '',
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
</div>
