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
$arIBlocks = [];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    if (!empty($_REQUEST['site'])) {
        $sSite = $_REQUEST['site'];
    } else if (!empty($_REQUEST['src_site'])) {
        $sSite = $_REQUEST['src_site'];
    }

    $arIBlocksList = Arrays::fromDBResult(CIBlock::GetList([], [
        'ACTIVE' => 'Y',
        'SITE_ID' => $sSite
    ]))->indexBy('ID');

    if (!empty($arCurrentValues['STAFF_IBLOCK_TYPE']))
        $arIBlocks = $arIBlocksList->asArray(function ($sKey, $arProperty) use (&$arCurrentValues) {
            if ($arProperty['IBLOCK_TYPE_ID'] === $arCurrentValues['STAFF_IBLOCK_TYPE'])
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

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'S' && $arProperty['LIST_TYPE'] == 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyLink = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyFile = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'F' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arStaffProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['STAFF_IBLOCK_ID']
    ]))->indexBy('ID');

    $hStaffPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'S' && $arProperty['LIST_TYPE'] === 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyLink = $arProperties->asArray($hPropertyLink);
    $arPropertyFile = $arProperties->asArray($hPropertyFile);
    $arStaffPropertyText = $arStaffProperties->asArray($hStaffPropertyText);
}

$arTemplateParameters = [];
$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];
$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['STAFF_IBLOCK_TYPE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_STAFF_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlockTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['STAFF_IBLOCK_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_STAFF_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocks,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['STAFF_IBLOCK_ID'])) {
        $arTemplateParameters['PROPERTY_STAFF'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_PROPERTY_STAFF'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyLink,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

            if (!empty($arCurrentValues['PROPERTY_STAFF'])) {
                $arTemplateParameters['PROPERTY_STAFF_POSITION'] = [
                    'PARENT' => 'DATA_SOURCE',
                    'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_PROPERTY_STAFF_POSITION'),
                    'TYPE' => 'LIST',
                    'VALUES' => $arStaffPropertyText,
                    'ADDITIONAL_VALUES' => 'Y',
                    'REFRESH' => 'Y'
                ];
            }
    }

    $arTemplateParameters['PROPERTY_TIME_FROM'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_PROPERTY_TIME_FROM'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_TIME_TO'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_PROPERTY_TIME_TO'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_TEXT'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_PROPERTY_TEXT'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROPERTY_FILE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_PROPERTY_FILE'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyFile,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['LINES_FIRST'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_LINES_FIRST'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'light' => Loc::getMessage('C_SCHEDULE_TEMP1_LINES_FIRST_LIGHT'),
        'dark' => Loc::getMessage('C_SCHEDULE_TEMP1_LINES_FIRST_DARK')
    ],
    'DEFAULT' => 'dark'
];

if (!empty($arCurrentValues['PROPERTY_TIME_FROM'])) {
    $arTemplateParameters['TIME_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_TIME_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['TIME_SHOW'] == 'Y') {
        $arTimeFormats = [
            'from' => Loc::getMessage('C_SCHEDULE_TEMP1_TIME_FROM')
        ];

        if (!empty($arCurrentValues['PROPERTY_TIME_TO']))
            $arTimeFormats = array_merge($arTimeFormats, [
                'from-to-1' => Loc::getMessage('C_SCHEDULE_TEMP1_TIME_FROM_TO_1'),
                'from-to-2' => Loc::getMessage('C_SCHEDULE_TEMP1_TIME_FROM_TO_2'),
                'to' => Loc::getMessage('C_SCHEDULE_TEMP1_TIME_TO')
            ]);

        $arTemplateParameters['TIME_FORMAT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_TIME_FORMAT'),
            'TYPE' => 'LIST',
            'VALUES' => $arTimeFormats,
            'DEFAULT' => 'from'
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_STAFF'])) {
    $arTemplateParameters['STAFF_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_STAFF_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['STAFF_SHOW'] == 'Y')) {
        $arTemplateParameters['STAFF_PICTURE_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_STAFF_PICTURE_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
        $arTemplateParameters['STAFF_POSITION_SHOW'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_STAFF_POSITION_SHOW'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_TEXT'])) {
    $arTemplateParameters['TEXT_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_TEXT_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_FILE'])) {
    $arTemplateParameters['FILE_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_FILE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FILE_SHOW'] == 'Y') {
        $arTemplateParameters['FILE_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_SCHEDULE_TEMP1_FILE_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_SCHEDULE_TEMP1_FILE_TEXT_DEFAULT')
        ];
    }
}