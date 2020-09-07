<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

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

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'BLOCKS' => [],
    'BLOCKS_ORDER' => '',
    'BLOCKS_BANNER_IBLOCK_TYPE' => null,
    'BLOCKS_BANNER_IBLOCK_ID' => null,
    'BLOCKS_BANNER_PROPERTY_ELEMENTS' => null,
    'BLOCKS_RESULT_1_PROPERTY_PICTURE' => null,
    'BLOCKS_RESULT_1_PROPERTY_HEADER' => null,
    'BLOCKS_RESULT_1_PROPERTY_TEXT' => null,
    'BLOCKS_ICONS_1_HEADER' => null,
    'BLOCKS_ICONS_1_IBLOCK_TYPE' => null,
    'BLOCKS_ICONS_1_IBLOCK_ID' => null,
    'BLOCKS_ICONS_1_PROPERTY_HEADER' => null,
    'BLOCKS_ICONS_1_PROPERTY_ELEMENTS' => null,
    'BLOCKS_MORE_1_BUTTON_TEXT' => null,
    'BLOCKS_MORE_1_PROPERTY_PICTURE' => null,
    'BLOCKS_MORE_1_PROPERTY_HEADER' => null,
    'BLOCKS_MORE_1_PROPERTY_TEXT' => null,
    'BLOCKS_MORE_1_PROPERTY_BUTTON_TEXT' => null,
    'BLOCKS_MORE_1_PROPERTY_BUTTON_LINK' => null,
    'BLOCKS_STAGES_1_HEADER' => null,
    'BLOCKS_STAGES_1_IBLOCK_TYPE' => null,
    'BLOCKS_STAGES_1_IBLOCK_ID' => null,
    'BLOCKS_STAGES_1_PROPERTY_HEADER' => null,
    'BLOCKS_STAGES_1_PROPERTY_ELEMENTS' => null,
    'BLOCKS_STAFF_1_HEADER' => null,
    'BLOCKS_STAFF_1_DESCRIPTION' => null,
    'BLOCKS_STAFF_1_IBLOCK_TYPE' => null,
    'BLOCKS_STAFF_1_IBLOCK_ID' => null,
    'BLOCKS_STAFF_1_PROPERTY_HEADER' => null,
    'BLOCKS_STAFF_1_PROPERTY_DESCRIPTION' => null,
    'BLOCKS_STAFF_1_PROPERTY_ELEMENTS' => null,
    'BLOCKS_RATES_1_HEADER' => null,
    'BLOCKS_RATES_1_IBLOCK_TYPE' => null,
    'BLOCKS_RATES_1_IBLOCK_ID' => null,
    'BLOCKS_RATES_1_PROPERTIES' => null,
    'BLOCKS_RATES_1_PROPERTY_HEADER' => null,
    'BLOCKS_RATES_1_PROPERTY_ELEMENTS' => null,
    'BLOCKS_PLAN_1_HEADER' => null,
    'BLOCKS_PLAN_1_IBLOCK_TYPE' => null,
    'BLOCKS_PLAN_1_IBLOCK_ID' => null,
    'BLOCKS_PLAN_1_PROPERTY_HEADER' => null,
    'BLOCKS_PLAN_1_PROPERTY_ELEMENTS' => null,
    'BLOCKS_PROJECTS_1_HEADER' => null,
    'BLOCKS_PROJECTS_1_IBLOCK_TYPE' => null,
    'BLOCKS_PROJECTS_1_IBLOCK_ID' => null,
    'BLOCKS_PROJECTS_1_PROPERTY_HEADER' => null,
    'BLOCKS_PROJECTS_1_PROPERTY_ELEMENTS' => null,
    'BLOCKS_BRANDS_1_HEADER' => null,
    'BLOCKS_BRANDS_1_IBLOCK_TYPE' => null,
    'BLOCKS_BRANDS_1_IBLOCK_ID' => null,
    'BLOCKS_BRANDS_1_PROPERTY_HEADER' => null,
    'BLOCKS_BRANDS_1_PROPERTY_ELEMENTS' => null,
    'BLOCKS_REVIEWS_1_HEADER' => null,
    'BLOCKS_REVIEWS_1_IBLOCK_TYPE' => null,
    'BLOCKS_REVIEWS_1_IBLOCK_ID' => null,
    'BLOCKS_REVIEWS_1_PROPERTY_HEADER' => null,
    'BLOCKS_REVIEWS_1_PROPERTY_ELEMENTS' => null,
    'BLOCKS_REVIEWS_1_PROPERTY_POSITION' => null,
    'BLOCKS_REVIEWS_1_PAGE' => null,
    'BLOCKS_REVIEWS_1_BUTTON_SHOW' => null,
    'BLOCKS_REVIEWS_1_BUTTON_TEXT' => null,
    'BLOCKS_SERVICES_1_HEADER' => null,
    'BLOCKS_SERVICES_1_IBLOCK_TYPE' => null,
    'BLOCKS_SERVICES_1_IBLOCK_ID' => null,
    'BLOCKS_SERVICES_1_PROPERTY_HEADER' => null,
    'BLOCKS_SERVICES_1_PROPERTY_ELEMENTS' => null,
    'BLOCKS_VIDEOS_1_HEADER' => null,
    'BLOCKS_VIDEOS_1_IBLOCK_TYPE' => null,
    'BLOCKS_VIDEOS_1_IBLOCK_ID' => null,
    'BLOCKS_VIDEOS_1_PROPERTY_HEADER' => null,
    'BLOCKS_VIDEOS_1_PROPERTY_ELEMENTS' => null,
    'BLOCKS_VIDEOS_1_PROPERTY_LINK' => null,
    'BLOCKS_FAQ_1_HEADER' => null,
    'BLOCKS_FAQ_1_IBLOCK_TYPE' => null,
    'BLOCKS_FAQ_1_IBLOCK_ID' => null,
    'BLOCKS_FAQ_1_PROPERTY_HEADER' => null,
    'BLOCKS_FAQ_1_PROPERTY_ELEMENTS' => null,
    'BLOCKS_SERVICES_2_HEADER' => null,
    'BLOCKS_SERVICES_2_IBLOCK_TYPE' => null,
    'BLOCKS_SERVICES_2_IBLOCK_ID' => null,
    'BLOCKS_SERVICES_2_PROPERTY_HEADER' => null,
    'BLOCKS_SERVICES_2_PROPERTY_ELEMENTS' => null,
    'BLOCKS_SERVICES_3_HEADER' => null,
    'BLOCKS_SERVICES_3_IBLOCK_TYPE' => null,
    'BLOCKS_SERVICES_3_IBLOCK_ID' => null,
    'BLOCKS_SERVICES_3_PROPERTY_HEADER' => null,
    'BLOCKS_SERVICES_3_PROPERTY_ELEMENTS' => null,
    'BLOCKS_BENEFITS_1_HEADER' => null,
    'BLOCKS_BENEFITS_1_DESCRIPTION' => null,
    'BLOCKS_BENEFITS_1_IBLOCK_TYPE' => null,
    'BLOCKS_BENEFITS_1_IBLOCK_ID' => null,
    'BLOCKS_BENEFITS_1_PROPERTY_HEADER' => null,
    'BLOCKS_BENEFITS_1_PROPERTY_DESCRIPTION' => null,
    'BLOCKS_BENEFITS_1_PROPERTY_ELEMENTS' => null,
    'BLOCKS_BENEFITS_2_HEADER' => null,
    'BLOCKS_BENEFITS_2_IBLOCK_TYPE' => null,
    'BLOCKS_BENEFITS_2_IBLOCK_ID' => null,
    'BLOCKS_BENEFITS_2_PROPERTY_HEADER' => null,
    'BLOCKS_BENEFITS_2_PROPERTY_ELEMENTS' => null,
    'BLOCKS_RESULT_2_HEADER' => null,
    'BLOCKS_RESULT_2_IBLOCK_TYPE' => null,
    'BLOCKS_RESULT_2_IBLOCK_ID' => null,
    'BLOCKS_RESULT_2_PROPERTY_HEADER' => null,
    'BLOCKS_RESULT_2_PROPERTY_ELEMENTS' => null,
    'BLOCKS_RATES_2_HEADER' => null,
    'BLOCKS_RATES_2_IBLOCK_TYPE' => null,
    'BLOCKS_RATES_2_IBLOCK_ID' => null,
    'BLOCKS_RATES_2_PROPERTIES' => null,
    'BLOCKS_RATES_2_PROPERTY_HEADER' => null,
    'BLOCKS_RATES_2_PROPERTY_ELEMENTS' => null,
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

