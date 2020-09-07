<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
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
    'PROPERTY_ARTICLE' => null,
    'PROPERTY_BRAND' => null,
    'PROPERTY_PICTURES' => null,
    'PROPERTY_TAB_META_TITLE' => null,
    'PROPERTY_TAB_META_CHAIN' => null,
    'PROPERTY_TAB_META_KEYWORDS' => null,
    'PROPERTY_TAB_META_DESCRIPTION' => null,
    'PROPERTY_TAB_META_BROWSER_TITLE' => null,
    'PROPERTY_SERVICES' => null,
    'PROPERTY_DOCUMENTS' => null,
    'PROPERTY_VIDEO' => null,
    'PROPERTY_ASSOCIATED' => null,
    'PROPERTY_RECOMMENDED' => null,
    'PROPERTY_ORDER_USE' => null,
    'OFFERS_PROPERTY_ARTICLE' => null,
    'OFFERS_PROPERTY_PICTURES' => null,
    'LAZYLOAD_USE' => 'N',
    'ARTICLE_SHOW' => 'Y',
    'BRAND_SHOW' => 'Y',
    'VIDEO_IBLOCK_TYPE' => null,
    'VIDEO_IBLOCK_ID' => null,
    'VIDEO_PROPERTY_URL' => 'LINK',
    'REVIEWS_IBLOCK_TYPE' => null,
    'REVIEWS_IBLOCK_ID' => 	null,
    'REVIEWS_PROPERTY_ELEMENT_ID' => null,
    'REVIEWS_MAIL_EVENT' => null,
    'REVIEWS_USE_CAPTCHA' => 'Y',
    'REVIEWS_SHOW' => 'Y',
    'SERVICES_IBLOCK_TYPE' => null,
    'SERVICES_IBLOCK_ID' => null,
    'SERVICES_PROPERTY_PRICE' => 'SYSTEM_PRICE',
    'ORDER_FAST_USE' => 'N',
    'ORDER_FAST_TEMPLATE' => null,
    'VIEW' => 'wide',
    'VIEW_TABS_POSITION' => 'top',
    'PANEL_SHOW' => 'N',
    'PANEL_MOBILE_SHOW' => 'N',
    'ACTION' => 'none',
    'DELAY_USE' => 'N',
    'VOTE_SHOW' => 'Y',
    'VOTE_MODE' => 'rating',
    'QUANTITY_SHOW' => 'Y',
    'QUANTITY_MODE' => 'number',
    'SERVICES_SHOW' => 'Y',
    'DOCUMENTS_SHOW' => 'Y',
    'ASSOCIATED_SHOW' => 'Y',
    'RECOMMENDED_SHOW' => 'Y',
    'MARKS_SHOW' => 'Y',
    'GALLERY_SHOW' => 'Y',
    'GALLERY_ZOOM' => 'Y',
    'GALLERY_POPUP' => 'Y',
    'GALLERY_PANEL' => 'Y',
    'COUNTER_SHOW' => 'Y',
    'STORES_SHOW' => 'Y',
    'SETS_SHOW' => 'Y',
    'PROPERTIES_DETAIL_SHOW' => 'Y',
    'PRICE_EXTENDED' => 'N',
    'PRICE_RANGE' => 'N',
    'PRICE_DIFFERENCE' => 'N',
    'FORM_ID' => null,
    'FORM_TEMPLATE' => null,
    'FORM_PROPERTY_PRODUCT' => null,
    'BASKET_URL' => null,
    'COMPARE_URL' => null,
    'USE_STORE' => 'N',
    'PRINT_SHOW' => 'N',
    'PROPERTY_ADVANTAGES' => null,
    'ADVANTAGES_SHOW' => 'N',
    'FORM_CHEAPER_SHOW' => 'N',
    'FORM_CHEAPER_ID' => null,

    'WIDE' => 'Y',
    'TAB' => null,
    'TAB_URL' => null
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y'
    ],
    'ARTICLE' => [
        'SHOW' => $arParams['ARTICLE_SHOW'] === 'Y'
    ],
    'BRAND' => [
        'SHOW' => $arParams['BRAND_SHOW'] === 'Y'
    ],
    'COUNTER' => [
        'SHOW' => $arParams['COUNTER_SHOW'] === 'Y'
    ],
    'QUANTITY' => [
        'SHOW' => ArrayHelper::getValue($arParams, 'QUANTITY_SHOW') === 'Y',
        'MODE' => ArrayHelper::fromRange(['number', 'text', 'logic'], ArrayHelper::getValue($arParams, 'QUANTITY_MODE')),
        'BOUNDS' => [
            'FEW' => ArrayHelper::getValue($arParams, 'QUANTITY_BOUNDS_FEW'),
            'MANY' => ArrayHelper::getValue($arParams, 'QUANTITY_BOUNDS_MANY')
        ]
    ],
    'STORES' => [
        'SHOW' => $arParams['USE_STORE'] === 'Y' && $arParams['STORES_SHOW'] === 'Y'
    ],
    'SETS' => [
        'SHOW' => $arParams['SETS_SHOW'] === 'Y'
    ],
    'MARKS' => [
        'SHOW' => $arParams['MARKS_SHOW'] === 'Y'
    ],
    'GALLERY' => [
        'SHOW' => $arParams['GALLERY_SHOW'] === 'Y',
        'ZOOM' => $arParams['GALLERY_ZOOM'] === 'Y',
        'POPUP' => $arParams['GALLERY_POPUP'] === 'Y',
        'SLIDER' => $arParams['GALLERY_SLIDER'] === 'Y'
    ],
    'DESCRIPTION' => [
        'PREVIEW' => [
            'SHOW' => $arParams['DESCRIPTION_PREVIEW_SHOW'] === 'Y'
        ],
        'DETAIL' => [
            'SHOW' => $arParams['DESCRIPTION_DETAIL_SHOW'] === 'Y'
        ]
    ],
    'PROPERTIES' => [
        'PREVIEW' => [
            'SHOW' => $arParams['PROPERTIES_PREVIEW_SHOW'] === 'Y',
            'COUNT' => $arParams['PROPERTIES_PREVIEW_COUNT'],
        ],
        'DETAIL' => [
            'SHOW' => $arParams['PROPERTIES_DETAIL_SHOW'] === 'Y'
        ]
    ],
    'DOCUMENTS' => [
        'SHOW' => $arParams['DOCUMENTS_SHOW'] === 'Y'
    ],
    'VIDEO' => [
        'SHOW' => $arParams['VIDEO_SHOW'] === 'Y'
    ],
    'REVIEWS' => [
        'SHOW' => $arParams['REVIEWS_SHOW'] === 'Y'
    ],
    'ASSOCIATED' => [
        'SHOW' => $arParams['ASSOCIATED_SHOW'] === 'Y'
    ],
    'RECOMMENDED' => [
        'SHOW' => $arParams['RECOMMENDED_SHOW'] === 'Y'
    ],
    'SERVICES' => [
        'SHOW' => $arParams['SERVICES_SHOW'] === 'Y'
    ],
    'VIEW' => [
        'VALUE' => ArrayHelper::fromRange([
            'wide',
            'tabs'
        ], $arParams['VIEW'])
    ],
    'WIDE' => $arParams['WIDE'] === 'Y',
    'PANEL' => [
        'DESKTOP' => [
            'SHOW' => $arParams['PANEL_SHOW'] === 'Y'
        ],
        'MOBILE' => [
            'SHOW' => $arParams['PANEL_MOBILE_SHOW'] === 'Y'
        ]
    ],
    'VOTE' => [
        'SHOW' => $arParams['VOTE_SHOW'] === 'Y',
        'MODE' => ArrayHelper::fromRange(['average', 'rating'], $arParams['VOTE_MODE'])
    ],
    'PRICE' => [
        'EXTENDED' => $arParams['PRICE_EXTENDED'] === 'Y',
        'RANGE' => $arParams['PRICE_RANGE'] === 'Y',
        'DIFFERENCE' => $arParams['PRICE_DIFFERENCE'] === 'Y'
    ],
    'FORM' => [
        'SHOW' => $arParams['FORM_SHOW'] === 'Y'
    ],
    'PRINT' => [
        'SHOW' => $arParams['PRINT_SHOW'] === 'Y'
    ],
    'ADVANTAGES' => [
        'SHOW' => $arParams['ADVANTAGES_SHOW'] === 'Y'
    ]
];

