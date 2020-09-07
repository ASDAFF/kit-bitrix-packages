<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'LINK_MODE' => 'property',
    'PROPERTY_LINK' => null,
    'PROPERTY_TEXT' => null,
    'PROPERTY_PRICE' => null,
    'PROPERTY_OLD_PRICE' => null,
    'PROPERTY_MARK_HIT' => null,
    'PROPERTY_MARK_RECOMMEND' => null,
    'PROPERTY_MARK_NEW' => null,
    'COLUMNS' => 3,
    'LINK_USE' => 'N',
    'LINK_BLANK' => 'N',
    'PRICE_USE' => 'N',
    'MARKS_USE' => 'N',
    'DESCRIPTION_LINK_SHOW' => 'N',
    'DESCRIPTION_LINK_TEXT' => null,
    'DESCRIPTION_LINK_URL' => null,
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'LINK' => [
        'USE' => $arParams['LINK_USE'] === 'Y',
        'BLANK' => $arParams['LINK_BLANK'] === 'Y'
    ],
    'PRICE' => [
        'USE' => $arParams['PRICE_USE'] === 'Y' && !empty($arParams['PROPERTY_PRICE'])
    ],
    'MARKS' => [
        'USE' => $arParams['MARKS_USE'] === 'Y'
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL']['COLUMNS'] = ArrayHelper::fromRange([2, 3, 4], $arParams['COLUMNS']);
$arResult['VISUAL'] = ArrayHelper::merge($arVisual, $arResult['VISUAL']);

unset($arVisual);

$arResult['BLOCKS']['DESCRIPTION']['LINK'] = [
    'SHOW' => $arParams['DESCRIPTION_LINK_SHOW'] === 'Y',
    'TEXT' => $arParams['DESCRIPTION_LINK_TEXT'],
    'URL' => StringHelper::replaceMacros($arParams['DESCRIPTION_LINK_URL'], $arMacros)
];

$hPropertyValue = function ($arProperty) {
    $sText = null;

    if (!empty($arProperty)) {
        if (Type::isArray($arProperty)) {
            if (ArrayHelper::keyExists('TEXT', $arProperty))
                $sText = $arProperty['TEXT'];
            else
                $sText = ArrayHelper::getFirstValue($arProperty);
        } else {
            $sText = $arProperty;
        }
    }

    if (!empty($sText))
        $sText = Html::stripTags($sText);

    return $sText;
};

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'TEXT' => $hPropertyValue(ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_TEXT'], 'VALUE'])),
        'PRICE' => [
            'NEW' => $hPropertyValue(ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_PRICE'], 'VALUE'])),
            'OLD' => $hPropertyValue(ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_OLD_PRICE'], 'VALUE']))
        ],
        'MARKS' => [
            'HIT' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_MARK_HIT'], 'VALUE']),
            'RECOMMEND' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_MARK_RECOMMEND'], 'VALUE']),
            'NEW' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_MARK_NEW'], 'VALUE'])
        ]
    ];
}

unset($arItem, $arMacros);