include(__DIR__.'/modifiers/blocks.php');
include(__DIR__.'/modifiers/properties.php');

$arResult['LAZYLOAD'] = [
    'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
    'STUB' => null
];

if (defined('EDITOR'))
    $arResult['LAZYLOAD']['USE'] = false;

if ($arResult['LAZYLOAD']['USE'])
    $arResult['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$fGetPropertyValue = function ($sName, $bRaw = false) use (&$arResult) {
    $mValue = null;

    if (empty($arResult['PROPERTIES'][$sName]))
        return $mValue;

    $arProperty = $arResult['PROPERTIES'][$sName];

    if (!empty($arProperty['USER_TYPE']) && !$bRaw) {
        $arProperty = CIBlockFormatProperties::GetDisplayValue($arResult, $arProperty, 'services_out');

        if (!empty($arProperty['DISPLAY_VALUE'])) {
            $mValue = $arProperty['DISPLAY_VALUE'];
        } else {
            $mValue = $arProperty['VALUE'];
        }
    } else {
        $mValue = $bRaw ? $arProperty['~VALUE'] : $arProperty['VALUE'];
    }

    return $mValue;
};

/** Блок banner */
$arBlock = &$arResult['BLOCKS']['banner'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_BANNER_';
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_BANNER_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_BANNER_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_BANNER_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;

    unset($sPrefix);
}