if ($arVisual['VIEW']['VALUE'] === 'tabs') {
    $arVisual['VIEW']['POSITION'] = ArrayHelper::fromRange([
        'top',
        'right'
    ], $arParams['VIEW_TABS_POSITION']);
}

if (empty($arResult['PREVIEW_TEXT']))
    $arVisual['DESCRIPTION']['PREVIEW']['SHOW'] = false;

if (empty($arResult['DETAIL_TEXT']))
    $arVisual['DESCRIPTION']['DETAIL']['SHOW'] = false;

if (empty($arParams['WEB_FORM_ID']) || empty($arParams['WEB_FORM_TEMPLATE']))
    $arVisual['FORM']['SHOW'] = false;

$arResult['ACTION'] = ArrayHelper::fromRange([
    'none',
    'buy',
    'order'
], $arParams['ACTION']);

$arOrderUse = ArrayHelper::getValue($arResult, [
    'PROPERTIES',
    $arParams['PROPERTY_ORDER_USE'],
    'VALUE'
]);

if (!empty($arOrderUse) && $arResult['ACTION'] === 'buy') {
    $arResult['ACTION'] = 'order';
}

$arResult['COMPARE'] = [
    'USE' => $arParams['USE_COMPARE'] === 'Y',
    'CODE' => $arParams['COMPARE_NAME']
];

