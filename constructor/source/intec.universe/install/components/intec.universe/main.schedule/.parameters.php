<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

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

$arIBlockTypes = CIBlockParameters::GetIBlockTypes();
$arIBlocksList = Arrays::fromDBResult(CIBlock::GetList())->indexBy('ID');

if (!empty($arCurrentValues['IBLOCK_TYPE']))
    $arIBlocks = $arIBlocksList->asArray(function ($sKey, $arProperty) use (&$arCurrentValues) {
        if ($arProperty['IBLOCK_TYPE_ID'] === $arCurrentValues['IBLOCK_TYPE'])
            return [
                'key' => $arProperty['ID'],
                'value' => '[' . $arProperty['ID'] . '] ' . $arProperty['NAME']
            ];

        return ['skip' => true];
    });
else
    $arIBlocks = $arIBlocksList->asArray(function ($sKey, $arProperty) {
        return [
            'key' => $arProperty['ID'],
            'value' => '['.$arProperty['ID'].'] '.$arProperty['NAME']
        ];
    });

if (!empty($arCurrentValues['IBLOCK_ID'])) {

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
        'ACTIVE' => 'Y'
    ]))->indexBy('ID');

    $hPropertyCheckbox = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'L' && $arProperty['LIST_TYPE'] === 'C' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyCheckbox = $arProperties->asArray($hPropertyCheckbox);
}

$arParameters = [];

/** BASE */
$arParameters['IBLOCK_TYPE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlockTypes,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];
$arParameters['IBLOCK_ID'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_IBLOCK_ID'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlocks,
    'ADDITIONAL_VALUES' => 'Y',
    'REFRESH' => 'Y'
];
$arParameters['ELEMENTS_COUNT'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_ELEMENTS_COUNT'),
    'TYPE' => 'STRING'
];
$arParameters['CACHE_TIME'] = [];

/** DATA_SOURCE */
$arParameters[] = [];

/** VISUAL */
$arParameters['HEADER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_HEADER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['HEADER_SHOW'] == 'Y') {
    $arParameters['HEADER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_HEADER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_SCHEDULE_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_SCHEDULE_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_SCHEDULE_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arParameters['HEADER_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_HEADER_TEXT'),
        'TYPE' => 'STRING',
        'DEFAULT' => Loc::getMessage('C_MAIN_SCHEDULE_HEADER_TEXT_DEFAULT')
    ];
}

$arParameters['DESCRIPTION_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_DESCRIPTION_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['DESCRIPTION_SHOW'] == 'Y') {
    $arParameters['DESCRIPTION_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_DESCRIPTION_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_SCHEDULE_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_SCHEDULE_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_SCHEDULE_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arParameters['DESCRIPTION_TEXT'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_DESCRIPTION_TEXT'),
        'TYPE' => 'STRING'
    ];
}

/** SORT */
$arParameters['SORT_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_SORT_BY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ID' => Loc::getMessage('C_MAIN_SCHEDULE_SORT_BY_ID'),
        'SORT' => Loc::getMessage('C_MAIN_SCHEDULE_SORT_BY_SORT'),
        'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_SORT_BY_NAME')
    ],
    'DEFAULT' => 'SORT',
    'ADDITIONAL_VALUES' => 'Y'
];
$arParameters['ORDER_BY'] = [
    'PARENT' => 'SORT',
    'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_ORDER_BY'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'ASC' => Loc::getMessage('C_MAIN_SCHEDULE_ORDER_BY_ASC'),
        'DESC' => Loc::getMessage('C_MAIN_SCHEDULE_ORDER_BY_DESC')
    ],
    'DEFAULT' => 'ASC'
];

/** Параметры компонента */
$arComponentParameters = [
    'GROUPS' => [
        'SORT' => [
            'NAME' => Loc::getMessage('C_MAIN_SCHEDULE_GROUP_SORT'),
            'SORT' => 800
        ]
    ],
    'PARAMETERS' => $arParameters
];