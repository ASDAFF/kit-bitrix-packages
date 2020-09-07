<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global $APPLICATION
 */

$arParams = ArrayHelper::merge([
    'SEARCH_SHOW' => 'N',
    'SEARCH_MODE' => 'site'
], $arParams);

$arResult['SEARCH'] = [
    'SHOW' => $arParams['SEARCH_SHOW'] === 'Y',
    'MODE' => ArrayHelper::fromRange([
        'site',
        'catalog'
    ], $arParams['SEARCH_MODE'])
];