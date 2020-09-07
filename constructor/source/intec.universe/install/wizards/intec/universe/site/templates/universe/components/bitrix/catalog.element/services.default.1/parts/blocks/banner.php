<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arBlock
 */

?>
<?php $APPLICATION->IncludeComponent(
    'intec.universe:main.slider',
    'template.1',
    ArrayHelper::merge($arBlock['PARAMETERS'], [
        'IBLOCK_TYPE' => $arBlock['IBLOCK']['TYPE'],
        'IBLOCK_ID' => $arBlock['IBLOCK']['ID'],
        'FILTER' => [
            'ID' => $arBlock['IBLOCK']['ELEMENTS']
        ],
        'ELEMENTS_COUNT' => '',
        'SECTIONS' => null,
        'BUTTONS_BACK_LINK' => $arResult['LIST_PAGE_URL'],
        'CACHE_TYPE' => 'N',
        'SETTINGS_USE' => 'N',
        'LAZYLOAD_USE' => $arResult['LAZYLOAD']['USE'] ? 'Y' : 'N'
    ]),
    $component
) ?>
