<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [
    'LINE_COUNT' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_2_LINE_COUNT'),
        'TYPE' => 'LIST',
        'VALUES' => [
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5
        ],
        'DEFAULT' => 4
    ]
];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_2_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_2_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'number' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_2_VIEW_NUMBER'),
        'icon' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_2_VIEW_ICON')
    ],
    'DEFAULT' => 'number'
];