<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];
$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['WIDE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_WIDE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['HEIGHT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_HEIGHT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'auto' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_HEIGHT_AUTO'),
        300 => '300px',
        350 => '350px',
        400 => '400px',
        450 => '450px',
        500 => '500px',
        550 => '550px',
        600 => '600px',
        650 => '650px'
    ],
    'DEFAULT' => 'auto',
    'ADDITIONAL_VALUES' => 'Y'
];

if ($arCurrentValues['WIDE'] !== 'Y') {
    $arTemplateParameters['ROUNDED'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_ROUNDED'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];
}

$arTemplateParameters['FADE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_FADE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['SHADOW_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_SHADOW_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SHADOW_USE'] === 'Y') {
    $arTemplateParameters['SHADOW_MODE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_SHADOW_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => [
            'hover' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_SHADOW_MODE_HOVER'),
            'permanent' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_SHADOW_MODE_PERMANENT')
        ],
        'DEFAULT' => 'hover'
    ];
}

$arTemplateParameters['THEME'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_THEME'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'light' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_THEME_LIGHT'),
        'dark' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_THEME_DARK')
    ],
    'DEFAULT' => 'light'
];
$arTemplateParameters['PARALLAX_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_PARALLAX_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PARALLAX_USE'] == 'Y') {
    $arTemplateParameters['PARALLAX_RATIO'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_VIDEO_TEMPLATE_1_PARALLAX_RATIO'),
        'TYPE' => 'STRING',
        'DEFAULT' => 20
    ];
}