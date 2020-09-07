<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

/**
 * @var array $arCurrentValues
 * @var array $arTemplateParameters
 */

use Bitrix\Main\Localization\Loc;

$arTemplateParameters = [];
$arTemplateParameters['LOGOTYPE_SHOW'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_1_LOGOTYPE_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['LOGOTYPE_SHOW'] == 'Y') {
    $arTemplateParameters['LOGOTYPE'] = [
        'PARENT' => 'DATA_SOURCE',
        'NAME' => Loc::getMessage('C_MENU_POPUP_1_LOGOTYPE'),
        'TYPE' => 'STRING'
    ];

    $arTemplateParameters['LOGOTYPE_LINK'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_MENU_POPUP_1_LOGOTYPE_LINK'),
        'TYPE' => 'STRING'
    ];
}

$arTemplateParameters['BUTTONS_CLOSE_POSITION'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('C_MENU_POPUP_1_BUTTONS_CLOSE_POSITION'),
    'TYPE' => 'LIST',
    'DEFAULT ' => 'left',
    'VALUES' => [
        'left' => Loc::getMessage('C_MENU_POPUP_1_BUTTONS_CLOSE_POSITION_LEFT'),
        'right' => Loc::getMessage('C_MENU_POPUP_1_BUTTONS_CLOSE_POSITION_RIGHT')
    ]
];