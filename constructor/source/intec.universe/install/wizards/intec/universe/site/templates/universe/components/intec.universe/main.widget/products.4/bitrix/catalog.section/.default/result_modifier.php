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
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N',
    'PROPERTY_MARKS_HIT' => null,
    'PROPERTY_MARKS_NEW' => null,
    'PROPERTY_MARKS_RECOMMEND' => null,
    'PROPERTY_ORDER_USE' => null,
    'PROPERTY_STORES_SHOW' => null,
    'PROPERTY_PICTURES' => null,
    'PROPERTY_ARTICLE' => null,
    'OFFERS_PROPERTY_PICTURES' => null,
    'OFFERS_PROPERTY_STORES_SHOW' => null,
    'OFFERS_PROPERTY_ARTICLE' => null,
    'ARTICLE_SHOW' => 'N',
    'COLUMNS' => 3,
    'COLUMNS_MOBILE' => 1,
    'LINES' => 0,
    'BLOCKS_HEADER_SHOW' => 'N',
    'BLOCKS_HEADER_TEXT' => null,
    'BLOCKS_HEADER_ALIGN' => 'left',
    'BLOCKS_DESCRIPTION_SHOW' => 'N',
    'BLOCKS_DESCRIPTION_TEXT' => null,
    'BLOCKS_DESCRIPTION_ALIGN' => 'left',
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
    'IMAGE_SLIDER_SHOW' => 'N',
    'IMAGE_SLIDER_NAV_SHOW' => 'N',
    'IMAGE_SLIDER_OVERLAY_USE' => 'Y',
    'ACTION' => 'buy',
    'OFFERS_USE' => 'N',
    'VOTE_SHOW' => 'N',
    'VOTE_MODE' => 'rating',
    'QUANTITY_SHOW' => 'N',
    'QUANTITY_MODE' => 'number',
    'QUANTITY_BOUNDS_FEW' => null,
    'QUANTITY_BOUNDS_MANY' => null
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

$arBlocks = [
    'HEADER' => [
        'SHOW' => $arParams['BLOCKS_HEADER_SHOW'] === 'Y',
        'TEXT' => $arParams['~BLOCKS_HEADER_TEXT'],
        'ALIGN' => ArrayHelper::fromRange($arPosition, $arParams['BLOCKS_HEADER_ALIGN'])
    ],
    'DESCRIPTION' => [
        'SHOW' => $arParams['BLOCKS_DESCRIPTION_SHOW'] === 'Y',
        'TEXT' => $arParams['~BLOCKS_DESCRIPTION_TEXT'],
        'ALIGN' => ArrayHelper::fromRange($arPosition, $arParams['BLOCKS_DESCRIPTION_ALIGN'])
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
        'MOBILE' => ArrayHelper::fromRange([1, 2], $arParams['COLUMNS_MOBILE'])
    ],
    'IMAGE' => [
        'SLIDER' => $arParams['IMAGE_SLIDER_SHOW'] === 'Y',
        'NAV' => $arParams['IMAGE_SLIDER_NAV_SHOW'] === 'Y',
        'OVERLAY' => $arParams['IMAGE_SLIDER_OVERLAY_USE'] === 'Y'
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
    'OFFERS' => [
        'USE' => $arParams['OFFERS_USE'] === 'Y'
    ],
    'WIDE' => $arParams['WIDE'] === 'Y',
    'VOTE' => [
        'SHOW' => $arParams['VOTE_SHOW'] === 'Y',
        'MODE' => ArrayHelper::fromRange(['average', 'rating'], $arParams['VOTE_MODE'])
    ],
    'QUANTITY' => [
        'SHOW' => $arParams['QUANTITY_SHOW'] === 'Y',
        'MODE' => ArrayHelper::fromRange(['number', 'text', 'logic'], $arParams['QUANTITY_MODE']),
        'BOUNDS' => [
            'FEW' => Type::toFloat($arParams['QUANTITY_BOUNDS_FEW']),
            'MANY' => Type::toFloat($arParams['QUANTITY_BOUNDS_MANY'])
        ]
    ],
    'SECTION' => [
        'SHOW' => $arParams['SECTION_SHOW'] === 'Y',
        'ALIGN' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['SECTION_ALIGN'])
    ],
    'ARTICLE' => [
        'SHOW' => $arParams['ARTICLE_SHOW'] === 'Y'
    ],
    'TABS' => [
        'ALIGN' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['TABS_ALIGN'])
    ],
    'LINES' => Type::toInteger($arParams['LINES'])
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

