<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'ICON_USE' => 'N',
    'TEXT_SHOW' => 'Y',
], $arParams);

$arVisual = [
    'ICON' => [
        'USE' => $arParams['ICON_USE'] == 'Y'
    ],
    'TEXT' => [
        'SHOW' => $arParams['TEXT_SHOW'] == 'Y'
    ]
];

$arResult['VISUAL'] = $arVisual;
unset($arVisual);