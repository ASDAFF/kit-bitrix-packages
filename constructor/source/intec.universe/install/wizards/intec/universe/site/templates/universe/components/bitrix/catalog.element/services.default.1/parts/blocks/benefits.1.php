<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<?php $APPLICATION->IncludeComponent(
    'intec.universe:main.advantages',
    'template.6',
    [
        'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
        'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
        'SECTIONS' => [],
        'FILTER' => [
            'ID' => $arBlock['IBLOCK']['ELEMENTS']
        ],
        'ELEMENTS_COUNT' => '',
        'HEADER_SHOW' => 'Y',
        'HEADER_POSITION' => 'center',
        'HEADER' => $arBlock['HEADER'],
        'DESCRIPTION_SHOW' => 'Y',
        'DESCRIPTION_POSITION' => 'center',
        'DESCRIPTION' => $arBlock['DESCRIPTION'],
        'COLUMNS' => '3',
        'VIEW' => 'point',
        'ALIGNMENT' => 'left',
        'NAME_SHOW' => 'Y',
        'CACHE_TYPE' => 'N',
        'SORT_BY' => 'SORT',
        'ORDER_BY' => 'ASC',
        'SETTINGS_USE' => 'N',
        'LAZYLOAD_USE' => $arResult['LAZYLOAD']['USE'] ? 'Y' : 'N'
    ],
    $component
) ?>