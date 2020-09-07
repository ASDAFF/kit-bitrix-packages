<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_VIDEO_TEMP1_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_VIDEO_TEMP1_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

/** DATA_SOURCE */
$arTemplateParameters['WIDTH'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_VIDEO_TEMP1_WIDTH'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

/** VISUAL */
$arTemplateParameters['HEIGHT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_VIDEO_TEMP1_HEIGHT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        300 => '300px',
        350 => '350px',
        400 => '400px',
        450 => '450px',
        500 => '500px'
    ],
    'DEFAULT' => 400,
    'ADDITIONAL_VALUES' => 'Y'
];

$arTemplateParameters['PARALLAX_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_VIDEO_TEMP1_PARALLAX_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PARALLAX_USE'] == 'Y') {
    $arTemplateParameters['PARALLAX_RATIO'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_VIDEO_TEMP1_PARALLAX_RATIO'),
        'TYPE' => 'STRING',
        'DEFAULT' => 40
    ];
}

$arTemplateParameters['BUTTON_COLOR_THEME'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_VIDEO_TEMP1_BUTTON_COLOR_THEME'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'dark' => Loc::getMessage('C_VIDEO_TEMP1_BUTTON_COLOR_THEME_DARK'),
        'light' => Loc::getMessage('C_VIDEO_TEMP1_BUTTON_COLOR_THEME_LIGHT'),
        'custom' => Loc::getMessage('C_VIDEO_TEMP1_BUTTON_COLOR_THEME_CUSTOM')
    ],
    'DEFAULT' => 'light',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['BUTTON_COLOR_THEME'] == 'custom') {
    $arTemplateParameters['BUTTON_COLOR_CUSTOM'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_VIDEO_TEMP1_BUTTON_COLOR_CUSTOM'),
        'TYPE' => 'STRING'
    ];
}