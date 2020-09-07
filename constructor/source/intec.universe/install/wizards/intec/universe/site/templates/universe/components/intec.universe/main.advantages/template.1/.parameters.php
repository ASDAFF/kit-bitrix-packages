<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [
    'SETTINGS_USE' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_1_SETTINGS_USE'),
        'TYPE' => 'CHECKBOX'
    ],
    'LAZYLOAD_USE' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_1_LAZYLOAD_USE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ],
    'ELEMENTS_ROW_COUNT' => [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_1_ELEMENTS_ROW_COUNT'),
        'TYPE' => 'LIST',
        'DEFAULT' => 4,
        'VALUES' => [
            2 => 2,
            3 => 3,
            4 => 4
        ]
    ]
];