if (empty($arResult['COMPARE']['CODE']))
    $arResult['COMPARE']['USE'] = false;

$arResult['DELAY'] = [
    'USE' => $arParams['DELAY_USE'] === 'Y'
];

if ($arResult['ACTION'] !== 'buy') {
    $arResult['DELAY']['USE'] = false;
}

$arResult['FORM']['ORDER'] = [
    'SHOW' => $arResult['ACTION'] === 'order',
    'ID' => $arParams['FORM_ID'],
    'TEMPLATE' => $arParams['FORM_TEMPLATE'],
    'PROPERTIES' => [
        'PRODUCT' => $arParams['FORM_PROPERTY_PRODUCT']
    ]
];

$arResult['FORM']['CHEAPER'] = [
    'SHOW' => $arParams['FORM_CHEAPER_SHOW'] === 'Y',
    'ID' => $arParams['FORM_CHEAPER_ID'],
    'TEMPLATE' => $arParams['FORM_TEMPLATE'],
    'PROPERTIES' => [
        'PRODUCT' => $arParams['FORM_PROPERTY_PRODUCT']
    ]
];

$arResult['URL'] = [
    'BASKET' => $arParams['BASKET_URL'],
    'CONSENT' => $arParams['CONSENT_URL']
];

foreach ($arResult['URL'] as $sKey => $sUrl)
    $arResult['URL'][$sKey] = StringHelper::replaceMacros($sUrl, $arMacros);

if ($bLite) {
    $arResult['DELAY']['USE'] = false;
    $arVisual['SETS']['SHOW'] = false;
    $arVisual['STORES']['SHOW'] = false;

    include(__DIR__.'/modifiers/lite/catalog.php');
}

include(__DIR__.'/modifiers/fields.php');
include(__DIR__.'/modifiers/pictures.php');
include(__DIR__.'/modifiers/order.fast.php');
include(__DIR__.'/modifiers/shares.php');
include(__DIR__.'/modifiers/services.php');
include(__DIR__.'/modifiers/marks.php');

if ($arResult['ACTION'] !== 'buy') {
    $arResult['ORDER_FAST']['USE'] = false;
    $arVisual['COUNTER']['SHOW'] = false;
}

if (empty($arResult['BRAND']))
    $arVisual['BRAND']['SHOW'] = false;

if (empty($arResult['DOCUMENTS']))
    $arVisual['DOCUMENTS']['SHOW'] = false;

if (empty($arResult['VIDEO']) || empty($arParams['VIDEO_IBLOCK_ID']))
    $arVisual['VIDEO']['SHOW'] = false;

if (empty($arResult['ASSOCIATED']))
    $arVisual['ASSOCIATED']['SHOW'] = false;

if (empty($arResult['RECOMMENDED']))
    $arVisual['RECOMMENDED']['SHOW'] = false;

if (!$arResult['SERVICES']['SHOW'])
    $arVisual['SERVICES']['SHOW'] = false;

if (empty($arResult['ADVANTAGES']))
    $arVisual['ADVANTAGES']['SHOW'] = false;

include(__DIR__.'/modifiers/tab.php');

if ($bBase)
    include(__DIR__.'/modifiers/base/catalog.php');

if ($bBase || $bLite)
    include(__DIR__.'/modifiers/catalog.php');

include(__DIR__.'/modifiers/properties.php');

