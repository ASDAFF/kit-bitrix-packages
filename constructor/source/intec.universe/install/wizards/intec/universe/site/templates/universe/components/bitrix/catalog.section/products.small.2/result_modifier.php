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
    'COLUMNS' => 4,
    'BORDERS' => 'N',
    'NAME_ALIGN' => 'left',
    'PRICE_ALIGN' => 'left',
    'ACTION' => 'buy',
    'COUNTER_SHOW' => 'N',
    'FORM_ID' => null,
    'FORM_TEMPLATE' => null,
    'FORM_PROPERTY_PRODUCT' => null,
    'WIDE' => 'Y'
], $arParams);

$arVisual = [
    'COLUMNS' => ArrayHelper::fromRange([4, 3, 2], $arParams['COLUMNS']),
    'BORDERS' => $arParams['BORDERS'] === 'Y',
    'NAME' => [
        'ALIGN' => ArrayHelper::fromRange(['left', 'center', 'right'], $arParams['NAME_ALIGN'])
    ],
    'PRICE' => [
        'ALIGN' => ArrayHelper::fromRange(['left', 'center', 'right'], $arParams['PRICE_ALIGN'])
    ],
    'COUNTER' => [
        'SHOW' => $arParams['COUNTER_SHOW'] === 'Y'
    ],
    'WIDE' => $arParams['WIDE'] === 'Y'
];

if (!$arVisual['WIDE'] && $arVisual['COLUMNS'] > 3)
    $arVisual['COLUMNS'] = 3;

$arResult['ACTION'] = ArrayHelper::fromRange([
    'none',
    'buy',
    'detail',
    'order'
], $arParams['ACTION']);

if ($arResult['ACTION'] !== 'buy')
    $arVisual['COUNTER']['SHOW'] = false;

$arResult['FORM'] = [
    'SHOW' => $arResult['ACTION'] === 'order',
    'ID' => $arParams['FORM_ID'],
    'TEMPLATE' => $arParams['FORM_TEMPLATE'],
    'PROPERTIES' => [
        'PRODUCT' => $arParams['FORM_PROPERTY_PRODUCT']
    ]
];

foreach ($arResult['ITEMS'] as &$arItem)
    $arItem['ACTION'] = $arResult['ACTION'];

unset($arItem);

$arResult['URL'] = [];
$arUrl = [
    'BASKET' => $arParams['BASKET_URL'],
    'CONSENT' => $arParams['CONSENT_URL']
];

foreach ($arUrl as $sKey => $sUrl)
    $arResult['URL'][$sKey] = StringHelper::replaceMacros($sUrl, $arMacros);

unset($arUrl, $sKey, $sUrl);

if ($bLite)
    include(__DIR__.'/modifiers/lite/catalog.php');

include(__DIR__.'/modifiers/pictures.php');

if ($bBase)
    include(__DIR__.'/modifiers/base/catalog.php');

if ($bBase || $bLite)
    include(__DIR__.'/modifiers/catalog.php');

$arResult['VISUAL'] = $arVisual;

unset($bBase, $bLite, $arMacros, $arVisual);