if ($arVisual['LINES'] < 1)
    $arVisual['LINES'] = null;

if ($arVisual['QUANTITY']['BOUNDS']['FEW'] < 0)
    $arVisual['QUANTITY']['BOUNDS']['FEW'] = 5;

if ($arVisual['QUANTITY']['BOUNDS']['MANY'] <= $arVisual['QUANTITY']['BOUNDS']['FEW'])
    $arVisual['QUANTITY']['BOUNDS']['MANY'] = $arVisual['QUANTITY']['BOUNDS']['FEW'] + 10;

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

include(__DIR__.'/modifiers/order.fast.php');

if ($arResult['ACTION'] !== 'buy') {
    $arResult['DELAY']['USE'] = false;
    $arResult['ORDER_FAST']['USE'] = false;
}

if ($bLite)
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

    $arItem['ORDER_FAST'] = [
        'USE' => $arResult['ORDER_FAST']['USE']
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
        $arItem['ORDER_FAST']['USE'] = false;
    }

    $arItem['MARKS'] = [
        'RECOMMEND' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['MARKS']['RECOMMEND'], 'VALUE']),
        'NEW' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['MARKS']['NEW'], 'VALUE']),
        'HIT' => ArrayHelper::getValue($arItem, ['PROPERTIES', $arCodes['MARKS']['HIT'], 'VALUE'])
    ];

    $arItem['MARKS']['RECOMMEND'] = !empty($arItem['MARKS']['RECOMMEND']);
    $arItem['MARKS']['NEW'] = !empty($arItem['MARKS']['NEW']);
    $arItem['MARKS']['HIT'] = !empty($arItem['MARKS']['HIT']);

    $arItem['VOTE'] = [
        'COUNT' => ArrayHelper::getValue($arItem, ['PROPERTIES', 'vote_count', 'VALUE'])
    ];

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

$arResult['BLOCKS'] = $arBlocks;
$arResult['VISUAL'] = $arVisual;
$arResult['MODE'] = $arParams['MODE'];

$arCategories = [];
$arProperty = $arParams['PROPERTY_CATEGORY'];

if (!empty($arProperty)) {
    $arProperty = CIBlockProperty::GetList([], [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'CODE' => $arProperty
    ])->GetNext();
}

if (!empty($arProperty)) {
    $rsCategories = CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], [
        'PROPERTY_ID' => $arProperty['ID']
    ]);

    while ($arCategory = $rsCategories->GetNext()) {
        if (empty($arCategory['XML_ID']))
            continue;

        $arCategories[$arCategory['XML_ID']] = [
            'CODE' => $arCategory['XML_ID'],
            'NAME' => $arCategory['VALUE'],
            'SORT' => $arCategory['SORT'],
            'ITEMS' => []
        ];
    }

    unset($arCategory);
    unset($rsCategories);
} else {
    $arResult['ITEMS'] = [];
}

foreach ($arResult['ITEMS'] as &$arItem) {
    if (!empty($arItem['IBLOCK_SECTION_ID']))
        $arSections[] = $arItem['IBLOCK_SECTION_ID'];

    $arCategory = ArrayHelper::getValue($arItem, [
        'PROPERTIES',
        $arProperty['CODE'],
        'VALUE_XML_ID'
    ]);

    if (!empty($arCategory) && ArrayHelper::keyExists($arCategory, $arCategories)) {
        $arCategory = &$arCategories[$arCategory];
        $arCategory['ITEMS'][] = &$arItem;

        unset($arCategory);
    }
}

if (!empty($arSections)) {
    $rsSections = CIBlockSection::GetList([
        'SORT' => 'ASC'
    ], [
        'ID' => $arSections,
        'IBLOCK_ID' => $arParams['IBLOCK_ID']
    ]);

    $arSections = Arrays::from([]);
    $rsSections->SetUrlTemplates(
        $arParams['DETAIL_URL'],
        $arParams['SECTION_URL']
    );

    while ($arSection = $rsSections->GetNext())
        $arSections->set($arSection['ID'], $arSection);

    unset($arSection);
    unset($rsSections);
}

$arSections = Arrays::from($arSections);

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['SECTION'] = $arSections->get($arItem['IBLOCK_SECTION_ID']);

    unset($arItem);
}

$arResult['CATEGORIES'] = [];

foreach ($arCategories as $sKey => $arCategory)
    if (!empty($arCategory['ITEMS']))
        $arResult['CATEGORIES'][$sKey] = $arCategory;

unset($arCategory);
unset($arCategories);
unset($arBlocks);
unset($arVisual);