<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\collections\Arrays;
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

$arParams = ArrayHelper::merge([
    'PROPERTY_MARKS_HIT' => null,
    'PROPERTY_MARKS_NEW' => null,
    'PROPERTY_MARKS_RECOMMEND' => null,
    'PROPERTY_ORDER_USE' => null,
    'PROPERTY_PICTURES' => null,
    'OFFERS_PROPERTY_PICTURES' => null,
    'LAZYLOAD_USE' => 'N',
    'COLUMNS' => 3,
    'COLUMNS_MOBILE' => 1,
    'DELAY_USE' => 'N',
    'BORDERS' => 'N',
    'BORDERS_STYLE' => 'squared',
    'COUNTER_SHOW' => 'N',
    'MARKS_SHOW' => 'N',
    'MARKS_ORIENTATION' => 'horizontal',
    'QUICK_VIEW_USE' => 'N',
    'QUICK_VIEW_DETAIL' => 'N',
    'QUICK_VIEW_VIEW' => 'right',
    'QUICK_VIEW_TEMPLATE' => null,
    'NAME_POSITION' => 'middle',
    'NAME_ALIGN' => 'left',
    'PRICE_ALIGN' => 'left',
    'IMAGE_SLIDER_SHOW' => 'N',
    'ACTION' => 'buy',
    'OFFERS_USE' => 'N',
    'OFFERS_ALIGN' => 'left',
    'OFFERS_VIEW' => 'default',
    'OFFERS_VIEW_EXTENDED_LEFT' => null,
    'OFFERS_VIEW_EXTENDED_RIGHT' => null,
    'IMAGE_SLIDER_NAV_HIDE' => 'N',
    'VOTE_SHOW' => 'N',
    'VOTE_ALIGN' => 'left',
    'VOTE_MODE' => 'rating',
    'QUANTITY_SHOW' => 'N',
    'QUANTITY_MODE' => 'number',
    'QUANTITY_BOUNDS_FEW' => null,
    'QUANTITY_BOUNDS_MANY' => null,
    'QUANTITY_ALIGN' => 'left'
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

$arPosition = [
    'left',
    'center',
    'right'
];

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
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'BORDERS' => [
        'USE' => $arParams['BORDERS'] === 'Y',
        'STYLE' => ArrayHelper::fromRange(['squared', 'rounded'], $arParams['BORDERS_STYLE'])
    ],
    'COLUMNS' => [
        'DESKTOP' => ArrayHelper::fromRange([3, 2, 4], $arParams['COLUMNS']),
        'MOBILE' => ArrayHelper::fromRange([1, 2], $arParams['COLUMNS_MOBILE']),
    ],
    'IMAGE' => [
        'SLIDER' => $arParams['IMAGE_SLIDER_SHOW'] === 'Y',
        'NAV' => $arParams['IMAGE_SLIDER_NAV_HIDE'] === 'Y'
    ],
    'COUNTER' => [
        'SHOW' => $arParams['COUNTER_SHOW'] === 'Y'
    ],
    'MARKS' => [
        'ORIENTATION' => ArrayHelper::fromRange(['horizontal', 'vertical'], $arParams['MARKS_ORIENTATION'])
    ],
    'COMPARE' => [
        'USE' => $arParams['USE_COMPARE'] === 'Y',
        'CODE' => $arParams['COMPARE_NAME']
    ],
    'DELAY' => [
        'USE' => $arParams['DELAY_USE'] === 'Y'
    ],
    'NAME' => [
        'ALIGN' => ArrayHelper::fromRange($arPosition, $arParams['NAME_ALIGN']),
        'POSITION' => ArrayHelper::fromRange(['middle', 'top'], $arParams['NAME_POSITION'])
    ],
    'PRICE' => [
        'ALIGN' => ArrayHelper::fromRange(['start', 'center', 'end'], $arParams['PRICE_ALIGN'])
    ],
    'OFFERS' => [
        'USE' => $arParams['OFFERS_USE'] === 'Y',
        'ALIGN' => ArrayHelper::fromRange($arPosition, $arParams['OFFERS_ALIGN']),
        'VIEW' => ArrayHelper::fromRange(['default', 'extended'], $arParams['OFFERS_VIEW']),
        'EXTENDED' => [
            'LEFT' => $arParams['OFFERS_VIEW_EXTENDED_LEFT'],
            'RIGHT' => $arParams['OFFERS_VIEW_EXTENDED_RIGHT']
        ]
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
        'MODE' => ArrayHelper::fromRange(['average', 'rating'], $arParams['VOTE_MODE']),
        'ALIGN' => ArrayHelper::fromRange($arPosition, $arParams['VOTE_ALIGN'])
    ],
    'QUANTITY' => [
        'SHOW' => $arParams['QUANTITY_SHOW'] === 'Y',
        'MODE' => ArrayHelper::fromRange(['number', 'text', 'logic'], $arParams['QUANTITY_MODE']),
        'ALIGN' => ArrayHelper::fromRange($arPosition, $arParams['QUANTITY_ALIGN']),
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
    $arVisual['QUANTITY']['BOUNDS']['FEW'] = 5;

if ($arVisual['QUANTITY']['BOUNDS']['MANY'] <= $arVisual['QUANTITY']['BOUNDS']['FEW'])
    $arVisual['QUANTITY']['BOUNDS']['MANY'] = $arVisual['QUANTITY']['BOUNDS']['FEW'] + 10;

if ($arVisual['OFFERS']['VIEW'] === 'extended' && $arVisual['IMAGE']['NAV'])
    $arVisual['IMAGE']['NAV'] = true;

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
    'SHOW' => $arParams['ACTION'] === 'order',
    'ID' => $arParams['FORM_ID'],
    'TEMPLATE' => $arParams['FORM_TEMPLATE'],
    'PROPERTIES' => [
        'PRODUCT' => $arParams['FORM_PROPERTY_PRODUCT']
    ]
];

if (empty($arResult['FORM']['ID']))
    $arResult['FORM']['SHOW'] = false;

$arUrl['URL'] = [
    'BASKET' => $arParams['BASKET_URL'],
    'CONSENT' => $arParams['CONSENT_URL']
];

foreach ($arUrl['URL'] as $sKey => $sUrl)
    $arResult['URL'][$sKey] = StringHelper::replaceMacros($sUrl, $arMacros);

foreach ($arResult['ITEMS'] as &$arItem) {
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

    $arItem['MARKS'] = [
        'RECOMMEND' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['MARKS']['RECOMMEND'], 'VALUE']),
        'NEW' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['MARKS']['NEW'], 'VALUE']),
        'HIT' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['MARKS']['HIT'], 'VALUE'])
    ];

    $arItem['MARKS']['RECOMMEND'] = !empty($arItem['MARKS']['RECOMMEND']);
    $arItem['MARKS']['NEW'] = !empty($arItem['MARKS']['NEW']);
    $arItem['MARKS']['HIT'] = !empty($arItem['MARKS']['HIT']);

    unset($arItem);
}

if ($bLite)
    include(__DIR__.'/modifiers/lite/catalog.php');

include(__DIR__.'/modifiers/pictures.php');
include(__DIR__.'/modifiers/properties.php');
include(__DIR__.'/modifiers/quick.view.php');

if ($bBase)
    include(__DIR__.'/modifiers/base/catalog.php');

if ($bBase || $bLite)
    include(__DIR__.'/modifiers/catalog.php');

$arResult['VISUAL'] = $arVisual;