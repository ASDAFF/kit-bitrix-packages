<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['LINE_COUNT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_LINE_COUNT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        4 => 4,
        6 => 6
    ],
    'DEFAULT' => 6
];

$arTemplateParameters['ALIGNMENT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_ALIGNMENT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_ALIGNMENT_LEFT'),
        'center' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_ALIGNMENT_CENTER'),
        'right' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_ALIGNMENT_RIGHT')
    ],
    'DEFAULT' => 'center'
];

$arTemplateParameters['DELIMITERS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_DELIMITERS'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['WIDE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_WIDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arTemplateParameters['FOOTER_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_FOOTER_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['FOOTER_SHOW'] === 'Y') {
    $arTemplateParameters['FOOTER_POSITION'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_FOOTER_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'left' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_POSITION_LEFT'),
            'center' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_POSITION_CENTER'),
            'right' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_POSITION_RIGHT')
        ],
        'DEFAULT' => 'center'
    ];

    $arTemplateParameters['FOOTER_BUTTON_SHOW'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_FOOTER_BUTTON_SHOW'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y'
    ];

    if ($arCurrentValues['FOOTER_BUTTON_SHOW'] === 'Y') {
        $arTemplateParameters['FOOTER_BUTTON_TEXT'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_FOOTER_BUTTON_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_FOOTER_BUTTON_TEXT_DEFAULT')
        ];
    }
}

$arTemplateParameters['LIST_PAGE_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_MAIN_GALLERY_TEMPLATE_2_LIST_PAGE_URL'),
    'TYPE' => 'STRING'
];