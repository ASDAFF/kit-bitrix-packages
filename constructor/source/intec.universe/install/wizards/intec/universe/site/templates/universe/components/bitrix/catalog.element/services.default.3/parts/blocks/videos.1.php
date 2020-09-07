<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<div class="catalog-element-videos">
	<?php $APPLICATION->IncludeComponent(
		'intec.universe:main.videos',
		'template.1',
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
			'HEADER_POSITION' => $arBlock['HEADER']['POSITION'],
			'HEADER' => $arBlock['HEADER']['VALUE'],
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
</div>