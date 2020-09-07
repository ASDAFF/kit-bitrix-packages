<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use intec\core\helpers\ArrayHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'BANNER_WIDE' => 'N',
    'BANNER_HEIGHT' => '400px',
    'DESCRIPTION_PROPERTY_DURATION' => null,
    'PROMO_PROPERTY_ELEMENTS' => null,
    'PROMO_IBLOCK_TYPE' => null,
    'PROMO_IBLOCK_ID' => null,
    'CONDITIONS_HEADER' => null,
    'CONDITIONS_PROPERTY_ELEMENTS' => null,
    'CONDITIONS_IBLOCK_TYPE' => null,
    'CONDITIONS_IBLOCK_ID' => null,
    'CONDITIONS_COLUMNS' => 3,
    'VIDEOS_HEADER' => null,
    'VIDEOS_HEADER_POSITION' => 'left',
    'VIDEOS_PROPERTY_ELEMENTS' => null,
    'VIDEOS_PROPERTY_URL' => null,
    'VIDEOS_IBLOCK_TYPE' => null,
    'VIDEOS_IBLOCK_ID' => null,
    'VIDEOS_COLUMNS' => 3,
    'GALLERY_HEADER' => null,
    'GALLERY_HEADER_POSITION' => 'left',
    'GALLERY_WIDE' => 'N',
    'GALLERY_PROPERTY_ELEMENTS' => null,
    'GALLERY_IBLOCK_TYPE' => null,
    'GALLERY_IBLOCK_ID' => null,
    'GALLERY_LINE_COUNT' => 4,
    'SECTIONS_HEADER' => null,
    'SECTIONS_HEADER_POSITION' => 'left',
    'SECTIONS_WIDE' => 'N',
    'SECTIONS_PROPERTY_SECTIONS' => null,
    'SECTIONS_IBLOCK_TYPE' => null,
    'SECTIONS_IBLOCK_ID' => null,
    'SECTIONS_LINE_COUNT' => 4,
    'SERVICES_HEADER' => null,
    'SERVICES_HEADER_POSITION' => 'left',
    'SERVICES_PROPERTY_ELEMENTS' => null,
    'SERVICES_IBLOCK_TYPE' => null,
    'SERVICES_IBLOCK_ID' => null,
    'PRODUCTS_HEADER' => null,
    'PRODUCTS_HEADER_POSITION' => 'left',
    'PRODUCTS_PROPERTY_ELEMENTS' => null,
    'PRODUCTS_IBLOCK_TYPE' => null,
    'PRODUCTS_IBLOCK_ID' => null,
    'LINKS_BUTTON' => null,
    'LINKS_SOCIAL_SHOW' => 'N',
    'LINKS_TITLE' => null,
    'LINKS_HANDLERS' => null,
    'LINKS_SHORTEN_URL_LOGIN' => null,
    'LINKS_SHORTEN_URL_KEY' => null
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['BLOCKS'] = [
    'banner' => [
        'TYPE' => 'sticky.both',
        'ACTIVE' => true
    ],
    'description' => [
        'ACTIVE' => true
    ],
    'promo' => [
        'ACTIVE' => true
    ],
    'conditions' => [
        'ACTIVE' => true
    ],
    'form' => [
        'ACTIVE' => true
    ],
    'videos' => [
        'ACTIVE' => true
    ],
    'gallery' => [
        'ACTIVE' => true
    ],
    'sections' => [
        'ACTIVE' => true
    ],
    'services' => [
        'ACTIVE' => true
    ],
    'products' => [
        'ACTIVE' => true
    ],
    'links' => [
        'ACTIVE' => true
    ]
];

foreach ($arResult['BLOCKS'] as &$arBlock) {
    if (empty($arBlock['TYPE']))
        $arBlock['TYPE'] = 'normal';
}

unset($arBlock);

/** Блок banner */
$arBlock = &$arResult['BLOCKS']['banner'];

if ($arBlock['ACTIVE']) {
    $arBlock['WIDE'] = $arParams['BANNER_WIDE'] === 'Y';
    $arBlock['HEIGHT'] = $arParams['BANNER_HEIGHT'];

    if (!empty($arResult['DETAIL_PICTURE'])) {
        $arBlock['PICTURE'] = $arResult['DETAIL_PICTURE'];
    } else if (!empty($arResult['PREVIEW_PICTURE'])) {
        $arBlock['PICTURE'] = $arResult['PREVIEW_PICTURE'];
    }

    if (!empty($arBlock['PICTURE'])) {
        $arBlock['PICTURE'] = $arBlock['PICTURE']['SRC'];
    } else {
        $arBlock['PICTURE'] = null;
    }

    if (empty($arBlock['PICTURE']))
        $arBlock['ACTIVE'] = false;
}

/** Блок description */
$arBlock = &$arResult['BLOCKS']['description'];

if ($arBlock['ACTIVE']) {
    $arBlock['DURATION'] = ArrayHelper::getValue($arResult, ['PROPERTIES', $arParams['DESCRIPTION_PROPERTY_DURATION'], 'VALUE']);
    $arBlock['TEXT'] = $arResult['DETAIL_TEXT'];

    if (empty($arBlock['DURATION']) && empty($arBlock['TEXT']))
        $arBlock['ACTIVE'] = false;
}

/** Блок promo */
$arBlock = &$arResult['BLOCKS']['promo'];

if ($arBlock['ACTIVE']) {
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['PROMO_IBLOCK_TYPE'],
        'ID' => $arParams['PROMO_IBLOCK_ID'],
        'ELEMENTS' => ArrayHelper::getValue($arResult, ['PROPERTIES', $arParams['PROMO_PROPERTY_ELEMENTS'], 'VALUE']),
    ];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок conditions */
