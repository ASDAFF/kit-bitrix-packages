<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'OVERLAY' => 'N'
], $arParams);

$arVisual = [
    'OVERLAY' => $arParams['OVERLAY'] === 'Y'
];

$arResult['VISUAL'] = $arVisual;