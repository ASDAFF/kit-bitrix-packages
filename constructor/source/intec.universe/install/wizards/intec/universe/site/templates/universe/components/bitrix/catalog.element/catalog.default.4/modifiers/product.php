<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;

/**
 * @var array $arResult
 * @var array $arParams
 * @var boolean $bBase
 */

$arResult['ACTION'] = ArrayHelper::fromRange(['buy', 'order', 'none'], $arParams['ACTION']);

$arResult['URL'] = [
    'BASKET' => $arParams['BASKET_URL'],
    'CONSENT' => $arParams['CONSENT_URL']
];

$arResult['DELAY'] = [
    'USE' => $arParams['DELAY_USE'] === 'Y' && $bBase && $arResult['ACTION'] === 'buy'
];

$arResult['COMPARE'] = [
    'USE' => $arParams['USE_COMPARE'] === 'Y' && !empty($arParams['COMPARE_NAME']),
    'CODE' => $arParams['COMPARE_NAME']
];