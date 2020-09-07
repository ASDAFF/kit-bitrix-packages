<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'PROPERTY_PRICE' => null,
    'PROPERTY_CURRENCY' => null,
    'PROPERTY_DISCOUNT' => null,
    'PROPERTY_DISCOUNT_TYPE' => null,
    'PROPERTY_DETAIL_URL' => null,
    'PROPERTY_LIST' => null,
    'COLUMNS' => 3,
    'VIEW' => 'tabs',
    'TABS_POSITION' => 'center',
    'SECTION_DESCRIPTION_SHOW' => 'N',
    'SECTION_DESCRIPTION_POSITION' => 'center',
    'COUNTER_SHOW' => 'N',
    'COUNTER_TEXT' => null,
    'PRICE_SHOW' => 'N',
    'DISCOUNT_SHOW' => 'N',
    'PREVIEW_SHOW' => 'N',
    'PROPERTIES_SHOW' => 'N',
    'BUTTON_SHOW' => 'N',
    'BUTTON_TEXT' => null,
    'BUTTON_MODE' => 'order',
    'DETAIL_BLANK' => 'N',
    'ORDER_FORM_ID' => null,
    'ORDER_FORM_TEMPLATE' => '.default',
    'ORDER_FORM_FIELD' => null,
    'ORDER_FORM_TITLE' => null,
    'ORDER_CONSENT' => null,
    'SLIDER_USE' => 'N',
    'SLIDER_NAV' => 'N',
    'SLIDER_DOTS' => 'N',
    'SLIDER_LOOP' => 'N',
    'SLIDER_AUTO_USE' => 'N',
    'SLIDER_AUTO_TIME' => 10000,
    'SLIDER_AUTO_HOVER' => 'N'
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

$arVisual = [
    'COLUMNS' => ArrayHelper::fromRange([3, 4], $arParams['COLUMNS']),
    'VIEW' => ArrayHelper::fromRange(['tabs', 'simple'], $arParams['VIEW']),
    'TABS' => [
        'POSITION' => ArrayHelper::fromRange(['center', 'left', 'right'], $arParams['TABS_POSITION'])
    ],
    'SECTION' => [
        'DESCRIPTION' => [
            'SHOW' => $arParams['SECTION_DESCRIPTION_SHOW'] === 'Y',
            'POSITION' => ArrayHelper::fromRange(['center', 'left', 'right'], $arParams['SECTION_DESCRIPTION_POSITION'])
        ]
    ],
    'COUNTER' => [
        'SHOW' => $arParams['COUNTER_SHOW'] === 'Y',
        'TEXT' => $arParams['COUNTER_TEXT']
    ],
    'PRICE' => [
        'SHOW' => !empty($arParams['PROPERTY_PRICE']) && $arParams['PRICE_SHOW'] === 'Y'
    ],
    'DISCOUNT' => [
        'SHOW' => !empty($arParams['PROPERTY_DISCOUNT']) && $arParams['DISCOUNT_SHOW'] === 'Y'
    ],
    'PREVIEW' => [
        'SHOW' => $arParams['PREVIEW_SHOW'] === 'Y'
    ],
    'PROPERTIES' => [
        'SHOW' => !empty($arParams['PROPERTY_LIST']) && $arParams['PROPERTIES_SHOW'] === 'Y'
    ],
    'BUTTON' => [
        'SHOW' => $arParams['BUTTON_SHOW'] === 'Y',
        'TEXT' => $arParams['BUTTON_TEXT'],
        'MODE' => ArrayHelper::fromRange(['order', 'detail'], $arParams['BUTTON_MODE'])
    ],
    'DETAIL' => [
        'BLANK' => $arParams['DETAIL_BLANK'] === 'Y'
    ],
    'SLIDER' => [
        'USE' => $arParams['SLIDER_USE'] === 'Y',
        'NAV' => $arParams['SLIDER_NAV'] === 'Y',
        'DOTS' => $arParams['SLIDER_DOTS'] === 'Y',
        'LOOP' => $arParams['SLIDER_LOOP'] === 'Y',
        'AUTO' => [
            'USE' => $arParams['SLIDER_AUTO_USE'] === 'Y',
            'TIME' => Type::toInteger($arParams['SLIDER_AUTO_TIME']),
            'HOVER' => $arParams['SLIDER_AUTO_HOVER'] === 'Y'
        ]
    ]
];

