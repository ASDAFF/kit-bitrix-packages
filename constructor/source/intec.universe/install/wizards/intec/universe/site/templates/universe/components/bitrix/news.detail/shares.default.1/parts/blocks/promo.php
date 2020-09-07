<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<div class="news-detail-promo">
	<?php $APPLICATION->IncludeComponent(
		"intec.universe:main.advantages",
		"template.29",
		Array(
			'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
			'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
			'FILTER' => [
				'ID' => $arBlock['IBLOCK']['ELEMENTS']
			],
            'SETTINGS_USE' => 'N',
			'LAZYLOAD_USE' => $arVisual['LAZYLOAD']['USE'] ? 'Y' : 'N',
			'DESCRIPTION_SHOW' => 'N',
			'HEADER_SHOW' => 'N',
			'PICTURE_SHOW' => 'Y',
			'PREVIEW_SHOW' => 'Y',
			'SECTIONS' => null,
			'CACHE_TYPE' => 'N'
		),
		$component
	) ?>
</div>