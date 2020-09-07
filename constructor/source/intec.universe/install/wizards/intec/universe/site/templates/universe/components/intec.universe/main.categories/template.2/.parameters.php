<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_2_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_2_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => '2',
        3 => '3'
    ],
    'DEFAULT' => 3
];

if (!empty($arCurrentValues['PROPERTY_LINK']) || $arCurrentValues['LINK_MODE'] === 'component') {
    $arTemplateParameters['LINK_USE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_2_LINK_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ];

    if ($arCurrentValues['LINK_USE'] === 'Y') {
        $arTemplateParameters['LINK_BLANK'] = [
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_2_LINK_BLANK'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ];
    }
}

$arTemplateParameters['PICTURE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_2_PICTURE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_CATEGORIES_TEMPLATE_2_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];