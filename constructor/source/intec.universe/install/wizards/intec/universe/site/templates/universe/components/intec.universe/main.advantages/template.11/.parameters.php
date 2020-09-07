<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_11_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX'
];
$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_11_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['PREVIEW_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_11_PREVIEW_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];
$arTemplateParameters['VIEW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_11_VIEW'),
    'TYPE' => 'LIST',
    'VALUES' => [
        'number' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_11_VIEW_NUMBER'),
        'icon' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_11_VIEW_ICON'),
        'empty' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_11_VIEW_EMPTY')
    ],
    'DEFAULT' => 'number'
];
$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_11_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => 2,
        3 => 3
    ],
    'DEFAULT' => 3
];
$arTemplateParameters['BACKGROUND_PATH'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MAIN_ADVANTAGES_TEMPLATE_11_BACKGROUND_PATH'),
    'TYPE' => 'STRING',
    'DEFAULT' => ''
];