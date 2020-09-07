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

if ($arCurrentValues['IBLOCK_ID']) {
    if (!empty($_REQUEST['site'])) {
        $sSite = $_REQUEST['site'];
    } else if (!empty($_REQUEST['src_site'])) {
        $sSite = $_REQUEST['src_site'];
    }

    $arIBlockTypes = CIBlockParameters::GetIBlockTypes();
    $arIBlocksList = Arrays::fromDBResult(CIBlock::GetList([], [
        'ACTIVE' => 'Y',
        'SITE_ID' => $sSite
    ]))->indexBy('ID');

    $hGetIBlocks = function ($type = '') use (&$arIBlocksList) {
        if (!empty($type))
            $arIBlocks = $arIBlocksList->asArray(function ($sKey, $arProperty) use (&$type) {
                if ($arProperty['IBLOCK_TYPE_ID'] === $type)
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

        return $arIBlocks;
    };

    $arProperties = Arrays::fromDBResult(CIBlockProperty::GetList(['SORT' => 'ASC'], [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID']
    ]))->indexBy('ID');

    $hPropertyText = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] == 'S' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'N')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };
    $hPropertyLink = function ($sKey, $arProperty) {
        if ($arProperty['PROPERTY_TYPE'] === 'E' && $arProperty['LIST_TYPE'] === 'L')
            return [
                'key' => $arProperty['CODE'],
                'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
            ];

        return ['skip' => true];
    };

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyLink = $arProperties->asArray($hPropertyLink);
}

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['VIDEO_IBLOCK_TYPE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_VIDEO_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlockTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['VIDEO_IBLOCK_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_VIDEO_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $hGetIBlocks($arCurrentValues['VIDEO_IBLOCK_TYPE']),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['SERVICES_IBLOCK_TYPE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_SERVICES_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlockTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['SERVICES_IBLOCK_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_SERVICES_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $hGetIBlocks($arCurrentValues['SERVICES_IBLOCK_TYPE']),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROJECTS_IBLOCK_TYPE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_PROJECTS_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlockTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['PROJECTS_IBLOCK_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_PROJECTS_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $hGetIBlocks($arCurrentValues['PROJECTS_IBLOCK_TYPE']),
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    $arTemplateParameters['PROPERTY_POSITION'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_PROPERTY_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['VIDEO_IBLOCK_ID'])) {
        $arTemplateParameters['PROPERTY_VIDEO'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_PROPERTY_VIDEO'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyLink,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];

        if (!empty($arCurrentValues['PROPERTY_VIDEO'])) {
            $arVideoProperties = Arrays::fromDBResult(CIBlockProperty::GetList([], [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $arCurrentValues['VIDEO_IBLOCK_ID']
            ]))->indexBy('ID');

            $arTemplateParameters['VIDEO_PROPERTY_LINK'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_VIDEO_PROPERTY_LINK'),
                'TYPE' => 'LIST',
                'VALUES' => $arVideoProperties->asArray($hPropertyText),
                'ADDITIONAL_VALUES' => 'Y',
                'REFRESH' => 'Y'
            ];

            unset($arVideoProperties);
        }
    }

    if (!empty($arCurrentValues['SERVICES_IBLOCK_ID'])) {
        $arTemplateParameters['PROPERTY_SERVICES'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_PROPERTY_SERVICES'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyLink,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
    }

    if (!empty($arCurrentValues['PROJECTS_IBLOCK_ID'])) {
        $arTemplateParameters['PROPERTY_PROJECTS'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_PROPERTY_PROJECTS'),
            'TYPE' => 'LIST',
            'VALUES' => $arPropertyLink,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y'
        ];
    }
}

$arTemplateParameters['LINK_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_LINK_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['LINK_USE'] === 'Y') {
    $arTemplateParameters['LINK_BLANK'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_LINK_BLANK'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROPERTY_POSITION'])) {
    $arTemplateParameters['POSITION_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_POSITION_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['VIDEO_IBLOCK_ID']) && !empty($arCurrentValues['PROPERTY_VIDEO']) && !empty($arCurrentValues['VIDEO_PROPERTY_LINK'])) {
    $arTemplateParameters['VIDEO_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_VIDEO_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['VIDEO_SHOW'] === 'Y') {
        $arTemplateParameters['VIDEO_QUALITY'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_VIDEO_QUALITY'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'mqdefault' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_VIDEO_QUALITY_MQ'),
                'hqdefault' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_VIDEO_QUALITY_HQ'),
                'sddefault' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_VIDEO_QUALITY_SD'),
                'maxresdefault' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_VIDEO_QUALITY_MAXRES')
            ],
            'DEFAULT' => 'sddefault'
        ];
    }
}

if (!empty($arCurrentValues['SERVICES_IBLOCK_ID']) && !empty($arCurrentValues['PROPERTY_SERVICES'])) {
    $arTemplateParameters['SERVICES_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_SERVICES_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (!empty($arCurrentValues['PROJECTS_IBLOCK_ID']) && !empty($arCurrentValues['PROPERTY_PROJECTS'])) {
    $arTemplateParameters['PROJECTS_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_PROJECTS_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['FOOTER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_FOOTER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FOOTER_SHOW'] == 'Y') {
    $arTemplateParameters['FOOTER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_FOOTER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arTemplateParameters['FOOTER_BUTTON_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_FOOTER_BUTTON_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FOOTER_BUTTON_SHOW'] === 'Y') {
        $arTemplateParameters['FOOTER_BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_FOOTER_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_8_FOOTER_BUTTON_TEXT_DEFAULT')
        ];
    }
}