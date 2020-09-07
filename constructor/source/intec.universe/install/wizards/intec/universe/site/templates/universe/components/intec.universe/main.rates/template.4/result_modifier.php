<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'PROPERTY_PRICE' => null,
    'PROPERTY_CURRENCY' => null,
    'COLUMNS' => 4,
    'PRICE_SHOW' => 'N',
    'PRICE_LIST' => null,
    'PRICE_LIST_SHOW' => 'N',
    'PRICE_LIST_BUTTON_TEXT' => null
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

$arVisual = [
    'COLUMNS' => ArrayHelper::fromRange([4, 3], $arParams['COLUMNS']),
    'PRICE' => [
        'SHOW' => $arParams['PRICE_SHOW'] === 'Y'
    ],
    'PRICE_LIST' => [
        'SHOW' => $arParams['PRICE_LIST_SHOW'] === 'Y',
        'BUTTON_TEXT' => !empty($arParams['PRICE_LIST_BUTTON_TEXT']) ? $arParams['~PRICE_LIST_BUTTON_TEXT'] : Loc::getMessage('C_MAIN_RATES_TEMPLATE_4_TEMPLATE_PRICE_LIST_BUTTON_DEFAULT')
    ]
];

$arResult['VISUAL'] = ArrayHelper::merge($arVisual, $arResult['VISUAL']);

unset($arVisual);

$arResult['PRICE_LIST']['LINK'] = StringHelper::replaceMacros($arParams['PRICE_LIST'], $arMacros);

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'PRICE' => null,
        'CURRENCY' => null,
    ];

    if (!empty($arParams['PROPERTY_PRICE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_PRICE'],
            'VALUE'
        ]);

        if (!Type::isArray($arProperty))
            $arItem['DATA']['PRICE'] = $arProperty;
        else
            $arItem['DATA']['PRICE'] = ArrayHelper::getFirstValue($arProperty);
    }

    if (!empty($arParams['PROPERTY_CURRENCY'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_CURRENCY'],
            'VALUE'
        ]);

        if (!Type::isArray($arProperty))
            $arItem['DATA']['CURRENCY'] = $arProperty;
        else
            $arItem['DATA']['CURRENCY'] = ArrayHelper::getFirstValue($arProperty);
    }

}