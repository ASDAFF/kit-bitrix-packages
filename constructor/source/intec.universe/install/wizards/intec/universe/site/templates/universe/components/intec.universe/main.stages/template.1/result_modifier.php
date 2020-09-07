<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arResult['VISUAL'] = [
    'COUNT' => [
        'SHOW' => ArrayHelper::getValue($arParams, 'COUNT_SHOW') == 'Y'
    ],
    'DESCRIPTION' => [
        'SHOW' => ArrayHelper::getValue($arParams, 'ELEMENT_DESCRIPTION_SHOW') == 'Y'
    ]
];