if (empty($arResult['DISPLAY_PROPERTIES'])) {
    $arVisual['PROPERTIES']['PREVIEW']['SHOW'] = false;
    $arVisual['PROPERTIES']['DETAIL']['SHOW'] = false;
} else {
    if ($arVisual['PROPERTIES']['PREVIEW']['SHOW']) {
        $arVisual['PROPERTIES']['PREVIEW']['SHOW'] = false;

        foreach ($arResult['DISPLAY_PROPERTIES'] as &$arProperty)
            if (empty($arProperty['USER_TYPE'])) {
                $arVisual['PROPERTIES']['PREVIEW']['SHOW'] = true;
                break;
            }

        unset($arProperty);
    }
}

$arResult['SECTIONS'] = [];

if ($arVisual['DESCRIPTION']['DETAIL']['SHOW'])
    $arResult['SECTIONS']['DESCRIPTION'] = [];

if ($arVisual['STORES']['SHOW'])
    $arResult['SECTIONS']['STORES'] = [];

if ($arVisual['PROPERTIES']['DETAIL']['SHOW'])
    $arResult['SECTIONS']['PROPERTIES'] = [];

if ($arVisual['DOCUMENTS']['SHOW'])
    $arResult['SECTIONS']['DOCUMENTS'] = [];

if ($arVisual['VIDEO']['SHOW'])
    $arResult['SECTIONS']['VIDEO'] = [];

if ($arVisual['REVIEWS']['SHOW'])
    $arResult['SECTIONS']['REVIEWS'] = [];

$bSectionFirst = true;

foreach ($arResult['SECTIONS'] as $sCode => &$arSection) {
    $arSection = ArrayHelper::merge([
        'CODE' => StringHelper::toLowerCase($sCode),
        'NAME' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_SECTIONS_'.$sCode)
    ], $arSection, [
        'ACTIVE' => $bSectionFirst,
        'URL' => null
    ]);

    if ($arResult['TAB']['USE']) {
        $arSection['URL'] = $bSectionFirst ?
            $arResult['DETAIL_PAGE_URL'] :
            StringHelper::replaceMacros($arResult['TAB']['URL']['TEMPLATE'], [
                'TAB' => $arSection['CODE']
            ]);
    }

    if ($bSectionFirst)
        $bSectionFirst = false;
}

unset($bSectionFirst);

if ($arResult['TAB']['USE'] && !empty($arResult['TAB']['VALUE']) && isset($arResult['SECTIONS'][$arResult['TAB']['VALUE']])) {
    $arTab = $arResult['SECTIONS'][$arResult['TAB']['VALUE']];

    foreach ($arResult['TAB']['META'] as $sKey => $sValue) {
        if (!empty($sValue))
            $arResult['TAB']['META'][$sKey] = StringHelper::replaceMacros($sValue, [
                'TAB' => $arTab['NAME']
            ]);
    }

    if (!empty($arResult['TAB']['META']['TITLE']))
        $arResult['META_TAGS']['TITLE'] = $arResult['TAB']['META']['TITLE'];

    if (!empty($arResult['TAB']['META']['BROWSER_TITLE']))
        $arResult['META_TAGS']['BROWSER_TITLE'] = $arResult['TAB']['META']['BROWSER_TITLE'];

    if (!empty($arResult['TAB']['META']['CHAIN']))
        $arResult['META_TAGS']['ELEMENT_CHAIN'] = $arResult['TAB']['META']['CHAIN'];

    if (!empty($arResult['TAB']['META']['KEYWORDS']))
        $arResult['META_TAGS']['KEYWORDS'] = $arResult['TAB']['META']['TITLE'];

    if (!empty($arResult['TAB']['META']['DESCRIPTION']))
        $arResult['META_TAGS']['DESCRIPTION'] = $arResult['TAB']['META']['DESCRIPTION'];

    unset($arTab, $sKey, $sValue);
}

$arResult['SKU_VIEW'] = ArrayHelper::fromRange([
    'dynamic',
    'list'
], $arParams['SKU_VIEW']);

if (!empty($arResult['OFFERS']) && $arResult['SKU_VIEW'] == 'list') {
    $arVisual['PANEL']['DESKTOP']['SHOW'] = false;
    $arVisual['PANEL']['MOBILE']['SHOW'] = false;
}

$arResult['VISUAL'] = $arVisual;
$this->getComponent()->setResultCacheKeys(['SECTIONS', 'VISUAL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE']);