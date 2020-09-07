<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<div class="catalog-element-gallery">
    <?php $APPLICATION->IncludeComponent(
        'intec.universe:main.gallery',
        'template.2',
        [
            'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
            'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
            'SECTIONS' => [],
            'FILTER' => [
                'ID' => $arBlock['IBLOCK']['ELEMENTS']
            ],
            'ELEMENTS_COUNT' => '4',
            'HEADER_SHOW' => 'Y',
            'HEADER_POSITION' => $arBlock['HEADER']['POSITION'],
            'HEADER_TEXT' => $arBlock['HEADER']['VALUE'],
            'DESCRIPTION_SHOW' => 'N',
            'WIDE' => 'N',
            'LINE_COUNT' => '4',
            'FOOTER_SHOW' => 'N',
            'CACHE_TYPE' => 'N',
            'SORT_BY' => 'SORT',
            'ORDER_BY' => 'ASC',
            'SETTINGS_USE' => 'N',
            'LAZYLOAD_USE' => $arResult['LAZYLOAD']['USE'] ? 'Y' : 'N'
        ],
        $component
    ) ?>
</div>
