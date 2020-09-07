<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\collections\Arrays;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$arIBlocksTypes = CIBlockParameters::GetIBlockTypes();
$arIBlock = null;
$arFilter = [
    'ACTIVE' => 'Y'
];

if (!empty($arCurrentValues['IBLOCK_TYPE']))
    $arFilter['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];

$arIBlocks = Arrays::fromDBResult(CIBlock::GetList([
    'SORT' => 'ASC'
], $arFilter))->indexBy('ID');

if (!empty($arCurrentValues['IBLOCK_ID']))
    $arIBlock = $arIBlocks->get($arCurrentValues['IBLOCK_ID']);

$arSections = Arrays::from([]);
$arProperties = Arrays::from([]);

if (!empty($arIBlock)) {
    $arSections = Arrays::fromDBResult(CIBlockSection::GetList(['SORT' => 'LEFT_MARGIN'], [
        'IBLOCK_ID' => $arIBlock['ID'],
        'ACTIVE' => 'Y'
    ]))->indexBy('ID');

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
        'IBLOCK_ID' => $arIBlock['ID'],
        'ACTIVE' => 'Y'
    ]))->indexBy('ID');
}

$arParameters = [];

/** BASE */
$arParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_TAGS_LIST_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocksTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

$arParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_TAGS_LIST_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocks->asArray(function ($sKey, $arIBlock) {
        return [
            'key' => $arIBlock['ID'],
            'value' => '['.$arIBlock['ID'].'] '.$arIBlock['NAME']
        ];
    }),
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];

if (!empty($arIBlock)) {
    $arParameters['SECTION_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_TAGS_LIST_SECTION_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arSections->asArray(function ($sKey, $arSection) {
            return [
                'key' => $arSection['ID'],
                'value' => '['.$arSection['ID'].'] '.str_repeat('.',$arSection['DEPTH_LEVEL']).$arSection['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];

    $arParameters['SECTION_SUBSECTIONS'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_TAGS_LIST_SECTION_SUBSECTIONS'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'N' => Loc::getMessage('C_TAGS_LIST_SECTION_SUBSECTIONS_N'),
            'Y' => Loc::getMessage('C_TAGS_LIST_SECTION_SUBSECTIONS_Y'),
            'A' => Loc::getMessage('C_TAGS_LIST_SECTION_SUBSECTIONS_A')
        ]
    ];

    $arParameters['PROPERTY'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_TAGS_LIST_PROPERTY'),
        'TYPE' => 'LIST',
        'VALUES' => $arProperties->asArray(function ($sKey, $arProperty) {
            if (empty($arProperty['CODE']) || $arProperty['PROPERTY_TYPE'] != 'L' || $arProperty['USER_TYPE'] !== null)
                return ['skip' => true];

            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];
        }),
        'ADDITIONAL_VALUES' => 'Y'
    ];
}

$arParameters['COUNT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_TAGS_LIST_COUNT'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arParameters['USED'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_TAGS_LIST_USED'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arParameters['FILTER_NAME'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_TAGS_LIST_FILTER_NAME'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'arrFilter'
];

$arParameters['VARIABLE_TAGS'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_TAGS_LIST_VARIABLE_TAGS'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'tags'
];

/** CACHE */
$arParameters['CACHE_TIME'] = [];

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => $arParameters
];