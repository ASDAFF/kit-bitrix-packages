<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];
$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_8_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5
    ],
    'DEFAULT' => 5
];

$arTemplateParameters['ALIGNMENT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_8_ALIGNMENT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'left' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_8_ALIGNMENT_LEFT'),
        'center' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_8_ALIGNMENT_CENTER'),
        'right' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_8_ALIGNMENT_RIGHT')
    ],
    'DEFAULT' => 'center'
];