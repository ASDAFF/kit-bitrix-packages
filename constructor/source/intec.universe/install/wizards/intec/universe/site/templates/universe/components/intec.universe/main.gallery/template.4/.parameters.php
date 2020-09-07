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
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['WIDE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_WIDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

$arTemplateParameters['TABS_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_TABS_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['TABS_USE'] === 'Y') {
    $arTemplateParameters['TABS_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_TABS_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];
}

$arTemplateParameters['FOOTER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_FOOTER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FOOTER_SHOW'] == 'Y') {
    $arTemplateParameters['FOOTER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_FOOTER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];

    $arTemplateParameters['FOOTER_BUTTON_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_FOOTER_BUTTON_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FOOTER_BUTTON_SHOW'] === 'Y') {
        $arTemplateParameters['FOOTER_BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_FOOTER_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_FOOTER_BUTTON_TEXT_DEFAULT')
        ];
        $arTemplateParameters['LIST_PAGE_URL'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_4_LIST_PAGE_URL'),
            'TYPE' => 'STRING'
        ];
    }
}