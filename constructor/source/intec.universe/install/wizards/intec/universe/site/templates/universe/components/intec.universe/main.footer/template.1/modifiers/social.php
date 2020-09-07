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
    'SOCIAL_SHOW' => 'N'
], $arParams);

$arResult['SOCIAL'] = [
    'SHOW' => false,
    'ITEMS' => [
        'VK' => null,
        'FACEBOOK' => null,
        'INSTAGRAM' => null,
        'TWITTER' => null
    ]
];

foreach ($arResult['SOCIAL']['ITEMS'] as $sKey => $arItem) {
    $arItem = [
        'CODE' => $sKey,
        'SHOW' => false,
        'LINK' => ArrayHelper::getValue($arParams, 'SOCIAL_'.$sKey.'_LINK')
    ];

    if (!empty($arItem['LINK'])) {
        $arItem['SHOW'] = true;
        $arResult['SOCIAL']['SHOW'] = true;
    }

    $arResult['SOCIAL']['ITEMS'][$sKey] = $arItem;
}

$arResult['SOCIAL']['SHOW'] =
    $arResult['SOCIAL']['SHOW'] &&
    $arParams['SOCIAL_SHOW'] === 'Y';

unset($sKey);
unset($arItem);