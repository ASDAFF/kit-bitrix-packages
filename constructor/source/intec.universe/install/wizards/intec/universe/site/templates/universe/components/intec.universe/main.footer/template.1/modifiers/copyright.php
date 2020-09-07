<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global $APPLICATION
 */

$arParams = ArrayHelper::merge([
    'COPYRIGHT_SHOW' => 'N',
    'COPYRIGHT_VALUE' => null
], $arParams);

$arResult['COPYRIGHT'] = [
    'SHOW' => $arParams['COPYRIGHT_SHOW'] === 'Y',
    'VALUE' => $arParams['COPYRIGHT_VALUE']
];

if (!empty($arResult['COPYRIGHT']['VALUE'])) {
    $arResult['COPYRIGHT']['VALUE'] = StringHelper::replaceMacros($arResult['COPYRIGHT']['VALUE'], [
        'YEAR' => date('Y'),
        'MONTH' => date('m'),
        'DAY' => date('d')
    ]);
} else {
    $arResult['COPYRIGHT']['SHOW'] = false;
}