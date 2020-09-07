<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$bBase = false;
$bLite = false;

if (!Loader::includeModule('intec.core'))
    return;

if (Loader::includeModule('catalog') && Loader::includeModule('sale')) {
    $bBase = true;
} else if (Loader::includeModule('intec.startshop')) {
    $bLite = true;
}

$arParams = ArrayHelper::merge([
    'PROPERTY_MARKS_RECOMMEND' => null,
    'PROPERTY_MARKS_NEW' => null,
    'PROPERTY_MARKS_HIT' => null,
    'PROPERTY_PICTURES' => null,
    'PROPERTY_ORDER_USE' => null,
    'OFFERS_PROPERTY_PICTURES' => null,
    'LAZYLOAD_USE' => 'N',
    'COLUMNS' => 3,
    'COLUMNS_MOBILE' => 1,
    'MARKS_SHOW' => 'N',
    'MARKS_ORIENTATION' => 'horizontal',
    'NAME_ALIGN' => 'left',
    'VOTE_SHOW' => 'N',
    'VOTE_ALIGN' => 'left',
    'VOTE_MODE' => 'rating',
    'QUANTITY_SHOW' => 'N',
    'QUANTITY_MODE' => 'number',
    'QUANTITY_BOUNDS_FEW' => null,
    'QUANTITY_BOUNDS_MANY' => null,
    'QUANTITY_ALIGN' => 'left',
    'QUICK_VIEW_USE' => 'N',
    'QUICK_VIEW_DETAIL' => 'N',
    'QUICK_VIEW_TEMPLATE' => null,
    'WEIGHT_SHOW' => 'N',
    'WEIGHT_ALIGN' => 'left',
    'DESCRIPTION_SHOW' => 'N',
    'DESCRIPTION_ALIGN' => 'left',
    'PRICE_ALIGN' => 'left',
    'ACTION' => 'buy',
    'COUNTER_SHOW' => 'N',
    'FORM_ID' => null,
    'FORM_PROPERTY_PRODUCT' => null,
    'FORM_TEMPLATE' => null,
    'CONSENT_URL' => null,
    'OFFERS_USE' => 'N',
    'OFFERS_ALIGN' => 'start',
    'IMAGE_SLIDER_SHOW' => 'N',
    'IMAGE_SLIDER_ANIMATION' => 'standard',
    'WIDE' => 'Y',
    'LAZY_LOAD' => 'N'
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
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

$arPosition = [
    'left',
    'center',
    'right'
];

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'COLUMNS' => [
        'DESKTOP' => ArrayHelper::fromRange([3, 2, 4], $arParams['COLUMNS']),
        'MOBILE' => ArrayHelper::fromRange([1, 2], $arParams['COLUMNS_MOBILE']),
    ],
    'MARKS' => [
        'SHOW' => $arParams['MARKS_SHOW'] === 'Y',
        'ORIENTATION' => ArrayHelper::fromRange(['horizontal', 'vertical'], $arParams['MARKS_ORIENTATION'])
    ],
    'IMAGE' => [
        'SLIDER' => $arParams['IMAGE_SLIDER_SHOW'] === 'Y',
        'NAV' => $arParams['IMAGE_SLIDER_NAV'] === 'Y',
        'ANIMATION' => ArrayHelper::fromRange(['standard', 'fade'], $arParams['IMAGE_SLIDER_ANIMATION'])
    ],
    'VOTE' => [
        'SHOW' => $arParams['VOTE_SHOW'] === 'Y',
        'ALIGN' => ArrayHelper::fromRange($arPosition, $arParams['VOTE_ALIGN']),
        'MODE' => ArrayHelper::fromRange(['average', 'rating'], $arParams['VOTE_MODE'])
    ],
    'QUANTITY' => [
        'SHOW' => $arParams['QUANTITY_SHOW'] === 'Y',
        'MODE' => ArrayHelper::fromRange(['number', 'text', 'logic'], $arParams['QUANTITY_MODE']),
        'ALIGN' => ArrayHelper::fromRange($arPosition, $arParams['QUANTITY_ALIGN']),
        'BOUNDS' => [
            'FEW' => Type::toInteger($arParams['QUANTITY_BOUNDS_FEW']),
            'MANY' => Type::toInteger($arParams['QUANTITY_BOUNDS_MANY'])
        ]
    ],
    'NAME' => [
        'ALIGN' => ArrayHelper::fromRange($arPosition, $arParams['NAME_ALIGN'])
    ],
    'WEIGHT' => [
        'SHOW' => $arParams['WEIGHT_SHOW'] === 'Y',
        'ALIGN' => ArrayHelper::fromRange($arPosition, $arParams['WEIGHT_ALIGN'])
    ],
    'DESCRIPTION' => [
        'SHOW' => $arParams['DESCRIPTION_SHOW'] === 'Y',
        'ALIGN' => ArrayHelper::fromRange($arPosition, $arParams['DESCRIPTION_ALIGN'])
    ],
    'OFFERS' => [
        'USE' => $arParams['OFFERS_USE'] === 'Y',
        'ALIGN' => ArrayHelper::fromRange(['start', 'center', 'end'], $arParams['OFFERS_ALIGN'])
    ],
    'PRICE' => [
        'ALIGN' => ArrayHelper::fromRange($arPosition, $arParams['PRICE_ALIGN'])
    ],
    'COUNTER' => [
        'SHOW' => $arParams['COUNTER_SHOW'] === 'Y' && $arParams['ACTION'] === 'buy'
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
];

if (defined('EDITOR') || !class_exists('\\intec\\template\\Properties'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if ($arVisual['QUANTITY']['BOUNDS']['FEW'] < 0)
    $arVisual['QUANTITY']['BOUNDS']['FEW'] = 5;

if ($arVisual['QUANTITY']['BOUNDS']['MANY'] <= $arVisual['QUANTITY']['BOUNDS']['FEW'])
    $arVisual['QUANTITY']['BOUNDS']['MANY'] = $arVisual['QUANTITY']['BOUNDS']['FEW'] + 10;

if (!$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 3)
    $arVisual['COLUMNS']['DESKTOP'] = 3;

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

unset($arUrl);

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
        'RECOMMEND' => ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arCodes['MARKS']['RECOMMEND'],
            'VALUE'
        ]),
        'NEW' => ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arCodes['MARKS']['NEW'],
            'VALUE'
        ]),
        'HIT' => ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arCodes['MARKS']['HIT'],
            'VALUE'
        ])
    ];

    $arItem['MARKS']['RECOMMEND'] = !empty($arItem['MARKS']['RECOMMEND']);
    $arItem['MARKS']['NEW'] = !empty($arItem['MARKS']['NEW']);
    $arItem['MARKS']['HIT'] = !empty($arItem['MARKS']['HIT']);

    unset($arItem);
}

if ($bLite)
    include(__DIR__ . '/modifiers/lite/catalog.php');

include(__DIR__.'/modifiers/pictures.php');
include(__DIR__.'/modifiers/quick.view.php');
include(__DIR__.'/modifiers/properties.php');

if ($bBase)
    include(__DIR__.'/modifiers/base/catalog.php');

if ($bBase || $bLite)
    include(__DIR__.'/modifiers/catalog.php');

$arResult['VISUAL'] = $arVisual;