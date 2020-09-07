<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use intec\Core;
use intec\core\collections\Arrays;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'PROPERTY_TAGS' => null,
    'DATE_TYPE' => 'DATE_ACTIVE_FROM',
    'LINK_USE' => 'N',
    'LINK_BLANK' => 'N',
    'TAGS_SHOW' => 'N',
    'TAGS_VARIABLE' => 'tag',
    'TAGS_MODE' => 'default'
], $arParams);

$arVisual = [
    'DATE' => [
        'TYPE' => ArrayHelper::fromRange([
            'DATE_ACTIVE_FROM',
            'DATE_CREATE',
            'TIMESTAMP_X',
            'DATE_ACTIVE_TO'
        ], $arParams['DATE_TYPE'])
    ],
    'LINK' => [
        'USE' => $arParams['LINK_USE'] === 'Y',
        'BLANK' => $arParams['LINK_BLANK'] === 'Y'
    ]
];

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['DATA'] = [
        'DATE' => null,
        'TAGS' => []
    ];

    /** Дата */
    if (!empty($arParams['DATE_FORMAT'])) {
        if (!empty($arItem['TIMESTAMP_X'])) {
            $arItem['TIMESTAMP_X_FORMATTED'] = CIBlockFormatProperties::DateFormat(
                $arParams['DATE_FORMAT'],
                MakeTimeStamp(
                    $arItem['TIMESTAMP_X'],
                    CSite::GetDateFormat()
                )
            );
        }
    }

    if (!empty($arItem[$arParams['DATE_TYPE'].'_FORMATTED'])) {
        $arItem['DATA']['DATE'] = $arItem[$arParams['DATE_TYPE'].'_FORMATTED'];
    } else {
        $arItem['DATA']['DATE'] = $arItem['DATE_CREATE'];
    }

    /** Теги */
    if (!empty($arParams['PROPERTY_TAGS'])) {
        $arProperty = ArrayHelper::getValue($arItem, [
            'PROPERTIES',
            $arParams['PROPERTY_TAGS']
        ]);

        if (!empty($arProperty['VALUE_ENUM_ID']) && Type::isArray($arProperty['VALUE_ENUM_ID'])) {
            foreach ($arProperty['VALUE_ENUM_ID'] as $key => $sValue) {
                $arItem['DATA']['TAGS'][$arProperty['VALUE_XML_ID'][$key]] = $arProperty['VALUE'][$key];
            }

            unset($key, $sValue);
        }

        unset($arProperty);
    }
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

$arResult['VISUAL'] = ArrayHelper::merge($arResult['VISUAL'], $arVisual);
$arResult['TAGS'] = $arTags;

unset($arVisual);