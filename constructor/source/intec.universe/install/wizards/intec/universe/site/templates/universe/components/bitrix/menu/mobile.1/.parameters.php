<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

$arTemplateParameters = [];
$arTemplateParameters['ADDRESS_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_MOBILE_1_ADDRESS_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['ADDRESS_SHOW'] === 'Y') {
    $arTemplateParameters['ADDRESS'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_1_ADDRESS'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['PHONE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_MOBILE_1_PHONE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['PHONE_SHOW'] === 'Y') {
    $arTemplateParameters['PHONE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_1_PHONE'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['LOGOTYPE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_MOBILE_1_LOGOTYPE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['LOGOTYPE_SHOW'] == 'Y') {
    $arTemplateParameters['LOGOTYPE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_1_LOGOTYPE'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['LOGOTYPE_LINK'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_1_LOGOTYPE_LINK'),
        'TYPE' => 'STRING'
    ];
}

if (Loader::includeModule('intec.regionality')) {
    $arTemplateParameters['REGIONALITY_USE'] = [
        'PARENT' => 'BASE',
        'NAME' => Loc::getMessage('C_MENU_MOBILE_1_REGIONALITY_USE'),
        'TYPE' => 'CHECKBOX'
    ];
}