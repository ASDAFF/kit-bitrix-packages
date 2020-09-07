<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['PICTURE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_PICTURE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => 2,
        3 => 3,
        4 => 4
    ],
    'DEFAULT' => 3
];
$arTemplateParameters['PICTURE_SIZE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_PICTURE_SIZE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        60 => '60x60',
        80 => '80x80',
        100 => '100x100'
    ],
    'DEFAULT' => 3
];
$arTemplateParameters['NAME_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_NAME_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['NAME_SIZE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_NAME_SIZE'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'big' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_BIG_SIZE'),
        'middle' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_MIDDLE_SIZE'),
        'small' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_SMALL_SIZE')
    ],
    'DEFAULT' => 'middle'
];
$arTemplateParameters['NAME_MARGIN'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_NAME_MARGIN'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'big' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_BIG_SIZE'),
        'middle' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_MIDDLE_SIZE'),
        'small' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_SMALL_SIZE')
    ],
    'DEFAULT' => 'middle'
];
$arTemplateParameters['ELEMENT_MARGIN'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_ELEMENT_MARGIN'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'big' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_BIG_SIZE'),
        'middle' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_MIDDLE_SIZE'),
        'small' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_13_SMALL_SIZE')
    ],
    'DEFAULT' => 'middle'
];