<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$arParams = ArrayHelper::merge([
    'ICONS' => []
], $arParams);

if (!Type::isArray($arParams['ICONS']))
    $arParams['ICONS'] = [];

$arResult['ICONS'] = [
    'SHOW' => false,
    'ITEMS' => [
        'ALFABANK' => null,
        'SBERBANK' => null,
        'QIWI' => null,
        'YANDEXMONEY' => null,
        'VISA' => null,
        'MASTERCARD' => null
    ]
];

foreach ($arResult['ICONS']['ITEMS'] as $sKey => $arItem) {
    $arItem = [
        'CODE' => $sKey,
        'SHOW' => false
    ];

    if (ArrayHelper::isIn($sKey, $arParams['ICONS'])) {
        $arItem['SHOW'] = true;
        $arResult['ICONS']['SHOW'] = true;
    }

    $arResult['ICONS']['ITEMS'][$sKey] = $arItem;
}

unset($sKey);
unset($arItem);