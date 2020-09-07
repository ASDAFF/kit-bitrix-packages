<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SALE_PERSONAL_SECTION_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arTemplateParameters['MAILING_SHOW'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SALE_PERSONAL_SECTION_MAILING_SHOW'),
    'TYPE' => 'CHECKBOX',
    'REFRESH' => 'Y'
];

if ($arCurrentValues['MAILING_SHOW'] === 'Y') {
    $arTemplateParameters['MAILING_PATH'] = [
        'PARENT' => 'URL_TEMPLATES',
        'NAME' => Loc::getMessage('C_SALE_PERSONAL_SECTION_MAILING_PATH'),
        'TYPE' => 'STRING'
    ];
}