if (!$arVisual['SLIDER']['AUTO']['TIME'] || $arVisual['SLIDER']['AUTO']['TIME'] < 1)
    $arVisual['SLIDER']['AUTO']['TIME'] = 10000;

$arResult['VISUAL'] = ArrayHelper::merge($arVisual, $arResult['VISUAL']);

unset($arVisual);

$arForm = [
    'SHOW' => false,
    'ID' => $arParams['ORDER_FORM_ID'],
    'TEMPLATE' => $arParams['ORDER_FORM_TEMPLATE'],
    'FIELD' => $arParams['ORDER_FORM_FIELD'],
    'TITLE' => $arParams['ORDER_FORM_TITLE'],
    'CONSENT' => $arParams['ORDER_CONSENT']
];

if (!empty($arForm['ID']) && !empty($arForm['TEMPLATE']) && !empty($arForm['FIELD']))
    $arForm['SHOW'] = true;

$arResult['FORM'] = $arForm;

unset($arForm);

$hSetPropertyText = function (&$item, $property = '') {
    $result = null;

    if (!empty($item) && !empty($property)) {
        $arProperty = ArrayHelper::getValue($item, [
            'PROPERTIES',
            $property,
            'VALUE'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty)) {
                if (ArrayHelper::keyExists('TEXT', $arProperty))
                    $arProperty = Html::stripTags($arProperty['TEXT']);
                else
                    $arProperty = implode(', ', $arProperty);

                if (empty($arProperty))
                    $arProperty = null;
            }

            $result = $arProperty;
        }
    }

    return $result;
};

$hGetDiscountPrice = function ($price = '', $discount = []) {
    $result = null;

    if (!empty($price)) {
        $value = 0;

        if (!empty($discount['VALUE'])) {
            if ($discount['TYPE'] !== 'value')
                if ($discount['VALUE'] > 0 && $discount['VALUE'] <= 100)
                    $value = $price / 100 * $discount['VALUE'];
                else
                    $value = 0;
            else {
                $value = $discount['VALUE'];

                if ($value < 0)
                    $value = 0;
            }
        }

        $result = round($price - $value, 2);
    }

    return $result;
};

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'PRICE' => [
            'NEW' => null,
            'OLD' => $hSetPropertyText($arItem, $arParams['PROPERTY_PRICE']),
            'CURRENCY' => $hSetPropertyText($arItem, $arParams['PROPERTY_CURRENCY']),
        ],
        'DISCOUNT' => [
            'VALUE' => $hSetPropertyText($arItem, $arParams['PROPERTY_DISCOUNT']),
            'TYPE' => 'percent'
        ],
        'DETAIL' => $hSetPropertyText($arItem, $arParams['PROPERTY_DETAIL_URL']),
        'PROPERTIES' => [
            'SHOW' => false
        ]
    ];

    if (!empty($arParams['PROPERTY_DISCOUNT_TYPE'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_DISCOUNT_TYPE'],
            'VALUE_XML_ID'
        ]);

        if (!empty($arProperty)) {
            if (Type::isArray($arProperty))
                $arProperty = ArrayHelper::getFirstValue($arProperty);

            $arItem['DATA']['DISCOUNT']['TYPE'] = $arProperty;
        }

        unset($arProperty);
    }

    if (!empty($arItem['DATA']['PRICE']['NEW']))
        $arItem['DATA']['PRICE']['VALUE'] = Type::toFloat($arItem['DATA']['PRICE']['VALUE']);

    if (!empty($arItem['DATA']['DISCOUNT']['VALUE']))
        $arItem['DATA']['DISCOUNT']['VALUE'] = Type::toFloat($arItem['DATA']['DISCOUNT']['VALUE']);

    if (!empty($arItem['DATA']['DETAIL']))
        $arItem['DATA']['DETAIL'] = StringHelper::replaceMacros($arItem['DATA']['DETAIL'], $arMacros);

    $arItem['DATA']['PRICE']['NEW'] = $hGetDiscountPrice(
        $arItem['DATA']['PRICE']['OLD'],
        $arItem['DATA']['DISCOUNT']
    );

    if ($arItem['DATA']['PRICE']['NEW'] == $arItem['DATA']['PRICE']['OLD'])
        $arItem['DATA']['PRICE']['OLD'] = null;

    foreach ($arItem['DISPLAY_PROPERTIES'] as $arProperty)
        if (!empty($arProperty['DISPLAY_VALUE']))
            $arItem['DATA']['PROPERTIES']['SHOW'] = true;
}

unset($arItem);