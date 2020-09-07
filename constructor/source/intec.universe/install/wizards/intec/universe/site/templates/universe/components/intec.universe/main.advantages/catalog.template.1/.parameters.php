<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [
    'SETTINGS_USE' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_CATALOG_TEMPLATE_1_SETTINGS_USE'),
        'TYPE' => 'CHECKBOX'
    ],
    'LAZYLOAD_USE' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_CATALOG_TEMPLATE_1_LAZYLOAD_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ],
    'COLUMNS' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_CATALOG_TEMPLATE_1_COLUMNS'),
        'TYPE' => 'LIST',
        'DEFAULT' => 4,
        'VALUES' => [
            2 => 2,
            3 => 3,
            4 => 4
        ]
    ],
    'VIEW' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_CATALOG_TEMPLATE_1_VIEW'),
        'TYPE' => 'LIST',
        'DEFAULT' => 'view.1',
        'VALUES' => [
            'view.1' => Loc::getMessage('C_MAIN_ADVANTAGES_CATALOG_TEMPLATE_1_VIEW_1'),
            'view.2' => Loc::getMessage('C_MAIN_ADVANTAGES_CATALOG_TEMPLATE_1_VIEW_2'),
            'view.3' => Loc::getMessage('C_MAIN_ADVANTAGES_CATALOG_TEMPLATE_1_VIEW_3'),
        ]
    ]
];