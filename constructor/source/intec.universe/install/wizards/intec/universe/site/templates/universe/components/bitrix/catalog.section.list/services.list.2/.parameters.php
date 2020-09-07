<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!Loader::includeModule('intec.core'))
    return;

$arTemplateParameters = [];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_LIST_2_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];
$arTemplateParameters['COLUMNS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_LIST_2_COLUMNS'),
    'TYPE' => 'LIST',
    'VALUES' => [
        2 => 2,
        3 => 3
    ],
    'DEFAULT' => 2
];
$arTemplateParameters['ROUNDING_USE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_LIST_2_ROUNDING_USE'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ROUNDING_USE'] === 'Y') {
    $arTemplateParameters['ROUNDING_VALUE'] = [
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('C_BITRIX_CATALOG_SECTION_LIST_SERVICES_LIST_2_ROUNDING_VALUE'),
        'TYPE' => 'STRING',
        'DEFAULT' => 100
    ];
}