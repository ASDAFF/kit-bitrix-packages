<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['DATE_TYPE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_DATE_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'DATE_CREATE' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_DATE_TYPE_DATE_CREATE'),
        'DATE_ACTIVE_FROM' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_DATE_TYPE_DATE_ACTIVE_FROM'),
        'DATE_ACTIVE_TO' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_DATE_TYPE_DATE_ACTIVE_TO'),
        'TIMESTAMP_X' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_DATE_TYPE_TIMESTAMP_X')
    ],
    'DEFAULT' => 'DATE_ACTIVE_FROM'
];
$arTemplateParameters['LINK_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_LINK_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['LINK_USE'] === 'Y') {
    $arTemplateParameters['LINK_BLANK'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_LINK_BLANK'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => '2',
        3 => '3',
        4 => '4'
    ],
    'DEFAULT' => 2,
    'REFRESH' => 'Y'
];

if ($arCurrentValues['COLUMNS'] != 4) {
    $arTemplateParameters['PREVIEW_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_PREVIEW_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['PREVIEW_SHOW'] === 'Y') {
        $arTemplateParameters['PREVIEW_TRUNCATE_USE'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_PREVIEW_TRUNCATE_USE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        ];

        if ($arCurrentValues['PREVIEW_TRUNCATE_USE'] === 'Y') {
            $arTemplateParameters['PREVIEW_TRUNCATE_COUNT'] = [
                'PARENT' => 'VISUAL',
                'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_PREVIEW_TRUNCATE_COUNT'),
                'TYPE' => 'STRING',
                'DEFAULT' => 20
            ];
        }
    }
}

$arTemplateParameters['SLIDER_LOOP'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_SLIDER_LOOP'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['SLIDER_AUTO_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_SLIDER_AUTO_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SLIDER_AUTO_USE'] === 'Y') {
    $arTemplateParameters['SLIDER_AUTO_TIME'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_SLIDER_AUTO_TIME'),
        'TYPE' => 'STRING',
        'DEFAULT' => 10000
    ];
    $arTemplateParameters['SLIDER_AUTO_HOVER'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_SLIDER_AUTO_HOVER'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['FOOTER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_FOOTER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FOOTER_SHOW'] === 'Y') {
    $arTemplateParameters['FOOTER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_FOOTER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_FOOTER_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_FOOTER_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_FOOTER_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
    $arTemplateParameters['FOOTER_BUTTON_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_FOOTER_BUTTON_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FOOTER_BUTTON_SHOW'] === 'Y') {
        $arTemplateParameters['FOOTER_BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_FOOTER_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_NEWS_TEMPLATE_2_FOOTER_BUTTON_TEXT_DEFAULT')
        ];
    }
}