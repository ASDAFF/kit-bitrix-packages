<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

$arTemplateParameters = [];

$arTemplateParameters['SETTINGS_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_SETTINGS_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['LAZYLOAD_USE'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_LAZYLOAD_USE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
];

$arTemplateParameters['CONSENT_SHOW'] = [
    'PARENT' => 'BASE',
    'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_CONSENT_SHOW'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
];

$arTemplateParameters['CONSENT_URL'] = [
    'PARENT' => 'URL_TEMPLATES',
    'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_CONSENT_URL'),
    'TYPE' => 'STRING',
    'DEFAULT' => '#SITE_DIR#company/consent/'
];

$arTemplateParameters['MESSAGES_TITLE'] = [
    'PARENT' => 'ADDITIONAL_SETTINGS',
    'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_MESSAGES_TITLE'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['MESSAGES_ORDER'] = [
    'PARENT' => 'ADDITIONAL_SETTINGS',
    'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_MESSAGES_ORDER'),
    'TYPE' => 'STRING'
];

$arTemplateParameters['MESSAGES_BUTTON'] = [
    'PARENT' => 'ADDITIONAL_SETTINGS',
    'NAME' => Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_MESSAGES_BUTTON'),
    'TYPE' => 'STRING'
];