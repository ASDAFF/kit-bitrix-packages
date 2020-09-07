<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$bBase = false;
$bLite = false;

if (Loader::includeModule('catalog') && Loader::includeModule('sale'))
    $bBase = true;
else if (Loader::includeModule('intec.startshop'))
    $bLite = true;

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

$arParams = ArrayHelper::merge([
    'PROPERTY_MARKS_HIT' => null,
    'PROPERTY_MARKS_NEW' => null,
    'PROPERTY_MARKS_RECOMMEND' => null,
    'PROPERTY_PICTURES' => null,
    'OFFERS_PROPERTY_PICTURES' => null,
    'LAZYLOAD_USE' => 'N',
    'MARKS_SHOW' => 'N',
    'GALLERY_PREVIEW' => 'N',
    'QUANTITY_SHOW' => 'N',
    'QUANTITY_MODE' => 'number',
    'QUANTITY_BOUNDS_FEW' => 10,
    'QUANTITY_BOUNDS_MANY' => 20,
    'ACTION' => 'none',
    'COUNTER_SHOW' => 'N',
    'DELAY_USE' => 'N',
    'USE_COMPARE' => 'N',
    'COMPARE_NAME' => 'compare',
    'DESCRIPTION_SHOW' => 'Y',
    'DESCRIPTION_MODE' => 'preview',
    'PROPERTIES_SHOW' => 'N',
    'DETAIL_SHOW' => 'N'
], $arParams);

$arCodes = [
    'MARKS' => [
        'HIT' => $arParams['PROPERTY_MARKS_HIT'],
        'NEW' => $arParams['PROPERTY_MARKS_NEW'],
        'RECOMMEND' => $arParams['PROPERTY_MARKS_RECOMMEND']
    ],
    'PICTURES' => $arParams['PROPERTY_PICTURES'],
    'OFFERS' => [
        'PICTURES' => $arParams['OFFERS_PROPERTY_PICTURES']
    ]
];

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y'
    ],
    'MARKS' => [
        'SHOW' => $arParams['MARKS_SHOW'] === 'Y'
    ],
    'GALLERY' => [
        'PREVIEW' => $arParams['GALLERY_PREVIEW'] === 'Y'
    ],
    'QUANTITY' => [
        'SHOW' => $arParams['QUANTITY_SHOW'] === 'Y',
        'MODE' => ArrayHelper::fromRange(['number', 'text', 'logic'], $arParams['QUANTITY_MODE']),
        'BOUNDS' => [
            'FEW' => $arParams['QUANTITY_BOUNDS_FEW'],
            'MANY' => $arParams['QUANTITY_BOUNDS_MANY']
        ]
    ],
    'COUNTER' => [
        'SHOW' => $arParams['COUNTER_SHOW'] === 'Y'
    ],
    'DESCRIPTION' => [
        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
        'MODE' => ArrayHelper::fromRange([
            'preview',
            'detail'
        ], $arParams['DESCRIPTION_MODE'])
    ],
    'PROPERTIES' => [
        'SHOW' => $arParams['PROPERTIES_SHOW'] === 'Y'
    ],
    'DETAIL' => [
        'SHOW' => $arParams['DETAIL_SHOW'] === 'Y'
    ]
];

if (empty($arResult[$arVisual['DESCRIPTION']['MODE'] === 'preview' ? 'PREVIEW_TEXT' : 'DETAIL_TEXT']))
    $arVisual['DESCRIPTION']['SHOW'] = false;

$arResult['ACTION'] = ArrayHelper::fromRange([
    'none',
    'buy',
    'detail'
], $arParams['ACTION']);

$arResult['DELAY'] = [
    'USE' => $arParams['DELAY_USE'] === 'Y'
];

$arResult['COMPARE'] = [
    'USE' => $arParams['USE_COMPARE'] === 'Y',
    'CODE' => $arParams['COMPARE_NAME']
];

if (empty($arResult['COMPARE']['CODE']))
    $arResult['COMPARE']['USE'] = false;

$arResult['URL'] = [
    'BASKET' => $arParams['BASKET_URL']
];

foreach ($arResult['URL'] as $sKey => $sUrl)
    $arResult['URL'][$sKey] = StringHelper::replaceMacros($sUrl, $arMacros);

if ($bLite) {
    $arResult['DELAY']['USE'] = false;

    include(__DIR__ . '/modifiers/lite/catalog.php');
}

include(__DIR__.'/modifiers/fields.php');
include(__DIR__.'/modifiers/marks.php');
include(__DIR__.'/modifiers/pictures.php');

if ($arResult['ACTION'] !== 'buy')
    $arVisual['COUNTER']['SHOW'] = false;

if ($bBase)
    include(__DIR__.'/modifiers/base/catalog.php');

if ($bBase || $bLite)
    include(__DIR__.'/modifiers/catalog.php');

include(__DIR__.'/modifiers/properties.php');

if (empty($arResult['DISPLAY_PROPERTIES'])) {
    $arVisual['PROPERTIES']['SHOW'] = false;
} else {
    $arVisual['PROPERTIES']['SHOW'] = false;

    foreach ($arResult['DISPLAY_PROPERTIES'] as &$arProperty)
        if (empty($arProperty['USER_TYPE'])) {
            $arVisual['PROPERTIES']['SHOW'] = true;
            break;
        }

    unset($arProperty);
}

$arResult['VISUAL'] = $arVisual;