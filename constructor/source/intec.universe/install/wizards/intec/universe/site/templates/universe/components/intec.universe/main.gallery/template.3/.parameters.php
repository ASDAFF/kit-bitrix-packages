<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('intec.core'))
    return;

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => ArrayHelper::merge([
        3 => '3',
        4 => '4'
    ], $arCurrentValues['WIDE'] === 'Y' ? [5 => '5', 6 => '6'] : []),
    'DEFAULT' => 4
];
$arTemplateParameters['WIDE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_WIDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];
$arTemplateParameters['INDENT_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_INDENT_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['INDENT_USE'] === 'Y') {
    $arTemplateParameters['INDENT_VALUE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_INDENT_VALUE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'small' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_INDENT_VALUE_SMALL'),
            'default' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_INDENT_VALUE_DEFAULT'),
            'big' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_INDENT_VALUE_BIG')
        ],
        'DEFAULT' => 'default'
    ];
}

$arTemplateParameters['TABS_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_TABS_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['TABS_USE'] === 'Y') {
    $arTemplateParameters['TABS_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_TABS_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
}

$arTemplateParameters['SLIDER_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_SLIDER_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SLIDER_USE'] === 'Y') {
    $arTemplateParameters['SLIDER_NAV'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_SLIDER_NAV'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
    $arTemplateParameters['SLIDER_DOTS'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_SLIDER_DOTS'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
    $arTemplateParameters['SLIDER_LOOP'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_SLIDER_LOOP'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['SLIDER_LOOP'] === 'Y') {
        $arTemplateParameters['SLIDER_CENTER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_SLIDER_CENTER'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }

    $arTemplateParameters['SLIDER_AUTO_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_SLIDER_AUTO_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['SLIDER_AUTO_USE'] === 'Y') {
        $arTemplateParameters['SLIDER_AUTO_TIME'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_SLIDER_AUTO_TIME'),
            'TYPE' => 'STRING',
            'DEFAULT' => '5000'
        ];
        $arTemplateParameters['SLIDER_AUTO_HOVER'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_SLIDER_AUTO_HOVER'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

$arTemplateParameters['FOOTER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_FOOTER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FOOTER_SHOW'] == 'Y') {
    $arTemplateParameters['FOOTER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_FOOTER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];

    $arTemplateParameters['FOOTER_BUTTON_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_FOOTER_BUTTON_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FOOTER_BUTTON_SHOW'] === 'Y') {
        $arTemplateParameters['FOOTER_BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_FOOTER_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_FOOTER_BUTTON_TEXT_DEFAULT')
        ];
        $arTemplateParameters['LIST_PAGE_URL'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_3_LIST_PAGE_URL'),
            'TYPE' => 'STRING'
        ];
    }
}