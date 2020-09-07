<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!Loader::includeModule('intec.core'))
    return;

$bBase = false;
$bLite = false;

if (Loader::includeModule('catalog') && Loader::includeModule('sale')) {
    $bBase = true;
} else if (Loader::includeModule('intec.startshop')) {
    $bLite = true;
}

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'ACTION' => 'buy',
    'BORDERS' => 'Y',
    'COUNTER_SHOW' => 'Y',
    'DELAY_USE' => 'Y',
    'VOTE_SHOW' => 'N',
    'VOTE_MODE' => 'rating',
    'QUANTITY_SHOW' => 'N',
    'QUANTITY_MODE' => 'number',
    'QUANTITY_BOUNDS_FEW' => 3,
    'QUANTITY_BOUNDS_MANY' => 10,
    'FORM_ID' => null,
    'FORM_TEMPLATE' => null,
    'FORM_PROPERTY_PRODUCT' => null,
    'OFFERS_USE' => 'Y',
    'QUICK_VIEW_USE' => 'N',
    'QUICK_VIEW_DETAIL' => 'N',
    'QUICK_VIEW_TEMPLATE' => null,
    'WIDE' => 'Y',
    'PROPERTY_MARKS_RECOMMEND' => null,
    'PROPERTY_MARKS_NEW' => null,
    'PROPERTY_MARKS_HIT' => null,
    'PROPERTY_ORDER_USE' => null,
    'LAZY_LOAD' => 'N'
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'BORDERS' => $arParams['BORDERS'] === 'Y',
    'COUNTER' => [
        'SHOW' => $arParams['COUNTER_SHOW'] === 'Y'
    ],
    'NAVIGATION' => [
        'TOP' => [
            'SHOW' => $arParams['DISPLAY_TOP_PAGER']
        ],
        'BOTTOM' => [
            'SHOW' => $arParams['DISPLAY_BOTTOM_PAGER']
        ],
        'LAZY' => [
            'BUTTON' => $arParams['LAZY_LOAD'] === 'Y',
            'SCROLL' => $arParams['LOAD_ON_SCROLL'] === 'Y'
        ]
    ],
    'WIDE' => $arParams['WIDE'] === 'Y',
    'VOTE' => [
        'SHOW' => $arParams['VOTE_SHOW'] === 'Y',
        'MODE' => ArrayHelper::fromRange([
            'average',
            'rating'
        ], $arParams['VOTE_MODE'])
    ],
    'QUANTITY' => [
        'SHOW' => $arParams['QUANTITY_SHOW'] === 'Y',
        'MODE' => ArrayHelper::fromRange([
            'number',
            'text',
            'logic'
        ], $arParams['QUANTITY_MODE']),
        'BOUNDS' => [
            'FEW' => Type::toFloat($arParams['QUANTITY_BOUNDS_FEW']),
            'MANY' => Type::toFloat($arParams['QUANTITY_BOUNDS_MANY'])
        ]
    ]
];

if (defined('EDITOR') || !class_exists('\\intec\\template\\Properties'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if ($arVisual['QUANTITY']['BOUNDS']['FEW'] < 0)
    $arVisual['QUANTITY']['BOUNDS']['FEW'] = 3;

if ($arVisual['QUANTITY']['BOUNDS']['MANY'] <= $arVisual['QUANTITY']['BOUNDS']['FEW'])
    $arVisual['QUANTITY']['BOUNDS']['MANY'] = $arVisual['QUANTITY']['BOUNDS']['FEW'] + 1;

if (empty($arResult['NAV_STRING'])) {
    $arVisual['NAVIGATION']['TOP']['SHOW'] = false;
    $arVisual['NAVIGATION']['BOTTOM']['SHOW'] = false;
}

$arResult['ACTION'] = ArrayHelper::fromRange([
    'none',
    'buy',
    'detail',
    'order'
], $arParams['ACTION']);

$arResult['DELAY'] = [
    'USE' => $arParams['DELAY_USE'] === 'Y'
];

if ($arResult['ACTION'] !== 'buy' || $bLite)
    $arResult['DELAY']['USE'] = false;

$arResult['COMPARE'] = [
    'USE' => $arParams['USE_COMPARE'] === 'Y',
    'CODE' => $arParams['COMPARE_NAME']
];

if (empty($arResult['COMPARE']['CODE']))
    $arResult['COMPARE']['USE'] = false;

$arResult['FORM'] = [
    'SHOW' => $arResult['ACTION'] === 'order',
    'ID' => $arParams['FORM_ID'],
    'TEMPLATE' => $arParams['FORM_TEMPLATE'],
    'PROPERTIES' => [
        'PRODUCT' => $arParams['FORM_PROPERTY_PRODUCT']
    ]
];

if (empty($arResult['FORM']['ID']))
    $arResult['FORM']['SHOW'] = false;

$arResult['URL'] = [
    'BASKET' => ArrayHelper::getValue($arParams, 'BASKET_URL'),
    'CONSENT' => ArrayHelper::getValue($arParams, 'CONSENT_URL')
];

foreach ($arResult['URL'] as $sKey => $sUrl)
    $arResult['URL'][$sKey] = StringHelper::replaceMacros($sUrl, $arMacros);

foreach ($arResult['ITEMS'] as $iKey => &$arItem) {
    $arItem['ACTION'] = $arResult['ACTION'];
    $arItem['DELAY'] = [
        'USE' => $arResult['DELAY']['USE']
    ];

    $arOrderUse = ArrayHelper::getValue($arItem, [
        'PROPERTIES',
        $arParams['PROPERTY_ORDER_USE'],
        'VALUE'
    ]);

    if (!empty($arOrderUse) && $arItem['ACTION'] === 'buy' && !empty($arResult['FORM']['ID'])) {
        $arItem['ACTION'] = 'order';
        $arResult['FORM']['SHOW'] = true;
        $arItem['DELAY']['USE'] = false;
    }

    $arItem['PICTURE'] = null;

    if (!empty($arItem['PREVIEW_PICTURE'])) {
        $arItem['PICTURE'] = $arItem['PREVIEW_PICTURE'];
    } else if (!empty($arItem['DETAIL_PICTURE'])) {
        $arItem['PICTURE'] = $arItem['DETAIL_PICTURE'];
    }

    $arItem['MARKS'] = [
        'recommend' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_MARKS_RECOMMEND'], 'VALUE']) != null,
        'new' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_MARKS_NEW'], 'VALUE']) != null,
        'hit' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arParams['PROPERTY_MARKS_HIT'], 'VALUE']) != null
    ];

    unset($arItem);
}

if ($bLite)
    include(__DIR__ . '/modifiers/lite/catalog.php');

include(__DIR__.'/modifiers/pictures.php');
include(__DIR__.'/modifiers/properties.php');
include(__DIR__.'/modifiers/quick.view.php');

if ($bBase)
    include(__DIR__.'/modifiers/base/catalog.php');

if ($bBase || $bLite)
    include(__DIR__.'/modifiers/catalog.php');

$arResult['VISUAL'] = $arVisual;