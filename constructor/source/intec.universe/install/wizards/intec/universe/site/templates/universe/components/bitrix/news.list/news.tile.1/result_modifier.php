<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\Core;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'LAZYLOAD_USE' => 'N',
    'PROPERTY_TAGS' => null,
    'COLUMNS' => 4,
    'VIEW' => 'default',
    'LINK_BLANK' => 'N',
    'DATE_SHOW' => 'N',
    'DATE_TYPE' => 'DATE_ACTIVE_FROM',
    'DATE_FORMAT' => 'd.m.Y',
    'PREVIEW_SHOW' => 'N',
    'PREVIEW_TRUNCATE_USE' => 'N',
    'PREVIEW_TRUNCATE_COUNT' => 30,
    'TAGS_SHOW' => 'N',
    'TAGS_VARIABLE' => 'tag',
    'TAGS_MODE' => 'default'
], $arParams);

$arVisual = [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ],
    'COLUMNS' => ArrayHelper::fromRange([4, 3], $arParams['COLUMNS']),
    'LINK' => [
        'BLANK' => $arParams['LINK_BLANK'] === 'Y'
    ],
    'VIEW' => ArrayHelper::fromRange(['default', 'big'], $arParams['VIEW']),
    'DATE' => [
        'SHOW' => $arParams['DATE_SHOW'] === 'Y',
        'TYPE' => ArrayHelper::fromRange([
            'DATE_ACTIVE_FROM',
            'DATE_CREATE',
            'DATE_ACTIVE_TO',
            'TIMESTAMP_X'
        ], $arParams['DATE_TYPE']),
        'FORMAT' => $arParams['DATE_FORMAT']
    ],
    'PREVIEW' => [
        'SHOW' => $arParams['PREVIEW_SHOW'] === 'Y',
        'TRUNCATE' => [
            'USE' => $arParams['PREVIEW_TRUNCATE_USE'] === 'Y',
            'COUNT' => Type::toInteger($arParams['PREVIEW_TRUNCATE_COUNT'])
        ]
    ],
    'NAVIGATION' => [
        'SHOW' => [
            'TOP' => false,
            'BOTTOM' => false,
            'ALWAYS' => $arParams['PAGER_SHOW_ALWAYS']
        ],
        'COUNT' => Type::toInteger($arParams['NEWS_COUNT'])
    ]
];

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

foreach ($arResult['ITEMS'] as &$arItem) {
    $arData = [
        'DATE' => null,
        'TAGS' => [],
        'PREVIEW' => null
    ];

    /** Дата */
    $sDate = ArrayHelper::getValue($arItem, $arVisual['DATE']['TYPE']);

    if (empty($sDate))
        $sDate = $arItem['DATE_CREATE'];

    if (!empty($arVisual['DATE']['FORMAT']))
        $sDate = CIBlockFormatProperties::DateFormat(
            $arVisual['DATE']['FORMAT'],
            MakeTimeStamp($sDate, CSite::GetDateFormat())
        );

    $arData['DATE'] = trim($sDate);

    unset($sDate);

    /** Теги */
    if (!empty($arParams['PROPERTY_TAGS'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_TAGS']
        ]);

        if (!empty($arProperty['VALUE_ENUM_ID']) && Type::isArray($arProperty['VALUE_ENUM_ID'])) {
            foreach ($arProperty['VALUE_ENUM_ID'] as $key => $sValue) {
                $arData['TAGS'][$arProperty['VALUE_XML_ID'][$key]] = $arProperty['VALUE'][$key];
            }

            unset($key, $sValue);
        }

        unset($arProperty);
    }

    /** Превью */
    if ($arVisual['PREVIEW']['SHOW'] && !empty($arItem['PREVIEW_TEXT'])) {
        $sPreview = $arItem['PREVIEW_TEXT'];

        if ($arVisual['PREVIEW']['TRUNCATE']['USE']) {
            $sPreview = Html::stripTags($sPreview);

            if (!empty($sPreview)) {
                $sPreview = preg_split(
                    '/[\s]+/',
                    $sPreview,
                    $arVisual['PREVIEW']['TRUNCATE']['COUNT'] + 1
                );

                if (count($sPreview) > $arVisual['PREVIEW']['TRUNCATE']['COUNT']) {
                    $sPreview = ArrayHelper::slice(
                        $sPreview,
                        0,
                        $arVisual['PREVIEW']['TRUNCATE']['COUNT']
                    );

                    $sPreview = implode(' ', $sPreview).'...';
                } else {
                    $sPreview = $arItem['PREVIEW_TEXT'];
                }
            }
        }

        $arData['PREVIEW'] = $sPreview;

        unset($sPreview);
    }

    $arItem['DATA'] = $arData;

    unset($arData);
}

unset($arItem);

/** Теги (общее) */
$arTags = [
    'SHOW' => $arParams['TAGS_SHOW'] === 'Y' && !empty($arParams['PROPERTY_TAGS']),
    'VARIABLE' => $arParams['TAGS_VARIABLE'],
    'MODE' => ArrayHelper::fromRange(['default', 'active'], $arParams['TAGS_MODE']),
    'LIST' => [],
    'ACTIVE' => []
];

if (empty($arTags['VARIABLE']))
    $arTags['VARIABLE'] = 'tags';

if ($arTags['SHOW']) {
    $arTagsList = Arrays::fromDBResult(CIBlockPropertyEnum::GetList(['SORT' => 'ASC'], [
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'CODE' => $arParams['PROPERTY_TAGS']
    ]))->asArray(function ($key, $sValue) {
        return [
            'key' => $sValue['XML_ID'],
            'value' => $sValue['VALUE']
        ];
    });

    if (!empty($arTagsList)) {
        $arTags['LIST'] = $arTagsList;

        $arTagsActive = Core::$app->request->get($arTags['VARIABLE']);

        if (!empty($arTagsActive) && Type::isArray($arTagsActive))
            $arTags['ACTIVE'] = $arTagsActive;

        unset($arTagsActive);
    } else {
        $arTags['SHOW'] = false;
    }

    unset($arTagsList);
}

/** Навигация */
$arNavigation = [];

if (!empty($arResult['NAV_RESULT'])) {
    $arNavigation = [
        'PAGE' => [
            'COUNT' => $arResult['NAV_RESULT']->NavPageCount,
            'NUMBER' => $arResult['NAV_RESULT']->NavPageNomer,
        ],
        'NUMBER' => $arResult['NAV_RESULT']->NavNum
    ];

    if ($arVisual['NAVIGATION']['SHOW']['ALWAYS']) {
        $arVisual['NAVIGATION']['SHOW']['TOP'] = $arParams['DISPLAY_TOP_PAGER'];
        $arVisual['NAVIGATION']['SHOW']['BOTTOM'] = $arParams['DISPLAY_BOTTOM_PAGER'];
    } else if ($arVisual['NAVIGATION']['COUNT'] > 0 && $arNavigation['PAGE']['COUNT'] > 1) {
        $arVisual['NAVIGATION']['SHOW']['TOP'] = $arParams['DISPLAY_TOP_PAGER'];
        $arVisual['NAVIGATION']['SHOW']['BOTTOM'] = $arParams['DISPLAY_BOTTOM_PAGER'];
    }
} else {
    $arVisual['NAVIGATION']['SHOW']['TOP'] = false;
    $arVisual['NAVIGATION']['SHOW']['BOTTOM'] = false;
}

$arResult['VISUAL'] = $arVisual;
$arResult['TAGS'] = $arTags;
$arResult['NAVIGATION'] = $arNavigation;

unset($arVisual, $arTags, $arNavigation);