$arBlock = &$arResult['BLOCKS']['conditions'];

if ($arBlock['ACTIVE']) {
    $arBlock['HEADER'] = [
        'VALUE' => $arParams['CONDITIONS_HEADER'],
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['CONDITIONS_HEADER_POSITION'])
    ];
    $arBlock['COLUMNS'] = $arParams['CONDITIONS_COLUMNS'];
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['CONDITIONS_IBLOCK_TYPE'],
        'ID' => $arParams['CONDITIONS_IBLOCK_ID'],
        'ELEMENTS' => ArrayHelper::getValue($arResult, ['PROPERTIES', $arParams['CONDITIONS_PROPERTY_ELEMENTS'], 'VALUE']),
    ];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок form */
$arBlock = &$arResult['BLOCKS']['form'];

if ($arBlock['ACTIVE']) {
    if (empty($arParams['FORM_FORM_ID']) || empty($arParams['FORM_FORM_TEMPLATE']) || $arParams['FORM_SHOW'] == 'N')
        $arBlock['ACTIVE'] = false;
}

/** Блок videos */
$arBlock = &$arResult['BLOCKS']['videos'];

if ($arBlock['ACTIVE']) {
    $arBlock['HEADER'] = [
        'VALUE' => $arParams['VIDEOS_HEADER'],
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['VIDEOS_HEADER_POSITION'])
    ];
    $arBlock['COLUMNS'] = $arParams['VIDEOS_COLUMNS'];
    $arBlock['LINK'] = $arParams['VIDEOS_PROPERTY_URL'];
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['VIDEOS_IBLOCK_TYPE'],
        'ID' => $arParams['VIDEOS_IBLOCK_ID'],
        'ELEMENTS' => ArrayHelper::getValue($arResult, ['PROPERTIES', $arParams['VIDEOS_PROPERTY_ELEMENTS'], 'VALUE']),
    ];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок gallery */
$arBlock = &$arResult['BLOCKS']['gallery'];

if ($arBlock['ACTIVE']) {
    $arBlock['HEADER'] = [
        'VALUE' => $arParams['GALLERY_HEADER'],
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['GALLERY_HEADER_POSITION'])
    ];
    $arBlock['COLUMNS'] = $arParams['GALLERY_LINE_COUNT'];
    $arBlock['WIDE'] = $arParams['GALLERY_WIDE'];
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['GALLERY_IBLOCK_TYPE'],
        'ID' => $arParams['GALLERY_IBLOCK_ID'],
        'ELEMENTS' => ArrayHelper::getValue($arResult, ['PROPERTIES', $arParams['GALLERY_PROPERTY_ELEMENTS'], 'VALUE']),
    ];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок sections */
$arBlock = &$arResult['BLOCKS']['sections'];

if ($arBlock['ACTIVE']) {
    $arBlock['HEADER'] = [
        'VALUE' => $arParams['SECTIONS_HEADER'],
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['SECTIONS_HEADER_POSITION'])
    ];
    $arBlock['COLUMNS'] = $arParams['SECTIONS_LINE_COUNT'];
    $arBlock['WIDE'] = $arParams['SECTIONS_WIDE'];
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['SECTIONS_IBLOCK_TYPE'],
        'ID' => $arParams['SECTIONS_IBLOCK_ID'],
        'ELEMENTS' => ArrayHelper::getValue($arResult, ['PROPERTIES', $arParams['SECTIONS_PROPERTY_SECTIONS'], 'VALUE']),
    ];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок services */
$arBlock = &$arResult['BLOCKS']['services'];

if ($arBlock['ACTIVE']) {
    $arBlock['HEADER'] = [
        'VALUE' => $arParams['SERVICES_HEADER'],
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['SERVICES_HEADER_POSITION'])
    ];
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['SERVICES_IBLOCK_TYPE'],
        'ID' => $arParams['SERVICES_IBLOCK_ID'],
        'ELEMENTS' => ArrayHelper::getValue($arResult, ['PROPERTIES', $arParams['SERVICES_PROPERTY_ELEMENTS'], 'VALUE']),
    ];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок products */
$arBlock = &$arResult['BLOCKS']['products'];

if ($arBlock['ACTIVE']) {
    $arBlock['HEADER'] = [
        'VALUE' => $arParams['PRODUCTS_HEADER'],
        'POSITION' => ArrayHelper::fromRange([
            'left',
            'center',
            'right'
        ], $arParams['CONDITIONS_HEADER_POSITION'])
    ];
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['PRODUCTS_IBLOCK_TYPE'],
        'ID' => $arParams['PRODUCTS_IBLOCK_ID'],
        'ELEMENTS' => ArrayHelper::getValue($arResult, ['PROPERTIES', $arParams['PRODUCTS_PROPERTY_ELEMENTS'], 'VALUE']),
    ];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок links */
$arBlock = &$arResult['BLOCKS']['links'];

if ($arBlock['ACTIVE']) {
    $arBlock['BUTTON'] = $arParams['LINKS_BUTTON'];
    $arBlock['SOCIAL_SHOW'] = $arParams['LINKS_SOCIAL_SHOW'] === 'Y';
    $arBlock['TITLE'] = $arParams['LINKS_TITLE'];
    $arBlock['HANDLERS'] = $arParams['LINKS_HANDLERS'];
    $arBlock['SHORTEN_URL_LOGIN'] = $arParams['LINKS_SHORTEN_URL_LOGIN'];
    $arBlock['SHORTEN_URL_KEY'] = $arParams['LINKS_SHORTEN_URL_KEY'];
}

$arResult['VISUAL'] = $arVisual;

unset($arBlock, $arVisual);

$this->__component->SetResultCacheKeys(['PREVIEW_PICTURE', 'DETAIL_PICTURE']);