/** Блок description.1 */
$arBlock = &$arResult['BLOCKS']['description.1'];

if ($arBlock['ACTIVE']) {
    $arBlock['TEXT'] = $arResult['DETAIL_TEXT'];

    if (empty($arBlock['TEXT']))
        $arBlock['ACTIVE'] = false;
}

/** Блок result.1 */
$arBlock = &$arResult['BLOCKS']['result.1'];

if ($arBlock['ACTIVE']) {
    $arBlock['PICTURE'] = $fGetPropertyValue($arParams['BLOCKS_RESULT_1_PROPERTY_PICTURE']);
    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_RESULT_1_PROPERTY_HEADER']);
    $arBlock['TEXT'] = $fGetPropertyValue($arParams['BLOCKS_RESULT_1_PROPERTY_TEXT']);

    if (empty($arBlock['HEADER']) && empty($arBlock['TEXT']))
        $arBlock['ACTIVE'] = false;
}

/** Блок icons.1 */
$arBlock = &$arResult['BLOCKS']['icons.1'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_ICONS_1_';
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_ICONS_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_ICONS_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_ICONS_1_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_ICONS_1_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_ICONS_1_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок categories.1 */
$arBlock = &$arResult['BLOCKS']['categories.1'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_CATEGORIES_1_';
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_CATEGORIES_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_CATEGORIES_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_CATEGORIES_1_PROPERTY_ELEMENTS'], true),
    ];

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_CATEGORIES_1_PROPERTY_HEADER']);
    $arBlock['DESCRIPTION'] = $fGetPropertyValue($arParams['BLOCKS_CATEGORIES_1_PROPERTY_DESCRIPTION']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_CATEGORIES_1_HEADER'];

    if (empty($arBlock['DESCRIPTION']))
        $arBlock['DESCRIPTION'] = $arParams['BLOCKS_CATEGORIES_1_DESCRIPTION'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок more.1 */
$arBlock = &$arResult['BLOCKS']['more.1'];

if ($arBlock['ACTIVE']) {
    $arBlock['PICTURE'] = $fGetPropertyValue($arParams['BLOCKS_MORE_1_PROPERTY_PICTURE']);
    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_MORE_1_PROPERTY_HEADER']);
    $arBlock['TEXT'] = $fGetPropertyValue($arParams['BLOCKS_MORE_1_PROPERTY_TEXT']);
    $arBlock['BUTTON'] = [
        'SHOW' => true,
        'TEXT' => $fGetPropertyValue($arParams['BLOCKS_MORE_1_PROPERTY_BUTTON_TEXT']),
        'LINK' => $fGetPropertyValue($arParams['BLOCKS_MORE_1_PROPERTY_BUTTON_LINK'])
    ];

    if (empty($arBlock['BUTTON']['TEXT']))
        $arBlock['BUTTON']['TEXT'] = $arParams['BLOCKS_MORE_1_BUTTON_TEXT'];

    if (!empty($arBlock['BUTTON']['LINK']))
        $arBlock['BUTTON']['LINK'] = StringHelper::replaceMacros($arBlock['BUTTON']['LINK'], $arMacros);

    if (empty($arBlock['BUTTON']['TEXT']) || empty($arBlock['BUTTON']['LINK']))
        $arBlock['BUTTON']['SHOW'] = false;

    if (empty($arBlock['HEADER']) && empty($arBlock['TEXT']))
        $arBlock['ACTIVE'] = false;
}

/** Блок stages.1 */
$arBlock = &$arResult['BLOCKS']['stages.1'];

if ($arBlock['ACTIVE']) {
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_STAGES_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_STAGES_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_STAGES_1_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_STAGES_1_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_STAGES_1_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок form.1 */
$arBlock = &$arResult['BLOCKS']['form.1'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_FORM_1_';

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }
}

/** Блок staff.1 */
$arBlock = &$arResult['BLOCKS']['staff.1'];

if ($arBlock['ACTIVE']) {
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_STAFF_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_STAFF_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_STAFF_1_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_STAFF_1_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_STAFF_1_HEADER'];

    $arBlock['DESCRIPTION'] = $fGetPropertyValue($arParams['BLOCKS_STAFF_1_PROPERTY_DESCRIPTION']);

    if (empty($arBlock['DESCRIPTION']))
        $arBlock['DESCRIPTION'] = $arParams['BLOCKS_STAFF_1_DESCRIPTION'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок rates.1 */
$arBlock = &$arResult['BLOCKS']['rates.1'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_RATES_1_';
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_RATES_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_RATES_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_RATES_1_PROPERTY_ELEMENTS'], true),
        'PROPERTIES' => $arParams['BLOCKS_RATES_1_PROPERTIES']
    ];

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_RATES_1_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_RATES_1_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок plan.1 */
$arBlock = &$arResult['BLOCKS']['plan.1'];

if ($arBlock['ACTIVE']) {
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_PLAN_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_PLAN_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_PLAN_1_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_PLAN_1_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_PLAN_1_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок form.2 */
$arBlock = &$arResult['BLOCKS']['form.2'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_FORM_2_';

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }
}

/** Блок projects.1 */
$arBlock = &$arResult['BLOCKS']['projects.1'];

if ($arBlock['ACTIVE']) {
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_PROJECTS_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_PROJECTS_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_PROJECTS_1_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_PROJECTS_1_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_PROJECTS_1_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок brands.1 */
$arBlock = &$arResult['BLOCKS']['brands.1'];

if ($arBlock['ACTIVE']) {
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_BRANDS_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_BRANDS_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_BRANDS_1_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['FOOTER'] = [
        'BUTTON_TEXT' => $arParams['BLOCKS_BRANDS_1_BUTTON_TEXT'],
        'LIST_PAGE_URL' => $arParams['BLOCKS_BRANDS_1_LIST_PAGE_URL']
    ];

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_BRANDS_1_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_BRANDS_1_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок reviews.1 */
$arBlock = &$arResult['BLOCKS']['reviews.1'];

if ($arBlock['ACTIVE']) {
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_REVIEWS_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_REVIEWS_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_REVIEWS_1_PROPERTY_ELEMENTS'], true),
        'PROPERTIES' => [
            'POSITION' => $arParams['BLOCKS_REVIEWS_1_PROPERTY_POSITION']
        ]
    ];

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_REVIEWS_1_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_REVIEWS_1_HEADER'];

    $arBlock['PAGE'] = $arParams['BLOCKS_REVIEWS_1_PAGE'];
    $arBlock['BUTTON'] = [
        'SHOW' => $arParams['BLOCKS_REVIEWS_1_BUTTON_SHOW'] === 'Y',
        'TEXT' => $arParams['BLOCKS_REVIEWS_1_BUTTON_TEXT']
    ];

    if (empty($arBlock['BUTTON']['TEXT']))
        $arBlock['BUTTON']['SHOW'] = false;

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок services.1 */
$arBlock = &$arResult['BLOCKS']['services.1'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_SERVICES_1_';
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_SERVICES_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_SERVICES_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_SERVICES_1_PROPERTY_ELEMENTS'], true),
    ];

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_SERVICES_1_PROPERTY_HEADER']);
    $arBlock['DESCRIPTION'] = $fGetPropertyValue($arParams['BLOCKS_SERVICES_1_PROPERTY_DESCRIPTION']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_SERVICES_1_HEADER'];

    if (empty($arBlock['DESCRIPTION']))
        $arBlock['DESCRIPTION'] = $arParams['BLOCKS_SERVICES_1_DESCRIPTION'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок videos.1 */
$arBlock = &$arResult['BLOCKS']['videos.1'];

if ($arBlock['ACTIVE']) {
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_VIDEOS_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_VIDEOS_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_VIDEOS_1_PROPERTY_ELEMENTS'], true),
        'PROPERTIES' => [
            'LINK' => $arParams['BLOCKS_VIDEOS_1_PROPERTY_LINK']
        ]
    ];

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_VIDEOS_1_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_VIDEOS_1_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок faq.1 */
$arBlock = &$arResult['BLOCKS']['faq.1'];

if ($arBlock['ACTIVE']) {
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_FAQ_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_FAQ_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_FAQ_1_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_FAQ_1_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_FAQ_1_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок services.2 */
$arBlock = &$arResult['BLOCKS']['services.2'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_SERVICES_2_';
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_SERVICES_2_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_SERVICES_2_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_SERVICES_2_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_SERVICES_2_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_SERVICES_2_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок services.3 */
$arBlock = &$arResult['BLOCKS']['services.3'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_SERVICES_3_';
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_SERVICES_3_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_SERVICES_3_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_SERVICES_3_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_SERVICES_3_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_SERVICES_3_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок result.2 */
$arBlock = &$arResult['BLOCKS']['result.2'];

if ($arBlock['ACTIVE']) {
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_RESULT_2_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_RESULT_2_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_RESULT_2_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_RESULT_2_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_RESULT_2_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок benefits.1 */
$arBlock = &$arResult['BLOCKS']['benefits.1'];

if ($arBlock['ACTIVE']) {
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_BENEFITS_1_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_BENEFITS_1_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_BENEFITS_1_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_BENEFITS_1_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_BENEFITS_1_HEADER'];

    $arBlock['DESCRIPTION'] = $fGetPropertyValue($arParams['BLOCKS_BENEFITS_1_PROPERTY_DESCRIPTION']);

    if (empty($arBlock['DESCRIPTION']))
        $arBlock['DESCRIPTION'] = $arParams['BLOCKS_BENEFITS_1_DESCRIPTION'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок benefits.2 */
$arBlock = &$arResult['BLOCKS']['benefits.2'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_BENEFITS_2_';
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_BENEFITS_2_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_BENEFITS_2_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_BENEFITS_2_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_BENEFITS_2_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_BENEFITS_2_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок benefits.3 */
$arBlock = &$arResult['BLOCKS']['benefits.3'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_BENEFITS_3_';
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_BENEFITS_3_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_BENEFITS_3_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_BENEFITS_3_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_BENEFITS_3_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_BENEFITS_3_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок benefits.4 */
$arBlock = &$arResult['BLOCKS']['benefits.4'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_BENEFITS_4_';
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_BENEFITS_4_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_BENEFITS_4_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_BENEFITS_4_PROPERTY_ELEMENTS'], true)
    ];

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_BENEFITS_4_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_BENEFITS_4_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

/** Блок rates.2 */
$arBlock = &$arResult['BLOCKS']['rates.2'];

if ($arBlock['ACTIVE']) {
    $sPrefix = 'BLOCKS_RATES_2_';
    $arBlock['IBLOCK'] = [
        'TYPE' => $arParams['BLOCKS_RATES_2_IBLOCK_TYPE'],
        'ID' => $arParams['BLOCKS_RATES_2_IBLOCK_ID'],
        'ELEMENTS' => $fGetPropertyValue($arParams['BLOCKS_RATES_2_PROPERTY_ELEMENTS'], true),
        'PROPERTIES' => $arParams['BLOCKS_RATES_2_PROPERTIES']
    ];

    $arBlock['PARAMETERS'] = [];

    foreach ($arParams as $sKey => $mValue) {
        if (!StringHelper::startsWith($sKey, $sPrefix))
            continue;

        $sKey = StringHelper::cut(
            $sKey,
            StringHelper::length($sPrefix)
        );

        $arBlock['PARAMETERS'][$sKey] = $mValue;
    }

    $arBlock['HEADER'] = $fGetPropertyValue($arParams['BLOCKS_RATES_2_PROPERTY_HEADER']);

    if (empty($arBlock['HEADER']))
        $arBlock['HEADER'] = $arParams['BLOCKS_RATES_2_HEADER'];

    if (!empty($arBlock['IBLOCK']['ELEMENTS']) && !Type::isArray($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['IBLOCK']['ELEMENTS'] = [$arBlock['IBLOCK']['ELEMENTS']];

    if (empty($arBlock['IBLOCK']['ID']) || empty($arBlock['IBLOCK']['ELEMENTS']))
        $arBlock['ACTIVE'] = false;
}

unset($arBlock);

$this->__component->SetResultCacheKeys(['PREVIEW_PICTURE', 'DETAIL_PICTURE']);