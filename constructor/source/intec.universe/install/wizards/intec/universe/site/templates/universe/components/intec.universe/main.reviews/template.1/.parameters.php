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

$arIBlockTypes = [];
$arIBlocks = [];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
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

    if (!empty($arCurrentValues['VIDEO_IBLOCK_TYPE']))
        $arIBlocks = $arIBlocksList->asArray(function ($sKey, $arProperty) use (&$arCurrentValues) {
            if ($arProperty['IBLOCK_TYPE_ID'] === $arCurrentValues['VIDEO_IBLOCK_TYPE'])
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
        if ($arProperty['PROPERTY_TYPE'] == 'S' && $arProperty['LIST_TYPE'] == 'L' && $arProperty['MULTIPLE'] === 'N')
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

    $arPropertyText = $arProperties->asArray($hPropertyText);
    $arPropertyLink = $arProperties->asArray($hPropertyLink);
}

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arTemplateParameters['VIDEO_IBLOCK_TYPE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_VIDEO_IBLOCK_TYPE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlockTypes,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
    $arTemplateParameters['VIDEO_IBLOCK_ID'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_VIDEO_IBLOCK_ID'),
        'TYPE' => 'LIST',
        'VALUES' => $arIBlocks,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];

    if (!empty($arCurrentValues['VIDEO_IBLOCK_ID'])) {
        $arTemplateParameters['PROPERTY_VIDEO'] = [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_PROPERTY_VIDEO'),
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

            $arTemplateParameters['VIDEO_IBLOCK_PROPERTY_LINK'] = [
                'PARENT' => 'DATA_SOURCE',
                'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_VIDEO_IBLOCK_PROPERTY_LINK'),
                'TYPE' => 'LIST',
                'VALUES' => $arVideoProperties->asArray(function ($sKey, $arProperty) {
                    return [
                        'key' => $arProperty['CODE'],
                        'value' => '['.$arProperty['CODE'].'] '.$arProperty['NAME']
                    ];
                }),
                'ADDITIONAL_VALUES' => 'Y',
                'REFRESH' => 'Y'
            ];
        }
    }

    $arTemplateParameters['PROPERTY_POSITION'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_PROPERTY_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => $arPropertyText,
        'ADDITIONAL_VALUES' => 'Y',
        'REFRESH' => 'Y'
    ];
}

$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        1 => '1',
        2 => '2'
    ],
    'DEFAULT' => 2
];
$arTemplateParameters['LINK_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_LINK_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

if ($arCurrentValues['LINK_USE'] === 'Y') {
    $arTemplateParameters['LINK_BLANK'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_LINK_BLANK'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

if (
    !empty($arCurrentValues['VIDEO_IBLOCK_ID']) &&
    !empty($arCurrentValues['PROPERTY_VIDEO']) &&
    !empty($arCurrentValues['VIDEO_IBLOCK_PROPERTY_LINK'])
   ) {
    $arTemplateParameters['VIDEO_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_VIDEO_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    if ($arCurrentValues['VIDEO_SHOW'] === 'Y') {
        $arTemplateParameters['VIDEO_IMAGE_QUALITY'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_VIDEO_IMAGE_QUALITY'),
            'TYPE' => 'LIST',
            'VALUES' => [
                'mqdefault' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_VIDEO_IMAGE_QUALITY_MQ'),
                'hqdefault' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_VIDEO_IMAGE_QUALITY_HQ'),
                'sddefault' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_VIDEO_IMAGE_QUALITY_SD'),
                'maxresdefault' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_VIDEO_IMAGE_QUALITY_MAXRES')
            ],
            'DEFAULT' => 'hqdefault'
        ];
    }
}

if (!empty($arCurrentValues['PROPERTY_POSITION'])) {
    $arTemplateParameters['POSITION_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_POSITION_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['SLIDER_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_SLIDER_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SLIDER_USE'] === 'Y') {
    $arTemplateParameters['SLIDER_DOTS'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_SLIDER_DOTS'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
    $arTemplateParameters['SLIDER_LOOP'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_SLIDER_LOOP'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
    $arTemplateParameters['SLIDER_AUTO_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_SLIDER_AUTO_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['SLIDER_AUTO_USE'] === 'Y') {
        $arTemplateParameters['SLIDER_AUTO_TIME'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_SLIDER_AUTO_TIME'),
            'TYPE' => 'STRING',
            'DEFAULT' => '10000'
        ];
        $arTemplateParameters['SLIDER_AUTO_HOVER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_SLIDER_AUTO_HOVER'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

$arTemplateParameters['FOOTER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_FOOTER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FOOTER_SHOW'] == 'Y') {
    $arTemplateParameters['FOOTER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_FOOTER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arTemplateParameters['FOOTER_BUTTON_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_FOOTER_BUTTON_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FOOTER_BUTTON_SHOW'] === 'Y') {
        $arTemplateParameters['FOOTER_BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_FOOTER_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_REVIEW_TEMPLATE_1_FOOTER_BUTTON_TEXT_DEFAULT')
        ];
    }
}