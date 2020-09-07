<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arBanner = [
    'TYPE' => null,
    'PRICE' => [],
    'BUTTON' => [
        'SHOW' => false
    ],
    'PICTURE',
    'CHARACTERISTICS'
];

if (!empty($arParams['PROPERTY_TYPE'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_TYPE']
    ]);

    if (!empty($arProperty['VALUE'])) {
        if ($arProperty['MULTIPLE'] === 'Y')
            $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

        $arBanner['TYPE'] = $arProperty['VALUE'];
    }
}

if (!empty($arParams['PROPERTY_PRICE'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_PRICE']
    ]);

    if (!empty($arProperty['VALUE'])) {
        if ($arProperty['MULTIPLE'] === 'Y')
            $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

        $arBanner['PRICE']['CURRENT'] = $arProperty['VALUE'];
    }
}

if (!empty($arParams['PROPERTY_PRICE_OLD'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_PRICE_OLD']
    ]);

    if (!empty($arProperty['VALUE'])) {
        if ($arProperty['MULTIPLE'] === 'Y')
            $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

        $arBanner['PRICE']['OLD'] = $arProperty['VALUE'];
    }
}

if (!empty($arParams['PROPERTY_BUTTON_URL'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_BUTTON_URL']
    ]);

    if (!empty($arProperty['VALUE'])) {
        if ($arProperty['MULTIPLE'] === 'Y')
            $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

        $arBanner['BUTTON']['URL'] = StringHelper::replaceMacros($arProperty['VALUE'], [
            'SITE_DIR' => SITE_DIR
        ]);
    }
}

if (!empty($arParams['PROPERTY_BUTTON_SHOW'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_BUTTON_SHOW']
    ]);

    if (!empty($arProperty['VALUE']) || !empty($arProperty['VALUE_XML_ID']))
        $arBanner['BUTTON']['SHOW'] = !empty($arBanner['BUTTON']['URL']) ? true : false;
}

if (!empty($arParams['PROPERTY_BUTTON_TEXT'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_BUTTON_TEXT']
    ]);

    if (!empty($arProperty['VALUE'])) {
        if ($arProperty['MULTIPLE'] === 'Y')
            $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

        $arBanner['BUTTON']['TEXT'] = $arProperty['VALUE'];
    }
}

if (!empty($arParams['PROPERTY_PICTURE'])) {
    $arProperty = ArrayHelper::getValue($arResult, [
        'PROPERTIES',
        $arParams['PROPERTY_PICTURE']
    ]);

    if (!empty($arProperty['VALUE'])) {
        if ($arProperty['MULTIPLE'] === 'Y')
            $arProperty['VALUE'] = ArrayHelper::getFirstValue($arProperty['VALUE']);

        $arBanner['PICTURE'] = $arProperty['VALUE'];
    }
}

if (!empty($arParams['PROPERTY_CHARACTERISTICS']) && Type::isArray($arParams['PROPERTY_CHARACTERISTICS'])) {
    $hGetType = function (&$arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'S' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'N' ||
            $arProperty['PROPERTY_TYPE'] == 'L' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'N')
            return 'string';

        if ($arProperty['PROPERTY_TYPE'] == 'L' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'Y')
            return 'list';

        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'C' && $arProperty['MULTIPLE'] === 'N')
            return 'bool';

        return false;
    };

    foreach ($arParams['PROPERTY_CHARACTERISTICS'] as $sCharacteristic) {
        $arProperty = ArrayHelper::getValue($arResult, [
            'PROPERTIES',
            $sCharacteristic
        ]);

        if (!empty($arProperty['VALUE'])) {
            $arPropertyType = $hGetType($arProperty);

            if (!$arPropertyType) {
                continue;
            } else if ($arPropertyType === 'string') {
                $arBanner['CHARACTERISTICS'][] = [
                    'TYPE' => $arPropertyType,
                    'NAME' => $arProperty['NAME'],
                    'VALUE' => $arProperty['VALUE']
                ];
            } else if ($arPropertyType === 'list') {
                $arBanner['CHARACTERISTICS'][] = [
                    'TYPE' => $arPropertyType,
                    'NAME' => $arProperty['NAME'],
                    'VALUES' => $arProperty['VALUE']
                ];
            } else if ($arPropertyType === 'bool') {
                $arBanner['CHARACTERISTICS'][] = [
                    'TYPE' => $arPropertyType,
                    'NAME' => $arProperty['NAME']
                ];
            }
        }
    }

    unset($hGetType, $sCharacteristic);
}

$arResult['DATA']['BANNER'] = $arBanner;

unset($arBanner, $arProperty);