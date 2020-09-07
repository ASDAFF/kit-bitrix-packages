<?php if (!defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED !== true) die();

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
    'PROPERTY_TEXT' => null,
    'LAZYLOAD_USE' => 'N',
    'MARKS_SHOW' => 'N',
    'WEIGHT_SHOW' => 'N',
    'QUANTITY_SHOW' => 'N',
    'QUANTITY_MODE' => 'number',
    'QUANTITY_BOUNDS_FEW' => 3,
    'QUANTITY_BOUNDS_MANY' => 10,
    'ACTION' => 'none',
    'COUNTER_SHOW' => 'N',
    'DESCRIPTION_SHOW' => 'Y',
    'DESCRIPTION_MODE' => 'preview',
    'TEXT_SHOW' => 'N',
    'GALLERY_PANEL' => 'N',
    'GALLERY_PREVIEW' => 'N',
    'INFORMATION_PAYMENT' => 'N',
    'PAYMENT_URL' => null,
    'INFORMATION_SHIPMENT' => 'N',
    'SHIPMENT_URL' => null,
    'BASKET_URL' => null
], $arParams);

$arCodes = [
    'MARKS' => [
        'HIT' => $arParams['PROPERTY_MARKS_HIT'],
        'NEW' => $arParams['PROPERTY_MARKS_NEW'],
        'RECOMMEND' => $arParams['PROPERTY_MARKS_RECOMMEND']
    ],
    'PICTURES' => $arParams['PROPERTY_PICTURES'],
    'TEXT' => $arParams['PROPERTY_TEXT'],
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
    'WEIGHT' => [
        'SHOW' => $arParams['WEIGHT_SHOW'] === 'Y'
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
    'TEXT' => [
        'SHOW' => $arParams['TEXT_SHOW'] === 'Y'
    ],
    'GALLERY' => [
        'PANEL' => $arParams['GALLERY_PANEL'] === 'Y',
        'PREVIEW' => $arParams['GALLERY_PREVIEW'] === 'Y'
    ],
    'INFORMATION' => [
        'PAYMENT' => $arParams['INFORMATION_PAYMENT'] === 'Y',
        'SHIPMENT' => $arParams['INFORMATION_SHIPMENT'] === 'Y'
    ]
];

if (empty($arResult[$arVisual['DESCRIPTION']['MODE'] === 'preview' ? 'PREVIEW_TEXT' : 'DETAIL_TEXT']))
    $arVisual['DESCRIPTION']['SHOW'] = false;

$arResult['ACTION'] = ArrayHelper::fromRange([
    'none',
    'buy',
    'detail'
], $arParams['ACTION']);

$arResult['URL'] = [
    'BASKET' => $arParams['BASKET_URL'],
    'PAYMENT' => $arParams['PAYMENT_URL'],
    'SHIPMENT' => $arParams['SHIPMENT_URL']
];

foreach ($arResult['URL'] as $sKey => $sUrl)
    $arResult['URL'][$sKey] = StringHelper::replaceMacros($sUrl, $arMacros);

if (empty($arResult['URL']['PAYMENT']))
    $arVisual['INFORMATION']['PAYMENT'] = false;

if (empty($arResult['URL']['SHIPMENT']))
    $arVisual['INFORMATION']['SHIPMENT'] = false;

if ($bLite)
    include(__DIR__.'/modifiers/lite/catalog.php');

include(__DIR__.'/modifiers/fields.php');
include(__DIR__.'/modifiers/marks.php');
include(__DIR__.'/modifiers/pictures.php');
include(__DIR__.'/modifiers/properties.php');

if (empty($arResult['TEXT']))
    $arVisual['TEXT']['SHOW'] = false;

if ($arResult['ACTION'] !== 'buy')
    $arVisual['COUNTER']['SHOW'] = false;

if ($bBase)
    include(__DIR__.'/modifiers/base/catalog.php');

if ($bBase || $bLite)
    include(__DIR__.'/modifiers/catalog.php');

$arResult['VISUAL'] = $arVisual;