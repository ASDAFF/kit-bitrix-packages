<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_6_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_6_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_6_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => 2,
        3 => 3
    ],
    'DEFAULT' => 3
];

$arTemplateParameters['VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_6_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'number' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_6_VIEW_NUMBER'),
        'point' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_6_VIEW_POINT'),
        'icon' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_6_VIEW_ICON')
    ],
    'DEFAULT' => 'number'
];

$arTemplateParameters['ALIGNMENT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_6_ALIGNMENT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_6_ALIGNMENT_LEFT'),
        'center' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_6_ALIGNMENT_CENTER'),
        'right' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_6_ALIGNMENT_RIGHT')
    ],
    'DEFAULT' => 'center'
];

$arTemplateParameters['NAME_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_6_NAME_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];