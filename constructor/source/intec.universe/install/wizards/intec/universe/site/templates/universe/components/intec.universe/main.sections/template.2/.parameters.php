<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];
$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_TEMPLATE_2_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_TEMPLATE_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

/** VISUAL */
$arTemplateParameters['LINE_COUNT'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_TEMPLATE_2_LINE_COUNT'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => '2',
        3 => '3'
    ],
    'DEFAULT' => 3
];
$arTemplateParameters['SUB_SECTIONS_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_SECTIONS_TEMPLATE_2_SUB_SECTIONS_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['SUB_SECTIONS_SHOW'] == 'Y') {
    $arTemplateParameters['SUB_SECTIONS_MAX'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_MAIN_SECTIONS_TEMPLATE_2_SUB_SECTIONS_MAX'),
        'TYPE' => 'LIST',
        'VALUES' => [
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
        ],
        'ADDITIONAL_VALUES' => 'Y',
        'DEFAULT' => 3
    ];
}